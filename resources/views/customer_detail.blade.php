@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card mb-3">
                <div class="card-header bg-primary text-white">
                    <strong>Cliente:</strong> {{ $customer->name }}
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-3">Email</dt>
                        <dd class="col-sm-9">{{ $customer->email }}</dd>
                        <dt class="col-sm-3">Dirección</dt>
                        <dd class="col-sm-9">{{ $customer->address }}</dd>
                    </dl>
                </div>
            </div>
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    Pedidos de {{ $customer->name }}
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Amazon Order ID</th>
                                <th>Estado</th>
                                <th>Tracking</th>
                                <th>Creado</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse ($customer->orders as $order)
                            <tr>
                                <td>{{ $order->id }}</td>
                                <td>{{ $order->amazon_order_id }}</td>
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
                                <td>{{ $order->tracking_number }}</td>
                                <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                                <td>
                                    <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No hay pedidos para este cliente.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="mt-3">
                <a href="{{ route('customers.index') }}" class="btn btn-secondary">Volver al listado de clientes</a>
            </div>
        </div>
    </div>
</div>
@endsection
