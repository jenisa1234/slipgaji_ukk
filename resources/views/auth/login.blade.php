<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Penggajian Karyawan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8fafc;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            margin: 0;
        }
        .bg-blob-top-left {
            position: absolute;
            top: -80px; left: -80px;
            width: 320px; height: 320px;
            background: linear-gradient(135deg, rgba(210, 225, 255, 0.6) 0%, rgba(230, 240, 255, 0.1) 100%);
            border-radius: 50%; z-index: 0;
        }
        .bg-blob-bottom-right {
            position: absolute;
            bottom: -80px; right: -80px;
            width: 350px; height: 350px;
            background: linear-gradient(135deg, rgba(210, 240, 220, 0.5) 0%, rgba(215, 230, 255, 0.6) 100%);
            border-radius: 50%; z-index: 0;
        }
        .dots-top-right, .dots-bottom-left {
            position: absolute;
            width: 120px; height: 90px;
            background-image: radial-gradient(#cbd5e1 2px, transparent 2px);
            background-size: 14px 14px; z-index: 0;
        }
        .dots-top-right { top: 40px; right: 40px; }
        .dots-bottom-left { bottom: 40px; left: 40px; }
        .login-card {
            width: 100%; max-width: 460px;
            border: none; border-radius: 16px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.05);
            z-index: 1; background: #ffffff;
        }
        .form-control:focus { box-shadow: none; }
    </style>
</head>
<body>

    <div class="bg-blob-top-left"></div>
    <div class="bg-blob-bottom-right"></div>
    <div class="dots-top-right"></div>
    <div class="dots-bottom-left"></div>

    <div class="card login-card p-4">
        <div class="card-body text-center">
            <div class="mb-3">
                <svg width="60" height="60" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="mb-1">
                    <path d="M17 21V19C17 17.9391 16.5786 16.9217 15.8284 16.1716C15.0783 15.4214 14.0609 15 13 15H5C3.93913 15 2.92172 15.4214 2.17157 16.1716C1.42143 16.9217 1 17.9391 1 21" stroke="#4A90E2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M9 11C11.2091 11 13 9.20914 13 7C13 4.79086 11.2091 3 9 3C6.79086 3 5 4.79086 5 7C5 9.20914 6.79086 11 9 11Z" stroke="#4A90E2" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M22 21V19C21.9993 18.1137 21.6944 17.2528 21.1251 16.5505C20.5558 15.8483 19.7535 15.3444 18.85 15.11" stroke="#87B884" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M16 3.13C16.8604 3.35031 17.623 3.85071 18.1766 4.55231C18.7303 5.25392 19.0445 6.11702 19.0445 7.005C19.0445 7.89298 18.7303 8.75608 18.1766 9.45769C17.623 10.1593 16.8604 10.6597 16 10.88" stroke="#87B884" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            
            <h3 class="fw-bold text-dark mb-1">LOGIN</h3>
            <p class="text-muted small mb-4">Silakan masuk untuk melanjutkan<br>ke sistem penggajian</p>

            @if(session('status'))
                <div class="alert alert-success py-2 small">{{ session('status') }}</div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger py-2 small">{{ session('error') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger py-2 small">{{ $errors->first() }}</div>
            @endif

            <form action="{{ route('login') }}" method="POST" class="text-start">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label text-secondary small fw-bold">Email</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light text-muted"><i class="fas fa-envelope"></i></span>
                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="Masukkan email" required autofocus>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-semibold text-secondary">Password</label>
                    <div class="input-group border rounded px-2 bg-light">
                        <span class="input-group-text bg-transparent border-0 pe-1"><i class="fas fa-lock text-muted"></i></span>
                        <input type="password" name="password" id="password" class="form-control border-0 bg-light shadow-none ps-2" placeholder="Masukkan password" required>
                        <span class="input-group-text bg-transparent border-0 ps-1" style="cursor: pointer;" onclick="togglePassword()">
                            <i class="fas fa-eye text-muted" id="toggleIcon"></i>
                        </span>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4 small">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label text-muted" for="remember">Ingat saya</label>
                    </div>
                    <a href="{{ route('password.request') }}" class="text-decoration-none text-success fw-semibold">Lupa Password?</a>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold shadow-sm" style="background-color: #4A90E2; border: none; border-radius: 8px;">LOGIN</button>
            </form>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>