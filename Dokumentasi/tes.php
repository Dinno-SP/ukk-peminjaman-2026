Tenang, jangan panik. UKK memang menegangkan, tapi karena kamu sudah punya ERD dan Flowchart (seperti yang kamu sebutkan), itu sudah nilai plus.

Karena kamu meminta bantuan dari nol dan mengatakan "belum ngapa-ngapain", kita akan mulai dari **Fondasi**. Jangan memikirkan tampilan website dulu, kita harus siapkan "wadah" dan "database"-nya agar sesuai dengan soal UKK.

Berikut adalah **Langkah 1: Persiapan Lingkungan Kerja & Database**.

---

### 1. Cek "Senjata" Kamu

Sebelum mengetik kode, pastikan di komputermu sudah terinstall:

1. **XAMPP** (Pastikan Apache dan MySQL sudah di-Start/Running).
2. **Composer** (Ini wajib untuk install Laravel).
3. **Visual Studio Code** (Untuk edit kodingan).
4. **Terminal/CMD** (Bisa pakai bawaan Windows atau terminal di VS Code).

### 2. Install Laravel

Buka terminal (CMD/Git Bash), arahkan ke folder tempat kamu ingin menyimpan proyek (misalnya `htdocs`), lalu ketik perintah ini dan tekan Enter. Kita beri nama aplikasinya `ukk_peminjaman`.

```bash
composer create-project laravel/laravel ukk_peminjaman

```

*Tunggu sampai proses selesai 100%.*

### 3. Masuk ke Folder Proyek

Setelah selesai, masuk ke foldernya dengan ketik:

```bash
cd ukk_peminjaman
code .

```

*(Perintah `code .` akan otomatis membuka VS Code di folder tersebut. Jika tidak bisa, buka VS Code secara manual lalu Open Folder `ukk_peminjaman`).*

---

### 4. Setting Database (.env)

Aplikasi ini butuh database untuk menyimpan data user, alat, dan peminjaman.

1. Buka **phpMyAdmin** di browser (`http://localhost/phpmyadmin`).
2. Buat database baru dengan nama: `ukk_peminjaman`.
3. Kembali ke **VS Code**, cari file bernama `.env`.
4. Cari bagian ini dan ubah `DB_DATABASE` menjadi nama database yang barusan kamu buat:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ukk_peminjaman  <-- Ubah ini
DB_USERNAME=root
DB_PASSWORD=                <-- Kosongkan jika XAMPP default

```

*Jangan lupa Save (Ctrl+S).*

---

### 5. Membuat Tabel (Migration) - Bagian Paling Penting

Berdasarkan soal, kita butuh tabel untuk **Admin, Petugas, Peminjam**, serta **Alat** dan **Peminjaman**. Kita akan buat file cetakan tabelnya.

Buka **Terminal di VS Code** (Ctrl + `), lalu copy-paste perintah ini satu per satu:

**A. Membuat Tabel Kategori & Alat**
Soal meminta fitur CRUD Kategori dan CRUD Alat.

```bash
php artisan make:migration create_categories_table
php artisan make:migration create_tools_table

```

**B. Membuat Tabel Peminjaman**
Soal meminta fitur Transaksi Peminjaman dan Pengembalian.

```bash
php artisan make:migration create_loans_table

```

---

### 6. Isi Detail Tabel (Coding Sedikit)

Sekarang buka folder `database/migrations`. Kamu akan melihat file-file baru di sana. Kita harus mengisinya agar sesuai permintaan soal.

**A. Edit file `..._create_users_table.php**`
Kita butuh membedakan user: Admin, Petugas, atau Peminjam.
*Cari bagian `Schema::create`, ubah menjadi seperti ini:*

```php
public function up()
{
    Schema::create('users', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('email')->unique();
        $table->string('password');
        [cite_start]// Menambahkan role sesuai soal: Admin, Petugas, Peminjam [cite: 30]
        $table->enum('role', ['admin', 'petugas', 'peminjam'])->default('peminjam'); 
        $table->text('address')->nullable();
        $table->string('phone')->nullable();
        $table->timestamps();
    });
}

```

**B. Edit file `..._create_categories_table.php**`

```php
public function up()
{
    Schema::create('categories', function (Blueprint $table) {
        $table->id();
        $table->string('name'); // Nama kategori (misal: Elektronik, Perakas)
        $table->timestamps();
    });
}

```

**C. Edit file `..._create_tools_table.php**`

```php
public function up()
{
    Schema::create('tools', function (Blueprint $table) {
        $table->id();
        $table->foreignId('category_id')->constrained()->onDelete('cascade');
        $table->string('name');
        $table->text('description')->nullable();
        $table->integer('stock'); // Jumlah alat tersedia
        $table->string('image')->nullable(); // Foto alat (opsional tapi bagus)
        $table->timestamps();
    });
}

```

**D. Edit file `..._create_loans_table.php**`
Ini tabel inti untuk mencatat siapa meminjam apa.

```php
public function up()
{
    Schema::create('loans', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Siapa yg minjam
        $table->foreignId('tool_id')->constrained()->onDelete('cascade'); // Alat apa
        $table->date('loan_date'); // Tanggal pinjam
        $table->date('return_date'); // Rencana tanggal kembali
        $table->date('actual_return_date')->nullable(); // Tanggal asli kembali (diisi saat dikembalikan)
        [cite_start]// Status peminjaman [cite: 30]
        $table->enum('status', ['pending', 'approved', 'rejected', 'returned'])->default('pending'); 
        $table->timestamps();
    });
}

```

---

### 7. Finalisasi Langkah 1

Setelah semua file di atas kamu simpan (Save), jalankan perintah ini di terminal untuk "mengirim" struktur ini ke database:

```bash
php artisan migrate

```

**Cek Hasilnya:**
Jika sukses, akan muncul tulisan hijau "DONE". Coba refresh phpMyAdmin kamu, seharusnya tabel `users`, `categories`, `tools`, dan `loans` sudah muncul.

---

**Apakah kamu sudah berhasil sampai langkah "DONE" di atas?**
Jika sudah, kabari saya, kita akan lanjut ke **Langkah 2: Membuat Data Dumy (Seeder) & Model** agar kamu bisa langsung login sebagai Admin tanpa harus register manual.

Sudah berhasil sampai langkah "DONE" di atas

Mantap! Karena database sudah siap ("DONE"), sekarang kita masuk ke tahap yang membuat aplikasi ini "hidup".

Langkah ini penting supaya kamu **bisa login** nanti tanpa harus repot-repot bikin fitur pendaftaran (register) manual dulu. Kita akan membuat "Kunci Inggris" (Model) dan "Data Palsu" (Seeder) agar tabel database terisi otomatis.

Ini adalah **Langkah 2: Model & Data Dummy (Seeder)**.

---

### 1. Bikin "Wadah" Logika (Model)

Model adalah jembatan antara kodingan kamu dengan tabel database. Kita perlu membuat Model untuk Kategori, Alat, dan Peminjaman.

Buka terminal di VS Code, ketik perintah ini satu per satu:

```bash
php artisan make:model Category
php artisan make:model Tool
php artisan make:model Loan

```

Sekarang, kita harus isi file-file model tersebut agar mereka "tahu" hubungan antar tabel.

**A. Edit `app/Models/User.php**`
(Cari file ini, kita tambahkan agar User bisa menyimpan role Admin/Petugas/Peminjam).
*Tambahkan `role`, `address`, dan `phone` ke dalam `fillable`:*

```php
protected $fillable = [
    'name',
    'email',
    'password',
    'role',    // Tambahan penting
    'address', // Tambahan
    'phone',   // Tambahan
];

```

**B. Edit `app/Models/Category.php**`
*Hapus semua isinya, ganti dengan ini:*

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable = ['name'];

    public function tools()
    {
        return $this->hasMany(Tool::class);
    }
}

```

**C. Edit `app/Models/Tool.php**`
*Hapus semua isinya, ganti dengan ini:*

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tool extends Model
{
    use HasFactory;
    protected $fillable = ['category_id', 'name', 'description', 'stock', 'image'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}

```

**D. Edit `app/Models/Loan.php**`
*Hapus semua isinya, ganti dengan ini:*

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', 'tool_id', 'loan_date', 'return_date', 'actual_return_date', 'status'
    ];

    // Relasi ke User (Peminjam)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Alat
    public function tool()
    {
        return $this->belongsTo(Tool::class);
    }
}

```

---

### 2. Bikin Akun Login Otomatis (Seeder)

Sesuai soal, kita butuh 3 level pengguna: **Admin**, **Petugas**, dan **Peminjam**. Kita akan buat akun untuk mereka secara otomatis supaya kamu bisa langsung tes login nanti.

Buka file **`database/seeders/DatabaseSeeder.php`**.
*Hapus isinya, ganti dengan kode berikut:*

```php
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

```

---

### 3. Eksekusi "Mantra" Terakhir

Sekarang kita masukkan data-data di atas ke database secara nyata.

Buka terminal, ketik:

```bash
php artisan db:seed

