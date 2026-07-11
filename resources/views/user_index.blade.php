@extends('layouts.app')

@section('content')
<style>
    .table-card {
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
        border: 1px solid #f1f5f9;
        overflow: hidden;
    }

    .table-header {
        padding: 24px;
        border-bottom: 1px solid #f1f5f9;
    }

    .search-box {
        position: relative;
        max-width: 320px;
    }

    .search-box input {
        border-radius: 12px;
        padding: 10px 16px 10px 42px;
        border: 1.5px solid #e2e8f0;
        font-size: 0.85rem;
        width: 100%;
        transition: all 0.2s;
    }

    .search-box input:focus {
        outline: none;
        border-color: #6366f1;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
    }

    .search-box i {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        width: 16px;
        height: 16px;
    }

    .custom-table {
        margin-bottom: 0;
    }

    .custom-table thead th {
        background-color: #f8fafc;
        color: #64748b;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        padding: 16px 24px;
        border-bottom: none;
        font-weight: 600;
    }

    .custom-table tbody td {
        padding: 16px 24px;
        vertical-align: middle;
        color: #334155;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.9rem;
    }

    .custom-table tbody tr:hover {
        background-color: #f8fafc;
        transition: 0.2s ease;
    }

    .stock-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        display: inline-block;
    }
    .stock-safe { background: #dcfce7; color: #16a34a; }
    .stock-low { background: #fee2e2; color: #dc2626; }

    .empty-state {
        padding: 60px 20px;
        text-align: center;
    }

    .skeleton-row td {
        padding: 18px 24px;
    }
    .skeleton-bar {
        height: 14px;
        border-radius: 6px;
        background: linear-gradient(90deg, #f1f5f9 25%, #e2e8f0 50%, #f1f5f9 75%);
        background-size: 200% 100%;
        animation: skeleton-loading 1.2s infinite;
    }
    @keyframes skeleton-loading {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }

    .readonly-badge {
        background: #f1f5f9;
        color: #64748b;
        font-size: 0.75rem;
        font-weight: 600;
        padding: 6px 14px;
        border-radius: 20px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
</style>

<div class="table-card">
    <div class="table-header d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h5 class="mb-1 fw-bold text-dark">Daftar Barang</h5>
            <p class="mb-0 text-muted small">Total keseluruhan inventaris yang terdaftar</p>
        </div>

        <div class="d-flex align-items-center gap-3 flex-wrap">
            <!-- Box Pencarian -->
            <div class="search-box d-flex align-items-center" style="position: relative;">
                <i data-lucide="search" style="position: absolute; left: 15px; width: 18px; color: #64748b; pointer-events: none;"></i>
                <input type="text" id="searchInput" placeholder="Cari nama atau kode barang..." style="padding-left: 45px; height: 42px; border-radius: 10px; border: 1px solid #e2e8f0; font-size: 0.9rem; width: 260px; outline: none;">
            </div>

            <!-- Badge Mode Lihat Saja -->
            <span class="readonly-badge d-flex align-items-center gap-2" style="height: 42px; padding: 0 16px; border-radius: 10px; display: inline-flex; align-items: center; white-space: nowrap;">
                <i data-lucide="eye" style="width: 16px;"></i> Mode Lihat Saja
            </span>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table custom-table">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="15%">Kode</th>
                    <th>Nama Barang</th>
                    <th width="20%">Kategori</th>   <!-- ✅ Tambah kolom kategori -->
                    <th width="15%">Stok</th>
                </tr>
            </thead>
            <tbody id="itemTableBody">
                <!-- Data dimuat secara dinamis via AJAX -->
            </tbody>
        </table>
    </div>
</div>

<script>
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }

    const USER_DATA_URL = "{{ route('user.items.data') }}";
    let searchDebounce = null;

    // ========== UTILITY ==========
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text ?? '';
        return div.innerHTML;
    }

    // ========== RENDER SKELETON ==========
    function renderSkeleton() {
        const tbody = document.getElementById('itemTableBody');
        let rows = '';
        for (let i = 0; i < 4; i++) {
            rows += `
                <tr class="skeleton-row">
                    <td><div class="skeleton-bar" style="width:20px;"></div></td>
                    <td><div class="skeleton-bar" style="width:70px;"></div></td>
                    <td><div class="skeleton-bar" style="width:140px;"></div></td>
                    <td><div class="skeleton-bar" style="width:100px;"></div></td>   <!-- ✅ skeleton kategori -->
                    <td><div class="skeleton-bar" style="width:60px;"></div></td>
                </tr>`;
        }
        tbody.innerHTML = rows;
    }

    // ========== RENDER DATA BARANG ==========
    function renderRows(items) {
        const tbody = document.getElementById('itemTableBody');

        if (!items || items.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="5">   <!-- ✅ colspan 5 -->
                        <div class="empty-state">
                            <i data-lucide="inbox" style="width:64px; height:64px; color:#cbd5e1; margin-bottom:16px;"></i>
                            <h6 class="fw-bold text-dark">Tidak ada data inventaris</h6>
                            <p class="text-muted small">Coba gunakan kata kunci pencarian lain.</p>
                        </div>
                    </td>
                </tr>`;
            if (typeof lucide !== 'undefined') lucide.createIcons();
            return;
        }

        let rows = '';
        items.forEach((item, index) => {
            const stockClass = item.stok <= 10 ? 'stock-low' : 'stock-safe';
            // Ambil nama kategori, fallback '-'
            const categoryName = item.category ? escapeHtml(item.category.nama_kategori) : '-';

            rows += `
                <tr>
                    <td>${index + 1}</td>
                    <td><span class="text-muted fw-semibold">${escapeHtml(item.kode)}</span></td>
                    <td class="fw-medium text-dark">${escapeHtml(item.nama)}</td>
                    <td><span class="badge bg-secondary bg-opacity-10 text-dark">${categoryName}</span></td>   <!-- ✅ kategori -->
                    <td><span class="stock-badge ${stockClass}">${item.stok} Unit</span></td>
                </tr>`;
        });

        tbody.innerHTML = rows;
        if (typeof lucide !== 'undefined') lucide.createIcons();
    }

    // ========== LOAD DATA BARANG ==========
    function loadItems(keyword = '') {
        renderSkeleton();

        const url = keyword
            ? `${USER_DATA_URL}?search=${encodeURIComponent(keyword)}`
            : USER_DATA_URL;

        fetch(url, {
            method: 'GET',
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        })
        .then(res => res.json())
        .then(result => {
            if (result.success) {
                renderRows(result.data);
            } else {
                toastr.error(result.message || 'Gagal memuat data.');
            }
        })
        .catch(() => {
            toastr.error('Gagal memuat data barang.');
        });
    }

    // ========== LIVE SEARCH ==========
    document.getElementById('searchInput').addEventListener('input', function () {
        const keyword = this.value.trim();
        clearTimeout(searchDebounce);
        searchDebounce = setTimeout(() => {
            loadItems(keyword);
        }, 350);
    });

    // ========== INISIALISASI ==========
    document.addEventListener('DOMContentLoaded', function () {
        loadItems();
    });
</script>
@endsection