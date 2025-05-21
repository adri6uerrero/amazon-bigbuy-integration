@extends('layouts.app')
@section('content')
<div class="container">
    @php
        $success = ($bigBuyResponse['success'] ?? false) && ($updateAmazon['success'] ?? false);
    @endphp
    @if($success)
        <div class="alert alert-success mt-4">
            <strong>¡Pedido procesado correctamente!</strong> El flujo simulado se completó sin errores.<br>
            Tracking generado: <b>{{ $bigBuyResponse['tracking_number'] ?? '-' }}</b>
        </div>
    @else
        <div class="alert alert-warning mt-4">
            <strong>Atención:</strong> Hubo un problema en el procesamiento simulado. Revisa los detalles abajo.
        </div>
    @endif
    <h1>Simulación de Flujo de Pedido Amazon → BigBuy</h1>
    <div class="card mb-3">
        <div class="card-header">1. Datos del pedido desde Amazon (simulado)</div>
        <div class="card-body">
            <pre>{{ print_r($orderData, true) }}</pre>
        </div>
    </div>
    <div class="card mb-3">
        <div class="card-header">2. Respuesta de BigBuy (simulado)</div>
        <div class="card-body">
            <pre>{{ print_r($bigBuyResponse, true) }}</pre>
        </div>
    </div>
    <div class="card mb-3">
        <div class="card-header">3. Actualización en Amazon (simulado)</div>
        <div class="card-body">
            <pre>{{ print_r($updateAmazon, true) }}</pre>
        </div>
    </div>
    <a href="{{ route('orders.index') }}" class="btn btn-secondary">Volver al listado de pedidos</a>
</div>
@endsection