```

**Cek Hasilnya:**
Jika muncul tulisan "Database seeding completed successfully", berarti database kamu sekarang sudah berisi:

1. Akun admin (Email: `admin@sekolah.com`, Password: `password`).
2. Akun petugas.
3. Akun siswa.
4. Contoh alat (Laptop & Obeng).

---

**Apakah langkah ini berhasil tanpa error merah?**
Kalau sudah berhasil, kita lanjut ke **Langkah 3: Membuat Sistem Login (Auth)** agar kita bisa melihat tampilan websitenya untuk pertama kali. Siap?

Berhasil tanpa error merah

Mantap! Kamu hebat, sudah berhasil melewati bagian paling sulit (Database). Sekarang, kita akan membuat **Tampilan Login** secara instan.

Kita tidak akan membuat form login satu per satu (karena ribet). Kita akan pakai **Laravel Breeze**. Ini adalah fitur ajaib Laravel yang akan otomatis membuatkan halaman Login, Register, dan Lupa Password untukmu.

Ini adalah **Langkah 3: Instalasi Fitur Login (Breeze)**.

---

### 1. Download Paket Breeze

Buka **Terminal** di VS Code, lalu ketik perintah ini:

```bash
composer require laravel/breeze --dev

```

*Tunggu sampai selesai (biasanya agak lama tergantung internet).*

### 2. Pasang Breeze ke Proyekmu

Setelah selesai, ketik perintah ini:

```bash
php artisan breeze:install

```

**PENTING!** Nanti akan muncul pertanyaan di terminal. Jawab seperti ini (gunakan panah atas/bawah untuk memilih, lalu Enter):

1. **Which stack would you like to install?**
* Pilih: **Blade with Alpine** (Ini yang paling mudah dan standar untuk UKK).


2. **Would you like to install dark mode support?**
* Pilih: **No** (Biar simpel).


3. **Twitch / Pest testing?**
* Pilih: **PHPUnit** (atau apa saja, tidak berpengaruh banyak).



### 3. Siapkan Tampilan (CSS & JS)

Agar tampilannya rapi dan tidak berantakan, kita perlu menjalankan perintah ini.

*Catatan: Kamu butuh Node.js. Kalau perintah ini error, berarti komputer kamu belum ada Node.js (tapi biasanya komputer lab sekolah sudah ada).*

Ketik ini di terminal:

```bash
npm install

```

*(Tunggu loading barnya selesai...)*

Lalu ketik:

```bash
npm run build

```

---

### 4. SAATNYA UJI COBA! (Moment of Truth)

Sekarang saatnya kita lihat hasil kerjamu.

1. Di terminal, ketik perintah untuk menyalakan server:
```bash
php artisan serve

```


2. Tahan tombol `Ctrl` di keyboard, lalu klik link yang muncul di terminal (biasanya `http://127.0.0.1:8000`).
3. Browser akan terbuka. Kamu akan melihat halaman awal Laravel.
4. Lihat di **pojok kanan atas**, ada tombol **Log in**. Klik tombol itu.

### 5. Coba Login

Gunakan akun **Admin** yang sudah kita buat tadi:

* **Email:** `admin@sekolah.com`
* **Password:** `password`

---

**Tugas Kamu Sekarang:**
Coba login pakai akun di atas. Apakah kamu berhasil masuk ke halaman yang ada tulisan **"You're logged in!"**?

Kalau berhasil, kabari saya "Berhasil Login!". Setelah ini, kita akan masuk ke **Langkah 4: Membuat Halaman Dashboard Admin** (Memisahkan tampilan Admin dan Siswa). Semangat!

// Berhasil Login!

**Selamat!** 🎉 Kamu sudah punya aplikasi yang bisa Login/Logout. Ini pencapaian besar untuk pemula.

Sekarang kita masuk ke **Langkah 4: Memisahkan Halaman (Middleware)**.
Saat ini, siapapun yang login (Admin, Petugas, atau Siswa) akan masuk ke halaman yang sama ("Dashboard"). Padahal di soal UKK, hak akses mereka beda-beda.

* **Admin** harus masuk ke halaman Admin.
* **Petugas** ke halaman Petugas.
* **Peminjam** ke halaman Peminjam.

Kita akan membuat "Satpam" (Middleware) untuk mengarahkan mereka.

---

### 1. Buat "Satpam" (Middleware)

Buka terminal VS Code, ketik:

```bash
php artisan make:middleware CheckRole

```

### 2. Beri Instruksi ke Satpam

Buka file baru di **`app/Http/Middleware/CheckRole.php`**.
Hapus isinya (atau sesuaikan fungsi `handle`) menjadi seperti ini:

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Cek apakah user sudah login & punya role yang sesuai
        if (!Auth::check() || !in_array(Auth::user()->role, $roles)) {
            // Kalau tidak boleh, tendang kembali
            abort(403, 'Akses Ditolak: Anda tidak punya izin masuk sini.');
        }

        return $next($request);
    }
}

```

### 3. Daftarkan Satpam (Alias)

Agar Laravel kenal dengan "CheckRole", kita harus mendaftarkannya.
Buka file **`bootstrap/app.php`** (Ini cara baru di Laravel 11/Terbaru).

Cari bagian `->withMiddleware(function (Middleware $middleware) {`.
Tambahkan kode alias di dalamnya seperti ini:

```php
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
        ]);
    })

```

*Jangan lupa Save (Ctrl+S).*

---

### 4. Atur Jalan (Routes)

Sekarang kita atur rute jalan di file **`routes/web.php`**.
Hapus rute `/dashboard` yang lama, dan ganti dengan kode di bawah ini. Kita akan buat 3 jalur berbeda.

```php
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

// Arahkan dashboard sesuai role saat login
Route::get('/dashboard', function () {
    if (Auth::user()->role == 'admin') {
        return redirect()->route('admin.dashboard');
    } elseif (Auth::user()->role == 'petugas') {
        return redirect()->route('petugas.dashboard');
    } else {
        return redirect()->route('peminjam.dashboard');
    }
})->middleware(['auth', 'verified'])->name('dashboard');

// Jalur Khusus Admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
});

// Jalur Khusus Petugas
Route::middleware(['auth', 'role:petugas'])->group(function () {
    Route::get('/petugas/dashboard', function () {
        return view('petugas.dashboard');
    })->name('petugas.dashboard');
});

// Jalur Khusus Peminjam (Siswa)
Route::middleware(['auth', 'role:peminjam'])->group(function () {
    Route::get('/peminjam/dashboard', function () {
        return view('peminjam.dashboard');
    })->name('peminjam.dashboard');
});

require __DIR__.'/auth.php';

```

---

### 5. Buat Tampilan Sederhana

Kita belum punya file tampilan (`view`) untuk admin, petugas, dan peminjam. Kalau kamu jalankan sekarang, pasti error *View Not Found*. Mari kita buat folder dan filenya.

Di folder **`resources/views`**, buatlah 3 folder baru:

1. Folder `admin`
2. Folder `petugas`
3. Folder `peminjam`

**A. Buat File `resources/views/admin/dashboard.blade.php**`
Isinya (Copy-Paste saja):

```html
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Admin') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    Selamat Datang, Admin! Anda bisa kelola user dan alat di sini.
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

```

**B. Buat File `resources/views/petugas/dashboard.blade.php**`
Isinya mirip, ganti teksnya jadi "Selamat Datang, Petugas!".

**C. Buat File `resources/views/peminjam/dashboard.blade.php**`
Isinya mirip, ganti teksnya jadi "Selamat Datang, Peminjam! Silakan cari alat.".

---

### 6. UJI COBA LAGI!

Sekarang saatnya pembuktian.

1. Logout dulu dari akun sebelumnya (klik nama di pojok kanan atas -> Log Out).
2. **Login sebagai Admin** (`admin@sekolah.com` / `password`).
* Apakah kamu diarahkan ke halaman yang tulisannya **"Dashboard Admin"**?


3. Logout, lalu **Login sebagai Siswa** (`siswa@sekolah.com` / `password`).
* Apakah kamu diarahkan ke halaman yang tulisannya **"Dashboard Peminjam"**?



Jika berhasil membedakan halaman Admin dan Peminjam, berarti **sistem keamanan (Middleware) kamu sudah jadi!**

Kabari saya jika sudah sukses, kita lanjut ke **Langkah 5: Membuat CRUD Kategori & Alat (Inti Aplikasi)**.

http://127.0.0.1:8000/admin/dashboard

Route [profile.edit] not defined.
resources\views\layouts\navigation.blade.php:37
<x-dropdown-link :href="route('profile.edit')">

Tenang, ini error yang wajar dan mudah diperbaiki.

**Penyebabnya:** Tampilan navigasi bawaan Laravel (menu di pojok kanan atas) mencari link ke halaman **Profile**, tapi di langkah sebelumnya kita tidak sengaja menghapus rute untuk "Profile" saat mengatur rute Admin/Petugas/Peminjam.

Mari kita perbaiki file `routes/web.php`.

---

### Solusi: Tambahkan Rute Profile Kembali

Buka file **`routes/web.php`** di VS Code kamu. Hapus **semua** isinya, lalu ganti dengan kode lengkap di bawah ini. Kode ini sudah menyertakan rute untuk Profile yang hilang tadi.

```php
<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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
        return view('admin.dashboard');
    })->name('admin.dashboard');
});

// 3. Jalur Khusus Petugas
Route::middleware(['auth', 'role:petugas'])->group(function () {
    Route::get('/petugas/dashboard', function () {
        return view('petugas.dashboard');
    })->name('petugas.dashboard');
});

