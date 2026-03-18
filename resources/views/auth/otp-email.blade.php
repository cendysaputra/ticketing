<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h2>Login dengan OTP</h2>

    @if ($errors->any())
        <div style="color: red;">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('otp.send') }}">
        @csrf
        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>
        <button type="submit">Kirim OTP</button>
    </form>
</body>
</html>