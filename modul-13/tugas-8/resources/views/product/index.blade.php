<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Stok Suku Cadang Honda') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold">Daftar Suku Cadang</h3>
                        <a href="{{ route('product.create') }}" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded transition">
                            + Tambah Suku Cadang
                        </a>
                    </div>

                    @if(session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200">
                            <thead>
                                <tr class="bg-gray-100 border-b">
                                    <th class="py-3 px-4 text-left font-semibold text-gray-700">Gambar</th>
                                    <th class="py-3 px-4 text-left font-semibold text-gray-700">Part Number</th>
                                    <th class="py-3 px-4 text-left font-semibold text-gray-700">Nama Barang</th>
                                    <th class="py-3 px-4 text-left font-semibold text-gray-700">Kategori</th>
                                    <th class="py-3 px-4 text-left font-semibold text-gray-700">Harga</th>
                                    <th class="py-3 px-4 text-left font-semibold text-gray-700">Stok</th>
                                    <th class="py-3 px-4 text-center font-semibold text-gray-700">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products as $product)
                                    <tr class="border-b hover:bg-gray-50 transition">
                                        <td class="py-3 px-4">
                                            <div class="w-12 h-12 rounded-lg flex items-center justify-center bg-gray-100 text-gray-500">
                                                @if($product->category == 'Mesin')
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12a7.5 7.5 0 0 0 15 0m-15 0a7.5 7.5 0 1 1 15 0m-15 0H3m16.5 0H21m-1.5 0H12m-8.457 3.077 1.41l1.409-1.409m11.192 0 1.409 1.409M9.172 9.172 7.763 7.764m11.191 1.408 1.41-1.41M12 12v3.75m-3.75-3.75v.008H12" />
                                                    </svg>
                                                @elseif($product->category == 'Oli & Pelumas')
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7 text-blue-600">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m.75 12 3 3m0 0 3-3m-3 3v-6m-1.5-9H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                                    </svg>
                                                @elseif($product->category == 'Pengereman')
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7 text-red-600">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                                                    </svg>
                                                @elseif($product->category == 'Kelistrikan')
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7 text-yellow-500">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="m3.75 13.5 10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75Z" />
                                                    </svg>
                                                @else
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-5.25v9" />
                                                    </svg>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="py-3 px-4 font-mono text-sm">{{ $product->part_number }}</td>
                                        <td class="py-3 px-4 font-bold">{{ $product->name }}</td>
                                        <td class="py-3 px-4">
                                            <span class="px-2 py-1 bg-gray-200 rounded-full text-xs font-semibold">{{ $product->category }}</span>
                                        </td>
                                        <td class="py-3 px-4 text-red-600 font-bold">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                        <td class="py-3 px-4">
                                            <span class="{{ $product->stock <= 5 ? 'text-red-500 font-bold' : 'text-gray-700' }}">
                                                {{ $product->stock }} pcs
                                            </span>
                                        </td>
                                        <td class="py-3 px-4 text-center">
                                            <div class="flex justify-center space-x-2">
                                                <a href="{{ route('product.edit', $product->id) }}" class="text-blue-600 hover:text-blue-900 font-semibold text-sm">Edit</a>
                                                <form action="{{ route('product.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900 font-semibold text-sm">Hapus</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="py-10 text-center text-gray-500 italic">Belum ada data suku cadang.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
