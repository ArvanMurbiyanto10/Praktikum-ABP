<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Honda Spare Parts - Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome Section -->
            <div class="bg-red-600 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-8 text-white">
                    <h1 class="text-3xl font-bold mb-2">Selamat Datang di Sistem Stok Astra Honda!</h1>
                    <p class="text-red-100">Kelola persediaan suku cadang orisinil Honda dengan cepat dan efisien.</p>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white p-6 rounded-lg shadow border-l-4 border-blue-500">
                    <div class="text-sm font-medium text-gray-500 uppercase">Total Suku Cadang</div>
                    <div class="text-2xl font-bold text-gray-800">{{ \App\Models\Product::count() }} Item</div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow border-l-4 border-red-500">
                    <div class="text-sm font-medium text-gray-500 uppercase">Stok Menipis (< 10)</div>
                    <div class="text-2xl font-bold text-red-600">{{ \App\Models\Product::where('stock', '<', 10)->count() }} Item</div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow border-l-4 border-green-500">
                    <div class="text-sm font-medium text-gray-500 uppercase">Kategori</div>
                    <div class="text-2xl font-bold text-gray-800">{{ \App\Models\Product::distinct('category')->count() }} Kategori</div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="font-bold text-lg mb-4">Aksi Cepat</h3>
                    <div class="flex flex-wrap gap-4">
                        <a href="{{ route('product.index') }}" class="bg-gray-800 hover:bg-gray-900 text-white px-6 py-3 rounded-lg transition font-semibold">
                            Lihat Semua Stok
                        </a>
                        <a href="{{ route('product.create') }}" class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg transition font-semibold">
                            Tambah Barang Baru
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
