<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Fitsport</title>
    @include('partials.admin.styles')
    @stack('styles')
</head>

<body>
    <div id="app">
        <div id="sidebar">
            @include('partials.admin.sidebar')
        </div>
        <div id="main">
            @include('partials.admin.header')
            
            @yield('content')
            
            @include('partials.admin.footer')
        </div>
    </div>
    <!-- jQuery primero (requerido para DataTables) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    @include('partials.admin.scripts')
    @stack('scripts')
    @include('partials.global-swal')
</body>

</html>
