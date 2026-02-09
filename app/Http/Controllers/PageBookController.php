<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\PageBook;
use Illuminate\Support\Facades\Storage;

class PageBookController extends Controller
{
    public function show($slug, $pageNumber = 1)
    {
        $book = Book::where('slug', $slug)
            ->with(['pages' => function($query) {
                $query->where('is_active', true)
                    ->orderBy('page_number');
            }])
            ->firstOrFail();

        $currentPage = $book->pages->where('page_number', $pageNumber)->first();
        
        if (!$currentPage) {
            abort(404);
        }

        return view('pages.book-detail', compact('book', 'currentPage', 'pageNumber'));
    }

    public function playAudioByLetters(Request $request, $slug, $pageId)
    {
        $page = PageBook::with('book')->findOrFail($pageId);
        $namaBenda = strtoupper(trim($page->nama_benda));
        
        // Filter hanya huruf A-Z
        $letters = preg_replace('/[^A-Z]/', '', $namaBenda);
        
        $audioFiles = [];
        
        // Cari file audio untuk setiap huruf
        foreach (str_split($letters) as $letter) {
            $audioPath = "public/page_books/huruf/{$letter}.mp3";
            if (Storage::exists($audioPath)) {
                $audioFiles[] = [
                    'letter' => $letter,
                    'url' => Storage::url("page_books/huruf/{$letter}.mp3")
                ];
            } else {
                // Fallback ke TTS jika file tidak ditemukan
                $audioFiles[] = [
                    'letter' => $letter,
                    'url' => route('typecast.custom'),
                    'text' => $letter,
                    'is_tts' => true
                ];
            }
        }
        
        // Tambahkan audio kata
        if ($page->audio_kata && Storage::exists($page->audio_kata)) {
            $audioFiles[] = [
                'type' => 'kata',
                'url' => Storage::url($page->audio_kata)
            ];
        }
        
        // Tambahkan audio path jika ada
        if ($page->audio_path && Storage::exists($page->audio_path)) {
            $audioFiles[] = [
                'type' => 'deskripsi',
                'url' => Storage::url($page->audio_path)
            ];
        }
        
        return response()->json([
            'success' => true,
            'letters' => $audioFiles,
            'word' => $page->nama_benda
        ]);
    }

    public function nextPage($slug, $currentPage)
    {
        $book = Book::where('slug', $slug)->firstOrFail();
        $nextPage = $book->pages()
            ->where('is_active', true)
            ->where('page_number', '>', $currentPage)
            ->orderBy('page_number')
            ->first();
            
        if ($nextPage) {
            return redirect()->route('book-page.show', ['slug' => $slug, 'pageNumber' => $nextPage->page_number]);
        }
        
        return redirect()->route('buku-membaca.show', ['slug' => $slug])
            ->with('info', 'Ini adalah halaman terakhir');
    }

    public function prevPage($slug, $currentPage)
    {
        $book = Book::where('slug', $slug)->firstOrFail();
        $prevPage = $book->pages()
            ->where('is_active', true)
            ->where('page_number', '<', $currentPage)
            ->orderBy('page_number', 'desc')
            ->first();
            
        if ($prevPage) {
            return redirect()->route('book-page.show', ['slug' => $slug, 'pageNumber' => $prevPage->page_number]);
        }
        
        return back()->with('info', 'Ini adalah halaman pertama');
    }
}