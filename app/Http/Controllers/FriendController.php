<?php

namespace App\Http\Controllers;

use App\Models\Connection;
use App\Models\Friend;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FriendController extends Controller
{
    /**
     * [DIPERBAIKI] Menampilkan Network Analysis Graph HANYA untuk koneksi langsung
     * dari user yang sedang login.
     */
    public function index()
    {
        $currentUser = Auth::user();

        $nodes = [];
        $edges = [];

        // 1. Tambahkan user yang login sebagai node utama (root)
        $rootNodeId = 'u' . $currentUser->id;
        $nodes[] = [
            'data' => [
                'id'     => $rootNodeId,
                'label'  => $currentUser->codename,
                'role'   => $currentUser->role->alias,
                'name'   => $currentUser->name,
                'avatar' => $currentUser->avatar ? asset($currentUser->avatar) : route('avatar.proxy', ['name' => $currentUser->codename]),
                'specialization' => $currentUser->specialization,
            ]
        ];

        // 2. Ambil HANYA koneksi langsung dari user ini
        $directConnections = Connection::where('source_type', User::class)
            ->where('source_id', $currentUser->id)
            ->with('target')
            ->get();

        // 3. Proses setiap koneksi langsung
        foreach ($directConnections as $connection) {
            $target = $connection->target;
            if ($target) {
                $isUser = $target instanceof User;
                $targetId = ($isUser ? 'u' : 'f') . $target->id;

                // Tambahkan target sebagai node
                $nodes[] = [
                    'data' => [
                        'id'     => $targetId,
                        'label'  => $target->codename,
                        'name'   => $target->name,
                        'role'   => $isUser ? $target->role->alias : 'Asset',
                        'avatar' => $isUser ? ($target->avatar ? asset($target->avatar) : route('avatar.proxy', ['name' => $target->codename])) : null,
                        'category' => !$isUser ? $target->category : null,
                        'specialization' => $isUser ? $target->specialization : null,
                    ]
                ];

                // Tambahkan garis (edge) dari root ke target
                $edges[] = [
                    'data' => [
                        'source' => $rootNodeId,
                        'target' => $targetId
                    ]
                ];
            }
        }

        return view('friends.index', [
            'pageTitle' => 'My Network',
            'rootNodeId' => $rootNodeId,
            'nodes' => $nodes,
            'edges' => $edges
        ]);
    }


    /**
     * Menampilkan Network Analysis Graph untuk jaringan UTAMA milik Director.
     * Method ini tidak berubah dan tetap menggunakan helper rekursif.
     */
    public function centralTreeGraph()
    {
        $director = User::whereHas('role', function ($query) {
            $query->where('name', 'Director');
        })->firstOrFail();

        $graphData = $this->buildGraphData($director);

        $graphData['pageTitle'] = 'Central Command Network';
        $graphData['rootNodeId'] = 'u' . $director->id;
        
        return view('friends.index', $graphData);
    }


     /**
     * Helper private untuk membangun data graph REKURSIF (hanya untuk Central Tree).
     */
    private function buildGraphData(Model $startEntity): array
    {
        $nodes = [];
        $edges = [];
        $processedIds = [];

        $traverse = function (Model $entity) use (&$nodes, &$edges, &$processedIds, &$traverse) {
            $isUser = $entity instanceof User;
            $entityType = $isUser ? 'u' : 'f';
            $entityId = $entityType . $entity->id;

            if (in_array($entityId, $processedIds)) return;
            
            $nodes[$entityId] = [
                'data' => [
                    'id'     => $entityId,
                    'label'  => $entity->codename,
                    'name'   => $entity->name,
                    'role'   => $isUser ? $entity->role->alias : 'Asset',
                    'avatar' => $isUser ? ($entity->avatar ? asset($entity->avatar) : route('avatar.proxy', ['name' => $entity->codename])) : null,
                    'category'       => !$isUser ? $entity->category : null,
                    'specialization' => $isUser ? $entity->specialization : null,
                ]
            ];
            $processedIds[] = $entityId;

            $connections = Connection::where('source_type', get_class($entity))
                ->where('source_id', $entity->id)
                ->with('target')
                ->get();

            foreach ($connections as $connection) {
                if ($target = $connection->target) {
                    $targetIsUser = $target instanceof User;
                    $targetType = $targetIsUser ? 'u' : 'f';
                    $targetId = $targetType . $target->id;

                    $edges[] = ['data' => ['source' => $entityId, 'target' => $targetId]];
                    $traverse($target);
                }
            }
        };

        $traverse($startEntity);

        return [
            'nodes' => array_values($nodes),
            'edges' => $edges
        ];
    }

    /**
     * Menampilkan form untuk membuat koneksi baru.
     */
    public function create()
    {
        // Ambil semua entitas yang bisa dihubungkan
        $connectableUsers = User::where('id', '!=', Auth::id())->orderBy('codename')->get();
        $connectableFriends = Friend::orderBy('codename')->get();

        // [BARU] Ambil daftar kategori dari file config
        $categories = config('blackfile.asset_categories');

        return view('friends.create', compact('connectableUsers', 'connectableFriends', 'categories'));
    }

    /**
     * Menyimpan koneksi baru.
     */
    public function store(Request $request)
    {
        // 1. Validasi (tetap sama)
        $request->validate([
            'name' => 'nullable|required_without:target_entity|string|max:255',
            'codename' => 'nullable|required_without:target_entity|string|max:50|unique:friends,codename',
            'target_entity' => 'nullable|required_without_all:name,codename|string',
            'category' => 'nullable|required_without:target_entity|string', // [BARU] Tambahkan validasi
        ], [
            'required_without' => 'The :attribute field is required when creating a new asset.',
            'required_without_all' => 'Please either register a new asset or select an existing entity to connect to.'
        ]);

        $source = Auth::user();
        $targetEntity = null;

        // 2. Tentukan Target (tetap sama)
        if ($request->filled('target_entity')) {
            [$type, $id] = explode('-', $request->target_entity);
            $modelClass = $type === 'user' ? User::class : Friend::class;
            $targetEntity = $modelClass::findOrFail($id);
        } elseif ($request->filled('name') && $request->filled('codename')) {
            $targetEntity = Friend::create([
                'user_id' => $source->id,
                'name' => $request->name,
                'codename' => $request->codename,
                'category' => $request->category, // [BARU] Simpan kategori
            ]);
        }

        // 3. Proses Koneksi (dengan logika baru)
        if ($targetEntity) {
            // [LOGIKA BARU] Tentukan tipe relasi secara dinamis
            $relationshipType = $targetEntity instanceof Friend ? 'asset' : 'operative';

            // Validasi koneksi duplikat & terbalik (tetap sama)
            $connectionExists = Connection::where('source_type', get_class($source))
                ->where('source_id', $source->id)
                ->where('target_type', get_class($targetEntity))
                ->where('target_id', $targetEntity->id)
                ->exists();

            if ($connectionExists) {
                return back()->withErrors(['target_entity' => 'This connection has already been established.'])->withInput();
            }

            // Buat koneksi dengan tipe yang sudah dinamis
            Connection::create([
                'source_id' => $source->id,
                'source_type' => get_class($source),
                'target_id' => $targetEntity->id,
                'target_type' => get_class($targetEntity),
                'relationship_type' => $relationshipType, // <-- Gunakan variabel baru
            ]);

            return redirect()->route('central-tree')->with('success', 'Connection established successfully.');
        }

        return back()->withErrors(['msg' => 'Invalid operation. Please try again.']);
    }

    public function storeSubAsset(Request $request)
    {
        $data = $request->validate([
            'source_type' => 'required|string',
            'source_id'   => 'required|integer',
            'name'        => 'required|string|max:255',
            'codename'    => 'required|string|max:50|unique:friends,codename',
            'category'    => 'nullable|string',
        ]);

        // Cari source dari koneksi
        $source = ($data['source_type'])::find($data['source_id']);
        if (!$source) {
            return back()->withErrors(['msg' => 'Source entity not found.']);
        }

        // Otorisasi: Pastikan user yang login adalah pemilik asli dari source
        if ($source->user_id !== Auth::id()) {
            abort(403, 'UNAUTHORIZED ACTION. You are not the handler of this asset.');
        }

        // Buat target (sub-aset baru)
        $target = Friend::create([
            'user_id'  => Auth::id(), // Pemilik sub-aset tetap handler utama
            'name'     => $data['name'],
            'codename' => $data['codename'],
            'category' => $data['category'],
        ]);

        // Buat koneksi dari source ke target
        Connection::create([
            'source_id'   => $source->id,
            'source_type' => get_class($source),
            'target_id'   => $target->id,
            'target_type' => get_class($target),
        ]);

        return redirect()->route('central-tree')->with('success', 'Sub-asset connection established.');
    }

    /**
     * [DISEMPURNAKAN] Menampilkan form untuk mengedit data aset.
     */
    public function edit(Friend $friend)
    {
        // Otorisasi: Pastikan user hanya bisa mengedit teman yang dia buat
        if ($friend->user_id !== auth()->id()) {
            abort(403, 'UNAUTHORIZED ACTION - You are not the original creator of this asset.');
        }

        // Cukup kirim data teman yang akan diedit, tidak perlu 'parentOptions' lagi
        return view('friends.edit', compact('friend'));
    }

    /**
     * [DISEMPURNAKAN] Memperbarui data aset di database.
     */
    public function update(Request $request, Friend $friend)
    {
        // Otorisasi
        if ($friend->user_id !== auth()->id()) {
            abort(403, 'UNAUTHORIZED ACTION');
        }

        // Validasi: Hanya untuk name dan codename
        $data = $request->validate([
            'name'      => 'required|string|max:255',
            'codename'  => 'required|string|max:50|unique:friends,codename,' . $friend->id,
            'category'  => 'nullable|string',
        ]);

        // Update data teman
        $friend->update($data);

        return redirect()->route('central-tree')->with('success', "Agent for asset '{$friend->codename}' has been updated.");
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

        return redirect()->route('central-tree')->with('success', "Asset '{$codename}' has been terminated.");
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
}
