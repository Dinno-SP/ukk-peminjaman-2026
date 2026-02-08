<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Tool;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Akun Admin
        User::create([
            'name' => 'Admin Sekolah',
            'email' => 'admin@sekolah.com',
            'password' => Hash::make('password'), // Passwordnya 'password'
            'role' => 'admin',
        ]);

        // 2. Buat Akun Petugas
        User::create([
            'name' => 'Petugas Lab',
            'email' => 'petugas@sekolah.com',
            'password' => Hash::make('password'),
            'role' => 'petugas',
        ]);

        // 3. Buat Akun Siswa (Peminjam)
        User::create([
            'name' => 'Siswa RPL',
            'email' => 'siswa@sekolah.com',
            'password' => Hash::make('password'),
            'role' => 'peminjam',
        ]);

        // 4. Buat Data Kategori Dummy
        $cat1 = Category::create(['name' => 'Elektronik']);
        $cat2 = Category::create(['name' => 'Perkakas Tangan']);

        // 5. Buat Data Alat Dummy
        Tool::create([
            'category_id' => $cat1->id,
            'name' => 'Laptop Asus ROG',
            'description' => 'Laptop spek tinggi untuk desain',
            'stock' => 5,
        ]);

        Tool::create([
            'category_id' => $cat2->id,
            'name' => 'Obeng Set',
            'description' => 'Set obeng lengkap',
            'stock' => 10,
        ]);
    }
}