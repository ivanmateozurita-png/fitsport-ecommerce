@extends('layouts.malefashion')

@section('content')
    <section class="breadcrumb-option">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="breadcrumb__text">
                        <h4>Checkout</h4>
                        <div class="breadcrumb__links">
                            <a href="{{ route('home') }}">Inicio</a>
                            <a href="{{ route('cart.index') }}">Carrito</a>
                            <span>Checkout</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="checkout spad" style="background: transparent; padding: 30px 0;">
        <div class="container">
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form action="{{ route('checkout.process') }}" method="POST" class="checkout__form">
                @csrf
                <div class="row">
                    <!-- Datos de envío - izquierda en PC -->
                    <div class="col-lg-7 col-12 mb-4">
                        <div class="checkout__form__input_wrapper" style="background: white; padding: 25px; border-radius: 8px; box-shadow: 0 5px 15px rgba(0,0,0,0.05);">
                            <h6 class="checkout__title" style="margin-bottom: 20px;">Datos de Envío</h6>
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <label for="name" style="font-size: 14px; margin-bottom: 8px; display: block;">Nombre Completo <span style="color: red;">*</span></label>
                                    <input type="text" id="name" name="name" value="{{ old('name', $user->name ?? '') }}" required 
                                           style="width: 100%; padding: 12px 15px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-lg-6 col-12 mb-3">
                                    <label for="email" style="font-size: 14px; margin-bottom: 8px; display: block;">Email <span style="color: red;">*</span></label>
                                    <input type="email" id="email" name="email" value="{{ old('email', $user->email ?? '') }}" required 
                                           style="width: 100%; padding: 12px 15px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
                                    @error('email')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-lg-6 col-12 mb-3">
                                    <label for="phone" style="font-size: 14px; margin-bottom: 8px; display: block;">Teléfono <span style="color: red;">*</span></label>
                                    <input type="text" id="phone" name="phone" value="{{ old('phone', $user->profile->phone ?? '') }}" required 
                                           style="width: 100%; padding: 12px 15px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
                                    @error('phone')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-12 mb-3">
                                    <label for="address" style="font-size: 14px; margin-bottom: 8px; display: block;">Dirección Completa <span style="color: red;">*</span></label>
                                    <textarea id="address" name="address" required 
                                              style="width: 100%; padding: 12px 15px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; min-height: 80px;">{{ old('address', $user->profile->address ?? '') }}</textarea>
                                    @error('address')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-12 mb-3">
                                    <label for="notas" style="font-size: 14px; margin-bottom: 8px; display: block;">Notas del Pedido (Opcional)</label>
                                    <textarea id="notas" name="notas" placeholder="Ej: Dejar en portería" 
                                              style="width: 100%; padding: 12px 15px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; min-height: 60px;">{{ old('notas') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tu Pedido - derecha en PC -->
                    <div class="col-lg-5 col-12">
                        <div class="checkout__order" style="width: 100%; margin: 0; padding: 25px; box-sizing: border-box;">

                            <h6 class="order__title">Tu Pedido</h6>
                            <div class="checkout__order__products">Producto <span>Total</span></div>
                            <ul class="checkout__total__products">
                                @foreach($cart as $item)
                                    <li>
                                        {{ $item['name'] }} 
                                        @if(isset($item['size']) && $item['size'])
                                            <span class="text-muted" style="font-size: 0.85em;">({{ $item['size'] }})</span>
                                        @endif
                                        x {{ $item['quantity'] }} 
                                        <span>${{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                                    </li>
                                @endforeach
                            </ul>
                            <ul class="checkout__total__all">
                                <li>Subtotal <span>${{ number_format($subtotal, 2) }}</span></li>
                                <li>IVA (15%) <span>${{ number_format($iva, 2) }}</span></li>
                                <li>Envío <span>Gratis</span></li>
                                <li><strong>Total</strong> <span style="color: #fede67; font-weight: bold;">${{ number_format($total, 2) }}</span></li>
                            </ul>
                            <button type="submit" class="order-btn" id="orderBtn">
                                <span class="btn-text">Confirmar Pedido</span>
                                <span class="btn-loader">
                                    <span class="truck-wrapper">
                                        <span class="truck">
                                            <span class="truck-body"></span>
                                            <span class="truck-cabin"></span>
                                            <span class="truck-wheel truck-wheel-front"></span>
                                            <span class="truck-wheel truck-wheel-back"></span>
                                        </span>
                                        <span class="road"></span>
                                    </span>
                                    <span class="loader-text">Procesando...</span>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>

<style>
/* Order Button Animation */
.order-btn {
    width: 100%;
    padding: 18px 30px;
    background: linear-gradient(135deg, #111 0%, #333 100%);
    border: none;
    border-radius: 8px;
    cursor: pointer;
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
}

.order-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.3);
}

.btn-text {
    color: #fff;
    font-size: 16px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    transition: opacity 0.3s ease;
}

.btn-loader {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    opacity: 0;
    width: 80%;
    transition: opacity 0.3s ease;
}

.order-btn.loading .btn-text {
    opacity: 0;
}

.order-btn.loading .btn-loader {
    opacity: 1;
}

.truck-wrapper {
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
    height: 35px;
}

.truck {
    position: relative;
    display: flex;
    align-items: flex-end;
    animation: drive 1s linear infinite;
}

.truck-body {
    width: 30px;
    height: 18px;
    background: #fede67;
    border-radius: 3px 3px 0 0;
    position: relative;
}

.truck-cabin {
    width: 15px;
    height: 12px;
    background: #fff;
    border-radius: 3px 3px 0 0;
    margin-left: 2px;
}

.truck-wheel {
    width: 8px;
    height: 8px;
    background: #333;
    border-radius: 50%;
    position: absolute;
    bottom: -4px;
    border: 2px solid #666;
    animation: spin 0.3s linear infinite;
}

.truck-wheel-front {
    left: 5px;
}

.truck-wheel-back {
    right: 8px;
}

.road {
    width: 100%;
    height: 2px;
    background: linear-gradient(90deg, transparent, #fede67, #fede67, transparent);
    margin-top: 4px;
    animation: road-move 0.5s linear infinite;
}

.loader-text {
    display: block;
    color: #fede67;
    font-size: 12px;
    font-weight: 600;
    margin-top: 5px;
    text-align: center;
}

@keyframes drive {
    0%, 100% { transform: translateX(-5px); }
    50% { transform: translateX(5px); }
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

@keyframes road-move {
    from { background-position: 0 0; }
    to { background-position: -20px 0; }
}

.order-btn.success {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
}

.order-btn.success .loader-text {
    color: #fff;
}

.order-btn.success .loader-text::after {
    content: '¡Pedido Confirmado!';
}

.order-btn.success .loader-text {
    display: none;
}

.order-btn.success::after {
    content: '✓ ¡Pedido Confirmado!';
    color: #fff;
    font-size: 16px;
    font-weight: 600;
}
</style>

<script>
document.getElementById('orderBtn').addEventListener('click', function(e) {
    const btn = this;
    const form = btn.closest('form');
    
    // Validar formulario primero
    if (!form.checkValidity()) {
        return; // Deja que el navegador muestre los errores
    }
    
    e.preventDefault();
    btn.classList.add('loading');
    btn.disabled = true;
    
    // Simular procesamiento y luego enviar
    setTimeout(() => {
        btn.classList.add('success');
        setTimeout(() => {
            form.submit();
        }, 800);
    }, 2000);
});
</script>
@endsection
