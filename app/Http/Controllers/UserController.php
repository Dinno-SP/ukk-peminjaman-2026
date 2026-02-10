<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'role' => 'required|in:admin,petugas,peminjam',
            // Validasi tambahan (opsional, boleh kosong)
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'class' => 'nullable|string',
            'nis' => 'nullable|string',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            // Masukkan data tambahan
            'phone' => $request->phone,
            'address' => $request->address,
            'class' => $request->class,
            'nis' => $request->nis,
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Tambah User',
            'description' => 'Menambahkan user baru: ' . $user->name,
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan!');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,petugas,peminjam',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            // Masukkan data tambahan ke array update
            'phone' => $request->phone,
            'address' => $request->address,
            'class' => $request->class,
            'nis' => $request->nis,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Edit User',
            'description' => 'Mengupdate user: ' . $user->name,
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui!');
    }

    public function destroy(User $user)
    {
        $namaUser = $user->name;
        $user->delete();

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Hapus User',
            'description' => 'Menghapus user: ' . $namaUser,
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil dihapus!');
    }
}