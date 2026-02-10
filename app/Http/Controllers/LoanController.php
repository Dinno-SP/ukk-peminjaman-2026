<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Tool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ActivityLog;

class LoanController extends Controller
{
    // Fungsi untuk memproses pengajuan pinjaman
    public function store(Request $request)
    {
        $request->validate([
            'tool_id' => 'required|exists:tools,id',
            'return_date' => 'required|date|after:today', // Tanggal kembali harus setelah hari ini
        ]);

        $tool = Tool::findOrFail($request->tool_id);

        // Cek stok dulu
        if ($tool->stock < 1) {
            return back()->with('error', 'Stok alat habis!');
        }

        // 1. Kurangi Stok Alat (Supaya tidak dipinjam orang lain)
        $tool->decrement('stock');

        // 2. Buat Data Peminjaman
        Loan::create([
            'user_id' => Auth::id(),
            'tool_id' => $tool->id,
            'loan_date' => now(), // Tanggal pinjam hari ini
            'return_date' => $request->return_date,
            'status' => 'pending', // Status awal Pending
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Ajukan Peminjaman',
            'description' => 'Mengajukan pinjam alat: ' . $tool->name,
        ]);

        return back()->with('success', 'Pengajuan berhasil! Menunggu persetujuan admin.');
    }

    // Fungsi untuk User mengklik tombol "Kembalikan"
    public function returnTool($id)
    {
        $loan = Loan::findOrFail($id);

        // Pastikan hanya peminjam yang bersangkutan yang bisa akses
        if ($loan->user_id != Auth::id()) {
            abort(403);
        }

        // Ubah status menjadi 'returning' (Proses Pengembalian)
        $loan->update([
            'status' => 'returning',
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Ajukan Pengembalian',
            'description' => 'User mengajukan pengembalian alat: ' . $loan->tool->name,
        ]);

        return back()->with('success', 'Permintaan pengembalian dikirim. Silakan serahkan alat ke Petugas.');
    }
}