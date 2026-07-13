@extends('layouts.admin')

@section('title', 'Edit Library — Admin')

@section('page-heading')
    <h1 class="page-title">EDIT LIBRARY</h1>
@endsection

@section('content')

@if($errors->any())
<div class="flash flash-error">
    <i class="fas fa-exclamation-circle"></i>
    <ul class="error-list">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="form-card">
    <form method="POST" action="{{ route('admin.update', $library->id) }}" class="admin-form">
        @csrf
        @method('PUT')

        {{-- Row 1 --}}
        <div class="form-row">
            <div class="form-group">
                <label for="name">LIBRARY NAME</label>
                <input type="text" id="name" name="name"
                       value="{{ old('name', $library->name) }}"
                       class="form-control" required>
            </div>
            <div class="form-group">
                <label for="developer">DEVELOPER</label>
                <input type="text" id="developer" name="developer"
                       value="{{ old('developer', $library->developer) }}"
                       class="form-control">
            </div>
        </div>

        {{-- Row 2 --}}
        <div class="form-row">
            <div class="form-group">
                <label for="language">LANGUAGE</label>
                <select id="language" name="language" class="form-control">
                    <option value="">— Select —</option>
                    @foreach($languages as $lang)
                        <option value="{{ $lang }}"
                            {{ old('language', $library->language) === $lang ? 'selected' : '' }}>
                            {{ $lang }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="category">CATEGORY</label>
                <select id="category" name="category" class="form-control">
                    <option value="">— Select —</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}"
                            {{ old('category', $library->category) === $cat ? 'selected' : '' }}>
                            {{ $cat }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Row 3 --}}
        <div class="form-row">
            <div class="form-group">
                <label for="latest_version">LATEST VERSION</label>
                <input type="text" id="latest_version" name="latest_version"
                       value="{{ old('latest_version', $library->latest_version) }}"
                       class="form-control">
            </div>
            <div class="form-group">
                <label for="latest_release">LATEST RELEASE</label>
                <input type="text" id="latest_release" name="latest_release"
                       value="{{ old('latest_release', $library->latest_release) }}"
                       class="form-control">
            </div>
        </div>

        {{-- Row 4 --}}
        <div class="form-row">
            <div class="form-group">
                <label for="license">LICENSE</label>
                <input type="text" id="license" name="license"
                       value="{{ old('license', $library->license) }}"
                       class="form-control">
            </div>
            <div class="form-group">
                <label for="github">GITHUB URL</label>
                <input type="url" id="github" name="github"
                       value="{{ old('github', $library->github) }}"
                       class="form-control">
            </div>
        </div>

        {{-- PQC Algorithms --}}
        <div class="form-group form-group--full">
            <label>PQC ALGORITHMS</label>
            <div class="checkbox-grid">
                @php $selectedAlgs = old('pqc_algorithm', $library->pqcAlgorithms ?? []); @endphp
                @foreach(['Kyber', 'Dilithium', 'Falcon', 'SPHINCS+', 'NTRU', 'McEliece', 'FrodoKEM', 'BIKE'] as $alg)
                <label class="checkbox-label">
                    <input type="checkbox" name="pqc_algorithm[]" value="{{ $alg }}"
                           {{ in_array($alg, $selectedAlgs) ? 'checked' : '' }}>
                    {{ $alg }}
                </label>
                @endforeach
            </div>
        </div>

        {{-- Overview --}}
        <div class="form-group form-group--full">
            <label for="overview">OVERVIEW</label>
            <textarea id="overview" name="overview" class="form-control form-textarea"
                      rows="4">{{ old('overview', $library->overview) }}</textarea>
        </div>

        {{-- Limitation --}}
        <div class="form-group form-group--full">
            <label for="limitation">LIMITATIONS</label>
            <textarea id="limitation" name="limitation" class="form-control form-textarea"
                      rows="3">{{ old('limitation', $library->limitation) }}</textarea>
        </div>

        {{-- Toggles --}}
        <div class="form-row form-row--toggles">
            <label class="toggle-label">
                <input type="hidden" name="open_source" value="0">
                <input type="checkbox" name="open_source" value="1"
                       {{ old('open_source', $library->open_source) ? 'checked' : '' }}
                       class="toggle-checkbox">
                <span class="toggle-text">Open Source</span>
            </label>
            <label class="toggle-label">
                <input type="hidden" name="show" value="0">
                <input type="checkbox" name="show" value="1"
                       {{ old('show', $library->show) ? 'checked' : '' }}
                       class="toggle-checkbox">
                <span class="toggle-text">Visible to Public</span>
            </label>
        </div>

        {{-- Actions --}}
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Update Library
            </button>
            <a href="{{ route('admin.browse') }}" class="btn btn-secondary">Cancel</a>
        </div>

    </form>
</div>

@endsection
