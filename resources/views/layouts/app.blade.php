<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name', 'Slip Gaji'))</title>
    
    <!-- Bootstrap 5 CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        :root {
            /* Warna Background Dark Navy seragam tanpa potong warna */
            --sidebar-bg: #111827; 
            --active-bg: #ffffff;
            --active-text: #111827;
            --text-color: #9ca3af;
            --hover-bg: rgba(255, 255, 255, 0.05);
        }

        body {
            background-color: #f4f7f6;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
        }

        .wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Clean Styling */
        #sidebar {
            width: 240px;
            min-width: 240px;
            background-color: var(--sidebar-bg);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 1.5rem 1.25rem;
            min-height: 100vh;
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            color: #ffffff;
            margin-bottom: 2.5rem;
            padding-left: 0.5rem;
        }

        .brand-icon {
            width: 38px;
            height: 38px;
            background: #2563eb;
            color: #ffffff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        /* Menu Link Minimalis */
        .nav-link-custom {
            color: var(--text-color);
            padding: 12px 16px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 14px;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.95rem;
            margin-bottom: 8px;
            transition: all 0.2s ease;
        }

        .nav-link-custom:hover {
            color: #ffffff;
            background-color: var(--hover-bg);
        }

        /* Highlight Putih untuk Menu Aktif */
        .nav-link-custom.active {
            color: var(--active-text) !important;
            background-color: var(--active-bg) !important;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        /* Logout disatukan dengan gaya menu */
        .btn-logout-link {
            background: none;
            border: none;
            color: var(--text-color);
            width: 100%;
            padding: 12px 16px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 14px;
            font-weight: 500;
            font-size: 0.95rem;
            text-decoration: none;
            transition: all 0.2s ease;
            text-align: left;
        }

        .btn-logout-link:hover {
            color: #ef4444;
            background-color: rgba(239, 68, 68, 0.1);
        }

        /* Main Content */
        #content {
            flex-grow: 1;
            padding: 1.5rem 2rem;
            overflow-y: auto;
        }

        .top-navbar {
            background-color: #ffffff;
            border-radius: 14px;
            padding: 1rem 1.5rem;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.03);
            margin-bottom: 2rem;
        }

        .user-badge {
            background-color: #0284c7;
            color: #ffffff;
            font-size: 0.8rem;
            padding: 6px 14px;
            border-radius: 20px;
            font-weight: 500;
        }

        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        @media (max-width: 767.98px) {
            .wrapper {
                display: block;
            }

            #sidebar {
                width: 100%;
                min-width: 0;
                min-height: auto;
                padding: 1rem;
            }

            .brand-logo {
                margin-bottom: 1rem;
            }

            #sidebar > div:last-child {
                margin-top: 0.75rem;
            }

            #content {
                width: 100%;
                padding: 1rem;
                overflow-x: hidden;
            }

            .top-navbar {
                align-items: flex-start !important;
                flex-wrap: wrap;
                gap: 0.75rem;
                padding: 0.9rem 1rem;
                margin-bottom: 1.25rem;
            }

            .top-navbar h5 {
                font-size: 1rem;
            }

            .user-badge {
                font-size: 0.75rem;
            }

            .table-responsive table {
                min-width: 980px;
            }

            .btn-group {
                flex-wrap: nowrap;
            }

            .modal-dialog {
                margin: 0.75rem;
            }
        }
    </style>
</head>
<body>

<div class="wrapper">
    <!-- Sidebar -->
    <nav id="sidebar">
        <div>
            <!-- 1. Brand Logo Simpel -->
            <div class="brand-logo">
                <div class="brand-icon">
                    <i class="bi bi-wallet2 fs-5"></i>
                </div>
                <h5 class="m-0 fw-bold tracking-wide">{{ config('app.name', 'Slip Gaji') }}</h5>
            </div>

            <!-- 2. Menu Navigasi -->
            <a href="{{ route('karyawan.index') }}" class="nav-link-custom {{ request()->routeIs('karyawan.*') ? 'active' : '' }}">
                <i class="bi bi-people fs-5"></i>
                <span>{{ config('ui.page.dashboard', 'Data Karyawan') }}</span>
            </a>
        </div>

        <!-- 3. Bottom Menu (Logout minimalis di paling bawah) -->
        <div>
            <form action="{{ route('logout') }}" method="POST" class="m-0">
                @csrf
                <button type="submit" class="btn-logout-link">
                    <i class="bi bi-box-arrow-right fs-5"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </nav>

    <!-- Main Content Area -->
    <div id="content">
        <!-- Top Header Putih -->
        <div class="top-navbar d-flex justify-content-between align-items-center">
            <h5 class="m-0 fw-bold text-dark">{{ config('ui.page.dashboard', 'Data Karyawan') }}</h5>
            <span class="user-badge">
                User: {{ Auth::user()->name ?? config('ui.default_user', 'Pengguna') }}
            </span>
        </div>

        <!-- Flash Notifications -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Content Dari View -->
        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>