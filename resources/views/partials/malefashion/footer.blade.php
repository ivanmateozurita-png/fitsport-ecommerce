    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="footer__about">
                        <div class="footer__logo">
                            <a href="#"><h3 class="text-white" style="font-weight: 800; letter-spacing: -1px;">Fit<span style="color: #fede67;">Sport</span></h3></a>
                        </div>
                        <p>El cliente es el corazón de nuestro modelo de negocio único, que incluye el diseño.</p>
                        <a href="#"><img src="{{ asset('assets/malefashion/img/payment.png') }}" alt=""></a>
                    </div>
                </div>
                <div class="col-lg-2 offset-lg-1 col-md-3 col-sm-6">
                    <div class="footer__widget">
                        <h6>Tienda</h6>
                        <ul>
                            <li><a href="{{ route('catalog.index') }}">Catalogo Completo</a></li>
                            <li><a href="{{ route('catalog.index') }}">Ropa Deportiva</a></li>
                            <li><a href="{{ route('catalog.index') }}">Zapatos</a></li>
                            <li><a href="{{ route('catalog.index') }}">Accesorios</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-2 col-md-3 col-sm-6">
                    <div class="footer__widget">
                        <h6>Mi Cuenta</h6>
                        <ul>
                            @auth
                                <li><a href="{{ route('orders.my') }}">Mis Pedidos</a></li>
                                <li><a href="{{ route('cart.index') }}">Carrito de Compras</a></li>
                            @else
                                <li><a href="{{ route('login') }}">Iniciar Sesión</a></li>
                                <li><a href="{{ route('register') }}">Registrarse</a></li>
                            @endauth
                        </ul>
                    </div>
                </div>
                <div class="col-lg-3 offset-lg-1 col-md-6 col-sm-6">
                    <div class="footer__widget">
                        <h6>Contacto</h6>
                        <div class="footer__newslatter">
                            <p>¿Tienes dudas? Escríbenos.</p>
                            <p style="color: #b7b7b7; font-size: 14px;">contacto@fitsport.com</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="footer__copyright__text">
                        <p>Copyright ©
                            <script>
                                document.write(new Date().getFullYear());
                            </script>
                            Todos los derechos reservados | Fitsport 2025
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </footer>
