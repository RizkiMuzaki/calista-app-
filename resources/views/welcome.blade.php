@extends('layouts.app')

@section('title', 'Beranda - Calista: Belajar Calistung & Budaya Indonesia')

@section('content')
<div class="beranda-container">
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-content">
            <div class="calista-badge">
                <span>🌟</span> Aplikasi Resmi
            </div>
            <h1 class="hero-title">
                Selamat Datang di 
                <span class="highlight">Calista!</span>
            </h1>
            <p class="hero-subtitle">
                <strong>Aplikasi Belajar Calistung Pertama di Indonesia</strong> yang mengajarkan membaca, menulis, berhitung sambil mengenal keindahan budaya Indonesia bersama <span class="nusa-ai">Nusa AI</span>.
            </p>
            
            <div class="hero-features">
                <div class="hero-feature">
                    <i class="fas fa-robot"></i>
                    <span>AI Pendamping Belajar</span>
                </div>
                <div class="hero-feature">
                    <i class="fas fa-palette"></i>
                    <span>Konten Budaya Indonesia</span>
                </div>
                <div class="hero-feature">
                    <i class="fas fa-clock"></i>
                    <span>Pengaturan Jam Belajar</span>
                </div>
            </div>
        </div>
        
        <div class="hero-image">
            <div class="hero-image-content">
                <img src="{{ asset('storage/game/calista.png') }}" alt="Calista" class="hero-main-image">
            </div>
        </div>
        
        <div class="hero-notice">
            <i class="fas fa-heart"></i>
            <strong>Aplikasi ini didesain untuk didampingi orang tua, membangun ikatan emosional yang kuat antara anak dan orang tua selama proses belajar.
        </div>
    </section>

    <!-- Nusa AI Section -->
    <section class="ai-section">
        <div class="ai-header">
            <div class="ai-badge">
                <i class="fas fa-star"></i>
                <span>FITUR UNGGULAN</span>
            </div>
            <h2 class="section-title">Kenalan dengan <span class="nusa-ai">Nusa AI</span></h2>
            <p class="section-subtitle">Asisten AI pertama di Indonesia yang khusus didesain untuk pembelajaran anak dengan konten budaya lokal!</p>
        </div>
        
        <div class="ai-features">
            <div class="ai-feature-card">
                <div class="ai-icon">
                    <i class="fas fa-comments"></i>
                </div>
                <h3>Interaktif & Ramah</h3>
                <p>Nusa AI berbicara dengan bahasa anak-anak dan selalu siap membantu dengan sabar</p>
            </div>
            
            <div class="ai-feature-card">
                <div class="ai-icon">
                    <i class="fas fa-landmark"></i>
                </div>
                <h3>Budaya Indonesia</h3>
                <p>Mengenal wayang, batik, tarian daerah, dan kekayaan budaya lainnya</p>
            </div>
            
            <div class="ai-feature-card">
                <div class="ai-icon">
                    <i class="fas fa-brain"></i>
                </div>
                <h3>Pembelajaran Personal</h3>
                <p>Menyesuaikan materi belajar dengan kemampuan dan minat anak</p>
            </div>
        </div>
        
        <div class="ai-demo">
            <div class="ai-message">
                <div class="message-bubble">
                    "Halo! Aku Nusa AI, teman belajarmu! Yuk kita belajar calistung sambil mengenal budaya Indonesia yang indah!"
                </div>
                <div class="ai-avatar">
                    <i class="fas fa-robot"></i>
                </div>
            </div>
        </div>
    </section>

    <!-- Learning Features Section -->
    <section class="learning-section">
        <h2 class="section-title">Belajar Calistung yang Menyenangkan</h2>
        <p class="section-subtitle">Metode belajar yang telah disesuaikan dengan kurikulum anak Indonesia</p>
        
        <div class="learning-grid">
            <div class="learning-card">
                <div class="learning-emoji">🔤</div>
                <h3>Membaca</h3>
                <p>Belajar huruf dengan cerita rakyat Indonesia seperti Malin Kundang dan Timun Mas</p>
                <ul class="learning-list">
                    <li><i class="fas fa-check"></i> Pengenalan huruf A-Z</li>
                    <li><i class="fas fa-check"></i> Membaca kata sederhana</li>
                    <li><i class="fas fa-check"></i> Cerita interaktif</li>
                </ul>
            </div>
            
            <div class="learning-card">
                <div class="learning-emoji">✍️</div>
                <h3>Menulis</h3>
                <p>Latihan menulis dengan contoh motif batik dan aksara daerah</p>
                <ul class="learning-list">
                    <li><i class="fas fa-check"></i> Menulis huruf & angka</li>
                    <li><i class="fas fa-check"></i> Tracing pattern batik</li>
                    <li><i class="fas fa-check"></i> Pengenalan aksara</li>
                </ul>
            </div>
            
            <div class="learning-card">
                <div class="learning-emoji">🔢</div>
                <h3>Berhitung</h3>
                <p>Belajar matematika dengan benda-benda budaya seperti wayang dan keramik</p>
                <ul class="learning-list">
                    <li><i class="fas fa-check"></i> Angka 1-100</li>
                    <li><i class="fas fa-check"></i> Penjumlahan/pengurangan</li>
                    <li><i class="fas fa-check"></i> Berhitung dengan gambar wayang</li>
                </ul>
            </div>
        </div>
    </section>

    <!-- Parental Control Section -->
    <section class="parental-section">
        <div class="parental-content">
            <h2 class="section-title">Kontrol Orang Tua yang <span class="highlight">Aman</span></h2>
            <p class="section-subtitle">Kami percaya teknologi harus mendukung, bukan menggantikan peran orang tua</p>
            
            <div class="parental-features">
                <div class="parental-feature">
                    <div class="parental-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div>
                        <h4>Atur Jam Belajar</h4>
                        <p>Batasi waktu belajar anak agar tidak kecanduan. Orang tua bisa mengatur durasi maksimal harian.</p>
                    </div>
                </div>
                
                <div class="parental-feature">
                    <div class="parental-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div>
                        <h4>Laporan Perkembangan</h4>
                        <p>Pantau perkembangan anak melalui laporan harian dan mingguan yang lengkap.</p>
                    </div>
                </div>
                
                <div class="parental-feature">
                    <div class="parental-icon">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <div>
                        <h4>Aktivitas Bersama</h4>
                        <p>Fitur khusus untuk kegiatan belajar bersama orang tua dan anak.</p>
                    </div>
                </div>
            </div>
            
            <div class="parental-cta">
                <p>Calista didesain sebagai alat bantu, bukan pengganti interaksi langsung. Mari dampingi anak-anak kita dengan penuh cinta!</p>
            </div>
        </div>
        
        <div class="parental-image">
          
        </div>
    </section>

    <!-- Artikel Terbaru Section -->
    <section class="latest-articles-section">
        <h2 class="section-title"><i class="fas fa-newspaper"></i> Tips Parenting & Edukasi</h2>
        <p class="section-subtitle">Artikel terbaru untuk mendukung peran orang tua dalam mendampingi belajar anak</p>
        
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
                            Baca Selengkapnya <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            @empty
                <p class="no-articles">Belum ada artikel tersedia</p>
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

    <!-- Final CTA -->
    <section class="final-cta-section">
        <div class="cta-content">
            <h2>Siap Memulai Petualangan Belajar?</h2>
            <p>Bergabung dengan ribuan keluarga Indonesia yang telah mempercayakan Calista untuk pendidikan awal anak mereka.</p>
            
            <div class="cta-buttons">
                <a href="{{ route('artikel.index') }}" class="cta-btn learn-more">
                    <i class="fas fa-graduation-cap"></i> Pelajari Dulu Tipsnya
                </a>
                <a href="{{ route('premium.show') }}" class="cta-btn premium-cta">
                    <i class="fas fa-crown"></i> Upgrade Premium
                </a>
                <button class="cta-btn primary download-app">
                    <i class="fas fa-download"></i> Download Calista
                </button>
            </div>
            
            <div class="app-stores">
                <p>Tersedia di:</p>
                <div class="store-buttons">
                    <div class="store-btn">
                        <i class="fab fa-google-play"></i> Google Play
                    </div>
                    <div class="store-btn">
                        <i class="fab fa-app-store"></i> App Store
                    </div>
                </div>
            </div>
            
            <div class="safety-notice">
                <i class="fas fa-shield-alt"></i>
                <span><strong>Keamanan Terjamin:</strong> Tidak ada iklan, tidak ada pembelian dalam aplikasi tanpa persetujuan orang tua.</span>
            </div>
        </div>
    </section>
