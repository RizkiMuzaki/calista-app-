<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class BookController extends Controller
{
    /**
     * Menampilkan daftar buku membaca
     */
    public function index()
    {
        // build books query, apply is_active filter only if column exists
        $booksQuery = Book::with(['module', 'pageBooks' => function($q) {
            if (Schema::hasColumn('page_books', 'is_active')) {
                $q->where('is_active', true);
            }
            $q->orderBy('page_number');
        }])->orderBy('order');
        if (Schema::hasColumn('books', 'is_active')) {
            $booksQuery->where('is_active', true);
        }
        $books = $booksQuery->get();

        // if books table lacks is_active, mark books as active so UI behaves consistently
        if (!Schema::hasColumn('books', 'is_active')) {
            foreach ($books as $b) {
                $b->is_active = true;
            }
        }

        // build modules query, apply is_active filter only when column exists
        $modulesQuery = Module::query();
        if (Schema::hasColumn('modules', 'is_active')) {
            $modulesQuery->where('is_active', true);
        }
        $modules = $modulesQuery->orderBy('name')->get();

        return view('pages.book', [
            'books' => $books,
            'modules' => $modules
        ]);
    }

    /**
     * Cek apakah user premium untuk akses buku
     * UPDATED: Hanya cek premium jika book->is_premium = 1
     */
    private function checkPremiumAccess($book = null)
    {
        // Jika book diberikan dan is_premium = 0, tidak perlu cek premium
        if ($book && Schema::hasColumn('books', 'is_premium') && !$book->is_premium) {
            return true; // Akses gratis
        }

        $user = auth()->user();
        
        if (!$user) {
            return false;
        }

        $activeSubscription = $user->subscriptions()
            ->where('status', 'aktif')
            ->where('tanggal_mulai', '<=', Carbon::now())
            ->where('tanggal_berakhir', '>=', Carbon::now())
            ->first();

        return $activeSubscription ? true : false;
    }

    /**
     * Menampilkan halaman buku berdasarkan module
     */
    public function byModule($moduleSlug)
    {
        $module = Module::where('slug', $moduleSlug)->firstOrFail();

        $booksQuery = Book::with(['module', 'pageBooks' => function($q) {
            if (Schema::hasColumn('page_books', 'is_active')) {
                $q->where('is_active', true);
            }
            $q->orderBy('page_number');
        }])
            ->where('module_id', $module->id)
            ->orderBy('order');

        if (Schema::hasColumn('books', 'is_active')) {
            $booksQuery->where('is_active', true);
        }

        $books = $booksQuery->get();

        if (!Schema::hasColumn('books', 'is_active')) {
            foreach ($books as $b) {
                $b->is_active = true;
            }
        }

        $modulesQuery = Module::query();
        if (Schema::hasColumn('modules', 'is_active')) {
            $modulesQuery->where('is_active', true);
        }
        $modules = $modulesQuery->orderBy('name')->get();

        return view('pages.book', [
            'books' => $books,
            'module' => $module,
            'modules' => $modules
        ]);
    }

    /**
     * Menampilkan detail buku dan halamannya
     */
    public function show($slug)
    {
        $book = Book::where('slug', $slug)
            ->with(['module', 'pageBooks' => function($query) {
                if (Schema::hasColumn('page_books', 'is_active')) {
                    $query->where('is_active', true);
                }
                $query->orderBy('page_number');
            }])
            ->firstOrFail();

        // UPDATED: Cek premium hanya jika is_premium = 1
        if (!$this->checkPremiumAccess($book)) {
            return redirect()->route('premium.show');
        }

        // TAMBAHKAN: Redirect jika type cerita ke halaman detail cerita
        if ($book->type === 'cerita') {
            return redirect()->route('cerita.show', $slug);
        }

        // only check is_active if the column exists
        if (Schema::hasColumn('books', 'is_active') && !$book->is_active) {
            $modulesQuery = Module::query();
            if (Schema::hasColumn('modules', 'is_active')) {
                $modulesQuery->where('is_active', true);
            }
            $modules = $modulesQuery->orderBy('name')->get();

            return view('pages.book-coming-soon', [
                'book' => $book,
                'modules' => $modules
            ]);
        }

        // if no is_active column exists, treat book as active for view logic
        if (!Schema::hasColumn('books', 'is_active')) {
            $book->is_active = true;
        }

        $modulesQuery = Module::query();
        if (Schema::hasColumn('modules', 'is_active')) {
            $modulesQuery->where('is_active', true);
        }
        $modules = $modulesQuery->orderBy('name')->get();

        // --- NEW: determine current page and page number to avoid undefined variable in blade ---
        $pageNumber = 1;
        $currentPage = $book->pageBooks->where('page_number', $pageNumber)->first();
        if (!$currentPage) {
            $currentPage = $book->pageBooks->first();
        }
        // If still null (no pages), provide a minimal placeholder object to prevent blade errors
        if (!$currentPage) {
            $currentPage = (object) [
                'id' => 0,
                'nama_benda' => '',
                'image_path' => null,
                'audio_path' => null,
                'explanation' => null,
                'audio_kata' => null
            ];
            $pageNumber = 0;
        }

        // ensure both relation names are available for views that reference $book->pages
        $book->setRelation('pages', $book->pageBooks ?: collect());

        return view('pages.book-detail', [
            'book' => $book,
            'modules' => $modules,
            'currentPage' => $currentPage,
            'pageNumber' => $pageNumber
        ]);
    }

    /**
     * Menampilkan halaman buku
     */
    public function read($slug, $pageNumber = 1)
    {
        $book = Book::where('slug', $slug)
            ->with(['module', 'pageBooks' => function($query) {
                if (Schema::hasColumn('page_books', 'is_active')) {
                    $query->where('is_active', true);
                }
                $query->orderBy('page_number');
            }])
            ->firstOrFail();

        // UPDATED: Cek premium hanya jika is_premium = 1
        if (!$this->checkPremiumAccess($book)) {
            return redirect()->route('premium.show');
        }

        // TAMBAHKAN: Redirect jika type cerita
        if ($book->type === 'cerita') {
            return redirect()->route('cerita.show', $slug);
        }

        if (Schema::hasColumn('books', 'is_active') && !$book->is_active) {
            $modulesQuery = Module::query();
            if (Schema::hasColumn('modules', 'is_active')) {
                $modulesQuery->where('is_active', true);
            }
            $modules = $modulesQuery->orderBy('name')->get();

            return view('pages.book-coming-soon', [
                'book' => $book,
                'modules' => $modules
            ]);
        }

        if (!Schema::hasColumn('books', 'is_active')) {
            $book->is_active = true;
        }

        // ensure pages relation exists so views using $book->pages won't error
        $book->setRelation('pages', $book->pageBooks ?: collect());

        $page = $book->pageBooks->where('page_number', $pageNumber)->first();
        if (!$page) {
            $page = $book->pageBooks->first();
        }

        $modulesQuery = Module::query();
        if (Schema::hasColumn('modules', 'is_active')) {
            $modulesQuery->where('is_active', true);
        }
        $modules = $modulesQuery->orderBy('name')->get();

        return view('pages.book-read', [
            'book' => $book,
            'page' => $page,
            'modules' => $modules
        ]);
    }

    /**
     * TAMBAHKAN: Menampilkan detail cerita rakyat dengan animasi
     */
    public function showCerita($slug)
    {
        $book = Book::where('slug', $slug)
            ->with(['module', 'storyPages.images', 'storyPages.choices'])
            ->firstOrFail();

        // UPDATED: Cek premium hanya jika is_premium = 1
        if (!$this->checkPremiumAccess($book)) {
            return redirect()->route('premium.show');
        }

        // Validasi: Pastikan buku ini bertipe cerita
        if ($book->type !== 'cerita') {
            return redirect()->route('buku-membaca.show', $slug);
        }

        $modulesQuery = Module::query();
        if (Schema::hasColumn('modules', 'is_active')) {
            $modulesQuery->where('is_active', true);
        }
        $modules = $modulesQuery->orderBy('name')->get();

        // Ambil semua halaman cerita
        $storyPages = $book->storyPages()
            ->where('is_active', true)
            ->orderBy('page_number')
            ->get();

        // Calculate total images across all pages
        $totalImages = $storyPages->sum(function($page) {
            return $page->images->count();
        });

        return view('pages.detailceritarakyat', [
            'book' => $book,
            'modules' => $modules,
            'storyPages' => $storyPages,
            'totalImages' => $totalImages
        ]);
    }

    /**
     * TAMBAHKAN: Menampilkan halaman cerita tertentu
     */
    public function readCerita($slug, $pageNumber = 1)
    {
        $book = Book::where('slug', $slug)
            ->with(['module', 'storyPages.images', 'storyPages.choices'])
            ->firstOrFail();

        // UPDATED: Cek premium hanya jika is_premium = 1
        if (!$this->checkPremiumAccess($book)) {
            return redirect()->route('premium.show');
        }

        if ($book->type !== 'cerita') {
            return redirect()->route('buku-membaca.show', $slug);
        }

        $modulesQuery = Module::query();
        if (Schema::hasColumn('modules', 'is_active')) {
            $modulesQuery->where('is_active', true);
        }
        $modules = $modulesQuery->orderBy('name')->get();

        // Ambil halaman saat ini
        $currentPage = $book->storyPages()
            ->where('page_number', $pageNumber)
            ->where('is_active', true)
            ->with('images', 'choices')
            ->first();

        if (!$currentPage) {
            // Jika halaman tidak ditemukan, redirect ke halaman pertama
            $firstPage = $book->storyPages()
                ->where('is_active', true)
                ->orderBy('page_number')
                ->first();
            
            if ($firstPage) {
                return redirect()->route('cerita.read', [$slug, $firstPage->page_number]);
            }
            
            abort(404, 'Halaman cerita tidak ditemukan');
        }

        // Ambil total halaman
        $totalPages = $book->storyPages()->where('is_active', true)->count();

        return view('pages.cerita-read', [
            'book' => $book,
            'modules' => $modules,
            'currentPage' => $currentPage,
            'pageNumber' => $pageNumber,
            'totalPages' => $totalPages
        ]);
    }

    /**
     * TAMBAHKAN: Menampilkan cerita interaktif dengan animasi
     */
    public function showInteractiveStory($slug)
    {
        $book = Book::where('slug', $slug)
            ->with(['module', 'storyPages' => function($query) {
                $query->where('is_active', true)
                    ->orderBy('page_number')
                    ->with(['images' => function($q) {
                        $q->where('is_active', true)
                          ->orderBy('order');
                    }, 'choices' => function($q) {
                        $q->orderBy('id');
                    }]);
            }])
            ->firstOrFail();

        // UPDATED: Cek premium hanya jika is_premium = 1
        if (!$this->checkPremiumAccess($book)) {
            return redirect()->route('premium.show');
        }

        // Validasi: Pastikan buku ini bertipe cerita
        if ($book->type !== 'cerita') {
            return redirect()->route('buku-membaca.show', $slug);
        }

        $modulesQuery = Module::query();
        if (Schema::hasColumn('modules', 'is_active')) {
            $modulesQuery->where('is_active', true);
        }
        $modules = $modulesQuery->orderBy('name')->get();

        // Debug: Log data images untuk setiap halaman
        foreach ($book->storyPages as $page) {
            \Log::info("Page {$page->page_number} - Images count: " . $page->images->count());
            foreach ($page->images as $image) {
                \Log::info("  Image: " . $image->image_path . " | Type: " . $image->type);
            }
        }

        // Calculate total images across all pages
        $totalImages = $book->storyPages->sum(function($page) {
            return $page->images->count();
        });

        return view('pages.detailceritarakyat', [
            'book' => $book,
            'modules' => $modules,
            'storyPages' => $book->storyPages,
            'totalImages' => $totalImages
        ]);
    }
}