<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Data Peminjaman') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <form action="{{ route('admin.loans.update', $loan->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Info Peminjam (Read Only) --}}
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Nama Peminjam</label>
                            <input type="text" value="{{ $loan->user->name }}" readonly class="bg-gray-200 shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>

                        {{-- Info Alat (Read Only) --}}
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Alat yang Dipinjam</label>
                            <input type="text" value="{{ $loan->tool->name }}" readonly class="bg-gray-200 shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>

                        {{-- Tanggal Kembali --}}
                        <div class="mb-4">
                            <label for="return_date" class="block text-gray-700 text-sm font-bold mb-2">Rencana Tanggal Kembali</label>
                            <input type="date" name="return_date" id="return_date" value="{{ $loan->return_date }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>

                        {{-- Status Peminjaman --}}
                        <div class="mb-4">
                            <label for="status" class="block text-gray-700 text-sm font-bold mb-2">Status Peminjaman</label>
                            <select name="status" id="status" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <option value="pending" {{ $loan->status == 'pending' ? 'selected' : '' }}>Pending (Menunggu)</option>
                                <option value="approved" {{ $loan->status == 'approved' ? 'selected' : '' }}>Approved (Sedang Dipinjam)</option>
                                <option value="returned" {{ $loan->status == 'returned' ? 'selected' : '' }}>Returned (Sudah Kembali)</option>
                                <option value="rejected" {{ $loan->status == 'rejected' ? 'selected' : '' }}>Rejected (Ditolak)</option>
                            </select>
                            <p class="text-sm text-red-500 mt-1">*Hati-hati mengubah status secara manual.</p>
                        </div>
                        {{-- TAMBAHAN: FORM PENGEMBALIAN (KHUSUS ADMIN) --}}
                        <div class="p-4 mb-4 bg-gray-100 rounded border border-gray-300">
                            <h3 class="text-lg font-bold mb-2 text-gray-700">Data Pengembalian (Admin Only)</h3>
                            
                            {{-- Input Tanggal Kembali Asli --}}
                            <div class="mb-4">
                                <label for="actual_return_date" class="block text-gray-700 text-sm font-bold mb-2">Tanggal Dikembalikan (Fakta)</label>
                                <input type="date" name="actual_return_date" id="actual_return_date" value="{{ $loan->actual_return_date }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <p class="text-xs text-gray-500">*Isi jika barang sudah dikembalikan.</p>
                            </div>

                            {{-- Input Denda Manual --}}
                            <div class="mb-4">
                                <label for="fine" class="block text-gray-700 text-sm font-bold mb-2">Denda (Rp)</label>
                                <input type="number" name="fine" id="fine" value="{{ $loan->fine }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="0">
                                <p class="text-xs text-gray-500">*Admin bisa mengubah nominal denda secara manual di sini.</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Simpan Perubahan
                            </button>
                            <a href="{{ route('admin.loans.index') }}" class="text-gray-500 hover:text-gray-700">
                                Batal
                            </a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>