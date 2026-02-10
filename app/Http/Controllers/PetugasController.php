<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
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
        $actives = Loan::with(['user', 'tool'])
            ->whereIn('status', ['approved', 'returning']) // Pakai whereIn
            ->get();
        
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
            
            // 1. Kembalikan stok alat
            $tool = Tool::findOrFail($loan->tool_id);
            $tool->increment('stock');

            // 2. Hitung Denda (PERBAIKAN LOGIKA)
            // Kita pakai startOfDay() supaya jam/menit/detik diabaikan. Fokus ke tanggal saja.
            $tanggalRencana = Carbon::parse($loan->return_date)->startOfDay(); 
            $tanggalKembali = Carbon::now()->startOfDay(); 
            
            $denda = 0;
            $jumlahHariTelat = 0;

            // Cek apakah tanggal kembali LEBIH BESAR dari rencana
            if ($tanggalKembali->greaterThan($tanggalRencana)) {
                $jumlahHariTelat = $tanggalKembali->diffInDays($tanggalRencana);
                $denda = $jumlahHariTelat * 1000;
            }

            // 3. Update data peminjaman
            $loan->update([
                'status' => 'returned',
                'actual_return_date' => Carbon::now(), // Untuk database tetap simpan jam aslinya
                'fine' => $denda,
            ]);
            
            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => 'Terima Pengembalian',
                'description' => 'Menerima pengembalian ' . $loan->tool->name . '. Denda: Rp ' . number_format($denda),
            ]);

            if ($denda > 0) {
                return back()->with('success', 'Terlambat ' . $jumlahHariTelat . ' hari. Denda: Rp ' . number_format($denda));
            }

            return back()->with('success', 'Alat dikembalikan tepat waktu.');
        }

    // 5. Fitur Lihat Laporan & Filter Tanggal
    public function laporan(Request $request)
    {
        // Ambil input tanggal dari form filter
        $tanggal = $request->input('date');

        // Query dasar: Ambil semua peminjaman beserta user dan alatnya
        $query = Loan::with(['user', 'tool']);

        // Jika user memilih tanggal, tambahkan filter
        if ($tanggal) {
            $query->whereDate('loan_date', $tanggal);
        }

        // Ambil datanya (urutkan dari yang terbaru)
        $loans = $query->latest()->get();

        return view('petugas.laporan', compact('loans', 'tanggal'));
    }
}