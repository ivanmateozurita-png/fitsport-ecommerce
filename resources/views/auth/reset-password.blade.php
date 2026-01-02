@extends('layouts.auth_modern')

@section('content')
<div class="split-screen">
    <!-- Left Side: Image & Branding -->
    <div class="left-side">
        <div class="left-content">
            <h1 style="font-size: 4rem; font-weight: 800; line-height: 1.2;">
                CREA <br> TU <br> <span style="color: #e53637;">NUEVA CLAVE</span>
            </h1>
            <p class="mt-4" style="font-size: 1.2rem; color: #ccc; max-width: 500px;">
                Asegura tu cuenta con una contraseña fuerte y vuelve a la acción.
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

            <form method="POST" action="{{ route('password.store') }}">
                @csrf

                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Email Address -->
                <div class="mb-4">
                    <label for="email" class="form-label">Correo Electrónico</label>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username">
                    @error('email')
                        <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label for="password" class="form-label">Nueva Contraseña</label>
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="••••••••">
                    @error('password')
                        <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="mb-4">
                    <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
                    <input id="password_confirmation" type="password" class="form-control @error('password_confirmation') is-invalid @enderror" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••">
                    @error('password_confirmation')
                        <span class="text-danger small mt-1 d-block">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn-fitsport">
                    RESTABLECER CONTRASEÑA
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
