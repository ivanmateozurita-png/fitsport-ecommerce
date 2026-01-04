@extends('layouts.malefashion')

@section('title', 'Inicio')

@section('content')
    <!-- Hero Section Begin -->
    <section class="hero">
        <div class="hero__slider owl-carousel">
            <div class="hero__items set-bg" data-setbg="{{ asset('assets/malefashion/img/hero/hero-1.webp') }}">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-5 col-lg-7 col-md-8">
                            <div class="hero__text">
                                <h6>Colección 2025</h6>
                                <h2>Ropa Deportiva de Alto Rendimiento</h2>
                                <p>Diseñada para atletas que buscan superar sus límites con estilo y comodidad.</p>
                                <a href="{{ route('catalog.index') }}" class="primary-btn">Ver Catálogo <span class="arrow_right"></span></a>
                                <div class="hero__social">
                                    <a href="#" aria-label="Facebook"><i class="fa fa-facebook"></i></a>
                                    <a href="#" aria-label="Instagram"><i class="fa fa-instagram"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="hero__items set-bg hero-shoe-align" data-setbg="{{ asset('assets/malefashion/img/hero/hero-2.webp') }}">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-5 col-lg-7 col-md-8">
                            <div class="hero__text">
                                <h6>Nuevos Arribos</h6>
                                <h2>Estilo y Potencia</h2>
                                <p>Descubre lo último en tecnología deportiva y moda urbana.</p>
                                <a href="{{ route('catalog.index') }}" class="primary-btn">Comprar Ahora <span class="arrow_right"></span></a>
                                <div class="hero__social">
                                    <a href="#" aria-label="Facebook"><i class="fa fa-facebook"></i></a>
                                    <a href="#" aria-label="Instagram"><i class="fa fa-instagram"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Hero Section End -->

    <!-- Banner Section Begin -->
    <section class="banner spad">
        <div class="container">
            <div class="row">
                <!-- Large Left Banner (Clothing) -->
                    <div class="col-lg-7 col-md-12">
                        <div class="banner__item">
                            <div class="banner__item__pic" style="height: 850px;"> <!-- Luxury Height Increased -->
                                <img src="{{ asset('assets/malefashion/img/banner/banner-1.webp') }}" alt="Coleccion Ropa" loading="lazy" style="height: 100%; object-fit: cover;">
                            </div>
                            <div class="banner__item__text">
                                <h2>Colección Ropa</h2>
                                <a href="{{ route('catalog.index') }}">VER MÁS</a>
                            </div>
                        </div>
                    </div>
                
                <!-- Right Column (stacked Banners) -->
                <div class="col-lg-5 col-md-12">
                    <!-- Top Right (Accessories) -->
                    <div class="banner__item">
                        <div class="banner__item__pic" style="height: 350px;"> <!-- Balanced Height -->
                            <img src="{{ asset('assets/malefashion/img/banner/banner-2.webp') }}" alt="Accesorios" loading="lazy" style="height: 100%; object-fit: cover;">
                        </div>
                        <div class="banner__item__text">
                            <h2>Accesorios</h2>
                            <a href="{{ route('catalog.index') }}">VER MÁS</a>
                        </div>
                    </div>
                    <!-- Bottom Right (Shoes) -->
                    <div class="banner__item">
                        <div class="banner__item__pic" style="height: 350px;"> 
                            <img src="{{ asset('assets/malefashion/img/banner/banner-3.webp') }}" alt="Calzado" loading="lazy" style="height: 100%; object-fit: cover;">
                        </div>
                        <div class="banner__item__text">
                            <h2>Calzado 2025</h2>
                            <a href="{{ route('catalog.index') }}">VER MÁS</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Banner Section End -->

    <!-- Product Section Begin -->
    <section class="product spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <ul class="filter__controls">
                        <li class="active" data-filter="*">Más Vendidos</li>
                        <li data-filter=".new-arrivals">Nuevos</li>
                        <li data-filter=".hot-sales">Ofertas</li>
                    </ul>
                </div>
            </div>
            <div class="row product__filter">
                @foreach($products as $product)
                <div class="col-lg-3 col-md-6 col-sm-6 col-md-6 col-sm-6 mix new-arrivals">
                    <div class="product__item">
                        <div class="product__item__pic set-bg" data-setbg="{{ asset($product->image_path) }}">
                            <span class="label">Nuevo</span>
                            <ul class="product__hover">
                                <li><a href="#" aria-label="Agregar a favoritos"><img src="{{ asset('assets/malefashion/img/icon/heart.png') }}" alt="Favoritos"></a></li>
                                <li><a href="{{ route('product.show', $product->id) }}" aria-label="Ver detalles"><img src="{{ asset('assets/malefashion/img/icon/search.png') }}" alt="Ver"></a></li>
                            </ul>
                        </div>
                        <div class="product__item__text">
                            <h6>{{ $product->name }}</h6>
                            <a href="{{ route('product.show', $product->id) }}" class="add-cart">+ Ver Detalles</a>

                            <h5>${{ number_format($product->price, 2) }}</h5>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
    <!-- Product Section End -->
@endsection
