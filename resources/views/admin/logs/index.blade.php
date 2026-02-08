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