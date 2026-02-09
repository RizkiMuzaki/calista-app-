@extends('layouts.app')

@section('title', 'Beranda - Petualangan Belajar Seru')

@section('content')
<div class="beranda-container">
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-content">
            <h1 class="hero-title">
                Selamat Datang di 
                <span class="highlight">Petualangan Belajar!</span>
            </h1>
            <p class="hero-subtitle">
                Tempat belajar paling seru untuk anak-anak! Jelajahi dunia pengetahuan dengan permainan yang mengasyikkan.
            </p>
            
            <div class="hero-buttons">
                <a href="{{ route('permainan') }}" class="primary-btn">
                    <i class="fas fa-play-circle"></i> Bermain Sekarang!
                </a>
                <a href="{{ route('materi') }}" class="secondary-btn">
                    <i class="fas fa-book-open"></i> Pelajari Materi
                </a>
            </div>
        </div>
        
        <div class="hero-image">
            <div class="floating-emoji">🚀</div>
            <div class="floating-emoji">🎨</div>
            <div class="floating-emoji">🧮</div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section">
        <h2 class="section-title">Apa yang Menarik di Sini?</h2>
        <p class="section-subtitle">Temukan semua kehebatan yang menantimu!</p>
        
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon" style="background: linear-gradient(135deg, #ff9e6d, #ff8787);">
                    <i class="fas fa-gamepad"></i>
                </div>
                <h3>Permainan Seru</h3>
                <p>Belajar sambil bermain dengan game edukasi yang menyenangkan!</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon" style="background: linear-gradient(135deg, #5c7cfa, #4dabf7);">
                    <i class="fas fa-trophy"></i>
                </div>
                <h3>Papan Peringkat</h3>
                <p>Lihat ranking-mu dan bersaing dengan teman-teman lainnya!</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon" style="background: linear-gradient(135deg, #51cf66, #40c057);">
                    <i class="fas fa-medal"></i>
                </div>
                <h3>Hadiah & Pencapaian</h3>
                <p>Kumpulkan bintang dan dapatkan penghargaan menarik!</p>
            </div>
            
            <div class="feature-card">
                <div class="feature-icon" style="background: linear-gradient(135deg, #ffd43b, #fcc419);">
                    <i class="fas fa-users"></i>
                </div>
                <h3>Bermain Bersama</h3>
                <p>Undang teman dan belajar bersama-sama!</p>
            </div>
        </div>
    </section>

    <!-- Popular Games Section -->
    <section class="games-section">
        <h2 class="section-title">Permainan Populer</h2>
        <p class="section-subtitle">Coba permainan favorit semua anak!</p>
        
        <div class="games-grid">
            <div class="game-card">
                <div class="game-image" style="background: linear-gradient(135deg, #a8edea, #fed6e3);">
                    <div class="game-emoji">🔢</div>
                </div>
                <div class="game-content">
                    <h3>Petualangan Matematika</h3>
                    <p>Jelajahi dunia angka dengan petualangan seru!</p>
                    <a href="{{ route('permainan.matematika') }}" class="game-btn">
                        <i class="fas fa-play"></i> Main Sekarang
                    </a>
                </div>
            </div>
            
            <div class="game-card">
                <div class="game-image" style="background: linear-gradient(135deg, #f093fb, #f5576c);">
                    <div class="game-emoji">🔤</div>
                </div>
                <div class="game-content">
                    <h3>Misteri Huruf</h3>
                    <p>Selamatkan huruf-huruf yang hilang dalam petualangan!</p>
                    <a href="{{ route('permainan.huruf') }}" class="game-btn">
                        <i class="fas fa-play"></i> Main Sekarang
                    </a>
                </div>
            </div>
            
            <div class="game-card">
                <div class="game-image" style="background: linear-gradient(135deg, #4facfe, #00f2fe);">
                    <div class="game-emoji">🎨</div>
                </div>
                <div class="game-content">
                    <h3>Warna & Bentuk</h3>
                    <p>Kenali warna dan bentuk dengan permainan kreatif!</p>
                    <a href="{{ route('permainan.warna') }}" class="game-btn">
                        <i class="fas fa-play"></i> Main Sekarang
                    </a>
                </div>
            </div>
        </div>
        
        <div class="center-button">
            <a href="{{ route('permainan') }}" class="view-all-btn">
                <i class="fas fa-eye"></i> Lihat Semua Permainan
            </a>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="cta-content">
            <h2>Siap untuk Petualangan?</h2>
            <p>Bergabung dengan ribuan anak lainnya yang sedang belajar dengan cara menyenangkan!</p>
            
            @auth
                <div class="cta-buttons-auth">
                    <a href="{{ route('permainan') }}" class="cta-btn">
                        <i class="fas fa-rocket"></i> Lanjutkan Petualangan!
                    </a>
                    <a href="{{ route('artikel.index') }}" class="cta-btn secondary-cta">
                        <i class="fas fa-book"></i> Baca Artikel Tips
                    </a>
                </div>
            @else
                <div class="cta-buttons">
                    <a href="{{ route('register') }}" class="cta-btn primary">
                        <i class="fas fa-user-plus"></i> Daftar Gratis
                    </a>
                    <a href="{{ route('login') }}" class="cta-btn secondary">
                        <i class="fas fa-sign-in-alt"></i> Masuk ke Akun
                    </a>
                </div>
            @endauth
        </div>
    </section>

    <!-- Artikel Terbaru Section -->
    <section class="latest-articles-section">
        <h2 class="section-title"><i class="fas fa-newspaper"></i> Artikel Terbaru</h2>
        <p class="section-subtitle">Tips dan trik belajar efektif untuk anak-anak</p>
        
        <div class="articles-preview-grid">
            @php
                $latestArtikels = \App\Models\Artikel::where('is_published', true)
                    ->orderBy('created_at', 'desc')
                    ->limit(3)
                    ->get();
            @endphp
            
            @forelse($latestArtikels as $artikel)
                <div class="article-preview-card">
                    @if($artikel->image)
                        <div class="preview-image">
                            <img src="{{ asset('storage/' . $artikel->image) }}" alt="{{ $artikel->title }}">
                        </div>
                    @else
                        <div class="preview-image no-image">
                            <i class="fas fa-file-alt"></i>
                        </div>
                    @endif
                    <div class="preview-content">
                        <p class="preview-date">{{ $artikel->created_at->format('d M Y') }}</p>
                        <h3 class="preview-title">
                            <a href="{{ route('artikel.show', $artikel->slug) }}">
                                {{ $artikel->title }}
                            </a>
                        </h3>
                        <a href="{{ route('artikel.show', $artikel->slug) }}" class="preview-link">
                            Baca <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            @empty
            @endforelse
        </div>

        @if($latestArtikels->count() > 0)
            <div class="center-button">
                <a href="{{ route('artikel.index') }}" class="view-all-btn">
                    <i class="fas fa-eye"></i> Lihat Semua Artikel
                </a>
            </div>
        @endif
    </section>
</div>

<style>
    .beranda-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    /* Hero Section */
    .hero-section {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 50px;
        align-items: center;
        padding: 60px 20px;
        background: white;
        border-radius: 30px;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
        margin-bottom: 60px;
        position: relative;
        overflow: hidden;
    }

    .hero-section::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 10px;
        background: linear-gradient(90deg, #ff9e6d, #5c7cfa, #51cf66, #ffd43b);
    }

    .hero-title {
        font-family: 'Fredoka One', cursive;
        font-size: 3.5rem;
        color: #333;
        margin-bottom: 20px;
        line-height: 1.2;
    }

    .highlight {
        background: linear-gradient(135deg, #ff9e6d, #ff8787);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .hero-subtitle {
        font-size: 1.4rem;
        color: #666;
        margin-bottom: 40px;
        line-height: 1.6;
    }

    .hero-buttons {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
    }

    .primary-btn {
        background: linear-gradient(135deg, #ff9e6d 0%, #ff8787 100%);
        color: white;
        padding: 20px 35px;
        border-radius: 20px;
        font-size: 1.4rem;
        font-weight: 900;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 15px;
        transition: all 0.3s;
        box-shadow: 0 10px 0 #ff6b6b;
        font-family: 'Fredoka One', cursive;
        letter-spacing: 1px;
    }

    .primary-btn:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 0 #ff6b6b;
    }

    .primary-btn:active {
        transform: translateY(2px);
        box-shadow: 0 8px 0 #ff6b6b;
    }

    .secondary-btn {
        background: linear-gradient(135deg, #5c7cfa 0%, #4dabf7 100%);
        color: white;
        padding: 20px 35px;
        border-radius: 20px;
        font-size: 1.4rem;
        font-weight: 900;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 15px;
        transition: all 0.3s;
        box-shadow: 0 10px 0 #3b5bdb;
        font-family: 'Fredoka One', cursive;
        letter-spacing: 1px;
    }

    .secondary-btn:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 0 #3b5bdb;
    }

    .hero-image {
        position: relative;
        height: 300px;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .floating-emoji {
        font-size: 5rem;
        position: absolute;
        animation: float 6s ease-in-out infinite;
    }

    .floating-emoji:nth-child(1) {
        top: 20px;
        left: 30px;
        animation-delay: 0s;
    }

    .floating-emoji:nth-child(2) {
        top: 50%;
        right: 40px;
        animation-delay: 2s;
    }

    .floating-emoji:nth-child(3) {
        bottom: 30px;
        left: 50%;
        animation-delay: 4s;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0) rotate(0deg); }
        50% { transform: translateY(-20px) rotate(10deg); }
    }

    /* Features Section */
    .features-section {
        margin-bottom: 80px;
    }

    .section-title {
        text-align: center;
        font-family: 'Fredoka One', cursive;
        font-size: 2.8rem;
        color: #333;
        margin-bottom: 15px;
    }

    .section-subtitle {
        text-align: center;
        font-size: 1.3rem;
        color: #666;
        margin-bottom: 50px;
    }

    .features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 30px;
    }

    .feature-card {
        background: white;
        padding: 30px;
        border-radius: 20px;
        text-align: center;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        transition: all 0.3s;
        border: 3px solid transparent;
    }

    .feature-card:hover {
        transform: translateY(-10px);
        border-color: #ff9e6d;
        box-shadow: 0 15px 40px rgba(255, 158, 109, 0.2);
    }

    .feature-icon {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 25px;
        font-size: 2.5rem;
        color: white;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }

    .feature-card h3 {
        font-size: 1.6rem;
        color: #333;
        margin-bottom: 15px;
    }

    .feature-card p {
        color: #666;
        line-height: 1.6;
        font-size: 1.1rem;
    }

    /* Games Section */
    .games-section {
        margin-bottom: 80px;
    }

    .games-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 30px;
        margin-bottom: 40px;
    }

    .game-card {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        transition: all 0.3s;
    }

    .game-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }

    .game-image {
        height: 180px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 30px;
    }

    .game-emoji {
        font-size: 4rem;
        animation: bounce 2s infinite;
    }

    @keyframes bounce {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
    }

    .game-content {
        padding: 25px;
    }

    .game-content h3 {
        font-size: 1.5rem;
        color: #333;
        margin-bottom: 10px;
    }

    .game-content p {
        color: #666;
        margin-bottom: 20px;
        line-height: 1.5;
    }

    .game-btn {
        background: linear-gradient(135deg, #51cf66 0%, #40c057 100%);
        color: white;
        padding: 12px 25px;
        border-radius: 15px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        font-weight: bold;
        transition: all 0.3s;
        box-shadow: 0 5px 0 #2b8a3e;
    }

    .game-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 0 #2b8a3e;
    }

    .center-button {
        text-align: center;
    }

    .view-all-btn {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: linear-gradient(135deg, #5c7cfa 0%, #4dabf7 100%);
        color: white;
        padding: 18px 35px;
        border-radius: 20px;
        font-size: 1.3rem;
        font-weight: bold;
        text-decoration: none;
        transition: all 0.3s;
        box-shadow: 0 8px 0 #3b5bdb;
    }

    .view-all-btn:hover {
        transform: translateY(-5px);
        box-shadow: 0 13px 0 #3b5bdb;
    }

    /* CTA Section */
    .cta-section {
        background: linear-gradient(135deg, #118ab2 0%, #06d6a0 100%);
        padding: 60px 40px;
        border-radius: 30px;
        text-align: center;
        color: white;
        margin-bottom: 40px;
    }

    .cta-content h2 {
        font-family: 'Fredoka One', cursive;
        font-size: 3rem;
        margin-bottom: 20px;
    }

    .cta-content p {
        font-size: 1.4rem;
        margin-bottom: 40px;
        opacity: 0.9;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
    }

    .cta-btn {
        display: inline-flex;
        align-items: center;
        gap: 15px;
        padding: 22px 45px;
        border-radius: 25px;
        font-size: 1.5rem;
        font-weight: 900;
        text-decoration: none;
        transition: all 0.3s;
        box-shadow: 0 12px 0 rgba(0, 0, 0, 0.2);
        font-family: 'Fredoka One', cursive;
    }

    .cta-btn.primary {
        background: linear-gradient(135deg, #ffd166 0%, #ff9e6d 100%);
        color: #333;
        box-shadow: 0 12px 0 #e63946;
    }

    .cta-btn.secondary {
        background: white;
        color: #118ab2;
        box-shadow: 0 12px 0 #0a7c8c;
    }

    .cta-btn:hover {
        transform: translateY(-5px);
        box-shadow: 0 17px 0 rgba(0, 0, 0, 0.2);
    }

    .cta-btn.primary:hover {
        box-shadow: 0 17px 0 #e63946;
    }

    .cta-btn.secondary:hover {
        box-shadow: 0 17px 0 #0a7c8c;
    }

    .cta-buttons {
        display: flex;
        gap: 30px;
        justify-content: center;
        flex-wrap: wrap;
    }

    /* CTA Section Auth Buttons */
    .cta-buttons-auth {
        display: flex;
        gap: 20px;
        justify-content: center;
        flex-wrap: wrap;
    }

    .cta-btn.secondary-cta {
        background: white;
        color: #118ab2;
        box-shadow: 0 12px 0 #0a7c8c;
    }

    .cta-btn.secondary-cta:hover {
        box-shadow: 0 17px 0 #0a7c8c;
    }

    /* Latest Articles Section */
    .latest-articles-section {
        margin-bottom: 60px;
    }

    .articles-preview-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 25px;
        margin-bottom: 30px;
    }

    .article-preview-card {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        transition: all 0.3s;
    }

    .article-preview-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 35px rgba(0, 0, 0, 0.12);
    }

    .preview-image {
        height: 150px;
        overflow: hidden;
        background: linear-gradient(135deg, #e0e0e0, #f5f5f5);
    }

    .preview-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s;
    }

    .article-preview-card:hover .preview-image img {
        transform: scale(1.1);
    }

    .preview-image.no-image {
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #5c7cfa 0%, #4dabf7 100%);
        color: white;
        font-size: 2.5rem;
    }

    .preview-content {
        padding: 20px;
    }

    .preview-date {
        font-size: 0.85rem;
        color: #999;
        margin-bottom: 8px;
    }

    .preview-title {
        font-size: 1.1rem;
        color: #333;
        margin-bottom: 15px;
        line-height: 1.4;
    }

    .preview-title a {
        text-decoration: none;
        color: #333;
        transition: color 0.3s;
    }

    .preview-title a:hover {
        color: #118ab2;
    }

    .preview-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #118ab2;
        text-decoration: none;
        font-weight: bold;
        transition: gap 0.3s;
    }

    .preview-link:hover {
        gap: 12px;
    }

    /* Responsive */
    @media (max-width: 900px) {
        .hero-section {
            grid-template-columns: 1fr;
            text-align: center;
        }

        .hero-title {
            font-size: 2.8rem;
        }

        .hero-buttons {
            justify-content: center;
        }

        .hero-image {
            height: 200px;
        }

        .floating-emoji {
            font-size: 4rem;
        }

        .section-title {
            font-size: 2.3rem;
        }

        .cta-content h2 {
            font-size: 2.5rem;
        }

        .cta-btn {
            padding: 18px 35px;
            font-size: 1.3rem;
        }
    }

    @media (max-width: 600px) {
        .hero-title {
            font-size: 2.3rem;
        }

        .hero-subtitle {
            font-size: 1.2rem;
        }

        .primary-btn, .secondary-btn {
            padding: 18px 25px;
            font-size: 1.2rem;
        }

        .features-grid, .games-grid {
            grid-template-columns: 1fr;
        }

        .cta-buttons {
            flex-direction: column;
            align-items: center;
        }
    }
</style>

@endsection