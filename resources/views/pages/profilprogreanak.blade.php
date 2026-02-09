<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Anak</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-blue: #5c7cfa;
            --secondary-blue: #4dabf7;
            --light-blue: #e3f2fd;
            --sky-blue: #87CEEB;
            --light-sky: #a8d8ff;
            --cloud-white: #ffffff;
            --success-green: #51cf66;
            --warning-yellow: #ffd43b;
            --danger-red: #ff6b6b;
            --dark-text: #333333;
            --light-text: #666666;
            --shadow: rgba(0, 0, 0, 0.1);
            --card-radius: 20px;
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #87CEEB 0%, #a8d8ff 50%, #c2e9fb 100%);
            min-height: 100vh;
            position: relative;
            overflow-x: hidden;
            color: white;
            padding: 20px;
        }

        /* Cloud Animation Background */
        .clouds-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            overflow: hidden;
        }

        .cloud {
            position: absolute;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 50px;
            animation: floatCloud 60s infinite linear;
        }

        .cloud:before, .cloud:after {
            content: '';
            position: absolute;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 50%;
        }

        .cloud1 {
            width: 200px;
            height: 60px;
            top: 10%;
            left: 5%;
            animation-duration: 80s;
        }

        .cloud1:before {
            width: 80px;
            height: 80px;
            top: -30px;
            left: 20px;
        }

        .cloud1:after {
            width: 120px;
            height: 120px;
            top: -50px;
            left: 70px;
        }

        .cloud2 {
            width: 150px;
            height: 50px;
            top: 20%;
            right: 10%;
            animation-duration: 70s;
            animation-delay: 10s;
        }

        .cloud2:before {
            width: 60px;
            height: 60px;
            top: -25px;
            left: 15px;
        }

        .cloud2:after {
            width: 90px;
            height: 90px;
            top: -40px;
            left: 60px;
        }

        .cloud3 {
            width: 180px;
            height: 55px;
            bottom: 20%;
            left: 15%;
            animation-duration: 90s;
            animation-delay: 5s;
        }

        .cloud3:before {
            width: 70px;
            height: 70px;
            top: -30px;
            left: 20px;
        }

        .cloud3:after {
            width: 100px;
            height: 100px;
            top: -45px;
            left: 70px;
        }

        @keyframes floatCloud {
            0% {
                transform: translateX(-100px);
            }
            100% {
                transform: translateX(calc(100vw + 200px));
            }
        }

        /* Simple Header */
        .simple-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            margin-bottom: 30px;
            animation: fadeInDown 0.6s ease-out;
        }

        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .logo-container {
            display: flex;
            align-items: center;
            gap: 15px;
            text-decoration: none;
        }

        .logo-img {
            height: 60px;
            width: auto;
            border-radius: 12px;
            transition: var(--transition);
        }

        .logo-img:hover {
            transform: scale(1.05) rotate(5deg);
        }

        .article-btn {
            padding: 14px 30px;
            border-radius: 15px;
            text-decoration: none;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: var(--transition);
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            font-size: 1.1rem;
        }

        .article-btn:hover {
            transform: translateY(-3px);
            background: rgba(255, 255, 255, 0.3);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.25);
        }

        /* Main Container */
        .main-container {
            max-width: 1200px;
            margin: 0 auto;
            animation: fadeIn 0.8s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Page Title */
        .page-title {
            font-family: 'Fredoka One', cursive;
            font-size: 3rem;
            margin-bottom: 40px;
            color: white;
            text-align: center;
            text-shadow: 3px 3px 10px rgba(0, 0, 0, 0.3);
            position: relative;
            display: inline-block;
            width: 100%;
            animation: titleBounce 1s ease-out;
        }

        @keyframes titleBounce {
            0% { transform: scale(0.8); opacity: 0; }
            60% { transform: scale(1.1); }
            100% { transform: scale(1); opacity: 1; }
        }

        .page-title i {
            color: var(--warning-yellow);
            margin-right: 20px;
            animation: bounce 2s infinite;
            text-shadow: 0 3px 8px rgba(0, 0, 0, 0.3);
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-12px); }
        }

        /* Active Child Section */
        .active-child-section {
            background: linear-gradient(135deg, rgba(92, 124, 250, 0.85) 0%, rgba(77, 171, 247, 0.85) 100%);
            border-radius: 25px;
            padding: 40px;
            margin-bottom: 50px;
            color: white;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(15px);
            border: 2px solid rgba(255, 255, 255, 0.4);
            animation: slideInLeft 0.6s ease-out;
            position: relative;
            overflow: hidden;
        }

        .active-child-section::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px);
            background-size: 40px 40px;
            animation: moveBackground 25s linear infinite;
            z-index: 0;
        }

        @keyframes moveBackground {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .active-child-section > * {
            position: relative;
            z-index: 1;
        }

        @keyframes slideInLeft {
            from { opacity: 0; transform: translateX(-40px); }
            to { opacity: 1; transform: translateX(0); }
        }

        .active-child-header {
            font-family: 'Fredoka One', cursive;
            font-size: 2.5rem;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            gap: 20px;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3);
        }

        .active-child-header i {
            color: var(--warning-yellow);
            font-size: 2.2rem;
            animation: rotate 4s linear infinite;
        }

        @keyframes rotate {
            0% { transform: rotateY(0); }
            100% { transform: rotateY(360deg); }
        }

        .active-child-card {
            background: rgba(255, 255, 255, 0.15);
            border-radius: 20px;
            padding: 35px;
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: inset 0 0 30px rgba(255, 255, 255, 0.1);
        }

        .child-info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            margin-bottom: 30px;
        }

        .info-item {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 18px;
            padding: 25px;
            transition: var(--transition);
            border: 1px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
        }

        .info-item:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
        }

        .info-label {
            font-size: 1.1rem;
            opacity: 0.9;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .info-value {
            font-size: 2.2rem;
            font-weight: bold;
            color: white;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        /* Timer Styles */
        .timer-container {
            margin-top: 30px;
        }

        .live-timer {
            font-size: 3.5rem;
            font-weight: bold;
            letter-spacing: 4px;
            background: rgba(0, 0, 0, 0.3);
            padding: 25px 40px;
            border-radius: 20px;
            display: inline-block;
            margin: 20px 0;
            font-family: 'Courier New', monospace;
            box-shadow: inset 0 0 20px rgba(0, 0, 0, 0.4), 0 10px 30px rgba(0, 0, 0, 0.3);
            animation: pulse 2s infinite;
            border: 3px solid rgba(255, 255, 255, 0.3);
            text-shadow: 0 0 15px rgba(255, 255, 255, 0.7);
        }

        @keyframes pulse {
            0%, 100% { 
                box-shadow: inset 0 0 20px rgba(0, 0, 0, 0.4), 0 0 0 rgba(81, 207, 102, 0.5); 
            }
            50% { 
                box-shadow: inset 0 0 20px rgba(0, 0, 0, 0.4), 0 0 40px rgba(81, 207, 102, 0.7); 
            }
        }

        .timer-buttons {
            display: flex;
            gap: 20px;
            margin-top: 25px;
            flex-wrap: wrap;
        }

        .timer-btn {
            padding: 18px 35px;
            color: white;
            border: none;
            border-radius: 15px;
            cursor: pointer;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 15px;
            transition: var(--transition);
            font-size: 1.2rem;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.25);
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.3);
            min-width: 180px;
            justify-content: center;
        }

        .timer-btn:hover {
            transform: translateY(-6px) scale(1.05);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.4);
        }

        .btn-start {
            background: linear-gradient(135deg, rgba(81, 207, 102, 0.95) 0%, rgba(64, 192, 87, 0.95) 100%);
        }

        .btn-stop {
            background: linear-gradient(135deg, rgba(255, 107, 107, 0.95) 0%, rgba(255, 135, 135, 0.95) 100%);
        }

        .btn-reset {
            background: linear-gradient(135deg, rgba(77, 171, 247, 0.95) 0%, rgba(51, 154, 240, 0.95) 100%);
        }

        .btn-game {
            background: linear-gradient(135deg, rgba(156, 54, 181, 0.95) 0%, rgba(204, 93, 232, 0.95) 100%);
            text-decoration: none;
        }

        .timer-status {
            margin-top: 25px;
            font-size: 1.1rem;
            opacity: 0.9;
            display: flex;
            align-items: center;
            gap: 15px;
            background: rgba(255, 255, 255, 0.15);
            padding: 18px 25px;
            border-radius: 15px;
            border: 1px solid rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(10px);
        }

        .timer-progress {
            margin-top: 30px;
        }

        .progress-bar-container {
            background: rgba(255, 255, 255, 0.25);
            height: 18px;
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 15px;
            border: 2px solid rgba(255, 255, 255, 0.4);
            box-shadow: inset 0 0 10px rgba(0, 0, 0, 0.2);
        }

        .progress-bar {
            height: 100%;
            background: linear-gradient(90deg, var(--success-green) 0%, #40c057 100%);
            width: 50%;
            transition: width 1s ease;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(81, 207, 102, 0.6);
        }

        .progress-labels {
            display: flex;
            justify-content: space-between;
            font-size: 1rem;
            opacity: 0.9;
            font-weight: bold;
        }

        /* Progress Graphics Section */
        .progress-section {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.12) 0%, rgba(255, 255, 255, 0.08) 100%);
            border-radius: 25px;
            padding: 40px;
            margin-bottom: 50px;
            color: white;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.25);
            backdrop-filter: blur(20px);
            border: 2px solid rgba(255, 255, 255, 0.3);
            animation: slideInRight 0.6s ease-out 0.2s both;
        }

        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(40px); }
            to { opacity: 1; transform: translateX(0); }
        }

        .section-title {
            font-family: 'Fredoka One', cursive;
            font-size: 2.5rem;
            margin-bottom: 40px;
            color: white;
            display: flex;
            align-items: center;
            gap: 20px;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3);
        }

        .overall-progress-card {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.85) 0%, rgba(118, 75, 162, 0.85) 100%);
            border-radius: 25px;
            padding: 40px;
            color: white;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.35);
            margin-bottom: 40px;
            animation: fadeInUp 0.6s ease-out 0.4s both;
            border: 2px solid rgba(255, 255, 255, 0.3);
            position: relative;
            overflow: hidden;
        }

        .overall-progress-card::before {
            content: '';
            position: absolute;
            top: -100%;
            left: -100%;
            width: 300%;
            height: 300%;
            background: radial-gradient(circle, rgba(255,255,255,0.05) 1px, transparent 1px);
            background-size: 50px 50px;
            animation: moveBackground 30s linear infinite;
            z-index: 0;
        }

        .overall-progress-card > * {
            position: relative;
            z-index: 1;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .progress-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 30px;
        }

        .progress-percentage {
            font-size: 3.5rem;
            font-weight: bold;
            color: var(--warning-yellow);
            text-shadow: 0 5px 15px rgba(0, 0, 0, 0.4);
            animation: countUp 2s ease-out;
        }

        @keyframes countUp {
            from { transform: scale(0.3); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 30px;
            margin-top: 30px;
        }

        .stat-item {
            background: rgba(255, 255, 255, 0.15);
            border-radius: 18px;
            padding: 25px;
            transition: var(--transition);
            border: 1px solid rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(10px);
        }

        .stat-item:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: translateY(-8px) scale(1.03);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.25);
        }

        .stat-label {
            font-size: 1.1rem;
            opacity: 0.9;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .stat-value {
            font-size: 2.2rem;
            font-weight: bold;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        /* Module Progress */
        .module-progress-container {
            background: rgba(255, 255, 255, 0.12);
            border-radius: 25px;
            padding: 35px;
            margin-bottom: 40px;
            animation: fadeInUp 0.6s ease-out 0.6s both;
            border: 2px solid rgba(255, 255, 255, 0.2);
        }

        .module-item {
            background: rgba(255, 255, 255, 0.15);
            border-radius: 18px;
            padding: 30px;
            margin-bottom: 25px;
            transition: var(--transition);
            border-left: 6px solid var(--primary-blue);
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.2);
        }

        .module-item:hover {
            transform: translateY(-8px) scale(1.02);
            background: rgba(255, 255, 255, 0.25);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.25);
        }

        .module-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .module-name {
            font-weight: bold;
            font-size: 1.3rem;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.3);
        }

        .module-percentage {
            font-weight: bold;
            color: var(--warning-yellow);
            font-size: 1.8rem;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.3);
        }

        .module-progress-bar {
            background: rgba(255, 255, 255, 0.25);
            height: 15px;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 15px;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        .module-progress-fill {
            background: linear-gradient(90deg, var(--success-green) 0%, #40c057 100%);
            height: 100%;
            border-radius: 8px;
            transition: width 1s ease;
            box-shadow: 0 0 15px rgba(81, 207, 102, 0.6);
        }

        /* Recent Progress Table */
        .recent-progress-container {
            background: rgba(255, 255, 255, 0.12);
            border-radius: 25px;
            padding: 35px;
            animation: fadeInUp 0.6s ease-out 0.8s both;
            border: 2px solid rgba(255, 255, 255, 0.2);
        }

        .progress-table {
            width: 100%;
            border-collapse: collapse;
        }

        .progress-table thead {
            background: linear-gradient(135deg, rgba(92, 124, 250, 0.9) 0%, rgba(77, 171, 247, 0.9) 100%);
            color: white;
            border-radius: 15px;
            overflow: hidden;
        }

        .progress-table th {
            padding: 22px;
            text-align: left;
            font-weight: 600;
            border-bottom: 3px solid rgba(255, 255, 255, 0.4);
            font-size: 1.1rem;
        }

        .progress-table tbody tr {
            border-bottom: 1px solid rgba(255, 255, 255, 0.15);
            transition: var(--transition);
        }

        .progress-table tbody tr:hover {
            background-color: rgba(255, 255, 255, 0.2);
            transform: scale(1.01);
        }

        .progress-table td {
            padding: 22px;
            color: rgba(255, 255, 255, 0.95);
            font-size: 1.05rem;
        }

        .score-badge {
            background: rgba(227, 242, 253, 0.25);
            color: #1971c2;
            padding: 12px 20px;
            border-radius: 25px;
            font-weight: bold;
            display: inline-block;
            border: 2px solid rgba(227, 242, 253, 0.4);
            font-size: 1.1rem;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            font-weight: bold;
            padding: 12px 20px;
            border-radius: 25px;
            backdrop-filter: blur(10px);
            font-size: 1.05rem;
        }

        .status-completed {
            color: var(--success-green);
            background: rgba(81, 207, 102, 0.25);
            border: 2px solid rgba(81, 207, 102, 0.4);
        }

        .status-progress {
            color: var(--warning-yellow);
            background: rgba(255, 212, 59, 0.25);
            border: 2px solid rgba(255, 212, 59, 0.4);
        }

        /* All Children Section */
        .children-section {
            animation: fadeIn 0.8s ease-out 1s both;
        }

        .children-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 35px;
            margin-bottom: 50px;
        }

        .child-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.18) 0%, rgba(255, 255, 255, 0.1) 100%);
            border-radius: 25px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            transition: var(--transition);
            position: relative;
            border: 3px solid transparent;
            backdrop-filter: blur(15px);
        }

        .child-card.active {
            border-color: var(--success-green);
            box-shadow: 0 25px 50px rgba(81, 207, 102, 0.5);
            transform: translateY(-15px);
        }

        .child-card:hover {
            transform: translateY(-15px);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.4);
            border-color: rgba(255, 255, 255, 0.4);
        }

        .child-card-header {
            background: linear-gradient(135deg, rgba(168, 237, 234, 0.4) 0%, rgba(254, 214, 227, 0.4) 100%);
            padding: 35px;
            text-align: center;
            position: relative;
            border-bottom: 2px solid rgba(255, 255, 255, 0.3);
        }

        .child-card.active .child-card-header {
            background: linear-gradient(135deg, rgba(81, 207, 102, 0.4) 0%, rgba(64, 192, 87, 0.4) 100%);
        }

        .child-avatar {
            width: 120px;
            height: 120px;
            background: rgba(255, 255, 255, 0.4);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
            font-size: 4rem;
            color: white;
            border: 6px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
            transition: var(--transition);
        }

        .child-card:hover .child-avatar {
            transform: scale(1.1) rotate(10deg);
        }

        .child-name {
            font-family: 'Fredoka One', cursive;
            font-size: 2.2rem;
            color: white;
            margin: 0;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3);
        }

        .active-badge {
            display: inline-block;
            background: linear-gradient(135deg, var(--success-green) 0%, #40c057 100%);
            color: white;
            padding: 15px 35px;
            border-radius: 30px;
            font-size: 1.1rem;
            font-weight: bold;
            margin-top: 25px;
            box-shadow: 0 10px 25px rgba(81, 207, 102, 0.5);
            border: 3px solid rgba(255, 255, 255, 0.4);
            animation: pulse 2s infinite;
        }

        .active-checkbox {
            margin-top: 25px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            cursor: pointer;
        }

        .active-checkbox input {
            transform: scale(2);
            cursor: pointer;
            accent-color: var(--success-green);
        }

        .active-checkbox span {
            font-weight: 600;
            color: white;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.3);
            font-size: 1.1rem;
        }

        .child-card-body {
            padding: 35px;
        }

        .child-info-item {
            margin-bottom: 30px;
        }

        .child-info-label {
            font-size: 1.15rem;
            color: rgba(255, 255, 255, 0.85);
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .child-info-value {
            font-weight: bold;
            color: white;
            font-size: 1.6rem;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.3);
        }

        .child-actions {
            display: flex;
            flex-direction: column;
            gap: 20px;
            margin-top: 30px;
        }

        .child-btn {
            width: 100%;
            padding: 22px;
            border: none;
            border-radius: 18px;
            font-weight: bold;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            font-size: 1.2rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.25);
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.3);
            color: white;
            text-decoration: none;
        }

        .child-btn:hover {
            transform: translateY(-8px) scale(1.05);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.4);
        }

        .btn-activate {
            background: linear-gradient(135deg, rgba(81, 207, 102, 0.95) 0%, rgba(64, 192, 87, 0.95) 100%);
        }

        .btn-edit {
            background: linear-gradient(135deg, rgba(77, 171, 247, 0.95) 0%, rgba(51, 154, 240, 0.95) 100%);
        }

        .btn-play {
            background: linear-gradient(135deg, rgba(156, 54, 181, 0.95) 0%, rgba(204, 93, 232, 0.95) 100%);
            animation: pulse 2s infinite;
        }

        /* Add Child Button */
        .add-child-container {
            text-align: center;
            margin-top: 60px;
            animation: fadeIn 0.8s ease-out 1.2s both;
        }

        .btn-add-child {
            display: inline-flex;
            align-items: center;
            gap: 25px;
            padding: 25px 60px;
            background: linear-gradient(135deg, rgba(92, 124, 250, 0.95) 0%, rgba(77, 171, 247, 0.95) 100%);
            color: white;
            text-decoration: none;
            border-radius: 25px;
            font-weight: bold;
            font-size: 1.4rem;
            transition: var(--transition);
            box-shadow: 0 20px 40px rgba(92, 124, 250, 0.6);
            border: 3px solid rgba(255, 255, 255, 0.4);
            backdrop-filter: blur(10px);
        }

        .btn-add-child:hover {
            transform: translateY(-8px) scale(1.08);
            box-shadow: 0 25px 50px rgba(92, 124, 250, 0.8);
            background: linear-gradient(135deg, rgba(92, 124, 250, 1) 0%, rgba(77, 171, 247, 1) 100%);
        }

        /* Modal */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            z-index: 1000;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(15px);
            animation: fadeIn 0.4s ease-out;
        }

        .modal-content {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.98) 0%, rgba(255, 255, 255, 0.95) 100%);
            border-radius: 30px;
            padding: 50px;
            max-width: 550px;
            width: 90%;
            box-shadow: 0 30px 70px rgba(0, 0, 0, 0.5);
            animation: slideUp 0.5s ease-out;
            border: 3px solid var(--primary-blue);
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .modal-title {
            font-family: 'Fredoka One', cursive;
            font-size: 2.2rem;
            margin-bottom: 35px;
            color: var(--primary-blue);
            text-align: center;
        }

        .modal-actions {
            display: flex;
            gap: 20px;
            margin-top: 40px;
        }

        .modal-btn {
            flex: 1;
            padding: 20px;
            border: none;
            border-radius: 18px;
            font-weight: bold;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            font-size: 1.2rem;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        .btn-cancel {
            background: #e9ecef;
            color: var(--dark-text);
        }

        .btn-save {
            background: linear-gradient(135deg, var(--success-green) 0%, #40c057 100%);
            color: white;
        }

        /* Notification */
        .notification {
            position: fixed;
            top: 40px;
            right: 40px;
            padding: 25px 35px;
            border-radius: 20px;
            color: white;
            font-weight: bold;
            z-index: 2000;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.4);
            animation: slideInRight 0.4s ease-out;
            display: flex;
            align-items: center;
            gap: 20px;
            min-width: 400px;
            max-width: 500px;
            backdrop-filter: blur(20px);
            border: 2px solid rgba(255, 255, 255, 0.4);
            font-size: 1.1rem;
        }

        .notification-success {
            background: linear-gradient(135deg, rgba(81, 207, 102, 0.98) 0%, rgba(64, 192, 87, 0.98) 100%);
        }

        .notification-error {
            background: linear-gradient(135deg, rgba(255, 107, 107, 0.98) 0%, rgba(255, 135, 135, 0.98) 100%);
        }

        .notification-warning {
            background: linear-gradient(135deg, rgba(255, 212, 59, 0.98) 0%, rgba(250, 176, 5, 0.98) 100%);
            color: #333;
        }

        .notification-info {
            background: linear-gradient(135deg, rgba(77, 171, 247, 0.98) 0%, rgba(51, 154, 240, 0.98) 100%);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            body {
                padding: 15px;
            }
            
            .simple-header {
                flex-direction: column;
                gap: 25px;
                padding: 20px;
                margin-bottom: 25px;
            }
            
            .page-title {
                font-size: 2.2rem;
                margin-bottom: 30px;
            }
            
            .active-child-section,
            .progress-section {
                padding: 25px;
            }
            
            .child-info-grid {
                grid-template-columns: 1fr;
            }
            
            .children-grid {
                grid-template-columns: 1fr;
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .progress-table {
                display: block;
                overflow-x: auto;
            }
            
            .timer-buttons {
                flex-direction: column;
            }
            
            .timer-btn {
                width: 100%;
                min-width: auto;
            }
            
            .live-timer {
                font-size: 2.2rem;
                padding: 18px 25px;
            }
            
            .modal-content {
                padding: 35px;
                width: 95%;
            }
            
            .notification {
                left: 20px;
                right: 20px;
                max-width: none;
                min-width: auto;
                top: 20px;
            }
            
            .btn-add-child {
                padding: 20px 40px;
                font-size: 1.2rem;
            }
            
            .active-child-header,
            .section-title {
                font-size: 1.8rem;
            }
            
            .info-value,
            .stat-value {
                font-size: 1.8rem;
            }
        }

        @media (max-width: 480px) {
            .page-title {
                font-size: 1.8rem;
            }
            
            .active-child-header,
            .section-title {
                font-size: 1.6rem;
            }
            
            .live-timer {
                font-size: 1.8rem;
                padding: 15px 20px;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .timer-btn,
            .child-btn {
                padding: 18px;
                font-size: 1.1rem;
            }
            
            .progress-percentage {
                font-size: 2.5rem;
            }
            
            .info-value,
            .stat-value {
                font-size: 1.6rem;
            }
            
            .child-name {
                font-size: 1.8rem;
            }
            
            .child-avatar {
                width: 100px;
                height: 100px;
                font-size: 3.5rem;
            }
            
            .article-btn {
                padding: 12px 25px;
                font-size: 1rem;
            }
            
            .logo-img {
                height: 50px;
            }
        }

        /* Empty States */
        .empty-state {
            background: rgba(255, 255, 255, 0.18);
            border-radius: 25px;
            padding: 60px 30px;
            text-align: center;
            margin: 30px 0;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            animation: fadeIn 0.8s ease-out;
            backdrop-filter: blur(15px);
            border: 2px solid rgba(255, 255, 255, 0.3);
            color: white;
        }

        .empty-state i {
            font-size: 5rem;
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 30px;
            animation: float 4s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0); }
            25% { transform: translateY(-20px) rotate(5deg); }
            50% { transform: translateY(0) rotate(0); }
            75% { transform: translateY(10px) rotate(-5deg); }
        }

        .empty-state p {
            font-size: 1.4rem;
            margin-bottom: 35px;
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.3);
            font-weight: 500;
        }

        /* Loading Animation */
        .loading {
            display: inline-block;
            width: 25px;
            height: 25px;
            border: 4px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Form Input Styles */
        input[type="number"] {
            background: rgba(255, 255, 255, 0.25);
            border: 3px solid rgba(255, 255, 255, 0.4);
            border-radius: 15px;
            padding: 18px;
            color: white;
            font-size: 1.2rem;
            transition: var(--transition);
            backdrop-filter: blur(10px);
            width: 100%;
            max-width: 300px;
            font-weight: 600;
        }

        input[type="number"]:focus {
            outline: none;
            border-color: white;
            box-shadow: 0 0 25px rgba(255, 255, 255, 0.5);
            background: rgba(255, 255, 255, 0.3);
            transform: scale(1.02);
        }

        input[type="number"]::placeholder {
            color: rgba(255, 255, 255, 0.7);
        }
    </style>
</head>
<body>
    <!-- Animated Clouds Background -->
    <div class="clouds-container">
        <div class="cloud cloud1"></div>
        <div class="cloud cloud2"></div>
        <div class="cloud cloud3"></div>
    </div>

    <!-- Simple Header with Logo and Article Button -->
    <header class="simple-header">
        <div class="logo-container">
            <img src="{{ asset('storage/logo/logo.png') }}" alt="Logo" class="logo-img">
        </div>
        
        <a href="{{ route('artikel.index') }}" class="article-btn">
            <i class="fas fa-newspaper"></i>
            <span>Artikel</span>
        </a>
    </header>

    <!-- Main Content -->
    <div class="main-container">
        <h1 class="page-title">
            <i class="fas fa-child"></i> Profil Anak
        </h1>

        <!-- Active Child Section -->
        @if($activeChild)
        <section class="active-child-section">
            <h2 class="active-child-header">
                <i class="fas fa-star"></i> Anak Aktif Saat Ini
            </h2>
            
            <div class="active-child-card">
                <div class="child-info-grid">
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-user"></i> Nama Anak
                        </div>
                        <div class="info-value">{{ $activeChild->nama_anak }}</div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-birthday-cake"></i> Tanggal Lahir
                        </div>
                        <div class="info-value">{{ $activeChild->tanggal_lahir->format('d M Y') }}</div>
                    </div>

                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-clock"></i> Sisa Waktu Belajar
                        </div>
                        <div class="timer-container">
                            <div class="live-timer" id="live-timer">
                                {{ $activeChild->getFormattedRemainingTime() }}
                            </div>
                            <div class="timer-buttons">
                                <button class="timer-btn btn-start" id="start-timer-btn" onclick="startTimer({{ $activeChild->id }})">
                                    <i class="fas fa-play"></i> Mulai Timer
                                </button>
                                <button class="timer-btn btn-stop" id="stop-timer-btn" onclick="stopTimer({{ $activeChild->id }})">
                                    <i class="fas fa-pause"></i> Hentikan
                                </button>
                                <button class="timer-btn btn-reset" onclick="resetTimer({{ $activeChild->id }})">
                                    <i class="fas fa-redo"></i> Reset
                                </button>
                                <button onclick="startGameWithValidation({{ $activeChild->id }})" class="timer-btn btn-game">
                                    <i class="fas fa-gamepad"></i> Mulai Game
                                </button>
                            </div>
                            <div class="timer-status" id="timer-status">
                                @if($activeChild->timer_started_at)
                                    <i class="fas fa-clock"></i> Timer berjalan sejak {{ $activeChild->timer_started_at->format('H:i') }}
                                @else
                                    <i class="fas fa-clock"></i> Timer belum dimulai
                                @endif
                            </div>
                            <div class="timer-progress">
                                <div class="progress-bar-container">
                                    <div class="progress-bar" id="progress-bar" style="width: {{ ($activeChild->sisa_detik / $activeChild->limit_detik) * 100 }}%"></div>
                                </div>
                                <div class="progress-labels">
                                    <span>0:00</span>
                                    <span id="limit-display">{{ floor($activeChild->limit_detik / 3600) }}:{{ str_pad(floor(($activeChild->limit_detik % 3600) / 60), 2, '0', STR_PAD_LEFT) }}:{{ str_pad($activeChild->limit_detik % 60, 2, '0', STR_PAD_LEFT) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Limit Time Update -->
                <div style="margin-top: 35px; padding-top: 30px; border-top: 3px solid rgba(255, 255, 255, 0.4);">
                    <label style="font-size: 1.3rem; font-weight: bold; display: block; margin-bottom: 20px;">
                        <i class="fas fa-hourglass-half"></i> Update Limit Waktu Belajar
                    </label>
                    <div style="display: flex; gap: 20px; flex-wrap: wrap; align-items: center;">
                        <input type="number" id="limit-detik" value="{{ $activeChild->limit_detik }}" min="60" max="28800" step="60" 
                               placeholder="Masukkan detik">
                        <small style="opacity: 0.9; align-self: center; font-size: 1rem;">detik (1 min - 8 jam)</small>
                        <button class="timer-btn" onclick="updateLimit({{ $activeChild->id }})" 
                                style="background: linear-gradient(135deg, var(--warning-yellow) 0%, #fab005 100%); color: #333; min-width: 150px;">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                    </div>
                    <small style="display: block; margin-top: 20px; opacity: 0.9; font-size: 1.1rem;">
                        Limit saat ini: {{ floor($activeChild->limit_detik / 3600) }} jam {{ floor(($activeChild->limit_detik % 3600) / 60) }} menit
                    </small>
                    <div style="margin-top: 20px; display: flex; gap: 15px; flex-wrap: wrap;">
                        <button onclick="setQuickLimit(3600)" class="timer-btn" style="padding: 12px 20px; background: rgba(255, 255, 255, 0.2); font-size: 1rem; min-width: 100px;">1 jam</button>
                        <button onclick="setQuickLimit(7200)" class="timer-btn" style="padding: 12px 20px; background: rgba(255, 255, 255, 0.2); font-size: 1rem; min-width: 100px;">2 jam</button>
                        <button onclick="setQuickLimit(10800)" class="timer-btn" style="padding: 12px 20px; background: rgba(255, 255, 255, 0.2); font-size: 1rem; min-width: 100px;">3 jam</button>
                        <button onclick="setQuickLimit(18000)" class="timer-btn" style="padding: 12px 20px; background: rgba(255, 255, 255, 0.2); font-size: 1rem; min-width: 100px;">5 jam</button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Progress Graphics Section -->
        <section class="progress-section">
            <h2 class="section-title">
                <i class="fas fa-chart-pie"></i> Progres Belajar
            </h2>

            <!-- Overall Progress Card -->
            @if($progressByType)
            <div class="overall-progress-card">
                <div class="progress-header">
                    <h3 style="font-size: 1.5rem; font-weight: bold; margin: 0;">
                        <i class="fas fa-chart-bar"></i> Progres Keseluruhan
                    </h3>
                    <span class="progress-percentage">{{ $progressByType['reading']['percentage'] }}%</span>
                </div>

                <!-- Progress Bar -->
                <div class="progress-bar-container" style="margin-bottom: 30px;">
                    <div class="progress-bar" style="width: {{ $progressByType['reading']['percentage'] }}%"></div>
                </div>

                <!-- Stats Grid -->
                <div class="stats-grid">
                    <div class="stat-item">
                        <div class="stat-label">
                            <i class="fas fa-layer-group"></i> Total Level
                        </div>
                        <div class="stat-value">{{ $progressByType['reading']['total'] }}</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-label">
                            <i class="fas fa-check-circle"></i> Selesai
                        </div>
                        <div class="stat-value">{{ $progressByType['reading']['completed'] }}</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-label">
                            <i class="fas fa-star"></i> Nilai Rata-rata
                        </div>
                        <div class="stat-value">{{ $progressByType['reading']['avgScore'] }}</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-label">
                            <i class="fas fa-flag"></i> Status
                        </div>
                        <div class="stat-value">
                            @if($progressByType['reading']['percentage'] == 100)
                                <span style="color: var(--warning-yellow); font-size: 1.1rem;">Sempurna! ⭐</span>
                            @elseif($progressByType['reading']['percentage'] >= 75)
                                <span style="color: var(--success-green); font-size: 1.1rem;">Sangat Baik 🌟</span>
                            @elseif($progressByType['reading']['percentage'] >= 50)
                                <span style="color: var(--secondary-blue); font-size: 1.1rem;">Baik 👍</span>
                            @else
                                <span style="color: #ffa8a8; font-size: 1.1rem;">Proses 📚</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Module Progress Chart -->
            @if($progressByModule && count($progressByModule) > 0)
            <div class="module-progress-container">
                <h3 style="font-size: 1.4rem; font-weight: bold; color: white; margin-bottom: 25px;">
                    <i class="fas fa-tasks"></i> Progres Per Modul
                </h3>

                <div style="display: flex; flex-direction: column; gap: 20px;">
                    @foreach($progressByModule as $item)
                    <div class="module-item">
                        <div class="module-header">
                            <h4 class="module-name">{{ $item['module'] }}</h4>
                            <span class="module-percentage">{{ $item['percentage'] }}%</span>
                        </div>
                        
                        <div class="module-progress-bar">
                            <div class="module-progress-fill" style="width: {{ $item['percentage'] }}%"></div>
                        </div>
                        
                        <div style="display: flex; justify-content: space-between; margin-top: 15px; font-size: 1rem; color: rgba(255, 255, 255, 0.85);">
                            <span>{{ $item['completed'] }} dari {{ $item['total'] }} level selesai</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Recent Progress -->
            @if($recentProgress && count($recentProgress) > 0)
            <div class="recent-progress-container">
                <h3 style="font-size: 1.4rem; font-weight: bold; color: white; margin-bottom: 25px;">
                    <i class="fas fa-history"></i> Aktivitas Terbaru
                </h3>

                <div style="max-height: 400px; overflow-y: auto;">
                    <table class="progress-table">
                        <thead>
                            <tr>
                                <th>Level</th>
                                <th style="text-align: center;">Nilai</th>
                                <th style="text-align: center;">Bintang</th>
                                <th style="text-align: center;">Status</th>
                                <th style="text-align: right;">Terakhir Update</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentProgress as $progress)
                            <tr>
                                <td>
                                    @if($progress->level)
                                        {{ $progress->level->title ?? 'Level ' . $progress->level->id }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td style="text-align: center;">
                                    <span class="score-badge">{{ $progress->score ?? 0 }}</span>
                                </td>
                                <td style="text-align: center; font-size: 1.3rem;">
                                    @for($i = 1; $i <= 3; $i++)
                                        @if($i <= $progress->bintang)
                                            ⭐
                                        @else
                                            ☆
                                        @endif
                                    @endfor
                                </td>
                                <td style="text-align: center;">
                                    @if($progress->selesai)
                                        <span class="status-badge status-completed">
                                            <i class="fas fa-check-circle"></i> Selesai
                                        </span>
                                    @else
                                        <span class="status-badge status-progress">
                                            <i class="fas fa-hourglass-half"></i> Proses
                                        </span>
                                    @endif
                                </td>
                                <td style="text-align: right; color: rgba(255, 255, 255, 0.8); font-size: 1rem;">
                                    {{ $progress->updated_at->diffForHumans() }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @else
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <p>Belum ada data progress</p>
            </div>
            @endif
        </section>
        @else
        <div class="empty-state" style="margin-bottom: 40px;">
            <i class="fas fa-info-circle"></i>
            <p>Belum ada anak yang diaktifkan. Silakan pilih anak di bawah.</p>
        </div>
        @endif

        <!-- All Children -->
        <section class="children-section">
            <h2 class="section-title">
                <i class="fas fa-users"></i> Daftar Anak
            </h2>

            @if($anaks->count() > 0)
                <div class="children-grid">
                    @foreach($anaks as $anak)
                    <div class="child-card @if($anak->is_active) active @endif">
                        <!-- Card Header -->
                        <div class="child-card-header">
                            <div class="child-avatar">
                                <i class="fas fa-user-circle"></i>
                            </div>
                            <h3 class="child-name">{{ $anak->nama_anak }}</h3>
                            
                            <!-- Checkbox untuk aktif -->
                            <div class="active-checkbox">
                                <input type="checkbox" class="active-child-checkbox" 
                                    data-anak-id="{{ $anak->id }}" 
                                    @if($anak->is_active) checked @endif>
                                <span>Aktifkan Anak Ini</span>
                            </div>
                            
                            @if($anak->is_active)
                            <span class="active-badge">
                                <i class="fas fa-check-circle"></i> AKTIF
                            </span>
                            @endif
                        </div>

                        <!-- Card Body -->
                        <div class="child-card-body">
                            <div class="child-info-item">
                                <div class="child-info-label">
                                    <i class="fas fa-birthday-cake"></i> Tanggal Lahir
                                </div>
                                <div class="child-info-value">{{ $anak->tanggal_lahir->format('d M Y') }}</div>
                            </div>

                            <div class="child-info-item">
                                <div class="child-info-label">
                                    <i class="fas fa-clock"></i> Waktu Tersisa
                                </div>
                                <div class="child-info-value child-timer-{{ $anak->id }}">
                                    {{ $anak->getFormattedRemainingTime() }}
                                </div>
                            </div>

                            <div class="child-info-item">
                                <div class="child-info-label">
                                    <i class="fas fa-hourglass-half"></i> Limit Waktu
                                </div>
                                <div class="child-info-value">
                                    {{ floor($anak->limit_detik / 3600) }} jam {{ floor(($anak->limit_detik % 3600) / 60) }} menit
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="child-actions">
                                @if(!$anak->is_active)
                                <button onclick="setActiveChild({{ $anak->id }})" class="child-btn btn-activate">
                                    <i class="fas fa-play-circle"></i> Aktifkan
                                </button>
                                @else
                                <button onclick="startGameWithValidation({{ $anak->id }})" class="child-btn btn-play">
                                    <i class="fas fa-gamepad"></i> Mulai Game
                                </button>
                                @endif

                                <button onclick="openEditModal({{ $anak->id }}, '{{ $anak->nama_anak }}', {{ $anak->limit_detik }})" 
                                        class="child-btn btn-edit">
                                    <i class="fas fa-edit"></i> Edit Limit
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <p>Belum ada data anak</p>
                <a href="{{ route('anak.create') }}" class="btn-add-child" style="margin-top: 20px;">
                    <i class="fas fa-plus"></i> Tambah Anak
                </a>
            </div>
            @endif

            <!-- Add New Child Button -->
            <div class="add-child-container">
                <a href="{{ route('anak.create') }}" class="btn-add-child">
                    <i class="fas fa-user-plus"></i> Tambah Anak Baru
                </a>
            </div>
        </section>
    </div>

    <!-- Edit Limit Modal -->
    <div class="modal-overlay" id="editModal">
        <div class="modal-content">
            <h2 class="modal-title">Edit Limit Waktu</h2>
            
            <div style="margin-bottom: 25px;">
                <label style="display: block; font-weight: bold; margin-bottom: 12px; color: var(--dark-text);">
                    Limit Waktu Belajar (detik)
                </label>
                <input type="number" id="editLimitInput" min="60" max="28800" step="60" 
                       style="width: 100%; padding: 15px; border: 2px solid #ddd; border-radius: 12px; font-size: 1.1rem; box-sizing: border-box; transition: var(--transition);"
                       onfocus="this.style.borderColor='var(--primary-blue)'"
                       onblur="this.style.borderColor='#ddd'">
                <small style="display: block; margin-top: 10px; color: var(--light-text);">
                    Masukkan waktu dalam satuan detik. Contoh: 3600 = 1 jam
                </small>
                <div style="margin-top: 15px; display: flex; gap: 10px; flex-wrap: wrap;">
                    <button onclick="setEditQuickLimit(1800)" class="timer-btn" style="padding: 8px 15px; background: var(--light-blue); color: var(--primary-blue); font-size: 0.9rem;">30 menit</button>
                    <button onclick="setEditQuickLimit(3600)" class="timer-btn" style="padding: 8px 15px; background: var(--light-blue); color: var(--primary-blue); font-size: 0.9rem;">1 jam</button>
                    <button onclick="setEditQuickLimit(7200)" class="timer-btn" style="padding: 8px 15px; background: var(--light-blue); color: var(--primary-blue); font-size: 0.9rem;">2 jam</button>
                    <button onclick="setEditQuickLimit(10800)" class="timer-btn" style="padding: 8px 15px; background: var(--light-blue); color: var(--primary-blue); font-size: 0.9rem;">3 jam</button>
                </div>
            </div>

            <div class="modal-actions">
                <button onclick="closeEditModal()" class="modal-btn btn-cancel">
                    <i class="fas fa-times"></i> Batal
                </button>
                <button onclick="saveEditLimit()" class="modal-btn btn-save">
                    <i class="fas fa-save"></i> Simpan
                </button>
            </div>
        </div>
    </div>

    <script>
    let editingAnakId = null;
    let timerInterval = null;
    let activeTimer = {{ $activeChild && $activeChild->timer_started_at ? 'true' : 'false' }};
    let localRemainingSeconds = 0;
    let lastSyncTime = 0;
    let limitDetik = 0;
    let serviceWorkerRegistration = null;
    let timerStartTimestamp = 0;
    let timerExpiredNotified = false; // Flag untuk mencegah notifikasi duplikat

    // Register Service Worker
    function registerServiceWorker() {
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/service-worker.js')
                .then(registration => {
                    serviceWorkerRegistration = registration;
                    console.log('Service Worker registered successfully');
                    
                    // Listen untuk messages dari Service Worker
                    navigator.serviceWorker.addEventListener('message', handleServiceWorkerMessage);
                })
                .catch(err => console.error('Service Worker registration failed:', err));
        }
    }

    // Handle messages dari Service Worker
    function handleServiceWorkerMessage(event) {
        const { type, payload } = event.data;
        
        if (type === 'TIMER_UPDATE') {
            // Update display dari background timer
            localRemainingSeconds = payload.remainingSeconds;
            document.getElementById('live-timer').textContent = formatTime(localRemainingSeconds);
            
            // Update progress bar
            if (limitDetik > 0) {
                const progressPercent = (localRemainingSeconds / limitDetik) * 100;
                document.getElementById('progress-bar').style.width = `${progressPercent}%`;
            }
        }
        
        if (type === 'TIMER_EXPIRED') {
            // Timer habis - hanya tampilkan notifikasi sekali
            if (!timerExpiredNotified) {
                activeTimer = false;
                timerExpiredNotified = true;
                showNotification('Waktu belajar telah habis!', 'warning');
            }
            updateLiveTimer();
        }
    }

    // Request notification permission
    function requestNotificationPermission() {
        if ('Notification' in window && Notification.permission === 'default') {
            Notification.requestPermission();
        }
    }

    // Format waktu dari detik
    function formatTime(seconds) {
        const hours = Math.floor(seconds / 3600);
        const minutes = Math.floor((seconds % 3600) / 60);
        const secs = seconds % 60;
        
        if (hours > 0) {
            return `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
        }
        return `${minutes.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
    }

    // Hitung countdown lokal (agar smooth 1 detik berkurang 1 detik)
    function localCountdown() {
        if (activeTimer && localRemainingSeconds > 0) {
            localRemainingSeconds -= 1;
            
            // Update tampilan
            document.getElementById('live-timer').textContent = formatTime(Math.max(0, localRemainingSeconds));
            
            // Update progress bar
            const progressPercent = (Math.max(0, localRemainingSeconds) / limitDetik) * 100;
            document.getElementById('progress-bar').style.width = `${progressPercent}%`;
            
            // Jika waktu habis
            if (localRemainingSeconds <= 0) {
                // Hanya tampilkan notifikasi sekali
                if (!timerExpiredNotified) {
                    timerExpiredNotified = true;
                    showNotification('Waktu belajar telah habis!', 'warning');
                }
                stopTimer({{ $activeChild ? $activeChild->id : 'null' }});
            }
        }
    }

    // Update timer live dengan sync ke server setiap 10 detik (untuk memastikan data akurat)
    function updateLiveTimer() {
        const activeChildId = {{ $activeChild ? $activeChild->id : 'null' }};
        if (!activeChildId) return;
        
        fetch(`/anak/${activeChildId}/remaining-time`)
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    // Hitung remaining time berdasarkan server timestamp
                    const now = Date.now();
                    const serverTime = data.server_time ? data.server_time * 1000 : now;
                    const timerStartedTime = data.timer_started_at ? new Date(data.timer_started_at).getTime() : 0;
                    const limitMs = data.limit_detik * 1000;
                    
                    if (data.is_running && timerStartedTime > 0) {
                        const elapsedMs = serverTime - timerStartedTime;
                        const remainingMs = Math.max(0, limitMs - elapsedMs);
                        localRemainingSeconds = Math.floor(remainingMs / 1000);
                    } else {
                        localRemainingSeconds = data.remaining_seconds;
                    }
                    
                    limitDetik = data.limit_detik;
                    timerStartTimestamp = now;
                    
                    // Update display
                    if (document.getElementById('live-timer')) {
                        document.getElementById('live-timer').textContent = formatTime(localRemainingSeconds);
                    }
                    
                    // Update progress bar
                    const progressPercent = (localRemainingSeconds / limitDetik) * 100;
                    if (document.getElementById('progress-bar')) {
                        document.getElementById('progress-bar').style.width = `${progressPercent}%`;
                    }
                    
                    // Update status
                    if (document.getElementById('timer-status')) {
                        document.getElementById('timer-status').innerHTML = data.is_running ? 
                            `<i class="fas fa-play" style="color: var(--success-green);"></i> <span style="color: white;">Timer sedang berjalan</span>` : 
                            `<i class="fas fa-pause" style="color: var(--warning-yellow);"></i> <span style="color: white;">Timer terhenti</span>`;
                    }
                    
                    // Update limit display
                    if (document.getElementById('limit-display')) {
                        document.getElementById('limit-display').textContent = formatTime(data.limit_detik);
                    }
                    
                    // Update active timer status
                    activeTimer = data.is_running;
                    
                    // Jika timer berjalan, kirim ke Service Worker untuk background tracking
                    if (data.is_running && 'serviceWorker' in navigator && navigator.serviceWorker.controller) {
                        navigator.serviceWorker.controller.postMessage({
                            type: 'TIMER_START',
                            payload: {
                                anakId: activeChildId,
                                startTime: timerStartedTime,
                                limitDetik: data.limit_detik,
                                serverTime: serverTime
                            }
                        });
                    }
                }
            })
            .catch(err => console.error('Error updating timer:', err));
    }

    // Update semua timer anak
    function updateAllChildTimers() {
        document.querySelectorAll('[class^="child-timer-"]').forEach(element => {
            const match = element.className.match(/child-timer-(\d+)/);
            if (match) {
                const childId = match[1];
                fetch(`/anak/${childId}/remaining-time`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            element.textContent = data.formatted_time;
                        }
                    });
            }
        });
    }

    // Mulai timer
    function startTimer(anakId) {
        showNotification('Memulai timer...', 'info');
        const btn = document.getElementById('start-timer-btn');
        const originalHTML = btn.innerHTML;
        btn.innerHTML = '<div class="loading"></div>';
        btn.disabled = true;
        
        fetch(`/anak/${anakId}/start-timer`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(res => res.json())
        .then(data => {
            btn.innerHTML = originalHTML;
            btn.disabled = false;
            if (data.success) {
                activeTimer = true;
                timerExpiredNotified = false; // Reset flag saat timer dimulai
                showNotification('Timer dimulai! (berjalan di background)', 'success');
                
                // Fetch data awal
                updateLiveTimer();
                updateAllChildTimers();
                
                // Local countdown setiap 1 detik
                if (timerInterval) clearInterval(timerInterval);
                timerInterval = setInterval(() => {
                    localCountdown();
                }, 1000);
                
                // Sync ke server setiap 10 detik
                setInterval(() => {
                    updateLiveTimer();
                    updateAllChildTimers();
                }, 10000);
            } else {
                showNotification(data.message || 'Gagal memulai timer', 'error');
            }
        })
        .catch(err => {
            console.error(err);
            btn.innerHTML = originalHTML;
            btn.disabled = false;
            showNotification('Gagal memulai timer', 'error');
        });
    }

    // Hentikan timer
    function stopTimer(anakId) {
        showNotification('Menghentikan timer...', 'info');
        const btn = document.getElementById('stop-timer-btn');
        const originalHTML = btn.innerHTML;
        btn.innerHTML = '<div class="loading"></div>';
        btn.disabled = true;
        
        fetch(`/anak/${anakId}/stop-timer`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(res => res.json())
        .then(data => {
            btn.innerHTML = originalHTML;
            btn.disabled = false;
            if (data.success) {
                activeTimer = false;
                showNotification('Timer dihentikan', 'info');
                
                // Stop interval
                if (timerInterval) {
                    clearInterval(timerInterval);
                    timerInterval = null;
                }
                
                // Notify Service Worker
                if ('serviceWorker' in navigator && navigator.serviceWorker.controller) {
                    navigator.serviceWorker.controller.postMessage({
                        type: 'TIMER_STOP'
                    });
                }
                
                updateLiveTimer();
                updateAllChildTimers();
            } else {
                showNotification(data.message || 'Gagal menghentikan timer', 'error');
            }
        })
        .catch(err => {
            console.error(err);
            btn.innerHTML = originalHTML;
            btn.disabled = false;
            showNotification('Gagal menghentikan timer', 'error');
        });
    }

    // Reset timer
    function resetTimer(anakId) {
        if (!confirm('Reset timer ke limit awal? Semua progress akan hilang.')) return;
        
        showNotification('Merestart timer...', 'info');
        
        fetch(`/anak/${anakId}/reset-timer`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                activeTimer = false;
                timerExpiredNotified = false; // Reset flag
                showNotification('Timer telah direset', 'success');
                
                // Stop interval
                if (timerInterval) {
                    clearInterval(timerInterval);
                    timerInterval = null;
                }
                
                // Notify Service Worker
                if ('serviceWorker' in navigator && navigator.serviceWorker.controller) {
                    navigator.serviceWorker.controller.postMessage({
                        type: 'TIMER_RESET'
                    });
                }
                
                updateLiveTimer();
                updateAllChildTimers();
            } else {
                showNotification(data.message || 'Gagal mereset timer', 'error');
            }
        })
        .catch(err => {
            console.error(err);
            showNotification('Gagal mereset timer', 'error');
        });
    }

    // Update limit waktu
    function updateLimit(anakId) {
        const limitDetik = document.getElementById('limit-detik').value;
        
        if (!limitDetik || limitDetik < 60 || limitDetik > 28800) {
            showNotification('Masukkan limit waktu antara 60 - 28800 detik', 'warning');
            return;
        }

        showNotification('Mengupdate limit waktu...', 'info');
        
        fetch(`/anak/${anakId}/update-limit`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ limit_detik: limitDetik })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message, 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showNotification(data.message, 'error');
            }
        })
        .catch(err => {
            console.error(err);
            showNotification('Terjadi kesalahan', 'error');
        });
    }

    // Set active child
    function setActiveChild(anakId) {
        showNotification('Mengaktifkan anak...', 'info');
        
        fetch(`/anak/${anakId}/set-active`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showNotification('Anak telah diaktifkan', 'success');
                setTimeout(() => location.reload(), 700);
            } else {
                showNotification(data.message || 'Gagal mengaktifkan anak', 'error');
            }
        })
        .catch(() => showNotification('Gagal mengaktifkan anak', 'error'));
    }

    // Set quick limit
    function setQuickLimit(seconds) {
        document.getElementById('limit-detik').value = seconds;
        showNotification(`Limit diatur ke ${seconds/3600} jam`, 'info');
    }

    // Set quick limit untuk edit modal
    function setEditQuickLimit(seconds) {
        document.getElementById('editLimitInput').value = seconds;
    }

    // Validasi sebelum mulai game
    function startGameWithValidation(anakId) {
        const activeChildId = {{ $activeChild ? $activeChild->id : 'null' }};
        
        // Jika mencoba main dari child card yang tidak aktif
        if (anakId !== activeChildId) {
            showNotification('❌ Anak ini belum aktif! Silakan aktifkan terlebih dahulu.', 'error');
            return;
        }
        
        // Fetch remaining time untuk cek status timer
        fetch(`/anak/${anakId}/remaining-time`)
            .then(res => res.json())
            .then(data => {
                if (!data.success) {
                    showNotification('❌ Gagal mengambil data anak', 'error');
                    return;
                }
                
                // Cek apakah timer sudah dimulai
                if (!data.is_running || !data.timer_started_at) {
                    showNotification('⏱️ Timer belum dimulai! Silakan mulai timer terlebih dahulu.', 'warning');
                    return;
                }
                
                // Cek apakah masih ada waktu
                if (data.remaining_seconds <= 0) {
                    showNotification('⏰ Waktu bermain habis! Silakan reset timer untuk bermain lagi.', 'error');
                    return;
                }
                
                // Semua validasi passed, redirect ke permainan
                showNotification('✅ Membuka permainan...', 'info');
                setTimeout(() => {
                    window.location.href = '{{ route("permainan") }}';
                }, 500);
            })
            .catch(err => {
                console.error('Error:', err);
                showNotification('❌ Terjadi kesalahan saat validasi', 'error');
            });
    }

    // Modal functions
    function openEditModal(anakId, namaAnak, limitDetik) {
        editingAnakId = anakId;
        document.getElementById('editLimitInput').value = limitDetik;
        document.getElementById('editModal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeEditModal() {
        document.getElementById('editModal').style.display = 'none';
        document.body.style.overflow = 'auto';
        editingAnakId = null;
    }

    function saveEditLimit() {
        const limitDetik = document.getElementById('editLimitInput').value;
        
        if (!limitDetik || limitDetik < 60 || limitDetik > 28800) {
            showNotification('Masukkan limit waktu antara 60 - 28800 detik', 'warning');
            return;
        }

        showNotification('Menyimpan perubahan...', 'info');
        
        fetch(`/anak/${editingAnakId}/update-limit`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
            },
            body: JSON.stringify({ limit_detik: limitDetik })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message, 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showNotification(data.message, 'error');
            }
        })
        .catch(err => {
            console.error(err);
            showNotification('Terjadi kesalahan', 'error');
        });
    }

    // Checkbox handler untuk aktifkan anak
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.active-child-checkbox').forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                const anakId = this.getAttribute('data-anak-id');
                
                if (this.checked) {
                    // Uncheck semua kecuali yang ini
                    document.querySelectorAll('.active-child-checkbox').forEach(cb => {
                        if (cb !== this) cb.checked = false;
                    });
                    
                    // AJAX set active
                    setActiveChild(anakId);
                } else {
                    // Jika dicentang lalu di-uncheck
                    fetch(`/anak/${anakId}/set-active`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ deactivate: true })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            showNotification('Anak dinonaktifkan', 'info');
                            setTimeout(() => location.reload(), 700);
                        } else {
                            showNotification(data.message || 'Gagal menonaktifkan anak', 'error');
                        }
                    })
                    .catch(() => showNotification('Gagal menonaktifkan anak', 'error'));
                }
            });
        });
    });

    // Notification system
    function showNotification(message, type = 'info') {
        // Remove existing notifications
        document.querySelectorAll('.notification').forEach(n => n.remove());
        
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : type === 'warning' ? 'exclamation-triangle' : 'info-circle'}"></i>
            <span>${message}</span>
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.animation = 'slideInRight 0.3s ease-out reverse';
            setTimeout(() => notification.remove(), 300);
        }, 4000);
    }

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        // Reset flag saat page load
        timerExpiredNotified = false;
        
        // Register Service Worker untuk background timer
        registerServiceWorker();
        
        // Request notification permission
        requestNotificationPermission();
        
        // Initial timer update
        updateLiveTimer();
        updateAllChildTimers();
        
        // Local countdown setiap 1 detik (smooth)
        if (timerInterval) clearInterval(timerInterval);
        timerInterval = setInterval(() => {
            localCountdown();
        }, 1000);
        
        // Sync ke server setiap 10 detik
        setInterval(() => {
            updateLiveTimer();
            updateAllChildTimers();
        }, 10000);
        
        // Close modal when clicking outside
        document.getElementById('editModal')?.addEventListener('click', function(e) {
            if (e.target === this) closeEditModal();
        });
        
        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeEditModal();
        });
    });

    // Update timer when page becomes visible
    document.addEventListener('visibilitychange', function() {
        if (!document.hidden) {
            updateLiveTimer();
            updateAllChildTimers();
        }
    });
    </script>
</body>
</html>