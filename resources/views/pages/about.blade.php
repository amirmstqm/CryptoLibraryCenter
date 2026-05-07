@extends('layouts.app')

@section('title', 'About - CryptoPortal')

@section('body-class', 'about-page')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/about.css') }}">
@endsection

@section('content')

    {{-- Hero Section --}}
    <section class="hero">
        <div class="crypto-animation" id="cryptoCanvas"></div>
        <div class="hero-content">
            <div class="container">
                <h1 id="animated-title">
                    <span class="animated-text">ABOUT CRYPTOPORTAL</span>
                </h1>
            </div>
        </div>
    </section>

    {{-- Main Content --}}
    <div class="main-content">
        <div class="container">

            {{-- About Cards --}}
            <div class="about-cards">
                <div class="about-card">
                    <div class="about-icon">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <h2>About Us</h2>
                    <p>Welcome to CryptoCodex, a reliable source for cryptography libraries.
                        Providing information on cryptographic solutions to support secure
                        system development.</p>
                </div>

                <div class="about-card mission-card">
                    <div class="about-icon">
                        <i class="fas fa-bullseye"></i>
                    </div>
                    <h2>Our Mission</h2>
                    <p>Expanding access to cryptographic knowledge and tools to support seamless
                        integration of encryption, hashing, and security protocols in software
                        development. Focusing on open-source solutions to promote collaboration
                        within the cybersecurity community.</p>
                </div>

                <div class="about-card">
                    <div class="about-icon">
                        <i class="fas fa-tools"></i>
                    </div>
                    <h2>What We Offer</h2>
                    <ul class="offer-list">
                        <li><i class="fas fa-check-circle"></i> Selected cryptography libraries with simple guides.</li>
                        <li><i class="fas fa-check-circle"></i> Practical implementation guides</li>
                        <li><i class="fas fa-check-circle"></i> Latest cryptographic standards updates</li>
                        <li><i class="fas fa-check-circle"></i> Direct source links for each library</li>
                    </ul>
                </div>
            </div>

            {{-- Core Principles --}}
            <div class="team-section">
                <h2 class="section-title">Our Core Principles</h2>
                <div class="principles">
                    <div class="principle">
                        <div class="principle-icon">
                            <i class="fas fa-book"></i>
                        </div>
                        <h3>Library Development</h3>
                        <p>We prioritize solutions that offer robust security guarantees and proven track records.</p>
                    </div>
                    <div class="principle">
                        <div class="principle-icon">
                            <i class="fas fa-code"></i>
                        </div>
                        <h3>Secure Programming</h3>
                        <p>Our resources are designed with practical implementation in mind.</p>
                    </div>
                    <div class="principle">
                        <div class="principle-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h3>Penetration Testing</h3>
                        <p>We continuously update our content to reflect the latest in cryptography.</p>
                    </div>
                    <div class="principle">
                        <div class="principle-icon">
                            <i class="fas fa-wave-square"></i>
                        </div>
                        <h3>Side Channel Analysis</h3>
                        <p>We continuously update our content to reflect the latest in cryptography.</p>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection

@section('scripts')
    <script type="module" src="{{ asset('js/main.js') }}"></script>
@endsection
