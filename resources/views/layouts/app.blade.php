<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>IPS Alma Vida – @yield('title', 'Sistema de Gestión')</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f0f4f8;
        }

        /* ── Sidebar ── */
        #sidebar {
            width: 260px;
            min-height: 100vh;
            background: #0f2942;
            position: fixed;
            top: 0; left: 0;
            z-index: 1000;
            display: flex;
            flex-direction: column;
        }
        .sidebar-brand {
            padding: 22px 18px 16px;
            border-bottom: 1px solid rgba(255,255,255,.08);
        }
        .brand-logo {
            width: 42px; height: 42px;
            background: #0d9488;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 20px; font-weight: 700; color: #fff;
            flex-shrink: 0;
        }
        .brand-name { font-size: 16px; font-weight: 600; color: #fff; margin-bottom: 1px; }
        .brand-sub  { font-size: 10px; color: rgba(255,255,255,.38); letter-spacing: .08em; text-transform: uppercase; }

        .sidebar-nav { flex: 1; padding: 14px 10px; overflow-y: auto; }
        .nav-section-label {
            font-size: 10px; font-weight: 600;
            letter-spacing: .12em; text-transform: uppercase;
            color: rgba(255,255,255,.28);
            padding: 10px 10px 5px;
        }
        .sidebar-link {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 13px;
            border-radius: 8px;
            color: rgba(255,255,255,.58);
            text-decoration: none;
            font-size: 14px;
            margin-bottom: 2px;
            transition: all .15s;
        }
        .sidebar-link:hover { background: rgba(255,255,255,.07); color: #fff; }
        .sidebar-link.active { background: #0d9488; color: #fff; }
        .sidebar-link i { font-size: 16px; width: 20px; text-align: center; }

        .sidebar-footer {
            padding: 12px 10px;
            border-top: 1px solid rgba(255,255,255,.08);
        }
        .user-pill {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 12px;
            background: rgba(255,255,255,.05);
            border-radius: 8px;
        }
        .user-avatar {
            width: 34px; height: 34px;
            background: #0d9488;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-size: 13px; font-weight: 700;
            flex-shrink: 0;
        }
        .user-name { font-size: 13px; font-weight: 500; color: #fff; }
        .user-role { font-size: 11px; color: rgba(255,255,255,.38); }

        /* ── Main wrapper ── */
        #main-content {
            margin-left: 260px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ── Topbar ── */
        .topbar {
            background: #fff;
            border-bottom: 1px solid #dee2e6;
            padding: 0 28px;
            height: 62px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky; top: 0; z-index: 900;
            box-shadow: 0 1px 4px rgba(0,0,0,.05);
        }
        .topbar-title { font-size: 17px; font-weight: 600; color: #1a2332; margin: 0; }

        /* ── Page content ── */
        .page-content { padding: 26px 28px; flex: 1; }

        /* ── Stat cards ── */
        .stat-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 1px 8px rgba(0,0,0,.06);
        }
        .stat-icon {
            width: 48px; height: 48px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 22px;
            flex-shrink: 0;
        }
        .stat-value { font-size: 28px; font-weight: 700; line-height: 1; color: #1a2332; }
        .stat-label { font-size: 12px; color: #6c757d; margin-top: 2px; }

        /* ── Cards ── */
        .content-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 1px 8px rgba(0,0,0,.06);
        }
        .content-card .card-header {
            background: #fff;
            border-bottom: 1px solid #dee2e6;
            border-radius: 12px 12px 0 0 !important;
            padding: 16px 20px;
            font-weight: 600;
            font-size: 15px;
            color: #1a2332;
        }

        /* ── Tables ── */
        .table thead th {
            background: #f8f9fa;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: .07em;
            text-transform: uppercase;
            color: #6c757d;
            border-bottom: 1px solid #dee2e6;
            white-space: nowrap;
        }
        .table tbody td { vertical-align: middle; font-size: 14px; }
        .table-hover tbody tr:hover { background-color: #f0faf9; }

        /* ── Forms ── */
        .form-section-title {
            font-size: 12px;
            font-weight: 600;
            color: #0d9488;
            text-transform: uppercase;
            letter-spacing: .09em;
            padding-bottom: 8px;
            border-bottom: 2px solid #ccf2ef;
            margin-bottom: 18px;
            margin-top: 6px;
        }
        .form-label { font-size: 13px; font-weight: 500; color: #374151; margin-bottom: 5px; }
        .form-control:focus, .form-select:focus {
            border-color: #0d9488;
            box-shadow: 0 0 0 .2rem rgba(13,148,136,.15);
        }
        .form-control.is-valid  { border-color: #198754; }
        .form-control.is-invalid{ border-color: #dc3545; }

        /* ── Badges ── */
        .badge-estado-activo     { background: #d1fae5; color: #065f46; font-weight: 500; }
        .badge-estado-cancelado  { background: #fee2e2; color: #991b1b; font-weight: 500; }
        .badge-estado-completado { background: #dbeafe; color: #1e40af; font-weight: 500; }

        /* ── AJAX search dropdown ── */
        #search-dropdown {
            position: absolute;
            top: 100%; left: 0; right: 0;
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: 0 0 8px 8px;
            border-top: none;
            box-shadow: 0 8px 20px rgba(0,0,0,.1);
            z-index: 500;
            display: none;
            max-height: 280px;
            overflow-y: auto;
        }
        .search-result-item {
            padding: 9px 14px;
            border-bottom: 1px solid #f0f0f0;
            cursor: pointer;
            font-size: 13px;
            transition: background .12s;
        }
        .search-result-item:hover { background: #f0faf9; }
        .search-result-item:last-child { border-bottom: none; }
        .search-result-name { font-weight: 500; color: #1a2332; }
        .search-result-meta { font-size: 11px; color: #6c757d; }

        /* ── Doc validation feedback ── */
        .doc-feedback { font-size: 12px; margin-top: 4px; }
        .doc-feedback.checking { color: #6c757d; }
        .doc-feedback.ok       { color: #065f46; }
        .doc-feedback.taken    { color: #991b1b; }

        /* ── Detail label/value pairs ── */
        .detail-label { font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: .06em; color: #6c757d; margin-bottom: 3px; }
        .detail-value { font-size: 15px; color: #1a2332; }

        @media (max-width: 992px) {
            #sidebar { width: 100%; min-height: auto; position: relative; }
            #main-content { margin-left: 0; }
        }
    </style>
    @stack('styles')
</head>
<body>

<!-- Sidebar -->
<div id="sidebar">
    <div class="sidebar-brand">
        <div class="d-flex align-items-center gap-3">
            <div class="brand-logo">A</div>
            <div>
                <div class="brand-name">Alma Vida</div>
                <div class="brand-sub">IPS · Gestión de Pacientes</div>
            </div>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section-label">Principal</div>
        <a href="{{ route('dashboard') }}"
           class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>

        <div class="nav-section-label">Módulos</div>
        <a href="{{ route('pacientes.index') }}"
           class="sidebar-link {{ request()->routeIs('pacientes.index') ? 'active' : '' }}">
            <i class="bi bi-people-fill"></i> Pacientes
        </a>
        <a href="{{ route('pacientes.create') }}"
           class="sidebar-link {{ request()->routeIs('pacientes.create') ? 'active' : '' }}">
            <i class="bi bi-person-plus-fill"></i> Registrar Paciente
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="user-pill">
            <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
            <div class="flex-grow-1 overflow-hidden">
                <div class="user-name text-truncate">{{ auth()->user()->name }}</div>
                <div class="user-role">Administrador</div>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="mb-0">
                @csrf
                <button type="submit" class="btn btn-link p-0 text-white-50" style="font-size:18px" title="Cerrar sesión">
                    <i class="bi bi-box-arrow-right"></i>
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Main -->
<div id="main-content">

    <header class="topbar">
        <h1 class="topbar-title">@yield('page-title', 'Dashboard')</h1>
        <div class="d-flex align-items-center gap-2">
            @yield('topbar-actions')
        </div>
    </header>

    <div class="px-4 pt-3">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mb-0" role="alert">
                <i class="bi bi-check-circle-fill"></i>
                {{ session('success') }}
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-0" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>

    <main class="page-content">
        @yield('content')
    </main>
</div>

<!-- Bootstrap 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
