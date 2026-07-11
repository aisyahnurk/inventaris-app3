@extends('layouts.app')

@section('content')
<style>
    .table-card { background: #ffffff; border-radius: 20px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03); border: 1px solid #f1f5f9; overflow: hidden; }
    .table-header { padding: 24px; border-bottom: 1px solid #f1f5f9; }
    .search-box { position: relative; max-width: 320px; }
    .search-box input { border-radius: 12px; padding: 10px 16px 10px 42px; border: 1.5px solid #e2e8f0; font-size: 0.85rem; width: 100%; transition: all 0.2s; }
    .search-box input:focus { outline: none; border-color: #6366f1; box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1); }
    .search-box i { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #94a3b8; width: 16px; height: 16px; }
    .custom-table { margin-bottom: 0; }
    .custom-table thead th { background-color: #f8fafc; color: #64748b; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; padding: 16px 24px; border-bottom: none; font-weight: 600; }
    .custom-table tbody td { padding: 16px 24px; vertical-align: middle; color: #334155; border-bottom: 1px solid #f1f5f9; font-size: 0.9rem; }
    .custom-table tbody tr:hover { background-color: #f8fafc; transition: 0.2s ease; }
    .jenis-badge { padding: 6px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: 600; display: inline-block; }
    .jenis-masuk { background: #dcfce7; color: #16a34a; }
    .jenis-keluar { background: #fee2e2; color: #dc2626; }
    .filter-select { border-radius: 12px; padding: 10px 16px; border: 1.5px solid #e2e8f0; font-size: 0.85rem; }
    .empty-state { padding: 60px 20px; text-align: center; }
    .skeleton-row td { padding: 18px 24px; }
    .skeleton-bar { height: 14px; border-radius: 6px; background: linear-gradient(90deg, #f1f5f9 25%, #e2e8f0 50%, #f1f5f9 75%); background-size: 200% 100%; animation: skeleton-loading 1.2s infinite; }
    @keyframes skeleton-loading { 0% { background-position: 200% 0; } 100% { background-position: -200% 0; } }
</style>

<div class="table-card">
    <div class="table-header d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <h5 class="mb-1 fw-bold text-dark">Riwayat Transaksi Barang</h5>
            <p class="mb-0 text-muted small">Lihat riwayat mutasi barang masuk & keluar (read-only)</p>
        </div>

        <div class="d-flex align-items-center gap-3 flex-wrap flex-grow-1 flex-md-grow-0 justify-content-md-end">
            <select id="filterJenis" class="filter-select">
                <option value="">Semua Jenis</option>
                <option value="masuk">Barang Masuk</option>
                <option value="keluar">Barang Keluar</option>
            </select>

            <div class="search-box position-relative flex-grow-1 flex-md-grow-0" style="min-width: 220px;">
                <i data-lucide="search" class="text-muted position-absolute top-50 start-0 translate-middle-y ms-3" style="width: 18px; pointer-events: none; z-index: 5;"></i>
                <input type="text" id="searchInput" class="form-control ps-5" placeholder="Cari nama/kode barang" style="border-radius: 10px; padding-top: 9px; padding-bottom: 9px; border: 1.5px solid #e2e8f0; font-size: 0.9rem; width: 100%;">
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table custom-table">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th>Barang</th>
                    <th width="12%">Jenis</th>
                    <th width="10%">Jumlah</th>
                    <th width="12%">Tanggal</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody id="trxTableBody"></tbody>
        </table>
    </div>
</div>

<script>
    if (typeof lucide !== 'undefined') lucide.createIcons();

    const TRX_DATA_URL = "{{ route('user.transaksi.data') }}";
    let searchDebounce = null;

    function renderSkeleton() {
        const tbody = document.getElementById('trxTableBody');
        let rows = '';
        for (let i = 0; i < 4; i++) {
            rows += `<tr class="skeleton-row">
                <td><div class="skeleton-bar" style="width: 20px;"></div></td>
                <td><div class="skeleton-bar" style="width: 140px;"></div></td>
                <td><div class="skeleton-bar" style="width: 70px;"></div></td>
                <td><div class="skeleton-bar" style="width: 40px;"></div></td>
                <td><div class="skeleton-bar" style="width: 80px;"></div></td>
                <td><div class="skeleton-bar" style="width: 120px;"></div></td>
            </tr>`;
        }
        tbody.innerHTML = rows;
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text ?? '';
        return div.innerHTML;
    }

    function renderRows(data) {
        const tbody = document.getElementById('trxTableBody');

        if (!data || data.length === 0) {
            tbody.innerHTML = `<tr><td colspan="6">
                <div class="empty-state">
                    <i data-lucide="inbox" style="width: 64px; height: 64px; color: #cbd5e1; margin-bottom: 16px;"></i>
                    <h6 class="fw-bold text-dark">Belum ada transaksi</h6>
                </div>
            </td></tr>`;
            if (typeof lucide !== 'undefined') lucide.createIcons();
            return;
        }

        let rows = '';
        data.forEach((trx, index) => {
            const jenisClass = trx.jenis === 'masuk' ? 'jenis-masuk' : 'jenis-keluar';
            const jenisLabel = trx.jenis === 'masuk' ? 'Barang Masuk' : 'Barang Keluar';
            rows += `<tr>
                <td>${index + 1}</td>
                <td class="fw-medium text-dark">${escapeHtml(trx.item_nama)} <span class="text-muted small">(${escapeHtml(trx.item_kode)})</span></td>
                <td><span class="jenis-badge ${jenisClass}">${jenisLabel}</span></td>
                <td>${trx.jumlah}</td>
                <td>${trx.tanggal}</td>
                <td class="text-muted">${escapeHtml(trx.keterangan) || '-'}</td>
            </tr>`;
        });

        tbody.innerHTML = rows;
        if (typeof lucide !== 'undefined') lucide.createIcons();
    }

    function loadTransactions() {
        renderSkeleton();
        const keyword = document.getElementById('searchInput').value.trim();
        const jenis = document.getElementById('filterJenis').value;

        const params = new URLSearchParams();
        if (keyword) params.set('search', keyword);
        if (jenis) params.set('jenis', jenis);

        const url = params.toString() ? `${TRX_DATA_URL}?${params.toString()}` : TRX_DATA_URL;

        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' } })
            .then(res => res.json())
            .then(result => { if (result.success) renderRows(result.data); })
            .catch(() => toastr.error('Gagal memuat data transaksi.'));
    }

    document.getElementById('searchInput').addEventListener('input', function () {
        clearTimeout(searchDebounce);
        searchDebounce = setTimeout(loadTransactions, 350);
    });
    document.getElementById('filterJenis').addEventListener('change', loadTransactions);

    document.addEventListener('DOMContentLoaded', loadTransactions);
</script>
@endsection
