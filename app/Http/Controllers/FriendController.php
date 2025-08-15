<?php

namespace App\Http\Controllers;

use App\Models\Friend;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FriendController extends Controller
{
    // Daftar semua teman pusat user yang login
    public function index()
    {
        $friends = Friend::where('user_id', Auth::id())
            ->whereNull('parent_id')
            ->with('children')
            ->get();

        return view('friends.index', compact('friends'));
    }

    // Form tambah teman
    public function create()
    {
        $parentFriends = Friend::where('user_id', Auth::id())->get();
        return view('friends.create', compact('parentFriends'));
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
