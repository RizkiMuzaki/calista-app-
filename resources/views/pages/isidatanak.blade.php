<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Data Anak - Calista</title>
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
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        .container {
            width: 100%;
            max-width: 500px;
            background: white;
            border-radius: 30px;
            padding: 40px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            animation: popIn 0.8s ease-out;
        }
        
        @keyframes popIn {
            from { transform: scale(0.8); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }
        
        h1 {
            font-family: 'Fredoka One', cursive;
            font-size: 2.8rem;
            color: #118ab2;
            text-align: center;
            margin-bottom: 30px;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        label {
            display: block;
            font-weight: 900;
            color: #495057;
            margin-bottom: 10px;
            font-size: 1.2rem;
            display: flex;
            align-items: center;
            gap: 10px;
            background: linear-gradient(135deg, #118ab2, #06d6a0);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        input {
            width: 100%;
            padding: 18px;
            border: 3px solid #dee2e6;
            border-radius: 15px;
            font-size: 1.1rem;
            font-weight: 600;
            transition: all 0.3s;
            background: #f8f9fa;
        }
        
        input:focus {
            outline: none;
            border-color: #118ab2;
            background: white;
            box-shadow: 0 0 0 6px rgba(17, 138, 178, 0.15);
        }
        
        .submit-btn {
            width: 100%;
            padding: 20px;
            background: linear-gradient(135deg, #ffd166 0%, #ef476f 100%);
            color: white;
            border: none;
            border-radius: 20px;
            font-size: 1.5rem;
            font-weight: 900;
            cursor: pointer;
            transition: all 0.3s;
            font-family: 'Fredoka One', cursive;
            box-shadow: 0 10px 0 #e63946;
            margin-top: 20px;
        }
        
        .submit-btn:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 0 #e63946;
        }
        
        .error-box {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 15px;
            margin-bottom: 20px;
            border-left: 5px solid #dc3545;
        }
        
        .success-box {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 15px;
            margin-bottom: 20px;
            border-left: 5px solid #28a745;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🧒 Data Anak</h1>
        
        @if($errors->any())
            <div class="error-box">
                <strong>Ada yang perlu diperbaiki:</strong>
                <ul style="margin-left: 20px; margin-top: 10px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        @if(session('success'))
            <div class="success-box">
                {{ session('success') }}
            </div>
        @endif
        
        <form method="POST" action="{{ route('anak.store') }}">
            @csrf
            
            <div class="form-group">
                <label for="nama_anak"><i class="fas fa-user"></i> Nama Anak</label>
                <input type="text" id="nama_anak" name="nama_anak" 
                       value="{{ old('nama_anak') }}" 
                       placeholder="Masukkan nama anak" required>
            </div>
            
            <div class="form-group">
                <label for="tanggal_lahir"><i class="fas fa-calendar"></i> Tanggal Lahir</label>
                <input type="date" id="tanggal_lahir" name="tanggal_lahir" 
                       value="{{ old('tanggal_lahir') }}" required>
            </div>
            
            <button type="submit" class="submit-btn">
                <i class="fas fa-save"></i> Simpan Data
            </button>
        </form>
    </div>
</body>
</html>
