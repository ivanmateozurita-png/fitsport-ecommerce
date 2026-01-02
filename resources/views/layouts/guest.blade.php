<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- ShopGrids Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('assets/shop/css/main.css') }}?v=2.1">
    
    <!-- Custom CSS for Login Page customization -->
    <style>
        body {
            /* Fallback to Black Gradient if var is missing */
            background: var(--bg_color, linear-gradient(to right, #111111 , #252525)) !important;
            font-family: 'Chennai', sans-serif;
        }
        .login-card {
            box-shadow: 0px 5px 25px rgba(0,0,0,0.2);
            border-radius: 15px;
            background: #fff;
            padding: 40px;
            border-top: 5px solid var(--yalow, #fede67);
        }
        .text-primary {
            color: var(--yalow, #fede67) !important;
        }
        .btn-primary {
            background-color: var(--yalow, #fede67);
            border-color: var(--yalow, #fede67);
            color: var(--black, #070707);
            font-weight: 600;
        }
        .btn-primary:hover {
            background-color: #e6c85e;
            border-color: #e6c85e;
            color: var(--black, #070707);
        }
        .form-control:focus {
            border-color: var(--yalow, #fede67);
            box-shadow: 0 0 0 0.25rem rgba(254, 222, 103, 0.25);
        }
        a {
            color: var(--top_level_green, #395c46);
        }
        a:hover {
            color: var(--green, #1c2d23);
        }
    </style>
</head>
<body>
    <div class="d-flex align-items-center min-vh-100 justify-content-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 col-lg-5">
                    <div class="text-center mb-4">
                        <a href="/" class="text-decoration-none">
                             <!-- Logo with White text for dark background, but here relying on text classes -->
                             <h2 class="text-white fw-bold" style="font-style: italic;">Fit<span class="text-warning">Sport</span></h2>
                        </a>
                    </div>
                    
                    <div class="login-card">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('assets/shop/js/bootstrap.min.js') }}"></script>
</body>
</html>
