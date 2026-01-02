<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Fitsport Ecommerce">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Fitsport | @yield('title', 'Inicio')</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('favicon.png') }}">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@300;400;600;700;800;900&display=swap"
    rel="stylesheet">

    <!-- Css Styles -->
    <link rel="stylesheet" href="{{ asset('assets/malefashion/css/bootstrap.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/malefashion/css/font-awesome.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/malefashion/css/elegant-icons.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/malefashion/css/magnific-popup.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/malefashion/css/nice-select.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/malefashion/css/owl.carousel.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/malefashion/css/slicknav.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/malefashion/css/style.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/malefashion/css/fitsport.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/malefashion/css/hero-fix.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('assets/malefashion/css/fitsport-premium.css') }}" type="text/css">
    
    <!-- SweetAlert2 -->
    <!-- SweetAlert2 & Global Notifications -->
    <!-- SweetAlert2 Removed from Head -->
    
    @stack('styles')
    @livewireStyles
</head>

<body>
    <!-- Page Preloder -->
    <div id="preloder">
        <div class="loader"></div>
    </div>

    <!-- Offcanvas Menu Begin -->
    <div class="offcanvas-menu-overlay"></div>
    <div class="offcanvas-menu-wrapper">
        <div class="offcanvas__nav__option">
            <a href="#" class="search-switch"><img src="{{ asset('assets/malefashion/img/icon/search.png') }}" alt=""></a>
            <a href="#"><img src="{{ asset('assets/malefashion/img/icon/heart.png') }}" alt=""></a>
            <a href="#"><img src="{{ asset('assets/malefashion/img/icon/cart.png') }}" alt=""> <span>0</span></a>
        </div>
        
        <div id="mobile-menu-wrap"></div>
    </div>
    <!-- Offcanvas Menu End -->

    <!-- Header Section Begin -->
    @include('partials.malefashion.header')
    <!-- Header Section End -->

    @yield('content')

    <!-- Footer Section Begin -->
    @include('partials.malefashion.footer')
    <!-- Footer Section End -->

    <!-- Search Begin (Livewire) -->
    <div class="search-model">
        <div class="h-100 d-flex align-items-center justify-content-center">
            <div class="search-close-switch">+</div>
            <div class="search-model-form" style="width: 100%; max-width: 600px; padding: 0 20px;">
                <livewire:product-search />
            </div>
        </div>
    </div>
    <!-- Search End -->

    <!-- FitBot AI Assistant -->
    @include('shop.partials.fitbot')

    <!-- Js Plugins -->
    <script src="{{ asset('assets/malefashion/js/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('assets/malefashion/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/malefashion/js/jquery.nice-select.min.js') }}"></script>
    <script src="{{ asset('assets/malefashion/js/jquery.nicescroll.min.js') }}"></script>
    <script src="{{ asset('assets/malefashion/js/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ asset('assets/malefashion/js/jquery.countdown.min.js') }}"></script>
    <script src="{{ asset('assets/malefashion/js/jquery.slicknav.js') }}"></script>
    <script src="{{ asset('assets/malefashion/js/mixitup.min.js') }}"></script>
    <script src="{{ asset('assets/malefashion/js/owl.carousel.min.js') }}"></script>
    @livewireScripts
    <script src="{{ asset('assets/malefashion/js/main.js') }}"></script>
    @include('partials.global-swal')
    @stack('scripts')
</body>

</html>


