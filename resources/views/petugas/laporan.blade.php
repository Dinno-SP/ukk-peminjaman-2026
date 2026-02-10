<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Laporan Peminjaman') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    {{-- Form Filter Tanggal & Tombol Print --}}
                    <div class="flex justify-between items-center mb-6">
                        <form action="{{ route('petugas.laporan') }}" method="GET" class="flex items-center space-x-2">
                            <div>
                                <label for="date" class="text-sm font-bold text-gray-700">Pilih Tanggal:</label>
                                <input type="date" name="date" id="date" value="{{ $tanggal }}" 
                                    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            </div>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-5">
                                Filter
                            </button>
                            {{-- Tombol Reset (Hapus Filter) --}}
                            <a href="{{ route('petugas.laporan') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mt-5">
                                Reset
                            </a>
                        </form>

                        {{-- Tombol Cetak PDF (Menggunakan fitur print bawaan browser) --}}
                        <button onclick="window.print()" class="bg-green-600 hover:bg-green-800 text-white font-bold py-2 px-4 rounded flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                            Cetak Laporan
                        </button>
                    </div>

                    {{-- Tabel Data --}}
                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto border-collapse border border-gray-200">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="border px-4 py-2 text-center">No</th>
                                    <th class="border px-4 py-2 text-center">Peminjam</th>
                                    <th class="border px-4 py-2 text-center">Alat</th>
                                    <th class="border px-4 py-2 text-center">Tanggal Dipinjam</th>
                                    <th class="border px-4 py-2 text-center">Status</th>
                                    <th class="border px-4 py-2 text-center">Denda</th>
                                    <th class="border px-4 py-2 text-center">Tanggal Dikembalikan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($loans as $index => $loan)
                                <tr class="hover:bg-gray-50 text-center">
                                    <td class="border px-4 py-2">{{ $index + 1 }}</td>
                                    <td class="border px-4 py-2">
                                        {{ $loan->user->name }} <br>
                                        <span class="text-xs text-gray-500">{{ $loan->user->class ?? '' }}</span>
                                    </td>
                                    <td class="border px-4 py-2">{{ $loan->tool->name }}</td>
                                    <td class="border px-4 py-2">
                                        {{ \Carbon\Carbon::parse($loan->loan_date)->format('d-m-Y') }}
                                    </td>
                                    <td class="border px-4 py-2">
                                        @if($loan->status == 'pending')
                                            <span class="bg-yellow-200 text-yellow-800 py-1 px-2 rounded text-xs">Menunggu</span>
                                        @elseif($loan->status == 'approved')
                                            <span class="bg-blue-200 text-blue-800 py-1 px-2 rounded text-xs">Sedang Dipinjam</span>
                                        @elseif($loan->status == 'returned')
                                            <span class="bg-green-200 text-green-800 py-1 px-2 rounded text-xs">Sudah Kembali</span>
                                        @else
                                            <span class="bg-red-200 text-red-800 py-1 px-2 rounded text-xs">Ditolak</span>
                                        @endif
                                    </td>
                                    <td class="border px-4 py-2">
                                        {{-- Kita gunakan abs() untuk mengubah negatif jadi positif --}}
                                        @if(abs($loan->fine) > 0)
                                            <span class="text-red-600 font-bold">Rp {{ number_format(abs($loan->fine), 0, ',', '.') }}</span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="border px-4 py-2">
                                        {{ \Carbon\Carbon::parse($loan->actual_return_date)->format('d-m-Y') }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="border px-4 py-4 text-center text-gray-500">
                                        Tidak ada data peminjaman pada tanggal ini.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
    
    {{-- CSS Khusus Print (Supaya saat diprint layoutnya rapi) --}}
    <style>
        @media print {
            button, a, form {
                display: none !important; /* Sembunyikan tombol saat diprint */
            }
            body {
                background-color: white;
            }
            .shadow-sm, .shadow-lg {
                box-shadow: none !important;
            }
        }
    </style>
</x-app-layout>