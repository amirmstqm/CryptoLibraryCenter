<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login — CryptoPortal</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        .login-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #0f1c2d 0%, #1a2e42 50%, #2b4e6f 100%);
        }
        .login-card {
            background: #ffffff;
            border-radius: 16px;
            padding: 2.5rem 2.4rem;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.35);
        }
        .login-brand {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 2rem;
        }
        .login-logo {
            width: 80px;
            height: 80px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
        .login-brand-name  { font-size: 1.1rem; font-weight: 800; color: #182033; letter-spacing: 0.06em; }
        .login-brand-sub   { font-size: 0.72rem; font-weight: 600; color: #6b82a0; letter-spacing: 0.08em; }
        .login-title       { font-size: 1.4rem; font-weight: 800; color: #182033; margin-bottom: 1.8rem; text-align: center; }
        .login-form        { display: flex; flex-direction: column; gap: 1.2rem; }
        .login-label       { font-size: 0.78rem; font-weight: 800; color: #374151; letter-spacing: 0.06em; display: block; margin-bottom: 0.4rem; }
        .login-input {
            width: 100%; padding: 0.75rem 1rem; border: 1px solid #cdd8e8; border-radius: 8px;
            font-size: 0.93rem; color: #182033; background: #f4f7fc; font-family: inherit;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .login-input:focus { outline: none; border-color: #3a9de1; box-shadow: 0 0 0 3px rgba(58,157,225,0.12); background: #fff; }
        .login-remember    { display: flex; align-items: center; gap: 0.5rem; font-size: 0.88rem; color: #374151; cursor: pointer; }
        .login-remember input { accent-color: #3a9de1; width: 15px; height: 15px; cursor: pointer; }
        .login-btn {
            padding: 0.8rem 1.4rem; background: linear-gradient(135deg, #3a9de1 0%, #2e7cb5 100%);
            color: #fff; border: none; border-radius: 8px; font-size: 0.95rem; font-weight: 700;
            cursor: pointer; transition: all 0.2s; margin-top: 0.4rem;
        }
        .login-btn:hover { background: linear-gradient(135deg, #2e7cb5 0%, #1f5a8f 100%); transform: translateY(-1px); }
        .login-error { background: #fee2e2; color: #7f1d1d; border-left: 4px solid #ef4444; padding: 0.7rem 1rem; border-radius: 6px; font-size: 0.88rem; }
    </style>
</head>
<body class="admin-body">

<div class="login-page">
    <div class="login-card">

        <div class="login-brand">
            <div class="login-logo"><img src="{{ asset('images/ptpkm1.png') }}" alt="PTPKM"></div>
            <span class="login-brand-name">PTPKM</span>
            <span class="login-brand-sub">CRYPTO PORTAL — ADMIN</span>
        </div>

        @if($errors->any())
        <div class="login-error" style="margin-bottom:1.2rem">
            {{ $errors->first() }}
        </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="login-form">
            @csrf

            <div>
                <label for="email" class="login-label">EMAIL ADDRESS</label>
                <input type="email" id="email" name="email"
                       value="{{ old('email') }}"
                       class="login-input" required autofocus>
            </div>

            <div>
                <label for="password" class="login-label">PASSWORD</label>
                <input type="password" id="password" name="password"
                       class="login-input" required>
            </div>

            <label class="login-remember">
                <input type="checkbox" name="remember"> Remember me
            </label>

            <button type="submit" class="login-btn">
                <i class="fas fa-sign-in-alt"></i> Sign In
            </button>
        </form>

    </div>
</div>

</body>
</html>
