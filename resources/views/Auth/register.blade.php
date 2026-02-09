<!doctype html>
<html lang="id">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<title>Register - Bergabung dengan Petualangan!</title>
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
			background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
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
			animation: float 25s infinite linear;
			opacity: 0.15;
		}
		
		.shape-1 {
			width: 80px;
			height: 80px;
			background: #ffd166;
			top: 15%;
			left: 5%;
			animation-duration: 30s;
		}
		
		.shape-2 {
			width: 120px;
			height: 120px;
			background: #06d6a0;
			bottom: 10%;
			right: 5%;
			animation-duration: 35s;
			animation-delay: 8s;
		}
		
		.shape-3 {
			width: 60px;
			height: 60px;
			background: #118ab2;
			top: 60%;
			left: 10%;
			animation-duration: 25s;
			animation-delay: 15s;
		}
		
		.shape-4 {
			width: 100px;
			height: 100px;
			background: #ef476f;
			top: 10%;
			right: 10%;
			animation-duration: 40s;
			animation-delay: 20s;
		}
		
		@keyframes float {
			0% { transform: translate(0, 0) rotate(0deg) scale(1); }
			33% { transform: translate(20px, -30px) rotate(120deg) scale(1.05); }
			66% { transform: translate(-10px, 15px) rotate(240deg) scale(0.95); }
			100% { transform: translate(0, 0) rotate(360deg) scale(1); }
		}
		
		.container {
			width: 100%;
			max-width: 550px;
			background-color: white;
			border-radius: 25px;
			box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
			overflow: hidden;
			border: 6px solid white;
			position: relative;
			animation: popIn 0.8s cubic-bezier(0.68, -0.55, 0.265, 1.55) forwards;
		}
		
		@keyframes popIn {
			0% { transform: scale(0.95); opacity: 0; }
			100% { transform: scale(1); opacity: 1; }
		}
		
		.container::before {
			content: "";
			position: absolute;
			top: -20px;
			left: 50%;
			transform: translateX(-50%);
			width: 140px;
			height: 50px;
			background: linear-gradient(to right, #ffd166, #ef476f);
			border-radius: 30px 30px 0 0;
			z-index: 1;
			box-shadow: 0 5px 15px rgba(239, 71, 111, 0.2);
		}
		
		.header {
			background: linear-gradient(135deg, #118ab2 0%, #06d6a0 100%);
			color: white;
			padding: 25px 20px;
			text-align: center;
			position: relative;
			overflow: hidden;
		}
		
		.header::before {
			content: "";
			position: absolute;
			top: -60px;
			left: -60px;
			width: 150px;
			height: 150px;
			background-color: rgba(255, 255, 255, 0.1);
			border-radius: 50%;
		}
		
		.header::after {
			content: "";
			position: absolute;
			bottom: -80px;
			right: -80px;
			width: 180px;
			height: 180px;
			background-color: rgba(255, 255, 255, 0.08);
			border-radius: 50%;
		}
		
		.header h1 {
			font-family: 'Fredoka One', cursive;
			font-size: 2.3rem;
			text-shadow: 3px 3px 0 rgba(0, 0, 0, 0.2);
			letter-spacing: 0.5px;
			margin-bottom: 10px;
			position: relative;
			z-index: 2;
			animation: rainbowText 8s infinite linear;
			line-height: 1.2;
		}
		
		@keyframes rainbowText {
			0% { color: white; }
			20% { color: #ffd166; }
			40% { color: #06d6a0; }
			60% { color: #118ab2; }
			80% { color: #ef476f; }
			100% { color: white; }
		}
		
		.header p {
			font-size: 1.2rem;
			opacity: 0.9;
			position: relative;
			z-index: 2;
			font-weight: 700;
			line-height: 1.4;
		}
		
		.header-icons {
			position: absolute;
			top: 15px;
			right: 15px;
			display: flex;
			gap: 12px;
			z-index: 2;
		}
		
		.header-icon {
			font-size: 1.8rem;
			color: #ffd166;
			animation: dance 3s infinite;
			text-shadow: 2px 2px 0 rgba(0, 0, 0, 0.2);
		}
		
		@keyframes dance {
			0%, 100% { transform: translateY(0) rotate(0deg); }
			25% { transform: translateY(-8px) rotate(-5deg); }
			50% { transform: translateY(0) rotate(0deg); }
			75% { transform: translateY(-5px) rotate(5deg); }
		}
		
		.header-icon:nth-child(2) {
			animation-delay: 0.5s;
		}
		
		.header-icon:nth-child(3) {
			animation-delay: 1s;
		}
		
		.content {
			padding: 25px 20px;
			position: relative;
		}
		
		.message-box {
			padding: 18px 20px;
			border-radius: 18px;
			margin-bottom: 25px;
			font-weight: bold;
			display: flex;
			align-items: flex-start;
			gap: 15px;
			animation: slideDown 0.6s ease-out;
			transform-origin: top;
			box-shadow: 0 8px 15px rgba(0, 0, 0, 0.05);
		}
		
		@keyframes slideDown {
			from { transform: translateY(-20px); opacity: 0; }
			to { transform: translateY(0); opacity: 1; }
		}
		
		.success-box {
			background: linear-gradient(to right, #d4edda, #c3e6cb);
			color: #155724;
			border-left: 6px solid #28a745;
		}
		
		.error-box {
			background: linear-gradient(to right, #f8d7da, #f5c6cb);
			color: #721c24;
			border-left: 6px solid #dc3545;
		}
		
		.message-box i {
			flex-shrink: 0;
			margin-top: 2px;
		}
		
		.form-row {
			display: flex;
			flex-direction: column;
			gap: 20px;
			margin-bottom: 25px;
		}
		
		.form-group {
			position: relative;
			animation: fadeInUp 0.8s ease-out forwards;
			opacity: 0;
			transform: translateY(20px);
		}
		
		@keyframes fadeInUp {
			to { transform: translateY(0); opacity: 1; }
		}
		
		.form-group label {
			display: block;
			font-weight: 900;
			color: #495057;
			margin-bottom: 10px;
			font-size: 1.1rem;
			display: flex;
			align-items: center;
			gap: 10px;
			background: linear-gradient(135deg, #118ab2, #06d6a0);
			-webkit-background-clip: text;
			-webkit-text-fill-color: transparent;
			background-clip: text;
		}
		
		.input-container {
			position: relative;
			transition: all 0.3s ease;
		}
		
		.input-container:hover, .input-container:focus-within {
			transform: translateY(-5px);
		}
		
		.input-with-icon {
			position: relative;
		}
		
		.input-icon {
			position: absolute;
			left: 18px;
			top: 50%;
			transform: translateY(-50%);
			color: #118ab2;
			font-size: 1.4rem;
			z-index: 2;
			transition: all 0.3s;
		}
		
		.input-with-icon input {
			width: 100%;
			padding: 18px 18px 18px 60px;
			border: 3px solid #dee2e6;
			border-radius: 18px;
			font-size: 1.1rem;
			transition: all 0.3s;
			background-color: #f8f9fa;
			font-weight: 700;
			position: relative;
			color: #495057;
			-webkit-appearance: none;
			appearance: none;
		}
		
		.input-with-icon input:focus {
			outline: none;
			border-color: #118ab2;
			background-color: white;
			box-shadow: 0 0 0 5px rgba(17, 138, 178, 0.1);
		}
		
		.input-with-icon input:focus + .input-icon {
			color: #ef476f;
			transform: translateY(-50%) scale(1.2);
		}
		
		.input-effect {
			position: absolute;
			bottom: 0;
			left: 0;
			width: 0;
			height: 3px;
			background: linear-gradient(to right, #118ab2, #06d6a0);
			border-radius: 0 0 18px 18px;
			transition: width 0.5s ease;
		}
		
		.input-with-icon input:focus ~ .input-effect {
			width: 100%;
		}
		
		.password-requirements {
			background-color: #f1f3f5;
			border-radius: 15px;
			padding: 18px;
			margin-bottom: 25px;
			border-left: 6px solid #06d6a0;
			animation: slideInLeft 0.8s ease-out;
		}
		
		@keyframes slideInLeft {
			from { transform: translateX(-20px); opacity: 0; }
			to { transform: translateX(0); opacity: 1; }
		}
		
		.password-requirements h3 {
			color: #118ab2;
			margin-bottom: 12px;
			font-size: 1.2rem;
			display: flex;
			align-items: center;
			gap: 8px;
		}
		
		.requirement {
			display: flex;
			align-items: center;
			gap: 10px;
			margin-bottom: 8px;
			font-size: 1rem;
			color: #495057;
			transition: all 0.3s;
			padding: 5px 0;
		}
		
		.requirement i {
			color: #adb5bd;
			font-size: 1.1rem;
			transition: all 0.3s;
			flex-shrink: 0;
		}
		
		.requirement.valid i {
			color: #06d6a0;
		}
		
		.requirement.valid {
			color: #2b8a3e;
		}
		
		.submit-btn {
			background: linear-gradient(135deg, #ffd166 0%, #ef476f 100%);
			color: white;
			border: none;
			padding: 22px;
			width: 100%;
			border-radius: 20px;
			font-size: 1.5rem;
			font-weight: 900;
			cursor: pointer;
			transition: all 0.3s ease;
			box-shadow: 0 10px 0 #e63946, 0 15px 25px rgba(239, 71, 111, 0.2);
			position: relative;
			overflow: hidden;
			font-family: 'Fredoka One', cursive;
			letter-spacing: 0.5px;
			margin-top: 15px;
			-webkit-tap-highlight-color: transparent;
			touch-action: manipulation;
		}
		
		.submit-btn:hover, .submit-btn:active {
			transform: translateY(-5px);
			box-shadow: 0 15px 0 #e63946, 0 20px 30px rgba(239, 71, 111, 0.3);
		}
		
		.submit-btn:active {
			transform: translateY(3px);
			box-shadow: 0 7px 0 #e63946, 0 10px 20px rgba(239, 71, 111, 0.2);
			transition: transform 0.1s;
		}
		
		.submit-btn::before {
			content: "";
			position: absolute;
			top: 0;
			left: -100%;
			width: 100%;
			height: 100%;
			background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
			transition: left 0.7s;
		}
		
		.submit-btn:hover::before {
			left: 100%;
		}
		
		.submit-btn i {
			margin-right: 12px;
		}
		
		.login-link {
			text-align: center;
			margin-top: 30px;
			padding-top: 25px;
			border-top: 4px dashed #dee2e6;
			position: relative;
		}
		
		.login-link::before {
			content: "🎉";
			position: absolute;
			top: -15px;
			left: 50%;
			transform: translateX(-50%);
			font-size: 1.5rem;
			background: white;
			padding: 0 15px;
		}
		
		.login-link p {
			color: #495057;
			font-size: 1.2rem;
			margin-bottom: 20px;
			font-weight: 700;
			line-height: 1.4;
		}
		
		.login-btn {
			display: inline-block;
			background: linear-gradient(135deg, #118ab2 0%, #06d6a0 100%);
			color: white;
			text-decoration: none;
			padding: 18px 30px;
			border-radius: 18px;
			font-weight: 900;
			font-size: 1.2rem;
			transition: all 0.3s ease;
			box-shadow: 0 8px 0 #0a7c8c, 0 12px 20px rgba(6, 214, 160, 0.2);
			position: relative;
			overflow: hidden;
			font-family: 'Fredoka One', cursive;
			touch-action: manipulation;
		}
		
		.login-btn:hover, .login-btn:active {
			transform: translateY(-5px);
			box-shadow: 0 13px 0 #0a7c8c, 0 18px 30px rgba(6, 214, 160, 0.3);
		}
		
		.login-btn i {
			margin-right: 10px;
		}
		
		.floating-emojis {
			position: absolute;
			width: 100%;
			height: 100%;
			top: 0;
			left: 0;
			pointer-events: none;
			z-index: 1;
			overflow: hidden;
			display: none; /* Hidden on mobile by default */
		}
		
		.emoji {
			position: absolute;
			font-size: 2.5rem;
			animation: flyAround 25s infinite linear;
			z-index: -1;
			filter: drop-shadow(2px 2px 3px rgba(0,0,0,0.1));
		}
		
		.emoji-1 { top: 15%; left: 5%; animation-delay: 0s; }
		.emoji-2 { top: 10%; right: 8%; animation-delay: 5s; }
		.emoji-3 { bottom: 15%; left: 8%; animation-delay: 10s; }
		.emoji-4 { bottom: 20%; right: 5%; animation-delay: 15s; }
		.emoji-5 { top: 40%; left: 5%; animation-delay: 20s; }
		
		@keyframes flyAround {
			0% { transform: translate(0, 0) rotate(0deg) scale(1); }
			20% { transform: translate(20px, -20px) rotate(72deg) scale(1.1); }
			40% { transform: translate(0, -40px) rotate(144deg) scale(1); }
			60% { transform: translate(-20px, -20px) rotate(216deg) scale(1.05); }
			80% { transform: translate(-10px, 0) rotate(288deg) scale(0.95); }
			100% { transform: translate(0, 0) rotate(360deg) scale(1); }
		}
		
		.star-particle {
			position: absolute;
			width: 10px;
			height: 10px;
			background-color: #ffd166;
			clip-path: polygon(50% 0%, 61% 35%, 98% 35%, 68% 57%, 79% 91%, 50% 70%, 21% 91%, 32% 57%, 2% 35%, 39% 35%);
			pointer-events: none;
			z-index: -1;
			animation: starFloat 3s infinite linear;
			opacity: 0;
		}
		
		@keyframes starFloat {
			0% { transform: translateY(0) rotate(0deg) scale(0); opacity: 0; }
			10% { opacity: 1; transform: scale(1); }
			90% { opacity: 1; }
			100% { transform: translateY(-100px) rotate(360deg) scale(0); opacity: 0; }
		}
		
		.progress-bar {
			height: 8px;
			background-color: #e9ecef;
			border-radius: 4px;
			margin: 15px 0;
			overflow: hidden;
			display: none;
		}
		
		.progress-fill {
			height: 100%;
			background: linear-gradient(to right, #ffd166, #06d6a0, #118ab2);
			width: 0%;
			transition: width 0.5s;
			border-radius: 4px;
		}
		
		/* Field error styling */
		.field-error {
			color: #dc3545;
			margin-top: 6px;
			font-weight: 700;
			font-size: 0.9rem;
			padding-left: 5px;
			display: flex;
			align-items: center;
			gap: 5px;
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
				max-width: 98%;
			}
			
			.container::before {
				top: -15px;
				width: 120px;
				height: 40px;
				border-radius: 25px 25px 0 0;
			}
			
			.header {
				padding: 20px 15px;
			}
			
			.header h1 {
				font-size: 2rem;
			}
			
			.header p {
				font-size: 1.1rem;
			}
			
			.header-icons {
				top: 10px;
				right: 10px;
				gap: 8px;
			}
			
			.header-icon {
				font-size: 1.5rem;
			}
			
			.content {
				padding: 20px 15px;
			}
			
			.message-box {
				padding: 15px;
				border-radius: 15px;
				margin-bottom: 20px;
				gap: 12px;
				flex-direction: column;
				align-items: flex-start;
			}
			
			.message-box i {
				margin-bottom: 5px;
			}
			
			.form-row {
				gap: 15px;
				margin-bottom: 20px;
			}
			
			.form-group {
				margin-bottom: 0;
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
				padding: 16px 16px 16px 55px;
				font-size: 1rem;
				border-radius: 15px;
				border-width: 3px;
			}
			
			.password-requirements {
				padding: 15px;
				border-radius: 12px;
				margin-bottom: 20px;
			}
			
			.password-requirements h3 {
				font-size: 1.1rem;
				margin-bottom: 10px;
			}
			
			.requirement {
				font-size: 0.95rem;
				margin-bottom: 6px;
			}
			
			.requirement i {
				font-size: 1rem;
			}
			
			.submit-btn {
				padding: 20px;
				font-size: 1.3rem;
				border-radius: 18px;
				box-shadow: 0 8px 0 #e63946, 0 12px 20px rgba(239, 71, 111, 0.2);
				margin-top: 10px;
			}
			
			.submit-btn:hover, .submit-btn:active {
				box-shadow: 0 12px 0 #e63946, 0 16px 25px rgba(239, 71, 111, 0.3);
			}
			
			.login-link {
				margin-top: 25px;
				padding-top: 20px;
			}
			
			.login-link::before {
				top: -12px;
				font-size: 1.3rem;
				padding: 0 12px;
			}
			
			.login-link p {
				font-size: 1.1rem;
				margin-bottom: 15px;
			}
			
			.login-btn {
				padding: 16px 24px;
				font-size: 1.1rem;
				border-radius: 15px;
				box-shadow: 0 6px 0 #0a7c8c, 0 10px 15px rgba(6, 214, 160, 0.2);
			}
			
			.login-btn:hover, .login-btn:active {
				box-shadow: 0 10px 0 #0a7c8c, 0 14px 20px rgba(6, 214, 160, 0.3);
			}
			
			.floating-shape {
				display: none; /* Disable on very small screens for performance */
			}
			
			.star-particle {
				display: none; /* Disable particles on very small screens */
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
				font-size: 2.1rem;
			}
			
			.floating-emojis {
				display: block; /* Show on tablets */
			}
			
			.form-row {
				flex-direction: column;
			}
		}
		
		@media (min-width: 769px) {
			.form-row {
				flex-direction: row;
			}
			
			.floating-emojis {
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
				margin-top: 15px;
				margin-bottom: 15px;
				max-width: 500px;
			}
			
			.header {
				padding: 15px 20px;
			}
			
			.header h1 {
				font-size: 1.9rem;
				margin-bottom: 5px;
			}
			
			.header p {
				font-size: 1rem;
			}
			
			.content {
				padding: 20px;
			}
			
			.form-row {
				gap: 15px;
				margin-bottom: 15px;
			}
			
			.form-group {
				margin-bottom: 0;
			}
			
			.password-requirements {
				padding: 15px;
				margin-bottom: 15px;
			}
			
			.login-link {
				margin-top: 20px;
				padding-top: 15px;
			}
		}
		
		/* Touch device optimizations */
		@media (hover: none) and (pointer: coarse) {
			.input-container:hover, .input-container:focus-within {
				transform: none;
			}
			
			.submit-btn:hover, .login-btn:hover {
				transform: none;
				box-shadow: 0 10px 0 #e63946, 0 15px 25px rgba(239, 71, 111, 0.2);
			}
			
			.login-btn:hover {
				box-shadow: 0 8px 0 #0a7c8c, 0 12px 20px rgba(6, 214, 160, 0.2);
			}
			
			.submit-btn:active, .login-btn:active {
				transform: translateY(3px);
			}
			
			.submit-btn::before, .login-btn::before {
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
			.emoji,
			.header-icon {
				animation: none !important;
			}
			
			.container {
				animation: none !important;
			}
			
			.form-group {
				animation: none !important;
				opacity: 1;
				transform: none;
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
				background: linear-gradient(to right, #ffd166, #ef476f);
			}
			
			.input-with-icon input {
				background-color: #334155;
				border-color: #475569;
				color: #f1f5f9;
			}
			
			.input-with-icon input:focus {
				background-color: #475569;
			}
			
			.input-with-icon input::placeholder {
				color: #94a3b8;
			}
			
			.form-group label {
				background: linear-gradient(135deg, #60a5fa, #34d399);
				-webkit-background-clip: text;
				-webkit-text-fill-color: transparent;
				background-clip: text;
			}
			
			.password-requirements {
				background-color: #334155;
			}
			
			.requirement {
				color: #cbd5e1;
			}
			
			.login-link {
				border-top-color: #475569;
			}
			
			.login-link::before {
				background: #1e293b;
			}
			
			.login-link p {
				color: #cbd5e1;
			}
			
			.progress-bar {
				background-color: #475569;
			}
		}
		
		/* Improve scrolling on iOS */
		body {
			-webkit-overflow-scrolling: touch;
		}
		
		/* Prevent text size adjustment on orientation change */
		html {
			-webkit-text-size-adjust: 100%;
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
	
	<!-- Floating Emojis (hidden on mobile) -->
	<div class="floating-emojis">
		<div class="emoji emoji-1">🚀</div>
		<div class="emoji emoji-2">🎨</div>
		<div class="emoji emoji-3">🎮</div>
		<div class="emoji emoji-4">🎪</div>
		<div class="emoji emoji-5">🧸</div>
	</div>
	
	<div class="container">
		<div class="header">
			<div class="header-icons">
				<div class="header-icon">🌟</div>
				<div class="header-icon">🌈</div>
				<div class="header-icon">🎯</div>
			</div>
			<h1>Ayo Bergabung!</h1>
			<p>Mulai Petualangan Belajarmu Bersama Kami</p>
		</div>
		
		<div class="content">
			@if(session('success'))
				<div class="message-box success-box">
					<i class="fas fa-check-circle fa-2x"></i>
					<div style="font-size: 1.2rem; font-weight: 900;">{{ session('success') }}</div>
				</div>
			@endif

			@if($errors->any())
				<div class="message-box error-box">
					<i class="fas fa-exclamation-triangle fa-2x"></i>
					<div>
						<p style="font-size: 1.1rem; margin-bottom: 8px;">Wah, ada yang perlu diperbaiki:</p>
						<ul style="margin-left: 20px; font-size: 1rem;">
							@foreach($errors->all() as $err)
								<li>{{ $err }}</li>
							@endforeach
						</ul>
					</div>
				</div>
			@endif

			<form method="POST" action="{{ route('register') }}" id="registerForm">
				@csrf

				<div class="form-group" style="animation-delay: 0.1s;">
					<label for="name"><i class="fas fa-user"></i> Nama Kamu</label>
					<div class="input-container">
						<div class="input-with-icon">
							<i class="fas fa-user input-icon"></i>
							<input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Masukkan nama lengkap" required>
							<div class="input-effect"></div>
						</div>
					</div>
					@error('name')
						<div class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
					@enderror
				</div>

				<div class="form-group" style="animation-delay: 0.2s;">
					<label for="email"><i class="fas fa-envelope"></i> Email Kamu</label>
					<div class="input-container">
						<div class="input-with-icon">
							<i class="fas fa-envelope input-icon"></i>
							<input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="nama@contoh.com" required>
							<div class="input-effect"></div>
						</div>
					</div>
					@error('email')
						<div class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
					@enderror
				</div>

				<div class="form-row">
					<div class="form-group" style="animation-delay: 0.3s;">
						<label for="password"><i class="fas fa-lock"></i> Kata Sandi</label>
						<div class="input-container">
							<div class="input-with-icon">
								<i class="fas fa-lock input-icon"></i>
								<input type="password" id="password" name="password" placeholder="Buat kata sandi rahasia" required>
								<div class="input-effect"></div>
							</div>
						</div>
						@error('password')
							<div class="field-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
						@enderror
					</div>

					<div class="form-group" style="animation-delay: 0.5s;">
						<label for="password_confirmation"><i class="fas fa-lock"></i> Ulangi Kata Sandi</label>
						<div class="input-container">
							<div class="input-with-icon">
								<i class="fas fa-lock input-icon"></i>
								<input type="password" id="password_confirmation" name="password_confirmation" placeholder="Ketik ulang kata sandi" required>
								<div class="input-effect"></div>
							</div>
						</div>
					</div>
				</div>

				<div class="password-requirements">
					<h3><i class="fas fa-clipboard-check"></i> Kata Sandi Harus:</h3>
					<div class="requirement" id="req-length">
						<i class="fas fa-circle"></i>
						<span>Minimal 8 karakter</span>
					</div>
					<div class="requirement" id="req-uppercase">
						<i class="fas fa-circle"></i>
						<span>Mengandung huruf besar</span>
					</div>
					<div class="requirement" id="req-number">
						<i class="fas fa-circle"></i>
						<span>Mengandung angka</span>
					</div>
					<div class="requirement" id="req-match">
						<i class="fas fa-circle"></i>
						<span>Kata sandi cocok</span>
					</div>
				</div>

				<div class="progress-bar" id="password-strength-bar">
					<div class="progress-fill" id="password-strength-fill"></div>
				</div>

				<button type="submit" class="submit-btn" id="submitBtn">
					<i class="fas fa-user-plus"></i> Daftar Sekarang!
				</button>
			</form>

			<div class="login-link">
				<p>Sudah punya akun?</p>
				<a href="{{ route('login') }}" class="login-btn">
					<i class="fas fa-sign-in-alt"></i> Masuk ke Akun
				</a>
			</div>
		</div>
	</div>
	
	<script>
		document.addEventListener('DOMContentLoaded', function() {
			// Performance optimization for mobile
			const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
			const isHighEndDevice = window.devicePixelRatio >= 2 && !/Android 4|Android 5/.test(navigator.userAgent);
			
			// Only create particles on non-mobile or high-end devices
			if (!isMobile || isHighEndDevice) {
				// Create star particles on mouse move (disabled on touch devices)
				document.addEventListener('mousemove', function(e) {
					if (Math.random() > 0.85) {
						createStarParticle(e.clientX, e.clientY);
					}
				});
				
				// Create particles on input focus
				const inputs = document.querySelectorAll('input');
				inputs.forEach(input => {
					input.addEventListener('focus', function() {
						const rect = this.getBoundingClientRect();
						for(let i = 0; i < 8; i++) {
							setTimeout(() => {
								createStarParticle(
									rect.left + Math.random() * rect.width,
									rect.top + Math.random() * rect.height
								);
							}, i * 100);
						}
					});
					
					// Add input effects
					input.addEventListener('input', function() {
						if (this.id === 'password' || this.id === 'password_confirmation') {
							validatePassword();
						}
					});
				});
				
				// Make emojis interactive on non-mobile
				const emojis = document.querySelectorAll('.emoji');
				emojis.forEach(emoji => {
					emoji.addEventListener('click', function() {
						this.style.transform = 'scale(1.5) rotate(360deg)';
						this.style.transition = 'transform 0.5s';
						
						// Create particles around emoji
						const rect = this.getBoundingClientRect();
						for(let i = 0; i < 10; i++) {
							setTimeout(() => {
								createStarParticle(
									rect.left + rect.width/2,
									rect.top + rect.height/2
								);
							}, i * 50);
						}
						
						setTimeout(() => {
							this.style.transform = '';
						}, 500);
					});
				});
			}
			
			// Password validation function
			function validatePassword() {
				const password = document.getElementById('password').value;
				const confirmPassword = document.getElementById('password_confirmation').value;
				
				// Check requirements
				const reqLength = document.getElementById('req-length');
				const reqUppercase = document.getElementById('req-uppercase');
				const reqNumber = document.getElementById('req-number');
				const reqMatch = document.getElementById('req-match');
				
				const progressBar = document.getElementById('password-strength-bar');
				const progressFill = document.getElementById('password-strength-fill');
				
				// Show progress bar
				progressBar.style.display = 'block';
				
				// Check each requirement
				let score = 0;
				const totalChecks = 4;
				
				// Check length
				if (password.length >= 8) {
					reqLength.classList.add('valid');
					reqLength.querySelector('i').className = 'fas fa-check-circle';
					score++;
				} else {
					reqLength.classList.remove('valid');
					reqLength.querySelector('i').className = 'fas fa-circle';
				}
				
				// Check uppercase
				if (/[A-Z]/.test(password)) {
					reqUppercase.classList.add('valid');
					reqUppercase.querySelector('i').className = 'fas fa-check-circle';
					score++;
				} else {
					reqUppercase.classList.remove('valid');
					reqUppercase.querySelector('i').className = 'fas fa-circle';
				}
				
				// Check number
				if (/[0-9]/.test(password)) {
					reqNumber.classList.add('valid');
					reqNumber.querySelector('i').className = 'fas fa-check-circle';
					score++;
				} else {
					reqNumber.classList.remove('valid');
					reqNumber.querySelector('i').className = 'fas fa-circle';
				}
				
				// Check match
				if (password && confirmPassword && password === confirmPassword) {
					reqMatch.classList.add('valid');
					reqMatch.querySelector('i').className = 'fas fa-check-circle';
					score++;
				} else {
					reqMatch.classList.remove('valid');
					reqMatch.querySelector('i').className = 'fas fa-circle';
				}
				
				// Update progress bar
				const percentage = (score / totalChecks) * 100;
				progressFill.style.width = `${percentage}%`;
				
				// Change progress bar color based on strength
				if (percentage < 50) {
					progressFill.style.background = 'linear-gradient(to right, #ff6b6b, #ff9e6d)';
				} else if (percentage < 75) {
					progressFill.style.background = 'linear-gradient(to right, #ffd166, #ff9e6d)';
				} else {
					progressFill.style.background = 'linear-gradient(to right, #06d6a0, #118ab2)';
				}
			}
			
			// Form submission handling
			const registerForm = document.getElementById('registerForm');
			const submitBtn = document.getElementById('submitBtn');
			
			if (registerForm) {
				registerForm.addEventListener('submit', function(e) {
					// Validate form before submission
					if (!validateForm()) {
						e.preventDefault();
						return;
					}
					
					// Visual feedback on submit
					const originalText = submitBtn.innerHTML;
					submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Membuat Akun...';
					submitBtn.disabled = true;
					
					// Re-enable button if submission takes too long (10 seconds)
					setTimeout(() => {
						submitBtn.innerHTML = originalText;
						submitBtn.disabled = false;
					}, 10000);
				});
			}
			
			// Form validation
			function validateForm() {
				const name = document.getElementById('name').value.trim();
				const email = document.getElementById('email').value.trim();
				const password = document.getElementById('password').value;
				const confirmPassword = document.getElementById('password_confirmation').value;
				
				if (!name) {
					showError('name', 'Nama harus diisi');
					return false;
				}
				
				if (!email) {
					showError('email', 'Email harus diisi');
					return false;
				}
				
				if (!password) {
					showError('password', 'Kata sandi harus diisi');
					return false;
				}
				
				if (password !== confirmPassword) {
					showError('password_confirmation', 'Kata sandi tidak cocok');
					return false;
				}
				
				return true;
			}
			
			function showError(fieldId, message) {
				let errorDiv = document.querySelector(`#${fieldId}`).parentElement.parentElement.nextElementSibling;
				if (!errorDiv || !errorDiv.classList.contains('field-error')) {
					errorDiv = document.createElement('div');
					errorDiv.className = 'field-error';
					errorDiv.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${message}`;
					document.querySelector(`#${fieldId}`).parentElement.parentElement.parentElement.appendChild(errorDiv);
				} else {
					errorDiv.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${message}`;
				}
				
				// Scroll to error
				document.querySelector(`#${fieldId}`).scrollIntoView({ behavior: 'smooth', block: 'center' });
				document.querySelector(`#${fieldId}`).focus();
			}
			
			// Animate form groups on load
			const formGroups = document.querySelectorAll('.form-group');
			formGroups.forEach((group, index) => {
				setTimeout(() => {
					group.style.animation = 'fadeInUp 0.8s ease-out forwards';
				}, index * 150);
			});
			
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
			
			// Initialize password validation
			setTimeout(() => {
				validatePassword();
			}, 300);
			
			// Real-time validation for password fields
			document.getElementById('password').addEventListener('input', validatePassword);
			document.getElementById('password_confirmation').addEventListener('input', validatePassword);
		});
		
		// Function to create star particles (optimized for mobile)
		function createStarParticle(x, y, color = null) {
			const star = document.createElement('div');
			star.className = 'star-particle';
			
			// Use fewer colors for better performance
			const colors = ['#ffd166', '#06d6a0', '#118ab2'];
			star.style.backgroundColor = color || colors[Math.floor(Math.random() * colors.length)];
			
			// Smaller size on mobile
			const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
			const size = isMobile ? Math.random() * 8 + 6 : Math.random() * 12 + 8;
			star.style.width = `${size}px`;
			star.style.height = `${size}px`;
			
			// Position
			star.style.left = `${x}px`;
			star.style.top = `${y}px`;
			
			// Faster animation on mobile
			const duration = isMobile ? Math.random() * 2 + 1.5 : Math.random() * 3 + 2;
			star.style.animationDuration = `${duration}s`;
			
			document.body.appendChild(star);
			
			// Remove star after animation
			setTimeout(() => {
				if (star.parentNode) {
					star.remove();
				}
			}, duration * 1000);
		}
		
		// Add initial CSS for animations
		const style = document.createElement('style');
		style.textContent = `
			.form-group {
				opacity: 0;
				transform: translateY(15px);
			}
			
			/* Improve form validation feedback */
			input:invalid {
				border-color: #dc3545 !important;
			}
			
			input:valid {
				border-color: #06d6a0 !important;
			}
		`;
		document.head.appendChild(style);
	</script>
</body>
</html>