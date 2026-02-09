<?php

namespace App\Http\Controllers;

use App\Models\Artikel;
use Illuminate\Http\Request;

class ArtikelController extends Controller
{
    // Halaman daftar semua artikel
    public function index()
    {
        $artikels = Artikel::where('is_published', true)
            ->orderBy('created_at', 'desc')
            ->paginate(6);
        
        return view('pages.artikel', compact('artikels'));
    }

    // Halaman detail artikel
    public function show($slug)
    {
        $artikel = Artikel::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();
        
        // Ambil artikel terkait (3 artikel lainnya)
        $relatedArtikels = Artikel::where('is_published', true)
            ->where('id', '!=', $artikel->id)
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();
        
        return view('pages.detail-artikel', compact('artikel', 'relatedArtikels'));
    }
}
