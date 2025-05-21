@extends('layouts.app')

@section('content')
<style>
    /* Header styles */
    .customer-header {
        background: linear-gradient(135deg, #3d6eea 0%, #6e47ef 100%);
        color: white;
        padding: 20px 30px;
        border-radius: 15px;
        margin-bottom: 25px;
        box-shadow: 0 4px 20px rgba(61, 110, 234, 0.15);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .customer-header h2 {
        font-size: 1.5rem;
        font-weight: 500;
        margin: 0;
    }
    
    .customer-header p {
        margin: 0;
        opacity: 0.9;
        font-size: 0.85rem;
    }
    
    .customer-avatar {
        width: 60px;
        height: 60px;
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #3d6eea 0%, #6e47ef 100%);
        color: white;
        font-size: 1.5rem;
        font-weight: 600;
        margin-right: 20px;
        box-shadow: 0 4px 15px rgba(61, 110, 234, 0.2);
    }
    
    /* Card styles */
    .detail-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 5px 15px rgba(61, 110, 234, 0.06);
        overflow: hidden;
        border: 1px solid rgba(230, 230, 250, 0.7);
        margin-bottom: 25px;
        transition: all 0.3s ease;
    }
    
    .detail-card:hover {
        box-shadow: 0 8px 20px rgba(61, 110, 234, 0.1);
        transform: translateY(-2px);
    }
    
    .detail-card-header {
        padding: 20px;
        border-bottom: 1px solid #f5f7fa;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    
    .detail-card-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #333;
        margin: 0;
    }
    
    .detail-card-body {
        padding: 20px;
    }
    
    .info-label {
        font-size: 0.85rem;
        color: #718096;
        margin-bottom: 5px;
        display: block;
    }
    
    .info-value {
        font-size: 1rem;
        color: #333;
        font-weight: 500;
        margin-bottom: 20px;
        display: block;
    }
    
    /* Order table styles */
    .order-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }
    
    .order-table th {
        padding: 16px 20px;
        font-weight: 600;
        color: #444;
        border-bottom: 1px solid #eee;
        background-color: #f9fafc;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .order-table td {
        padding: 16px 20px;
        vertical-align: middle;
        border-bottom: 1px solid #eee;
        font-size: 0.95rem;
        transition: background-color 0.2s ease;
    }
    
    .order-table tbody tr:hover {
        background-color: #f5f9ff;
    }
    
    /* Badge styles */
    .status-badge {
        padding: 6px 12px;
        border-radius: 50px;
        font-weight: 500;
        font-size: 0.85rem;
        display: inline-block;
    }
    
    .status-pendiente {
        background: linear-gradient(135deg, #f7b733 0%, #fc4a1a 100%);
        color: white;
    }
    
    .status-procesando {
        background: linear-gradient(135deg, #00c6ff 0%, #0072ff 100%);
        color: white;
    }
    
    .status-enviado {
        background: linear-gradient(135deg, #42b883 0%, #347474 100%);
        color: white;
    }
    
    .status-entregado {
        background: linear-gradient(135deg, #56ab2f 0%, #a8e063 100%);
        color: white;
    }
    
    .status-cancelado {
        background: linear-gradient(135deg, #cb356b 0%, #bd3f32 100%);
        color: white;
    }
    
    .status-error {
        background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%);
        color: white;
    }
    
    /* Button styles */
    .action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border-radius: 10px;
        color: #536080;
        background: #f7f9fc;
        transition: all 0.2s ease;
        border: none;
        margin-right: 5px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }
    
    .action-btn:hover {
        background: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        color: #3d6eea;
    }
</style>

<div class="container-fluid">
    <!-- Header section with gradient background -->
    <div class="customer-header">
        <div class="d-flex align-items-center">
            <div class="customer-avatar">
                {{ strtoupper(substr($customer->name, 0, 1)) }}
            </div>
            <div>
                <h2>{{ $customer->name }}</h2>
                <p>Detalle de cliente y sus pedidos</p>
            </div>
        </div>
        <a href="{{ route('customers.index') }}" class="btn btn-light rounded-pill px-4">
            <i class="bi bi-arrow-left me-2"></i>Volver al listado
        </a>
    </div>
    
    <div class="row">
        <div class="col-lg-4">
            <div class="detail-card">
                <div class="detail-card-header">
                    <h5 class="detail-card-title">Información de Contacto</h5>
                </div>
                <div class="detail-card-body">
                    <span class="info-label">Email</span>
                    <span class="info-value">{{ $customer->email }}</span>
                    
                    <span class="info-label">Dirección</span>
                    <span class="info-value">{{ $customer->address }}</span>
                    
                    <div class="d-flex align-items-center justify-content-between pt-3">
                        <span class="info-label mb-0">Total de pedidos</span>
                        <span class="badge rounded-pill" 
                              style="background: linear-gradient(135deg, #42b883 0%, #3d6eea 100%); 
                                     padding: 6px 12px; font-weight: 500; font-size: 0.85rem;">
                            {{ $customer->orders()->count() }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-8">
            <div class="detail-card">
                <div class="detail-card-header">
                    <h5 class="detail-card-title">Pedidos del Cliente</h5>
                </div>
                <div class="p-0">
                    <table class="order-table mb-0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Amazon Order ID</th>
                                <th class="text-center">Estado</th>
                                <th>Tracking</th>
                                <th>Creado</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse ($customer->orders as $order)
                            <tr>
                                <td><strong>#{{ $order->id }}</strong></td>
                                <td>
                                    <div style="max-width: 180px; overflow: hidden; text-overflow: ellipsis;">
                                        {{ $order->amazon_order_id }}
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="status-badge status-{{ $order->status }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td>
                                    @if($order->tracking_number)
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-truck me-2 text-muted"></i>
                                            {{ $order->tracking_number }}
                                        </div>
                                    @else
                                        <span class="text-muted">No disponible</span>
                                    @endif
                                </td>
                                <td>
                                    <div style="font-weight: 500;">{{ $order->created_at->format('d/m/Y') }}</div>
                                    <div style="font-size: 0.8rem; color: #718096;">{{ $order->created_at->format('H:i') }}</div>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('orders.show', $order->id) }}" class="action-btn" title="Ver detalles">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('orders.edit', $order->id) }}" class="action-btn" title="Editar pedido">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="bi bi-box text-muted mb-3" style="font-size: 2.5rem;"></i>
                                        <p class="mb-1" style="font-weight: 500; color: #444;">No hay pedidos para este cliente</p>
                                        <p class="text-muted" style="font-size: 0.9rem;">Los pedidos aparecerán aquí cuando se creen</p>
                                    </div>
                                </td>
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
