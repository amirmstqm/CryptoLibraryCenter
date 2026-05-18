@extends('auth.layout')

@section('title', 'Login')

@section('content')
<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h1>Welcome Back</h1>
            <p class="auth-subtitle">Sign in to your CryptoLibraryCenter account</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <div class="alert-title">Login Failed</div>
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}" class="auth-form">
            @csrf

            <div class="form-group">
                <label for="email">Email Address</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    value="{{ old('email') }}"
                    class="form-control @error('email') is-invalid @enderror"
                    placeholder="you@example.com"
                    required
                    autofocus
                >
                @error('email')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    class="form-control @error('password') is-invalid @enderror"
                    placeholder="••••••••"
                    required
                >
                @error('password')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group checkbox-group">
                <input 
                    type="checkbox" 
                    id="remember" 
                    name="remember"
                    {{ old('remember') ? 'checked' : '' }}
                >
                <label for="remember">Remember me</label>
            </div>

            <button type="submit" class="btn btn-primary btn-block">
                Sign In
            </button>
        </form>

        <div class="auth-footer">
            <p>Don't have an account? <a href="{{ route('register') }}">Create one now</a></p>
        </div>
    </div>
</div>
@endsection
