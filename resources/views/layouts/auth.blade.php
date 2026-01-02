<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Fitsport Ecommerce">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Fitsport | @yield('title', 'Autenticación')</title>

    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@300;400;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/malefashion/css/bootstrap.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/malefashion/css/font-awesome.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/malefashion/css/style.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/malefashion/css/fitsport.css') }}" type="text/css">
</head>

<body>
    <div id="preloder">
        <div class="loader"></div>
    </div>

    <header class="header">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="header__logo py-4">
                        <a href="{{ route('home') }}">
                            <h1 style="color: var(--fitsport-gold); font-weight: 800; margin: 0;">FitSport</h1>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    @yield('content')

    <footer class="footer" style="margin-top: 100px;">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="footer__copyright__text">
                        <p>Copyright &copy; 2025 Todos los derechos reservados | Fitsport 2025</p>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="{{ asset('assets/malefashion/js/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('assets/malefashion/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/malefashion/js/main.js') }}"></script>
</body>

</html>
