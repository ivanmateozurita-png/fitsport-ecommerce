<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fitsport | Iniciar Sesión</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    
    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('assets/malefashion/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/malefashion/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/malefashion/css/fitsport.css') }}">
    
    <style>
        body {
            font-family: 'Outfit', sans-serif;
            overflow-x: hidden;
            background-color: #000;
        }
        
        .split-screen {
            display: flex;
            min-height: 100vh;
            width: 100%;
        }
        
        .left-side {
            flex: 1;
            background-image: url("{{ asset('assets/malefashion/img/hero/hero-1.jpg') }}");
            background-size: cover;
            background-position: center;
            position: relative;
            display: none; /* Mobile first */
        }
        
        .left-side::before {
            content: '';
            position: absolute;
            top: 0; 
            left: 0;
            width: 100%; 
            height: 100%;
            background: linear-gradient(135deg, rgba(0,0,0,0.8), rgba(0,0,0,0.4));
        }
        
        .left-content {
            position: relative;
            z-index: 2;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 4rem;
            color: white;
        }
        
        .right-side {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background-color: #121212;
            padding: 2rem;
            position: relative;
        }
        
        /* Modern Form Styling */
        .login-card {
            width: 100%;
            max-width: 450px;
            padding: 3rem;
            background: #1e1e1e;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            border: 1px solid #333;
            /* Animation */
            animation: slideUp 0.8s ease-out forwards;
            opacity: 0;
            transform: translateY(30px);
        }
        
        .form-control {
            background-color: #2a2a2a;
            border: 1px solid #444;
            color: #fff;
            height: 50px;
            padding-left: 20px;
            border-radius: 8px;
            transition: all 0.3s;
        }
        
        .form-control:focus {
            background-color: #333;
            border-color: #e53637; /* Fitsport Red/Gold */
            box-shadow: 0 0 0 4px rgba(229, 54, 55, 0.1);
            color: white;
        }
        
        .form-label {
            color: #aaa;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }
        
        .btn-fitsport {
            background-color: #e53637;
            color: white;
            height: 50px;
            border-radius: 8px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            border: none;
            transition: all 0.3s;
            width: 100%;
            margin-top: 1rem;
        }
        
        .btn-fitsport:hover {
            background-color: #ff4d4d;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(229, 54, 55, 0.3);
            color: white;
        }
        
        .auth-links a {
            color: #888;
            transition: color 0.2s;
            text-decoration: none;
            font-size: 0.9rem;
        }
        
        .auth-links a:hover {
            color: #e53637;
        }
        
        .logo-text {
            color: white;
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 2rem;
            text-align: center;
            display: block;
            text-decoration: none;
        }
        
        .logo-text span {
            color: #e53637;
        }
        
        /* Desktop view */
        @media (min-width: 992px) {
            .left-side {
                display: block;
                flex: 1.2; /* Slightly wider image area */
            }
            .right-side {
                flex: 1;
            }
        }
        
        /* Animation Keyframes */
        @keyframes slideUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        .animate-delay-1 { animation-delay: 0.2s; }
        .animate-delay-2 { animation-delay: 0.4s; }
        
    </style>
</head>
<body>
    @yield('content')
    @include('partials.global-swal')
</body>
</html>
