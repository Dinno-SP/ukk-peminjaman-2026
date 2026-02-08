<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PetugasController;

Route::get('/', function () {
    return view('welcome');
});

// 1. Arahkan dashboard ke controller yang tepat sesuai Role
Route::get('/dashboard', function () {
    if (Auth::user()->role == 'admin') {
        return redirect()->route('admin.dashboard');
    } elseif (Auth::user()->role == 'petugas') {
        return redirect()->route('petugas.dashboard');
    } else {
        return redirect()->route('peminjam.dashboard');
    }
})->middleware(['auth', 'verified'])->name('dashboard');

// 2. Jalur Khusus Admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        $total_users = \App\Models\User::count();
        $total_tools = \App\Models\Tool::count();
        $total_categories = \App\Models\Category::count();
        
        return view('admin.dashboard', compact('total_users', 'total_tools', 'total_categories'));
    })->name('admin.dashboard');

    Route::get('/admin/logs', [\App\Http\Controllers\LogController::class, 'index'])->name('admin.logs');
    Route::resource('categories', CategoryController::class);
    Route::resource('tools', \App\Http\Controllers\ToolController::class);
    Route::resource('users', \App\Http\Controllers\UserController::class);
});

// 3. Jalur Khusus Petugas
Route::middleware(['auth', 'role:petugas'])->group(function () {
    // Dashboard Petugas
    Route::get('/petugas/dashboard', [PetugasController::class, 'index'])->name('petugas.dashboard');

    // Aksi-aksi Petugas
    Route::post('/petugas/approve/{id}', [PetugasController::class, 'approve'])->name('petugas.approve');
    Route::post('/petugas/reject/{id}', [PetugasController::class, 'reject'])->name('petugas.reject');
    Route::post('/petugas/return/{id}', [PetugasController::class, 'complete'])->name('petugas.return');
});

// 4. Jalur Khusus Peminjam (Siswa)
Route::middleware(['auth', 'role:peminjam'])->group(function () {
    
    // Ubah bagian ini agar mengirim data $tools ke tampilan
    Route::get('/peminjam/dashboard', function () {
        $tools = \App\Models\Tool::where('stock', '>', 0)->get(); // Hanya ambil alat yang stoknya ada
        $loans = \App\Models\Loan::where('user_id', Illuminate\Support\Facades\Auth::id())->with('tool')->get(); // Riwayat pinjam saya
        return view('peminjam.dashboard', compact('tools', 'loans'));
    })->name('peminjam.dashboard');

    // Tambahkan jalur untuk proses pinjam
    Route::post('/loans', [App\Http\Controllers\LoanController::class, 'store'])->name('loan.store');
});

// 5. Rute Profile 
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';