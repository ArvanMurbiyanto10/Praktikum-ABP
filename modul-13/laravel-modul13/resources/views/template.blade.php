<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f1f5f9;
            font-family: 'Segoe UI', sans-serif;
        }

        .main-container {
            max-width: 900px;
            margin: auto;
        }

        .top-bar {
            background: white;
            padding: 12px 18px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }
    </style>
</head>

<body>

    <div class="main-container mt-4">

        @auth
            <div class="top-bar d-flex justify-content-between align-items-center mb-3">
                <div>
                    <small class="text-muted">Login sebagai</small><br>
                    <span class="fw-semibold">{{ Auth::user()->name }}</span>
                </div>

                <a href="{{ route('logout') }}" class="btn btn-sm"
                    style="background:#ef4444; color:white; border-radius:6px;">
                    Logout
                </a>
            </div>
        @endauth

        <div>
            @yield('content')
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>