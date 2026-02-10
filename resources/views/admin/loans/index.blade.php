<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kelola Data Peminjaman') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    {{-- Pesan Sukses --}}
                    @if(session('success'))
                        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-4 py-2 text-left">Peminjam</th>
                                    <th class="px-4 py-2 text-left">Alat</th>
                                    <th class="px-4 py-2 text-left">Tanggal dipinjam</th>
                                    <th class="px-4 py-2 text-left">Rencana Kembali</th>
                                    <th class="px-4 py-2 text-left">Status</th>
                                    <th class="px-4 py-2 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($loans as $loan)
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="px-4 py-2">{{ $loan->user->name }}</td>
                                    <td class="px-4 py-2">{{ $loan->tool->name }}</td>
                                    <td class="px-4 py-2">
                                        {{ \Carbon\Carbon::parse($loan->loan_date)->format('d-m-Y') }}
                                    </td>
                                    <td class="px-4 py-2">
                                        {{ \Carbon\Carbon::parse($loan->return_date)->format('d-m-Y') }}
                                    </td>
                                    <td class="px-4 py-2">
                                        @if($loan->status == 'pending')
                                            <span class="bg-yellow-200 text-yellow-800 py-1 px-3 rounded-full text-xs">Menunggu</span>
                                        @elseif($loan->status == 'approved')
                                            <span class="bg-blue-200 text-blue-800 py-1 px-3 rounded-full text-xs">Dipinjam</span>
                                        @elseif($loan->status == 'returned')
                                            <span class="bg-green-200 text-green-800 py-1 px-3 rounded-full text-xs">Kembali</span>
                                        @else
                                            <span class="bg-red-200 text-red-800 py-1 px-3 rounded-full text-xs">Ditolak</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-2 text-center">
                                        <div class="flex justify-center space-x-2">
                                            {{-- Tombol Edit --}}
                                            <a href="{{ route('admin.loans.edit', $loan->id) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded text-sm">
                                                Edit
                                            </a>

                                            {{-- Tombol Hapus --}}
                                            <form action="{{ route('admin.loans.destroy', $loan->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded text-sm">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>