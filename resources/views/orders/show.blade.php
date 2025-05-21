@extends('layouts.app')
@section('content')
<style>
    /* Header styles */
    .order-header {
        background: linear-gradient(135deg, #3d6eea 0%, #6e47ef 100%);
        color: white;
        padding: 20px 30px;
        border-radius: 15px;
        margin-bottom: 25px;
        box-shadow: 0 4px 20px rgba(61, 110, 234, 0.15);
    }
    
    .status-badge {
        display: inline-block;
        padding: 6px 14px;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: capitalize;
        letter-spacing: 0.3px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.07);
        color: #fff;
    }
    
    .status-pendiente {
        background: linear-gradient(135deg, #ffa726 0%, #ffb74d 100%);
    }
    
    .status-procesando {
        background: linear-gradient(135deg, #3d6eea 0%, #6e47ef 100%);
    }
    
    .status-enviado {
        background: linear-gradient(135deg, #00c6fb 0%, #005bea 100%);
    }
    
    .status-entregado {
        background: linear-gradient(135deg, #42b883 0%, #338a68 100%);
    }
    
    .status-cancelado {
        background: linear-gradient(135deg, #ff6b6b 0%, #bf4158 100%);
    }
    
    /* Card styles */
    .order-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 5px 15px rgba(61, 110, 234, 0.06);
        overflow: hidden;
        margin-bottom: 25px;
        border: 1px solid rgba(230, 230, 250, 0.7);
        transition: all 0.3s ease;
    }
    
    .order-card:hover {
        box-shadow: 0 8px 20px rgba(61, 110, 234, 0.1);
        transform: translateY(-2px);
    }
    
    .order-card-header {
        padding: 18px 24px;
        border-bottom: 1px solid #eef2f7;
        background: linear-gradient(to right, #f9fafc, #f5f7ff);
        font-weight: 600;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .order-card-body {
        padding: 24px;
    }
    
    .order-detail-label {
        color: #718096;
        font-size: 0.875rem;
        margin-bottom: 5px;
    }
    
    .order-detail-value {
        font-weight: 500;
        margin-bottom: 15px;
    }
    
    .timeline {
        position: relative;
        margin-left: 30px;
    }
    
    .timeline::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 2px;
        background-color: #e5e7eb;
        transform: translateX(-50%);
    }
    
    .timeline-item {
        position: relative;
        padding-left: 30px;
        padding-bottom: 25px;
    }
    
    .timeline-item:last-child {
        padding-bottom: 0;
    }
    
    .timeline-dot {
        position: absolute;
        left: 0;
        top: 0;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background-color: #3d6eea;
        transform: translateX(-50%);
        z-index: 1;
    }
    
    .timeline-dot.dot-active {
        background-color: #00a843;
    }
    
    .timeline-dot.dot-error {
        background-color: #ef4444;
    }
    
    .timeline-content {
        background-color: #f9fafc;
        border-radius: 8px;
        padding: 15px;
    }
    
    .timeline-date {
        font-size: 0.75rem;
        color: #718096;
        margin-top: 5px;
    }
    
    .order-items-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }
    
    .order-items-table th {
        background-color: #f9fafc;
        color: #718096;
        font-weight: 500;
        text-align: left;
        padding: 12px 15px;
        font-size: 0.875rem;
        border-bottom: 1px solid #eee;
    }
    
    .order-items-table td {
        padding: 15px;
        border-bottom: 1px solid #eee;
        vertical-align: middle;
    }
    
    .order-items-table tr:last-child td {
        border-bottom: none;
    }
    
    .product-image {
        width: 60px;
        height: 60px;
        border-radius: 8px;
        object-fit: cover;
        margin-right: 15px;
    }
    
    .product-info {
        display: flex;
        align-items: center;
    }
    
    .product-name {
        font-weight: 500;
        color: #111827;
        font-size: 0.875rem;
    }
    
    .product-sku {
        color: #718096;
        font-size: 0.75rem;
        margin-top: 4px;
    }
    
    .order-total-section {
        background-color: #f9fafc;
        padding: 15px;
        border-radius: 8px;
        margin-top: 15px;
    }
    
    .order-total-row {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
    }
    
    .order-total-label {
        color: #718096;
        font-size: 0.875rem;
    }
    
    .order-total-value {
        font-weight: 500;
    }
    
    .order-total-final {
        font-weight: 600;
        font-size: 1.125rem;
        color: #111827;
    }
    
    .action-buttons .btn {
        border-radius: 12px;
        font-weight: 600;
        padding: 10px 20px;
        transition: all 0.3s ease;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #3d6eea 0%, #6e47ef 100%);
        border: none;
        box-shadow: 0 4px 10px rgba(61, 110, 234, 0.2);
    }
    
    .btn-primary:hover {
        box-shadow: 0 6px 15px rgba(61, 110, 234, 0.3);
        transform: translateY(-2px);
    }
    
    .btn-primary:active {
        transform: translateY(0);
    }
    
    .btn-outline-secondary {
        background: white;
        border: 1px solid #e5e7eb;
        color: #4b5563;
        transition: all 0.3s ease;
    }
    
    .btn-outline-secondary:hover {
        background: #f9fafb;
        color: #3d6eea;
        border-color: #d1dcf8;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
    }
    
    .order-notes-section {
        margin-top: 20px;
    }
    
    .order-note {
        background: linear-gradient(to right, #f9fafc, #f5f7ff);
        border-radius: 12px;
        padding: 18px;
        margin-bottom: 16px;
        border: 1px solid rgba(230, 230, 250, 0.7);
        box-shadow: 0 2px 8px rgba(61, 110, 234, 0.05);
        transition: all 0.3s ease;
    }
    
    .order-note:hover {
        box-shadow: 0 4px 12px rgba(61, 110, 234, 0.08);
        transform: translateY(-2px);
    }
    
    .order-note-author {
        font-weight: 600;
        margin-bottom: 6px;
        color: #3d6eea;
    }
    
    .order-note-date {
        font-size: 0.75rem;
        color: #718096;
        margin-bottom: 12px;
        opacity: 0.8;
    }
    
    .order-note-content {
        font-size: 0.9rem;
        line-height: 1.5;
    }
    
    .input-note {
        border-radius: 12px;
        border: 1px solid #e0e0e0;
        padding: 14px 18px;
        width: 100%;
        margin-bottom: 14px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.02);
        transition: all 0.3s ease;
        font-size: 0.95rem;
    }
    
    .input-note:focus {
        border-color: #3d6eea;
        box-shadow: 0 0 0 3px rgba(61, 110, 234, 0.1);
        outline: none;
    }
</style>

<!-- Header with order info and actions -->
<div class="order-header d-flex justify-content-between align-items-center mb-4">
    <div>
        <div class="d-flex align-items-center mb-2">
            <a href="{{ route('orders.index') }}" class="text-white me-3">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h2 class="mb-0">Pedido #{{ $order->amazon_order_id }}</h2>
            <span class="status-badge ms-3 {{ 'status-' . $order->status }}">
                {{ ucfirst($order->status) }}
            </span>
        </div>
        <p>Creado el {{ $order->created_at->format('d/m/Y H:i') }}</p>
    </div>
    <div class="action-buttons">
        <div class="dropdown d-inline-block me-2">
            <button class="btn btn-light dropdown-toggle" type="button" id="orderActionsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-gear me-2"></i> Acciones
            </button>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="orderActionsDropdown">
                <li><a class="dropdown-item" href="#"><i class="bi bi-truck me-2 text-muted"></i> Actualizar envío</a></li>
                <li><a class="dropdown-item" href="#"><i class="bi bi-printer me-2 text-muted"></i> Imprimir etiqueta</a></li>
                <li><a class="dropdown-item" href="#"><i class="bi bi-file-earmark-pdf me-2 text-muted"></i> Generar factura</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item text-danger" href="#" data-bs-toggle="modal" data-bs-target="#cancelOrderModal"><i class="bi bi-x-circle me-2"></i> Cancelar pedido</a></li>
            </ul>
        </div>
        <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-primary">
            <i class="bi bi-pencil me-2"></i> Editar
        </a>
    </div>
</div>

<div class="row">
    <!-- Left column - Order details and customer info -->
    <div class="col-md-8">
        <!-- Order items card -->
        <div class="order-card mb-4">
            <div class="order-card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Artículos del pedido</h5>
                <span class="badge bg-secondary">{{ $order->items->count() }} artículos</span>
            </div>
            <div class="order-card-body p-0">
                <div class="table-responsive">
                    <table class="order-items-table">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th class="text-center">Cantidad</th>
                                <th class="text-end">Precio</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr>
                                <td>
                                    <div class="product-info">
                                        <img src="https://via.placeholder.com/60" class="product-image" alt="Producto">
                                        <div>
                                            <div class="product-name">{{ $item->name ?? 'Producto ' . $loop->iteration }}</div>
                                            <div class="product-sku">SKU: {{ $item->sku }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">{{ $item->quantity }}</td>
                                <td class="text-end">€{{ number_format($item->price, 2) }}</td>
                                <td class="text-end">€{{ number_format($item->price * $item->quantity, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Order total calculations -->
                <div class="order-total-section mx-3 mb-3">
                    <div class="order-total-row">
                        <div class="order-total-label">Subtotal</div>
                        <div class="order-total-value">€{{ number_format($order->items->sum(function($item) { return $item->price * $item->quantity; }), 2) }}</div>
                    </div>
                    <div class="order-total-row">
                        <div class="order-total-label">Impuestos (21%)</div>
                        <div class="order-total-value">€{{ number_format($order->items->sum(function($item) { return $item->price * $item->quantity * 0.21; }), 2) }}</div>
                    </div>
                    <div class="order-total-row">
                        <div class="order-total-label">Envío</div>
                        <div class="order-total-value">€0.00</div>
                    </div>
                    <hr>
                    <div class="order-total-row">
                        <div class="order-total-label order-total-final">Total</div>
                        <div class="order-total-value order-total-final">
                            €{{ number_format($order->items->sum(function($item) { 
                                return ($item->price * $item->quantity) * 1.21;
                            }), 2) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Customer info -->
        <div class="order-card mb-4">
            <div class="order-card-header">
                <h5 class="mb-0">Información del cliente</h5>
            </div>
            <div class="order-card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="order-detail-label">Nombre</div>
                        <div class="order-detail-value">{{ $order->customer->name ?? 'No disponible' }}</div>
                        
                        <div class="order-detail-label">Email</div>
                        <div class="order-detail-value">{{ $order->customer->email ?? 'No disponible' }}</div>
                        
                        <div class="order-detail-label">Teléfono</div>
                        <div class="order-detail-value">{{ $order->customer->phone ?? 'No disponible' }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="order-detail-label">Dirección de envío</div>
                        <div class="order-detail-value">{{ $order->customer->address ?? 'No disponible' }}</div>
                        
                        <div class="order-detail-label">País</div>
                        <div class="order-detail-value">{{ $order->customer->country ?? 'España' }}</div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Order notes -->
        <div class="order-card mb-4">
            <div class="order-card-header">
                <h5 class="mb-0">Notas del pedido</h5>
            </div>
            <div class="order-card-body">
                <div class="order-notes-section">
                    @if(isset($order->logs) && $order->logs->count() > 0)
                        @foreach($order->logs as $log)
                        <div class="order-note">
                            <div class="order-note-author">{{ $log->user->name ?? 'Sistema' }}</div>
                            <div class="order-note-date">{{ $log->created_at->format('d/m/Y H:i') }}</div>
                            <div class="order-note-content">{{ $log->description }}</div>
                        </div>
                        @endforeach
                    @else
                        <p class="text-muted">No hay notas para este pedido.</p>
                    @endif
                    
                    <form action="{{ route('orders.addNote', $order->id) }}" method="POST" class="mt-3">
                        @csrf
                        <textarea name="note" class="input-note" rows="3" placeholder="Añadir una nota..."></textarea>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-plus-lg me-2"></i> Añadir nota
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Right column - Order timeline and shipping info -->
    <div class="col-md-4">
        <!-- Order timeline -->
        <div class="order-card mb-4">
            <div class="order-card-header">
                <h5 class="mb-0">Seguimiento del pedido</h5>
            </div>
            <div class="order-card-body">
                <div class="timeline">
                    <div class="timeline-item">
                        <div class="timeline-dot dot-active"></div>
                        <div class="timeline-content">
                            <strong>Pedido recibido</strong>
                            <p class="mb-0">Pedido recibido desde Amazon</p>
                        </div>
                        <div class="timeline-date">{{ $order->created_at->format('d/m/Y H:i') }}</div>
                    </div>
                    
                    <div class="timeline-item">
                        <div class="timeline-dot {{ $order->status != 'pendiente' ? 'dot-active' : '' }}"></div>
                        <div class="timeline-content">
                            <strong>Procesando</strong>
                            <p class="mb-0">Pedido en preparación</p>
                        </div>
                        <div class="timeline-date">
                            @if($order->status != 'pendiente')
                                {{ $order->updated_at->format('d/m/Y H:i') }}
                            @else
                                Pendiente
                            @endif
                        </div>
                    </div>
                    
                    <div class="timeline-item">
                        <div class="timeline-dot {{ in_array($order->status, ['enviado', 'entregado']) ? 'dot-active' : '' }}"></div>
                        <div class="timeline-content">
                            <strong>Enviado</strong>
                            <p class="mb-0">Pedido enviado por BigBuy</p>
                            @if($order->tracking_number)
                                <p class="mb-0 mt-1">Tracking: <strong>{{ $order->tracking_number }}</strong></p>
                            @endif
                        </div>
                        <div class="timeline-date">
                            @if(in_array($order->status, ['enviado', 'entregado']))
                                {{ $order->updated_at->format('d/m/Y H:i') }}
                            @else
                                Pendiente
                            @endif
                        </div>
                    </div>
                    
                    <div class="timeline-item">
                        <div class="timeline-dot {{ $order->status == 'entregado' ? 'dot-active' : '' }}"></div>
                        <div class="timeline-content">
                            <strong>Entregado</strong>
                            <p class="mb-0">Pedido entregado al cliente</p>
                        </div>
                        <div class="timeline-date">
                            @if($order->status == 'entregado')
                                {{ $order->updated_at->format('d/m/Y H:i') }}
                            @else
                                Pendiente
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Shipping info -->
        <div class="order-card mb-4">
            <div class="order-card-header">
                <h5 class="mb-0">Información de envío</h5>
            </div>
            <div class="order-card-body">
                <div class="order-detail-label">Método de envío</div>
                <div class="order-detail-value">Estándar</div>
                
                <div class="order-detail-label">Transportista</div>
                <div class="order-detail-value">{{ $order->tracking_number ? 'BigBuy Logistics' : 'Pendiente' }}</div>
                
                <div class="order-detail-label">Número de seguimiento</div>
                <div class="order-detail-value">
                    @if($order->tracking_number)
                        <div class="d-flex align-items-center">
                            <span>{{ $order->tracking_number }}</span>
                            <button class="btn btn-sm btn-outline-secondary ms-2" onclick="copyToClipboard('{{ $order->tracking_number }}')">
                                <i class="bi bi-clipboard"></i>
                            </button>
                        </div>
                    @else
                        <span class="text-muted">Pendiente de asignación</span>
                    @endif
                </div>
                
                <div class="order-detail-label">Fecha estimada de entrega</div>
                <div class="order-detail-value">
                    @if(in_array($order->status, ['enviado', 'entregado']))
                        {{ now()->addDays(3)->format('d/m/Y') }}
                    @else
                        <span class="text-muted">Pendiente</span>
                    @endif
                </div>
                
                @if($order->tracking_number && $order->status != 'entregado')
                    <a href="#" class="btn btn-primary w-100 mt-3">
                        <i class="bi bi-truck me-2"></i> Seguir envío
                    </a>
                @endif
            </div>
        </div>
        
        <!-- Order details -->
        <div class="order-card">
            <div class="order-card-header">
                <h5 class="mb-0">Detalles del pedido</h5>
            </div>
            <div class="order-card-body">
                <div class="order-detail-label">ID Pedido Amazon</div>
                <div class="order-detail-value">{{ $order->amazon_order_id }}</div>
                
                <div class="order-detail-label">ID Pedido BigBuy</div>
                <div class="order-detail-value">{{ $order->bigbuy_order_id ?? 'No asignado' }}</div>
                
                <div class="order-detail-label">Fecha de creación</div>
                <div class="order-detail-value">{{ $order->created_at->format('d/m/Y H:i') }}</div>
                
                <div class="order-detail-label">Última actualización</div>
                <div class="order-detail-value">{{ $order->updated_at->format('d/m/Y H:i') }}</div>
                
                <div class="order-detail-label">Método de pago</div>
                <div class="order-detail-value">{{ $order->payment_method ?? 'Amazon Payments' }}</div>
            </div>
        </div>
    </div>
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
                <form id="cancelOrderForm" method="POST" action="{{ route('orders.cancel', $order->id) }}">
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
        // Copy tracking number to clipboard
        window.copyToClipboard = function(text) {
            navigator.clipboard.writeText(text).then(function() {
                alert('Número de seguimiento copiado al portapapeles');
            }, function() {
                alert('No se pudo copiar el número de seguimiento');
            });
        }
        
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
        const confirmCancelButton = document.getElementById('confirmCancelButton');
        const cancelOrderForm = document.getElementById('cancelOrderForm');
        
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
