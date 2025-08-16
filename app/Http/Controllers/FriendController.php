<?php

namespace App\Http\Controllers;

use App\Models\Friend;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FriendController extends Controller
{
    /**
     * Menampilkan halaman utama manajemen teman, yaitu Network Analysis Graph.
     * Logika ini sebelumnya ada di centralTreeGraph().
     */
    public function index()
    {
        $rootUser = auth()->user();

        // Mengambil semua teman yang terhubung dengan user ini, beserta semua turunannya.
        // Pastikan relasi 'childrenRecursive' sudah didefinisikan di model Friend.
        $allFriends = Friend::with('childrenRecursive')
            ->where('user_id', $rootUser->id)
            ->whereNull('parent_id') // Mulai dari teman-teman level atas
            ->get();

        $nodes = [];
        $edges = [];
        $addedNodeIds = []; // Untuk melacak node yang sudah ditambahkan

        // Tambahkan node untuk user yang sedang login (root dari graph)
        $rootUserId = 'u' . $rootUser->id;
        $nodes[] = [
            'data' => [
                'id'     => $rootUserId,
                'label'  => $rootUser->codename,
                'role'   => $rootUser->role->alias
            ]
        ];
        $addedNodeIds[] = $rootUserId;

        // Fungsi rekursif untuk memproses setiap teman dan turunannya
        $processNodeAndChildren = function ($friend, $parentId) use (&$nodes, &$edges, &$addedNodeIds, &$processNodeAndChildren) {
            $friendId = 'f' . $friend->id;

            // Hindari duplikasi node jika ada relasi yang kompleks
            if (!in_array($friendId, $addedNodeIds)) {
                $nodes[] = [
                    'data' => [
                        'id'     => $friendId,
                        'label'  => $friend->codename,
                        'role'   => 'Friend' // Semua yang ada di tabel friends kita beri role Friend
                    ]
                ];
                $addedNodeIds[] = $friendId;
            }

            // Tambahkan garis (edge) dari parent ke teman ini
            $edges[] = [
                'data' => [
                    'source' => $parentId,
                    'target' => $friendId
                ]
            ];

            // Lakukan proses yang sama untuk semua anak dari teman ini
            foreach ($friend->childrenRecursive as $child) {
                $processNodeAndChildren($child, $friendId);
            }
        };

        // Mulai proses dari teman-teman level atas
        foreach ($allFriends as $friend) {
            $processNodeAndChildren($friend, $rootUserId);
        }

        // Filter edge duplikat untuk memastikan graph bersih
        $uniqueEdges = collect($edges)->unique(function ($edge) {
            return $edge['data']['source'] . '-' . $edge['data']['target'];
        })->values()->all();

        return view('friends.index', [
            'nodes' => $nodes,
            'edges' => $uniqueEdges
        ]);
    }

    // Form tambah teman
    public function create()
    {
        // Ambil semua teman yang ada untuk dijadikan opsi 'parent'
        $parentOptions = Friend::where('user_id', Auth::id())->orderBy('codename')->get();
        return view('friends.create', compact('parentOptions'));
    }

    // Simpan teman baru
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'      => 'required|string|max:255',
            'codename'  => 'required|string|max:50|unique:friends,codename',
            'parent_id' => 'nullable|exists:friends,id',
        ]);

        // Cegah loop
        if ($this->createsLoop($data['parent_id'], $data['codename'])) {
            return back()->withErrors(['codename' => 'Loop terdeteksi: teman ini sudah ada di jalur pohon.']);
        }

        Friend::create([
            'user_id'   => Auth::id(),
            'name'      => $data['name'],
            'codename'  => $data['codename'],
            'parent_id' => $data['parent_id'] ?? null,
        ]);

        return redirect()->route('friends.index')->with('success', 'Teman berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit asset/teman.
     */
    public function edit(Friend $friend)
    {
        // Otorisasi
        if ($friend->user_id !== auth()->id()) {
            abort(403, 'UNAUTHORIZED ACTION');
        }
        
        // Ambil semua teman KECUALI diri sendiri dan turunannya untuk opsi 'parent'
        // Ini untuk mencegah circular dependency (membuat diri sendiri menjadi anak dari turunannya)
        $excludeIds = $friend->getAllChildrenIds();
        $excludeIds[] = $friend->id;

        $parentOptions = Friend::where('user_id', Auth::id())
                                ->whereNotIn('id', $excludeIds)
                                ->orderBy('codename')
                                ->get();
        
        return view('friends.edit', compact('friend', 'parentOptions'));
    }

    public function update(Request $request, Friend $friend)
    {
        // 1. Otorisasi: Pastikan user hanya bisa mengupdate teman miliknya sendiri
        if ($friend->user_id !== auth()->id()) {
            abort(403, 'UNAUTHORIZED ACTION');
        }

        // 2. Validasi: Sama seperti store, tapi izinkan codename yang sama untuk record ini
        $data = $request->validate([
            'name'      => 'required|string|max:255',
            'codename'  => 'required|string|max:50|unique:friends,codename,' . $friend->id,
            'parent_id' => 'nullable|exists:friends,id',
        ]);

        // 3. Update data
        $friend->update([
            'name'      => $data['name'],
            'codename'  => $data['codename'],
            'parent_id' => $data['parent_id'] ?? null,
        ]);

        // 4. Redirect kembali dengan pesan sukses
        return redirect()->route('friends.index')->with('success', "Dossier for asset '{$friend->codename}' has been updated.");
    }

    /**
     * Menghapus asset/teman dari database.
     */
    public function destroy(Friend $friend)
    {
        // Pastikan user hanya bisa menghapus teman miliknya sendiri
        if ($friend->user_id !== auth()->id()) {
            abort(403, 'UNAUTHORIZED ACTION');
        }

        $codename = $friend->codename;
        $friend->delete();

        return redirect()->route('friends.index')->with('success', "Asset '{$codename}' has been terminated.");
    }


    // Fungsi rekursif untuk deteksi loop
    private function createsLoop($parentId, $codename)
    {
        if (!$parentId) return false;

        $parent = Friend::find($parentId);

        if (!$parent) return false;

        // Kalau codename di parent sama dengan yang mau ditambahkan → loop
        if ($parent->codename === $codename) return true;

        // Rekursif ke atas
        return $this->createsLoop($parent->parent_id, $codename);
    }

    // Lihat tree dari teman tertentu (friends of friend)
    public function showTree($id)
    {
        $root = Friend::where('user_id', Auth::id())
            ->where('id', $id)
            ->with('children')
            ->firstOrFail();

        return view('friends.tree', compact('root'));
    }

    public function centralTreeGraph()
    {
        $rootUser = auth()->user();

        // Ambil semua teman termasuk rekursif
        $allFriends = \App\Models\Friend::with('childrenRecursive')
            ->where('user_id', $rootUser->id)
            ->get();

        $nodes = [];
        $edges = [];

        // Tambahkan root
        $nodes[] = [
            'data' => [
                'id' => 'u' . $rootUser->id,
                'label' => $rootUser->codename,
                'role' => $rootUser->role->alias
            ]
        ];

        // Fungsi rekursif
        $addFriendNode = function ($friend, $parentId) use (&$nodes, &$edges, &$addFriendNode) {
            $friendId = 'f' . $friend->id;

            // Hindari node duplikat
            if (!collect($nodes)->contains(fn($n) => $n['data']['id'] === $friendId)) {
                $nodes[] = [
                    'data' => [
                        'id' => $friendId,
                        'label' => $friend->codename ?? $friend->name,
                        'role' => 'Friend'
                    ]
                ];
            }

            // Tambahkan edge
            $edges[] = [
                'data' => [
                    'source' => $parentId,
                    'target' => $friendId
                ]
            ];

            // Rekursif ke child
            foreach ($friend->childrenRecursive as $child) {
                $addFriendNode($child, $friendId);
            }
        };

        // Mulai dari semua teman root
        foreach ($allFriends as $friend) {
            $addFriendNode($friend, 'u' . $rootUser->id);
        }

        // 🔹 Filter supaya tidak ada edge duplikat
        $uniqueEdges = collect($edges)->unique(function ($edge) {
            return $edge['data']['source'] . '-' . $edge['data']['target'];
        })->values();

        return view('friends.central-tree-graph', [
            'nodes' => $nodes,
            'edges' => $uniqueEdges
        ]);
    }

}
