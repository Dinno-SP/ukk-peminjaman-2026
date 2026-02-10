<x-app-layout>
    <x-slot name="header">
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Dashboard Petugas') }}
                </h2>
                
                {{-- Tombol Menuju Halaman Laporan --}}
                <a href="{{ route('petugas.laporan') }}" class="bg-indigo-600 hover:bg-indigo-800 text-white font-bold py-2 px-4 rounded shadow-lg flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Lihat Laporan
                </a>
            </div>
        </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-bold mb-4 text-yellow-600">🔔 Permintaan Peminjaman Masuk</h3>
                <table class="min-w-full border">
                    <thead class="bg-yellow-50">
                        <tr>
                            <th class="border px-4 py-2">Peminjam</th>
                            <th class="border px-4 py-2">Alat</th>
                            <th class="border px-4 py-2">Tanggal dipinjam</th>
                            <th class="border px-4 py-2">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendings as $loan)
                        <tr>
                            <td class="border px-4 py-2">{{ $loan->user->name }}</td>
                            <td class="border px-4 py-2">{{ $loan->tool->name }}</td>
                            <td class="border px-4 py-2 text-center">
                                {{ \Carbon\Carbon::parse($loan->loan_date)->format('d-m-Y') }}
                            </td>
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
<td class="border px-4 py-2">
    {{ $loan->user->name }} <br>
    
    {{-- Indikator Status Tambahan --}}
    @if($loan->status == 'returning')
        <span class="text-xs font-bold text-orange-600 animate-pulse">
            (User Minta Pengembalian)
        </span>
    @endif
</td>
                            <td class="border px-4 py-2">{{ $loan->tool->name }}</td>
                            <td class="border px-4 py-2 text-center">
                                {{ \Carbon\Carbon::parse($loan->return_date)->format('d-m-Y') }}
                            </td>
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
                            <th class="border px-4 py-2">Tanggal dikembalikan</th>
                            <th class="border px-4 py-2">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($history as $loan)
                        <tr>
                            <td class="border px-4 py-2">{{ $loan->user->name }}</td>
                            <td class="border px-4 py-2">{{ $loan->tool->name }}</td>
                            <td class="border px-4 py-2 text-center">
                                {{ $loan->actual_return_date ? \Carbon\Carbon::parse($loan->actual_return_date)->format('d-m-Y') : '-' }}
                            </td>
                            <td class="border px-4 py-2 text-center">
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