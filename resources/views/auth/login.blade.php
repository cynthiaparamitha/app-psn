<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Perumda Tirta Patriot</title>
    
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #1f2d3d, #34495e);
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .login-card {
            background: #ffffff;
            padding: 35px 30px;
            width: 100%;
            max-width: 400px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
            animation: fadeInUp 0.4s ease-out both;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .logo-container {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo-container img {
            max-height: 85px;
            width: auto;
            object-fit: contain;
        }

        h2 {
            margin-top: 0;
            margin-bottom: 6px;
            text-align: center;
            color: #2c3e50;
            font-size: 22px;
            font-weight: bold;
        }

        .subtitle {
            text-align: center;
            color: #7f8c8d;
            font-size: 13.5px;
            margin-bottom: 25px;
        }

        .error {
            background: #fdf2f2;
            padding: 12px 15px;
            border-left: 4px solid #c0392b;
            color: #c0392b;
            margin-bottom: 20px;
            border-radius: 4px;
            font-size: 13px;
            font-weight: bold;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            font-size: 13px;
            color: #34495e;
            font-weight: bold;
            margin-bottom: 6px;
        }

        input {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid #bbb;
            border-radius: 5px;
            font-size: 14px;
            color: #2c3e50;
            background-color: #fff;
            transition: all 0.2s ease;
            box-sizing: border-box;
        }

        input:focus {
            border-color: #3498db;
            outline: none;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
        }

        button {
            width: 100%;
            background: #3498db;
            padding: 12px;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 15px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.2s ease;
            margin-top: 10px;
        }

        button:hover {
            background: #2980b9;
        }

        button:active {
            background: #2471a3;
        }
    </style>
</head>
<body>

<div class="login-card">

    <div class="logo-container">
        <img src="{{ asset('images/logo.png') }}" alt="Logo Perumda Tirta Patriot">
    </div>

    <h2>Selamat Datang</h2>
    <div class="subtitle">Login untuk mengakses aplikasi PSN</div>

    @if(session('error'))
        <div class="error">
            <span>⚠️ {{ session('error') }}</span>
        </div>
    @endif

    <form method="POST" action="/login">
        @csrf

        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" placeholder="Masukkan username Anda" autocomplete="username" required>
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" placeholder="••••••••" autocomplete="current-password" required>
        </div>

        <button type="submit">Masuk ke Aplikasi</button>
    </form>
</div>

</body>
</html>