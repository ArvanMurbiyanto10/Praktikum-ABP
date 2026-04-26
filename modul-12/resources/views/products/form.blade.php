@extends('template')

@section('title', 'Form ' . $title . ' Produk')

@section('content')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-6">

                {{-- HEADER --}}
                <h4 class="mb-3">{{ $title }} Produk</h4>

                <div class="card border-0 shadow-sm">
                    <div class="card-body">

                        <form method="POST" action="{{ $route }}">
                            @csrf

                            @if ($method === 'PUT')
                                @method('PUT')
                            @endif

                            {{-- NAMA --}}
                            <div class="mb-3">
                                <label class="form-label">Nama Produk</label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $product->name) }}">
                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- HARGA --}}
                            <div class="mb-3">
                                <label class="form-label">Harga</label>
                                <input type="number" name="price" class="form-control @error('price') is-invalid @enderror"
                                    value="{{ old('price', $product->price) }}">
                                @error('price')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- BUTTON --}}
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary btn-sm">
                                    Kembali
                                </a>

                                <button type="submit" class="btn btn-success btn-sm">
                                    {{ $title == 'Tambah' ? 'Simpan' : 'Update' }}
                                </button>
                            </div>

                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection