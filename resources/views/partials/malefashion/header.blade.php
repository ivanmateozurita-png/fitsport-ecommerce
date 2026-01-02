    <header class="header">
        <div class="header__top">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 col-md-7">
                        <div class="header__top__left">
                            <p>Envío gratis, devoluciones o reembolso en 30 días.</p>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-5">
                        <div class="header__top__right">
                            <div class="header__top__links">
                                @auth
                                    @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('bodeguero'))
                                        <a href="{{ route('admin.dashboard') }}" style="margin-right: 15px;">{{ auth()->user()->hasRole('admin') ? 'Administración' : 'Panel Bodega' }}</a>
                                    @endif
                                    <a href="{{ route('profile.show') }}" style="color: #fff; margin-right: 15px; text-decoration: none;">
                                        <i class="fa fa-user-circle"></i> {{ auth()->user()->name }}
                                    </a>
                                    <span style="color: #555; margin-right: 15px;">|</span>
                                    <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                                        @csrf
                                        <a href="#" onclick="event.preventDefault(); this.closest('form').submit();">Salir</a>
                                    </form>
                                @else
                                    <a href="{{ route('login') }}">Iniciar Sesión</a>
                                    <a href="{{ route('register') }}">Registrarse</a>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row" style="position: relative;">
                <div class="col-lg-3 col-md-3">
                    <div class="header__logo">
                        <a href="{{ route('home') }}">
                            <h3 style="font-weight: 800; letter-spacing: -1px; color: #ffffff;">Fit<span style="color: #fede67;">Sport</span><span style="color: #e53637; font-size: 2rem; line-height: 0;">.</span></h3>
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <nav class="header__menu mobile-menu">
                        <ul>
                            <li class="{{ request()->routeIs('home') ? 'active' : '' }}"><a href="{{ route('home') }}">Inicio</a></li>
                            <li class="{{ request()->routeIs('catalog.index') ? 'active' : '' }}"><a href="{{ route('catalog.index') }}">Catálogo</a></li>
                            @auth
                                <li class="{{ request()->routeIs('orders.my') ? 'active' : '' }}"><a href="{{ route('orders.my') }}" style="color: #e53637;">Mis Pedidos</a></li>
                                <li class="d-lg-none"><a href="{{ route('profile.show') }}">Mi Cuenta</a></li>
                                <li class="d-lg-none">
                                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit();">Salir</a>
                                    <form id="logout-form-mobile" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                                </li>
                            @else
                                <li class="d-lg-none"><a href="{{ route('login') }}">Iniciar Sesión</a></li>
                                <li class="d-lg-none"><a href="{{ route('register') }}">Registrarse</a></li>
                            @endauth
                        </ul>
                    </nav>
                </div>
                <!-- Desktop cart/search - hidden on mobile -->
                <div class="col-lg-3 col-md-3 d-none d-lg-flex" style="align-items: center; justify-content: flex-end; gap: 20px;">
                    <a href="#" class="search-switch"><i class="fa fa-search" style="font-size: 20px; color: #ffffff;"></i></a>
                    <a href="{{ route('cart.index') }}" style="position: relative; display: flex; align-items: center; gap: 5px; text-decoration: none;">
                        <i class="fa fa-shopping-cart" style="font-size: 22px; color: #ffffff;"></i>
                        <span id="cart-count" style="position: absolute; top: -8px; right: -10px; background: #fede67; color: #111; font-size: 11px; font-weight: bold; min-width: 18px; height: 18px; border-radius: 50%; display: {{ array_sum(array_map(fn($i) => $i['quantity'] ?? $i['cantidad'] ?? 0, session()->get('cart', []))) > 0 ? 'flex' : 'none' }}; align-items: center; justify-content: center;">{{ array_sum(array_map(fn($i) => $i['quantity'] ?? $i['cantidad'] ?? 0, session()->get('cart', []))) }}</span>
                        <span id="cart-price" style="color: #fede67; font-weight: 600; font-size: 14px; margin-left: 3px;">${{ number_format(array_sum(array_map(fn($i) => ($i['price'] ?? $i['precio'] ?? 0) * ($i['quantity'] ?? $i['cantidad'] ?? 0), session()->get('cart', []))), 2) }}</span>
                    </a>
                </div>
                <!-- Mobile cart/menu - shown only on mobile -->
                <div class="d-lg-none mobile-cart-icons">
                    <a href="{{ route('cart.index') }}" style="color: #fff !important; font-size: 22px; position: relative; background: transparent !important; border: none !important;">
                        <i class="fa fa-shopping-cart"></i>
                        @if(array_sum(array_map(fn($i) => $i['quantity'] ?? $i['cantidad'] ?? 0, session()->get('cart', []))) > 0)
                            <span style="position: absolute; top: -6px; right: -10px; background: #fede67; color: #111; font-size: 11px; font-weight: bold; min-width: 18px; height: 18px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">{{ array_sum(array_map(fn($i) => $i['quantity'] ?? $i['cantidad'] ?? 0, session()->get('cart', []))) }}</span>
                        @endif
                    </a>
                    <div class="canvas__open" style="color: #fff !important; font-size: 24px; cursor: pointer; background: transparent !important; border: none !important; width: auto !important; height: auto !important;"><i class="fa fa-bars"></i></div>
                </div>
            </div>
        </div>
    </header>
