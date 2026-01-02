@extends('layouts.auth_modern')

@section('content')
<div class="split-screen">
    <!-- Left Side: Branding -->
    <div class="left-side" style="background-image: url('{{ asset('assets/malefashion/img/hero/hero-2.jpg') }}');">
        <div class="left-content">
            <h1 style="font-size: 4rem; font-weight: 800; line-height: 1.2;">
                SEGURIDAD <br> <span style="color: #e53637;">PRIMERO</span>
            </h1>
            <p class="mt-4" style="font-size: 1.2rem; color: #ccc; max-width: 500px;">
                Verifica tu correo para activar tu cuenta y comenzar a comprar lo mejor en ropa deportiva.
            </p>
        </div>
    </div>

    <!-- Right Side: Verify Email Form -->
    <div class="right-side">
        <div class="login-card">
            <a href="{{ route('home') }}" class="logo-text">
                Fit<span>Sport</span>
            </a>
            
            <h4 class="text-white mb-4 text-center">Verifica tu Correo</h4>

            <div class="mb-4 text-white-50 text-center">
                {{ __('¡Gracias por registrarte! Antes de comenzar, ¿podrías verificar tu dirección de correo electrónico haciendo clic en el enlace que te acabamos de enviar? Si no recibiste el correo, con gusto te enviaremos otro.') }}
            </div>

            @if (session('status') == 'verification-link-sent')
                <div class="alert alert-success mb-4" role="alert">
                    {{ __('Se ha enviado un nuevo enlace de verificación a la dirección de correo electrónico que proporcionaste durante el registro.') }}
                </div>
            @endif

            <div class="mt-4">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="btn-fitsport">
                        {{ __('Reenviar Email de Verificación') }}
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}" class="mt-3 text-center">
                    @csrf
                    <button type="submit" class="auth-links" style="background: none; border: none; padding: 0;">
                        {{ __('Cerrar Sesión') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
