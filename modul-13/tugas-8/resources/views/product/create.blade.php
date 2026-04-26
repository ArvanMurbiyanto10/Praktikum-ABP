<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Suku Cadang Honda') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('product.store') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Part Number -->
                            <div>
                                <label for="part_number" class="block text-sm font-medium text-gray-700">Part Number</label>
                                <input type="text" name="part_number" id="part_number" value="{{ old('part_number') }}" required 
                                    placeholder="Contoh: 12345-K0W-N01"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                                @error('part_number') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <!-- Name -->
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700">Nama Suku Cadang</label>
                                <input type="text" name="name" id="name" value="{{ old('name') }}" required 
                                    placeholder="Nama barang"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <!-- Category -->
                            <div>
                                <label for="category" class="block text-sm font-medium text-gray-700">Kategori</label>
                                <select name="category" id="category" required 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                                    <option value="">Pilih Kategori</option>
                                    <option value="Mesin" {{ old('category') == 'Mesin' ? 'selected' : '' }}>Mesin</option>
                                    <option value="Transmisi" {{ old('category') == 'Transmisi' ? 'selected' : '' }}>Transmisi</option>
                                    <option value="Kelistrikan" {{ old('category') == 'Kelistrikan' ? 'selected' : '' }}>Kelistrikan</option>
                                    <option value="Pengereman" {{ old('category') == 'Pengereman' ? 'selected' : '' }}>Pengereman</option>
                                    <option value="Body & Chassis" {{ old('category') == 'Body & Chassis' ? 'selected' : '' }}>Body & Chassis</option>
                                    <option value="Oli & Pelumas" {{ old('category') == 'Oli & Pelumas' ? 'selected' : '' }}>Oli & Pelumas</option>
                                </select>
                                @error('category') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <!-- Price -->
                            <div>
                                <label for="price" class="block text-sm font-medium text-gray-700">Harga (Rp)</label>
                                <input type="number" name="price" id="price" value="{{ old('price') }}" required 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                                @error('price') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <!-- Stock -->
                            <div>
                                <label for="stock" class="block text-sm font-medium text-gray-700">Stok Awal</label>
                                <input type="number" name="stock" id="stock" value="{{ old('stock') }}" required 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                                @error('stock') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <!-- Image Link -->
                            <div>
                                <label for="image_url" class="block text-sm font-medium text-gray-700">Link Gambar (URL)</label>
                                <input type="url" name="image_url" id="image_url" value="{{ old('image_url') }}" 
                                    placeholder="https://example.com/image.jpg"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">
                                @error('image_url') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>

                            <!-- Description -->
                            <div class="md:col-span-2">
                                <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi / Spesifikasi</label>
                                <textarea name="description" id="description" rows="3" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500">{{ old('description') }}</textarea>
                                @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end space-x-3">
                            <a href="{{ route('product.index') }}" class="py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50">Batal</a>
                            <button type="submit" class="py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                Simpan Suku Cadang
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
