<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Aset - {{ $item->kode }}</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        body {
            background-color: #f8fafc;
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
        }

        .detail-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .detail-card {
            background: #ffffff;
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
            border: 1px solid #f1f5f9;
            overflow: hidden;
            width: 100%;
            max-width: 420px; /* Ukuran pas untuk layar HP */
            position: relative;
        }

        .detail-header {
            background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
            padding: 40px 24px 60px;
            text-align: center;
            color: white;
        }

        .icon-wrapper {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        .detail-body {
            padding: 0 32px 32px;
            margin-top: -30px;
        }

        .info-box {
            background: #ffffff;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            border: 1px solid #e2e8f0;
        }

        .info-group {
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px dashed #e2e8f0;
        }

        .info-group:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }

        .info-label {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b;
            font-weight: 600;
            margin-bottom: 4px;
            display: block;
        }

        .info-value {
            font-size: 1.1rem;
            font-weight: 700;
            color: #1e293b;
        }

        .stock-badge {
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .stock-safe { background: #dcfce7; color: #16a34a; }
        .stock-low { background: #fee2e2; color: #dc2626; }
        
        .footer-text {
            text-align: center;
            margin-top: 24px;
            font-size: 0.8rem;
            color: #94a3b8;
            font-weight: 500;
        }
    </style>
</head>
<body>

    <div class="detail-container">
        <div class="detail-card">
            <div class="detail-header">
                <div class="">
                    <img src="{{ asset('logo.png') }}" alt="Logo Inventaris" style="width: 120px; height: 120px; ">
                </div>
                <h4 class="fw-bold mb-1">Identitas Aset</h4>
                <p class="mb-0" style="color: rgba(255, 255, 255, 0.8); font-size: 0.9rem;">Sistem Informasi Sarpras UMUKA</p>
            </div>

            <div class="detail-body">
                <div class="info-box">
                    
                    <div class="info-group">
                        <span class="info-label">Kode Inventaris</span>
                        <div class="info-value d-flex align-items-center gap-2">
                            <i data-lucide="barcode" style="width: 20px; color: #6366f1;"></i>
                            {{ $item->kode }}
                        </div>
                    </div>

                    <div class="info-group">
                        <span class="info-label">Nama Aset</span>
                        <div class="info-value text-wrap">
                            {{ $item->nama }}
                        </div>
                    </div>

                    <div class="info-group">
                        <span class="info-label">Ketersediaan Stok</span>
                        <div class="mt-2">
                            @if($item->stok > 10)
                                <span class="stock-badge stock-safe">
                                    <i data-lucide="check-circle-2" style="width: 18px;"></i>
                                    {{ $item->stok }} Unit Tersedia
                                </span>
                            @else
                                <span class="stock-badge stock-low">
                                    <i data-lucide="alert-circle" style="width: 18px;"></i>
                                    Sisa {{ $item->stok }} Unit
                                </span>
                            @endif
                        </div>
                    </div>

                </div>
                
                <div class="footer-text">
                    &copy; {{ date('Y') }} Aisyah Store
                </div>
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>