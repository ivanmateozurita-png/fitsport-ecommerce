@extends('layouts.admin')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Crear Producto</h3>
                <p class="text-subtitle text-muted">Agrega un nuevo producto al catálogo.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Productos</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Crear</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section id="basic-vertical-layouts">
        <div class="row match-height">
            <div class="col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body">
                            <form class="form form-vertical" action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="name">Nombre del Producto</label>
                                                <input type="text" id="name" class="form-control" name="name" placeholder="Ej. Zapatillas Running" value="{{ old('name') }}" required>
                                                @error('name') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="category_id">Categoría</label>
                                                <select class="form-select" id="category_id" name="category_id" required>
                                                    <option value="">Seleccione...</option>
                                                    @foreach($categories as $cat)
                                                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                                            {{ $cat->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('category_id') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-4 col-12">
                                            <div class="form-group">
                                                <label for="price">Precio ($)</label>
                                                <input type="number" step="0.01" id="price" class="form-control" name="price" placeholder="0.00" value="{{ old('price') }}" required>
                                            </div>
                                        </div>

                                        <div class="col-md-4 col-12">
                                            <div class="form-group">
                                                <label for="stock">Stock</label>
                                                <input type="number" id="stock" class="form-control" name="stock" placeholder="Cantidad" value="{{ old('stock') }}" required>
                                            </div>
                                        </div>

                                        <div class="col-md-4 col-12">
                                            <div class="form-group">
                                                <label for="size">Talla</label>
                                                <input type="text" class="form-control" id="size" name="size" placeholder="Ej: M, L, 42, Única">
                                            </div>
                                        </div>
                                        
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="description">Descripción</label>
                                                <textarea class="form-control" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="image">Imagen Principal</label>
                                                <input class="form-control" type="file" id="image" name="image" accept="image/*">
                                                @error('image') <span class="text-danger">{{ $message }}</span> @enderror
                                            </div>
                                        </div>

                                        <div class="col-12 d-flex justify-content-end mt-3">
                                            <button type="submit" class="btn btn-primary me-1 mb-1">Guardar Producto</button>
                                            <a href="{{ route('admin.products.index') }}" class="btn btn-light-secondary me-1 mb-1">Cancelar</a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
