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
    .action-btn { width: 36px; height: 36px; display: inline-flex; align-items: center; justify-content: center; border-radius: 10px; transition: 0.3s; border: none; }
    .btn-delete { background: #fee2e2; color: #dc2626; }
    .btn-delete:hover { background: #fecaca; transform: translateY(-2px); }
    .btn-add { background: var(--primary-grad); color: white; border: none; border-radius: 12px; padding: 10px 20px; font-weight: 500; font-size: 0.9rem; transition: 0.3s; }
    .btn-add:hover { opacity: 0.9; color: white; transform: translateY(-2px); box-shadow: 0 10px 15px -3px rgba(99, 102, 241, 0.3); }
    .filter-select { border-radius: 12px; padding: 10px 16px; border: 1.5px solid #e2e8f0; font-size: 0.85rem; }
    .empty-state { padding: 60px 20px; text-align: center; }
    .skeleton-row td { padding: 18px 24px; }
    .skeleton-bar { height: 14px; border-radius: 6px; background: linear-gradient(90deg, #f1f5f9 25%, #e2e8f0 50%, #f1f5f9 75%); background-size: 200% 100%; animation: skeleton-loading 1.2s infinite; }
    @keyframes skeleton-loading { 0% { background-position: 200% 0; } 100% { background-position: -200% 0; } }
    .modal-content { border-radius: 20px; border: none; }
    .modal-header { border-bottom: 1px solid #f1f5f9; padding: 24px; }
    .modal-body { padding: 24px; }
    .modal-footer { border-top: 1px solid #f1f5f9; padding: 20px 24px; }
    .form-label { font-size: 0.85rem; font-weight: 600; color: #475569; margin-bottom: 8px; }
    .form-control, .form-select { border-radius: 12px; padding: 12px 16px; border: 1.5px solid #e2e8f0; font-size: 0.9rem; transition: all 0.2s; }
    .form-control:focus, .form-select:focus { border-color: #6366f1; box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1); }
    .btn-save { background: var(--primary-grad); color: white; border: none; border-radius: 12px; padding: 10px 22px; font-weight: 600; font-size: 0.9rem; transition: 0.3s; }
    .btn-save:hover { opacity: 0.9; color: white; }
</style>

<div class="table-card">
    <div class="table-header d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <h5 class="mb-1 fw-bold text-dark">Transaksi Barang Masuk & Keluar</h5>
            <p class="mb-0 text-muted small">Catat mutasi stok barang secara real-time</p>
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

            <button type="button" class="btn-add d-flex align-items-center gap-2 m-0" data-bs-toggle="modal" data-bs-target="#transaksiModal" onclick="openCreateModal()" style="height: 42px; white-space: nowrap;">
                <i data-lucide="plus" style="width: 18px;"></i> Input Transaksi
            </button>
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
                    <th width="12%">Diinput Oleh</th>
                    <th width="8%" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody id="trxTableBody"></tbody>
        </table>
    </div>
</div>

<!-- Modal Input Transaksi -->
<div class="modal fade" id="transaksiModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="transaksiForm">
                <div class="modal-header">
                    <h5 class="fw-bold mb-0">Input Transaksi Barang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Barang</label>
                        <select id="trxItemId" class="form-select" required>
                            <option value="">-- Pilih Barang --</option>
                            @foreach($items as $item)
                                <option value="{{ $item->id }}">{{ $item->nama }} ({{ $item->kode }}) - Stok: {{ $item->stok }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback" id="error-item_id"></div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jenis Transaksi</label>
                            <select id="trxJenis" class="form-select" required>
                                <option value="masuk">Barang Masuk</option>
                                <option value="keluar">Barang Keluar</option>
                            </select>
                            <div class="invalid-feedback" id="error-jenis"></div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jumlah</label>
                            <input type="number" id="trxJumlah" class="form-control" placeholder="0" min="1" required>
                            <div class="invalid-feedback" id="error-jumlah"></div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tanggal</label>
                        <input type="date" id="trxTanggal" class="form-control" required>
                        <div class="invalid-feedback" id="error-tanggal"></div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Keterangan</label>
                        <input type="text" id="trxKeterangan" class="form-control" placeholder="Contoh: Pembelian dari supplier A (opsional)">
                        <div class="invalid-feedback" id="error-keterangan"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" data-bs-dismiss="modal" style="background:#f1f5f9; color:#64748b; border-radius:12px; font-weight:600; font-size:0.9rem; padding:10px 22px;">Batal</button>
                    <button type="submit" class="btn-save d-flex align-items-center gap-2">
                        <i data-lucide="check" style="width: 18px;"></i> Simpan Transaksi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    if (typeof lucide !== 'undefined') lucide.createIcons();

    const TRX_DATA_URL = "{{ route('admin.transaksi.data') }}";
    const TRX_STORE_URL = "{{ route('admin.transaksi.store') }}";
    const TRX_DELETE_URL_BASE = "{{ url('/admin/transaksi') }}";
    const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    let trxModal, trxForm;
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
                <td><div class="skeleton-bar" style="width: 80px;"></div></td>
                <td><div class="skeleton-bar" style="width: 40px; margin: 0 auto;"></div></td>
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
            tbody.innerHTML = `<tr><td colspan="8">
                <div class="empty-state">
                    <i data-lucide="inbox" style="width: 64px; height: 64px; color: #cbd5e1; margin-bottom: 16px;"></i>
                    <h6 class="fw-bold text-dark">Belum ada transaksi</h6>
                    <p class="text-muted small">Klik "Input Transaksi" untuk mencatat barang masuk/keluar.</p>
                </div>
            </td></tr>`;
            if (typeof lucide !== 'undefined') lucide.createIcons();
            return;
        }

        let rows = '';
        data.forEach((trx, index) => {
            const jenisClass = trx.jenis === 'masuk' ? 'jenis-masuk' : 'jenis-keluar';
            const jenisLabel = trx.jenis === 'masuk' ? 'Barang Masuk' : 'Barang Keluar';
            rows += `<tr data-id="${trx.id}">
                <td>${index + 1}</td>
                <td class="fw-medium text-dark">${escapeHtml(trx.item_nama)} <span class="text-muted small">(${escapeHtml(trx.item_kode)})</span></td>
                <td><span class="jenis-badge ${jenisClass}">${jenisLabel}</span></td>
                <td>${trx.jumlah}</td>
                <td>${trx.tanggal}</td>
                <td class="text-muted">${escapeHtml(trx.keterangan) || '-'}</td>
                <td class="text-muted">${escapeHtml(trx.user_nama) || '-'}</td>
                <td>
                    <div class="d-flex justify-content-center gap-2">
                        <button type="button" class="action-btn btn-delete" title="Hapus Transaksi" data-action="delete" data-id="${trx.id}">
                            <i data-lucide="trash-2" style="width: 18px;"></i>
                        </button>
                    </div>
                </td>
            </tr>`;
        });

        tbody.innerHTML = rows;
        if (typeof lucide !== 'undefined') lucide.createIcons();
    }

    document.getElementById('trxTableBody').addEventListener('click', function (e) {
        const btn = e.target.closest('button[data-action="delete"]');
        if (!btn) return;
        confirmDelete(btn.getAttribute('data-id'));
    });

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

    function openCreateModal() {
        trxForm.reset();
        clearFormErrors();
        document.getElementById('trxTanggal').value = new Date().toISOString().split('T')[0];
    }

    function clearFormErrors() {
        const idMap = { item_id: 'trxItemId', jenis: 'trxJenis', jumlah: 'trxJumlah', tanggal: 'trxTanggal', keterangan: 'trxKeterangan' };
        Object.values(idMap).forEach(elId => document.getElementById(elId).classList.remove('is-invalid'));
        Object.keys(idMap).forEach(field => {
            const errorEl = document.getElementById(`error-${field}`);
            if (errorEl) errorEl.textContent = '';
        });
    }

    function handleFormSubmit(e) {
        e.preventDefault();
        clearFormErrors();

        const payload = {
            item_id: document.getElementById('trxItemId').value,
            jenis: document.getElementById('trxJenis').value,
            jumlah: document.getElementById('trxJumlah').value,
            tanggal: document.getElementById('trxTanggal').value,
            keterangan: document.getElementById('trxKeterangan').value,
        };

        fetch(TRX_STORE_URL, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json', 'Accept': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN, 'X-Requested-With': 'XMLHttpRequest',
            },
            body: JSON.stringify(payload)
        })
        .then(res => res.json().then(data => ({ status: res.status, body: data })))
        .then(({ status, body }) => {
            if (status === 201) {
                trxModal.hide();
                toastr.success(body.message);
                loadTransactions();
            } else if (status === 422) {
                const errors = body.errors || {};
                const idMap = { item_id: 'trxItemId', jenis: 'trxJenis', jumlah: 'trxJumlah', tanggal: 'trxTanggal', keterangan: 'trxKeterangan' };
                Object.keys(errors).forEach(field => {
                    const input = document.getElementById(idMap[field]);
                    const errorEl = document.getElementById(`error-${field}`);
                    if (input && errorEl) {
                        input.classList.add('is-invalid');
                        errorEl.textContent = errors[field][0];
                    }
                });
            } else {
                toastr.error(body.message || 'Terjadi kesalahan.');
            }
        })
        .catch(() => toastr.error('Gagal menghubungi server.'));
    }

    function confirmDelete(id) {
        Swal.fire({
            title: 'Hapus Transaksi?',
            text: 'Stok barang akan dikembalikan seperti sebelum transaksi ini dibuat.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#6366f1',
            cancelButtonColor: '#cbd5e1',
            confirmButtonText: 'Ya, Hapus Saja',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            customClass: { popup: 'rounded-4' }
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`${TRX_DELETE_URL_BASE}/${id}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': CSRF_TOKEN, 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                })
                .then(res => res.json())
                .then(body => {
                    if (body.success) { toastr.success(body.message); loadTransactions(); }
                    else toastr.error(body.message || 'Gagal menghapus data.');
                })
                .catch(() => toastr.error('Gagal menghubungi server.'));
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        trxModal = new bootstrap.Modal(document.getElementById('transaksiModal'));
        trxForm = document.getElementById('transaksiForm');
        trxForm.addEventListener('submit', handleFormSubmit);
        loadTransactions();
    });
</script>
@endsection
