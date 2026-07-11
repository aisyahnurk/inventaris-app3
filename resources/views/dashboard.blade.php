@extends('layouts.app')

@section('content')
<style>
    /* Styling khusus untuk Dashboard */
    .dashboard-header {
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        border-radius: 20px;
        padding: 32px;
        color: white;
        margin-bottom: 32px;
        box-shadow: 0 10px 25px rgba(99, 102, 241, 0.2);
    }

    .stat-card {
        background: #ffffff;
        border-radius: 20px;
        padding: 24px;
        border: 1px solid #f1f5f9;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
        display: flex;
        align-items: center;
        gap: 20px;
        transition: 0.3s;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.06);
    }

    .stat-icon {
        width: 64px;
        height: 64px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .icon-blue { background: #e0e7ff; color: #4f46e5; }
    .icon-green { background: #dcfce7; color: #16a34a; }
    .icon-orange { background: #ffedd5; color: #ea580c; }
    .icon-purple { background: #f3e8ff; color: #9333ea; }

    .quick-link-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 20px;
        border: 1px solid #e2e8f0;
        text-align: center;
        text-decoration: none;
        color: #334155;
        display: block;
        transition: 0.2s;
    }

    .quick-link-card:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
        color: #6366f1;
    }

    .quick-link-card i {
        margin-bottom: 12px;
        color: #64748b;
        transition: 0.2s;
    }

    .quick-link-card:hover i {
        color: #6366f1;
    }
</style>

<div class="dashboard-header d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
        <h3 class="fw-bold mb-1">Selamat Datang, {{ Auth::user()->name }}! 👋</h3>
        <p class="mb-0" style="opacity: 0.9;">Pantau dan kelola seluruh aset inventaris sarpras dari satu tempat.</p>
    </div>
    <div class="text-end d-none d-md-block">
        <div class="small" style="opacity: 0.8;">Tanggal Hari Ini</div>
        <div class="fw-semibold fs-5">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</div>
    </div>
</div>

<div class="row g-4 mb-5">
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon icon-blue">
                <i data-lucide="package" style="width: 32px; height: 32px;"></i>
            </div>
            <div>
                <p class="text-muted small fw-bold mb-1 text-uppercase">Total Barang</p>
                <h3 class="fw-bold mb-0 text-dark">{{ $total_barang }}</h3>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon icon-purple">
                <i data-lucide="layers" style="width: 32px; height: 32px;"></i>
            </div>
            <div>
                <p class="text-muted small fw-bold mb-1 text-uppercase">Kategori Master</p>
                <h3 class="fw-bold mb-0 text-dark">{{ $total_kategori }}</h3>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon icon-green">
                <i data-lucide="arrow-down-to-line" style="width: 32px; height: 32px;"></i>
            </div>
            <div>
                <p class="text-muted small fw-bold mb-1 text-uppercase">Barang Masuk</p>
                <h3 class="fw-bold mb-0 text-dark">{{ $barang_masuk }}</h3>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon icon-orange">
                <i data-lucide="arrow-up-from-line" style="width: 32px; height: 32px;"></i>
            </div>
            <div>
                <p class="text-muted small fw-bold mb-1 text-uppercase">Barang Keluar</p>
                <h3 class="fw-bold mb-0 text-dark">{{ $barang_keluar }}</h3>
            </div>
        </div>
    </div>
</div>

<h5 class="fw-bold text-dark mb-3">Akses Cepat Modul</h5>
<div class="row g-3">
    
    <div class="col-6 col-md-3">
        <a href="{{ route('admin.data-barang.index') }}" class="quick-link-card">
            <i data-lucide="box" style="width: 36px; height: 36px;"></i>
            <h6 class="fw-semibold mb-0">Kelola Barang</h6>
        </a>
    </div>

    <div class="col-6 col-md-3">
        <a href="{{ route('admin.kategori.index') }}" class="quick-link-card">
            <i data-lucide="tags" style="width: 36px; height: 36px;"></i>
            <h6 class="fw-semibold mb-0">Master Kategori</h6>
        </a>
    </div>

    <div class="col-6 col-md-3">
        <a href="{{ route('admin.transaksi.index') }}" class="quick-link-card">
            <i data-lucide="arrow-left-right" style="width: 36px; height: 36px;"></i>
            <h6 class="fw-semibold mb-0">Log Transaksi</h6>
        </a>
    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
</script>
@endsection