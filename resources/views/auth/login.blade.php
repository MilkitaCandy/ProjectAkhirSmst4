<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Manajemen Aset IT</title>
</head>
<body>
    <h2>Login Sistem Manajemen Aset IT</h2>

    <!-- Menampilkan pesan error jika login gagal -->
    @if ($errors->any())
        <div style="color: red;">
            {{ $errors->first() }}
        </div>
    @endif

    <form action="/login" method="POST">
        @csrf <!-- Wajib ada untuk keamanan form di Laravel -->
        
        <div>
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required>
        </div>
        <br>
        
        <div>
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>
        </div>
        <br>

        <button type="submit">Login</button>
    </form>
</body>
</html>