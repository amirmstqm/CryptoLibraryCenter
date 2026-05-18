@extends('auth.layout')

@section('title', 'Register')

@section('content')
<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h1>Join CryptoLibraryCenter</h1>
            <p class="auth-subtitle">Create an account to get started</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <div class="alert-title">Registration Error</div>
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('register.post') }}" class="auth-form">
            @csrf

            <div class="form-group">
                <label for="name">Full Name</label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    value="{{ old('name') }}"
                    class="form-control @error('name') is-invalid @enderror"
                    placeholder="John Doe"
                    required
                    autofocus
                >
                @error('name')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

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
                >
                @error('email')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-password-wrapper">
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="form-control @error('password') is-invalid @enderror"
                        placeholder="••••••••"
                        required
                    >
                    <button type="button" class="password-toggle" onclick="togglePassword('password', this)" tabindex="-1">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                <small class="form-hint">At least 8 characters, including uppercase, lowercase, numbers, and symbols</small>
                @error('password')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirm Password</label>
                <div class="input-password-wrapper">
                    <input 
                        type="password" 
                        id="password_confirmation" 
                        name="password_confirmation" 
                        class="form-control @error('password_confirmation') is-invalid @enderror"
                        placeholder="••••••••"
                        required
                    >
                    <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation', this)" tabindex="-1">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                @error('password_confirmation')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="expertise_level">Expertise Level</label>
                <select 
                    id="expertise_level" 
                    name="expertise_level" 
                    class="form-control @error('expertise_level') is-invalid @enderror"
                    required
                >
                    <option value="">Select your expertise level</option>
                    <option value="beginner" {{ old('expertise_level') === 'beginner' ? 'selected' : '' }}>Beginner</option>
                    <option value="intermediate" {{ old('expertise_level') === 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                    <option value="advanced" {{ old('expertise_level') === 'advanced' ? 'selected' : '' }}>Advanced</option>
                </select>
                @error('expertise_level')
                    <span class="form-error">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary btn-block">
                Create Account
            </button>
        </form>

        <div class="auth-footer">
            <p>Already have an account? <a href="{{ route('login') }}">Sign in here</a></p>
        </div>
    </div>
</div>
@endsection