</div>

<style>
    .beranda-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
        font-family: 'Poppins', sans-serif;
    }

    /* Hero Section */
    .hero-section {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 50px;
        align-items: center;
        padding: 60px 40px;
        background: linear-gradient(135deg, #f8f9ff 0%, #eef2ff 100%);
        border-radius: 30px;
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
        height: 8px;
        background: linear-gradient(90deg, #FF6B6B, #4ECDC4, #45B7D1, #96CEB4);
    }

    .calista-badge {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: rgba(255, 215, 0, 0.2);
        padding: 10px 20px;
        border-radius: 50px;
        margin-bottom: 25px;
        font-weight: 600;
        color: #D4AF37;
        border: 2px solid #FFD700;
    }

    .hero-title {
        font-family: 'Fredoka One', cursive;
        font-size: 3.2rem;
        color: #2D3748;
        margin-bottom: 20px;
        line-height: 1.2;
    }

    .highlight {
        background: linear-gradient(135deg, #FF6B6B 0%, #4ECDC4 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .hero-subtitle {
        font-size: 1.3rem;
        color: #4A5568;
        margin-bottom: 30px;
        line-height: 1.7;
    }

    .nusa-ai {
        font-weight: 900;
        color: #4ECDC4;
        background: rgba(78, 205, 196, 0.1);
        padding: 2px 8px;
        border-radius: 8px;
        font-style: italic;
    }

    .hero-features {
        display: flex;
        gap: 20px;
        margin-bottom: 30px;
        flex-wrap: wrap;
    }

    .hero-feature {
        display: flex;
        align-items: center;
        gap: 10px;
        background: white;
        padding: 12px 20px;
        border-radius: 12px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        font-weight: 600;
        color: #2D3748;
    }

    .hero-feature i {
        color: #4ECDC4;
        font-size: 1.2rem;
    }

    .hero-notice {
        background: rgba(255, 107, 107, 0.1);
        padding: 20px;
        border-radius: 15px;
        border-left: 5px solid #FF6B6B;
        color: #2D3748;
        font-size: 1rem;
        line-height: 1.6;
    }

    .hero-notice i {
        color: #FF6B6B;
        margin-right: 10px;
    }

    .hero-image {
        position: relative;
        height: 400px;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .floating-element {
        font-size: 3rem;
        position: absolute;
        animation: float 6s ease-in-out infinite;
        z-index: 1;
    }

    .floating-element.indonesia {
        top: 20px;
        left: 20px;
        animation-delay: 0s;
        font-size: 4rem;
    }

    .floating-element.ai {
        top: 40px;
        right: 40px;
        animation-delay: 2s;
    }

    .floating-element.book {
        bottom: 30px;
        left: 50px;
        animation-delay: 4s;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0) rotate(0deg); }
        50% { transform: translateY(-20px) rotate(10deg); }
    }

    .hero-image-content {
        position: relative;
        z-index: 2;
        text-align: center;
    }

    .parent-child-illustration {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 20px;
        font-size: 4rem;
        margin-bottom: 20px;
    }

    .hero-main-image {
        max-width: 100%;
        height: auto;
        max-height: 450px;
        object-fit: contain;
        margin-bottom: 20px;
        filter: drop-shadow(0 10px 25px rgba(0, 0, 0, 0.1));
    }

    .heart {
        font-size: 2rem;
        animation: heartbeat 1.5s ease-in-out infinite;
    }

    @keyframes heartbeat {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.2); }
    }

    .tablet {
        position: absolute;
        bottom: 20px;
        right: 40px;
        font-size: 3rem;
        animation: bounce 2s infinite;
    }

    .illustration-caption {
        font-size: 1.1rem;
        color: #4A5568;
        font-style: italic;
        margin-top: 10px;
    }

    /* Nusa AI Section */
    .ai-section {
        background: linear-gradient(135deg, #1A2980 0%, #26D0CE 100%);
        padding: 60px 40px;
        border-radius: 30px;
        color: white;
        margin-bottom: 60px;
    }

    .ai-header {
        text-align: center;
        margin-bottom: 50px;
    }

    .ai-badge {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: rgba(255, 255, 255, 0.2);
        padding: 12px 25px;
        border-radius: 50px;
        margin-bottom: 20px;
        font-weight: 600;
        backdrop-filter: blur(10px);
    }

    .ai-badge i {
        color: #FFD700;
    }

    .ai-section .section-title {
        font-family: 'Fredoka One', cursive;
        font-size: 2.8rem;
        margin-bottom: 15px;
        color: white;
    }

    .ai-section .section-subtitle {
        font-size: 1.2rem;
        opacity: 0.9;
        max-width: 600px;
        margin: 0 auto;
        color: white;
    }

    .ai-features {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 30px;
        margin-bottom: 50px;
    }

    .ai-feature-card {
        background: rgba(255, 255, 255, 0.1);
        padding: 30px;
        border-radius: 20px;
        text-align: center;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        transition: transform 0.3s;
    }

    .ai-feature-card:hover {
        transform: translateY(-10px);
        background: rgba(255, 255, 255, 0.15);
    }

    .ai-icon {
        width: 80px;
        height: 80px;
        background: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        font-size: 2rem;
        color: #1A2980;
    }

    .ai-feature-card h3 {
        font-size: 1.5rem;
        margin-bottom: 15px;
    }

    .ai-feature-card p {
        opacity: 0.9;
        line-height: 1.6;
    }

    .ai-demo {
        max-width: 600px;
        margin: 0 auto;
    }

    .ai-message {
        display: flex;
        align-items: center;
        gap: 20px;
        animation: slideIn 1s ease-out;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(-20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .message-bubble {
        background: white;
        color: #2D3748;
        padding: 25px;
        border-radius: 25px;
        border-bottom-left-radius: 0;
        flex: 1;
        font-size: 1.2rem;
        line-height: 1.6;
        position: relative;
    }

    .message-bubble::after {
        content: "";
        position: absolute;
        bottom: 0;
        left: -20px;
        width: 20px;
        height: 20px;
        background: white;
        border-bottom-right-radius: 20px;
    }

    .ai-avatar {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #FF6B6B, #4ECDC4);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        color: white;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
    }

    /* Learning Features Section */
    .learning-section {
        margin-bottom: 80px;
    }

    .section-title {
        text-align: center;
        font-family: 'Fredoka One', cursive;
        font-size: 2.5rem;
        color: #2D3748;
        margin-bottom: 15px;
    }

    .section-subtitle {
        text-align: center;
        font-size: 1.2rem;
        color: #4A5568;
        margin-bottom: 50px;
        max-width: 700px;
        margin-left: auto;
        margin-right: auto;
    }

    .learning-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 30px;
    }

    .learning-card {
        background: white;
        padding: 30px;
        border-radius: 20px;
        text-align: center;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        border-top: 5px solid #4ECDC4;
        transition: transform 0.3s;
    }

    .learning-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 40px rgba(78, 205, 196, 0.2);
    }

    .learning-emoji {
        font-size: 4rem;
        margin-bottom: 20px;
    }

    .learning-card h3 {
        font-size: 1.8rem;
        color: #2D3748;
        margin-bottom: 15px;
    }

    .learning-card p {
        color: #4A5568;
        margin-bottom: 25px;
        line-height: 1.6;
    }

    .learning-list {
        list-style: none;
        padding: 0;
        text-align: left;
    }

    .learning-list li {
        margin-bottom: 10px;
        color: #4A5568;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .learning-list i {
        color: #4ECDC4;
    }

    /* Parental Control Section */
    .parental-section {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 50px;
        align-items: center;
        background: linear-gradient(135deg, #FFF9EC 0%, #FFE8E8 100%);
        padding: 60px 40px;
        border-radius: 30px;
        margin-bottom: 80px;
    }

    .parental-features {
        margin: 30px 0;
    }

    .parental-feature {
        display: flex;
        align-items: center;
        gap: 20px;
        margin-bottom: 30px;
        padding: 20px;
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }

    .parental-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #FF6B6B, #4ECDC4);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
        flex-shrink: 0;
    }

    .parental-feature h4 {
        font-size: 1.3rem;
        color: #2D3748;
        margin-bottom: 5px;
    }

    .parental-feature p {
        color: #4A5568;
        line-height: 1.6;
    }

    .parental-cta {
        background: rgba(78, 205, 196, 0.1);
        padding: 20px;
        border-radius: 15px;
        border-left: 5px solid #4ECDC4;
        font-size: 1rem;
        line-height: 1.6;
    }

    .parental-image {
        text-align: center;
    }

    .parental-illustration {
        font-size: 3rem;
        position: relative;
        height: 300px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 20px;
    }

    .parent-setting {
        animation: float 3s ease-in-out infinite;
    }

    .child-learning {
        animation: float 3s ease-in-out infinite reverse;
    }

    .time-display {
        font-size: 1.5rem;
        background: white;
        padding: 10px 20px;
        border-radius: 50px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        margin-top: 20px;
    }

    /* Articles Section */
    .latest-articles-section {
        margin-bottom: 60px;
    }

    .articles-preview-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 30px;
        margin-bottom: 40px;
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
        height: 180px;
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
        padding: 25px;
    }

    .preview-date {
        font-size: 0.9rem;
        color: #718096;
        margin-bottom: 10px;
    }

    .preview-title {
        font-size: 1.2rem;
        color: #2D3748;
        margin-bottom: 15px;
        line-height: 1.4;
    }

    .preview-title a {
        text-decoration: none;
        color: #2D3748;
        transition: color 0.3s;
    }

    .preview-title a:hover {
        color: #4ECDC4;
    }

    .preview-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #4ECDC4;
        text-decoration: none;
        font-weight: 600;
        transition: gap 0.3s;
    }

    .preview-link:hover {
        gap: 12px;
    }

    .no-articles {
        text-align: center;
        grid-column: 1 / -1;
        color: #718096;
        font-style: italic;
        padding: 40px;
    }

    .center-button {
        text-align: center;
    }

    .view-all-btn {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: linear-gradient(135deg, #4ECDC4 0%, #45B7D1 100%);
        color: white;
        padding: 16px 32px;
        border-radius: 50px;
        font-size: 1.1rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s;
        box-shadow: 0 8px 0 #2D9CDB;
    }

    .view-all-btn:hover {
        transform: translateY(-5px);
        box-shadow: 0 13px 0 #2D9CDB;
    }

    /* Final CTA */
    .final-cta-section {
        background: linear-gradient(135deg, #2D3748 0%, #1A202C 100%);
        padding: 60px 40px;
        border-radius: 30px;
        text-align: center;
        color: white;
    }

    .final-cta-section h2 {
        font-family: 'Fredoka One', cursive;
        font-size: 2.8rem;
        margin-bottom: 20px;
    }

    .final-cta-section p {
        font-size: 1.3rem;
        opacity: 0.9;
        margin-bottom: 40px;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
    }

    .cta-buttons {
        display: flex;
        gap: 20px;
        justify-content: center;
        margin-bottom: 40px;
        flex-wrap: wrap;
    }

    .cta-btn {
        display: inline-flex;
        align-items: center;
        gap: 15px;
        padding: 18px 35px;
        border-radius: 50px;
        font-size: 1.2rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s;
        cursor: pointer;
        border: none;
        font-family: 'Poppins', sans-serif;
    }

    .cta-btn.primary {
        background: linear-gradient(135deg, #FF6B6B 0%, #FF8E8E 100%);
        color: white;
        box-shadow: 0 10px 0 #E53E3E;
    }

    .cta-btn.learn-more {
        background: transparent;
        color: white;
        border: 2px solid rgba(255, 255, 255, 0.3);
    }

    .cta-btn.premium-cta {
        background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%);
        color: #333;
        box-shadow: 0 10px 0 #CC8400;
        font-weight: 700;
    }

    .cta-btn:hover {
        transform: translateY(-5px);
    }

    .cta-btn.primary:hover {
        box-shadow: 0 15px 0 #E53E3E;
    }

    .cta-btn.learn-more:hover {
        background: rgba(255, 255, 255, 0.1);
        border-color: white;
    }

    .cta-btn.premium-cta:hover {
        box-shadow: 0 15px 0 #CC8400;
        background: linear-gradient(135deg, #FFE44D 0%, #FFB700 100%);
    }

    .app-stores {
        margin-bottom: 30px;
    }

    .app-stores p {
        font-size: 1rem;
        opacity: 0.8;
        margin-bottom: 15px;
    }

    .store-buttons {
        display: flex;
        gap: 15px;
        justify-content: center;
        flex-wrap: wrap;
    }

    .store-btn {
        display: flex;
        align-items: center;
        gap: 10px;
        background: rgba(255, 255, 255, 0.1);
        padding: 15px 25px;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.3s;
        cursor: pointer;
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .store-btn:hover {
        background: rgba(255, 255, 255, 0.2);
        transform: translateY(-3px);
    }

    .store-btn i {
        font-size: 1.3rem;
    }

    .safety-notice {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 15px;
        background: rgba(78, 205, 196, 0.2);
        padding: 20px;
        border-radius: 15px;
        max-width: 500px;
        margin: 0 auto;
    }

    .safety-notice i {
        color: #4ECDC4;
        font-size: 1.5rem;
    }

    .safety-notice span {
        font-size: 0.95rem;
    }

    /* Responsive Design */
    @media (max-width: 1024px) {
        .beranda-container {
            padding: 15px;
        }

        .hero-section {
            gap: 30px;
            padding: 40px 30px;
        }

        .hero-title {
            font-size: 2.8rem;
        }

        .section-title {
            font-size: 2.2rem;
        }
    }

    @media (max-width: 768px) {
        .beranda-container {
            padding: 10px;
        }

        /* Hero Section Mobile */
        .hero-section {
            grid-template-columns: 1fr;
            gap: 40px;
            padding: 30px 20px;
            margin-bottom: 40px;
            text-align: center;
        }

        .hero-title {
            font-size: 2.2rem;
            line-height: 1.3;
        }

        .hero-subtitle {
            font-size: 1.1rem;
            margin-bottom: 25px;
        }

        .hero-features {
            justify-content: center;
            gap: 15px;
        }

        .hero-feature {
            padding: 10px 16px;
            font-size: 0.95rem;
        }

        .hero-image {
            height: 300px;
        }

        .floating-element {
            font-size: 2.5rem;
        }

        .floating-element.indonesia {
            font-size: 3rem;
            top: 10px;
            left: 10px;
        }

        .parent-child-illustration {
            font-size: 3rem;
            gap: 15px;
        }

        .tablet {
            font-size: 2.5rem;
            right: 20px;
            bottom: 10px;
        }

        /* AI Section Mobile */
        .ai-section {
            padding: 40px 20px;
            border-radius: 20px;
            margin-bottom: 40px;
        }

        .ai-section .section-title {
            font-size: 2rem;
        }

        .ai-section .section-subtitle {
            font-size: 1.1rem;
            padding: 0 10px;
        }

        .ai-features {
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .ai-feature-card {
            padding: 25px 20px;
        }

        .ai-icon {
            width: 70px;
            height: 70px;
            font-size: 1.8rem;
        }

        .ai-message {
            flex-direction: column;
            text-align: center;
            gap: 15px;
        }

        .message-bubble {
            border-radius: 20px;
            border-bottom-left-radius: 20px;
            font-size: 1.1rem;
        }

        .message-bubble::after {
            display: none;
        }

        .ai-avatar {
            width: 70px;
            height: 70px;
            font-size: 2rem;
        }

        /* Learning Section Mobile */
        .learning-section {
            margin-bottom: 50px;
        }

        .section-title {
            font-size: 2rem;
            padding: 0 10px;
        }

        .section-subtitle {
            font-size: 1.1rem;
            padding: 0 15px;
            margin-bottom: 30px;
        }

        .learning-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .learning-card {
            padding: 25px 20px;
        }

        .learning-emoji {
            font-size: 3.5rem;
        }

        .learning-card h3 {
            font-size: 1.5rem;
        }

        /* Parental Section Mobile */
        .parental-section {
            grid-template-columns: 1fr;
            gap: 40px;
            padding: 40px 25px;
            margin-bottom: 50px;
            text-align: center;
        }

        .parental-feature {
            flex-direction: column;
            text-align: center;
            padding: 20px;
        }

        .parental-icon {
            width: 70px;
            height: 70px;
            font-size: 1.8rem;
        }

        .parental-illustration {
            height: 250px;
            font-size: 2.5rem;
        }

        .time-display {
            font-size: 1.3rem;
            padding: 8px 16px;
        }

        /* Articles Mobile */
        .latest-articles-section {
            margin-bottom: 40px;
        }

        .articles-preview-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .preview-image {
            height: 150px;
        }

        .preview-content {
            padding: 20px;
        }

        .preview-title {
            font-size: 1.1rem;
        }

        /* Final CTA Mobile */
        .final-cta-section {
            padding: 40px 25px;
            border-radius: 20px;
        }

        .final-cta-section h2 {
            font-size: 2rem;
        }

        .final-cta-section p {
            font-size: 1.1rem;
            padding: 0 10px;
        }

        .cta-buttons {
            flex-direction: column;
            gap: 15px;
            padding: 0 20px;
        }

        .cta-btn {
            width: 100%;
            justify-content: center;
            padding: 16px 20px;
            font-size: 1.1rem;
        }

        .store-buttons {
            flex-direction: column;
            gap: 10px;
            padding: 0 20px;
        }

        .store-btn {
            width: 100%;
            justify-content: center;
            padding: 14px 20px;
        }

        .safety-notice {
            flex-direction: column;
            text-align: center;
            gap: 10px;
            padding: 15px;
            margin: 0 20px;
        }
    }

    @media (max-width: 480px) {
        .beranda-container {
            padding: 5px;
        }

        /* Hero Mobile Small */
        .hero-section {
            padding: 25px 15px;
            margin-bottom: 30px;
            border-radius: 20px;
        }

        .hero-section::before {
            height: 5px;
        }

        .calista-badge {
            padding: 8px 16px;
            font-size: 0.9rem;
            margin-bottom: 20px;
        }

        .hero-title {
            font-size: 1.8rem;
        }

        .hero-subtitle {
            font-size: 1rem;
            margin-bottom: 20px;
        }

        .hero-features {
            flex-direction: column;
            align-items: center;
        }

        .hero-feature {
            width: 100%;
            justify-content: center;
        }

        .hero-notice {
            padding: 15px;
            font-size: 0.95rem;
        }

        .hero-image {
            height: 250px;
        }

        .floating-element {
            font-size: 2rem;
        }

        .floating-element.indonesia {
            font-size: 2.5rem;
            top: 5px;
            left: 5px;
        }

        .parent-child-illustration {
            font-size: 2.5rem;
            gap: 10px;
        }

        .heart {
            font-size: 1.5rem;
        }

        .tablet {
            font-size: 2rem;
            right: 10px;
            bottom: 5px;
        }

        .illustration-caption {
            font-size: 0.95rem;
        }

        /* AI Section Mobile Small */
        .ai-section {
            padding: 30px 15px;
            border-radius: 15px;
            margin-bottom: 30px;
        }

        .ai-badge {
            padding: 10px 20px;
            font-size: 0.9rem;
        }

        .ai-section .section-title {
            font-size: 1.7rem;
        }

        .ai-section .section-subtitle {
            font-size: 1rem;
        }

        .ai-feature-card {
            padding: 20px 15px;
            border-radius: 15px;
        }

        .ai-icon {
            width: 60px;
            height: 60px;
            font-size: 1.5rem;
            margin-bottom: 15px;
        }

        .ai-feature-card h3 {
            font-size: 1.3rem;
        }

        .ai-feature-card p {
            font-size: 0.95rem;
        }

        .message-bubble {
            padding: 20px;
            font-size: 1rem;
        }

        .ai-avatar {
            width: 60px;
            height: 60px;
            font-size: 1.8rem;
        }

        /* Learning Mobile Small */
        .learning-section {
            margin-bottom: 40px;
        }

        .section-title {
            font-size: 1.7rem;
        }

        .section-subtitle {
            font-size: 1rem;
            margin-bottom: 25px;
        }

        .learning-card {
            padding: 20px 15px;
            border-radius: 15px;
        }

        .learning-emoji {
            font-size: 3rem;
            margin-bottom: 15px;
        }

        .learning-card h3 {
            font-size: 1.3rem;
        }

        .learning-card p {
            font-size: 0.95rem;
            margin-bottom: 20px;
        }

        .learning-list {
            font-size: 0.9rem;
        }

        /* Parental Mobile Small */
        .parental-section {
            padding: 30px 20px;
            margin-bottom: 40px;
            border-radius: 20px;
        }

        .parental-section .section-title {
            font-size: 1.7rem;
        }

        .parental-feature {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 12px;
        }

        .parental-icon {
            width: 50px;
            height: 50px;
            font-size: 1.3rem;
        }

        .parental-feature h4 {
            font-size: 1.1rem;
        }

        .parental-feature p {
            font-size: 0.9rem;
        }

        .parental-cta {
            padding: 15px;
            font-size: 0.9rem;
        }

        .parental-illustration {
            height: 200px;
            font-size: 2rem;
        }

        .time-display {
            font-size: 1.1rem;
            padding: 8px 16px;
        }

        /* Articles Mobile Small */
        .latest-articles-section {
            margin-bottom: 30px;
        }

        .articles-preview-grid {
            gap: 15px;
        }

        .article-preview-card {
            border-radius: 12px;
        }

        .preview-image {
            height: 120px;
        }

        .preview-content {
            padding: 15px;
        }

        .preview-date {
            font-size: 0.8rem;
        }

        .preview-title {
            font-size: 1rem;
            margin-bottom: 10px;
        }

        .preview-link {
            font-size: 0.9rem;
        }

        .view-all-btn {
            padding: 14px 28px;
            font-size: 1rem;
            width: 100%;
            max-width: 300px;
        }

        /* Final CTA Mobile Small */
        .final-cta-section {
            padding: 30px 20px;
            border-radius: 20px;
        }

        .final-cta-section h2 {
            font-size: 1.7rem;
            margin-bottom: 15px;
        }

        .final-cta-section p {
            font-size: 1rem;
            margin-bottom: 30px;
        }

        .cta-buttons {
            gap: 10px;
        }

        .cta-btn {
            padding: 14px 20px;
            font-size: 1rem;
        }

        .app-stores p {
            font-size: 0.9rem;
        }

        .store-btn {
            padding: 12px 20px;
            font-size: 0.9rem;
        }

        .safety-notice {
            padding: 12px;
            font-size: 0.85rem;
            margin: 0 10px;
        }

        .safety-notice i {
            font-size: 1.2rem;
        }
    }

    /* Landscape Orientation */
    @media (max-height: 600px) and (orientation: landscape) {
        .hero-section {
            min-height: auto;
            padding: 30px 20px;
        }

        .hero-image {
            height: 200px;
        }

        .parental-section {
            min-height: auto;
        }

        .ai-features {
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        }
    }

    /* Print Styles */
    @media print {
        .beranda-container {
            max-width: 100%;
            padding: 0;
        }

        .hero-section, .ai-section, .parental-section, .final-cta-section {
            break-inside: avoid;
            page-break-inside: avoid;
        }

        .cta-buttons, .store-buttons, .hero-buttons {
            display: none;
        }
    }
</style>

<script>
    // Animation for store buttons
    document.addEventListener('DOMContentLoaded', function() {
        const storeButtons = document.querySelectorAll('.store-btn');
        const downloadBtn = document.querySelector('.download-app');
        
        storeButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                alert('Calista akan segera tersedia di platform ini!');
            });
        });
        
        if (downloadBtn) {
            downloadBtn.addEventListener('click', function() {
                alert('Fitur download akan segera tersedia! Untuk saat ini, Anda dapat mengakses Calista melalui website ini.');
            });
        }
        
        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                if(targetId !== '#') {
                    const targetElement = document.querySelector(targetId);
                    if(targetElement) {
                        targetElement.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                }
            });
        });
        
        // Add loading animation for images
        const images = document.querySelectorAll('img');
        images.forEach(img => {
            img.addEventListener('load', function() {
                this.classList.add('loaded');
            });
        });
    });
</script>

@endsection