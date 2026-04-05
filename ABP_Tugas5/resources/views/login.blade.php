<!DOCTYPE html>
<html lang="id">

<head>
    <title>Login System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9;
            display: flex;
            align-items: center;
            min-height: 100vh;
        }

        .auth-card {
            max-width: 420px;
            width: 100%;
            margin: auto;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            border: none;
            background: white;
        }

        .auth-header {
            background-color: #7BBDE8;
            /* Warna baru */
            color: white;
            padding: 35px 20px 25px;
            text-align: center;
        }

        .auth-header h2 {
            font-weight: 700;
            margin: 0;
            font-size: 1.8rem;
        }

        .auth-header p {
            margin: 5px 0 0;
            opacity: 0.9;
            font-size: 0.95rem;
        }

        .auth-body {
            padding: 40px 30px;
        }

        .form-control {
            border-radius: 8px;
            padding: 12px 15px;
            border: 1px solid #ced4da;
            background-color: #fdfdfd;
        }

        .form-control:focus {
            border-color: #7BBDE8;
            box-shadow: 0 0 0 0.25rem rgba(123, 189, 232, 0.25);
            background-color: white;
        }

        .form-label {
            font-weight: 600;
            font-size: 0.9rem;
            color: #495057;
        }

        .btn-primary-custom {
            background-color: #7BBDE8;
            /* Warna baru */
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-weight: 600;
            width: 100%;
            transition: 0.3s;
            color: white;
            margin-top: 10px;
        }

        .btn-primary-custom:hover {
            background-color: #62A8D9;
            /* Biru agak gelap saat hover */
            color: white;
            transform: translateY(-1px);
        }

        .link-custom {
            color: #7BBDE8;
            text-decoration: none;
            font-weight: 600;
            transition: 0.2s;
        }

        .link-custom:hover {
            color: #62A8D9;
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="auth-card">
        <div class="auth-header">
            <h2>Welcome Back</h2>
            <p>Silakan login ke akun Anda</p>
        </div>
        <div class="auth-body">
            <form action="/auth" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control" placeholder="nama@email.com" required>
                </div>
                <div class="mb-4">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Masukkan password"
                        required>
                </div>

                @if(session('error'))
                    <div class="alert alert-danger py-2 border-0 shadow-sm text-center" role="alert"
                        style="font-size: 0.9rem; background-color: #ffebee; color: #c62828;">
                        Email atau password salah
                    </div>
                @endif

                <button type="submit" class="btn btn-primary-custom mb-4 shadow-sm">Login</button>

                <div class="text-center mt-2">
                    <span class="text-muted" style="font-size: 0.95rem;">Belum punya akun?</span>
                    <a href="/registration" class="link-custom" style="font-size: 0.95rem;">Daftar sekarang</a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>