@extends('layouts.malefashion')

@section('title', 'Mi Perfil')

@section('content')
<!-- Breadcrumb Section Begin -->
<section class="breadcrumb-option">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb__text">
                    <h4>Mi Perfil</h4>
                    <div class="breadcrumb__links">
                        <a href="{{ route('home') }}">Inicio</a>
                        <span>Mi Perfil</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Breadcrumb Section End -->

<!-- Profile Section Begin -->
<section class="checkout spad">
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        @endif

        <div class="row">
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body text-center">
                        @if($profile && $profile->image_path)
                            <img src="{{ asset('storage/' . $profile->image_path) }}" 
                                 alt="Foto de perfil" 
                                 class="rounded-circle mb-3"
                                 style="width: 150px; height: 150px; object-fit: cover;">
                        @else
                            <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center mx-auto mb-3"
                                 style="width: 150px; height: 150px;">
                                <span class="text-white" style="font-size: 48px;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </span>
                            </div>
                        @endif
                        
                        <h4>{{ $user->name }}</h4>
                        <p class="text-muted">{{ $user->email }}</p>
                        
                        @if($user->email_verified_at)
                            <span class="badge badge-success">Email verificado</span>
                        @else
                            <span class="badge badge-warning">Email no verificado</span>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Información Personal</h5>
                        <a href="{{ route('profile.edit') }}" class="btn btn-dark btn-sm">
                            <i class="fa fa-edit"></i> Editar
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-sm-4"><strong>Nombre:</strong></div>
                            <div class="col-sm-8">{{ $user->name }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4"><strong>Email:</strong></div>
                            <div class="col-sm-8">{{ $user->email }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4"><strong>Teléfono:</strong></div>
                            <div class="col-sm-8">{{ $profile->phone ?? 'No especificado' }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4"><strong>Dirección:</strong></div>
                            <div class="col-sm-8">{{ $profile->address ?? 'No especificada' }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4"><strong>Ciudad:</strong></div>
                            <div class="col-sm-8">{{ $profile->city ?? 'No especificada' }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4"><strong>Miembro desde:</strong></div>
                            <div class="col-sm-8">{{ $user->created_at->format('d/m/Y') }}</div>
                        </div>
                    </div>
                </div>
                
                <!-- Historial de pedidos rápido -->
                <div class="card mt-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Mis Pedidos</h5>
                        <a href="{{ route('orders.my') }}" class="btn btn-outline-dark btn-sm">Ver todos</a>
                    </div>
                    <div class="card-body">
                        @if($user->orders && $user->orders->count() > 0)
                            <p>Tienes {{ $user->orders->count() }} pedido(s)</p>
                        @else
                            <p class="text-muted">Aún no has realizado ningún pedido.</p>
                            <a href="{{ route('catalog.index') }}" class="btn btn-dark">Ver catálogo</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Profile Section End -->
@endsection
