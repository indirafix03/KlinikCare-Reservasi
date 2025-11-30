<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Klinik Reservasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #4361ee;
            --secondary: #3f37c9;
            --success: #4cc9f0;
            --info: #4895ef;
            --warning: #f72585;
            --light: #f8f9fa;
            --dark: #212529;
        }
        
        body {
            background-color: #f5f7fb;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .stat-card {
            border-radius: 12px;
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s, box-shadow 0.3s;
            overflow: hidden;
            margin-bottom: 1.5rem;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
        }
        
        .stat-card .card-body {
            padding: 1.5rem;
        }
        
        .card-title {
            font-size: 0.9rem;
            font-weight: 500;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 0.5rem;
        }
        
        .card-text.display-4 {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .card-icon {
            font-size: 2.5rem;
            opacity: 0.8;
        }
        
        .bg-primary {
            background: linear-gradient(135deg, #4361ee 0%, #3a56d4 100%) !important;
        }
        
        .bg-success {
            background: linear-gradient(135deg, #4cc9f0 0%, #3ab7d9 100%) !important;
        }
        
        .bg-info {
            background: linear-gradient(135deg, #4895ef 0%, #3a7fd9 100%) !important;
        }
        
        .dashboard-card {
            border-radius: 12px;
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            margin-bottom: 1.5rem;
        }
        
        .dashboard-card .card-header {
            background: white;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            padding: 1rem 1.5rem;
            border-radius: 12px 12px 0 0 !important;
        }
        
        .dashboard-card .card-header h4, 
        .dashboard-card .card-header h5 {
            font-weight: 600;
            margin: 0;
            color: #495057;
        }
        
        .dashboard-card .card-body {
            padding: 1.5rem;
        }
        
        .quick-action-btn {
            border-radius: 10px;
            padding: 12px;
            text-align: center;
            transition: all 0.3s;
            border: 1px solid #e9ecef;
            background: white;
            height: 100%;
            display: block;
            text-decoration: none;
            color: inherit;
        }
        
        .quick-action-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            border-color: var(--primary);
            color: inherit;
        }
        
        .quick-action-btn i {
            font-size: 1.5rem;
            margin-bottom: 8px;
            display: block;
            color: var(--primary);
        }
        
        .table-modern {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }
        
        .table-modern thead {
            background: linear-gradient(135deg, #4361ee 0%, #3a56d4 100%);
            color: white;
        }
        
        .table-modern th {
            border: none;
            padding: 1rem;
            font-weight: 500;
        }
        
        .table-modern td {
            padding: 1rem;
            vertical-align: middle;
            border-color: #f1f3f4;
        }
        
        .table-modern tbody tr {
            transition: background-color 0.2s;
        }
        
        .table-modern tbody tr:hover {
            background-color: #f8f9fa;
        }
        
        .badge-modern {
            padding: 0.5em 0.8em;
            font-size: 0.75rem;
            font-weight: 500;
            border-radius: 6px;
        }
        
        .btn-modern {
            border-radius: 8px;
            padding: 0.5rem 1rem;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .btn-modern:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        .alert-modern {
            border-radius: 10px;
            border: none;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }
        
        .guest-nav {
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
        }
        
        .navbar-brand {
            font-weight: 600;
        }
        
        .welcome-section {
            background: linear-gradient(135deg, #4361ee 0%, #3a56d4 100%);
            border-radius: 12px;
            color: white;
            padding: 2rem;
            margin-bottom: 2rem;
        }
        
        .welcome-section h3 {
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        .welcome-section p {
            opacity: 0.9;
            margin-bottom: 0;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg {{ auth()->check() ? 'navbar-dark bg-primary' : 'guest-nav' }}">
        <div class="container">
            <a class="navbar-brand {{ auth()->check() ? '' : 'text-dark' }}" href="/">
                <i class="fas fa-hospital me-2"></i>Klinik Reservasi
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
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
                            <button type="submit" class="btn btn-link nav-link {{ auth()->check() ? '' : 'text-dark' }} border-0">
                                <i class="fas fa-sign-out-alt me-1"></i>Logout
                            </button>
                        </form>
                    @else
                        <a class="nav-link text-dark" href="{{ route('login') }}">Login</a>
                        <a class="nav-link text-dark" href="{{ route('register') }}">Register</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main class="py-4">
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success alert-modern alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-modern alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
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