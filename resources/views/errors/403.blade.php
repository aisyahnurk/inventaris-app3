<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Akses Ditolak</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f8fafc;
            background-image:
                radial-gradient(at 0% 0%, rgba(239, 68, 68, 0.12) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(99, 102, 241, 0.12) 0px, transparent 50%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            color: #1e293b;
        }
        .error-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-radius: 24px;
            padding: 50px 40px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.08);
            text-align: center;
            max-width: 460px;
        }
        .error-icon {
            width: 70px; height: 70px;
            background: #fee2e2; color: #dc2626;
            border-radius: 18px;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 24px;
        }
        .error-code {
            font-size: 3rem; font-weight: 700; color: #dc2626;
            margin-bottom: 4px; line-height: 1;
        }
        .error-title { font-weight: 700; font-size: 1.25rem; margin-bottom: 12px; }
        .error-message { color: #64748b; font-size: 0.9rem; margin-bottom: 28px; }
        .btn-back {
            background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
            color: white; border: none; border-radius: 12px;
            padding: 12px 28px; font-weight: 600; font-size: 0.875rem;
            text-decoration: none; display: inline-flex; align-items: center; gap: 8px;
            transition: all 0.3s;
        }
        .btn-back:hover {
            opacity: 0.9; color: white; transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(99, 102, 241, 0.3);
        }
    </style>
</head>
<body>
    <div class="error-card">
        <div class="error-icon">
            <i data-lucide="shield-alert" style="width: 32px; height: 32px;"></i>
        </div>
        <div class="error-code">403</div>
        <div class="error-title">Akses Dibatasi / Forbidden</div>
        <p class="error-message">
            {{ $exception->getMessage() ?: 'Anda tidak memiliki izin untuk mengakses halaman ini.' }}
        </p>
        <a href="/login" class="btn-back">
            <i data-lucide="arrow-left" style="width: 16px;"></i> Kembali ke Login
        </a>
    </div>
    <script>
        lucide.createIcons();
    </script>
</body>
</html>