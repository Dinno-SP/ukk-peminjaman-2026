<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Tool;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminLoanController extends Controller
{
    // MENAMPILKAN SEMUA DATA PEMINJAMAN (Fitur Read)
    public function index()
    {
        // Admin bisa melihat semua status (pending, approved, returned)
        $loans = Loan::with(['user', 'tool'])->latest()->get();
        return view('admin.loans.index', compact('loans'));
    }

    // FORM EDIT DATA PEMINJAMAN (Fitur Update - Misal salah input tanggal)
    public function edit($id)
    {
        $loan = Loan::findOrFail($id);
        $users = User::where('role', 'peminjam')->get();
        $tools = Tool::all();
        return view('admin.loans.edit', compact('loan', 'users', 'tools'));
    }

    // UPDATE DATA PEMINJAMAN
    public function update(Request $request, $id)
        {
            $loan = Loan::findOrFail($id);
            
            // Validasi input
            $request->validate([
                'status' => 'required|in:pending,approved,returned,rejected',
                'return_date' => 'required|date',
                // Tambahkan validasi untuk field baru
                'actual_return_date' => 'nullable|date',
                'fine' => 'nullable|numeric|min:0',
            ]);

            // Simpan Perubahan
            $loan->update([
                'status' => $request->status,
                'return_date' => $request->return_date,
                
                // Simpan data pengembalian manual dari Admin
                'actual_return_date' => $request->actual_return_date,
                'fine' => $request->fine ?? 0, // Jika kosong, set 0
            ]);

            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'Admin Edit Peminjaman',
                'description' => 'Admin mengubah data pinjam/kembali ID: ' . $loan->id,
            ]);

            return redirect()->route('admin.loans.index')->with('success', 'Data peminjaman & pengembalian berhasil diperbarui Admin.');
        }

    // HAPUS DATA PEMINJAMAN (Fitur Delete)
    public function destroy($id)
    {
        $loan = Loan::findOrFail($id);
        
        // Hati-hati! Jika menghapus data yang statusnya 'approved' (sedang dipinjam),
        // kita harus kembalikan stok alatnya dulu agar tidak hilang.
        if ($loan->status == 'approved') {
            $tool = Tool::findOrFail($loan->tool_id);
            $tool->increment('stock');
        }

        $loan->delete();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Admin Hapus Peminjaman',
            'description' => 'Admin menghapus data peminjaman ID: ' . $id,
        ]);

        return redirect()->route('admin.loans.index')->with('success', 'Data peminjaman dihapus.');
    }
}