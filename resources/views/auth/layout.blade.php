<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - CryptoLibraryCenter</title>
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body>
    <nav>
        <div class="container nav-container">
            <a href="{{ route('home') }}" class="logo">
                <span>CryptoLibraryCenter</span>
            </a>
            <ul class="nav-links">
                <li><a href="{{ route('home') }}">Home</a></li>
                <li><a href="{{ route('libraries') }}">Libraries</a></li>
                <li><a href="{{ route('about') }}">About</a></li>
            </ul>
        </div>
    </nav>

    <main class="auth-main">
        @yield('content')
    </main>

    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-column">
                    <h3>CryptoLibraryCenter</h3>
                    <p>A leading information hub focused on modern cryptography and post-quantum technologies.</p>
                </div>
            </div>
            <br>
            <div class="copyright">
                <p>&copy; 2025 CryptoLibraryCenter. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>