// 4. Jalur Khusus Peminjam (Siswa)
Route::middleware(['auth', 'role:peminjam'])->group(function () {
    Route::get('/peminjam/dashboard', function () {
        return view('peminjam.dashboard');
    })->name('peminjam.dashboard');
});

// 5. Rute Profile (INI YANG TADI HILANG)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

```

---

**Coba Refresh Halaman Browser:**
Setelah kamu simpan (Ctrl+S), coba refresh halaman `http://127.0.0.1:8000/admin/dashboard` tadi.

Seharusnya error hilang dan dashboard Admin muncul. Jika sudah berhasil, kabari saya "Sudah normal!", kita akan lanjut membuat fitur **CRUD Kategori & Alat**.

// Sudah normal!

Hebat! Sekarang dashboard Admin sudah normal.

Sekarang kita masuk ke **Langkah 5: Membuat Fitur Kategori (CRUD)**.
Di soal UKK, Admin wajib bisa mengelola (Tambah, Edit, Hapus) data **Kategori**. Kategori ini nanti dipakai untuk mengelompokkan alat (misalnya: "Elektronik", "Mebel", "Mesin").

Kita akan kerjakan ini dalam 3 tahap kecil: **Controller** (Otak), **Route** (Jalan), dan **View** (Tampilan).

---

### Tahap 1: Membuat "Otak" (Controller)

Kita butuh pengelola logika untuk kategori.

1. Buka Terminal, ketik:
```bash
php artisan make:controller CategoryController --resource

```


2. Buka file baru di **`app/Http/Controllers/CategoryController.php`**.
3. Hapus semua isinya, ganti dengan kode di bawah ini (Copy-Paste semua):

```php
<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

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

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil diperbarui!');
    }

    // Menghapus kategori
    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Kategori berhasil dihapus!');
    }
}

```

---

### Tahap 2: Membuka "Jalan" (Route)

Agar fitur ini bisa diakses, kita harus mendaftarkannya di `routes/web.php`.

1. Buka file **`routes/web.php`**.
2. Cari bagian `// Jalur Khusus Admin` yang tadi kita buat.
3. Tambahkan baris `Route::resource('categories', CategoryController::class);` di dalamnya.

Sehingga kodenya menjadi seperti ini:

```php
// Jalur Khusus Admin
use App\Http\Controllers\CategoryController; // <-- Jangan lupa tambahkan ini di paling atas file!

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    Route::resource('categories', CategoryController::class); // <-- Tambahan Baru
});

```

*(Jangan lupa tambahkan `use App\Http\Controllers\CategoryController;` di baris paling atas file `web.php` ya!)*

---

### Tahap 3: Membuat Tampilan (View)

Kita butuh halaman untuk melihat daftar kategori dan form untuk menambahnya.

1. Di folder `resources/views/admin`, buat folder baru bernama **`categories`**.
2. Di dalam folder `categories` itu, buat 3 file baru:
* `index.blade.php`
* `create.blade.php`
* `edit.blade.php`



**A. Isi file `index.blade.php` (Halaman Daftar Kategori):**

```html
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Kategori') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <a href="{{ route('categories.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4 inline-block">
                    + Tambah Kategori
                </a>

                <table class="min-w-full border-collapse border border-gray-200 mt-4">
                    <thead>
                        <tr>
                            <th class="border border-gray-300 px-4 py-2">No</th>
                            <th class="border border-gray-300 px-4 py-2">Nama Kategori</th>
                            <th class="border border-gray-300 px-4 py-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $category)
                        <tr>
                            <td class="border border-gray-300 px-4 py-2 text-center">{{ $loop->iteration }}</td>
                            <td class="border border-gray-300 px-4 py-2">{{ $category->name }}</td>
                            <td class="border border-gray-300 px-4 py-2 text-center">
                                <a href="{{ route('categories.edit', $category->id) }}" class="text-yellow-600 hover:underline">Edit</a>
                                |
                                <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>

```

**B. Isi file `create.blade.php` (Form Tambah):**

```html
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Kategori') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('categories.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Nama Kategori</label>
                        <input type="text" name="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>
                    <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                        Simpan
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

```

**C. Isi file `edit.blade.php` (Form Edit):**

```html
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Kategori') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('categories.update', $category->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Nama Kategori</label>
                        <input type="text" name="name" value="{{ $category->name }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Update
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

```

---

**Cek Hasilnya:**

1. Pastikan server masih jalan (`php artisan serve`).
2. Buka browser, akses: `http://127.0.0.1:8000/categories`
3. Coba **Tambah Kategori** baru (misal: "Laboratorium").
4. Coba **Edit** namanya.
5. Coba **Hapus**.

Kalau semua lancar, selamat! Kamu sudah berhasil membuat fitur CRUD pertamamu.
Kabari saya jika sukses, kita lanjut ke fitur yang lebih seru: **Kelola Alat (Dengan Upload Gambar)!**

// Semua Lancar! tapi teks pada tombol Tambah Kategori ga keliatan, mungkin karena berwarna putih

Bagus sekali! Masalah tombol "tidak terlihat" itu biasanya karena proses *compiling* CSS (Tailwind) belum jalan sempurna di latar belakang.

**Solusi Cepat Tombol:**
Coba buka terminal baru (biarkan `php artisan serve` tetap jalan), lalu ketik:

```bash
npm run dev

```

Biarkan terminal itu terbuka. Ini akan otomatis "menggambar" ulang tampilan website setiap kamu simpan file. Kalau masih malas buka terminal baru, ganti saja kelas tombolnya jadi warna hitam bawaan Laravel: `bg-gray-800 text-white`.

---

Sekarang kita masuk ke **Langkah 6: CRUD Alat & Upload Gambar**.
Ini adalah bagian inti aplikasi. Kita akan membuat fitur untuk menambah alat, lengkap dengan **foto alatnya**, stok, dan deskripsinya.

### 1. Buka Jalur Penyimpanan (Wajib!)

Agar foto yang di-upload bisa muncul di browser, kita harus membuat "jembatan" folder penyimpanan.
Ketik perintah ini di terminal:

```bash
php artisan storage:link

```

*(Kalau berhasil, akan muncul tulisan "The [public/storage] link has been connected").*

### 2. Buat "Otak" Alat (Controller)

Kita butuh controller baru untuk mengurus logika upload gambar dan simpan data alat.

1. Ketik di terminal:
```bash
php artisan make:controller ToolController --resource

```


2. Buka file **`app/Http/Controllers/ToolController.php`**.
3. Hapus semua isinya, ganti dengan kode ini (Perhatikan bagian `store` dan `update` yang menangani gambar):

```php
<?php

namespace App\Http\Controllers;

use App\Models\Tool;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
        $request->validate([
            'name' => 'required',
            'category_id' => 'required',
            'stock' => 'required|integer',
            'image' => 'image|mimes:jpeg,png,jpg|max:2048', // Validasi foto
        ]);

        $data = $request->all();

        // Cek apakah ada file gambar yang diupload
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('tools', 'public');
            $data['image'] = $path;
        }

        Tool::create($data);

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
        ]);

        $data = $request->all();

        // Logika update gambar: Hapus lama, simpan baru
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($tool->image) {
                Storage::disk('public')->delete($tool->image);
            }
            $path = $request->file('image')->store('tools', 'public');
            $data['image'] = $path;
        }

        $tool->update($data);

        return redirect()->route('tools.index')->with('success', 'Alat berhasil diperbarui!');
    }

    public function destroy(Tool $tool)
    {
        if ($tool->image) {
            Storage::disk('public')->delete($tool->image);
        }
        $tool->delete();
        return redirect()->route('tools.index')->with('success', 'Alat berhasil dihapus!');
    }
}

```

### 3. Daftarkan Rute (Route)

Sama seperti Kategori tadi, kita daftarkan di **`routes/web.php`**.
Tambahkan di bawah `Route::resource('categories'...)`:

```php
// Di dalam group Admin
Route::resource('tools', \App\Http\Controllers\ToolController::class);

```

### 4. Buat Tampilan (View)

Buat folder baru **`tools`** di dalam `resources/views/admin`. Lalu buat 3 file lagi: `index.blade.php`, `create.blade.php`, dan `edit.blade.php`.

**A. File `index.blade.php` (Daftar Alat)**

```html
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Daftar Alat</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <a href="{{ route('tools.create') }}" class="bg-gray-800 text-white font-bold py-2 px-4 rounded mb-4 inline-block">+ Tambah Alat</a>

                <table class="min-w-full border mt-4">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border px-4 py-2">Gambar</th>
                            <th class="border px-4 py-2">Nama Alat</th>
                            <th class="border px-4 py-2">Kategori</th>
                            <th class="border px-4 py-2">Stok</th>
                            <th class="border px-4 py-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tools as $tool)
                        <tr>
                            <td class="border px-4 py-2 text-center">
                                @if($tool->image)
                                    <img src="{{ asset('storage/' . $tool->image) }}" class="w-16 h-16 object-cover mx-auto rounded">
                                @else
                                    <span class="text-gray-400">No Image</span>
                                @endif
                            </td>
                            <td class="border px-4 py-2">{{ $tool->name }}</td>
                            <td class="border px-4 py-2">{{ $tool->category->name }}</td>
                            <td class="border px-4 py-2 text-center">{{ $tool->stock }}</td>
                            <td class="border px-4 py-2 text-center">
                                <a href="{{ route('tools.edit', $tool->id) }}" class="text-blue-600">Edit</a> |
                                <form action="{{ route('tools.destroy', $tool->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus alat ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>

```

