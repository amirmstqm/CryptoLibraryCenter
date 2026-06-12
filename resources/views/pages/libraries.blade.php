@extends('layouts.app')

@section('title', 'Libraries - CryptoPortal')

@section('body-class', 'libraries-page')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/libraries.css') }}">
    <link rel="stylesheet" href="{{ asset('css/comparison.css') }}">
@endsection

@section('content')

    {{-- Hero Section --}}
    <section class="hero">
        <div class="crypto-animation" id="cryptoCanvas"></div>
        <div class="hero-content">
            <div class="container">
                <h1 id="animated-title">
                    <span class="animated-text">CRYPTOGRAPHY LIBRARIES</span>
                </h1>
            </div>
        </div>
    </section>

    <div class="main-content">
        <div class="container">

            {{-- Search Bar --}}
            <div class="search-container">
                <div class="search-filter-group">
                    <div class="search-input-wrapper">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" id="search-input" placeholder="Search by name, developer, language or algorithm...">
                        <button type="button" id="clear-search" class="clear-search-btn" title="Clear search">&times;</button>
                    </div>
                </div>
            </div>

            <div class="row">
                {{-- Sidebar Filters --}}
                <aside class="sidebar">

                    {{-- PQC Algorithm Filter --}}
                    <h4>PQC Algorithm Filter</h4>
                    <div class="pqc-filter-group">
                        @foreach([
                            'kyber'     => 'Kyber',
                            'dilithium' => 'Dilithium',
                            'sphincs+'  => 'SPHINCS+',
                            'falcon'    => 'Falcon',
                        ] as $value => $label)
                            <label for="filter-{{ Str::slug($value) }}" class="pqc-filter-label">
                                <input type="checkbox"
                                       id="filter-{{ Str::slug($value) }}"
                                       class="pqc-filter-checkbox pqc-filter"
                                       value="{{ $value }}">
                                {{ $label }}
                            </label>
                        @endforeach
                    </div>

                    {{-- Language Filter --}}
                    <h4>Language Filter</h4>
                    <div class="language-filter-group">
                        @foreach([
                            'c'          => 'C',
                            'c++'        => 'C++',
                            'java'       => 'Java',
                            'c#'         => 'C#',
                            'rust'       => 'Rust',
                            'javascript' => 'JavaScript',
                            'python'     => 'Python',
                            'assembly'   => 'Assembly',
                            'cuda'       => 'CUDA',
                            'typescript' => 'TypeScript',
                            'go'         => 'Go',
                        ] as $value => $label)
                            <label for="filter-{{ Str::slug($value) }}" class="language-filter-label">
                                <input type="checkbox"
                                       id="filter-{{ Str::slug($value) }}"
                                       class="language-filter-checkbox language-filter"
                                       value="{{ $value }}">
                                {{ $label }}
                            </label>
                        @endforeach
                    </div>

                    {{-- PQC Supported Filter --}}
                    <h4>PQC Supported</h4>
                    <div class="pqc-supported-filter-group">
                        <label for="filter-pqc-yes" class="pqc-supported-filter-label">
                            <input type="checkbox"
                                   id="filter-pqc-yes"
                                   class="pqc-supported-filter-checkbox pqc-supported-filter"
                                   value="yes">
                            Yes
                        </label>
                        <label for="filter-pqc-no" class="pqc-supported-filter-label">
                            <input type="checkbox"
                                   id="filter-pqc-no"
                                   class="pqc-supported-filter-checkbox pqc-supported-filter"
                                   value="no">
                            No
                        </label>
                    </div>

                    {{-- Clear Filters --}}
                    <button type="button" id="clear-filters" class="clear-filters-btn">Clear All Filters</button>

                </aside>

                {{-- Library Cards (populated by JS via Firebase) --}}
                <div class="content-area">
                    <div class="results-bar">
                        <span id="result-count" class="result-count"></span>
                        <div class="results-actions">
                            <button id="compare-btn" class="compare-btn" title="Compare algorithms">
                                <i class="fas fa-chart-bar"></i> Compare Algorithms
                            </button>
                            <select id="sort-select" class="sort-select">
                                <option value="az">Name: A → Z</option>
                                <option value="za">Name: Z → A</option>
                            </select>
                        </div>
                    </div>
                    <div class="features" id="library-cards">
                        @foreach ($libraries as $lib)
                            @php $pqcAlgorithms = $lib->pqcAlgorithms; @endphp
                            <div class="feature-card"
                                 data-kyber="{{ in_array('Kyber', $pqcAlgorithms) ? 'true' : 'false' }}"
                                 data-dilithium="{{ in_array('Dilithium', $pqcAlgorithms) ? 'true' : 'false' }}"
                                 data-sphincs="{{ in_array('SPHINCS+', $pqcAlgorithms) ? 'true' : 'false' }}"
                                 data-falcon="{{ in_array('Falcon', $pqcAlgorithms) ? 'true' : 'false' }}"
                                 data-type="{{ count($pqcAlgorithms) ? 'pqc' : 'classic' }}"
                                 data-language="{{ $lib->language ?? '' }}"
                                 data-license="{{ $lib->license ?? '' }}"
                                 data-open-source="{{ $lib->open_source ? 'true' : 'false' }}"
                                 data-name="{{ strtolower($lib->name) }}"
                                 data-developer="{{ strtolower($lib->developer ?? '') }}"
                                 data-pqc-algorithms="{{ implode(',', array_map('strtolower', $pqcAlgorithms)) }}"
                                 onclick="window.location.href='{{ route('details', ['id' => $lib->firebase_id]) }}'">

                                <h3>{{ $lib->name }}</h3>
                                <div class="library-details">
                                    <p><strong>Developer:</strong> {{ $lib->developer ?? 'N/A' }}</p>
                                    <p><strong>Languages:</strong> {{ $lib->language ?? 'N/A' }}</p>
                                    <p><strong>Latest Version:</strong> {{ $lib->latest_version ?? 'N/A' }} ({{ $lib->latest_release ?? 'N/A' }})</p>
                                    <p><strong>License:</strong> {{ $lib->license ?? 'N/A' }}</p>
                                    <p><strong>Open Source:</strong> {{ $lib->open_source ? 'Yes' : 'No' }}</p>
                                </div>
                                @if (count($pqcAlgorithms))
                                    <div class="algorithm-badges">
                                        @foreach ($pqcAlgorithms as $alg)
                                            <span class="algorithm-badge">{{ $alg }}</span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

    </div>
</div>

{{-- Comparison Modal --}}
@include('comparison-modal')

@endsection

@section('scripts')
    {{-- Inject library data for the client-side comparison module --}}
    <script>
        window.__libraries__ = @json($librariesJson);
    </script>

    {{-- App JS modules --}}
    <script type="module" src="{{ asset('js/main.js') }}"></script>
    <script type="module" src="{{ asset('js/filter.js') }}"></script>

    {{-- Wire up compare button --}}
    <script type="module">
        document.addEventListener('DOMContentLoaded', () => {
            const compareBtn = document.getElementById('compare-btn');
            if (compareBtn && window.openComparisonModal) {
                compareBtn.addEventListener('click', window.openComparisonModal);
            }
        });
    </script>
@endsection
