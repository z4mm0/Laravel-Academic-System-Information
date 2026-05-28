<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Sistem Informasi Nilai Akademik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-color: #0d6efd;
            --secondary-color: #6c757d;
            --success-color: #198754;
            --danger-color: #dc3545;
            --warning-color: #ffc107;
            --info-color: #0dcaf0;
            --light-bg: #f8f9fa;
            --border-color: #e9ecef;
            --shadow-sm: 0 2px 4px rgba(0,0,0,0.08);
            --shadow-md: 0 4px 12px rgba(0,0,0,0.12);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        body {
            background-color: var(--light-bg);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
        }

        /* NAVBAR STYLING */
        .navbar {
            background: linear-gradient(135deg, var(--primary-color) 0%, #0a58ca 100%);
            box-shadow: var(--shadow-md);
            border-bottom: 3px solid rgba(0,0,0,0.1);
            padding: 1rem 0;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.4rem;
            letter-spacing: -0.5px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .navbar-brand i {
            font-size: 1.6rem;
        }

        .navbar-nav .nav-link {
            font-weight: 500;
            margin: 0 5px;
            transition: var(--transition);
            color: rgba(255,255,255,0.9) !important;
        }

        .navbar-nav .nav-link:hover {
            color: white !important;
        }

        .dropdown-item-text {
            padding: 0.5rem 1rem;
            font-size: 0.85rem;
            color: #666;
            font-weight: 600;
        }

        /* SIDEBAR STYLING */
        .sidebar {
            background: white;
            box-shadow: var(--shadow-sm);
            min-height: calc(100vh - 70px);
            padding: 0;
            position: sticky;
            top: 70px;
        }

        .sidebar-header {
            padding: 1.5rem 1rem;
            border-bottom: 2px solid var(--border-color);
            background: linear-gradient(135deg, rgba(13, 110, 253, 0.05) 0%, rgba(13, 110, 253, 0) 100%);
        }

        .sidebar-header h6 {
            color: var(--primary-color);
            font-weight: 700;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .sidebar-menu {
            list-style: none;
            padding: 1rem 0;
        }

        .sidebar-menu li {
            margin: 0.5rem 0;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #555;
            text-decoration: none;
            padding: 0.8rem 1.2rem;
            font-weight: 500;
            transition: var(--transition);
            border-left: 3px solid transparent;
            margin: 0 0.5rem;
            border-radius: 0 6px 6px 0;
        }

        .sidebar-menu a i {
            width: 20px;
            text-align: center;
            font-size: 1.1rem;
        }

        .sidebar-menu a:hover {
            background-color: rgba(13, 110, 253, 0.08);
            color: var(--primary-color);
            border-left-color: var(--primary-color);
        }

        .sidebar-menu a.active {
            background: linear-gradient(90deg, rgba(13, 110, 253, 0.15) 0%, transparent 100%);
            color: var(--primary-color);
            border-left-color: var(--primary-color);
            font-weight: 600;
        }

        /* MAIN CONTENT */
        .content-wrapper {
            min-height: calc(100vh - 70px);
            padding: 2rem 0;
        }

        .content {
            padding: 0 2rem;
        }

        @media (max-width: 768px) {
            .content {
                padding: 0 1rem;
            }
        }

        /* PAGE HEADER */
        .page-header {
            margin-bottom: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 2px solid var(--border-color);
        }

        .page-header h1 {
            font-size: 2rem;
            font-weight: 700;
            color: #222;
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 0.5rem;
        }

        .page-header p {
            color: #666;
            margin: 0;
            font-size: 0.95rem;
        }

        .page-header i {
            font-size: 2.2rem;
            color: var(--primary-color);
        }

        /* CARD STYLING */
        .card {
            border: 0;
            box-shadow: var(--shadow-sm);
            border-radius: 12px;
            transition: var(--transition);
            overflow: hidden;
        }

        .card:hover {
            box-shadow: var(--shadow-md);
            transform: translateY(-2px);
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, #0a58ca 100%);
            border: 0;
            color: white;
            font-weight: 600;
            padding: 1.2rem 1.5rem;
        }

        .card-body {
            padding: 1.5rem;
        }

        /* BUTTONS */
        .btn {
            font-weight: 600;
            padding: 0.6rem 1.2rem;
            border-radius: 8px;
            transition: var(--transition);
            border: 0;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, #0a58ca 100%);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .btn-sm {
            padding: 0.4rem 0.8rem;
            font-size: 0.85rem;
        }

        /* TABLES */
        .table {
            margin-bottom: 0;
        }

        .table thead th {
            background-color: var(--light-bg);
            border-top: 2px solid var(--border-color);
            border-bottom: 2px solid var(--border-color);
            font-weight: 600;
            color: #333;
            padding: 1rem;
        }

        .table tbody td {
            padding: 0.8rem 1rem;
            vertical-align: middle;
            border-color: var(--border-color);
        }

        .table tbody tr {
            transition: var(--transition);
        }

        .table tbody tr:hover {
            background-color: rgba(13, 110, 253, 0.05);
        }

        /* BADGES */
        .badge {
            padding: 0.4rem 0.8rem;
            font-weight: 600;
            border-radius: 6px;
            font-size: 0.8rem;
        }

        /* ALERTS */
        .alert {
            border: 0;
            border-radius: 8px;
            border-left: 4px solid;
        }

        .alert-success {
            background-color: #d1e7dd;
            border-left-color: var(--success-color);
            color: #0f5132;
        }

        .alert-danger {
            background-color: #f8d7da;
            border-left-color: var(--danger-color);
            color: #842029;
        }

        .alert-info {
            background-color: #cfe2ff;
            border-left-color: var(--info-color);
            color: #084298;
        }

        /* FORMS */
        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 0.5rem;
        }

        .form-control, .form-select {
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 0.6rem 1rem;
            transition: var(--transition);
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.1);
        }

        /* STAT CARDS */
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            text-align: center;
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
            border-top: 4px solid var(--primary-color);
        }

        .stat-card:hover {
            box-shadow: var(--shadow-md);
            transform: translateY(-4px);
        }

        .stat-card i {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }

        .stat-card .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: #222;
            margin: 0.5rem 0;
        }

        .stat-card .stat-label {
            color: #666;
            font-size: 0.9rem;
            margin: 0;
        }

        /* FOOTER */
        .footer {
            background: white;
            border-top: 1px solid var(--border-color);
            padding: 1.5rem;
            text-align: center;
            color: #666;
            font-size: 0.9rem;
            margin-top: 2rem;
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .page-header h1 {
                font-size: 1.5rem;
            }

            .content-wrapper {
                padding: 1rem 0;
            }

            .sidebar {
                min-height: auto;
            }
        }

        /* ANIMATIONS */
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card, .alert {
            animation: slideIn 0.3s ease-out;
        }
    </style>
    @yield('extra-css')
