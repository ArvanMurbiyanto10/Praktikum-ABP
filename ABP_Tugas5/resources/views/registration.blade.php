<!DOCTYPE html>
<html lang="id">

<head>
    <title>Registration System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9;
            display: flex;
            align-items: center;
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px;
        }

        .auth-wrapper {
            max-width: 900px;
            width: 100%;
            margin: auto;
            background: white;
            border-radius: 24px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            display: flex;
            flex-direction: row;
        }

        .auth-brand {
            background: linear-gradient(135deg, #7BBDE8, #62A8D9);
            color: white;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 50px;
            text-align: center;
        }

        .auth-brand h1 {
            font-weight: 700;
            font-size: 2.2rem;
            margin-bottom: 15px;
        }

        .auth-brand p {
            font-size: 1.05rem;
            opacity: 0.9;
            line-height: 1.6;
        }

        .auth-form-container {
            flex: 1.2;
            padding: 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .auth-form-container h2 {
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 5px;
            font-size: 1.8rem;
        }

        .auth-form-container p {
            color: #6c757d;
            margin-bottom: 35px;
            font-size: 0.95rem;
        }

        .form-control {
            border-radius: 10px;
            padding: 14px 15px;
            border: 1.5px solid #e9ecef;
            background-color: #f8f9fa;
            font-size: 0.95rem;
        }

        .form-control:focus {
            border-color: #7BBDE8;
            box-shadow: 0 0 0 4px rgba(123, 189, 232, 0.15);
            background-color: white;
        }

        .form-label {
            font-weight: 600;
            font-size: 0.85rem;
            color: #495057;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-primary-custom {
            background-color: #7BBDE8;
            border: none;
            padding: 14px;
            border-radius: 10px;
            font-weight: 600;
            width: 100%;
            transition: all 0.3s ease;
            color: white;
            margin-top: 10px;
            font-size: 1rem;
        }

        .btn-primary-custom:hover {
            background-color: #62A8D9;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 15px rgba(123, 189, 232, 0.3);
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

        /* Responsif untuk layar HP */
        @media (max-width: 768px) {
            .auth-wrapper {
                flex-direction: column;
                max-width: 450px;
            }

            .auth-brand {
                padding: 40px 30px;
            }

            .auth-form-container {
                padding: 40px 30px;
            }
        }
    </style>
</head>

<body>
    <div class="auth-wrapper">
        <div class="auth-brand">
            <h1>Halo, Pengguna Baru!</h1>
            <p>Bergabunglah dengan platform kami dan nikmati semua fitur menarik yang telah kami sediakan khusus untuk
                Anda.</p>
        </div>

        <div class="auth-form-container">
            <h2>Daftar Akun</h2>
            <p>Lengkapi formulir di bawah ini untuk memulai</p>

            <form action="/register" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="name" class="form-control" placeholder="Contoh: Budi Santoso" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control" placeholder="nama@email.com" required>
                </div>
                <div class="mb-4">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>

                @if(session('success'))
                    <div class="alert alert-success py-2 border-0 shadow-sm text-center mb-4" role="alert"
                        style="font-size: 0.9rem; background-color: #e8f5e9; color: #2e7d32; border-radius: 8px;">
                        Registrasi berhasil! Silakan login.
                    </div>
                @endif

                <button type="submit" class="btn btn-primary-custom mb-4">Daftar Sekarang</button>

                <div class="text-center mt-2">
                    <span class="text-muted" style="font-size: 0.95rem;">Sudah memiliki akun?</span>
                    <a href="/login" class="link-custom" style="font-size: 0.95rem;">Login di sini</a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>