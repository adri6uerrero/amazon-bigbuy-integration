@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card-summary">
                <div class="title">Total Pedidos</div>
                <div class="value">{{ $orders->total() }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card-summary">
                <div class="title">Pendientes</div>
                <div class="value">{{ \App\Models\Order::where('status', 'pendiente')->count() }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card-summary">
                <div class="title">Enviados</div>
                <div class="value">{{ \App\Models\Order::where('status', 'enviado')->count() }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card-summary">
                <div class="title">Errores</div>
                <div class="value">{{ \App\Models\Order::where('status', 'error')->count() }}</div>
            </div>
        </div>
    </div>
    <div class="card-footer d-flex justify-content-end">
        <nav>
            <ul class="pagination mb-0">
                @if ($orders->onFirstPage())
                    <li class="page-item disabled"><span class="page-link"><span class="me-1">&#8592;</span> Previous</span></li>
                @else
                    <li class="page-item"><a class="page-link" href="{{ $orders->previousPageUrl() }}" rel="prev"><span class="me-1">&#8592;</span> Previous</a></li>
                @endif

                @if ($orders->hasMorePages())
                    <li class="page-item"><a class="page-link" href="{{ $orders->nextPageUrl() }}" rel="next">Next <span class="ms-1">&#8594;</span></a></li>
                @else
                    <li class="page-item disabled"><span class="page-link">Next <span class="ms-1">&#8594;</span></span></li>
                @endif
            </ul>
        </nav>
    </div>
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h2>Listado de Pedidos</h2>
        <a href="{{ route('orders.create') }}" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Crear Pedido
        </a>
    </div>
    <form method="GET" action="{{ route('orders.index') }}" class="mb-3">
    <div class="row g-2 align-items-end">
        <div class="col-md-4">
            <label for="search" class="form-label">Buscar:</label>
            <input type="text" name="search" id="search" value="{{ request('search') }}" class="form-control" placeholder="Amazon Order ID, Cliente, Tracking, Email...">
        </div>
        <div class="col-md-3">
            <label for="status" class="form-label">Estado:</label>
            <select name="status" id="status" class="form-select">
                <option value="">Todos</option>
                <option value="pendiente" {{ request('status') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                <option value="enviado" {{ request('status') == 'enviado' ? 'selected' : '' }}>Enviado</option>
                <option value="error" {{ request('status') == 'error' ? 'selected' : '' }}>Error</option>
            </select>
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <button type="submit" class="btn btn-primary w-100">Buscar</button>
        </div>
    </div>
</form>
    </div>
</form>
    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Amazon Order ID</th>
                <th>Cliente</th>
                <th>Estado</th>
                <th>Tracking</th>
                <th>Creado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($orders as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    @php
    $highlight = function($text) {
        $search = request('search');
        if (!$search) return $text;
        return preg_replace('/(' . preg_quote($search, '/') . ')/i', '<mark>$1</mark>', $text);
    };
@endphp
<td>{!! $highlight($order->amazon_order_id) !!}</td>
<td>{!! $order->customer ? $highlight($order->customer->name) : '-' !!}</td>
                    <td>
    @php
        $badge = match($order->status) {
            'pendiente' => 'warning',
            'enviado' => 'success',
            'error' => 'danger',
            default => 'secondary',
        };
    @endphp
    <span class="badge bg-{{ $badge }}">{{ ucfirst($order->status) }}</span>
</td>
                    <td>{{ $order->tracking_number ?? '-' }}</td>
                    <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                    <td>
                        <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary" title="Ver Detalle">
    <i class="bi bi-eye"></i>
</a>
@if($order->status === 'error')
<form action="{{ route('orders.retry', $order->id) }}" method="POST" style="display:inline-block;">
    @csrf
    <button class="btn btn-sm btn-outline-warning" title="Reintentar"><i class="bi bi-arrow-repeat"></i></button>
</form>
@endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">No hay pedidos.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    {{ $orders->withQueryString()->links() }}
</div>
@endsection
