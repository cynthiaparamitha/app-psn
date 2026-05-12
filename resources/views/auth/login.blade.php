<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.95);
            padding: 40px 35px;
            width: 100%;
            max-width: 400px;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(10px);
            animation: fadeInUp 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94) both;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        h2 {
            margin-top: 0;
            margin-bottom: 8px;
            text-align: center;
            color: #1a252f;
            font-size: 28px;
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .subtitle {
            text-align: center;
            color: #7f8c8d;
            font-size: 14px;
            margin-bottom: 25px;
        }

        .error {
            background: #fdf2f2;
            padding: 12px 15px;
            border-left: 4px solid #f05252;
            color: #c81e1e;
            margin-bottom: 20px;
            border-radius: 8px;
            font-size: 14px;
            display: flex;
            align-items: center;
            font-weight: 500;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-size: 13px;
            color: #4b5563;
            font-weight: 600;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        input {
            width: 100%;
            padding: 12px 16px;
            border: 1.5px solid #e5e7eb;
            border-radius: 8px;
            font-size: 15px;
            color: #1f2937;
            background-color: #f9fafb;
            transition: all 0.2s ease;
        }

        input:focus {
            border-color: #3b82f6;
            background-color: #fff;
            outline: none;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15);
        }

        button {
            width: 100%;
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            padding: 14px;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
            transition: all 0.2s ease;
            margin-top: 10px;
        }

        button:hover {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            transform: translateY(-1px);
            box-shadow: 0 6px 15px rgba(37, 99, 235, 0.3);
        }

        button:active {
            transform: translateY(1px);
            box-shadow: 0 2px 6px rgba(37, 99, 235, 0.2);
        }

        .form-footer {
            margin-top: 25px;
            text-align: center;
            font-size: 13px;
            color: #6b7280;
        }

        .form-footer a {
            color: #3b82f6;
            text-decoration: none;
            font-weight: 500;
        }

        .form-footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="login-card">
    <h2>Selamat Datang</h2>
    <div class="subtitle">Login untuk mengakses aplikasi PSN</div>

    @if(session('error'))
        <div class="error">
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <form method="POST" action="/login">
        @csrf

        <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" placeholder="Masukkan username Anda" required>
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" placeholder="••••••••" required>
        </div>

        <button type="submit">Masuk ke Aplikasi</button>
    </form>

    <!-- <div class="form-footer">
        Lupa password? <a href="#">Hubungi Admin</a>
    </div> -->
</div>

</body>
</html>