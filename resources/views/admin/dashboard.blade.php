@extends('layouts.admin')

@section('content')
<style>
    .custom-centered-icon {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        width: 48px !important;
        height: 48px !important;
        min-width: 48px !important;
        padding: 0 !important;
        margin-right: 1rem !important;
        border-radius: 0.5rem !important;
    }
    .custom-centered-icon i {
        display: flex !important; /* Ensuring the icon itself is a flex container can help with pseudo-elements */
        align-items: center !important;
        justify-content: center !important;
        font-size: 1.5rem !important;
        line-height: 1 !important;
        margin: 0 !important;
        padding: 0 !important;
        width: auto !important;
        height: auto !important;
        transform: none !important; /* Reset position hacks */
    }

    /* Avatar Icon - Same as custom-centered-icon but circular and smaller */
    .custom-avatar-icon {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        width: 40px !important;
        height: 40px !important;
        min-width: 40px !important;
        padding: 0 !important;
        border-radius: 50% !important;
        border: none !important;
    }
    .custom-avatar-icon i {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        line-height: 1 !important;
        margin: 0 !important;
        padding: 0 !important;
        transform: none !important;
    }
</style>
<div class="page-heading">
    <h3>Dashboard - Resumen</h3>
</div>
<div class="page-content">
    <section class="row">
        <div class="col-12 col-lg-12">
            <div class="row">
                <!-- Productos -->
                <div class="col-6 col-md-3">
                    <div class="card shadow-sm">
                        <div class="card-body px-3 py-4 d-flex align-items-center">
                            <div class="custom-centered-icon bg-primary bg-opacity-25">
                                <i class="bi bi-tag-fill text-primary"></i>
                            </div>
                            <div>
                                <h6 class="text-muted font-semibold mb-0">Productos</h6>
                                <h6 class="font-extrabold mb-0">{{ $productCount }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Pedidos -->
                <div class="col-6 col-md-3">
                    <div class="card shadow-sm">
                        <div class="card-body px-3 py-4 d-flex align-items-center">
                            <div class="custom-centered-icon bg-info bg-opacity-25">
                                <i class="bi bi-bag-check-fill text-info"></i>
                            </div>
                            <div>
                                <h6 class="text-muted font-semibold mb-0">Pedidos Totales</h6>
                                <h6 class="font-extrabold mb-0">{{ $ordersCount }}</h6>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stock Bajo -->
                <div class="col-6 col-md-3">
                    <div class="card shadow-sm">
                        <div class="card-body px-3 py-4 d-flex align-items-center">
                            <div class="custom-centered-icon bg-danger bg-opacity-25">
                                <i class="bi bi-exclamation-triangle-fill text-danger"></i>
                            </div>
                            <div>
                                <h6 class="text-muted font-semibold mb-0">Stock Bajo</h6>
                                <h6 class="font-extrabold mb-0">{{ $lowStock }}</h6>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Acceso Rápido -->
                <div class="col-6 col-md-3">
                    <div class="card shadow-sm">
                        <div class="card-body px-3 py-4 d-flex align-items-center">
                            <div class="custom-centered-icon bg-success bg-opacity-25">
                                <i class="bi bi-plus-lg text-success"></i>
                            </div>
                            <div>
                                <h6 class="text-muted font-semibold mb-0">Acceso Rápido</h6>
                                <h6 class="font-extrabold mb-0">
                                    <a href="{{ route('admin.products.create') }}" class="btn btn-sm btn-success py-0 px-2" style="font-size: 0.8rem;">+ Nuevo</a>
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            
            <!-- Tabla Últimos Pedidos -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Últimos Pedidos</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover table-lg">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Cliente</th>
                                            <th>Fecha</th>
                                            <th>Total</th>
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentOrders as $order)
                                            <tr>
                                                <td class="col-1">#{{ $order->id }}</td>
                                                <td class="col-3">
                                                    <div class="d-flex align-items-center">
                                                        @if($order->user && $order->user->profile && $order->user->profile->image_path)
                                                            <img src="{{ asset('storage/' . $order->user->profile->image_path) }}" 
                                                                 alt="{{ $order->user->name }}"
                                                                 class="rounded-circle me-3"
                                                                 style="width: 40px; height: 40px; object-fit: cover;">
                                                        @else
                                                            <div class="custom-avatar-icon bg-light text-primary me-3">
                                                                <i class="bi bi-person-circle fs-4"></i>
                                                            </div>
                                                        @endif
                                                        <p class="font-bold mb-0">{{ $order->user->name ?? 'Usuario' }}</p>
                                                    </div>
                                                </td>
                                                <td class="col-auto">
                                                    <p class=" mb-0">{{ $order->date->format('d/m/Y') }}</p>
                                                </td>
                                                <td class="col-auto">
                                                    <p class="font-bold mb-0">${{ number_format($order->total, 2) }}</p>
                                                </td>
                                                <td class="col-auto">
                                                     @if($order->status == 'pending')
                                                        <span class="badge bg-warning text-dark">Pendiente</span>
                                                    @elseif($order->status == 'paid')
                                                        <span class="badge bg-success">Pagado</span>
                                                    @elseif($order->status == 'shipped')
                                                        <span class="badge bg-info">Enviado</span>
                                                    @elseif($order->status == 'delivered')
                                                        <span class="badge bg-success">Entregado</span>
                                                    @elseif($order->status == 'cancelled')
                                                        <span class="badge bg-danger">Cancelado</span>
                                                    @else
                                                        <span class="badge bg-secondary">{{ ucfirst($order->status) }}</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center">No hay pedidos recientes.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
