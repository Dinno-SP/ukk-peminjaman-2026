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