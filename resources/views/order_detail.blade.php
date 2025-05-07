@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Detalle del Pedido #{{ $order->id }}</h1>
    <div class="mb-3">
        <a href="{{ route('orders.index') }}" class="btn btn-secondary">&larr; Volver al listado</a>
    </div>
    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title">Amazon Order ID: {{ $order->amazon_order_id }}</h5>
            <p class="card-text"><strong>Estado:</strong> {{ ucfirst($order->status) }}</p>
            <p class="card-text"><strong>Tracking:</strong> {{ $order->tracking_number ?? '-' }}</p>
            <p class="card-text"><strong>Fecha de creación:</strong> {{ $order->created_at->format('Y-m-d H:i') }}</p>
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
    @if($order->status === 'error')
    <form action="{{ route('orders.retry', $order->id) }}" method="POST">
        @csrf
        <button class="btn btn-warning">Reintentar procesamiento</button>
    </form>
    @endif
</div>
@endsection
