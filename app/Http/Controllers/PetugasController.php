<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Tool;
use Illuminate\Http\Request;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class PetugasController extends Controller
{
    // 1. Tampilkan Dashboard Petugas (Daftar Peminjaman)
    public function index()
    {
        // Ambil data yang statusnya 'pending' (Menunggu Persetujuan)
        $pendings = Loan::with(['user', 'tool'])->where('status', 'pending')->get();
        
        // Ambil data yang statusnya 'approved' (Sedang Dipinjam)
        $actives = Loan::with(['user', 'tool'])->where('status', 'approved')->get();
        
        // Ambil semua data untuk riwayat/laporan
        $history = Loan::with(['user', 'tool'])->whereIn('status', ['returned', 'rejected'])->get();

        return view('petugas.dashboard', compact('pendings', 'actives', 'history'));
    }

    // 2. Aksi: Setujui Peminjaman
    public function approve($id)
    {
        $loan = Loan::findOrFail($id);
        $loan->update(['status' => 'approved']);
        
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Setujui Peminjaman',
            'description' => 'Menyetujui peminjaman ' . $loan->tool->name . ' oleh ' . $loan->user->name,
        ]);

        return back()->with('success', 'Peminjaman disetujui! Barang boleh diambil.');
    }

    // 3. Aksi: Tolak Peminjaman
    public function reject($id)
    {
        $loan = Loan::findOrFail($id);
        
        // Kembalikan stok alat karena batal pinjam
        $tool = Tool::findOrFail($loan->tool_id);
        $tool->increment('stock');

        $loan->update(['status' => 'rejected']);
        
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Tolak Peminjaman',
            'description' => 'Menolak peminjaman ' . $loan->tool->name . ' oleh ' . $loan->user->name,
        ]);

        return back()->with('success', 'Peminjaman ditolak.');
    }

    // 4. Aksi: Proses Pengembalian Barang
    public function complete($id)
    {
        $loan = Loan::findOrFail($id);
        
        // Kembalikan stok alat
        $tool = Tool::findOrFail($loan->tool_id);
        $tool->increment('stock');

        // Update status dan tanggal kembali asli
        $loan->update([
            'status' => 'returned',
            'actual_return_date' => now(),
        ]);
        
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Terima Pengembalian',
            'description' => 'Menerima pengembalian ' . $loan->tool->name . ' dari ' . $loan->user->name,
        ]);

        return back()->with('success', 'Alat berhasil dikembalikan. Stok bertambah.');
    }
}