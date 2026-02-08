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