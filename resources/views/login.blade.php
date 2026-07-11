<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Manajemen Inventaris - Login</title>
    
    <!-- Poppins Font & Lucide Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-grad: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
            --bg-color: #f8fafc;
            --text-dark: #1e293b;
            --text-muted: #64748b;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--bg-color);
            background-image: 
                radial-gradient(at 0% 0%, rgba(99, 102, 241, 0.15) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(168, 85, 247, 0.15) 0px, transparent 50%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }

        .auth-container {
            width: 100%;
            max-width: 420px;
            padding: 20px;
        }

        .auth-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.7);
            border-radius: 24px;
            padding: 40px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.08);
        }

        .brand-logo {
            width: 54px;
            height: 54px;
            background: var(--primary-grad);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            margin: 0 auto 24px;
            box-shadow: 0 10px 15px -3px rgba(99, 102, 241, 0.3);
        }

        .auth-header h2 {
            font-weight: 700;
            color: var(--text-dark);
            font-size: 1.5rem;
            text-align: center;
            margin-bottom: 8px;
        }

        .auth-header p {
            color: var(--text-muted);
            text-align: center;
            font-size: 0.875rem;
            margin-bottom: 32px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            font-size: 0.813rem;
            font-weight: 600;
            color: var(--text-dark);
            margin-left: 4px;
            margin-bottom: 8px;
            display: block;
        }

        .input-wrapper {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            width: 18px;
            height: 18px;
        }

        .form-control {
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            padding: 12px 16px 12px 48px;
            font-size: 0.875rem;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
            outline: none;
        }

        .btn-submit {
            background: var(--primary-grad);
            color: white;
            border: none;
            border-radius: 12px;
            padding: 14px;
            font-weight: 600;
            font-size: 0.875rem;
            width: 100%;
            margin-top: 10px;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .btn-submit:hover {
            opacity: 0.9;
            transform: translateY(-1px);
            box-shadow: 0 10px 15px -3px rgba(99, 102, 241, 0.3);
        }

        .alert-custom {
            background: #fff5f5;
            border-left: 4px solid #f56565;
            color: #c53030;
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 0.813rem;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
        }
    </style>
</head>
<body>

    <div class="auth-container">
        <div class="auth-card">
            <div class="" style="display: flex;justify-content: center; align-items: center;">
                <!-- Panggil logo.png dari folder public pakai helper asset() -->
                <img src="{{ asset('logo.png') }}" alt="Logo Inventaris" style="width: 120px; height: auto;">
            </div>
            
            <div class="auth-header">
                <h2>Login</h2>
                <p>Silahkan Masuk Terlebih Dahulu</p>
            </div>

            @if(session('error'))
            <div class="alert-custom">
                <i data-lucide="alert-circle" style="width: 18px; margin-right: 10px;"></i>
                {{ session('error') }}
            </div>
            @endif

            <form action="/login" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <div class="input-wrapper">
                        <i data-lucide="mail" class="input-icon"></i>
                        <input type="email" name="email" class="form-control" placeholder="Masukkan Email" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Kata Sandi</label>
                    <div class="input-wrapper">
                        <i data-lucide="lock" class="input-icon"></i>
                        <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                    </div>
                </div>

                <button type="submit" class="btn-submit">Masuk Sekarang</button>
            </form>
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>