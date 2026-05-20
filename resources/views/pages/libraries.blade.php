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
                        {{-- Cards injected by data-fetching.js --}}
                    </div>
                </div>
            </div>

    </div>
</div>

{{-- Comparison Modal --}}
@include('comparison-modal')

@endsection

@section('scripts')
    {{-- Firebase SDKs --}}
    <script src="https://www.gstatic.com/firebasejs/9.6.1/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.6.1/firebase-firestore-compat.js"></script>

    {{-- App JS modules --}}
    <script type="module" src="{{ asset('js/main.js') }}"></script>
    <script type="module" src="{{ asset('js/filter.js') }}"></script>
    <script type="module" src="{{ asset('js/library-details.js') }}"></script>

    {{-- Wire up compare button after DOM is ready --}}
    <script type="module">
        // Wait for comparison module to be initialized
        document.addEventListener('DOMContentLoaded', () => {
            const compareBtn = document.getElementById('compare-btn');
            if (compareBtn && window.openComparisonModal) {
                compareBtn.addEventListener('click', window.openComparisonModal);
            }
        });
    </script>
    <script type="module" src="{{ asset('js/firebase-config.js') }}"></script>
    <script type="module">
        import { fetchAndDisplayLibraries } from "{{ asset('js/data-fetching.js') }}";
        fetchAndDisplayLibraries();
    </script>
@endsection