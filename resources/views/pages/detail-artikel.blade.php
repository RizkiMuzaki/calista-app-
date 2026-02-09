@extends('layouts.app')

@section('title', $artikel->title . ' - Petualangan Belajar Seru')

@section('content')
<div class="detail-artikel-container">
    <!-- Background Image -->
    <div class="background-image">
        @if($artikel->image)
            <img src="{{ asset('storage/' . $artikel->image) }}" alt="{{ $artikel->title }}" class="bg-image">
        @else
            <div class="bg-gradient"></div>
        @endif
        <div class="overlay"></div>
    </div>

    <!-- Breadcrumb -->
    <nav class="breadcrumb-nav">
        <a href="{{ route('artikel.index') }}"><i class="fas fa-newspaper"></i> Artikel</a>
        <span><i class="fas fa-chevron-right"></i></span>
        <span>{{ Str::limit($artikel->title, 50) }}</span>
    </nav>

    <div class="artikel-detail-wrapper">
        <!-- Main Content -->
        <main class="artikel-detail-main">
            <!-- Article Header -->
            <article class="artikel-detail">
                @if($artikel->image)
                    <div class="artikel-detail-image">
                        <img src="{{ asset('storage/' . $artikel->image) }}" alt="{{ $artikel->title }}">
                    </div>
                @endif

                <div class="artikel-detail-header">
                    <h1 class="artikel-detail-title">{{ $artikel->title }}</h1>
                    
                    <!-- Username below title -->
                    <div class="author-display">
                        <i class="fas fa-user-circle"></i>
                        <span class="author-name">{{ $artikel->user->name ?? 'Admin' }}</span>
                    </div>
                    
                    <div class="artikel-detail-meta">
                        <span class="meta-item">
                            <i class="far fa-calendar"></i>
                            {{ $artikel->created_at->format('d F Y') }}
                        </span>
                        <span class="meta-item">
                            <i class="far fa-clock"></i>
                            {{ ceil(str_word_count(strip_tags($artikel->content)) / 200) }} min read
                        </span>
                    </div>
                </div>

                <!-- Article Content -->
                <div class="artikel-detail-content">
                    {!! $artikel->content !!}
                </div>

                <!-- Share Section -->
                <div class="share-section">
                    <h3><i class="fas fa-share-alt"></i> Bagikan Artikel</h3>
                    <div class="share-buttons">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ route('artikel.show', $artikel->slug) }}" 
                           target="_blank" class="share-btn facebook" title="Bagikan ke Facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ route('artikel.show', $artikel->slug) }}&text={{ $artikel->title }}" 
                           target="_blank" class="share-btn twitter" title="Bagikan ke Twitter">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="https://wa.me/?text={{ $artikel->title }}%20{{ route('artikel.show', $artikel->slug) }}" 
                           target="_blank" class="share-btn whatsapp" title="Bagikan ke WhatsApp">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                        <button class="share-btn copy-link" onclick="copyLink()" title="Salin Link">
                            <i class="fas fa-link"></i>
                        </button>
                    </div>
                </div>
            </article>

            <!-- Related Articles -->
            @if($relatedArtikels->count() > 0)
                <section class="related-articles">
                    <h2><i class="fas fa-related"></i> Artikel Terkait</h2>
                    <div class="related-grid">
                        @foreach($relatedArtikels as $related)
                            <div class="related-card">
                                @if($related->image)
                                    <div class="related-image">
                                        <img src="{{ asset('storage/' . $related->image) }}" alt="{{ $related->title }}">
                                    </div>
                                @else
                                    <div class="related-image no-image">
                                        <i class="fas fa-file-alt"></i>
                                    </div>
                                @endif

                                <div class="related-content">
                                    <p class="related-date">{{ $related->created_at->format('d M Y') }}</p>
                                    <h3 class="related-title">
                                        <a href="{{ route('artikel.show', $related->slug) }}">
                                            {{ $related->title }}
                                        </a>
                                    </h3>
                                    <a href="{{ route('artikel.show', $related->slug) }}" class="related-link">
                                        Baca Selengkapnya <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif
        </main>

        <!-- Sidebar - EMPTY (removed author widget) -->
        <aside class="artikel-sidebar">
            <!-- Sidebar content removed as requested -->
        </aside>
    </div>

    <!-- Back Button -->
    <div class="back-button-section">
        <a href="{{ route('artikel.index') }}" class="back-button">
            <i class="fas fa-chevron-left"></i> Kembali ke Artikel
        </a>
    </div>
