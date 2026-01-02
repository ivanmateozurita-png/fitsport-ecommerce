<div class="sidebar-wrapper active">
    <!-- Sidebar Header -->
    <div class="sidebar-header position-relative">
        <div class="d-flex justify-content-between align-items-center">
            <div class="logo">
                <a href="{{ route('admin.dashboard') }}" style="text-decoration: none;">
                    <h3 style="color: #ca1515; font-weight: 800; letter-spacing: 2px; margin: 0;">FIT<span style="color: #fff;">SPORT</span></h3>
                </a>
            </div>
            <!-- Modo oscuro fijo -->
            <div class="sidebar-toggler  x">
                <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
            </div>
        </div>
    </div>
    
    <!-- Sidebar Menu -->
    <div class="sidebar-menu">
        <ul class="menu">
            <li class="sidebar-title">Menú</li>

            <li class="sidebar-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <a href="{{ route('admin.dashboard') }}" class='sidebar-link'>
                    <i class="bi bi-grid-fill"></i>
                    <span>Inicio</span>
                </a>
            </li>

            {{-- Productos: Admin y Bodeguero --}}
            <li class="sidebar-item {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                <a href="{{ route('admin.products.index') }}" class='sidebar-link'>
                    <i class="bi bi-shop"></i>
                    <span>Productos</span>
                </a>
            </li>

            {{-- Categorías: Solo Admin --}}
            @if(auth()->user()->hasRole('admin'))
            <li class="sidebar-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <a href="{{ route('admin.categories.index') }}" class='sidebar-link'>
                    <i class="bi bi-tags-fill"></i>
                    <span>Categorías</span>
                </a>
            </li>
            @endif
            
            {{-- Pedidos: Admin y Bodeguero --}}
            <li class="sidebar-item {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                <a href="{{ route('admin.orders.index') }}" class='sidebar-link'>
                    <i class="bi bi-cart-fill"></i>
                    <span>Pedidos</span>
                </a>
            </li>

            {{-- Usuarios: Solo Admin --}}
            @if(auth()->user()->hasRole('admin'))
            <li class="sidebar-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <a href="{{ route('admin.users.index') }}" class='sidebar-link'>
                    <i class="bi bi-people-fill"></i>
                    <span>Usuarios</span>
                </a>
            </li>

            {{-- Reportes: Solo Admin --}}
            <li class="sidebar-item {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                <a href="{{ route('admin.reports.sales') }}" class='sidebar-link'>
                    <i class="bi bi-bar-chart-fill"></i>
                    <span>Reportes</span>
                </a>
            </li>
            @endif

            <li class="sidebar-item mt-5">
                <a href="{{ route('home') }}" class='sidebar-link'>
                    <i class="bi bi-arrow-left-circle-fill text-danger"></i>
                    <span>Volver a la Tienda</span>
                </a>
            </li>



        </ul>
    </div>
</div>
