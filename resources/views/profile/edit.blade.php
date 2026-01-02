@extends('layouts.malefashion')

@section('title', 'Editar Perfil')

@section('content')
<!-- Breadcrumb Section Begin -->
<section class="breadcrumb-option">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb__text">
                    <h4>Editar Perfil</h4>
                    <div class="breadcrumb__links">
                        <a href="{{ route('home') }}">Inicio</a>
                        <a href="{{ route('profile.show') }}">Mi Perfil</a>
                        <span>Editar</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Breadcrumb Section End -->

<!-- Edit Profile Section Begin -->
<section class="checkout spad">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Editar Información Personal</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            
                            <!-- Foto de perfil -->
                            <div class="form-group text-center mb-4">
                                @if($profile && $profile->image_path)
                                    <img src="{{ asset('storage/' . $profile->image_path) }}" 
                                         alt="Foto de perfil" 
                                         class="rounded-circle mb-3"
                                         style="width: 120px; height: 120px; object-fit: cover;"
                                         id="preview-image">
                                @else
                                    <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center mx-auto mb-3"
                                         style="width: 120px; height: 120px;" id="default-avatar">
                                        <span class="text-white" style="font-size: 36px;">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </span>
                                    </div>
                                    <img src="" alt="Preview" class="rounded-circle mb-3 d-none"
                                         style="width: 120px; height: 120px; object-fit: cover;"
                                         id="preview-image">
                                @endif
                                
                                <div>
                                    <label for="image" class="btn btn-outline-dark btn-sm">
                                        <i class="fa fa-camera"></i> Cambiar foto
                                    </label>
                                    <input type="file" name="image" id="image" class="d-none" accept="image/*">
                                </div>
                                @error('image')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <!-- Nombre -->
                            <div class="form-group">
                                <label for="name">Nombre completo *</label>
                                <input type="text" name="name" id="name" 
                                       class="form-control @error('name') is-invalid @enderror"
                                       value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Email (solo lectura) -->
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" value="{{ $user->email }}" disabled>
                                <small class="text-muted">El email no se puede cambiar</small>
                            </div>

                            <!-- Teléfono -->
                            <div class="form-group">
                                <label for="phone">Teléfono</label>
                                <input type="text" name="phone" id="phone" 
                                       class="form-control @error('phone') is-invalid @enderror"
                                       value="{{ old('phone', $profile->phone ?? '') }}"
                                       placeholder="Ej: 0991234567">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Dirección -->
                            <div class="form-group">
                                <label for="address">Dirección</label>
                                <input type="text" name="address" id="address" 
                                       class="form-control @error('address') is-invalid @enderror"
                                       value="{{ old('address', $profile->address ?? '') }}"
                                       placeholder="Calle, número, sector...">
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Ciudad -->
                            <div class="form-group">
                                <label for="city">Ciudad</label>
                                <input type="text" name="city" id="city" 
                                       class="form-control @error('city') is-invalid @enderror"
                                       value="{{ old('city', $profile->city ?? '') }}"
                                       placeholder="Ej: Quito">
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Botones -->
                            <div class="form-group d-flex justify-content-between mt-4">
                                <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary">
                                    Cancelar
                                </a>
                                <button type="submit" class="btn btn-dark">
                                    Guardar cambios
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Edit Profile Section End -->

@push('scripts')
<script>
    // Preview de imagen
    document.getElementById('image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('preview-image');
                const defaultAvatar = document.getElementById('default-avatar');
                
                preview.src = e.target.result;
                preview.classList.remove('d-none');
                
                if (defaultAvatar) {
                    defaultAvatar.classList.add('d-none');
                }
            }
            reader.readAsDataURL(file);
        }
    });
</script>
@endpush
@endsection
