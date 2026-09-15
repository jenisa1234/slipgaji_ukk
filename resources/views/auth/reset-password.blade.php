<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Sistem Slip Gaji</title>
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
        <h4 class="fw-bold m-0" style="color: #2C3E50;">RESET PASSWORD</h4>
        <small class="text-muted">{{ $verified ? 'Buat password baru untuk akun Anda' : 'Masukkan kode yang dikirim ke email Anda' }}</small>
    </div>

    @if (session('status'))
        <div class="alert alert-success small">{{ session('status') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger small">{{ $errors->first() }}</div>
    @endif

    @if (!$verified)
    <form method="POST" action="{{ route('password.verify.code') }}">
        @csrf
        <p class="text-muted small mb-3">Kode verifikasi telah dikirim ke <strong>{{ $email }}</strong>.</p>

        <div class="mb-3">
            <label for="code" class="form-label fw-semibold">Kode Verifikasi</label>
            <input type="text" id="code" name="code" class="form-control" inputmode="numeric" maxlength="6" pattern="[0-9]{6}" placeholder="Masukkan 6 digit kode" required autofocus>
        </div>

        <button type="submit" class="btn btn-custom w-100">Verifikasi Kode</button>
    </form>
    @else
    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        <input type="hidden" name="email" value="{{ $email }}">

        <div class="mb-3">
            <label for="password" class="form-label fw-semibold">Password Baru</label>
            <input type="password" id="password" name="password" class="form-control" placeholder="Minimal 8 karakter" required autofocus>
        </div>

        <div class="mb-4">
            <label for="password_confirmation" class="form-label fw-semibold">Ulangi Password Baru</label>
            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Ulangi password" required>
        </div>

        <button type="submit" class="btn btn-custom w-100">Simpan Password Baru</button>
    </form>
    @endif
</div>
</body>
</html>