<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CryptoPortal — Post-Quantum Cryptography Library Hub</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">

    {{-- Prevent dark-mode flash --}}
    <script>if (localStorage.getItem('darkMode') === 'true') document.documentElement.classList.add('dark');</script>
</head>
<body class="landing-page">

    {{-- ══ Navbar ══ --}}
    <nav class="landing-nav">
        <div class="landing-nav-container">
            <a href="{{ route('landing') }}" class="logo">
                <span>CryptoPortal</span>
            </a>

            <div class="landing-nav-right">
                <button id="dark-toggle" class="dark-toggle" title="Toggle dark mode">
                    <i class="fas fa-moon" id="dark-icon"></i>
                </button>
                <a href="{{ route('libraries') }}" class="btn-primary-nav">Explore Libraries</a>
            </div>
        </div>
    </nav>

    {{-- ══ Hero ══ --}}
    <section class="landing-hero">
        <canvas id="heroCanvas" class="hero-canvas"></canvas>
        <div class="hero-inner">
            <div class="hero-badge">
                <i class="fas fa-shield-alt"></i>
                <span>Post-Quantum Cryptography</span>
            </div>
            <h1 class="hero-title">
                The Knowledge Hub for<br>
                <span class="hero-accent">Cryptography Libraries</span>
            </h1>
            <p class="hero-subtitle">
                Explore, compare, and implement post-quantum cryptographic libraries.
                A one-stop resource for developers and researchers navigating
                the future of secure computing.
            </p>
            <div class="hero-cta-group">
                <a href="{{ route('libraries') }}" class="cta-primary">
                    <i class="fas fa-rocket"></i> Explore Libraries
                </a>
            </div>
        </div>
        <div class="hero-scroll-hint">
            <i class="fas fa-chevron-down"></i>
        </div>
    </section>

    {{-- ══ Stats Bar ══ --}}
    <section class="stats-bar">
        <div class="stats-container">
            <div class="stat-item">
                <div class="stat-number">30<span class="stat-plus">+</span></div>
                <div class="stat-label">Cryptography Libraries</div>
            </div>
            <div class="stat-divider"></div>
            <div class="stat-item">
                <div class="stat-number">10<span class="stat-plus">+</span></div>
                <div class="stat-label">PQC Algorithms</div>
            </div>
            <div class="stat-divider"></div>
            <div class="stat-item">
                <div class="stat-number">15<span class="stat-plus">+</span></div>
                <div class="stat-label">Programming Languages</div>
            </div>
            <div class="stat-divider"></div>
            <div class="stat-item">
                <div class="stat-number">100<span class="stat-plus">%</span></div>
                <div class="stat-label">Open Source</div>
            </div>
        </div>
    </section>

    {{-- ══ Features ══ --}}
    <section class="features-section">
        <div class="section-container">
            <div class="section-header">
                <h2 class="section-title">Everything You Need</h2>
                <p class="section-subtitle">A comprehensive platform to explore, learn, and implement post-quantum cryptography</p>
            </div>

            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon-wrap">
                        <i class="fas fa-lock"></i>
                    </div>
                    <h3>PQC-Ready Libraries</h3>
                    <p>Browse libraries that support post-quantum cryptography algorithms, from NIST-standardised KEM and signature schemes to experimental implementations.</p>
                </div>

                <div class="feature-card feature-card--accent">
                    <div class="feature-icon-wrap">
                        <i class="fas fa-balance-scale"></i>
                    </div>
                    <h3>Side-by-Side Comparison</h3>
                    <p>Compare up to 3 libraries at once — developer, language support, version, license, GitHub repository and supported PQC algorithms all in one view.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon-wrap">
                        <i class="fas fa-book-open"></i>
                    </div>
                    <h3>Implementation Guides</h3>
                    <p>Step-by-step installation guides, code examples, and test cases to get you from zero to working implementation quickly.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon-wrap">
                        <i class="fas fa-code-branch"></i>
                    </div>
                    <h3>Direct Source Access</h3>
                    <p>Linked directly to official GitHub repositories and documentation so you always have the most current information at hand.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon-wrap">
                        <i class="fas fa-filter"></i>
                    </div>
                    <h3>Smart Filtering</h3>
                    <p>Filter libraries by programming language, algorithm type, license, and open-source status to find exactly what your project needs.</p>
                </div>

                <div class="feature-card">
                    <div class="feature-icon-wrap">
                        <i class="fas fa-sync-alt"></i>
                    </div>
                    <h3>Always Up to Date</h3>
                    <p>Data is continuously synchronised so you always see the latest library versions, release dates, and algorithm support status.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ══ How It Works ══ --}}
    <section class="how-section">
        <div class="section-container">
            <div class="section-header">
                <h2 class="section-title">How It Works</h2>
                <p class="section-subtitle">From discovery to implementation in three simple steps</p>
            </div>

            <div class="steps-grid">
                <div class="step-card">
                    <div class="step-number">01</div>
                    <div class="step-icon"><i class="fas fa-search"></i></div>
                    <h3>Browse & Filter</h3>
                    <p>Search through our curated library catalogue. Filter by language, algorithm, license, or PQC support to narrow your options.</p>
                </div>

                <div class="step-connector"><i class="fas fa-chevron-right"></i></div>

                <div class="step-card">
                    <div class="step-number">02</div>
                    <div class="step-icon"><i class="fas fa-columns"></i></div>
                    <h3>Compare Libraries</h3>
                    <p>Select up to 3 libraries and compare them side-by-side across key features: version, license, developer, and algorithm support.</p>
                </div>

                <div class="step-connector"><i class="fas fa-chevron-right"></i></div>

                <div class="step-card">
                    <div class="step-number">03</div>
                    <div class="step-icon"><i class="fas fa-terminal"></i></div>
                    <h3>Implement</h3>
                    <p>Follow our detailed installation and usage guides to integrate the chosen library directly into your application.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ══ Algorithms spotlight ══ --}}
    <section class="algo-section">
        <div class="section-container">
            <div class="section-header">
                <h2 class="section-title">Covering NIST-Standardised Algorithms</h2>
                <p class="section-subtitle">From key encapsulation to digital signatures, we track them all</p>
            </div>
            <div class="algo-chips-wrap">
                <span class="algo-chip chip--kem">Kyber (ML-KEM)</span>
                <span class="algo-chip chip--sig">Dilithium (ML-DSA)</span>
                <span class="algo-chip chip--sig">Falcon</span>
                <span class="algo-chip chip--sig">SPHINCS+</span>
                <span class="algo-chip chip--kem">NTRU</span>
                <span class="algo-chip chip--kem">SABER</span>
                <span class="algo-chip chip--other">McEliece</span>
                <span class="algo-chip chip--other">BIKE</span>
                <span class="algo-chip chip--other">HQC</span>
                <span class="algo-chip chip--more">+ many more</span>
            </div>
            <div class="algo-legend">
                <span class="legend-item"><span class="legend-dot dot--kem"></span>Key Encapsulation (KEM)</span>
                <span class="legend-item"><span class="legend-dot dot--sig"></span>Digital Signature</span>
                <span class="legend-item"><span class="legend-dot dot--other"></span>Other PQC</span>
            </div>
        </div>
    </section>

    {{-- ══ CTA Banner ══ --}}
    <section class="cta-section">
        <div class="cta-inner">
            <canvas id="ctaCanvas" class="cta-canvas"></canvas>
            <div class="cta-content">
                <h2>Ready to Explore?</h2>
                <p>Join researchers and developers who rely on CryptoPortal for their post-quantum cryptography needs.</p>
                <div class="cta-buttons">
                <a href="{{ route('libraries') }}" class="cta-primary">
                    <i class="fas fa-search"></i> Browse Libraries
                </a>
            </div>
            </div>
        </div>
    </section>

    {{-- ══ Footer ══ --}}
    <footer class="landing-footer">
        <div class="footer-inner">
            <div class="footer-brand">
                <span class="footer-logo">CryptoPortal</span>
                <p>A reliable information hub for modern cryptography and post-quantum technologies.</p>
            </div>
            <div class="footer-links-group">
                <div class="footer-col">
                    <h4>Platform</h4>
                    <a href="{{ route('libraries') }}">Library</a>
                    <a href="{{ route('about') }}">About</a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <span>&copy; {{ date('Y') }} CryptoPortal. All rights reserved.</span>
        </div>
    </footer>

    <script src="{{ asset('js/landing.js') }}"></script>
</body>
</html>
