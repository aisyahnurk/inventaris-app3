<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sistem Inventaris - Dashboard</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
   
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    
    <style>
        :root {
            --primary-grad: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
            --sidebar-bg: #ffffff;
            --bg-body: #f8fafc;
            --text-dark: #1e293b;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg-body);
            color: var(--text-dark);
            overflow-x: hidden;
        }

        .sidebar {
            height: 100vh;
            width: 260px;
            position: fixed;
            left: 0;
            top: 0;
            background: var(--sidebar-bg);
            border-right: 1px solid #e2e8f0;
            padding: 24px;
            z-index: 1000;
        }

        .brand-section {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 40px;
            padding: 0 10px;
        }

        .brand-logo {
            width: 35px;
            height: 35px;
            background: var(--primary-grad);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }

        .brand-name {
            font-weight: 700;
            font-size: 1.1rem;
            letter-spacing: -0.5px;
            color: var(--text-dark);
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 15px;
            color: #64748b;
            border-radius: 12px;
            transition: all 0.3s;
            margin-bottom: 5px;
            font-weight: 500;
        }

        .nav-link:hover, .nav-link.active {
            background: #f1f5f9;
            color: #6366f1;
        }

        .nav-link.active {
            background: rgba(99, 102, 241, 0.1);
            color: #6366f1;
        }

        /* Main Content */
        .main-content {
            margin-left: 260px;
            padding: 40px;
            min-height: 100vh;
        }

        .top-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 16px;
            background: white;
            border-radius: 50px;
            border: 1px solid #e2e8f0;
        }

        .logout-btn {
            border: none;
            background: #fff1f2;
            color: #e11d48;
            padding: 8px;
            border-radius: 10px;
            transition: 0.3s;
        }

        .logout-btn:hover {
            background: #ffe4e6;
        }

        .card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            background: white;
        }

        #toast-container > .toast-success {
            background-color: #6366f1;
        }
    </style>
</head>
<body>

    <aside class="sidebar">
        <div class="brand-section" style="justify-content: center; margin-bottom: 32px;">
            
            <div class="brand-logo" style="background: transparent; width: 120px; height: auto; box-shadow: none;"> 
                <img src="{{ asset('logo.png') }}" alt="Logo Inventaris" style="width: 100%; height: auto; object-fit: contain;">
            </div>
        
        </div>

    <nav>
                    @if(Auth::user()->role == 'admin')
                        <a href="/admin/dashboard" class="nav-link {{ Request::is('admin/dashboard') ? 'active' : '' }}">
                            <i data-lucide="layout-grid"></i> Dashboard
                        </a>

                        <span class="text-muted small fw-bold px-3 mt-3 mb-1" style="letter-spacing: 0.05em; font-size: 0.75rem; display: block;">DATA MASTER</span>
                        
                        <a href="{{ route('admin.data-barang.index') }}" class="nav-link {{ Request::is('admin/data-barang') ? 'active' : '' }}">
                            <i data-lucide="tags"></i> Data Barang
                        </a>

                        <a href="{{ route('admin.kategori.index') }}" class="nav-link {{ Request::is('admin/master/kategori') ? 'active' : '' }}">
                            <i data-lucide="tags"></i> Kategori Barang
                        </a>

                        <span class="text-muted small fw-bold px-3 mt-3 mb-1" style="letter-spacing: 0.05em; font-size: 0.75rem; display: block;">TRANSAKSI</span>

                        <a href="{{ route('admin.transaksi.index') }}" class="nav-link {{ Request::is('admin/transaksi') ? 'active' : '' }}">
                            <i data-lucide="arrow-left-right"></i> Barang Masuk/Keluar
                        </a>
                    @else
                        <a href="/user/inventaris" class="nav-link {{ Request::is('user/inventaris') ? 'active' : '' }}">
                            <i data-lucide="layout-grid"></i> Daftar Barang
                        </a>
                    @endif
                </nav>
            </div>

            <div class="sidebar-footer" style="padding: 0 8px; margin-bottom: 16px;">
                <form action="/logout" method="POST" id="sidebarLogoutForm">
                    @csrf
                    <a href="#" onclick="event.preventDefault(); document.getElementById('sidebarLogoutForm').submit();" class="nav-link text-danger border-0 bg-transparent w-100" style="display: flex; align-items: center; gap: 12px; text-decoration: none;">
                        <i data-lucide="log-out"></i> Logout
                    </a>
                </form>
            </div>
        </aside>

    <main class="main-content">
        <header class="top-header">
            <div>
                <h4 class="fw-bold mb-0">Manajemen Inventaris</h4>
                <p class="text-muted small mb-0">Kelola stok barang Anda dengan mudah</p>
            </div>

            @auth
            <div class="user-profile">
                <span class="small fw-semibold">{{ Auth::user()->name }}</span>
                <form action="/logout" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="logout-btn" title="Logout">
                        <i data-lucide="log-out" style="width: 18px"></i>
                    </button>
                </form>
            </div>
            @endauth
        </header>

        @yield('content')
    </main>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        lucide.createIcons();
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "3000"
        }

        @if(session('success'))
            toastr.success("{{ session('success') }}");
        @endif

        @if(session('error'))
            toastr.error("{{ session('error') }}");
        @endif
    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>