</head>
<body>
    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="/">
                <i class="bi bi-book"></i>
                SIA Nilai Akademik
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @auth
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle"></i>
                            {{ auth()->user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><span class="dropdown-item-text">
                            <i class="bi {{ in_array(auth()->user()->role, ['guru_mapel', 'wali_kelas']) ? 'bi-briefcase' : 'bi-mortarboard' }}"></i>
                                {{ in_array(auth()->user()->role, ['guru_mapel', 'wali_kelas']) ? 'Guru' : 'Siswa' }}
                            </span></li>
                            <li><span class="dropdown-item-text">{{ auth()->user()->email }}</span></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="bi bi-box-arrow-right"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                    @else
                    <li class="nav-item">
                        <a class="nav-link" href="/login">
                            <i class="bi bi-lock"></i> Login
                        </a>
                    </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>
<div class="container-fluid">
        <div class="row" style="min-height: calc(100vh - 70px);">
            <!-- SIDEBAR -->
            @auth
            <div class="col-md-2 d-none d-md-block sidebar">
                <div class="sidebar-header">
                    <h6>
                        <i class="bi {{ in_array(auth()->user()->role, ['guru_mapel', 'wali_kelas']) ? 'bi-briefcase-fill' : 'bi-mortarboard-fill' }}"></i>
                        {{ in_array(auth()->user()->role, ['guru_mapel', 'wali_kelas']) ? 'Menu Guru' : 'Menu Siswa' }}
                    </h6>
                </div>
                <ul class="sidebar-menu">
@if (auth()->user()->role === 'superadmin')
                        <!-- SUPERADMIN MENU -->
                        <li>
                            <a href="{{ route('admin.superusers.dashboard') }}" class="@if(Request::is('admin/superusers/dashboard')) active @endif">
                                <i class="bi bi-house-door-fill"></i>
                                Dashboard
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.gurus.index') }}" class="@if(Request::is('admin/gurus*')) active @endif">
                                <i class="bi bi-people-fill"></i>
                                Kelola Guru
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.siswas.index') }}" class="@if(Request::is('admin/siswas*')) active @endif">
                                <i class="bi bi-person-lines-fill"></i>
                                Kelola Siswa
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.classes.index') }}" class="@if(Request::is('admin/classes*')) active @endif">
                                <i class="bi bi-building"></i>
                                Kelola Kelas
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.superusers.subjects.index') }}" class="@if(Request::is('admin/superusers/subjects*')) active @endif">
                                <i class="bi bi-book-fill"></i>
                                Kelola Mata Pelajaran
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.superusers.superusers.index') }}" class="@if(Request::is('admin/superusers/superusers*')) active @endif">
                                <i class="bi bi-person-check-fill"></i>
                                Kelola Super Admin
                            </a>
                        </li>
                        <li><hr class="my-2"></li>
@elseif(auth()->user()->role === 'guru_mapel')
                        <!-- GURU MAPEL MENU -->
                        <li>
                            <a href="{{ route('guru_mapel.dashboard') }}" class="@if(Request::is('guru_mapel/dashboard')) active @endif">
                                <i class="bi bi-house-fill"></i>
                                Dashboard
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('guru_mapel.classes.index') }}" class="@if(Request::is('guru_mapel/classes*')) active @endif">
                                <i class="bi bi-building"></i>
                                Kelas Saya
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('guru_mapel.subjects.index') }}" class="@if(Request::is('guru_mapel/subjects*')) active @endif">
                                <i class="bi bi-book-fill"></i>
                                Mata Pelajaran
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('guru_mapel.grades.all') }}" class="@if(Request::is('guru_mapel/grades*')) active @endif">
                                <i class="bi bi-graph-up"></i>
                                Nilai Siswa
                            </a>
                        </li>
                        <li><hr class="my-2"></li>
                    @elseif(auth()->user()->role === 'wali_kelas')
                        <!-- WALI KELAS MENU -->
                        <li>
                            <a href="{{ route('wali_kelas.dashboard') }}" class="@if(Request::is('wali_kelas/dashboard')) active @endif">
                                <i class="bi bi-house-fill"></i>
                                Dashboard
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('wali_kelas.classes.index') }}" class="@if(Request::is('wali_kelas/classes*')) active @endif">
                                <i class="bi bi-building"></i>
                                Kelas Saya
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('wali_kelas.classes.index') }}" class="@if(Request::is('wali_kelas/classes*')) active @endif">
                                <i class="bi bi-graph-up"></i>
                                Nilai Kelas
                            </a>
                        </li>
                        <li><hr class="my-2"></li>
                    @else
                        <!-- SISWA MENU -->
                        <li>
                            <a href="{{ route('student.dashboard') }}" class="@if(Request::is('student/dashboard')) active @endif">
                                <i class="bi bi-house-fill"></i>
                                Dashboard
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('student.grades.index') }}" class="@if(Request::is('student/grades')) active @endif">
                                <i class="bi bi-file-text"></i>
                                Nilai Saya
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('student.statistics') }}" class="@if(Request::is('student/statistics')) active @endif">
                                <i class="bi bi-bar-chart"></i>
                                Statistik
                            </a>
                        </li>
                    @endif
                </ul>
            </div>

            <!-- MAIN CONTENT -->
            <div class="col-md-10 ms-auto content-wrapper">
                <div class="content">
                    @if ($message = Session::get('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle"></i>
                        {{ $message }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    @if ($message = Session::get('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-circle"></i>
                        {{ $message }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    @if ($message = Session::get('info'))
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <i class="bi bi-info-circle"></i>
                        {{ $message }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle"></i>
                        <strong>Terjadi kesalahan!</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    @yield('content')
                </div>
                <div class="footer">
                    <p>&copy; 2026 Sistem Informasi Nilai Akademik. All rights reserved.</p>
                </div>
            </div>
            @else
            <!-- NOT AUTHENTICATED -->
            <div class="col-12 content-wrapper">
                <div class="content">
                    @if ($message = Session::get('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle"></i>
                        {{ $message }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    @if ($message = Session::get('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-circle"></i>
                        {{ $message }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle"></i>
                        <strong>Terjadi kesalahan!</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    @yield('content')
                </div>
                <div class="footer">
                    <p>&copy; 2026 Sistem Informasi Nilai Akademik. All rights reserved.</p>
                </div>
            </div>
            @endauth
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-dismiss alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000);
            });
        });
    </script>
    @yield('extra-js')
</body>
</html>
