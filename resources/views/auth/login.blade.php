<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login</title>

    <style>
        body {
            font-family: 'Segoe UI', Tahoma, sans-serif;
            background: linear-gradient(135deg, #4a90e2, #50c9c3);
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-card {
            background: white;
            padding: 35px 40px;
            width: 340px;
            border-radius: 12px;
            box-shadow: 0 6px 18px rgba(0,0,0,0.15);
            animation: fadeIn 0.4s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        h2 {
            margin-top: 0;
            margin-bottom: 10px;
            text-align: center;
            color: #333;
            font-weight: 600;
        }

        .error {
            background: #ffeded;
            padding: 10px;
            border-left: 4px solid #ff5757;
            color: #c0392b;
            margin-bottom: 15px;
            border-radius: 6px;
            font-size: 14px;
        }

        label {
            font-size: 14px;
            color: #444;
            font-weight: 500;
        }

        input {
            width: 100%;
            padding: 10px 12px;
            margin-top: 6px;
            margin-bottom: 18px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 15px;
            transition: 0.2s;
        }

        input:focus {
            border-color: #4a90e2;
            outline: none;
            box-shadow: 0 0 5px rgba(74,144,226,0.4);
        }

        button {
            width: 100%;
            background: #4a90e2;
            padding: 10px;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.2s;
        }

        button:hover {
            background: #3b7bc1;
        }
    </style>

</head>
<body>

<div class="login-card">
    <h2>Login</h2>

    @if(session('error'))
        <div class="error">{{ session('error') }}</div>
    @endif

    <form method="POST" action="/login">
        @csrf

        <label>Username</label>
        <input type="text" name="username" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit">Masuk</button>
    </form>
</div>

</body>
</html>