@extends('layouts.admin')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Detalle del Pedido #{{ $order->id }}</h3>
                <p class="text-subtitle text-muted">Información detallada y gestión de estado.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Pedidos</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Detalle</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="row">
            <!-- Columna Izquierda: Detalles del Cliente e Items -->
            <div class="col-12 col-lg-8">
                <!-- Items del Pedido -->
                <div class="card">
                    <div class="card-header">
                        <h4>Productos Comprados</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Producto</th>
                                        <th>Precio Unit.</th>
                                        <th>Cant.</th>
                                        <th class="text-end">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->items as $item)
                                        <tr>
                                                    <div class="d-flex align-items-center">
                                                        @if($item->product && $item->product->image_path)
                                                            <img src="{{ asset($item->product->image_path) }}" alt="img" width="40" class="rounded me-2" onerror="this.style.display='none'">
                                                        @endif
                                                        <div>
                                                            <h6 class="mb-0">{{ $item->product ? $item->product->name : 'Producto Eliminado' }}</h6>
                                                            @if($item->size)
                                                                <small class="text-muted">Talla: {{ $item->size }}</small>
                                                            @endif
                                                        </div>
                                                    </div>
                                            <td>${{ number_format($item->unit_price, 2) }}</td>
                                            <td>{{ $item->quantity }}</td>
                                            <td class="text-end">${{ number_format($item->unit_price * $item->quantity, 2) }}</td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td colspan="3" class="text-end fw-bold">TOTAL</td>
                                        <td class="text-end fw-bold fs-5">${{ number_format($order->total, 2) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Columna Derecha: Información y Acciones -->
            <div class="col-12 col-lg-4">
                
                <!-- Gestión de Estado -->
                <div class="card">
                    <div class="card-header bg-primary"> 
                        <h4 class="text-white mb-0">Estado del Pedido</h4>
                    </div>
                    <div class="card-body pt-4">
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="form-group mb-3">
                                <label for="status" class="form-label">Cambiar Estado:</label>
                                <select name="status" id="status" class="form-select">
                                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pendiente</option>
                                    <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Enviado</option>
                                    <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Entregado</option>
                                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelado</option>
                                </select>
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Actualizar Estado</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Detalles del Cliente -->
                <div class="card">
                    <div class="card-header">
                        <h4>Datos del Cliente</h4>
                    </div>
                    <div class="card-body">
                        @if($order->user)
                            <div class="d-flex align-items-center mb-4">
                                @if($order->user->profile && $order->user->profile->image_path)
                                    <div class="avatar avatar-lg me-3">
                                        <img src="{{ asset('storage/' . $order->user->profile->image_path) }}" alt="User" style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover;">
                                    </div>
                                @else
                                    <div class="avatar avatar-lg me-3" style="background: #6c757d; border-radius: 50%; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 18px;">
                                        {{ strtoupper(substr($order->user->name, 0, 2)) }}
                                    </div>
                                @endif
                                <div>
                                    <h5 class="mb-0">{{ $order->user->name }}</h5>
                                    <small class="text-muted">Cliente Registrado</small>
                                </div>
                            </div>
                            <hr>
                            <p><strong>Email:</strong><br> {{ $order->user->email }}</p>
                            <p><strong>Teléfono:</strong><br> {{ $order->user->profile->phone ?? 'No registrado' }}</p>
                            <p><strong>Dirección de Envío:</strong><br> {{ $order->user->profile->address ?? 'No registrada' }}</p>
                        @else
                            <div class="alert alert-danger">
                                El usuario asociado a este pedido ha sido eliminado de la base de datos.
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </section>
</div>
@endsection
