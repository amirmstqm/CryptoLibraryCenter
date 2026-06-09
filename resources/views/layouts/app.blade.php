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

    {{-- Dark mode: apply immediately to prevent flash --}}
    <script>if (localStorage.getItem('darkMode') === 'true') document.documentElement.classList.add('dark');</script>
</head>

<body class="@yield('body-class')">

    {{-- Navigation --}}
    <nav>
        <div class="container nav-container">
            <a href="{{ route('landing') }}" class="logo">
                <span>CryptoPortal</span>
            </a>
            <ul class="nav-links">
                <!-- <li><a href="{{ route('libraries') }}" @class(['active' => request()->routeIs('libraries')])>Library</a></li> -->
            </ul>

            <div class="nav-auth">
                <button id="dark-toggle" class="dark-toggle" title="Toggle dark mode">
                    <i class="fas fa-moon" id="dark-icon"></i>
                </button>
            </div>
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

    <script>
        // Dark mode toggle
        const darkToggle = document.getElementById('dark-toggle');
        const darkIcon   = document.getElementById('dark-icon');

        function syncDarkIcon() {
            const isDark = document.documentElement.classList.contains('dark');
            darkIcon.className = isDark ? 'fas fa-sun' : 'fas fa-moon';
        }

        syncDarkIcon();

        if (darkToggle) {
            darkToggle.addEventListener('click', () => {
                const isDark = document.documentElement.classList.toggle('dark');
                localStorage.setItem('darkMode', isDark);
                syncDarkIcon();
            });
        }
    </script>

</body>
</html>
