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
    
    /* Form styles */
    .form-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 5px 15px rgba(61, 110, 234, 0.06);
        overflow: hidden;
        border: 1px solid rgba(230, 230, 250, 0.7);
        margin-bottom: 25px;
        transition: all 0.3s ease;
    }
    
    .form-card:hover {
        box-shadow: 0 8px 20px rgba(61, 110, 234, 0.1);
        transform: translateY(-2px);
    }
    
    .form-card-header {
        padding: 18px 24px;
        border-bottom: 1px solid #eef2f7;
        background: linear-gradient(to right, #f9fafc, #f5f7ff);
        font-weight: 600;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .form-card-body {
        padding: 24px;
    }
    
    .form-control, .form-select {
        border-radius: 12px;
        border: 1px solid #e0e0e0;
        padding: 12px 16px;
        height: auto;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.02);
        transition: all 0.3s ease;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #3d6eea;
        box-shadow: 0 0 0 3px rgba(61, 110, 234, 0.1);
        outline: none;
    }
    
    .status-option {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 15px;
        padding: 15px;
        border-radius: 12px;
        border: 1px solid #eef2f7;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .status-option:hover {
        background-color: #f8faff;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
    }
    
    .status-option.selected {
        border-color: #3d6eea;
        background-color: #f5f7ff;
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
    
    .btn-primary {
        background: linear-gradient(135deg, #3d6eea 0%, #6e47ef 100%);
        border: none;
        padding: 10px 24px;
        border-radius: 12px;
        font-weight: 600;
        box-shadow: 0 4px 10px rgba(61, 110, 234, 0.2);
        transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(61, 110, 234, 0.3);
    }
    
    .btn-primary:active {
        transform: translateY(0);
    }
    
    .btn-outline-secondary {
        background: white;
        border: 1px solid #e5e7eb;
        color: #4b5563;
        transition: all 0.3s ease;
        border-radius: 12px;
        padding: 10px 24px;
    }
    
    .btn-outline-secondary:hover {
        background: #f9fafb;
        color: #3d6eea;
        border-color: #d1dcf8;
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
    }
    
    .tracking-input-container {
        display: none;
    }
    
    .tracking-input-container.show {
        display: block;
        animation: fadeIn 0.3s ease;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<!-- Header section with gradient background -->
<div class="orders-header">
    <div>
        <h2>Actualizar Estado de Pedido</h2>
        <p>Gestiona el estado y seguimiento del pedido #{{ $order->amazon_order_id }}</p>
    </div>
    <div>
        <a href="{{ route('orders.show', $order->id) }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i> Volver al Detalle
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8 mx-auto">
        <form action="{{ route('orders.update', $order->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-card mb-4">
                <div class="form-card-header">
                    <h5 class="mb-0">Estado Actual: <span class="status-badge status-{{ $order->status }}">{{ ucfirst($order->status) }}</span></h5>
                </div>
                <div class="form-card-body">
                    <div class="mb-4">
                        <p class="mb-3 fw-medium">Selecciona el nuevo estado del pedido:</p>
                        
                        <!-- Estado: Pendiente -->
                        <div class="status-option @if(old('status', $order->status) == 'pendiente') selected @endif">
                            <input type="radio" name="status" id="status_pendiente" value="pendiente" class="status-radio" @if(old('status', $order->status) == 'pendiente') checked @endif>
                            <label for="status_pendiente" class="d-flex align-items-center justify-content-between w-100">
                                <div>
                                    <span class="status-badge status-pendiente">Pendiente</span>
                                    <p class="mb-0 mt-2 text-muted">El pedido está pendiente de procesamiento</p>
                                </div>
                                <i class="bi bi-hourglass text-muted fs-4"></i>
                            </label>
                        </div>
                        
                        <!-- Estado: Procesando -->
                        <div class="status-option @if(old('status', $order->status) == 'procesando') selected @endif">
                            <input type="radio" name="status" id="status_procesando" value="procesando" class="status-radio" @if(old('status', $order->status) == 'procesando') checked @endif>
                            <label for="status_procesando" class="d-flex align-items-center justify-content-between w-100">
                                <div>
                                    <span class="status-badge status-procesando">Procesando</span>
                                    <p class="mb-0 mt-2 text-muted">El pedido está siendo procesado</p>
                                </div>
                                <i class="bi bi-gear text-muted fs-4"></i>
                            </label>
                        </div>
                        
                        <!-- Estado: Enviado -->
                        <div class="status-option @if(old('status', $order->status) == 'enviado') selected @endif">
                            <input type="radio" name="status" id="status_enviado" value="enviado" class="status-radio" @if(old('status', $order->status) == 'enviado') checked @endif>
                            <label for="status_enviado" class="d-flex align-items-center justify-content-between w-100">
                                <div>
                                    <span class="status-badge status-enviado">Enviado</span>
                                    <p class="mb-0 mt-2 text-muted">El pedido ha sido enviado al cliente</p>
                                </div>
                                <i class="bi bi-truck text-muted fs-4"></i>
                            </label>
                        </div>
                        
                        <!-- Tracking Number (aparece solo cuando el estado es "enviado") -->
                        <div class="tracking-input-container mt-3 mb-3 @if(old('status', $order->status) == 'enviado') show @endif" id="tracking_container">
                            <div class="alert alert-info">
                                <div class="d-flex">
                                    <i class="bi bi-info-circle me-2 fs-5"></i>
                                    <div>
                                        <h6 class="fw-bold mb-1">Número de seguimiento requerido</h6>
                                        <p class="mb-0">Al marcar el pedido como enviado, debes proporcionar un número de seguimiento.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="tracking_number" class="form-label">Número de seguimiento</label>
                                <input type="text" class="form-control @error('tracking_number') is-invalid @enderror" id="tracking_number" name="tracking_number" value="{{ old('tracking_number', $order->tracking_number) }}">
                                @error('tracking_number')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="carrier" class="form-label">Transportista</label>
                                <select class="form-select @error('carrier') is-invalid @enderror" id="carrier" name="carrier">
                                    <option value="">Seleccionar transportista...</option>
                                    <option value="bigbuy_logistics" {{ old('carrier', $order->carrier) == 'bigbuy_logistics' ? 'selected' : '' }}>BigBuy Logistics</option>
                                    <option value="ups" {{ old('carrier', $order->carrier) == 'ups' ? 'selected' : '' }}>UPS</option>
                                    <option value="dhl" {{ old('carrier', $order->carrier) == 'dhl' ? 'selected' : '' }}>DHL</option>
                                    <option value="correos" {{ old('carrier', $order->carrier) == 'correos' ? 'selected' : '' }}>Correos</option>
                                </select>
                                @error('carrier')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Estado: Entregado -->
                        <div class="status-option @if(old('status', $order->status) == 'entregado') selected @endif">
                            <input type="radio" name="status" id="status_entregado" value="entregado" class="status-radio" @if(old('status', $order->status) == 'entregado') checked @endif>
                            <label for="status_entregado" class="d-flex align-items-center justify-content-between w-100">
                                <div>
                                    <span class="status-badge status-entregado">Entregado</span>
                                    <p class="mb-0 mt-2 text-muted">El pedido ha sido entregado al cliente</p>
                                </div>
                                <i class="bi bi-check-circle text-muted fs-4"></i>
                            </label>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notas de actualización (opcional)</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Añade notas sobre la actualización de estado...">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>
            
            <div class="d-flex justify-content-end gap-3">
                <a href="{{ route('orders.show', $order->id) }}" class="btn btn-outline-secondary">Cancelar</a>
                <button type="submit" class="btn btn-primary">Actualizar Estado</button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const statusRadios = document.querySelectorAll('.status-radio');
        const statusOptions = document.querySelectorAll('.status-option');
        const trackingContainer = document.getElementById('tracking_container');
        
        // Marcar la opción seleccionada
        statusRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                // Quitar clase selected de todas las opciones
                statusOptions.forEach(option => {
                    option.classList.remove('selected');
                });
                
                // Añadir clase selected a la opción seleccionada
                this.closest('.status-option').classList.add('selected');
                
                // Mostrar/ocultar el contenedor de tracking
                if (this.value === 'enviado') {
                    trackingContainer.classList.add('show');
                } else {
                    trackingContainer.classList.remove('show');
                }
            });
        });
    });
</script>
@endsection
