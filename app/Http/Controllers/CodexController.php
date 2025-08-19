<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class CodexController extends Controller
{
    /**
     * Display the codex/glossary page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Ambil semua data istilah dari file config
        $terms = config('blackfile.codex_terms', []);

        // Gunakan Collection untuk mengelompokkan istilah berdasarkan kategori
        $groupedTerms = collect($terms)->groupBy('category');

        // Kirim data yang sudah dikelompokkan ke view
        return view('codex.index', [
            'groupedTerms' => $groupedTerms
        ]);
    }
}
