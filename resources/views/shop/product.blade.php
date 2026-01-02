@extends('layouts.malefashion')

@section('title', $product->name)

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/malefashion/css/product-stock.css') }}" type="text/css">
@endpush

@section('content')
    <!-- Shop Details Section Begin -->
    <section class="shop-details">
        <div class="product__details__pic">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="product__details__breadcrumb">
                            <a href="{{ route('home') }}">Inicio</a>
                            <a href="{{ route('catalog.index') }}">Catálogo</a>
                            <span>{{ $product->name }}</span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="product__details__pic__item">
                            <img src="{{ asset($product->image_path) }}" alt="{{ $product->name }}" style="max-height: 500px; object-fit: contain;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="product__details__content">
            <div class="container">
                <div class="row d-flex justify-content-center">
                    <div class="col-lg-8">
                        <div class="product__details__text">
                            <h4>{{ $product->name }}</h4>

                            <h3>${{ number_format($product->price, 2) }}</h3>
                            <p>{{ $product->description }}</p>
                            <div class="product__details__stock">
                                @if($product->stock > 0)
                                    <span class="stock-badge in-stock">
                                        <i class="fa fa-check-circle"></i> Disponible ({{ $product->stock }} unidades)
                                    </span>
                                @else
                                    <span class="stock-badge out-of-stock">
                                        <i class="fa fa-times-circle"></i> Agotado
                                    </span>
                                @endif
                            </div>
                            <div class="product__details__option">
                                <div class="product__details__option__size">
                                    <span>Talla:</span>
                                    <span class="size-value">{{ $product->size ?? 'Única' }}</span>
                                    <input type="hidden" name="size" value="{{ $product->size ?? 'Única' }}">
                                </div>
                            </div>
                            <div class="product__details__cart__option">
                                <form id="add-to-cart-form">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="hidden" name="size" value="{{ $product->size ?? 'Única' }}">
                                    <div class="quantity">
                                        <div class="pro-qty">
                                            <input type="number" name="quantity" value="1" min="1" id="quantity-input">
                                        </div>
                                    </div>
                                    <button type="submit" class="primary-btn">Añadir al Carrito</button>
                                </form>
                            </div>
                            <div class="product__details__last__option">
                                <ul>
                                    <li><span>SKU:</span> {{ $product->id }}</li>
                                    <li><span>Categoría:</span> {{ $product->category->name ?? 'General' }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Shop Details Section End -->

    <div id="cart-notification" style="position: fixed; top: 100px; right: 20px; z-index: 9999; display: none;">
        <div class="alert alert-success" style="box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
            <strong>¡Producto añadido!</strong> Se añadió al carrito correctamente.
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add to cart form handler
            document.getElementById('add-to-cart-form').addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                const btn = this.querySelector('button[type="submit"]');
                const originalText = btn.textContent;
                
                btn.disabled = true;
                btn.textContent = 'Añadiendo...';
                
                fetch('{{ route("cart.add") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        const cartCountElement = document.getElementById('cart-count');
                        if (cartCountElement) {
                            cartCountElement.textContent = data.cart_count;
                            cartCountElement.style.display = data.cart_count > 0 ? 'flex' : 'none';
                        }
                        const cartPriceElement = document.getElementById('cart-price');
                        if (cartPriceElement) {
                            cartPriceElement.textContent = '$' + data.cart_total.toFixed(2);
                        }
                        
                        Swal.fire({
                            title: '¡Producto añadido!',
                            text: 'Se añadió al carrito correctamente.',
                            icon: 'success',
                            confirmButtonColor: '#e53637',
                            confirmButtonText: 'Aceptar'
                        });
                    } else {
                        console.error('Error adding to cart:', data.message);
                        Swal.fire({
                            title: 'Error',
                            text: data.message || 'Error al añadir al carrito.',
                            icon: 'error',
                            confirmButtonColor: '#e53637',
                            confirmButtonText: 'Aceptar'
                        });
                    }
                    
                    btn.disabled = false;
                    btn.textContent = originalText;
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        title: 'Error',
                        text: 'Ocurrió un error de red. Inténtalo de nuevo.',
                        icon: 'error',
                        confirmButtonColor: '#e53637',
                        confirmButtonText: 'Aceptar'
                    });
                    btn.disabled = false;
                    btn.textContent = originalText;
                });
            });
        });
    </script>
@endsection
