@extends('layouts.app')

@section('title', 'Library Details - CryptoPortal')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/details.css') }}">
    <link rel="stylesheet" href="{{ asset('css/prism-custom-theme.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css">
@endsection

@section('content')

    <div class="main-content">
        <div class="container">

            <div id="loading-spinner" class="spinner"></div>

            <div id="library-details" class="library-details-card">

                {{-- Header injected by library-details.js --}}

                {{-- Tabs --}}
                <div class="tabs">
                    <button class="tab-button active" data-tab="overview">Overview</button>
                    <button class="tab-button" data-tab="details">Details</button>
                    <button class="tab-button" data-tab="limitations">Limitations</button>
                    <button class="tab-button" data-tab="installation">Installation</button>
                    <button class="tab-button" data-tab="testing">Testing</button>
                </div>

                <div id="overview" class="tab-content active">
                    {{-- Injected by JS --}}
                </div>

                <div id="details" class="tab-content">
                    {{-- Injected by JS --}}
                </div>

                <div id="limitations" class="tab-content">
                    {{-- Injected by JS --}}
                </div>

                <div id="installation" class="tab-content">
                    {{-- Injected by JS --}}
                </div>

                <div id="testing" class="tab-content">
                    {{-- Injected by JS --}}
                </div>

            </div>
        </div>
    </div>

    <a href="{{ route('libraries') }}" class="back-button">← Back to Library List</a>

@endsection

@section('scripts')
    <script type="module" src="{{ asset('js/library-details.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/prism.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-java.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox-plus-jquery.min.js"></script>
@endsection
