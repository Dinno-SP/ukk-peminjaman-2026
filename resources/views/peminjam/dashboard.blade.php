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

            {{-- DAFTAR ALAT (KATALOG) --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-4 text-gray-800">🛠️ Pilih Alat untuk Dipinjam</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @forelse($tools as $tool)
                    <div class="border rounded-lg p-4 shadow hover:shadow-md transition bg-gray-50 flex flex-col justify-between">
                        <div>
                            <h4 class="text-xl font-bold text-indigo-700">{{ $tool->name }}</h4>
                            <p class="text-gray-600 mb-2 text-sm font-semibold">Stok Tersedia: {{ $tool->stock }}</p>
                            <p class="text-sm text-gray-500 mb-4">{{ $tool->description ?? 'Tidak ada deskripsi.' }}</p>
                        </div>
                        
                        <form action="{{ route('loan.store') }}" method="POST" class="mt-2">
                            @csrf
                            <input type="hidden" name="tool_id" value="{{ $tool->id }}">
                            
                            <div class="mb-3">
                                <label class="text-xs font-bold text-gray-700 block mb-1">Rencana Kembali:</label>
                                <input type="date" name="return_date" class="border rounded w-full text-sm p-2 focus:ring focus:ring-indigo-200" required>
                            </div>
                            
                            <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded hover:bg-indigo-700 text-sm font-bold transition">
                                Ajukan Pinjam
                            </button>
                        </form>
                    </div>
                    @empty
                    <div class="col-span-3 text-center py-4 text-gray-500">
                        Maaf, saat ini belum ada alat yang tersedia.
                    </div>
                    @endforelse
                </div>
            </div>

            {{-- STATUS PEMINJAMAN SAYA --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-4 text-gray-800">📋 Status Peminjaman Saya</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full border border-gray-200">
                        <thead>
                            <tr class="bg-gray-100 text-left text-gray-600 uppercase text-sm leading-normal">
                                <th class="border px-4 py-3 text-center">Alat</th>
                                <th class="border px-4 py-3 text-center">Tanggal Dipinjam</th>
                                <th class="border px-4 py-3 text-center">Rencana Kembali</th>
                                <th class="border px-4 py-3 text-center">Status</th>
                                <th class="border px-4 py-3 text-center">Denda</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 text-sm font-light">
                            @forelse($loans as $loan)
                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="border px-4 py-3 font-medium">{{ $loan->tool->name }}</td>
                                
                                {{-- FORMAT TANGGAL INDONESIA (d-m-Y) --}}
                                <td class="border px-4 py-3 text-center">
                                    {{ \Carbon\Carbon::parse($loan->loan_date)->format('d-m-Y') }}
                                </td>
                                <td class="border px-4 py-3 text-center">
                                    {{ \Carbon\Carbon::parse($loan->return_date)->format('d-m-Y') }}
                                </td>
                                
                                <td class="border px-4 py-3 text-center">
                                    @if($loan->status == 'pending')
                                        <span class="bg-yellow-200 text-yellow-800 py-1 px-3 rounded-full text-xs font-bold">Menunggu</span>
                                    
                                    @elseif($loan->status == 'approved')
                                        <div class="space-y-2">
                                            <span class="bg-blue-200 text-blue-800 py-1 px-3 rounded-full text-xs font-bold">Dipinjam</span>
                                            
                                            {{-- TOMBOL KEMBALIKAN (Hanya muncul saat dipinjam) --}}
                                            <form action="{{ route('loan.return', $loan->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="bg-indigo-600 hover:bg-indigo-800 text-white text-xs font-bold py-1 px-2 rounded-full" onclick="return confirm('Apakah Anda yakin ingin mengembalikan alat ini? Pastikan membawa alat ke petugas.')">
                                                    Kembalikan
                                                </button>
                                            </form>
                                        </div>

                                    @elseif($loan->status == 'returning')
                                        <span class="bg-orange-200 text-orange-800 py-1 px-3 rounded-full text-xs font-bold">Proses Kembali</span>
                                        <p class="text-xs text-gray-500 mt-1">Menunggu konfirmasi petugas</p>

                                    @elseif($loan->status == 'returned')
                                        <span class="bg-green-200 text-green-800 py-1 px-3 rounded-full text-xs font-bold">Selesai</span>

                                    @else
                                        <span class="bg-red-200 text-red-800 py-1 px-3 rounded-full text-xs font-bold">Ditolak</span>
                                    @endif
                                </td>

                                {{-- FITUR PLUS: Siswa bisa lihat kalau dia kena denda --}}
                                <td class="border px-4 py-3 text-center">
                                    @if($loan->fine > 0)
                                        <span class="text-red-600 font-bold">Rp {{ number_format(abs($loan->fine), 0, ',', '.') }}</span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-gray-500">Anda belum pernah meminjam alat.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>