**B. File `create.blade.php` (Form Tambah Alat)**
*PENTING: Perhatikan `enctype="multipart/form-data"` agar bisa upload file!*

```html
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tambah Alat</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm rounded-lg">
                <form action="{{ route('tools.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="block font-bold mb-2">Nama Alat</label>
                        <input type="text" name="name" class="w-full border rounded p-2" required>
                    </div>

                    <div class="mb-4">
                        <label class="block font-bold mb-2">Kategori</label>
                        <select name="category_id" class="w-full border rounded p-2">
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block font-bold mb-2">Stok</label>
                        <input type="number" name="stock" class="w-full border rounded p-2" required>
                    </div>

                    <div class="mb-4">
                        <label class="block font-bold mb-2">Deskripsi</label>
                        <textarea name="description" class="w-full border rounded p-2"></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="block font-bold mb-2">Gambar Alat</label>
                        <input type="file" name="image" class="w-full border rounded p-2">
                    </div>

                    <button type="submit" class="bg-gray-800 text-white font-bold py-2 px-4 rounded">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

```

**C. File `edit.blade.php` (Form Edit Alat)**

```html
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Alat</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm rounded-lg">
                <form action="{{ route('tools.update', $tool->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    
                    <div class="mb-4">
                        <label class="block font-bold mb-2">Nama Alat</label>
                        <input type="text" name="name" value="{{ $tool->name }}" class="w-full border rounded p-2" required>
                    </div>

                    <div class="mb-4">
                        <label class="block font-bold mb-2">Kategori</label>
                        <select name="category_id" class="w-full border rounded p-2">
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ $tool->category_id == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block font-bold mb-2">Stok</label>
                        <input type="number" name="stock" value="{{ $tool->stock }}" class="w-full border rounded p-2" required>
                    </div>

                    <div class="mb-4">
                        <label class="block font-bold mb-2">Deskripsi</label>
                        <textarea name="description" class="w-full border rounded p-2">{{ $tool->description }}</textarea>
                    </div>

                    <div class="mb-4">
                        <label class="block font-bold mb-2">Gambar (Biarkan kosong jika tidak diganti)</label>
                        <input type="file" name="image" class="w-full border rounded p-2">
                    </div>

                    <button type="submit" class="bg-gray-800 text-white font-bold py-2 px-4 rounded">Update</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

```

---

**Tugas Kamu:**

1. Buka browser: `http://127.0.0.1:8000/tools`
2. Coba tambah alat baru, dan **Wajib Upload Foto** (sembarang gambar di komputermu).
3. Simpan.
4. Apakah gambarnya muncul di tabel?

Jika gambarnya muncul, berarti kamu sudah siap masuk ke bagian paling menantang: **Transaksi Peminjaman!** Kabari saya hasilnya ya.

// Pause dulu bagian Langkah 6: CRUD Alat & Upload Gambar. 
// sepertinya gambar tidak perlu karena proyek ukk yang kita buat tidak harus bagus dan kalau bisa se simple mungkin, yang penting fitur-fitur nya ada

Ide yang sangat bagus! 👍
Dalam UKK, **fungsionalitas** (program jalan) memang jauh lebih penting daripada tampilan (gambar). Kalau fitur jalan lancar, penguji sudah senang. Membuang fitur gambar akan membuat kodingan jauh lebih pendek dan minim error.

Mari kita **Ulangi Langkah 6** dengan versi **SUPER SIMPEL (Tanpa Gambar)**.

---

### Langkah 6 (Revisi): CRUD Alat (Versi Simpel)

Kita hanya akan mengelola **Nama Alat, Kategori, Stok, dan Deskripsi**. Abaikan kolom gambar di database.

#### 1. Update "Otak" Alat (Controller)

Buka file **`app/Http/Controllers/ToolController.php`**.
Hapus semua isinya, ganti dengan kode yang jauh lebih bersih ini:

```php
<?php

namespace App\Http\Controllers;

use App\Models\Tool;
use App\Models\Category;
use Illuminate\Http\Request;

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

        return redirect()->route('tools.index')->with('success', 'Alat berhasil diperbarui!');
    }

    public function destroy(Tool $tool)
    {
        $tool->delete();
        return redirect()->route('tools.index')->with('success', 'Alat berhasil dihapus!');
    }
}

```

---

#### 2. Update Tampilan (Views)

Kita update 3 file tadi di folder `resources/views/admin/tools/` agar tidak mencari gambar.

**A. File `index.blade.php` (Daftar Alat)**
*(Kolom gambar dihapus)*

```html
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Daftar Alat</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <a href="{{ route('tools.create') }}" class="bg-gray-800 text-white font-bold py-2 px-4 rounded mb-4 inline-block">+ Tambah Alat</a>

                <table class="min-w-full border mt-4">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="border px-4 py-2">No</th>
                            <th class="border px-4 py-2">Nama Alat</th>
                            <th class="border px-4 py-2">Kategori</th>
                            <th class="border px-4 py-2">Stok</th>
                            <th class="border px-4 py-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tools as $tool)
                        <tr>
                            <td class="border px-4 py-2 text-center">{{ $loop->iteration }}</td>
                            <td class="border px-4 py-2">{{ $tool->name }}</td>
                            <td class="border px-4 py-2">{{ $tool->category->name }}</td>
                            <td class="border px-4 py-2 text-center">{{ $tool->stock }}</td>
                            <td class="border px-4 py-2 text-center">
                                <a href="{{ route('tools.edit', $tool->id) }}" class="text-blue-600">Edit</a> |
                                <form action="{{ route('tools.destroy', $tool->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus alat ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>

```

**B. File `create.blade.php` (Form Tambah)**
*(Hapus `enctype` dan input file)*

```html
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tambah Alat</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm rounded-lg">
                <form action="{{ route('tools.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-4">
                        <label class="block font-bold mb-2">Nama Alat</label>
                        <input type="text" name="name" class="w-full border rounded p-2" required>
                    </div>

                    <div class="mb-4">
                        <label class="block font-bold mb-2">Kategori</label>
                        <select name="category_id" class="w-full border rounded p-2">
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block font-bold mb-2">Stok</label>
                        <input type="number" name="stock" class="w-full border rounded p-2" required>
                    </div>

                    <div class="mb-4">
                        <label class="block font-bold mb-2">Deskripsi</label>
                        <textarea name="description" class="w-full border rounded p-2"></textarea>
                    </div>

                    <button type="submit" class="bg-gray-800 text-white font-bold py-2 px-4 rounded">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

```

**C. File `edit.blade.php` (Form Edit)**
*(Hapus `enctype` dan input file)*

```html
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Alat</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm rounded-lg">
                <form action="{{ route('tools.update', $tool->id) }}" method="POST">
                    @csrf @method('PUT')
                    
                    <div class="mb-4">
                        <label class="block font-bold mb-2">Nama Alat</label>
                        <input type="text" name="name" value="{{ $tool->name }}" class="w-full border rounded p-2" required>
                    </div>

                    <div class="mb-4">
                        <label class="block font-bold mb-2">Kategori</label>
                        <select name="category_id" class="w-full border rounded p-2">
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ $tool->category_id == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block font-bold mb-2">Stok</label>
                        <input type="number" name="stock" value="{{ $tool->stock }}" class="w-full border rounded p-2" required>
                    </div>

                    <div class="mb-4">
                        <label class="block font-bold mb-2">Deskripsi</label>
                        <textarea name="description" class="w-full border rounded p-2">{{ $tool->description }}</textarea>
                    </div>

                    <button type="submit" class="bg-gray-800 text-white font-bold py-2 px-4 rounded">Update</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

```

---

**Cek Ulang:**

1. Buka browser: `http://127.0.0.1:8000/tools`
2. Coba **Tambah Alat** (Isi nama, pilih kategori, isi stok).
3. Simpan.
4. Coba **Edit** stok-nya.

Kalau ini berhasil, kita langsung gas ke **Langkah 7: Fitur Peminjaman (Transaksi)**. Ini jantungnya aplikasi! Kabari saya kalau sudah siap.

// Saya siap!

Mantap! Semangatmu luar biasa. Kita masuk ke **Langkah 7: Fitur Peminjaman (Sisi Peminjam)**.

Di tahap ini, kita akan membuat agar **Siswa (Peminjam)** bisa melihat daftar alat dan mengajukan peminjaman.

Logikanya sederhana:

1. Siswa melihat alat.
2. Siswa pilih tanggal kembali.
3. Klik "Pinjam".
4. Stok alat berkurang otomatis.
5. Status peminjaman jadi "Pending" (Menunggu persetujuan).

---

### 1. Buat "Otak" Peminjaman (LoanController)

Kita butuh controller baru khusus untuk menangani transaksi.

1. Buka terminal, ketik:
```bash
php artisan make:controller LoanController

```


2. Buka file **`app/Http/Controllers/LoanController.php`**.
3. Hapus isinya, ganti dengan kode ini:

