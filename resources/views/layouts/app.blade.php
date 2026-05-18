<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'CryptoPortal')</title>

    {{-- Page-specific CSS --}}
    @yield('styles')

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body class="@yield('body-class')">

    {{-- Navigation --}}
    <nav>
        <div class="container nav-container">
            <a href="{{ route('home') }}" class="logo">
                <span>CryptoPortal</span>
            </a>
            <ul class="nav-links">
                <li><a href="{{ route('home') }}" @class(['active' => request()->routeIs('home')])>Home</a></li>
                <li><a href="{{ route('libraries') }}" @class(['active' => request()->routeIs('libraries')])>Library</a></li>
                <li><a href="{{ route('about') }}" @class(['active' => request()->routeIs('about')])>About</a></li>
            </ul>
        </div>
    </nav>

    {{-- Page Content --}}
    @yield('content')

    {{-- Footer --}}
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-column">
                    <h3>CryptoPortal</h3>
                    <p>A leading information hub focused on modern cryptography and post-quantum technologies.</p>
                </div>
            </div>
            <br>
            <div class="copyright">
                <p>© 2025 Bahagian Integrasi Teknologi, Pusat Teknologi Dan Pengurusan Kriptologi Malaysia. All rights reserved.</p>
            </div>
        </div>
    </footer>

    {{-- Page-specific JS --}}
    @yield('scripts')

</body>
</html>
