<!doctype html>
<html lang="id">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<title>Login - Petualangan Belajar Seru!</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
	<link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;700;900&display=swap" rel="stylesheet">
	<style>
		* {
			margin: 0;
			padding: 0;
			box-sizing: border-box;
			-webkit-tap-highlight-color: transparent;
		}
		
		html {
			font-size: 16px;
			height: 100%;
		}
		
		body {
			font-family: 'Nunito', sans-serif;
			background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
			min-height: 100vh;
			display: flex;
			justify-content: center;
			align-items: center;
			padding: 15px;
			overflow-x: hidden;
			-webkit-font-smoothing: antialiased;
			-moz-osx-font-smoothing: grayscale;
		}
		
		.background-animation {
			position: fixed;
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
			pointer-events: none;
			z-index: -1;
			overflow: hidden;
		}
		
		.floating-shape {
			position: absolute;
			border-radius: 50%;
			animation: float 20s infinite linear;
			opacity: 0.2;
		}
		
		.shape-1 {
			width: 70px;
			height: 70px;
			background: #ff9e6d;
			top: 10%;
			left: 5%;
			animation-duration: 25s;
			animation-delay: 0s;
		}
		
		.shape-2 {
			width: 100px;
			height: 100px;
			background: #5c7cfa;
			bottom: 15%;
			right: 5%;
			animation-duration: 30s;
			animation-delay: 5s;
		}
		
		.shape-3 {
			width: 60px;
			height: 60px;
			background: #51cf66;
			top: 40%;
			right: 10%;
			animation-duration: 20s;
			animation-delay: 10s;
		}
		
		.shape-4 {
			width: 80px;
			height: 80px;
			background: #ffd43b;
			bottom: 20%;
			left: 10%;
			animation-duration: 35s;
			animation-delay: 15s;
		}
		
		@keyframes float {
			0% { transform: translateY(0) rotate(0deg); }
			25% { transform: translateY(-15px) rotate(90deg); }
			50% { transform: translateY(0) rotate(180deg); }
			75% { transform: translateY(15px) rotate(270deg); }
			100% { transform: translateY(0) rotate(360deg); }
		}
		
		.container {
			width: 100%;
			max-width: 500px;
			background-color: white;
			border-radius: 25px;
			box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
			overflow: hidden;
			border: 5px solid white;
			position: relative;
			animation: bounceIn 0.8s ease-out;
		}
		
		@keyframes bounceIn {
			0% { transform: scale(0.95); opacity: 0; }
			70% { transform: scale(1.02); }
			100% { transform: scale(1); opacity: 1; }
		}
		
		.container::before {
			content: "";
			position: absolute;
			top: -20px;
			left: 50%;
			transform: translateX(-50%);
			width: 120px;
			height: 50px;
			background: linear-gradient(to right, #ff9e6d, #ff8787);
			border-radius: 25px 25px 0 0;
			z-index: 1;
			box-shadow: 0 5px 15px rgba(255, 158, 109, 0.3);
		}
		
		.header {
			background: linear-gradient(135deg, #5c7cfa 0%, #4dabf7 100%);
			color: white;
			padding: 25px 20px;
			text-align: center;
			position: relative;
			overflow: hidden;
		}
		
		.header::before {
			content: "";
			position: absolute;
			top: -40px;
			left: -40px;
			width: 80px;
			height: 80px;
			background-color: rgba(255, 255, 255, 0.1);
			border-radius: 50%;
		}
		
		.header::after {
			content: "";
			position: absolute;
			bottom: -40px;
			right: -40px;
			width: 90px;
			height: 90px;
			background-color: rgba(255, 255, 255, 0.1);
			border-radius: 50%;
		}
		
		.header h1 {
			font-family: 'Fredoka One', cursive;
			font-size: 2.2rem;
			text-shadow: 2px 2px 0 rgba(59, 91, 219, 0.3);
			letter-spacing: 0.5px;
			margin-bottom: 8px;
			position: relative;
			z-index: 2;
			animation: colorChange 5s infinite alternate;
			line-height: 1.2;
		}
		
		@keyframes colorChange {
			0%, 100% { color: white; }
			50% { color: #ffd43b; }
		}
		
		.header p {
			font-size: 1.1rem;
			opacity: 0.9;
			position: relative;
			z-index: 2;
			line-height: 1.4;
		}
		
		.header-icons {
			position: absolute;
			top: 15px;
			right: 15px;
			display: flex;
			gap: 10px;
			z-index: 2;
		}
		
		.header-icon {
			font-size: 1.8rem;
			color: #ffd43b;
			animation: bounce 2s infinite;
		}
		
		@keyframes bounce {
			0%, 100% { transform: translateY(0); }
			50% { transform: translateY(-5px); }
		}
		
		.header-icon:nth-child(2) {
			animation-delay: 0.5s;
		}
		
		.content {
			padding: 25px 20px;
			position: relative;
		}
		
		.message-box {
			padding: 18px 15px;
			border-radius: 15px;
			margin-bottom: 25px;
			font-weight: bold;
			display: flex;
			align-items: flex-start;
			gap: 12px;
			animation: slideIn 0.5s ease-out;
			transform-origin: top;
		}
		
		@keyframes slideIn {
			from { transform: translateY(-10px); opacity: 0; }
			to { transform: translateY(0); opacity: 1; }
		}
		
		.error-box {
			background: linear-gradient(to right, #ffe3e3, #ffc9c9);
			color: #c92a2a;
			border-left: 6px solid #ff6b6b;
			box-shadow: 0 5px 15px rgba(255, 107, 107, 0.1);
		}
		
		.success-box {
			background: linear-gradient(to right, #d3f9d8, #b2f2bb);
			color: #2b8a3e;
			border-left: 6px solid #51cf66;
			box-shadow: 0 5px 15px rgba(81, 207, 102, 0.1);
		}
		
		.message-box i {
			flex-shrink: 0;
			margin-top: 2px;
		}
		
		.message-box ul {
			margin-left: 20px;
			margin-top: 8px;
		}
		
		.form-group {
			margin-bottom: 25px;
			position: relative;
		}
		
		.form-group label {
			display: block;
			font-weight: 900;
			color: #495057;
			margin-bottom: 10px;
			font-size: 1.1rem;
			display: flex;
			align-items: center;
			gap: 8px;
		}
		
		.input-container {
			position: relative;
			transition: transform 0.3s;
		}
		
		.input-container:hover {
			transform: translateY(-3px);
		}
		
		.input-with-icon {
			position: relative;
		}
		
		.input-icon {
			position: absolute;
			left: 18px;
			top: 50%;
			transform: translateY(-50%);
			color: #5c7cfa;
			font-size: 1.3rem;
			z-index: 2;
			transition: all 0.3s;
		}
		
		.input-with-icon input {
			width: 100%;
			padding: 18px 18px 18px 55px;
			border: 3px solid #dee2e6;
			border-radius: 18px;
			font-size: 1.1rem;
			transition: all 0.3s;
			background-color: #f8f9fa;
			font-weight: 700;
			position: relative;
			-webkit-appearance: none;
			appearance: none;
		}
		
		.input-with-icon input:focus {
			outline: none;
			border-color: #5c7cfa;
			background-color: white;
			box-shadow: 0 0 0 4px rgba(92, 124, 250, 0.2);
		}
		
		.input-with-icon input:focus + .input-icon {
			color: #ff9e6d;
			transform: translateY(-50%) scale(1.1);
		}
		
		.input-effect {
			position: absolute;
			bottom: 0;
			left: 0;
			width: 0;
			height: 3px;
			background: linear-gradient(to right, #5c7cfa, #ff9e6d);
			border-radius: 0 0 18px 18px;
			transition: width 0.5s;
		}
		
		.input-with-icon input:focus ~ .input-effect {
			width: 100%;
		}
		
		.remember-me {
			display: flex;
			align-items: center;
			margin-bottom: 30px;
			padding: 15px;
			background-color: #f1f3f5;
			border-radius: 15px;
			transition: all 0.3s;
		}
		
		.remember-me:hover {
			background-color: #e9ecef;
			transform: translateX(5px);
		}
		
		.remember-me input {
			width: 22px;
			height: 22px;
			margin-right: 12px;
			accent-color: #5c7cfa;
			cursor: pointer;
			flex-shrink: 0;
		}
		
		.remember-me label {
			font-size: 1rem;
			color: #495057;
			cursor: pointer;
			font-weight: 700;
			line-height: 1.4;
		}
		
		.submit-btn {
			background: linear-gradient(135deg, #ff9e6d 0%, #ff8787 100%);
			color: white;
			border: none;
			padding: 22px;
			width: 100%;
			border-radius: 18px;
			font-size: 1.4rem;
			font-weight: 900;
			cursor: pointer;
			transition: all 0.3s;
			box-shadow: 0 8px 0 #ff6b6b, 0 10px 20px rgba(255, 107, 107, 0.2);
			position: relative;
			overflow: hidden;
			font-family: 'Fredoka One', cursive;
			letter-spacing: 0.5px;
			-webkit-tap-highlight-color: transparent;
			touch-action: manipulation;
		}
		
		.submit-btn:hover, .submit-btn:active {
			transform: translateY(-3px);
			box-shadow: 0 11px 0 #ff6b6b, 0 15px 25px rgba(255, 107, 107, 0.3);
		}
		
		.submit-btn:active {
			transform: translateY(2px);
			box-shadow: 0 6px 0 #ff6b6b, 0 8px 15px rgba(255, 107, 107, 0.2);
			transition: transform 0.1s;
		}
		
		.submit-btn::after {
			content: "";
			position: absolute;
			top: 0;
			left: -100%;
			width: 100%;
			height: 100%;
			background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
			transition: left 0.5s;
		}
		
		.submit-btn:hover::after {
			left: 100%;
		}
		
		.submit-btn i {
			margin-right: 10px;
			animation: pulse 1.5s infinite;
		}
		
		@keyframes pulse {
			0%, 100% { transform: scale(1); }
			50% { transform: scale(1.1); }
		}
		
		.register-link {
			text-align: center;
			margin-top: 30px;
			padding-top: 25px;
			border-top: 3px dashed #dee2e6;
			position: relative;
		}
		
		.register-link::before {
			content: "✨";
			position: absolute;
			top: -12px;
			left: 50%;
			transform: translateX(-50%);
			font-size: 1.2rem;
			background: white;
			padding: 0 12px;
		}
		
		.register-link p {
			color: #495057;
			font-size: 1.1rem;
			margin-bottom: 18px;
			font-weight: 700;
			line-height: 1.4;
		}
		
		.register-btn {
			display: inline-block;
			background: linear-gradient(135deg, #51cf66 0%, #40c057 100%);
			color: white;
			text-decoration: none;
			padding: 16px 28px;
			border-radius: 15px;
			font-weight: 900;
			font-size: 1.1rem;
			transition: all 0.3s;
			box-shadow: 0 6px 0 #2b8a3e, 0 8px 15px rgba(43, 138, 62, 0.2);
			position: relative;
			overflow: hidden;
			font-family: 'Fredoka One', cursive;
			touch-action: manipulation;
		}
		
		.register-btn:hover, .register-btn:active {
			transform: translateY(-3px);
			box-shadow: 0 9px 0 #2b8a3e, 0 12px 20px rgba(43, 138, 62, 0.3);
		}
		
		.register-btn i {
			margin-right: 8px;
			animation: spin 4s infinite linear;
		}
		
		@keyframes spin {
			0% { transform: rotate(0deg); }
			100% { transform: rotate(360deg); }
		}
		
		.floating-characters {
			position: absolute;
			width: 100%;
			height: 100%;
			top: 0;
			left: 0;
			pointer-events: none;
			z-index: 1;
			overflow: hidden;
			display: none; /* Disable on mobile for better performance */
		}
		
		.character {
			position: absolute;
			font-size: 2.5rem;
			animation: floatAround 20s infinite linear;
			z-index: -1;
		}
		
		.character-1 { top: 10%; left: 5%; color: #ff9e6d; animation-delay: 0s; }
		.character-2 { top: 15%; right: 8%; color: #5c7cfa; animation-delay: 5s; }
		.character-3 { bottom: 20%; left: 7%; color: #51cf66; animation-delay: 10s; }
		.character-4 { bottom: 10%; right: 10%; color: #ffd43b; animation-delay: 15s; }
		
		@keyframes floatAround {
			0% { transform: translate(0, 0) rotate(0deg); }
			25% { transform: translate(15px, -15px) rotate(90deg); }
			50% { transform: translate(0, -30px) rotate(180deg); }
			75% { transform: translate(-15px, -15px) rotate(270deg); }
			100% { transform: translate(0, 0) rotate(360deg); }
		}
		
		.particle {
			position: absolute;
			width: 8px;
			height: 8px;
			border-radius: 50%;
			pointer-events: none;
			z-index: -1;
			animation: particleFloat 2s infinite linear;
		}
		
		@keyframes particleFloat {
			0% { transform: translateY(0) scale(1); opacity: 0; }
			10% { opacity: 1; }
			90% { opacity: 1; }
			100% { transform: translateY(-80px) scale(0); opacity: 0; }
		}
		
		/* Field error styling */
		.field-error {
			color: #c92a2a;
			margin-top: 6px;
			font-weight: 700;
			font-size: 0.9rem;
			padding-left: 5px;
		}
		
		/* Confetti */
		@keyframes confetti {
			0% { transform: translateY(0) rotate(0deg); opacity: 1; }
			100% { transform: translateY(400px) rotate(360deg); opacity: 0; }
		}
		
		.confetti {
			position: fixed;
			width: 12px;
			height: 12px;
			pointer-events: none;
			z-index: 1000;
			opacity: 0;
		}
		
		/* Mobile optimizations */
		@media (max-width: 480px) {
			body {
				padding: 10px;
				min-height: -webkit-fill-available;
			}
			
			.container {
				border-radius: 20px;
				border-width: 4px;
			}
			
			.container::before {
				top: -15px;
				width: 100px;
				height: 40px;
				border-radius: 20px 20px 0 0;
			}
			
			.header {
				padding: 20px 15px;
			}
			
			.header h1 {
				font-size: 1.9rem;
			}
			
			.header p {
				font-size: 1rem;
			}
			
			.header-icons {
				top: 10px;
				right: 10px;
			}
			
			.header-icon {
				font-size: 1.5rem;
			}
			
			.content {
				padding: 20px 15px;
			}
			
			.message-box {
				padding: 15px;
				border-radius: 12px;
				margin-bottom: 20px;
				gap: 10px;
				flex-direction: column;
				align-items: flex-start;
			}
			
			.message-box i {
				margin-bottom: 5px;
			}
			
			.form-group {
				margin-bottom: 20px;
			}
			
			.form-group label {
				font-size: 1rem;
				margin-bottom: 8px;
			}
			
			.input-icon {
				left: 15px;
				font-size: 1.2rem;
			}
			
			.input-with-icon input {
				padding: 16px 16px 16px 50px;
				font-size: 1rem;
				border-radius: 15px;
			}
			
			.remember-me {
				padding: 12px;
				margin-bottom: 25px;
				border-radius: 12px;
			}
			
			.remember-me input {
				width: 20px;
				height: 20px;
				margin-right: 10px;
			}
			
			.remember-me label {
				font-size: 0.95rem;
			}
			
			.submit-btn {
				padding: 20px;
				font-size: 1.3rem;
				border-radius: 15px;
				box-shadow: 0 6px 0 #ff6b6b, 0 8px 15px rgba(255, 107, 107, 0.2);
			}
			
			.submit-btn:hover, .submit-btn:active {
				box-shadow: 0 9px 0 #ff6b6b, 0 12px 20px rgba(255, 107, 107, 0.3);
			}
			
			.register-link {
				margin-top: 25px;
				padding-top: 20px;
			}
			
			.register-link p {
				font-size: 1rem;
				margin-bottom: 15px;
			}
			
			.register-btn {
				padding: 14px 24px;
				font-size: 1rem;
				border-radius: 12px;
				box-shadow: 0 5px 0 #2b8a3e, 0 7px 12px rgba(43, 138, 62, 0.2);
			}
			
			.floating-shape {
				display: none; /* Disable on very small screens for performance */
			}
		}
		
		@media (min-width: 481px) and (max-width: 768px) {
			body {
				padding: 15px;
			}
			
			.container {
				max-width: 90%;
			}
			
			.header h1 {
				font-size: 2rem;
			}
			
			.floating-characters {
				display: block; /* Show on tablets */
			}
		}
		
		@media (min-width: 769px) {
			.floating-characters {
				display: block; /* Show on desktop */
			}
		}
		
		/* Landscape mode optimization */
		@media (max-height: 600px) and (orientation: landscape) {
			body {
				padding: 10px;
				align-items: flex-start;
			}
			
			.container {
				margin-top: 20px;
				margin-bottom: 20px;
				max-width: 450px;
			}
			
			.header {
				padding: 15px 20px;
			}
			
			.header h1 {
				font-size: 1.8rem;
				margin-bottom: 5px;
			}
			
			.header p {
				font-size: 1rem;
			}
			
			.content {
				padding: 20px;
			}
			
			.form-group {
				margin-bottom: 15px;
			}
			
			.remember-me {
				margin-bottom: 20px;
				padding: 10px 15px;
			}
			
			.register-link {
				margin-top: 20px;
				padding-top: 15px;
			}
		}
		
		/* Touch device optimizations */
		@media (hover: none) and (pointer: coarse) {
			.input-container:hover {
				transform: none;
			}
			
			.remember-me:hover {
				transform: none;
				background-color: #f1f3f5;
			}
			
			.submit-btn:hover, .register-btn:hover {
				transform: none;
				box-shadow: 0 8px 0 #ff6b6b, 0 10px 20px rgba(255, 107, 107, 0.2);
			}
			
			.register-btn:hover {
				box-shadow: 0 6px 0 #2b8a3e, 0 8px 15px rgba(43, 138, 62, 0.2);
			}
			
			.submit-btn:active, .register-btn:active {
				transform: translateY(3px);
			}
			
			.submit-btn::after, .register-btn::after {
				display: none; /* Disable shine effect on touch devices */
			}
		}
		
		/* Reduced motion preference */
		@media (prefers-reduced-motion: reduce) {
			*,
			*::before,
			*::after {
				animation-duration: 0.01ms !important;
				animation-iteration-count: 1 !important;
				transition-duration: 0.01ms !important;
			}
			
			.floating-shape,
			.character,
			.submit-btn i,
			.register-btn i,
			.header-icon {
				animation: none !important;
			}
		}
		
		/* Dark mode support */
		@media (prefers-color-scheme: dark) {
			body {
				background: linear-gradient(135deg, #2c3e50 0%, #4a235a 100%);
			}
			
			.container {
				background-color: #1e293b;
				border-color: #334155;
			}
			
			.container::before {
				background: linear-gradient(to right, #ff9e6d, #ff8787);
			}
			
			.input-with-icon input {
				background-color: #334155;
				border-color: #475569;
				color: #f1f5f9;
			}
			
			.input-with-icon input:focus {
				background-color: #475569;
			}
			
			.form-group label {
				color: #cbd5e1;
			}
			
			.remember-me {
				background-color: #334155;
			}
			
			.remember-me:hover {
				background-color: #475569;
			}
			
			.remember-me label {
				color: #cbd5e1;
			}
			
			.register-link {
				border-top-color: #475569;
			}
			
			.register-link::before {
				background: #1e293b;
			}
			
			.register-link p {
				color: #cbd5e1;
			}
		}
	</style>
</head>
<body>
	<!-- Background Animation -->
	<div class="background-animation">
		<div class="floating-shape shape-1"></div>
		<div class="floating-shape shape-2"></div>
		<div class="floating-shape shape-3"></div>
		<div class="floating-shape shape-4"></div>
	</div>
	
	<!-- Floating Characters (hidden on mobile) -->
	<div class="floating-characters">
		<div class="character character-1">🐶</div>
		<div class="character character-2">🐱</div>
		<div class="character character-3">🐼</div>
		<div class="character character-4">🦊</div>
	</div>
	
	<div class="container">
		<div class="header">
			<div class="header-icons">
				<div class="header-icon">🚀</div>
				<div class="header-icon">🎨</div>
			</div>
			<h1>Selamat Datang!</h1>
			<p>Petualangan Belajar Menanti!</p>
		</div>
		
		<div class="content">
			@if($errors->any())
				<div class="message-box error-box">
					<i class="fas fa-exclamation-triangle fa-2x"></i>
					<div>
						<p style="font-size: 1.1rem;">Wah, ada yang perlu diperbaiki:</p>
						<ul style="margin-left: 20px; margin-top: 8px; font-size: 1rem;">
							@foreach($errors->all() as $err)
								<li>{{ $err }}</li>
							@endforeach
						</ul>
					</div>
				</div>
			@endif

			{{-- juga tampilkan flash 'error' jika ada --}}
			@if(session('error'))
				<div class="message-box error-box">
					<i class="fas fa-exclamation-triangle fa-2x"></i>
					<div style="font-size: 1.1rem;">{{ session('error') }}</div>
				</div>
			@endif

			{{-- tampilkan success/status --}}
			@if(session('status') || session('success'))
				<div class="message-box success-box">
					<i class="fas fa-check-circle fa-2x"></i>
					<div style="font-size: 1.1rem;">{{ session('status') ?? session('success') }}</div>
				</div>
			@endif

			<form method="POST" action="{{ route('login') }}" id="loginForm">
				@csrf

				<div class="form-group">
					<label for="email"><i class="fas fa-envelope"></i> Email Kamu</label>
					<div class="input-container">
						<div class="input-with-icon">
							<i class="fas fa-envelope input-icon"></i>
							<input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="nama@contoh.com" required>
							<div class="input-effect"></div>
						</div>
					</div>
					{{-- error per-field --}}
					@error('email')
						<div class="field-error">{{ $message }}</div>
					@enderror
				</div>

				<div class="form-group">
					<label for="password"><i class="fas fa-lock"></i> Kata Sandi Rahasia</label>
					<div class="input-container">
						<div class="input-with-icon">
							<i class="fas fa-lock input-icon"></i>
							<input type="password" id="password" name="password" placeholder="Masukkan kata sandi rahasia" required>
							<div class="input-effect"></div>
						</div>
					</div>
					{{-- error per-field --}}
					@error('password')
						<div class="field-error">{{ $message }}</div>
					@enderror
				</div>

				<div class="remember-me">
					<input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
					<label for="remember">Ingat saya di perangkat ini</label>
				</div>

				<button type="submit" class="submit-btn" id="submitBtn">
					<i class="fas fa-rocket"></i> Mulai Petualangan!
				</button>
			</form>

			<div class="register-link">
				<p>Baru di dunia belajar seru?</p>
				<a href="{{ route('register') }}" class="register-btn">
					<i class="fas fa-magic"></i> Buat Akun Baru
				</a>
			</div>
		</div>
	</div>
	
	<script>
		document.addEventListener('DOMContentLoaded', function() {
			// Performance optimization for mobile
			const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
			
			// Only create particles on non-mobile devices or high-end devices
			if (!isMobile || window.devicePixelRatio < 2) {
				// Create particles on mouse move (disabled on touch devices)
				document.addEventListener('mousemove', function(e) {
					if (Math.random() > 0.8) {
						createParticle(e.clientX, e.clientY);
					}
				});
				
				// Create particles on input focus
				const inputs = document.querySelectorAll('input');
				inputs.forEach(input => {
					input.addEventListener('focus', function() {
						const rect = this.getBoundingClientRect();
						for(let i = 0; i < 5; i++) {
							setTimeout(() => {
								createParticle(
									rect.left + Math.random() * rect.width,
									rect.top + Math.random() * rect.height
								);
							}, i * 100);
						}
					});
				});
			}
			
			// Form submission handling
			const loginForm = document.getElementById('loginForm');
			const submitBtn = document.getElementById('submitBtn');
			
			if (loginForm) {
				loginForm.addEventListener('submit', function(e) {
					// Visual feedback on submit
					const originalText = submitBtn.innerHTML;
					submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyiapkan Petualangan...';
					submitBtn.disabled = true;
					
					// Create confetti explosion for visual feedback
					if (!isMobile) {
						createConfettiExplosion();
					}
					
					// Re-enable button if submission takes too long (10 seconds)
					setTimeout(() => {
						submitBtn.innerHTML = originalText;
						submitBtn.disabled = false;
					}, 10000);
				});
			}
			
			// Animate form elements on load
			const formGroups = document.querySelectorAll('.form-group');
			formGroups.forEach((group, index) => {
				setTimeout(() => {
					group.style.animation = 'slideIn 0.6s ease-out forwards';
					group.style.opacity = '1';
				}, index * 150);
			});
			
			// Make floating characters interactive on non-mobile
			if (!isMobile) {
				const characters = document.querySelectorAll('.character');
				characters.forEach(char => {
					char.addEventListener('click', function() {
						this.style.transform = 'scale(1.3)';
						setTimeout(() => {
							this.style.transform = '';
						}, 300);
						
						// Create particles around character
						const rect = this.getBoundingClientRect();
						for(let i = 0; i < 8; i++) {
							setTimeout(() => {
								createParticle(
									rect.left + rect.width/2,
									rect.top + rect.height/2,
									this.style.color
								);
							}, i * 50);
						}
					});
				});
			}
			
			// Prevent zoom on input focus in iOS
			if (isMobile) {
				const inputs = document.querySelectorAll('input');
				inputs.forEach(input => {
					input.addEventListener('focus', () => {
						setTimeout(() => {
							document.body.style.transform = 'scale(1)';
						}, 100);
					});
				});
			}
			
			// Handle virtual keyboard appearance
			if (isMobile) {
				const viewport = document.querySelector("meta[name=viewport]");
				const originalContent = viewport.getAttribute("content");
				
				window.addEventListener("resize", function() {
					if (document.activeElement.tagName === "INPUT" || document.activeElement.tagName === "TEXTAREA") {
						viewport.setAttribute("content", originalContent + ", height=" + window.innerHeight);
					}
				});
				
				document.addEventListener("focusout", function() {
					setTimeout(() => {
						viewport.setAttribute("content", originalContent);
					}, 300);
				});
			}
		});
		
		// Function to create particles (optimized for mobile)
		function createParticle(x, y, color = null) {
			const particle = document.createElement('div');
			particle.className = 'particle';
			
			// Use fewer colors for better performance
			const colors = ['#ff9e6d', '#5c7cfa', '#51cf66'];
			particle.style.backgroundColor = color || colors[Math.floor(Math.random() * colors.length)];
			
			// Smaller size on mobile
			const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
			const size = isMobile ? Math.random() * 8 + 3 : Math.random() * 10 + 5;
			particle.style.width = `${size}px`;
			particle.style.height = `${size}px`;
			
			// Position
			particle.style.left = `${x}px`;
			particle.style.top = `${y}px`;
			
			// Faster animation on mobile
			const duration = isMobile ? Math.random() * 1.5 + 1 : Math.random() * 2 + 2;
			particle.style.animationDuration = `${duration}s`;
			
			document.body.appendChild(particle);
			
			// Remove particle after animation
			setTimeout(() => {
				if (particle.parentNode) {
					particle.remove();
				}
			}, duration * 1000);
		}
		
		// Function to create confetti explosion (simplified for mobile)
		function createConfettiExplosion() {
			const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
			const count = isMobile ? 20 : 40; // Fewer particles on mobile
			const colors = ['#ff9e6d', '#5c7cfa', '#51cf66', '#ffd43b'];
			
			for(let i = 0; i < count; i++) {
				const confetti = document.createElement('div');
				confetti.className = 'confetti';
				confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
				
				// Simple shapes for performance
				confetti.style.borderRadius = Math.random() > 0.5 ? '50%' : '0';
				
				// Smaller size on mobile
				const size = isMobile ? Math.random() * 10 + 8 : Math.random() * 15 + 10;
				confetti.style.width = `${size}px`;
				confetti.style.height = `${size}px`;
				
				// Random starting position
				confetti.style.left = `${Math.random() * 100}vw`;
				confetti.style.top = `-30px`;
				
				// Faster animation on mobile
				const duration = isMobile ? Math.random() * 2 + 1 : Math.random() * 3 + 2;
				const delay = Math.random() * 0.5;
				confetti.style.animation = `confetti ${duration}s ease-out ${delay}s forwards`;
				
				document.body.appendChild(confetti);
				
				// Remove confetti after animation
				setTimeout(() => {
					if (confetti.parentNode) {
						confetti.remove();
					}
				}, (duration + delay) * 1000);
			}
		}
		
		// Add initial CSS for animations
		const style = document.createElement('style');
		style.textContent = `
			.form-group {
				opacity: 0;
				transform: translateY(15px);
			}
			
			/* Improve scrolling on iOS */
			body {
				-webkit-overflow-scrolling: touch;
			}
			
			/* Prevent text size adjustment on orientation change */
			html {
				-webkit-text-size-adjust: 100%;
			}
		`;
		document.head.appendChild(style);
	</script>
</body>
</html>