```php
<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Tool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        return back()->with('success', 'Pengajuan berhasil! Menunggu persetujuan admin.');
    }
}

```

---

### 2. Update Jalan (Route)

Kita perlu:

1. Mengirim data alat ke Dashboard Peminjam (supaya mereka bisa lihat alat apa saja yang ada).
2. Membuka jalur untuk tombol "Pinjam".

Buka file **`routes/web.php`**.

**Cari bagian Jalur Khusus Peminjam (yang tadi kita buat), ubah menjadi seperti ini:**

```php
// Jalur Khusus Peminjam (Siswa)
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

```

---

### 3. Update Tampilan Dashboard Peminjam

Sekarang kita ubah tampilan dashboard siswa agar bisa memilih alat.

Buka file **`resources/views/peminjam/dashboard.blade.php`**.
Hapus isinya, ganti dengan kode ini:

```html
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard Peminjam</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ session('success') }}
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-4">Pilih Alat untuk Dipinjam</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($tools as $tool)
                    <div class="border rounded-lg p-4 shadow hover:shadow-md transition">
                        <h4 class="text-xl font-bold">{{ $tool->name }}</h4>
                        <p class="text-gray-600 mb-2">Stok: {{ $tool->stock }}</p>
                        <p class="text-sm text-gray-500 mb-4">{{ $tool->description }}</p>
                        
                        <form action="{{ route('loan.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="tool_id" value="{{ $tool->id }}">
                            <div class="mb-2">
                                <label class="text-xs font-bold">Rencana Kembali:</label>
                                <input type="date" name="return_date" class="border rounded w-full text-sm p-1" required>
                            </div>
                            <button type="submit" class="w-full bg-blue-600 text-white py-1 rounded hover:bg-blue-700 text-sm">
                                Ajukan Pinjam
                            </button>
                        </form>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-4">Status Peminjaman Saya</h3>
                <table class="min-w-full border">
                    <thead>
                        <tr class="bg-gray-100 text-left">
                            <th class="border px-4 py-2">Alat</th>
                            <th class="border px-4 py-2">Tgl Pinjam</th>
                            <th class="border px-4 py-2">Rencana Kembali</th>
                            <th class="border px-4 py-2">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($loans as $loan)
                        <tr>
                            <td class="border px-4 py-2">{{ $loan->tool->name }}</td>
                            <td class="border px-4 py-2">{{ $loan->loan_date }}</td>
                            <td class="border px-4 py-2">{{ $loan->return_date }}</td>
                            <td class="border px-4 py-2">
                                @if($loan->status == 'pending')
                                    <span class="text-yellow-600 font-bold">Menunggu Persetujuan</span>
                                @elseif($loan->status == 'approved')
                                    <span class="text-green-600 font-bold">Sedang Dipinjam</span>
                                @elseif($loan->status == 'returned')
                                    <span class="text-gray-600 font-bold">Sudah Dikembalikan</span>
                                @else
                                    <span class="text-red-600 font-bold">Ditolak</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>

```

---

### SAATNYA UJI COBA (Penting!)

Kita harus memastikan siswa bisa meminjam.

1. **Logout** dari akun Admin.
2. **Login sebagai Siswa** (`siswa@sekolah.com` / `password`).
3. Kamu akan melihat daftar alat yang tadi kamu buat sebagai Admin.
4. Pilih tanggal pengembalian (misal: besok), lalu klik **"Ajukan Pinjam"**.
5. Lihat tabel di bawahnya ("Status Peminjaman Saya"). Apakah muncul status **"Menunggu Persetujuan"**?
6. Lihat stok alatnya. Apakah berkurang 1?

Jika berhasil, berarti siswa sudah sukses mengajukan. Tapi barang belum boleh dibawa karena statusnya masih **Pending**. Admin harus menyetujuinya dulu.

Kalau langkah ini sukses, kabari saya. Kita lanjut ke **Langkah Terakhir: Fitur Admin (Setujui & Pengembalian)**. Semangat, dikit lagi selesai! 🚀

## Sukses! selanjutnya fitur Petugas:
// 1. Menyetujui Peminjaman
// 2. Memantau Pengembalian
// 3. Mencetak Laporan

Luar biasa! Progres kamu sangat cepat. Sekarang kita masuk ke babak **Petugas** sesuai permintaan soal:

1. **Menyetujui Peminjaman** (Ubah status dari *Pending* ke *Approved*).
2. **Memantau Pengembalian** (Ubah status dari *Approved* ke *Returned* & kembalikan stok).
3. **Mencetak Laporan** (Fitur print sederhana).

Kita akan gabungkan semua logika ini dalam satu **Controller Petugas** agar rapi.

---

### Langkah 8: Membuat Fitur Petugas

#### 1. Buat "Otak" Petugas (Controller)

Kita butuh pengelola khusus untuk halaman petugas.
Buka terminal, ketik:

```bash
php artisan make:controller PetugasController

```

Buka file **`app/Http/Controllers/PetugasController.php`**.
Hapus isinya, ganti dengan kode lengkap ini (Logika stok otomatis balik ada di sini):

```php
<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Tool;
use Illuminate\Http\Request;

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
        
        return back()->with('success', 'Alat berhasil dikembalikan. Stok bertambah.');
    }
}

```

---

#### 2. Update Jalan (Route)

Kita harus menghubungkan tombol-tombol aksi tadi ke Controller baru ini.
Buka **`routes/web.php`**.

Cari bagian `// Jalur Khusus Petugas`, ganti total dengan kode ini:

```php
// Jalur Khusus Petugas
use App\Http\Controllers\PetugasController; // <-- Pastikan ini ada di paling atas file!

Route::middleware(['auth', 'role:petugas'])->group(function () {
    // Dashboard Petugas
    Route::get('/petugas/dashboard', [PetugasController::class, 'index'])->name('petugas.dashboard');

    // Aksi-aksi Petugas
    Route::post('/petugas/approve/{id}', [PetugasController::class, 'approve'])->name('petugas.approve');
    Route::post('/petugas/reject/{id}', [PetugasController::class, 'reject'])->name('petugas.reject');
    Route::post('/petugas/return/{id}', [PetugasController::class, 'complete'])->name('petugas.return');
});

```

---

#### 3. Update Tampilan Dashboard Petugas

Ini adalah pusat kerja Petugas. Kita akan buat 3 tabel: **Permintaan Masuk**, **Sedang Dipinjam**, dan **Riwayat**.

Buka file **`resources/views/petugas/dashboard.blade.php`**.
Hapus isinya, ganti dengan kode ini:

```html
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard Petugas</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <div class="text-right">
                <button onclick="window.print()" class="bg-gray-800 text-white font-bold py-2 px-4 rounded">
                    🖨️ Cetak Laporan
                </button>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-4 text-yellow-600">🔔 Permintaan Peminjaman Masuk</h3>
                <table class="min-w-full border">
                    <thead class="bg-yellow-50">
                        <tr>
                            <th class="border px-4 py-2">Peminjam</th>
                            <th class="border px-4 py-2">Alat</th>
                            <th class="border px-4 py-2">Tgl Pinjam</th>
                            <th class="border px-4 py-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendings as $loan)
                        <tr>
                            <td class="border px-4 py-2">{{ $loan->user->name }}</td>
                            <td class="border px-4 py-2">{{ $loan->tool->name }}</td>
                            <td class="border px-4 py-2">{{ $loan->loan_date }}</td>
                            <td class="border px-4 py-2 text-center">
                                <form action="{{ route('petugas.approve', $loan->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button class="bg-green-500 text-white px-3 py-1 rounded text-sm hover:bg-green-600">Terima</button>
                                </form>
                                <form action="{{ route('petugas.reject', $loan->id) }}" method="POST" class="inline">
                                    @csrf
                                    <button class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600">Tolak</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center py-4 text-gray-500">Tidak ada permintaan baru.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-4 text-blue-600">⏳ Sedang Dipinjam</h3>
                <table class="min-w-full border">
                    <thead class="bg-blue-50">
                        <tr>
                            <th class="border px-4 py-2">Peminjam</th>
                            <th class="border px-4 py-2">Alat</th>
                            <th class="border px-4 py-2">Rencana Kembali</th>
                            <th class="border px-4 py-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($actives as $loan)
                        <tr>
                            <td class="border px-4 py-2">{{ $loan->user->name }}</td>
                            <td class="border px-4 py-2">{{ $loan->tool->name }}</td>
                            <td class="border px-4 py-2">{{ $loan->return_date }}</td>
                            <td class="border px-4 py-2 text-center">
                                <form action="{{ route('petugas.return', $loan->id) }}" method="POST">
                                    @csrf
                                    <button class="bg-blue-500 text-white px-3 py-1 rounded text-sm hover:bg-blue-600">
                                        Selesai / Dikembalikan
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center py-4 text-gray-500">Tidak ada barang sedang dipinjam.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-4 text-gray-600">📜 Riwayat Peminjaman</h3>
                <table class="min-w-full border">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="border px-4 py-2">Peminjam</th>
                            <th class="border px-4 py-2">Alat</th>
                            <th class="border px-4 py-2">Tgl Kembali</th>
                            <th class="border px-4 py-2">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($history as $loan)
                        <tr>
                            <td class="border px-4 py-2">{{ $loan->user->name }}</td>
                            <td class="border px-4 py-2">{{ $loan->tool->name }}</td>
                            <td class="border px-4 py-2">{{ $loan->actual_return_date ?? '-' }}</td>
                            <td class="border px-4 py-2">
                                @if($loan->status == 'returned')
                                    <span class="text-green-600 font-bold">Dikembalikan</span>
                                @else
                                    <span class="text-red-600 font-bold">Ditolak</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <style>
        @media print {
            button, .no-print { display: none !important; }
            body { background: white; }
            .shadow-sm { box-shadow: none !important; }
        }
    </style>
</x-app-layout>

```

