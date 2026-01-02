@extends('layouts.malefashion')

@section('content')
    <section class="breadcrumb-option">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__text">
                        <h4>Mis Pedidos</h4>
                        <div class="breadcrumb__links">
                            <a href="{{ route('home') }}">Inicio</a>
                            <span>Mis Pedidos</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="shopping-cart spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    @if($orders->count() > 0)
                        <div class="shopping__cart__table">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Pedido</th>
                                        <th>Fecha</th>
                                        <th>Estado</th>
                                        <th>Total</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $order)
                                        <tr>
                                            <td class="product__cart__item">
                                                <div class="product__cart__item__text">
                                                    <h6>Orden #{{ $order->id }}</h6>
                                                    <span>{{ $order->items->count() }} Productos</span>
                                                </div>
                                            </td>
                                            <td class="cart__price">{{ $order->date->format('d/m/Y') }}</td>
                                            <td class="cart__price">
                                                @if($order->status == 'pending')
                                                    <span class="badge badge-warning text-white">Pendiente</span>
                                                @elseif($order->status == 'paid')
                                                    <span class="badge badge-success">Pagado</span>
                                                @elseif($order->status == 'shipped')
                                                    <span class="badge badge-info text-white">Enviado</span>
                                                @else
                                                    <span class="badge badge-secondary">Entregado</span>
                                                @endif
                                            </td>
                                            <td class="cart__price">${{ number_format($order->total, 2) }}</td>
                                            <td class="cart__close">
                                                <a href="{{ route('order.confirmation', $order->id) }}" class="primary-btn" style="padding: 10px 20px; font-size: 14px;">Ver Factura</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center">
                            <h3>Aún no has realizado pedidos.</h3>
                            <a href="{{ route('catalog.index') }}" class="primary-btn mt-4">Ir a Comprar</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
