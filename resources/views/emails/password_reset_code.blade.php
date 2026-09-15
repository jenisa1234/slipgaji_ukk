<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ config('ui.email.reset_subject', 'Kode Reset Password') }}</title>
</head>
<body>
    <p>Gunakan kode berikut untuk mengatur ulang password Anda:</p>
    <p style="font-size: 28px; font-weight: bold; letter-spacing: 8px;">{{ $code }}</p>
    <p>Kode ini berlaku sampai {{ $expiresAt->format('d-m-Y H:i') }}.</p>
    <p>Jika Anda tidak meminta reset password, abaikan email ini.</p>
</body>
</html>