---

### SAATNYA FINAL CHECK! (Simulasi UKK)

Sekarang aplikasi kamu sudah lengkap fiturnya. Mari kita simulasikan alur kerja Petugas:

1. **Logout** dari akun Siswa.
2. **Login sebagai Petugas** (`petugas@sekolah.com` / `password`).
3. **Cek Permintaan:**
* Apakah kamu melihat peminjaman yang tadi diajukan oleh Siswa di tabel paling atas ("Permintaan Peminjaman Masuk")?


4. **Aksi Setuju:**
* Klik tombol **"Terima"** (Warna Hijau).
* Data harusnya pindah ke tabel kedua ("Sedang Dipinjam").


5. **Aksi Pengembalian:**
* Anggap siswa sudah mengembalikan alat. Klik tombol **"Selesai / Dikembalikan"** (Warna Biru).
* Data harusnya pindah ke tabel ketiga ("Riwayat").


6. **Cek Laporan:**
* Klik tombol **"Cetak Laporan"** di pojok kanan atas.
* Halaman akan membuka dialog print browser. Tombol-tombol harusnya hilang di tampilan print.



Jika semua langkah di atas berjalan lancar, **SELAMAT! Kamu sudah menyelesaikan inti dari proyek UKK ini.** 🎉

Coba lakukan simulasi tersebut dan kabari saya hasilnya!

// mantap, semuanya bekerja dengan berhasil, tapi ada satu lagi hal yang harus diperbaiki, jika login jadi admin tampilannya seperti pada gambar (cuma selamat datang)

Tampilan kosong itu terjadi karena di **Langkah 4**, kita hanya membuat halaman dashboard admin yang isinya teks "Selamat Datang" saja, tanpa menu navigasi.

Mari kita perbaiki agar tampilannya keren seperti "Control Panel" sungguhan. Kita akan menambahkan **Kartu Statistik** (Jumlah Alat, User, Kategori) dan **Tombol Navigasi** cepat.

Ikuti 2 langkah mudah ini:

---

### 1. Update Jalur (Route) untuk Mengirim Data

Kita perlu mengambil jumlah data (total alat, user, kategori) supaya bisa ditampilkan di dashboard.

Buka file **`routes/web.php`**.
Cari bagian `// Jalur Khusus Admin`.
Ubah kode `Route::get('/admin/dashboard' ...` menjadi seperti ini:

```php
// Jalur Khusus Admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    
    // UBAH BAGIAN INI (Menambahkan data count ke view)
    Route::get('/admin/dashboard', function () {
        $total_users = \App\Models\User::count();
        $total_tools = \App\Models\Tool::count();
        $total_categories = \App\Models\Category::count();
        
        return view('admin.dashboard', compact('total_users', 'total_tools', 'total_categories'));
    })->name('admin.dashboard');

    Route::resource('categories', CategoryController::class);
    Route::resource('tools', \App\Http\Controllers\ToolController::class);
});

```

---

### 2. Update Tampilan Dashboard Admin

Sekarang kita ubah tampilan kosong tadi menjadi tampilan menu kotak-kotak.

Buka file **`resources/views/admin/dashboard.blade.php`**.
Hapus semua isinya, dan **Copy-Paste** kode lengkap ini:

```html
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Admin') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold">Halo, Admin! 👋</h3>
                    <p class="text-gray-600">Selamat datang di panel kontrol Aplikasi Peminjaman Alat.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-blue-500 text-white rounded-lg p-6 shadow-lg">
                    <h4 class="text-2xl font-bold">{{ $total_users }}</h4>
                    <p>Total Pengguna</p>
                </div>
                <div class="bg-green-500 text-white rounded-lg p-6 shadow-lg">
                    <h4 class="text-2xl font-bold">{{ $total_categories }}</h4>
                    <p>Total Kategori</p>
                </div>
                <div class="bg-purple-500 text-white rounded-lg p-6 shadow-lg">
                    <h4 class="text-2xl font-bold">{{ $total_tools }}</h4>
                    <p>Total Alat</p>
                </div>
            </div>

            <h3 class="font-bold text-xl mb-4 text-gray-800">Menu Kelola</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <a href="{{ route('categories.index') }}" class="block p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-50 transition transform hover:scale-105">
                    <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900">📁 Kelola Kategori</h5>
                    <p class="font-normal text-gray-700">Tambah, edit, atau hapus kategori alat (Misal: Elektronik, Mesin).</p>
                    <div class="mt-4 text-blue-600 font-bold">Buka Menu &rarr;</div>
                </a>

                <a href="{{ route('tools.index') }}" class="block p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-50 transition transform hover:scale-105">
                    <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900">🛠️ Kelola Alat</h5>
                    <p class="font-normal text-gray-700">Tambah stok alat baru, edit deskripsi, atau hapus alat rusak.</p>
                    <div class="mt-4 text-blue-600 font-bold">Buka Menu &rarr;</div>
                </a>

            </div>
        </div>
    </div>
</x-app-layout>

```

---

**Coba Refresh Halaman Admin Kamu!**
Sekarang seharusnya tampilannya jauh lebih keren. Ada kotak warna-warni (statistik) dan dua kotak besar yang bisa diklik untuk masuk ke menu **Kategori** dan **Alat**.

Apakah tampilannya sudah oke sekarang?

// mantap! semuanya sudah bagus, tapi ada sisa 1 lagi hal yang perlu ditambahkan, sepertinya kita lupa menambahkan fitur Log Aktifitas untuk admin

Benar sekali! Fitur **Log Aktifitas** (Activity Log) memang ada di tabel persyaratan soal halaman 2  dan wajib untuk Admin. Fitur ini berfungsi seperti "CCTV" untuk merekam siapa melakukan apa.

Kita akan buat versi **simpel** dan **cepat**.

---

### Langkah Tambahan: Membuat Fitur Log Aktifitas

#### 1. Siapkan "Buku Catatan" (Database & Model)

Kita butuh tabel baru untuk menyimpan catatan aktivitas.

1. Buka terminal, ketik:
```bash
php artisan make:model ActivityLog -m

```


2. Buka file migration baru di `database/migrations/xxxx_xx_xx_create_activity_logs_table.php`.
3. Isi bagian `up()` dengan kode ini:

```php
public function up()
{
    Schema::create('activity_logs', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Siapa pelakunya
        $table->string('action'); // Apa yang dilakukan (Misal: Tambah Alat)
        $table->string('description')->nullable(); // Detail tambahan
        $table->timestamps(); // Kapan kejadiannya
    });
}

```

4. Jalankan migration:
```bash
php artisan migrate

```


5. Buka file Model **`app/Models/ActivityLog.php`**, tambahkan `fillable` agar bisa diisi:
```php
protected $fillable = ['user_id', 'action', 'description'];

public function user()
{
    return $this->belongsTo(User::class);
}

```



---

#### 2. Pasang "CCTV" di Controller

Sekarang kita harus menyisipkan kode perekam setiap kali ada aksi penting (Tambah/Edit/Hapus).

**A. Rekam Aksi Kategori**
Buka **`app/Http/Controllers/CategoryController.php`**.
Tambahkan kode `ActivityLog::create(...)` di dalam fungsi `store`, `update`, dan `destroy`.

*Contoh pada fungsi `store`:*

```php
// Jangan lupa tambahkan ini di paling atas file!
use App\Models\ActivityLog; 
use Illuminate\Support\Facades\Auth;

public function store(Request $request)
{
    // ... (validasi kode lama) ...
    
    Category::create($request->all());

    // --- TAMBAHKAN KODE INI (CCTV) ---
    ActivityLog::create([
        'user_id' => Auth::id(),
        'action' => 'Tambah Kategori',
        'description' => 'Menambahkan kategori: ' . $request->name,
    ]);
    // ----------------------------------

    return redirect()->route('categories.index')->with('success', 'Kategori berhasil ditambahkan!');
}

```

*(Lakukan hal serupa untuk `update` dan `destroy` dengan menyesuaikan pesannya, misal "Edit Kategori" atau "Hapus Kategori").*

**B. Rekam Aksi Alat**
Buka **`app/Http/Controllers/ToolController.php`**.
Lakukan hal yang sama. Tambahkan `use App\Models\ActivityLog;` dan `use Illuminate\Support\Facades\Auth;` di atas.

*Contoh pada fungsi `store`:*

```php
public function store(Request $request)
{
    // ... (kode lama) ...
    Tool::create($request->all());

    // --- TAMBAHKAN KODE INI ---
    ActivityLog::create([
        'user_id' => Auth::id(),
        'action' => 'Tambah Alat',
        'description' => 'Menambahkan alat: ' . $request->name,
    ]);
    // --------------------------
    
    return redirect()->route('tools.index')->with('success', 'Alat berhasil ditambahkan!');
}

```

