<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Alat</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow-sm rounded-lg">
                <form action="{{ route('tools.update', $tool->id) }}" method="POST">
                    @csrf @method('PUT')
                    
                    <div class="mb-4">
                        <label class="block font-bold mb-2">Nama Alat</label>
                        <input type="text" name="name" value="{{ $tool->name }}" class="w-full border rounded p-2" required>
                    </div>

                    <div class="mb-4">
                        <label class="block font-bold mb-2">Kategori</label>
                        <select name="category_id" class="w-full border rounded p-2">
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ $tool->category_id == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block font-bold mb-2">Stok</label>
                        <input type="number" name="stock" value="{{ $tool->stock }}" class="w-full border rounded p-2" required>
                    </div>

                    <div class="mb-4">
                        <label class="block font-bold mb-2">Deskripsi</label>
                        <textarea name="description" class="w-full border rounded p-2">{{ $tool->description }}</textarea>
                    </div>

                    <button type="submit" class="bg-gray-800 text-white font-bold py-2 px-4 rounded">Update</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>