<?php

namespace App\Http\Controllers;

use App\Models\Tool;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ActivityLog;

class ToolController extends Controller
{
    public function index()
    {
        // Ambil data alat beserta nama kategorinya
        $tools = Tool::with('category')->get();
        return view('admin.tools.index', compact('tools'));
    }

    public function create()
    {
        $categories = Category::all(); // Kita butuh daftar kategori untuk dipilih
        return view('admin.tools.create', compact('categories'));
    }

    public function store(Request $request)
    {
        // Validasi input (tanpa gambar)
        $request->validate([
            'name' => 'required',
            'category_id' => 'required',
            'stock' => 'required|integer',
            'description' => 'nullable'
        ]);

        // Simpan langsung
        Tool::create($request->all());

        ActivityLog::create([
        'user_id' => Auth::id(),
        'action' => 'Tambah Alat',
        'description' => 'Menambahkan alat: ' . $request->name,
    ]);

        return redirect()->route('tools.index')->with('success', 'Alat berhasil ditambahkan!');
    }

    public function edit(Tool $tool)
    {
        $categories = Category::all();
        return view('admin.tools.edit', compact('tool', 'categories'));
    }

    public function update(Request $request, Tool $tool)
    {
        $request->validate([
            'name' => 'required',
            'category_id' => 'required',
            'stock' => 'required|integer',
            'description' => 'nullable'
        ]);

        // Update data
        $tool->update($request->all());

        ActivityLog::create([
        'user_id' => Auth::id(),
        'action' => 'Update Alat',
        'description' => 'Mengupdate alat: ' . $request->name,
    ]);

        return redirect()->route('tools.index')->with('success', 'Alat berhasil diperbarui!');
    }

    public function destroy(Tool $tool)
    {
        // Simpan nama alat dulu sebelum dihapus untuk catatan log
        $namaAlat = $tool->name;

        // Hapus alat
        $tool->delete();

        // Catat di Log (Gunakan variabel $namaAlat, BUKAN $request->name)
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Hapus Alat',
            'description' => 'Menghapus alat: ' . $namaAlat,
        ]);

        return redirect()->route('tools.index')->with('success', 'Alat berhasil dihapus!');
    }
}