<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - Sistem Slip Gaji</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f7f6; min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .auth-card { width: 100%; max-width: 420px; border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); background: #fff; padding: 2rem; }
        .btn-custom { background-color: #4A90E2; color: #fff; padding: 10px; border-radius: 8px; font-weight: 600; border: none; }
        .btn-custom:hover { background-color: #357ABD; color: #fff; }
    </style>
</head>
<body>
<div class="auth-card">
    <div class="text-center mb-4">
        <h4 class="fw-bold m-0" style="color: #2C3E50;">LUPA PASSWORD</h4>
        <small class="text-muted">Masukkan email akun terdaftar Anda</small>
    </div>

    @if (session('status'))
        <div class="alert alert-success small">{{ session('status') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger small">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <label for="email" class="form-label fw-semibold">Email</label>
        <input type="email" id="email" name="email" class="form-control mb-3" value="{{ old('email') }}" placeholder="email@anda.com" required autofocus>
        <button type="submit" class="btn btn-custom w-100 mb-3">Kirim Kode Reset</button>
        <a href="{{ route('login') }}" class="d-block text-center text-decoration-none text-muted">Kembali ke Login</a>
    </form>
</div>
</body>
</html>