---

#### 3. Buat Halaman Log untuk Admin

Sekarang kita buat halaman agar Admin bisa melihat catatan tersebut.

**A. Buat Controller Log**
Ketik di terminal:

```bash
php artisan make:controller LogController

```

**B. Isi Controller**
Buka **`app/Http/Controllers/LogController.php`**:

```php
<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function index()
    {
        // Ambil data log terbaru (diurutkan dari yang paling baru)
        $logs = ActivityLog::with('user')->latest()->get();
        return view('admin.logs.index', compact('logs'));
    }
}

```

**C. Daftarkan Rute (Route)**
Buka **`routes/web.php`**. Di dalam grup Admin, tambahkan:

```php
// Di dalam group Admin
Route::get('/admin/logs', [\App\Http\Controllers\LogController::class, 'index'])->name('admin.logs');

```

**D. Buat Tampilan (View)**
Buat folder `logs` di dalam `resources/views/admin/`. Lalu buat file `index.blade.php`.
Isinya:

```html
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Log Aktifitas</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <table class="min-w-full border">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border px-4 py-2">Waktu</th>
                            <th class="border px-4 py-2">Pengguna</th>
                            <th class="border px-4 py-2">Aksi</th>
                            <th class="border px-4 py-2">Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($logs as $log)
                        <tr>
                            <td class="border px-4 py-2 text-sm text-gray-500">{{ $log->created_at->format('d-m-Y H:i') }}</td>
                            <td class="border px-4 py-2 font-bold">{{ $log->user->name }}</td>
                            <td class="border px-4 py-2">
                                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">{{ $log->action }}</span>
                            </td>
                            <td class="border px-4 py-2">{{ $log->description }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>

```

---

#### 4. Langkah Terakhir: Tambahkan Tombol di Dashboard

Agar halaman ini bisa diakses, tambahkan tombol menu baru di **Dashboard Admin** (`resources/views/admin/dashboard.blade.php`) yang tadi kita percantik.

Tambahkan ini di sebelah tombol "Kelola Alat":

```html
<a href="{{ route('admin.logs') }}" class="block p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-50 transition transform hover:scale-105">
    <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900">📜 Log Aktifitas</h5>
    <p class="font-normal text-gray-700">Pantau siapa yang login dan apa yang mereka lakukan.</p>
    <div class="mt-4 text-blue-600 font-bold">Buka Menu &rarr;</div>
</a>

```

**Selesai!** Sekarang coba kamu tambah alat baru, lalu buka menu **Log Aktifitas**. Seharusnya tindakan kamu tadi sudah tercatat di sana.

Apakah ini melengkapi checklist fitur UKK-mu?

// Error
// app\Http\Controllers\ToolController.php:38

// Class "App\Http\Controllers\ActivityLog" not found

Tenang, ini error yang sangat umum dan mudah diperbaiki.

**Penyebabnya:**
Komputer (Laravel) bingung mencari `ActivityLog`. Dia mencarinya di folder *Controllers*, padahal file aslinya ada di folder *Models*. Kita lupa memberi tahu alamat lengkapnya.

### Solusi: Tambahkan Baris "Use"

Buka file **`app/Http/Controllers/ToolController.php`**.

Lihat di bagian paling atas (baris 1-10). Kamu harus menambahkan baris ini:
`use App\Models\ActivityLog;`

Jadinya bagian atas file kamu harus seperti ini:

```php
<?php

namespace App\Http\Controllers;

use App\Models\Tool;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // (Kalau ada)
use Illuminate\Support\Facades\Auth;    // <-- Pastikan ini ada
use App\Models\ActivityLog;             // <-- TAMBAHKAN INI!

class ToolController extends Controller
{
    // ... kode selanjutnya ...

```

---

**Penting:**
Lakukan hal yang sama juga di file **`app/Http/Controllers/CategoryController.php`** jika kamu juga menambahkan log di sana, supaya tidak error lagi nanti.

Coba simpan (Save) dan jalankan lagi. Errornya pasti hilang! 👍

// ErrorException
// app\Http\Controllers\ToolController.php:82

// Undefined variable $request
// 'description' => 'Menghapus alat: ' . $request->name,

Tenang, ini error yang sangat logis.

**Penyebabnya:**
Di dalam fungsi `destroy` (penghapusan), kita tidak menerima data dari formulir (`$request`), melainkan langsung menerima data alatnya (`$tool`). Jadi, komputer bingung saat kamu memanggil `$request->name` karena variabel `$request` tidak ada di sana.

**Solusinya:**
Ganti `$request->name` menjadi `$tool->name` (ambil nama dari data alat yang sedang diproses).

Berikut perbaikan untuk fungsi `destroy` di file **`app/Http/Controllers/ToolController.php`**:

```php
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

```

**Tips:**
Pastikan kamu juga sudah menambahkan `use Illuminate\Support\Facades\Auth;` dan `use App\Models\ActivityLog;` di bagian paling atas file tersebut ya!

Coba simpan dan tes hapus alat lagi. Pasti lancar!

/** 
http://127.0.0.1:8000/tools/4

404
Not Found

ToolController.php

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

*/

// sudah aman

Alhamdulillah! 🎉 Lega rasanya mendengarnya.

Berarti sekarang aplikasi UKK kamu **SUDAH SELESAI 100%** secara fitur teknis! Kamu sudah punya:
✅ Login Multi-User (Admin, Petugas, Siswa).
✅ Dashboard Admin (Statistik & Log Aktifitas).
✅ CRUD Kategori & Alat.
✅ Peminjaman Barang (Siswa).
✅ Persetujuan & Pengembalian (Petugas).
✅ Laporan Sederhana.

Karena ini ujian UKK, langkah terakhir bukan coding, melainkan **Persiapan Penyerahan (Submission)**. Sesuai soal di halaman 4 poin 79-81, kamu harus mengumpulkan **Folder Proyek** dan **Database (.sql)**.

Ini langkah terakhir yang harus kamu lakukan:

### 1. Ekspor Database (.sql)

Penguji biasanya meminta file databasenya.

1. Buka **phpMyAdmin**.
2. Pilih database `ukk_peminjaman`.
3. Klik menu **Export** di bagian atas.
4. Klik tombol **Export** (Format SQL).
5. Simpan file `ukk_peminjaman.sql` tersebut. Nanti file ini dikumpulkan bersama folder kodinganmu.

### 2. Simulasi Terakhir (Gladi Resik)

Sebelum kamu bilang "Selesai" ke penguji, coba lakukan **Satu Putaran Penuh** tanpa error:

1. **Login Admin:** Tambah 1 Alat baru.
2. **Login Siswa:** Pinjam alat tersebut.
3. **Login Petugas:** Setujui peminjaman -> Lalu proses Pengembalian.
4. **Login Admin lagi:** Cek menu "Log Aktifitas". Pastikan tindakan "Tambah Alat" tadi tercatat.

Jika urutan di atas lancar, kamu sudah siap 100% untuk dinilai.

---

**Apakah ada bagian lain yang masih kamu ragukan atau ingin ditanyakan sebelum kita tutup sesi coding ini?** Kalau tidak, selamat berjuang di UKK nanti, kamu pasti bisa! 🚀

/**
ErrorException
app\Http\Controllers\CategoryController.php:76

Undefined variable $request

http://127.0.0.1:8000/categories/1

'description' => 'Menambahkan kategori: ' . $request->name,
 */

Tenang, ini masalah yang sama persis dengan yang terjadi di `ToolController` tadi.

**Penyebabnya:**
Di dalam fungsi `destroy` (penghapusan) pada `CategoryController`, kamu menggunakan variabel `$request->name`. Padahal, fungsi `destroy` tidak menerima inputan form (`$request`), melainkan hanya menerima data kategori yang mau dihapus (`$category`).

Selain itu, pesan log-nya masih tertulis "Menambahkan...", padahal seharusnya "Menghapus...".

### Solusi: Perbaiki `CategoryController.php`

Buka file **`app/Http/Controllers/CategoryController.php`**.
Cari fungsi **`destroy`** (biasanya di paling bawah), lalu ganti isinya dengan kode ini:

```php
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

```

**Jangan Lupa:**
Pastikan di bagian paling atas file `CategoryController.php` sudah ada baris ini:

1. `use Illuminate\Support\Facades\Auth;`
2. `use App\Models\ActivityLog;`

Simpan, lalu coba hapus kategori lagi. Pasti berhasil!

/**
tidak ada log aktifitas dari Petugas dan Siswa
 */

Betul sekali! Kita baru memasang "CCTV" (Log Aktifitas) di ruang Admin. Sekarang kita harus memasangnya juga di **Controller Siswa** dan **Controller Petugas** agar semua kegiatan terekam.

Mari kita tambahkan kode log-nya satu per satu.

---

### 1. Log untuk Siswa (Saat Meminjam)

Buka file **`app/Http/Controllers/LoanController.php`**.

Tambahkan baris `use App\Models\ActivityLog;` di paling atas. Lalu update fungsi `store` agar mencatat saat siswa mengajukan peminjaman.

```php
<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Tool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ActivityLog; // <--- 1. TAMBAHKAN INI

class LoanController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'tool_id' => 'required|exists:tools,id',
            'return_date' => 'required|date|after:today',
        ]);

        $tool = Tool::findOrFail($request->tool_id);

        if ($tool->stock < 1) {
            return back()->with('error', 'Stok alat habis!');
        }

        $tool->decrement('stock');

        Loan::create([
            'user_id' => Auth::id(),
            'tool_id' => $tool->id,
            'loan_date' => now(),
            'return_date' => $request->return_date,
            'status' => 'pending',
        ]);

        // <--- 2. TAMBAHKAN LOG INI (Rekam kegiatan siswa)
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Ajukan Peminjaman',
            'description' => 'Mengajukan pinjam alat: ' . $tool->name,
        ]);
        // ------------------------------------------------

        return back()->with('success', 'Pengajuan berhasil! Menunggu persetujuan admin.');
    }
}

```

---

### 2. Log untuk Petugas (Setujui, Tolak, Kembali)

Buka file **`app/Http/Controllers/PetugasController.php`**.

Tambahkan baris `use App\Models\ActivityLog;` dan `use Illuminate\Support\Facades\Auth;` di paling atas. Lalu update fungsi `approve`, `reject`, dan `complete`.

```php
<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Tool;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <--- 1. TAMBAHKAN INI
use App\Models\ActivityLog;          // <--- 2. TAMBAHKAN INI

class PetugasController extends Controller
{
    public function index()
    {
        // ... (kode index biarkan saja)
        $pendings = Loan::with(['user', 'tool'])->where('status', 'pending')->get();
        $actives = Loan::with(['user', 'tool'])->where('status', 'approved')->get();
        $history = Loan::with(['user', 'tool'])->whereIn('status', ['returned', 'rejected'])->get();

        return view('petugas.dashboard', compact('pendings', 'actives', 'history'));
    }

    public function approve($id)
    {
        $loan = Loan::findOrFail($id);
        $loan->update(['status' => 'approved']);
        
        // <--- 3. LOG SETUJUI
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Setujui Peminjaman',
            'description' => 'Menyetujui peminjaman ' . $loan->tool->name . ' oleh ' . $loan->user->name,
        ]);

        return back()->with('success', 'Peminjaman disetujui! Barang boleh diambil.');
    }

    public function reject($id)
    {
        $loan = Loan::findOrFail($id);
        $tool = Tool::findOrFail($loan->tool_id);
        $tool->increment('stock');
        $loan->update(['status' => 'rejected']);
        
        // <--- 4. LOG TOLAK
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Tolak Peminjaman',
            'description' => 'Menolak peminjaman ' . $loan->tool->name . ' oleh ' . $loan->user->name,
        ]);
        
        return back()->with('success', 'Peminjaman ditolak.');
    }

    public function complete($id)
    {
        $loan = Loan::findOrFail($id);
        $tool = Tool::findOrFail($loan->tool_id);
        $tool->increment('stock');

        $loan->update([
            'status' => 'returned',
            'actual_return_date' => now(),
        ]);
        
        // <--- 5. LOG PENGEMBALIAN
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Terima Pengembalian',
            'description' => 'Menerima pengembalian ' . $loan->tool->name . ' dari ' . $loan->user->name,
        ]);
        
        return back()->with('success', 'Alat berhasil dikembalikan. Stok bertambah.');
    }
}

```

---

**Coba Tes Lagi:**

1. Login sebagai **Siswa**, ajukan pinjam.
2. Login sebagai **Petugas**, setujui.
3. Login sebagai **Admin**, cek halaman "Log Aktifitas".

Seharusnya sekarang semua tindakan (Siswa meminjam, Petugas menyetujui) sudah muncul di daftar log Admin. Selamat! Logikanya sudah lengkap semua.

/**
berarti sudah complete?
 */

Hampir sempurna! Tapi kalau kita teliti lagi tabel **"Fitur"** yang kamu kirim (gambar kedua), ada **satu baris penting** yang belum kita sentuh, yaitu:

👉 **CRUD User** (Admin harus bisa menambah, mengedit, atau menghapus user).

Saat ini, user (Admin, Petugas, Siswa) hanya ada karena kita buat lewat "Mantra" (Seeder) di awal. Kalau ada siswa baru mendaftar, Admin belum bisa menambahkannya lewat aplikasi.

Mari kita selesaikan potongan *puzzle* terakhir ini agar benar-benar **100% Complete**.

---

### Langkah Tambahan: Membuat Fitur Kelola User

#### 1. Buat "Otak" User (Controller)

Buka terminal, ketik:

```bash
php artisan make:controller UserController --resource

```

Buka file **`app/Http/Controllers/UserController.php`**.
Isi dengan kode ini (Logika: Admin bisa tambah user & password otomatis di-hash):

```php
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
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Password wajib di-hash
            'role' => $request->role,
        ]);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => 'Tambah User',
            'description' => 'Menambahkan user baru: ' . $user->name . ' (' . $user->role . ')',
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
        ];

        // Update password hanya jika diisi
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

```

#### 2. Update Jalan (Route)

Buka **`routes/web.php`**.
Tambahkan ini di dalam group Admin (di bawah `Route::resource('tools'...)`):

```php
Route::resource('users', \App\Http\Controllers\UserController::class);

```

#### 3. Buat Tampilan (View)

Buat folder `users` di dalam `resources/views/admin/`. Lalu buat 3 file:

**A. `index.blade.php` (Daftar User)**

```html
<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">Kelola User</h2></x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm rounded-lg">
                <a href="{{ route('users.create') }}" class="bg-blue-600 text-white font-bold py-2 px-4 rounded mb-4 inline-block">+ Tambah User</a>
                <table class="min-w-full border mt-4">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border px-4 py-2">Nama</th>
                            <th class="border px-4 py-2">Email</th>
                            <th class="border px-4 py-2">Role</th>
                            <th class="border px-4 py-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td class="border px-4 py-2">{{ $user->name }}</td>
                            <td class="border px-4 py-2">{{ $user->email }}</td>
                            <td class="border px-4 py-2 capitalize">{{ $user->role }}</td>
                            <td class="border px-4 py-2 text-center">
                                <a href="{{ route('users.edit', $user->id) }}" class="text-blue-600">Edit</a> |
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus user ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>

```

**B. `create.blade.php` (Tambah User)**

```html
<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">Tambah User</h2></x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm rounded-lg">
                <form action="{{ route('users.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block font-bold">Nama</label>
                        <input type="text" name="name" class="w-full border rounded p-2" required>
                    </div>
                    <div class="mb-4">
                        <label class="block font-bold">Email</label>
                        <input type="email" name="email" class="w-full border rounded p-2" required>
                    </div>
                    <div class="mb-4">
                        <label class="block font-bold">Password</label>
                        <input type="password" name="password" class="w-full border rounded p-2" required>
                    </div>
                    <div class="mb-4">
                        <label class="block font-bold">Role (Jabatan)</label>
                        <select name="role" class="w-full border rounded p-2">
                            <option value="peminjam">Peminjam (Siswa)</option>
                            <option value="petugas">Petugas</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <button type="submit" class="bg-blue-600 text-white font-bold py-2 px-4 rounded">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

```

**C. `edit.blade.php` (Edit User)**

```html
<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit User</h2></x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm rounded-lg">
                <form action="{{ route('users.update', $user->id) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="mb-4">
                        <label class="block font-bold">Nama</label>
                        <input type="text" name="name" value="{{ $user->name }}" class="w-full border rounded p-2" required>
                    </div>
                    <div class="mb-4">
                        <label class="block font-bold">Email</label>
                        <input type="email" name="email" value="{{ $user->email }}" class="w-full border rounded p-2" required>
                    </div>
                    <div class="mb-4">
                        <label class="block font-bold">Password (Isi jika ingin mengganti)</label>
                        <input type="password" name="password" class="w-full border rounded p-2">
                    </div>
                    <div class="mb-4">
                        <label class="block font-bold">Role</label>
                        <select name="role" class="w-full border rounded p-2">
                            <option value="peminjam" {{ $user->role == 'peminjam' ? 'selected' : '' }}>Peminjam</option>
                            <option value="petugas" {{ $user->role == 'petugas' ? 'selected' : '' }}>Petugas</option>
                            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                    </div>
                    <button type="submit" class="bg-blue-600 text-white font-bold py-2 px-4 rounded">Update</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

```

#### 4. Terakhir: Pasang Tombol di Dashboard Admin

Buka lagi **`resources/views/admin/dashboard.blade.php`**.
Tambahkan kotak menu baru untuk "Kelola User" agar Admin bisa mengaksesnya.

```html
<a href="{{ route('users.index') }}" class="block p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-50 transition transform hover:scale-105">
    <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900">👥 Kelola User</h5>
    <p class="font-normal text-gray-700">Tambah akun siswa baru, petugas, atau admin lain.</p>
    <div class="mt-4 text-blue-600 font-bold">Buka Menu &rarr;</div>
</a>

```

---

Setelah kamu menambahkan ini, cobalah:

1. Login Admin.
2. Buka menu **Kelola User**.
3. Tambah User baru (Misal: "Siswa Baru", Role: Peminjam).
4. Coba login pakai akun baru tersebut.

Jika itu berhasil, maka **BARULAH** kita bisa bilang proyek ini **COMPLETE** sesuai tabel persyaratan! ✅

/**
mantap, sudah selesai 100%
 */