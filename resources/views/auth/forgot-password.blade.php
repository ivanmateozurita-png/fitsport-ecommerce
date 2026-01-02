@extends('layouts.auth_modern')

@section('content')
<div class="split-screen">
    <!-- Left Side: Image & Branding -->
    <div class="left-side">
        <div class="left-content">
            <h1 style="font-size: 4rem; font-weight: 800; line-height: 1.2;">
                RECUPERA <br> TU <br> <span style="color: #e53637;">ACCESO</span>
            </h1>
            <p class="mt-4" style="font-size: 1.2rem; color: #ccc; max-width: 500px;">
                No te preocupes. Te enviaremos las instrucciones para restablecer tu contraseña y volver al entrenamiento.
            </p>
        </div>
    </div>

    <!-- Right Side: Form -->
    <div class="right-side">
        <div class="login-card">
            <a href="{{ route('home') }}" class="logo-text">
                Fit<span>Sport</span>
            </a>
            
            <h4 class="text-white mb-4 text-center">Restablecer Contraseña</h4>

            <div class="mb-4 text-white-50 text-center small">
                ¿Olvidaste tu contraseña? No hay problema. Solo indícanos tu correo electrónico y te enviaremos un enlace para que elijas una nueva.
            </div>

            @if (session('status'))
                <div class="alert alert-success mb-4" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <!-- Email Address -->
                <div class="mb-4">
                    <label for="email" class="form-label">Correo Electrónico</label>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autofocus placeholder="ejemplo@email.com">
                    @error('email')
                        <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn-fitsport">
                    ENVIAR ENLACE DE RECUPERACIÓN
                </button>

                <div class="text-center mt-4">
                    <a href="{{ route('login') }}" style="color: #e53637; font-weight: 600; text-decoration: none;">
                        <i class="bi bi-arrow-left"></i> Volver al Login
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
