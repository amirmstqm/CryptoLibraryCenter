<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - CryptoLibraryCenter</title>
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    {{-- Dark mode: apply immediately to prevent flash --}}
    <script>if (localStorage.getItem('darkMode') === 'true') document.documentElement.classList.add('dark');</script>
</head>
<body>
    <nav>
        <div class="container nav-container">
            <a href="{{ route('login') }}" class="logo">
                <span>CryptoLibraryCenter</span>
            </a>
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
<script>
    function togglePassword(fieldId, btn) {
        const input = document.getElementById(fieldId);
        const icon  = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }
</script>
