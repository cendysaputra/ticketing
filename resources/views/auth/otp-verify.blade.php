<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi OTP</title>
</head>
<body>
    <h2>Masukkan Kode OTP</h2>

    @if ($errors->any())
        <div style="color: red;">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('otp.verify') }}">
        @csrf
        <input type="hidden" name="email" value="{{ session('email') }}">

        <label>Kode OTP:</label><br>
        <input type="text" name="code" maxlength="6" required><br><br>
        <button type="submit">Verifikasi</button>
    </form>
</body>
</html>