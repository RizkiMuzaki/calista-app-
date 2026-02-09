<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Module;
use App\Models\Level;

class ModuleController extends Controller
{
    /**
     * Display a listing of the modules.
     */
    public function index()
    {
        $modules = Module::orderBy('id')->get();
        
        // Data untuk halaman permainan (karena hanya ada 1 view)
        return view('pages.permainan', [
            'modules' => $modules,
            'title' => 'Calista - Belajar Budaya Indonesia'
        ]);
    }

    /**
     * Display the specified module.
     */
    public function show($slug)
    {
        $module = Module::where('slug', $slug)->firstOrFail();
        
        // Ambil semua level untuk module ini
        $levels = Level::where('module_id', $module->id)
                      ->orderBy('order_number')
                      ->get();
        
        return view('pages.module-detail', [
            'module' => $module,
            'levels' => $levels, // INI YANG PERLU DITAMBAHKAN
            'title' => $module->name . ' - Calista'
        ]);
    }
}