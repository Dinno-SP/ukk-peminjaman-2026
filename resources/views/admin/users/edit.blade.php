<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit User</h2></x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm rounded-lg">
                <form action="{{ route('users.update', $user->id) }}" method="POST">
                    @csrf @method('PUT')
                    <div class="mb-4">
                        <label class="block font-bold">Nama</label>
                        <input type="text" name="name" value="{{ $user->name }}" class="w-full border rounded p-2" required>
                    </div>
                    <div class="mb-4">
                        <label class="block font-bold">Email</label>
                        <input type="email" name="email" value="{{ $user->email }}" class="w-full border rounded p-2" required>
                    </div>
                    <div class="mb-4">
                        <label class="block font-bold">Password (Isi jika ingin mengganti)</label>
                        <input type="password" name="password" class="w-full border rounded p-2">
                    </div>
                    <div class="mb-4">
                        <label class="block font-bold">Role</label>
                        <select name="role" class="w-full border rounded p-2">
                            <option value="peminjam" {{ $user->role == 'peminjam' ? 'selected' : '' }}>Peminjam</option>
                            <option value="petugas" {{ $user->role == 'petugas' ? 'selected' : '' }}>Petugas</option>
                            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">NIS (Nomor Induk Sekolah)</label>
                        <input type="text" name="nis" value="{{ old('nis', $user->nis ?? '') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Contoh: 12345678">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Kelas</label>
                        <input type="text" name="class" value="{{ old('class', $user->class ?? '') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Contoh: XII RPL 1">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Nomor Telepon</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone ?? '') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="0812xxxx">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Alamat</label>
                        <textarea name="address" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ old('address', $user->address ?? '') }}</textarea>
                    </div>
                    <button type="submit" class="bg-blue-600 text-white font-bold py-2 px-4 rounded">Update</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>