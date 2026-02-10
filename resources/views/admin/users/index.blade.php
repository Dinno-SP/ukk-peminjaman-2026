<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">Kelola User</h2></x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm rounded-lg">
                <a href="{{ route('users.create') }}" class="bg-blue-600 text-white font-bold py-2 px-4 rounded mb-4 inline-block">+ Tambah User</a>
                <table class="min-w-full border mt-4">
                    <thead>
                        <tr>
                            <th class="px-4 py-2">Nama</th>
                            <th class="px-4 py-2">Email</th>
                            <th class="px-4 py-2">Role</th>
                            <th class="px-4 py-2">Info Siswa</th> <th class="px-4 py-2">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td class="px-4 py-2">{{ $user->name }}</td>
                            <td class="px-4 py-2">{{ $user->email }}</td>
                            <td class="px-4 py-2">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $user->role == 'admin' ? 'bg-red-100 text-red-800' : ($user->role == 'petugas' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800') }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="px-4 py-2 text-sm">
                                @if($user->role == 'peminjam')
                                    <strong>NIS:</strong> {{ $user->nis ?? '-' }}<br>
                                    <strong>Kelas:</strong> {{ $user->class ?? '-' }}<br>
                                    <strong>HP:</strong> {{ $user->phone ?? '-' }}
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-2">
                                <a href="{{ route('users.edit', $user->id) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline ml-2" onsubmit="return confirm('Hapus user ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
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