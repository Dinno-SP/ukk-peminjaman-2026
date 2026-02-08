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