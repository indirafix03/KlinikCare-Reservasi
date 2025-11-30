<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Klinik Reservasi</title>
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
            --sidebar-width: 250px;
        }
        
        body {
            background-color: #f5f7fb;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, var(--primary) 0%, var(--secondary) 100%);
            width: var(--sidebar-width);
            position: fixed;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            z-index: 100;
        }
        
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 12px 20px;
            margin: 4px 10px;
            border-radius: 8px;
            transition: all 0.3s;
        }
        
        .sidebar .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
        }
        
        .sidebar .nav-link.active {
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
            font-weight: 500;
        }
        
        .sidebar .nav-link i {
            width: 20px;
            margin-right: 10px;
        }
        
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 20px;
            width: calc(100% - var(--sidebar-width));
        }
        
        .navbar-brand {
            font-weight: 600;
        }
        
        .stat-card {
            border-radius: 12px;
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s, box-shadow 0.3s;
            overflow: hidden;
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
        
        .bg-warning {
            background: linear-gradient(135deg, #f72585 0%, #e02178 100%) !important;
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
        
        .dashboard-card .card-header h6 {
            font-weight: 600;
            margin: 0;
            color: #495057;
        }
        
        .dashboard-card .card-body {
            padding: 1.5rem;
        }
        
        .list-group-item {
            border: none;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            padding: 1rem 0;
        }
        
        .list-group-item:last-child {
            border-bottom: none;
        }
        
        .quick-action-btn {
            border-radius: 10px;
            padding: 12px;
            text-align: center;
            transition: all 0.3s;
            border: 1px solid #e9ecef;
            background: white;
            height: 100%;
        }
        
        .quick-action-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            border-color: var(--primary);
        }
        
        .quick-action-btn i {
            font-size: 1.5rem;
            margin-bottom: 8px;
            display: block;
        }
        
        .stat-badge {
            font-size: 0.75rem;
            padding: 0.35em 0.65em;
        }
        
        .border-stat {
            border-radius: 10px;
            padding: 1.5rem;
            text-align: center;
            transition: all 0.3s;
        }
        
        .border-stat:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }
        
        .border-stat h4 {
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                position: relative;
                min-height: auto;
            }
            
            .main-content {
                margin-left: 0;
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <nav class="sidebar d-none d-md-block">
            <div class="position-sticky pt-4">
                <div class="px-3 mb-4">
                    <h5 class="text-white text-center mb-0">Admin Panel</h5>
                </div>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" 
                           href="{{ route('admin.dashboard') }}">
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.dokters.*') ? 'active' : '' }}" 
                           href="{{ route('admin.dokters.index') }}">
                            <i class="fas fa-user-md"></i> Data Dokter
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.jadwal-praktek.*') ? 'active' : '' }}" 
                           href="{{ route('admin.jadwal-praktek.index') }}">
                            <i class="fas fa-calendar-alt"></i> Jadwal Praktek
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.reservasis.*') ? 'active' : '' }}" 
                           href="{{ route('admin.reservasis.index') }}">
                            <i class="fas fa-calendar-check"></i> Reservasi
                        </a>
                    </li>
                </ul>
            </div>
        </nav>

        <!-- Main content -->
        <div class="main-content">
            <!-- Navbar -->
            <nav class="navbar navbar-expand-lg navbar-light bg-white rounded shadow-sm mb-4">
                <div class="container-fluid">
                    <a class="navbar-brand fw-bold text-primary" href="{{ route('admin.dashboard') }}">
                        <i class="fas fa-hospital me-2"></i> Klinik Reservasi
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav ms-auto">
                            <li class="nav-item">
                                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-sign-out-alt me-1"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <!-- Page header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 fw-bold text-dark">@yield('title', 'Dashboard')</h1>
                <div class="text-muted">
                    <i class="fas fa-calendar-day me-1"></i> {{ now()->format('l, d F Y') }}
                </div>
            </div>

            <!-- Alerts -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Content -->
            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>