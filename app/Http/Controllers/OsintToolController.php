<?php

namespace App\Http\Controllers;
class OsintToolController extends Controller
{
    public function index()
    {
        return view('tools.index');
    }

    public function commentAnalyzer()
    {
        return view('tools.comments.index');
    }
    public function exifExtractor()
    {
        return view('tools.exif.index');
    }
}
