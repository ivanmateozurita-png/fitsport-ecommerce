@extends('layouts.admin')

@section('title', 'Reporte de Ventas')

@push('styles')
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
@endpush

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Reporte de Ventas</h3>
                <p class="text-subtitle text-muted">Resumen de ingresos y pedidos completados.</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Reportes</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-header">
                Filtros de Búsqueda
            </div>
            <div class="card-body">
                <form action="{{ route('admin.reports.sales') }}" method="GET" class="row align-items-end">
                    <div class="col-md-4 mb-3">
                        <label for="start_date" class="form-label">Fecha Inicio</label>
                        <input type="date" class="form-control" name="start_date" value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="end_date" class="form-label">Fecha Fin</label>
                        <input type="date" class="form-control" name="end_date" value="{{ request('end_date') }}">
                    </div>
                    <div class="col-md-4 mb-3">
                        <button type="submit" class="btn btn-primary w-100">Filtrar</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-md-4">
                <div class="card bg-primary text-white">
                    <div class="card-body text-center">
                        <h5 class="text-white">Ingresos Totales</h5>
                        <h2 class="text-white">${{ number_format($totalRevenue, 2) }}</h2>
                        <p class="mb-0">En el periodo seleccionado</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                 <div class="card bg-success text-white">
                    <div class="card-body text-center">
                        <h5 class="text-white">Pedidos Completados</h5>
                        <h2 class="text-white">{{ $orders->count() }}</h2>
                        <p class="mb-0">Pagados o Enviados</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Detalle de Transacciones</span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="tblSales">
                        <thead>
                            <tr>
                                <th>Pedido #</th>
                                <th>Fecha</th>
                                <th>Cliente</th>
                                <th>Estado</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                                <tr>
                                    <td>{{ $order->id }}</td>
                                    <td>{{ $order->date ? $order->date->format('d/m/Y') : 'N/A' }}</td>
                                    <td>{{ $order->user->name ?? 'Usuario Eliminado' }}</td>
                                    <td>
                                        <span class="badge bg-success">{{ ucfirst($order->status) }}</span>
                                    </td>
                                    <td>${{ number_format($order->total, 2) }}</td>
                                </tr>
                            @empty
                                {{-- No empty row here, DataTables handles empty state --}}
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

<script>
$(document).ready(function() {
    $('#tblSales').DataTable({
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.6/i18n/es-ES.json'
        },
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50],
        dom: "<'row mb-3'" +
            "<'col-md-6'l>" +
            "<'col-md-6 text-end'B>" +
            ">" +
            "<'row mb-2'" +
            "<'col-md-6'f>" +
            "<'col-md-6'>" +
            ">" +
            "<'row'<'col-12'tr>>" +
            "<'row mt-2'" +
            "<'col-md-5'i>" +
            "<'col-md-7'p>" +
            ">",
        buttons: [
            {
                extend: 'excel',
                text: '<i class="bi bi-file-earmark-excel"></i> Excel',
                className: 'btn btn-success btn-sm',
                title: 'Reporte de Ventas - Fitsport'
            },
            {
                extend: 'pdf',
                text: '<i class="bi bi-file-earmark-pdf"></i> PDF',
                className: 'btn btn-danger btn-sm',
                title: 'Reporte de Ventas - Fitsport'
            },
            {
                extend: 'print',
                text: '<i class="bi bi-printer"></i> Imprimir',
                className: 'btn btn-secondary btn-sm',
                title: 'Reporte de Ventas - Fitsport'
            }
        ]
    });
});
</script>
@endpush
