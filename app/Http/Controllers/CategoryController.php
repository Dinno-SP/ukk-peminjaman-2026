<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\ActivityLog; 
use Illuminate\Support\Facades\Auth;


class CategoryController extends Controller
{
    // Menampilkan semua kategori
    public function index()
    {
        $categories = Category::all();
        return view('admin.categories.index', compact('categories'));
    }

    // Menampilkan form tambah kategori
    public function create()
    {
        return view('admin.categories.create');
    }

    // Menyimpan kategori baru ke database
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Category::create($request->all());

        ActivityLog::create([
        'user_id' => Auth::id(),
        'action' => 'Tambah Kategori',
        'description' => 'Menambahkan kategori: ' . $request->name,
    ]);

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil ditambahkan!');
    }

    // Menampilkan form edit kategori
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    // Menyimpan perubahan kategori
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category->update($request->all());

        ActivityLog::create([
        'user_id' => Auth::id(),
        'action' => 'Update Kategori',
        'description' => 'Menambahkan kategori: ' . $request->name,
    ]);

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil diperbarui!');
    }

    // Menghapus kategori
    public function destroy(Category $category)
    {
        // 1. Simpan nama kategori dulu sebelum dihapus (untuk catatan log)
        $namaKategori = $category->name;
    
        // 2. Hapus kategori
        $category->delete();

        // 3. Catat di Log (Gunakan variabel $namaKategori, JANGAN $request)
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Hapus Kategori',   // <-- Ubah jadi Hapus
            'description' => 'Menghapus kategori: ' . $namaKategori, // <-- Gunakan variabel nama tadi
        ]);

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil dihapus!');
    }
}