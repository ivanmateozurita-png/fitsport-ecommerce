@extends('layouts.malefashion')

@section('title', 'Mi Cuenta')

@section('content')
    <!-- Breadcrumb Section Begin -->
    <section class="breadcrumb-option">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__text">
                        <h4>Mi Cuenta</h4>
                        <div class="breadcrumb__links">
                            <a href="{{ route('home') }}">Inicio</a>
                            <span>Dashboard</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- Dashboard Section Begin -->
    <section class="shop spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title text-center">
                        <h2>Bienvenido, {{ Auth::user()->nombre_completo }}</h2>
                        <span style="display: block; margin-top: 10px; color: #b7b7b7;">Gestiona tu actividad desde aquí</span>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <!-- Mis Pedidos -->
                <div class="col-lg-4 col-md-6 col-sm-6">
                    <div class="blog__item">
                        <div class="blog__item__pic set-bg" data-setbg="{{ asset('assets/malefashion/img/blog/blog-2.jpg') }}" style="background-color: #f3f2ee;"></div>
                        <div class="blog__item__text">
                            <span><img src="{{ asset('assets/malefashion/img/icon/calendar.png') }}" alt=""> Historial</span>
                            <h5>Mis Pedidos</h5>
                            <a href="{{ route('orders.my') }}">Ver Historial</a>
                        </div>
                    </div>
                </div>

                <!-- ADMIN PANEL (Solo Visible para Admins) -->
                @if(Auth::user()->rol === 'admin')
                <div class="col-lg-4 col-md-6 col-sm-6">
                    <div class="blog__item">
                        <div class="blog__item__pic set-bg" data-setbg="{{ asset('assets/malefashion/img/about/testimonial-pic.jpg') }}" style="background-color: #f3f2ee;"></div>
                        <div class="blog__item__text">
                            <span><img src="{{ asset('assets/malefashion/img/icon/settings.png') }}" alt=""> Gestión</span>
                            <h5 style="color: #e53637;">Panel Admin</h5>
                            <a href="{{ route('admin.dashboard') }}">Ir a Administración</a>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Seguir Comprando -->
                <div class="col-lg-4 col-md-6 col-sm-6">
                    <div class="blog__item">
                        <div class="blog__item__pic set-bg" data-setbg="{{ asset('assets/malefashion/img/blog/blog-3.jpg') }}" style="background-color: #f3f2ee;"></div>
                        <div class="blog__item__text">
                            <span><img src="{{ asset('assets/malefashion/img/icon/search.png') }}" alt=""> Catálogo</span>
                            <h5>Seguir Comprando</h5>
                            <a href="{{ route('catalog.index') }}">Ir a la Tienda</a>
                        </div>
                    </div>
                </div>
                
                 <!-- Perfil -->
                 <div class="col-lg-4 col-md-6 col-sm-6">
                    <div class="blog__item">
                        <div class="blog__item__pic set-bg" data-setbg="{{ asset('assets/malefashion/img/blog/blog-1.jpg') }}" style="background-color: #f3f2ee;"></div>
                        <div class="blog__item__text">
                            <span><img src="{{ asset('assets/malefashion/img/icon/heart.png') }}" alt=""> Cuenta</span>
                            <h5>Mi Perfil</h5>
                            <a href="{{ route('profile.edit') }}">Editar Datos</a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row mt-5">
                <div class="col-lg-12 text-center">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="site-btn" style="background-color: #000; color: #fff;">CERRAR SESIÓN</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <!-- Dashboard Section End -->
@endsection
