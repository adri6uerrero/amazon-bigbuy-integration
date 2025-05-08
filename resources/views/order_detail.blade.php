@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Detalle del Pedido #{{ $order->id }}</h1>
    <div class="mb-3">
        <a href="{{ route('orders.index') }}" class="btn btn-secondary">&larr; Volver al listado</a>
    </div>
    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title">Amazon Order ID: <span class="text-primary">{{ $order->amazon_order_id }}</span></h5>
@php
    $badge = match($order->status) {
        'pendiente' => 'warning',
        'enviado' => 'success',
        'error' => 'danger',
        'cancelado' => 'secondary',
        default => 'secondary',
    };
@endphp
<p class="card-text">
    <strong>Estado:</strong> <span class="badge bg-{{ $badge }}">{{ ucfirst($order->status) }}</span>
@if($order->status === 'cancelado')
    <span class="ms-2 text-secondary"><i class="bi bi-x-circle"></i> Pedido cancelado</span>
@endif
    @if($order->status === 'error')
        <span class="ms-2 text-danger"><i class="bi bi-exclamation-triangle"></i> Pedido con error</span>
    @elseif($order->status === 'pendiente')
        <span class="ms-2 text-warning"><i class="bi bi-clock"></i> Pendiente de acción</span>
    @endif
</p>
<p class="card-text"><strong>Tracking:</strong> {{ $order->tracking_number ?? '-' }}</p>
<p class="card-text"><strong>Fecha de creación:</strong> {{ $order->created_at->format('Y-m-d H:i') }}</p>
<div class="my-3">
    @if($order->status === 'error')
        <form action="{{ route('orders.retry', $order->id) }}" method="POST" class="d-inline">
            @csrf
            <button class="btn btn-warning"><i class="bi bi-arrow-repeat"></i> Reintentar procesamiento</button>
        </form>
    @endif
    @if($order->status === 'pendiente')
        <form action="{{ route('orders.markShipped', $order->id) }}" method="POST" class="d-inline">
            @csrf
            <button class="btn btn-success"><i class="bi bi-truck"></i> Marcar como Enviado</button>
        </form>
        <form action="{{ route('orders.cancel', $order->id) }}" method="POST" class="d-inline ms-2">
            @csrf
            <button class="btn btn-outline-danger"><i class="bi bi-x-circle"></i> Cancelar Pedido</button>
        </form>
    @endif
    @if($order->status === 'cancelado' || $order->status === 'enviado')
        <form action="{{ route('orders.reopen', $order->id) }}" method="POST" class="d-inline ms-2">
            @csrf
            <button class="btn btn-outline-info"><i class="bi bi-arrow-counterclockwise"></i> Reabrir Pedido</button>
        </form>
    @endif
    @if($order->status === 'enviado' || $order->status === 'pendiente')
        <form action="{{ route('orders.resendEmail', $order->id) }}" method="POST" class="d-inline ms-2">
            @csrf
            <button class="btn btn-outline-primary"><i class="bi bi-envelope-arrow-up"></i> Reenviar Email</button>
        </form>
    @endif
    <button class="btn btn-outline-secondary ms-2" data-bs-toggle="modal" data-bs-target="#addNoteModal"><i class="bi bi-sticky"></i> Añadir Nota</button>

    <!-- Modal para añadir nota -->
    <div class="modal fade" id="addNoteModal" tabindex="-1" aria-labelledby="addNoteModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <form method="POST" action="{{ route('orders.addNote', $order->id) }}">
            @csrf
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="addNoteModalLabel">Añadir Nota al Pedido</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
              </div>
              <div class="modal-body">
                <textarea name="note" class="form-control" rows="3" required placeholder="Escribe una nota..."></textarea>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-primary">Guardar Nota</button>
              </div>
            </div>
        </form>
      </div>
    </div>
</div>
<hr>
<div class="alert alert-info">
    <strong>Total de items:</strong> {{ $order->items->sum('quantity') }}<br>
    <strong>Importe total:</strong> ${{ number_format($order->items->sum(function($i){return $i->quantity * $i->price;}), 2) }}
</div>
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">Historial de acciones</div>
        <div class="card-body p-2">
            @if($order->logs && count($order->logs))
                <ul class="list-group list-group-flush">
                    @foreach($order->logs as $log)
                        <li class="list-group-item small">
                            <span class="badge bg-secondary">{{ $log->created_at->format('d/m/Y H:i') }}</span>
                            <strong>{{ $log->action }}</strong> - {{ $log->description }}
                        </li>
                    @endforeach
                </ul>
            @else
                <span class="text-muted">Sin historial.</span>
            @endif
        </div>
    </div>

    <div class="card mb-3">
        <div class="card-header">Datos del Cliente</div>
        <div class="card-body">
            <p><strong>Nombre:</strong> {{ $order->customer->name }}</p>
            <p><strong>Email:</strong> {{ $order->customer->email }}</p>
            <p><strong>Dirección:</strong> {{ $order->customer->address }}</p>
        </div>
    </div>
    <div class="card mb-3">
        <div class="card-header">Items del Pedido</div>
        <div class="card-body">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>SKU</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->items as $item)
                        <tr>
                            <td>{{ $item->sku }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ $item->price }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
</div>
@endsection
