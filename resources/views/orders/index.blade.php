@extends('layouts.app')
@section('content')
<style>
    /* Header styles */
    .orders-header {
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
    
    .orders-header h2 {
        font-size: 1.5rem;
        font-weight: 500;
        margin: 0;
    }
    
    .orders-header p {
        margin: 0;
        opacity: 0.9;
        font-size: 0.85rem;
    }
    
    .total-badge {
        background: rgba(255, 255, 255, 0.2);
        font-size: 0.85rem;
        padding: 4px 10px;
        border-radius: 20px;
        margin-left: 10px;
    }
    
    /* Action buttons */
    .header-actions .btn {
        border-radius: 50px;
        padding: 8px 20px;
        font-weight: 500;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }
    
    .header-actions .btn:hover {
        transform: translateY(-2px);
    }
    
    .header-actions .btn-primary {
        background: #fff;
        color: #3d6eea;
        border: none;
    }
    
    /* Filter section */
    .filter-section {
        background: white;
        border-radius: 15px;
        padding: 18px 24px;
        margin-bottom: 25px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.04);
    }
    
    .filter-section .form-control, 
    .filter-section .form-select {
        border-radius: 10px;
        border: 1px solid #e0e0e0;
        padding: 10px 15px;
        box-shadow: none;
        transition: all 0.3s ease;
    }
    
    .filter-section .form-control:focus,
    .filter-section .form-select:focus {
        border-color: #3d6eea;
        box-shadow: 0 0 0 3px rgba(61, 110, 234, 0.1);
    }
    
    .filter-section .btn {
        border-radius: 10px;
        padding: 10px 15px;
    }
    
    /* Orders table */
    .orders-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.04);
        margin-bottom: 20px;
        overflow: hidden;
        border: 1px solid rgba(230, 230, 230, 0.5);
    }
    
    .orders-table {
        width: 100%;
    }
    
    .orders-table th {
        padding: 16px 20px;
        font-weight: 600;
        color: #444;
        border-bottom: 1px solid #eee;
        background-color: #f9fafc;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .orders-table td {
        padding: 16px 20px;
        vertical-align: middle;
        border-bottom: 1px solid #eee;
        font-size: 0.95rem;
        transition: background-color 0.2s ease;
    }
    
    .orders-table tbody tr:hover {
        background-color: #f5f9ff;
    }
    
    .orders-table tr:last-child td {
        border-bottom: none;
    }
    
    .orders-table tr:hover {
        background-color: #f9fafc;
    }
    
    /* Status badges */
    .status-badge {
        display: inline-block;
        padding: 6px 14px;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: capitalize;
        letter-spacing: 0.3px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.07);
    }
    
    .status-pending {
        background: linear-gradient(135deg, #ffa726 0%, #ffb74d 100%);
        color: #fff;
    }
    
    .status-processing {
        background: linear-gradient(135deg, #3d6eea 0%, #6e47ef 100%);
        color: #fff;
    }
    
    .status-shipped {
        background: linear-gradient(135deg, #00c6fb 0%, #005bea 100%);
        color: #fff;
    }
    
    .status-delivered {
        background: linear-gradient(135deg, #42b883 0%, #338a68 100%);
        color: #fff;
    }
    
    .status-cancelled {
        background: linear-gradient(135deg, #ff6b6b 0%, #bf4158 100%);
        color: #fff;
    }
    
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
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }
    
    .action-btn:hover {
        background: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        color: #3d6eea;
    }
    
    .action-btn i {
        font-size: 1rem;
    }
    
    .pagination-container {
        margin-top: 20px;
        display: flex;
        justify-content: center;
    }
    
    .pagination {
        display: flex;
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .pagination li {
        margin: 0 5px;
    }
    
    .pagination a, .pagination span {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border-radius: 8px;
        color: #3d6eea;
        text-decoration: none;
        transition: all 0.2s;
    }
    
    .pagination a:hover {
        background: #f0f5ff;
    }
    
    .pagination .active span {
        background: #3d6eea;
        color: white;
    }
    
    .total-badge {
        font-size: 0.8rem;
        font-weight: 500;
        color: white;
        background: rgba(255,255,255,0.2);
        padding: 3px 10px;
        border-radius: 20px;
        margin-left: 10px;
    }
    
    .action-buttons .btn {
        border-radius: 8px;
        font-weight: 500;
        padding: 8px 16px;
        transition: all 0.2s;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #3d6eea 0%, #6e47ef 100%);
        border: none;
    }
    
    .btn-primary:hover {
        box-shadow: 0 4px 10px rgba(61, 110, 234, 0.25);
        transform: translateY(-1px);
    }
    
    .btn-outline-secondary {
        border: 1px solid #d1d5db;
        color: #4b5563;
    }
    
    .btn-outline-secondary:hover {
        background: #f9fafb;
        color: #111827;
    }
</style>

<!-- Header section with gradient background -->
<div class="orders-header d-flex justify-content-between align-items-center">
    <div>
        <h2>Gestión de Pedidos <span class="total-badge">{{ $orders->total() ?? 0 }}</span></h2>
        <p>Control y seguimiento de pedidos entre Amazon y BigBuy</p>
    </div>
    <div class="action-buttons">
        <a href="{{ route('orders.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg me-2"></i> Nuevo Pedido
        </a>
        <button class="btn btn-outline-secondary ms-2">
            <i class="bi bi-file-earmark-excel me-2"></i> Exportar
        </button>
    </div>
</div>

<!-- Filter section -->
<div class="filter-section">
    <form action="{{ route('orders.index') }}" method="GET" class="row">
        <div class="col-md-3 mb-3 mb-md-0">
            <input type="text" name="search" class="form-control" placeholder="Buscar por ID o cliente..." value="{{ request('search') }}">
        </div>
        <div class="col-md-2 mb-3 mb-md-0">
            <select name="status" class="form-select">
                <option value="">Estado: Todos</option>
                <option value="pendiente" {{ request('status') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                <option value="procesando" {{ request('status') == 'procesando' ? 'selected' : '' }}>Procesando</option>
                <option value="enviado" {{ request('status') == 'enviado' ? 'selected' : '' }}>Enviado</option>
                <option value="entregado" {{ request('status') == 'entregado' ? 'selected' : '' }}>Entregado</option>
                <option value="cancelado" {{ request('status') == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
            </select>
        </div>
        <div class="col-md-2 mb-3 mb-md-0">
            <select name="date_range" class="form-select">
                <option value="">Periodo: Todos</option>
                <option value="today" {{ request('date_range') == 'today' ? 'selected' : '' }}>Hoy</option>
                <option value="week" {{ request('date_range') == 'week' ? 'selected' : '' }}>Última semana</option>
                <option value="month" {{ request('date_range') == 'month' ? 'selected' : '' }}>Último mes</option>
                <option value="year" {{ request('date_range') == 'year' ? 'selected' : '' }}>Último año</option>
            </select>
        </div>
        <div class="col-md-2 mb-3 mb-md-0">
            <select name="source" class="form-select">
                <option value="">Origen: Todos</option>
                <option value="amazon" {{ request('source') == 'amazon' ? 'selected' : '' }}>Amazon</option>
                <option value="manual" {{ request('source') == 'manual' ? 'selected' : '' }}>Manual</option>
            </select>
        </div>
        <div class="col-md-3">
            <div class="d-flex">
                <button type="submit" class="btn btn-primary flex-grow-1 me-2">
                    <i class="bi bi-search me-2"></i> Filtrar
                </button>
                <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x-lg"></i>
                </a>
            </div>
        </div>
    </form>
</div>

<!-- Orders table -->
<div class="orders-card">
    <div class="table-responsive">
        <table class="orders-table">
            <thead>
                <tr>
                    <th>ID de Pedido</th>
                    <th>Cliente</th>
                    <th>Fecha</th>
                    <th>Total</th>
                    <th>Estado</th>
                    <th>Tracking</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr>
                    <td>
                        <a href="{{ route('orders.show', $order->id) }}" class="text-decoration-none fw-medium text-body">
                            {{ $order->amazon_order_id }}
                        </a>
                    </td>
                    <td>
                        @if($order->customer)
                            <span class="fw-medium">{{ $order->customer->name }}</span>
                        @else
                            <span class="text-muted">Cliente no asignado</span>
                        @endif
                    </td>
                    <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        €{{ number_format($order->items->sum(function($item) {
                            return $item->price * $item->quantity;
                        }), 2) }}
                    </td>
                    <td>
                        <span class="status-badge {{ 'status-' . strtolower(str_replace(' ', '-', $order->status)) }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td>
                        @if($order->tracking_number)
                            <span class="d-inline-block text-truncate" style="max-width: 120px;" title="{{ $order->tracking_number }}">
                                {{ $order->tracking_number }}
                            </span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <div class="d-flex justify-content-end">
                            <a href="{{ route('orders.show', $order->id) }}" class="action-btn me-1" title="Ver detalles">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('orders.edit', $order->id) }}" class="action-btn me-1" title="Editar">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <button type="button" class="action-btn" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="#">
                                        <i class="bi bi-truck me-2 text-muted"></i> Actualizar envío
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#">
                                        <i class="bi bi-printer me-2 text-muted"></i> Imprimir etiqueta
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#">
                                        <i class="bi bi-file-earmark-pdf me-2 text-muted"></i> Generar factura
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <button class="dropdown-item text-danger" type="button" data-bs-toggle="modal" data-bs-target="#cancelOrderModal" data-order-id="{{ $order->id }}">
                                        <i class="bi bi-x-circle me-2"></i> Cancelar pedido
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4">
                        <div class="d-flex flex-column align-items-center">
                            <i class="bi bi-inbox text-muted mb-2" style="font-size: 2rem;"></i>
                            <p class="text-muted mb-0">No se encontraron pedidos</p>
                            <p class="text-muted fs-sm">Intenta cambiar los filtros o crea un nuevo pedido</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
<div class="pagination-container">
    {{ $orders->withQueryString()->links() }}
</div>

<!-- Cancel Order Modal -->
<div class="modal fade" id="cancelOrderModal" tabindex="-1" aria-labelledby="cancelOrderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 15px;">
            <div class="modal-header">
                <h5 class="modal-title" id="cancelOrderModalLabel">Cancelar Pedido</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro de que deseas cancelar este pedido? Esta acción no se puede deshacer.</p>
                <form id="cancelOrderForm" method="POST" action="">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="cancel_reason" class="form-label">Motivo de la cancelación</label>
                        <select class="form-select" id="cancel_reason" name="cancel_reason" required>
                            <option value="">Selecciona un motivo</option>
                            <option value="cliente_solicita">Cliente solicita cancelación</option>
                            <option value="sin_stock">Sin stock disponible</option>
                            <option value="fraude">Potencial fraude</option>
                            <option value="error_precio">Error en precio</option>
                            <option value="otro">Otro motivo</option>
                        </select>
                    </div>
                    <div class="mb-3 d-none" id="other_reason_container">
                        <label for="other_reason" class="form-label">Especifica el motivo</label>
                        <textarea class="form-control" id="other_reason" name="other_reason" rows="2"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="confirmCancelButton">Confirmar cancelación</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Show "other reason" textarea when "Otro motivo" is selected
        const cancelReasonSelect = document.getElementById('cancel_reason');
        const otherReasonContainer = document.getElementById('other_reason_container');
        
        cancelReasonSelect.addEventListener('change', function() {
            if (this.value === 'otro') {
                otherReasonContainer.classList.remove('d-none');
            } else {
                otherReasonContainer.classList.add('d-none');
            }
        });
        
        // Handle order cancellation
        const cancelOrderModal = document.getElementById('cancelOrderModal');
        const cancelOrderForm = document.getElementById('cancelOrderForm');
        const confirmCancelButton = document.getElementById('confirmCancelButton');
        
        cancelOrderModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const orderId = button.getAttribute('data-order-id');
            cancelOrderForm.action = `/orders/${orderId}/cancel`;
        });
        
        confirmCancelButton.addEventListener('click', function() {
            if (cancelOrderForm.checkValidity()) {
                cancelOrderForm.submit();
            } else {
                cancelOrderForm.reportValidity();
            }
        });
    });
</script>
@endsection
