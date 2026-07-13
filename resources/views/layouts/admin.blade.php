<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin — CryptoPortal')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="admin-body">

<div class="admin-wrapper">

    {{-- ── Sidebar ──────────────────────────────────── --}}
    <aside class="admin-sidebar">

        <div class="sidebar-header">
            <div class="sidebar-logo-wrap">
                <img src="{{ asset('images/ptpkm1.png') }}" alt="PTPKM" class="sidebar-logo-img">
            </div>
        </div>

        <nav class="sidebar-nav">
            <a href="{{ route('admin.dashboard') }}"
               class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fas fa-th-large"></i></span>
                <span class="nav-label">Dashboard</span>
            </a>
            <a href="{{ route('admin.add') }}"
               class="nav-item {{ request()->routeIs('admin.add') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fas fa-plus-circle"></i></span>
                <span class="nav-label">Add</span>
            </a>
            <a href="{{ route('admin.browse') }}"
               class="nav-item {{ request()->routeIs('admin.browse') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fas fa-search"></i></span>
                <span class="nav-label">Browse</span>
            </a>
        </nav>

        <div class="sidebar-footer">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="signout-btn">
                    <span class="nav-icon"><i class="fas fa-sign-out-alt"></i></span>
                    <span class="nav-label">Sign Out</span>
                </button>
            </form>
        </div>

    </aside>

    {{-- ── Main ─────────────────────────────────────── --}}
    <div class="admin-main">

        {{-- Top Header --}}
        <header class="admin-topbar">
            <div class="topbar-left">
                @yield('page-heading')
            </div>
            <div class="topbar-right">
                @auth
                <div class="topbar-user">
                    @if(Auth::user()->profile_picture)
                        <img src="{{ Auth::user()->profile_picture }}"
                             class="topbar-avatar" alt="Avatar">
                    @else
                        <div class="topbar-avatar-placeholder">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                    @endif
                    <div class="topbar-greeting">
                        <span class="greeting-name">Hello, {{ Auth::user()->name }}</span>
                        <span class="greeting-sub">Welcome Back !</span>
                    </div>
                </div>
                @endauth
            </div>
        </header>

        {{-- Flash Messages --}}
        @if(session('success'))
        <div class="flash flash-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="flash flash-error">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
        @endif

        {{-- Page Body --}}
        <div class="admin-content">
            @yield('content')
        </div>

    </div>{{-- /.admin-main --}}

</div>{{-- /.admin-wrapper --}}

@yield('scripts')
</body>
</html>
