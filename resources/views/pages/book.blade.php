<!DOCTYPE html>
<html lang="id">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Calista - Buku Membaca</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
	<link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;700;900&display=swap" rel="stylesheet">
	<style>
		* {
			margin: 0;
			padding: 0;
			box-sizing: border-box;
		}

		body {
			font-family: 'Nunito', sans-serif;
			background: linear-gradient(135deg, #67b0ff 0%, #3a8cff 100%);
			color: white;
			min-height: 100vh;
			overflow-x: hidden;
			position: relative;
		}

		/* Header Navigation */
		.header-nav {
			position: fixed;
			top: 0;
			left: 0;
			width: 100%;
			background: rgba(255, 255, 255, 0.95);
			padding: 15px 20px;
			z-index: 1000;
			display: flex;
			justify-content: space-between;
			align-items: center;
			box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
			backdrop-filter: blur(10px);
		}

		.logo {
			font-family: 'Fredoka One', cursive;
			font-size: 1.8rem;
			color: #3a8cff;
			text-decoration: none;
		}

		.nav-links {
			display: flex;
			gap: 20px;
		}

		.nav-link {
			color: #4b5563;
			text-decoration: none;
			font-weight: 700;
			padding: 8px 16px;
			border-radius: 20px;
			transition: all 0.3s;
		}

		.nav-link:hover {
			background: #3a8cff;
			color: white;
		}

		.nav-link.active {
			background: #3a8cff;
			color: white;
		}

		/* Matahari di tengah atas */
		.small-sun {
			position: fixed;
			top: 40px; /* moved up since header removed */
			left: 50%;
			transform: translateX(-50%);
			width: 80px;
			height: 80px;
			background: radial-gradient(circle, #ffdd00 0%, #ff9500 100%);
			border-radius: 50%;
			box-shadow: 0 0 25px 15px rgba(255, 221, 0, 0.5),
						0 0 50px 30px rgba(255, 149, 0, 0.4);
			z-index: 1;
			animation: sunPulse 3s infinite alternate ease-in-out;
		}

		@keyframes sunPulse {
			0% {
				transform: translateX(-50%) scale(1);
				box-shadow: 0 0 25px 15px rgba(255, 221, 0, 0.5),
							0 0 50px 30px rgba(255, 149, 0, 0.4);
			}
			100% {
				transform: translateX(-50%) scale(1.1);
				box-shadow: 0 0 30px 20px rgba(255, 221, 0, 0.6),
							0 0 60px 35px rgba(255, 149, 0, 0.5);
			}
		}

		/* Container awan */
		.clouds-container {
			position: fixed;
			top: 0;
			left: 0;
			width: 100%;
			height: 180px;
			pointer-events: none;
			z-index: 0;
			overflow: visible;
		}

		.cloud {
			position: absolute;
			background: #ffffff;
			border-radius: 50%;
			filter: blur(0.5px);
			box-shadow: 
				0 0 20px rgba(255, 255, 255, 0.8),
				0 0 40px rgba(255, 255, 255, 0.4);
			animation: cloudFloatLeft 40s infinite linear;
		}

		/* Cloud variations */
		.cloud-1 { width: 120px; height: 50px; top: 50px; right: -120px; animation-delay: 0s; animation-duration: 50s; }
		.cloud-2 { width: 100px; height: 40px; top: 70px; right: -100px; animation-delay: 5s; animation-duration: 55s; }
		.cloud-3 { width: 90px; height: 35px; top: 40px; right: -90px; animation-delay: 10s; animation-duration: 60s; }
		.cloud-4 { width: 80px; height: 30px; top: 80px; right: -80px; animation-delay: 15s; animation-duration: 50s; }
		.cloud-5 { width: 110px; height: 45px; top: 35px; right: -110px; animation-delay: 20s; animation-duration: 65s; }
		.cloud-6 { width: 95px; height: 38px; top: 90px; right: -95px; animation-delay: 25s; animation-duration: 45s; }
		.cloud-7 { width: 85px; height: 32px; top: 60px; right: -85px; animation-delay: 30s; animation-duration: 70s; }
		.cloud-8 { width: 130px; height: 55px; top: 30px; right: -130px; animation-delay: 35s; animation-duration: 75s; }

		@keyframes cloudFloatLeft {
			0% { transform: translateX(0) translateY(0); }
			50% { transform: translateX(-50vw) translateY(5px); }
			100% { transform: translateX(calc(-100vw - 400px)) translateY(0); }
		}

		/* RUMPUT */
		.grass-container {
			position: fixed;
			bottom: 0;
			left: 0;
			width: 100%;
			height: 150px;
			z-index: 1;
			overflow: hidden;
		}

		.grass-background {
			position: absolute;
			bottom: 0;
			width: 100%;
			height: 150px;
			background: linear-gradient(to top, 
				#1b5e20 0%,
				#2e7d32 20%,
				#4caf50 40%,
				#8bc34a 60%,
				#aed581 80%,
				#dcedc8 100%
			);
			z-index: 1;
		}

		.grass-blade-container {
			position: absolute;
			bottom: 0;
			width: 100%;
			height: 150px;
			z-index: 2;
		}

		.grass-blade {
			position: absolute;
			bottom: 0;
			width: 2px;
			background: linear-gradient(to top, 
				transparent 0%,
				#1b5e20 20%,
				#4caf50 50%,
				#8bc34a 70%,
				#c5e1a5 90%
			);
			transform-origin: bottom center;
		}

		.wind-effect {
			position: absolute;
			bottom: 0;
			width: 100%;
			height: 150px;
			background: linear-gradient(90deg, 
				transparent,
				rgba(255, 255, 255, 0.1),
				rgba(255, 255, 255, 0.2),
				rgba(255, 255, 255, 0.1),
				transparent
			);
			animation: windBlow 12s infinite linear;
			z-index: 3;
			pointer-events: none;
			opacity: 0.4;
		}

		@keyframes windBlow {
			0% { transform: translateX(-100%); }
			100% { transform: translateX(100%); }
		}

		/* Main Container */
		.main-container {
			padding: 100px 20px 120px; /* reduced top padding after nav removal */
			position: relative;
			z-index: 4;
			max-width: 100vw;
		}

		/* Page Title */
		.page-title {
			text-align: center;
			margin-bottom: 40px;
		}

		.page-title h1 {
			font-family: 'Fredoka One', cursive;
			font-size: 3rem;
			margin-bottom: 15px;
			text-shadow: 3px 3px 0 rgba(0, 0, 0, 0.2);
		}

		.page-title p {
			font-size: 1.2rem;
			opacity: 0.9;
			max-width: 600px;
			margin: 0 auto;
		}

		/* Filter Navigation */
		.filter-nav {
			display: flex;
			justify-content: center;
			flex-wrap: wrap;
			gap: 10px;
			margin-bottom: 30px;
		}

		.filter-btn {
			padding: 10px 20px;
			background: rgba(255, 255, 255, 0.2);
			border: 2px solid rgba(255, 255, 255, 0.3);
			color: white;
			border-radius: 25px;
			font-weight: 700;
			cursor: pointer;
			transition: all 0.3s;
			backdrop-filter: blur(5px);
		}

		.filter-btn:hover {
			background: rgba(255, 255, 255, 0.3);
			transform: translateY(-2px);
		}

		.filter-btn.active {
			background: rgba(255, 255, 255, 0.9);
			color: #3a8cff;
			border-color: white;
		}

		/* Books Grid */
		.books-grid {
			display: grid;
			grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
			gap: 30px;
			padding: 20px;
		}

		/* Book Card */
		.book-card {
			background: linear-gradient(145deg, rgba(255, 255, 255, 0.95), rgba(255, 255, 255, 0.85));
			border-radius: 20px;
			overflow: hidden;
			cursor: pointer;
			transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
			border: 3px solid rgba(255, 255, 255, 0.3);
			box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2),
						inset 0 1px 0 rgba(255, 255, 255, 0.5);
			height: 100%;
			display: flex;
			flex-direction: column;
		}

		.book-card:hover {
			transform: translateY(-10px) scale(1.03);
			box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3),
						inset 0 1px 0 rgba(255, 255, 255, 0.5);
		}

		.book-card.disabled {
			opacity: 0.7;
			cursor: not-allowed;
		}

		.book-card.disabled:hover {
			transform: none;
		}

		/* Book Cover */
		.book-cover {
			/* increased height so cover images are larger */
			height: 320px;
			overflow: hidden;
			position: relative;
			display: flex;
			align-items: center;
			justify-content: center;
			background: #f3f4f6; /* neutral background so 'contain' images look good */
		}

		.book-cover img {
			max-width: 100%;
			max-height: 100%;
			object-fit: contain; /* show full image without cropping */
			transition: transform 0.3s ease;
			display: block;
		}

		/* avoid large zoom that would crop image — keep subtle or none */
		.book-card:hover .book-cover img {
			transform: none;
		}

		/* Status Badges */
		.status-badge {
			position: absolute;
			top: 15px;
			right: 15px;
			padding: 5px 12px;
			border-radius: 15px;
			font-size: 0.8rem;
			font-weight: bold;
			z-index: 2;
		}

		.coming-soon {
			background: #f59e0b;
			color: white;
		}

		.premium {
			background: linear-gradient(135deg, #f59e0b, #d97706);
			color: white;
		}

		.module-badge {
			position: absolute;
			top: 15px;
			left: 15px;
			padding: 5px 12px;
			background: rgba(0, 0, 0, 0.6);
			color: white;
			border-radius: 15px;
			font-size: 0.8rem;
			z-index: 2;
			backdrop-filter: blur(5px);
		}

		/* Book Info */
		.book-info {
			padding: 20px;
			flex-grow: 1;
			display: flex;
			flex-direction: column;
		}

		.book-title {
			font-size: 1.4rem;
			font-weight: 900;
			color: #1e293b;
			margin-bottom: 10px;
			line-height: 1.3;
			min-height: 55px;
		}

		.book-meta {
			display: flex;
			align-items: center;
			gap: 15px;
			margin-bottom: 15px;
			color: #6b7280;
			font-size: 0.9rem;
		}

		.book-meta i {
			margin-right: 5px;
		}

		/* Action Button */
		.book-action {
			margin-top: auto;
		}

		.book-btn {
			display: flex;
			align-items: center;
			justify-content: center;
			gap: 8px;
			width: 100%;
			padding: 12px;
			border: none;
			border-radius: 12px;
			font-weight: bold;
			font-size: 1rem;
			cursor: pointer;
			transition: all 0.3s;
		}

		.read-btn {
			background: linear-gradient(135deg, #3b82f6, #1d4ed8);
			color: white;
			box-shadow: 0 6px 0 #1d4ed8;
		}

		.read-btn:hover {
			background: linear-gradient(135deg, #60a5fa, #3b82f6);
			transform: translateY(-3px);
			box-shadow: 0 9px 0 #1d4ed8;
		}

		.disabled-btn {
			background: #9ca3af;
			color: #6b7280;
			cursor: not-allowed;
			box-shadow: 0 6px 0 #6b7280;
		}

		.disabled-btn:hover {
			transform: none;
			box-shadow: 0 6px 0 #6b7280;
		}

		/* Empty State */
		.empty-state {
			text-align: center;
			padding: 60px 20px;
			grid-column: 1 / -1;
		}

		.empty-state i {
			font-size: 5rem;
			color: rgba(255, 255, 255, 0.7);
			margin-bottom: 20px;
		}

		.empty-state h3 {
			font-size: 1.8rem;
			margin-bottom: 10px;
		}

		.empty-state p {
			font-size: 1.1rem;
			opacity: 0.9;
			margin-bottom: 20px;
		}

		/* Music control widget */


		/* Responsive Design */
		@media (max-width: 1024px) {
			.books-grid {
				grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
			}
			
			.page-title h1 {
				font-size: 2.5rem;
			}
			
			.small-sun {
				width: 70px;
				height: 70px;
				top: 35px;
			}

			/* slightly smaller on medium screens */
			.book-cover { height: 300px; }
		}

		@media (max-width: 768px) {
			.header-nav {
				flex-direction: column;
				gap: 10px;
				padding: 10px;
			}
			
			.nav-links {
				flex-wrap: wrap;
				justify-content: center;
			}
			
			.main-container {
				padding: 160px 15px 100px;
			}
			
			.page-title h1 {
				font-size: 2.2rem;
			}
			
			.books-grid {
				grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
				gap: 20px;
				padding: 10px;
			}
			
			/* increased relative to previous 200 -> now 260 */
			.book-cover {
				height: 260px;
			}
			
			.book-title {
				font-size: 1.2rem;
				min-height: 50px;
			}
			
			.small-sun {
				width: 60px;
				height: 60px;
				top: 30px;
			}
		}

		@media (max-width: 480px) {
			.page-title h1 {
				font-size: 1.8rem;
			}
			
			.books-grid {
				grid-template-columns: 1fr;
				max-width: 350px;
				margin: 0 auto;
			}
			
			.filter-nav {
				flex-direction: column;
				align-items: center;
			}
			
			.filter-btn {
				width: 200px;
			}
			
			/* phone size */
			.book-cover {
				height: 220px;
			}
			
			.small-sun {
				width: 50px;
				height: 50px;
				top: 25px;
			}
		}
	</style>
</head>
<body>
    <!-- Matahari di tengah atas -->
    <div class="small-sun"></div>

    <!-- Awan -->
    <div class="clouds-container">
        <div class="cloud cloud-1"></div>
        <div class="cloud cloud-2"></div>
        <div class="cloud cloud-3"></div>
        <div class="cloud cloud-4"></div>
        <div class="cloud cloud-5"></div>
        <div class="cloud cloud-6"></div>
        <div class="cloud cloud-7"></div>
        <div class="cloud cloud-8"></div>
    </div>

    <!-- Rumput -->
    <div class="grass-container">
        <div class="grass-background"></div>
        <div class="grass-blade-container" id="grassBlades"></div>
        <div class="wind-effect"></div>
    </div>

    <!-- Main Content -->
    <div class="main-container">
        <!-- Page Title -->
        <div class="page-title">
            <h1>📚 Perpustakaan Calista</h1>
            <p>Temukan koleksi buku membaca menarik untuk melatih kemampuan membaca anak</p>
        </div>

        <!-- Filter Navigation -->
        <div class="filter-nav">
            <button class="filter-btn active" onclick="filterBooks(this, 'all')">
                📖 Semua Buku
            </button>
        </div>

      
        <!-- Books Grid -->
        <div class="books-grid" id="booksGrid">
            @foreach($books as $book)
                @php
                    $isDisabled = !$book->is_active;
                    $moduleColor = ['#3b82f6', '#ef4444', '#22c55e', '#f59e0b', '#8b5cf6', '#ec4899'][$book->module_id % 6] ?? '#3b82f6';
                @endphp
                
                <div class="book-card {{ $isDisabled ? 'disabled' : '' }}" 
                     data-module="module-{{ $book->module_id }}"
                     onclick="{{ $isDisabled ? '' : "window.location.href='".route('buku-membaca.show', $book->slug)."'" }}">
                    
                    <!-- Book Cover -->
                    <div class="book-cover">
                        @if($book->cover_image)
                            <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}">
                        @else
                            <div style="width: 100%; height: 100%; background: linear-gradient(135deg, {{ $moduleColor }}99, {{ $moduleColor }}66); display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-book" style="font-size: 4rem; color: white; opacity: 0.8;"></i>
                            </div>
                        @endif
                        
                        <!-- Status Badges -->
                        @if(!$book->is_active)
                            <div class="status-badge coming-soon">
                                <i class="fas fa-clock"></i> Segera Hadir
                            </div>
                        @endif
                        
                        @if($book->is_premium)
                            <div class="status-badge premium">
                                <i class="fas fa-crown"></i> Premium
                            </div>
                        @endif
                        
                        <!-- Module Badge -->
                        @if($book->module)
                            <div class="module-badge">
                                {{ $book->module->name }}
                            </div>
                        @endif
                    </div>
                    
                    <!-- Book Info -->
                    <div class="book-info">
                        <h3 class="book-title">{{ $book->title }}</h3>
                        
                        <div class="book-meta">
                            @if($book->pageBooks->count() > 0)
                                <span><i class="fas fa-file-alt"></i> {{ $book->pageBooks->count() }} halaman</span>
                            @endif
                            <span><i class="fas fa-clock"></i> 5-10 menit</span>
                        </div>
                        
                        <div class="book-action">
                            @if($book->is_active)
                                <button class="book-btn read-btn">
                                    <i class="fas fa-book-open"></i> Baca Sekarang
                                </button>
                            @else
                                <button class="book-btn disabled-btn">
                                    <i class="fas fa-clock"></i> Akan Segera Hadir
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach

            @if($books->count() == 0)
                <!-- Empty State -->
                <div class="empty-state">
                    <i class="fas fa-book"></i>
                    <h3>Belum Ada Buku</h3>
                    <p>Belum ada buku yang tersedia saat ini. Buku-buku menarik akan segera hadir!</p>
                    <a href="{{ route('calista.index') }}" class="filter-btn" style="display: inline-flex; align-items: center; gap: 8px;">
                        <i class="fas fa-arrow-left"></i> Kembali ke Calista
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Music widget -->
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Generate grass blades
            generateGrassBlades();
            
            // Filter books function (updated signature: filterBooks(buttonEl, moduleId))
            window.filterBooks = function(buttonEl, moduleId) {
                // Update active button
                document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active'));
                if (buttonEl && buttonEl.classList) buttonEl.classList.add('active');
                
                // Filter books
                const books = document.querySelectorAll('.book-card');
                books.forEach(book => {
                    if (moduleId === 'all' || book.dataset.module === moduleId) {
                        book.style.display = 'block';
                    } else {
                        book.style.display = 'none';
                    }
                });
            };
        });

        function generateGrassBlades() {
            const container = document.getElementById('grassBlades');
            const bladeCount = Math.floor(window.innerWidth / 2.5);
            
            for (let i = 0; i < bladeCount; i++) {
                const blade = document.createElement('div');
                blade.className = 'grass-blade';
                
                const left = Math.random() * 100;
                const height = 40 + Math.random() * 110;
                const lean = (Math.random() - 0.5) * 45;
                const delay = Math.random() * 8;
                const duration = 1.5 + Math.random() * 4;
                
                blade.style.left = `${left}%`;
                blade.style.height = `${height}px`;
                blade.style.transform = `skew(${lean}deg)`;
                blade.style.animation = `bladeSway ${duration}s ease-in-out ${delay}s infinite alternate`;
                
                const hue = 100 + Math.random() * 40;
                const saturation = 50 + Math.random() * 30;
                const lightness = 30 + Math.random() * 30;
                blade.style.background = `linear-gradient(to top, 
                    transparent 0%,
                    hsl(${hue}, ${saturation}%, ${lightness}%) 30%,
                    hsl(${hue + 10}, ${saturation + 10}%, ${lightness + 15}%) 60%,
                    hsl(${hue + 20}, ${saturation + 5}%, ${lightness + 25}%) 90%
                )`;
                
                container.appendChild(blade);
            }
        }

        // Add CSS for blade swaying
        const style = document.createElement('style');
        style.textContent = `
            @keyframes bladeSway {
                0%, 100% { 
                    transform: skew(0deg) translateX(0); 
                }
                25% { 
                    transform: skew(10deg) translateX(-3px); 
                }
                50% { 
                    transform: skew(-7deg) translateX(2px); 
                }
                75% { 
                    transform: skew(4deg) translateX(-2px); 
                }
            }
            
            .grass-blade {
                animation-timing-function: cubic-bezier(0.42, 0, 0.58, 1);
            }
        `;
        document.head.appendChild(style);

        // Regenerate grass on window resize
        let resizeTimer;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                const container = document.getElementById('grassBlades');
                container.innerHTML = '';
                generateGrassBlades();
            }, 250);
        });


    </script>



    <!-- Time Out Modal Styles -->
    <style>
        .timeout-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            animation: fadeIn 0.3s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .timeout-modal {
            background: linear-gradient(135deg, #FF6B6B 0%, #FF8E72 100%);
            border-radius: 40px;
            padding: 50px 40px;
            max-width: 500px;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: slideUp 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(50px) scale(0.9);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .timeout-modal i {
            font-size: 80px;
            color: white;
            margin-bottom: 20px;
            display: block;
            animation: bounce 1s infinite;
        }

        @keyframes bounce {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        .timeout-modal h2 {
            font-size: 36px;
            color: white;
            margin: 20px 0;
            font-family: 'Fredoka One', cursive;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
        }

        .timeout-modal p {
            font-size: 18px;
            color: rgba(255, 255, 255, 0.9);
            margin: 10px 0 30px 0;
        }

        .timeout-button {
            background: linear-gradient(135deg, #4CAF50 0%, #45a049 100%);
            border: none;
            color: white;
            padding: 15px 40px;
            font-size: 18px;
            border-radius: 25px;
            cursor: pointer;
            font-weight: 700;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
            font-family: 'Nunito', sans-serif;
        }

        .timeout-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
        }

        .timeout-button:active {
            transform: translateY(-1px);
        }
    </style>

    <!-- Real-time Timer Check Script -->
    <script>
        (function() {
            // Ambil child_id dari active child
            let childId = null;
            let isTimeoutShown = false;

            // Function untuk mendapatkan active child terlebih dahulu
            function getActiveChild() {
                fetch(`/anak/check-active-status`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        childId = data.active_child.id;
                    }
                })
                .catch(error => console.error('Error getting active child:', error));
            }

            // Function untuk cek waktu tersisa via API
            function checkRemainingTime() {
                if (!childId || isTimeoutShown) return;

                fetch(`/anak/${childId}/remaining-time`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.remaining_seconds <= 0) {
                        showTimeoutModal();
                    }
                })
                .catch(error => console.error('Error checking time:', error));
            }

            // Function untuk menampilkan timeout modal
            function showTimeoutModal() {
                if (isTimeoutShown) return;
                isTimeoutShown = true;

                // Ciptakan overlay dan modal
                const overlay = document.createElement('div');
                overlay.className = 'timeout-overlay';
                
                const modal = document.createElement('div');
                modal.className = 'timeout-modal';
                modal.innerHTML = `
                    <i class="fas fa-hourglass-end"></i>
                    <h2>Yahhh Waktu Habis! ⏰</h2>
                    <p>Waktu bermain kamu sudah habis untuk hari ini.</p>
                    <p>Coba lagi besok, ya!</p>
                    <button class="timeout-button">Kembali ke Profil</button>
                `;

                overlay.appendChild(modal);
                document.body.appendChild(overlay);

                // Tambahkan event listener ke button
                const button = modal.querySelector('.timeout-button');
                button.addEventListener('click', function() {
                    // Redirect ke selesai
                    window.location.href = '{{ route("selesai") }}';
                });

                // Prevent scroll
                document.body.style.overflow = 'hidden';
            }

            // Mulai dengan mengambil active child
            document.addEventListener('DOMContentLoaded', function() {
                getActiveChild();
                // Setelah mendapat childId, cek waktu
                setTimeout(() => {
                    checkRemainingTime();
                    // Kemudian cek setiap 2 detik
                    setInterval(checkRemainingTime, 2000);
                }, 100);
            });
        })();
    </script>
</body>
</html>