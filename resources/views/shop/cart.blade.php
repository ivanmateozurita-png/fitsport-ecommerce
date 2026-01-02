@extends('layouts.malefashion')

@section('content')
    <section class="breadcrumb-option">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__text">
                        <h4>Carrito de Compras</h4>
                        <div class="breadcrumb__links">
                            <a href="{{ route('home') }}">Inicio</a>
                            <span>Carrito</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="shopping-cart spad">
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
            @endif

            @if(count($cart) > 0)
                <div class="row">
                    <div class="col-lg-8">
                        <div class="shopping__cart__table">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Producto</th>
                                        <th>Precio</th>
                                        <th>Cantidad</th>
                                        <th>Subtotal</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cart as $key => $item)
                                        <tr data-id="{{ $key }}" data-price="{{ $item['price'] ?? 0 }}">
                                            <td class="product__cart__item">
                                                <div class="product__cart__item__pic">
                                                    <img src="{{ asset($item['image_path'] ?? '') }}" alt="{{ $item['name'] ?? 'Producto' }}" style="width: 90px;">
                                                </div>
                                                <div class="product__cart__item__text">
                                                    <h6>{{ $item['name'] ?? 'Producto' }}</h6>
                                                    @if(isset($item['size']) && $item['size'])
                                                        <span class="text-muted" style="font-size: 0.9em;">Talla: {{ $item['size'] }}</span>
                                                    @endif
                                                    <h5>${{ number_format($item['price'] ?? 0, 2) }}</h5>
                                                </div>
                                            </td>
                                            <td class="cart__price">${{ number_format($item['price'] ?? 0, 2) }}</td>
                                            <td class="quantity__item">
                                                <div class="quantity">
                                                    <div class="pro-qty-2">
                                                        <input type="text" value="{{ $item['quantity'] ?? 1 }}" data-id="{{ $key }}">
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="cart__subtotal item-subtotal">${{ number_format(($item['price'] ?? 0) * ($item['quantity'] ?? 1), 2) }}</td>
                                            <td class="cart__close">
                                                <button type="button" class="remove-item" data-id="{{ $key }}">
                                                    <i class="fa fa-close"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="continue__btn">
                                    <a href="{{ route('catalog.index') }}">Continuar Comprando</a>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6">
                                <div class="continue__btn update__btn">
                                    <button type="button" class="clear-cart">
                                        <i class="fa fa-trash"></i> Vaciar Carrito
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="cart__total">
                            <h6>Resumen del Pedido</h6>
                            @php
                                $iva = $total * 0.15;
                                $totalConIva = $total + $iva;
                            @endphp
                            <ul>
                                <li>Subtotal <span id="cart-subtotal">${{ number_format($total, 2) }}</span></li>
                                <li>IVA (15%) <span id="cart-iva">${{ number_format($iva, 2) }}</span></li>
                                <li><strong>Total</strong> <span id="cart-total" style="color: #e53637; font-weight: bold;">${{ number_format($totalConIva, 2) }}</span></li>
                            </ul>
                            <a href="{{ route('checkout.index') }}" class="primary-btn">Proceder al Pago</a>
                        </div>
                    </div>
                </div>
            @else
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <div class="shopping__cart__table">
                             <h4>Tu carrito está vacío.</h4>
                             <a href="{{ route('catalog.index') }}" class="primary-btn mt-4">Ir a Comprar</a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let timeout = null;

            // Función para actualizar UI 
            function updateOptimisticUI(id, qty) {
                const tr = document.querySelector(`tr[data-id="${id}"]`);
                if (!tr) return;

                const price = parseFloat(tr.dataset.price);
                const subtotal = price * qty;
                
                // Actualizar subtotal del item visualmente
                const subtotalEl = tr.querySelector('.item-subtotal');
                if (subtotalEl) {
                    subtotalEl.textContent = '$' + subtotal.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                }

                // Indicar "Calculando..." en el total global
                const totalEl = document.getElementById('cart-total');
                if(totalEl) totalEl.style.opacity = '0.5';
            }

            // Listener para cambios manuales
            document.querySelectorAll('.pro-qty-2 input').forEach(input => {
                input.addEventListener('input', function() {
                    const id = this.dataset.id;
                    const val = parseInt(this.value) || 1;
                    
                    updateOptimisticUI(id, val);

                    clearTimeout(timeout);
                    timeout = setTimeout(() => {
                        updateCart(id, val);
                    }, 300); 
                });
                
                input.addEventListener('blur', function() {
                     const id = this.dataset.id;
                     const val = parseInt(this.value) || 1;
                     updateCart(id, val);
                });
            });
            
            // Listener botones 
            $('.pro-qty-2').on('click', '.qtybtn', function() {
                var input = $(this).parent().find('input');
                var id = input.data('id');
                // Esperamos un tick para que main.js actualice el value del input
                setTimeout(() => {
                    var val = parseInt(input.val());
                    updateOptimisticUI(id, val);
                    
                    clearTimeout(timeout);
                    timeout = setTimeout(() => {
                        updateCart(id, val);
                    }, 300);
                }, 10);
            });


            // Eliminar item
            document.querySelectorAll('.remove-item').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.dataset.id;
                    Swal.fire({
                        title: '¿Eliminar producto?',
                        text: "Se quitará este artículo del carrito",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#e53637',
                        cancelButtonColor: '#000',
                        confirmButtonText: 'Sí, eliminar',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            removeItem(id);
                        }
                    });
                });
            });

            // Vaciar carrito
            document.querySelector('.clear-cart')?.addEventListener('click', function() {
                Swal.fire({
                    title: '¿Vaciar carrito?',
                    text: "Se eliminarán todos los productos",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e53637',
                    cancelButtonColor: '#000',
                    confirmButtonText: 'Sí, vaciar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        clearCart();
                    }
                });
            });

            function updateCart(id, quantity) {
                if(quantity < 1) {
                    quantity = 1;
                    document.querySelector(`input[data-id="${id}"]`).value = 1;
                }
                
                fetch(`/cart/update/${id}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({ quantity: quantity, _method: 'PATCH' })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        const subtotal = parseFloat(data.cart_total);
                        const iva = subtotal * 0.15;
                        const totalConIva = subtotal + iva;
                        
                        document.getElementById('cart-subtotal').textContent = '$' + subtotal.toFixed(2);
                        document.getElementById('cart-iva').textContent = '$' + iva.toFixed(2);
                        const totalEl = document.getElementById('cart-total');
                        totalEl.textContent = '$' + totalConIva.toFixed(2);
                        totalEl.style.opacity = '1';
                        
                        const itemSubtotal = document.querySelector(`tr[data-id="${id}"] .item-subtotal`);
                        if(itemSubtotal) itemSubtotal.textContent = '$' + parseFloat(data.item_subtotal).toFixed(2);
                        
                        updateHeaderCart(data.cart_count, totalConIva);
                    } else {
                        Swal.fire('Error', data.message || 'Error al actualizar', 'error')
                            .then(() => location.reload());
                    }
                })
                .catch(err => {
                    console.error('Error:', err);
                    Swal.fire('Error', 'Hubo un problema al actualizar el carrito.', 'error')
                        .then(() => location.reload());
                });
            }

            function removeItem(id) {
                fetch(`/cart/remove/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            title: 'Eliminado',
                            text: 'El producto ha sido eliminado del carrito.',
                            icon: 'success',
                            confirmButtonColor: '#e53637',
                            confirmButtonText: 'OK',
                            timer: 2000
                        }).then(() => {
                            location.reload();
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        title: 'Error',
                        text: 'Hubo un problema al eliminar el producto.',
                        icon: 'error',
                        confirmButtonColor: '#e53637'
                    }).then(() => location.reload());
                });
            }

            function clearCart() {
                fetch('/cart/clear', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            title: 'Carrito Vacío',
                            text: 'Se han eliminado todos los productos.',
                            icon: 'success',
                            confirmButtonColor: '#e53637',
                            confirmButtonText: 'OK',
                            timer: 2000
                        }).then(() => {
                            location.reload();
                        });
                    }
                })
                .catch(err => {
                    console.error('Error:', err);
                    Swal.fire({
                        title: 'Error',
                        text: 'Hubo un problema al vaciar el carrito.',
                        icon: 'error',
                        confirmButtonColor: '#e53637'
                    }).then(() => location.reload());
                });
            }

            function updateHeaderCart(count, total) {
                const cartCountEl = document.getElementById('cart-count');
                if (cartCountEl) cartCountEl.textContent = count;
                const cartPriceEl = document.getElementById('cart-price');
                if (cartPriceEl) cartPriceEl.textContent = '$' + parseFloat(total).toFixed(2);
            }
        });
    </script>
@endpush
