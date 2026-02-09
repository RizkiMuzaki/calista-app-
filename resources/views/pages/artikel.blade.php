@extends('layouts.app')

@section('title', 'Artikel - Petualangan Belajar Seru')

@section('content')
<div class="artikel-container">
    <!-- Header Section -->
    <section class="artikel-header">
        <div class="header-content">
            <h1 class="header-title">
                <i class="fas fa-newspaper"></i> Artikel & Tips Belajar
            </h1>
            <p class="header-subtitle">
                Pelajari tips dan trik belajar efektif untuk anak-anak
            </p>
        </div>
    </section>

    <!-- Search & Filter Section -->
    <section class="search-filter-section">
        <div class="search-box">
            <i class="fas fa-search"></i>
            <input type="text" id="searchInput" placeholder="Cari artikel...">
        </div>
    </section>

    <!-- Articles Grid -->
    <section class="articles-section">
        @if($artikels->count() > 0)
            <div class="articles-grid">
                @foreach($artikels as $artikel)
                    <article class="artikel-card">
                        @if($artikel->image)
                            <div class="artikel-image">
                                <img src="{{ asset('storage/' . $artikel->image) }}" alt="{{ $artikel->title }}">
                                <div class="artikel-overlay">
                                    <a href="{{ route('artikel.show', $artikel->slug) }}" class="read-btn">
                                        <i class="fas fa-arrow-right"></i> Baca Selengkapnya
                                    </a>
                                </div>
                            </div>
                        @else
                            <div class="artikel-image no-image">
                                <div class="placeholder-icon">
                                    <i class="fas fa-file-alt"></i>
                                </div>
                                <div class="artikel-overlay">
                                    <a href="{{ route('artikel.show', $artikel->slug) }}" class="read-btn">
                                        <i class="fas fa-arrow-right"></i> Baca Selengkapnya
                                    </a>
                                </div>
                            </div>
                        @endif

                        <div class="artikel-content">
                            <div class="artikel-meta">
                                <span class="publish-date">
                                    <i class="far fa-calendar"></i>
                                    {{ $artikel->created_at->format('d M Y') }}
                                </span>
                                <span class="author">
                                    <i class="far fa-user"></i>
                                    {{ $artikel->user->name ?? 'Admin' }}
                                </span>
                            </div>

                            <h3 class="artikel-title">
                                <a href="{{ route('artikel.show', $artikel->slug) }}">
                                    {{ $artikel->title }}
                                </a>
                            </h3>

                            <p class="artikel-excerpt">
                                {{ Str::limit(strip_tags($artikel->content), 150) }}
                            </p>

                            <a href="{{ route('artikel.show', $artikel->slug) }}" class="read-more-link">
                                Baca Lengkap <i class="fas fa-chevron-right"></i>
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($artikels->hasPages())
                <div class="pagination-section">
                    {{ $artikels->links() }}
                </div>
            @endif
        @else
            <div class="no-artikel">
                <i class="fas fa-inbox"></i>
                <p>Belum ada artikel yang dipublikasikan</p>
            </div>
        @endif
    </section>
</div>

