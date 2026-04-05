<!DOCTYPE html>
<html lang="id">

<head>
    <title>Dashboard - Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f4f8;
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
        }

        /* HEADER / TOP BAR */
        .top-bar {
            height: 120px;
            padding: 0 40px;
            background: linear-gradient(135deg, #7BBDE8, #62A8D9);
            /* Biru Langit */
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 15px rgba(123, 189, 232, 0.4);
        }

        /* AREA GREETING (Tulisan Selamat Datang) */
        .greeting p {
            font-size: 1rem;
            margin: 0;
            opacity: 0.9;
        }

        .greeting h3 {
            font-size: 1.8rem;
            font-weight: 700;
            margin: 0;
            letter-spacing: 0.5px;
        }

        .btn-logout {
            background-color: white;
            color: #7BBDE8;
            font-weight: 600;
            border: none;
            padding: 10px 25px;
            border-radius: 10px;
            transition: 0.3s;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .btn-logout:hover {
            background-color: #f8f9fa;
            transform: translateY(-2px);
            color: #62A8D9;
        }

        .main-content {
            padding: 40px;
        }

        .welcome-card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            border: none;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
        }
    </style>
</head>

<body>

    <header class="top-bar">
        <div class="greeting">
            <p>Halo, selamat datang kembali</p>
            <h3>{{ $user->name }}</h3>
        </div>
        <div class="action-area">
            <a href="/logout" class="btn btn-logout">Logout</a>
        </div>
    </header>


</body>

</html>