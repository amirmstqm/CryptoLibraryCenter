@extends('auth.layout')

@section('title', 'My Profile')

@section('content')
<div class="profile-container">
    <div class="profile-wrapper">
        <div class="profile-sidebar">
            <div class="profile-card">
                <div class="profile-picture-container">
                    @if ($user->profile_picture)
                        <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="{{ $user->name }}" class="profile-picture">
                    @else
                        <div class="profile-picture-placeholder">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                </div>
                <div class="profile-info">
                    <h2>{{ $user->name }}</h2>
                    <p class="profile-email">{{ $user->email }}</p>
                    <p class="profile-level">{{ ucfirst($user->expertise_level) }} Level</p>
                </div>
            </div>
        </div>

        <div class="profile-content">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <div class="alert-title">Update Failed</div>
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <div class="profile-section">
                <h3>Edit Profile</h3>
                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="profile-form">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name', $user->name) }}"
                            class="form-control @error('name') is-invalid @enderror"
                            required
                        >
                        @error('name')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="bio">Bio</label>
                        <textarea
                            id="bio"
                            name="bio"
                            rows="4"
                            class="form-control @error('bio') is-invalid @enderror"
                            placeholder="Tell us about your cryptography interests..."
                        >{{ old('bio', $user->bio) }}</textarea>
                        <small class="form-hint">Maximum 500 characters</small>
                        @error('bio')
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
                            <option value="beginner" {{ old('expertise_level', $user->expertise_level) === 'beginner' ? 'selected' : '' }}>Beginner</option>
                            <option value="intermediate" {{ old('expertise_level', $user->expertise_level) === 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                            <option value="advanced" {{ old('expertise_level', $user->expertise_level) === 'advanced' ? 'selected' : '' }}>Advanced</option>
                        </select>
                        @error('expertise_level')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="profile_picture">Profile Picture</label>
                        <input
                            type="file"
                            id="profile_picture"
                            name="profile_picture"
                            accept="image/*"
                            class="form-control @error('profile_picture') is-invalid @enderror"
                        >
                        <small class="form-hint">Maximum 2MB. Supported formats: JPG, PNG, GIF</small>
                        @error('profile_picture')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">
                        Save Changes
                    </button>
                </form>
            </div>

            <div class="profile-section">
                <h3>Logout</h3>
                <p>End your current session and return to the home page.</p>
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-secondary">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
