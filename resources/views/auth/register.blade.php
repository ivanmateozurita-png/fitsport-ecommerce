@extends('layouts.auth_modern')

@section('content')
<div class="split-screen">
    <!-- Left Side: Image & Branding -->
    <div class="left-side">
        <div class="left-content">
            <h1 style="font-size: 4rem; font-weight: 800; line-height: 1.2;">
                ÚNETE <br> AL <br> <span style="color: #e53637;">EQUIPO</span>
            </h1>
            <p class="mt-4" style="font-size: 1.2rem; color: #ccc; max-width: 500px;">
                Crea tu cuenta hoy y lleva tu rendimiento al siguiente nivel. Acceso exclusivo a colecciones y ofertas.
            </p>
        </div>
    </div>

    <!-- Right Side: Register Form -->
    <div class="right-side">
        <div class="login-card" style="max-width: 550px;"> <!-- Slightly wider for 2 columns -->
            <a href="{{ route('home') }}" class="logo-text">
                Fit<span>Sport</span>
            </a>
            
            <h4 class="text-white mb-4 text-center">Crear Cuenta Nueva</h4>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Nombre Completo -->
                <div class="mb-3">
                    <label for="nombre_completo" class="form-label">Nombre Completo</label>
                    <input id="nombre_completo" type="text" class="form-control @error('nombre_completo') is-invalid @enderror" name="nombre_completo" value="{{ old('nombre_completo') }}" required autofocus placeholder="Tu nombre">
                    @error('nombre_completo')
                        <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                    @enderror
                </div>

                <div class="row">
                    <!-- Email -->
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required placeholder="email@ejemplo.com">
                        @error('email')
                            <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Telefono -->
                    <div class="col-md-6 mb-3">
                        <label for="telefono" class="form-label">Teléfono</label>
                        <input id="telefono" type="text" class="form-control @error('telefono') is-invalid @enderror" name="telefono" value="{{ old('telefono') }}" placeholder="Opcional">
                        @error('telefono')
                            <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <!-- direccion -->
                <div class="mb-3">
                    <label for="direccion" class="form-label">Dirección</label>
                    <textarea id="direccion" name="direccion" rows="2" class="form-control @error('direccion') is-invalid @enderror" placeholder="Tu dirección de envío">{{ old('direccion') }}</textarea>
                    @error('direccion')
                        <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                    @enderror
                </div>

                <div class="row">
                    <!-- Password -->
                    <div class="col-md-6 mb-4">
                        <label for="password" class="form-label">Contraseña</label>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required placeholder="••••••••">
                        @error('password')
                            <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="col-md-6 mb-4">
                        <label for="password_confirmation" class="form-label">Confirmar Pass</label>
                        <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required placeholder="••••••••">
                    </div>
                </div>

                <button type="submit" class="btn-fitsport">
                    REGISTRARSE
                </button>

                <!-- Divider -->
                <div class="d-flex align-items-center my-4">
                    <hr style="flex: 1; border-color: #444;">
                    <span class="px-3 text-white-50">o regístrate con</span>
                    <hr style="flex: 1; border-color: #444;">
                </div>

                <!-- Google Register Button -->
                <a href="{{ route('auth.google') }}" class="btn-google" style="display: flex; align-items: center; justify-content: center; gap: 10px; width: 100%; padding: 12px 20px; background: #fff; color: #333; border-radius: 8px; text-decoration: none; font-weight: 600; transition: all 0.3s;">
                    <svg width="20" height="20" viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>
                    Continuar con Google
                </a>

                <div class="text-center mt-4">
                    <p class="text-white-50 mb-0">¿Ya tienes cuenta? 
                        <a href="{{ route('login') }}" style="color: #e53637; font-weight: 600; text-decoration: none;">Inicia sesión aquí</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