</div>

<style>
    /* Background Image */
    .background-image {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100vh;
        z-index: -1;
        overflow: hidden;
    }

    .background-image .bg-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        filter: brightness(0.6);
    }

    .background-image .bg-gradient {
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #118ab2 0%, #06d6a0 100%);
    }

    .background-image .overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(3px);
    }

    .detail-artikel-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
        position: relative;
        z-index: 1;
        min-height: 100vh;
    }

    /* Breadcrumb - Transparent */
    .breadcrumb-nav {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 40px;
        font-size: 0.95rem;
        flex-wrap: wrap;
        padding: 15px 20px;
        background: rgba(255, 255, 255, 0.15);
        border-radius: 12px;
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .breadcrumb-nav a {
        display: flex;
        align-items: center;
        gap: 5px;
        color: white;
        text-decoration: none;
        transition: all 0.3s;
        padding: 5px 10px;
        border-radius: 6px;
    }

    .breadcrumb-nav a:hover {
        background: rgba(255, 255, 255, 0.2);
    }

    .breadcrumb-nav span {
        color: rgba(255, 255, 255, 0.8);
    }

    /* Wrapper - Changed to single column since sidebar is empty */
    .artikel-detail-wrapper {
        display: block;
        margin-bottom: 50px;
    }

    .artikel-detail-main {
        width: 100%;
    }

    /* Main Content - Transparent */
    .artikel-detail {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.15);
        margin-bottom: 40px;
    }

    .artikel-detail-image {
        width: 100%;
        height: 400px;
        overflow: hidden;
        position: relative;
    }

    .artikel-detail-image:before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(to bottom, transparent 70%, rgba(0, 0, 0, 0.3) 100%);
        z-index: 1;
    }

    .artikel-detail-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s;
    }

    .artikel-detail:hover .artikel-detail-image img {
        transform: scale(1.05);
    }

    .artikel-detail-header {
        padding: 40px 40px 20px;
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(10px);
    }

    .artikel-detail-title {
        font-family: 'Fredoka One', cursive;
        font-size: 2.5rem;
        color: white;
        margin-bottom: 15px;
        line-height: 1.3;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
    }

    /* Author Display below title */
    .author-display {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 20px;
        padding: 10px 15px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 10px;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        max-width: fit-content;
    }

    .author-display i {
        color: #06d6a0;
        font-size: 1.2rem;
    }

    .author-display .author-name {
        color: white;
        font-weight: 600;
        font-size: 1.1rem;
    }

    .artikel-detail-meta {
        display: flex;
        gap: 30px;
        flex-wrap: wrap;
        padding-bottom: 20px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 8px;
        color: rgba(255, 255, 255, 0.9);
        font-size: 0.95rem;
        padding: 8px 15px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .meta-item i {
        color: #06d6a0;
    }

    /* Article Content - Transparent */
    .artikel-detail-content {
        padding: 40px;
        font-size: 1.1rem;
        line-height: 1.8;
        color: rgba(255, 255, 255, 0.95);
        background: rgba(0, 0, 0, 0.1);
        backdrop-filter: blur(10px);
    }

    .artikel-detail-content h2,
    .artikel-detail-content h3,
    .artikel-detail-content h4 {
        color: white;
        margin-top: 30px;
        margin-bottom: 15px;
        font-weight: bold;
        padding: 10px 15px;
        background: rgba(17, 138, 178, 0.2);
        border-radius: 8px;
        border-left: 4px solid #06d6a0;
        text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.3);
    }

    .artikel-detail-content h2 {
        font-size: 1.8rem;
    }

    .artikel-detail-content p {
        margin-bottom: 20px;
        text-align: justify;
        padding: 10px;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 8px;
        backdrop-filter: blur(5px);
    }

    .artikel-detail-content ul,
    .artikel-detail-content ol {
        margin-bottom: 20px;
        margin-left: 25px;
        padding: 15px;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 8px;
        backdrop-filter: blur(5px);
    }

    .artikel-detail-content li {
        margin-bottom: 10px;
        padding: 5px 0;
        color: rgba(255, 255, 255, 0.9);
    }

    .artikel-detail-content blockquote {
        border-left: 4px solid #06d6a0;
        padding: 20px;
        margin: 25px 0;
        font-style: italic;
        color: rgba(255, 255, 255, 0.9);
        background: rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        backdrop-filter: blur(15px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .artikel-detail-content img {
        max-width: 100%;
        height: auto;
        border-radius: 12px;
        margin: 20px 0;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    /* Share Section - Transparent */
    .share-section {
        padding: 30px 40px;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(15px);
    }

    .share-section h3 {
        margin-bottom: 20px;
        color: white;
        font-size: 1.2rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .share-buttons {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
    }

    .share-btn {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        color: white;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: all 0.3s;
        backdrop-filter: blur(10px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .share-btn.facebook {
        background: rgba(59, 89, 152, 0.8);
    }

    .share-btn.twitter {
        background: rgba(29, 161, 242, 0.8);
    }

    .share-btn.whatsapp {
        background: rgba(37, 211, 102, 0.8);
    }

    .share-btn.copy-link {
        background: rgba(17, 138, 178, 0.8);
    }

    .share-btn:hover {
        transform: translateY(-5px) scale(1.1);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
        border-color: rgba(255, 255, 255, 0.3);
    }

    /* Related Articles - Transparent */
    .related-articles {
        margin-top: 60px;
        padding-top: 40px;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
    }

    .related-articles h2 {
        font-family: 'Fredoka One', cursive;
        font-size: 2rem;
        color: white;
        margin-bottom: 30px;
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 15px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 15px;
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .related-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 25px;
    }

    .related-card {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        transition: all 0.3s;
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .related-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
        border-color: rgba(255, 255, 255, 0.2);
        background: rgba(255, 255, 255, 0.15);
    }

    .related-image {
        height: 150px;
        overflow: hidden;
        position: relative;
    }

    .related-image:before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(to bottom, transparent 70%, rgba(0, 0, 0, 0.3) 100%);
        z-index: 1;
    }

    .related-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s;
    }

    .related-card:hover .related-image img {
        transform: scale(1.1);
    }

    .related-image.no-image {
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, rgba(17, 138, 178, 0.8) 0%, rgba(6, 214, 160, 0.8) 100%);
        color: white;
        font-size: 2.5rem;
        backdrop-filter: blur(10px);
    }

    .related-content {
        padding: 20px;
        background: rgba(0, 0, 0, 0.1);
        backdrop-filter: blur(10px);
    }

    .related-date {
        font-size: 0.85rem;
        color: rgba(255, 255, 255, 0.7);
        margin-bottom: 8px;
        padding: 5px 10px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 12px;
        display: inline-block;
        backdrop-filter: blur(5px);
    }

    .related-title {
        font-size: 1.1rem;
        color: white;
        margin-bottom: 12px;
        line-height: 1.4;
    }

    .related-title a {
        text-decoration: none;
        color: white;
        transition: all 0.3s;
        display: block;
        padding: 5px;
        border-radius: 6px;
    }

    .related-title a:hover {
        background: rgba(255, 255, 255, 0.1);
    }

    .related-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #06d6a0;
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: bold;
        padding: 8px 16px;
        background: rgba(6, 214, 160, 0.1);
        border-radius: 20px;
        transition: all 0.3s;
        backdrop-filter: blur(5px);
        border: 1px solid rgba(6, 214, 160, 0.2);
    }

    .related-link:hover {
        gap: 12px;
        background: rgba(6, 214, 160, 0.2);
        transform: translateX(5px);
    }

    /* Sidebar - Hidden */
    .artikel-sidebar {
        display: none;
    }

    /* Back Button - Transparent */
    .back-button-section {
        text-align: center;
        margin-top: 50px;
    }

    .back-button {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: rgba(17, 138, 178, 0.3);
        color: white;
        padding: 15px 35px;
        border-radius: 15px;
        text-decoration: none;
        font-weight: bold;
        transition: all 0.3s;
        backdrop-filter: blur(15px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .back-button:hover {
        transform: translateX(-10px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
        background: rgba(6, 214, 160, 0.3);
        border-color: rgba(255, 255, 255, 0.3);
    }

    /* Responsive Design */
    @media (max-width: 1100px) {
        .related-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 900px) {
        .artikel-detail-title {
            font-size: 2rem;
        }
        
        .related-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .detail-artikel-container {
            padding: 15px;
        }
        
        .artikel-detail-image {
            height: 300px;
        }
        
        .artikel-detail-header,
        .artikel-detail-content {
            padding: 25px;
        }
        
        .artikel-detail-title {
            font-size: 1.8rem;
        }
        
        .artikel-detail-meta {
            gap: 15px;
            justify-content: center;
        }
        
        .meta-item {
            font-size: 0.85rem;
            padding: 6px 12px;
        }
        
        .share-buttons {
            justify-content: center;
        }
    }

    @media (max-width: 600px) {
        .breadcrumb-nav {
            padding: 10px 15px;
            font-size: 0.85rem;
        }
        
        .artikel-detail-image {
            height: 250px;
        }
        
        .artikel-detail-header,
        .artikel-detail-content {
            padding: 20px;
        }
        
        .artikel-detail-title {
            font-size: 1.6rem;
        }
        
        .author-display {
            font-size: 0.9rem;
            padding: 8px 12px;
        }
        
        .artikel-detail-meta {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }
        
        .related-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }
        
        .share-btn {
            width: 45px;
            height: 45px;
            font-size: 1.2rem;
        }
        
        .back-button {
            padding: 12px 25px;
            font-size: 0.95rem;
        }
    }

    @media (max-width: 480px) {
        .artikel-detail-image {
            height: 200px;
        }
        
        .artikel-detail-title {
            font-size: 1.4rem;
        }
        
        .artikel-detail-content {
            font-size: 1rem;
        }
        
        .share-buttons {
            gap: 10px;
        }
        
        .share-btn {
            width: 40px;
            height: 40px;
            font-size: 1.1rem;
        }
        
        .back-button {
            width: 100%;
            justify-content: center;
        }
    }

    /* Touch device optimizations */
    @media (hover: none) and (pointer: coarse) {
        .related-card:hover,
        .share-btn:hover,
        .back-button:hover {
            transform: none;
        }
        
        .share-btn:active,
        .back-button:active {
            transform: scale(0.95);
        }
        
        .meta-item,
        .share-btn {
            padding: 12px;
            min-height: 44px;
        }
    }
</style>

<script>
    function copyLink() {
        const url = window.location.href;
        navigator.clipboard.writeText(url).then(() => {
            showNotification('Link berhasil disalin!', 'success');
        }).catch(err => {
            showNotification('Gagal menyalin link', 'error');
        });
    }
    
    function showNotification(message, type) {
        const notification = document.createElement('div');
        notification.className = `notification ${type}`;
        notification.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
            <span>${message}</span>
        `;
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${type === 'success' ? 'rgba(6, 214, 160, 0.9)' : 'rgba(239, 71, 111, 0.9)'};
            color: white;
            padding: 15px 25px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
            z-index: 1000;
            animation: slideIn 0.3s ease;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.animation = 'slideOut 0.3s ease';
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }
    
    // Add animation keyframes
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        @keyframes slideOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }
    `;
    document.head.appendChild(style);
</script>

@endsection