<style>
    .artikel-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    /* Header Section */
    .artikel-header {
        background: linear-gradient(135deg, #118ab2 0%, #06d6a0 100%);
        padding: 60px 40px;
        border-radius: 25px;
        text-align: center;
        color: white;
        margin-bottom: 50px;
    }

    .header-content {
        max-width: 600px;
        margin: 0 auto;
    }

    .header-title {
        font-family: 'Fredoka One', cursive;
        font-size: 3rem;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 15px;
    }

    .header-subtitle {
        font-size: 1.3rem;
        opacity: 0.9;
    }

    /* Search Section */
    .search-filter-section {
        margin-bottom: 50px;
        text-align: center;
    }

    .search-box {
        position: relative;
        max-width: 500px;
        margin: 0 auto;
    }

    .search-box i {
        position: absolute;
        left: 20px;
        top: 50%;
        transform: translateY(-50%);
        color: #999;
        font-size: 1.2rem;
    }

    .search-box input {
        width: 100%;
        padding: 18px 20px 18px 50px;
        border: 2px solid #e0e0e0;
        border-radius: 15px;
        font-size: 1.1rem;
        transition: all 0.3s;
        background: white;
    }

    .search-box input:focus {
        outline: none;
        border-color: #118ab2;
        box-shadow: 0 0 0 3px rgba(17, 138, 178, 0.1);
    }

    /* Articles Grid */
    .articles-section {
        margin-bottom: 50px;
    }

    .articles-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 30px;
    }

    .artikel-card {
        background: white;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        transition: all 0.3s;
        display: flex;
        flex-direction: column;
    }

    .artikel-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }

    .artikel-image {
        position: relative;
        height: 200px;
        overflow: hidden;
        background: linear-gradient(135deg, #e0e0e0, #f5f5f5);
    }

    .artikel-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s;
    }

    .artikel-card:hover .artikel-image img {
        transform: scale(1.1);
    }

    .artikel-image.no-image {
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #5c7cfa 0%, #4dabf7 100%);
    }

    .placeholder-icon {
        font-size: 4rem;
        color: white;
        opacity: 0.7;
    }

    .artikel-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.6);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s;
    }

    .artikel-card:hover .artikel-overlay {
        opacity: 1;
    }

    .read-btn {
        background: linear-gradient(135deg, #ff9e6d 0%, #ff8787 100%);
        color: white;
        padding: 12px 25px;
        border-radius: 15px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        font-weight: bold;
        transition: all 0.3s;
    }

    .read-btn:hover {
        transform: scale(1.1);
    }

    .artikel-content {
        padding: 25px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .artikel-meta {
        display: flex;
        gap: 20px;
        margin-bottom: 15px;
        font-size: 0.95rem;
        color: #999;
    }

    .publish-date, .author {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .artikel-title {
        font-size: 1.4rem;
        color: #333;
        margin-bottom: 12px;
        line-height: 1.4;
    }

    .artikel-title a {
        text-decoration: none;
        color: #333;
        transition: color 0.3s;
    }

    .artikel-title a:hover {
        color: #118ab2;
    }

    .artikel-excerpt {
        color: #666;
        line-height: 1.6;
        margin-bottom: 20px;
        flex-grow: 1;
    }

    .read-more-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #118ab2;
        text-decoration: none;
        font-weight: bold;
        transition: all 0.3s;
    }

    .read-more-link:hover {
        gap: 12px;
        color: #06d6a0;
    }

    /* Pagination */
    .pagination-section {
        display: flex;
        justify-content: center;
        margin-top: 50px;
    }

    .pagination-section .pagination {
        display: flex;
        gap: 8px;
    }

    .pagination-section .page-link {
        padding: 10px 15px;
        border: 2px solid #e0e0e0;
        border-radius: 10px;
        text-decoration: none;
        color: #333;
        transition: all 0.3s;
    }

    .pagination-section .page-link:hover {
        border-color: #118ab2;
        color: #118ab2;
    }

    .pagination-section .page-item.active .page-link {
        background: linear-gradient(135deg, #118ab2 0%, #06d6a0 100%);
        border-color: transparent;
        color: white;
    }

    /* No Artikel */
    .no-artikel {
        text-align: center;
        padding: 80px 20px;
    }

    .no-artikel i {
        font-size: 5rem;
        color: #ddd;
        margin-bottom: 20px;
    }

    .no-artikel p {
        font-size: 1.3rem;
        color: #999;
    }

    /* Responsive */
    @media (max-width: 900px) {
        .artikel-header {
            padding: 40px 30px;
        }

        .header-title {
            font-size: 2.3rem;
        }

        .articles-grid {
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }
    }

    @media (max-width: 600px) {
        .artikel-container {
            padding: 15px;
        }

        .artikel-header {
            padding: 30px 20px;
            margin-bottom: 30px;
        }

        .header-title {
            font-size: 1.8rem;
        }

        .header-subtitle {
            font-size: 1rem;
        }

        .articles-grid {
            grid-template-columns: 1fr;
        }

        .artikel-meta {
            flex-direction: column;
            gap: 8px;
        }
    }
</style>

<script>
    // Search functionality
    document.getElementById('searchInput').addEventListener('keyup', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const cards = document.querySelectorAll('.artikel-card');

        cards.forEach(card => {
            const title = card.querySelector('.artikel-title').textContent.toLowerCase();
            const excerpt = card.querySelector('.artikel-excerpt').textContent.toLowerCase();

            if (title.includes(searchTerm) || excerpt.includes(searchTerm)) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    });
</script>

@endsection
