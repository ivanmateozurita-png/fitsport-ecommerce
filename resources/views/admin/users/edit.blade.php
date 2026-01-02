@extends('layouts.admin')

@section('title', 'Editar Usuario')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Editar Rol de Usuario</h3>
                <p class="text-subtitle text-muted">Modificar permisos del usuario.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Usuarios</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Editar</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-header">
                <h4>Usuario: {{ $user->name }}</h4>
                <p>Email: {{ $user->email }}</p>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group mb-3">
                        <label for="role" class="form-label">Rol del Sistema</label>
                        <select class="form-select" name="role" id="role">
                            <option value="client" {{ $user->hasRole('client') ? 'selected' : '' }}>Cliente</option>
                            <option value="bodeguero" {{ $user->hasRole('bodeguero') ? 'selected' : '' }}>Bodeguero (Productos)</option>
                            <option value="admin" {{ $user->hasRole('admin') ? 'selected' : '' }}>Administrador</option>
                        </select>
                        <small class="text-muted">
                            <strong>Cliente:</strong> Solo compra y ve su historial<br>
                            <strong>Bodeguero:</strong> Gestiona productos y pedidos<br>
                            <strong>Admin:</strong> Acceso total al sistema
                        </small>
                    </div>

                    <div class="d-flex justify-content-end">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary me-2">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Actualizar Rol</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
@endsection
