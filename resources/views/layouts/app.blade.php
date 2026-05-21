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
            <a href="{{ route('libraries') }}" class="logo">
                <span>CryptoPortal</span>
            </a>
            <ul class="nav-links">
                <!-- <li><a href="{{ route('libraries') }}" @class(['active' => request()->routeIs('libraries')])>Library</a></li> -->
            </ul>

            <div class="nav-auth">
                <button id="dark-toggle" class="dark-toggle" title="Toggle dark mode">
                    <i class="fas fa-moon" id="dark-icon"></i>
                </button>
                @auth
                    <div class="user-menu">
                        <button class="user-menu-btn" id="userMenuBtn">
                            @if (auth()->user()->profile_picture)
                                <img src="{{ asset('storage/' . auth()->user()->profile_picture) }}" alt="{{ auth()->user()->name }}" class="user-avatar">
                            @else
                                <div class="user-avatar-placeholder">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                            @endif
                            <span class="user-name">{{ auth()->user()->name }}</span>
                        </button>
                        <div class="user-dropdown" id="userDropdown">
                            <a href="{{ route('profile') }}" class="dropdown-item">
                                <i class="fas fa-user"></i> My Profile
                            </a>
                            <a href="{{ route('about') }}" class="dropdown-item">
                                <i class="fas fa-info-circle"></i> About
                            </a>
                            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                                @csrf
                                <button type="submit" class="dropdown-item logout-btn">
                                    <i class="fas fa-sign-out-alt"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="nav-auth-links">
                        <a href="{{ route('login') }}" class="btn-login">Sign In</a>
                        <a href="{{ route('register') }}" class="btn-register">Register</a>
                    </div>
                @endauth
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
        // User menu dropdown
        const userMenuBtn = document.getElementById('userMenuBtn');
        const userDropdown = document.getElementById('userDropdown');

        if (userMenuBtn) {
            userMenuBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                userDropdown.classList.toggle('active');
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', () => {
                userDropdown.classList.remove('active');
            });
        }

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
