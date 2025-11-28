<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Klinik Reservasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .time-slots-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-top: 10px;
        }

        .time-slot {
            padding: 12px;
            border: 2px solid #dee2e6;
            border-radius: 8px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .time-slot.available {
            background-color: #e8f5e8;
            border-color: #28a745;
            color: #155724;
        }

        .time-slot.available:hover {
            background-color: #d4edda;
            transform: translateY(-2px);
        }

        .time-slot.unavailable {
            background-color: #f8d7da;
            border-color: #dc3545;
            color: #721c24;
            cursor: not-allowed;
            opacity: 0.6;
        }

        .time-slot.selected {
            background-color: #007bff;
            color: white;
            border-color: #0056b3;
        }

        .doctor-card {
            transition: transform 0.2s;
        }

        .doctor-card:hover {
            transform: translateY(-5px);
        }
        
        .guest-nav {
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg {{ auth()->check() ? 'navbar-dark bg-primary' : 'guest-nav' }}">
        <div class="container">
            <a class="navbar-brand {{ auth()->check() ? '' : 'text-dark' }}" href="/">Klinik Reservasi</a>
            <div class="navbar-nav ms-auto">
                @auth
                    @if(auth()->user()->isAdmin())
                        <a class="nav-link" href="{{ route('admin.dashboard') }}">Dashboard Admin</a>
                        <a class="nav-link" href="{{ route('admin.dokters.index') }}">Dokter</a>
                        <a class="nav-link" href="{{ route('admin.jadwal-praktek.index') }}">Jadwal</a>
                        <a class="nav-link" href="{{ route('admin.reservasis.index') }}">Reservasi</a>
                    @else
                        <a class="nav-link" href="{{ route('user.dashboard') }}">Dashboard</a>
                        <a class="nav-link" href="{{ route('user.reservasis.index') }}">Reservasi Saya</a>
                        <a class="nav-link" href="{{ route('user.reservasis.create') }}">Buat Reservasi</a>
                    @endif
                    
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-link nav-link {{ auth()->check() ? '' : 'text-dark' }}">Logout</button>
                    </form>
                @else
                    <a class="nav-link text-dark" href="{{ route('login') }}">Login</a>
                    <a class="nav-link text-dark" href="{{ route('register') }}">Register</a>
                @endauth
            </div>
        </div>
    </nav>

    <main class="py-4">
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>