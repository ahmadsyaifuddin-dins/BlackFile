<?php

namespace App\Http\Controllers;

use App\Models\DefaultMusic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class DefaultMusicController extends Controller
{
    public function index()
    {
        if (auth()->user()->role->name !== 'Director') {
            abort(403);
        }
        $musics = DefaultMusic::all();
        return view('credits.music_management', compact('musics'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'music_file' => 'required|file|mimes:mp3,m4a,wav,ogg|max:8192',
        ]);

        $file = $request->file('music_file');
        $fileName = Str::slug($request->name) . '.' . $file->getClientOriginalExtension();
        $path = 'music/default/' . $fileName;

        $file->move(public_path('music/default'), $fileName);

        DefaultMusic::create([
            'name' => $request->name,
            'path' => $path,
        ]);

        return back()->with('success', 'Default music added.');
    }

    public function destroy(DefaultMusic $defaultMusic)
    {
        File::delete(public_path($defaultMusic->path));
        $defaultMusic->delete();
        return back()->with('success', 'Default music removed.');
    }
}
