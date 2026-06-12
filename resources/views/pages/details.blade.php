@extends('layouts.app')

@section('title', $library->name . ' - CryptoPortal')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/details.css') }}">
    <link rel="stylesheet" href="{{ asset('css/prism-custom-theme.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css">
@endsection

@section('content')

@php
    $pqcAlgorithms    = $library->pqcAlgorithms;
    $installationSteps = $library->installation_step ?? [];
    $testingData      = $library->testing ?? [];
    $images           = $library->image ?? [];
@endphp

    <div class="main-content">
        <div class="container">

            <div id="library-details" class="library-details-card">

                {{-- Header --}}
                <div class="library-header">
                    <h1>{{ $library->name }}</h1>
                    <p class="subtitle">Open-source library for quantum-resistant cryptography</p>
                </div>

                {{-- Tabs --}}
                <div class="tabs">
                    <button class="tab-button active" data-tab="overview">Overview</button>
                    <button class="tab-button" data-tab="details">Details</button>
                    <button class="tab-button" data-tab="limitations">Limitations</button>
                    <button class="tab-button" data-tab="installation">Installation</button>
                    <button class="tab-button" data-tab="testing">Testing</button>
                </div>

                {{-- Overview Tab --}}
                <div id="overview" class="tab-content active">
                    <div class="overview-content">
                        <p>{{ $library->overview ?? 'No overview available.' }}</p>
                    </div>
                </div>

                {{-- Details Tab --}}
                <div id="details" class="tab-content">
                    <div class="details-grid">
                        <div class="detail-item">
                            <h3>Developer</h3><p>{{ $library->developer ?? 'Not specified' }}</p>
                            <h3>Languages</h3><p>{{ $library->language ?? 'Not specified' }}</p>
                        </div>
                        <div class="detail-item">
                            <h3>Latest Version</h3><p>{{ $library->latest_version ?? 'Not specified' }}</p>
                            <h3>Latest Release</h3><p>{{ $library->latest_release ?? 'Not specified' }}</p>
                        </div>
                        <div class="detail-item">
                            <h3>License</h3><p>{{ $library->license ?? 'Not specified' }}</p>
                            <h3>Open Source</h3><p>{{ $library->open_source ? 'Yes' : 'No' }}</p>
                        </div>
                    </div>

                    <div class="github-section">
                        <p>For more detailed information, refer to the GitHub page below.</p>
                        <h3>GitHub Repository</h3>
                        @if ($library->github)
                            <a href="{{ $library->github }}" target="_blank" rel="noopener noreferrer" class="github-link">
                                <svg class="github-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                    <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                                </svg>
                                {{ $library->github }}
                            </a>
                        @else
                            <p>No GitHub repository specified</p>
                        @endif
                    </div>

                    <div class="algorithms-section">
                        <h3>PQC Algorithms</h3>
                        <div class="algorithms-list">
                            @forelse ($pqcAlgorithms as $alg)
                                <span class="algorithm-tag">{{ $alg }}</span>
                            @empty
                                <span>None</span>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Limitations Tab --}}
                <div id="limitations" class="tab-content">
                    <div class="limitation-content">
                        <p>{{ $library->limitation ?? 'No limitations information available.' }}</p>
                    </div>
                </div>

                {{-- Installation Tab --}}
                <div id="installation" class="tab-content">
                    <div class="installation-steps">
                        @if (count($installationSteps))
                            @foreach ($installationSteps as $i => $step)
                                <div class="installation-step">
                                    <h4>Step {{ $i + 1 }}</h4>
                                    @if (is_array($step))
                                        <code>{{ $step['command'] ?? '' }}</code>
                                        <p>{{ $step['explanation'] ?? 'No explanation provided' }}</p>
                                        @if (!empty($step['imageURL']))
                                            <div class="installation-image-item">
                                                <a href="{{ $step['imageURL'] }}"
                                                   data-lightbox="installation-images"
                                                   data-title="Step {{ $i + 1 }}">
                                                    <img src="{{ $step['imageURL'] }}"
                                                         alt="Installation Step {{ $i + 1 }}"
                                                         class="installation-image" />
                                                </a>
                                                @if (!empty($step['imageExplanation']))
                                                    <p class="installation-image-explanation">{{ $step['imageExplanation'] }}</p>
                                                @endif
                                            </div>
                                        @endif
                                    @else
                                        <code>{{ $step }}</code>
                                        <p>Explanation not available for this step</p>
                                    @endif
                                </div>
                            @endforeach
                        @else
                            <p>No installation instructions available.</p>
                        @endif
                    </div>
                </div>

                {{-- Testing Tab --}}
                <div id="testing" class="tab-content">
                    <div class="testing-content">
                        @if (count($testingData))
                            @foreach ($testingData as $idx => $test)
                                <div class="testing-item">
                                    @if (is_array($test))
                                        <h4>Basic Example Program for Testing Purposes</h4>
                                        <pre><code class="language-c">{{ $test['command'] ?? '' }}</code></pre>
                                        <p>{{ $test['explanation'] ?? 'No explanation provided' }}</p>
                                    @else
                                        <h4>Test {{ $idx + 1 }}</h4>
                                        <pre><code class="language-c">{{ $test }}</code></pre>
                                        <p>No explanation provided</p>
                                    @endif
                                </div>
                            @endforeach
                        @else
                            <p>No testing information available.</p>
                        @endif

                        @if (count($images))
                            <div class="testing-images">
                                <h4>Final Output from the Program</h4>
                                <div class="testing-images-list">
                                    @foreach ($images as $idx => $img)
                                        @if (!empty($img['imageURL']))
                                            <div class="testing-image-item">
                                                <a href="{{ $img['imageURL'] }}"
                                                   data-lightbox="testing-images"
                                                   data-title="{{ $img['explanation'] ?? 'Testing Image' }}">
                                                    <img src="{{ $img['imageURL'] }}"
                                                         alt="Testing Image {{ $idx + 1 }}"
                                                         class="testing-image" />
                                                </a>
                                                @if (!empty($img['explanation']))
                                                    <p class="testing-image-explanation">{{ $img['explanation'] }}</p>
                                                @endif
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

            </div>

            <a href="{{ route('libraries') }}" class="back-button">← Back to Library List</a>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        // Tab switching
        document.addEventListener('DOMContentLoaded', () => {
            const tabButtons  = document.querySelectorAll('.tab-button');
            const tabContents = document.querySelectorAll('.tab-content');

            tabButtons.forEach(button => {
                button.addEventListener('click', () => {
                    tabButtons.forEach(btn => btn.classList.remove('active'));
                    tabContents.forEach(content => content.classList.remove('active'));
                    button.classList.add('active');
                    document.getElementById(button.getAttribute('data-tab')).classList.add('active');
                });
            });
        });
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/prism.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-java.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox-plus-jquery.min.js"></script>
@endsection
