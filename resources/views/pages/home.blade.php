@extends('layouts.app')

@section('title', 'Home - CryptoPortal')

@section('body-class', 'home-page')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
@endsection

@section('content')

    {{-- Hero Section --}}
    <section class="hero">
        <div class="crypto-animation" id="cryptoCanvas"></div>
        <div class="hero-content">
            <div class="container">
                <h1 id="animated-title">
                    <span class="animated-text">CRYPTOGRAPHY LIBRARIES <br>KNOWLEDGE SYSTEM</span>
                </h1>
                <p class="tagline">
                    A one-stop center for anyone
                    looking to explore and research
                    available cryptology libraries.
                    These libraries are open-source,
                    allowing developers to integrate
                    them into their programs. It also
                    serves as a resource for those who
                    wish to learn and gain a deeper
                    understanding of each library.
                </p>
                <a href="{{ route('libraries') }}" class="cta-button">Explore Library</a>
            </div>
        </div>
    </section>

    {{-- Features Section --}}
    <section class="features-section">
        <div class="container">
            <h2 class="section-title">What You'll Discover</h2>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">🔐</div>
                    <h3>PQC-Supported Libraries</h3>
                    <p>Explore the libraries that support Post-Quantum Cryptography (PQC) algorithms,
                        enabling secure integration against quantum-based threats.
                        Gain insights into their functionalities and applications.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">🌐</div>
                    <h3>Library Source Link</h3>
                    <p>Direct access to source links and easily navigate to the official source links for each library
                        with a single click.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">📚</div>
                    <h3>Gain Expertise in Each Library</h3>
                    <p>
                        Understand the implementation language, installation process,
                        supported algorithms, and other key aspects to effectively
                        utilize each cryptographic library.</p>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('scripts')
    <script type="module" src="{{ asset('js/main.js') }}"></script>
@endsection
