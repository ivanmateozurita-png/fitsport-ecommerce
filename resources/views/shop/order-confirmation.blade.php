@extends('layouts.malefashion')

@section('content')
    {{-- Estilos para impresión --}}
    <style>
        @media print {
            /* Ocultar todo excepto la factura */
            body * {
                visibility: hidden;
            }
            #invoice-section, #invoice-section * {
                visibility: visible;
            }
            #invoice-section {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                background: white;
                padding: 20px;
            }
            .no-print {
                display: none !important;
            }
            .invoice-header {
                border-bottom: 2px solid #000 !important;
            }
        }
        
        /* Estilos de factura */
        .invoice-container {
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 30px;
            margin-top: 20px;
        }
        .invoice-header {
            border-bottom: 2px solid #000;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .invoice-logo {
            font-size: 28px;
            font-weight: bold;
        }
        .invoice-title {
            text-align: right;
        }
        .invoice-details {
            margin-bottom: 30px;
        }
        .invoice-table th {
            background: #f8f9fa;
            border-bottom: 2px solid #000;
        }
        .invoice-total {
            border-top: 2px solid #000;
            font-weight: bold;
        }
    </style>

    <section class="breadcrumb-option no-print">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__text">
                        <h4>Confirmación de Pedido</h4>
                        <div class="breadcrumb__links">
                            <a href="{{ route('home') }}">Inicio</a>
                            <span>Pedido Confirmado</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="checkout spad">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    {{-- Mensaje de éxito --}}
                    <div class="text-center mb-4 no-print">
                        <i class="fa fa-check-circle" style="font-size: 80px; color: var(--fitsport-gold);"></i>
                        <h2 class="mt-3">¡Pedido Realizado Exitosamente!</h2>
                        <p class="text-muted">Tu pedido ha sido procesado correctamente</p>
                    </div>

                    {{-- Botones de acción --}}
                    <div class="text-center mb-4 no-print">
                        <button onclick="window.print()" class="btn btn-dark">
                            <i class="fa fa-print"></i> Imprimir Factura
                        </button>
                        <button onclick="window.print()" class="btn btn-outline-dark ml-2">
                            <i class="fa fa-download"></i> Descargar PDF
                        </button>
                    </div>

                    {{-- FACTURA --}}
                    <div id="invoice-section" class="invoice-container">
                        {{-- Encabezado de factura --}}
                        <div class="invoice-header row">
                            <div class="col-6">
                                <div class="invoice-logo">FITSPORT</div>
                                <small class="text-muted">Tu tienda deportiva de confianza</small>
                            </div>
                            <div class="col-6 invoice-title">
                                <h4>FACTURA</h4>
                                <p class="mb-0"><strong>N° {{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</strong></p>
                                <small>Fecha: {{ $order->date->format('d/m/Y H:i') }}</small>
                            </div>
                        </div>

                        {{-- Datos del cliente --}}
                        <div class="invoice-details row">
                            <div class="col-6">
                                <h6>Facturado a:</h6>
                                <p class="mb-0"><strong>{{ $order->user->name ?? 'Cliente' }}</strong></p>
                                <p class="mb-0">{{ $order->user->email ?? '' }}</p>
                            </div>
                            <div class="col-6 text-right">
                                <h6>Estado del Pedido:</h6>
                                <span class="badge badge-{{ $order->status == 'paid' ? 'success' : ($order->status == 'pending' ? 'warning' : 'info') }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </div>
                        </div>

                        {{-- Tabla de productos --}}
                        <table class="table invoice-table">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th class="text-center">Cantidad</th>
                                    <th class="text-right">P. Unitario</th>
                                    <th class="text-right">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                    <tr>
                                        <td>
                                            {{ $item->product->name ?? 'Producto' }}
                                            @if($item->size)
                                                <br><small class="text-muted">Talla: {{ $item->size }}</small>
                                            @endif
                                        </td>
                                        <td class="text-center">{{ $item->quantity }}</td>
                                        <td class="text-right">${{ number_format($item->unit_price, 2) }}</td>
                                        <td class="text-right">${{ number_format($item->unit_price * $item->quantity, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-right">Subtotal:</td>
                                    <td class="text-right">${{ number_format($order->total, 2) }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-right">Envío:</td>
                                    <td class="text-right">Gratis</td>
                                </tr>
                                <tr class="invoice-total">
                                    <td colspan="3" class="text-right"><strong>TOTAL:</strong></td>
                                    <td class="text-right"><strong>${{ number_format($order->total, 2) }}</strong></td>
                                </tr>
                            </tfoot>
                        </table>

                        {{-- Pie de factura --}}
                        <div class="text-center mt-4 pt-3" style="border-top: 1px solid #dee2e6;">
                            <small class="text-muted">
                                Gracias por tu compra en Fitsport.<br>
                                Ante cualquier consulta, contáctanos a soporte@fitsport.com
                            </small>
                        </div>
                    </div>

                    {{-- Botones adicionales --}}
                    <div class="text-center mt-4 no-print" style="display: flex; flex-direction: column; gap: 15px; align-items: center;">
                        <a href="{{ route('catalog.index') }}" class="primary-btn" style="width: 250px; text-align: center;">Seguir Comprando</a>
                        @auth
                            <a href="{{ route('orders.my') }}" class="primary-btn" style="width: 250px; text-align: center; background: transparent; border: 2px solid #111; color: #111;">Ver Mis Pedidos</a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

