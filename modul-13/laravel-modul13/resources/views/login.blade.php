@extends('template')
@section('title', 'Login')

@section('content')
    <div class="d-flex justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="col-md-4">
            <div class="p-4" style="border: 1px solid #ddd; border-radius: 12px; background: #f9fafb;">

                <div class="text-center mb-4">
                    <h4 style="font-weight: 600;">Welcome Back</h4>
                    <small class="text-muted">Silakan login untuk melanjutkan</small>
                </div>

                @if (session('msg'))
                    <div class="alert alert-warning text-center">
                        {{ session('msg') }}
                    </div>
                @endif

                <form action="{{ route('login.post') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label small text-muted">Email</label>
                        <input type="email" class="form-control" name="email" placeholder="contoh@email.com" required
                            value="{{ old('email') }}" style="border-radius: 8px;">
                    </div>

                    <div class="mb-3">
                        <label class="form-label small text-muted">Password</label>
                        <input type="password" class="form-control" name="password" placeholder="••••••••" required
                            style="border-radius: 8px;">
                    </div>

                    <button type="submit" class="btn w-100" style="background: #4f46e5; color: white; border-radius: 8px;">
                        Masuk
                    </button>
                </form>

                <div class="text-center mt-3">
                    <small class="text-muted">© Sistem Login Sederhana</small>
                </div>

            </div>
        </div>
    </div>
@endsection