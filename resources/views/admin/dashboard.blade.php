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
                <a href="{{ route('admin.logs') }}" class="block p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-50 transition transform hover:scale-105">
                <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900">📜 Log Aktifitas</h5>
                <p class="font-normal text-gray-700">Pantau siapa yang login dan apa yang mereka lakukan.</p>
                <div class="mt-4 text-blue-600 font-bold">Buka Menu &rarr;</div>
            </a>
            <a href="{{ route('users.index') }}" class="block p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-50 transition transform hover:scale-105">
            <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900">👥 Kelola User</h5>
            <p class="font-normal text-gray-700">Tambah akun siswa baru, petugas, atau admin lain.</p>
            <div class="mt-4 text-blue-600 font-bold">Buka Menu &rarr;</div>
        </a>
            </div>
        </div>
    </div>
</x-app-layout>