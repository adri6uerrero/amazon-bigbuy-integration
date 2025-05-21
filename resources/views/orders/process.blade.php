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
    
    /* Process result styles */
    .result-card {
        display: none;
    }
    
    .result-card.show {
        display: block;
        animation: fadeIn 0.5s ease;
    }
    
    .result-success {
        background: linear-gradient(to right, #f0f9ff, #e6f7f2);
        border-left: 4px solid #42b883;
    }
    
    .result-error {
        background: linear-gradient(to right, #fff5f5, #feecef);
        border-left: 4px solid #ff6b6b;
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
    
    .step-item {
        display: flex;
        position: relative;
        padding-bottom: 30px;
    }
    
    .step-item:last-child {
        padding-bottom: 0;
    }
    
    .step-item::before {
        content: '';
        position: absolute;
        left: 15px;
        top: 30px;
        bottom: 0;
        width: 2px;
        background: #e5e7eb;
        z-index: 1;
    }
    
    .step-item:last-child::before {
        display: none;
    }
    
    .step-circle {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
        background: white;
        border: 2px solid #3d6eea;
        color: #3d6eea;
        z-index: 2;
        box-shadow: 0 0 0 4px rgba(61, 110, 234, 0.1);
    }
    
    .step-content {
        flex: 1;
    }
    
    .step-title {
        font-weight: 600;
        color: #3d6eea;
        margin-bottom: 6px;
    }
    
    .step-desc {
        color: #6b7280;
        font-size: 0.9rem;
    }
    
    /* Animation */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    /* Loading animation */
    .loading-indicator {
        display: none;
        text-align: center;
        padding: 30px 0;
    }
    
    .loading-indicator.show {
        display: block;
    }
    
    .loading-spinner {
        width: 40px;
        height: 40px;
        margin: 0 auto 15px;
        border: 4px solid rgba(61, 110, 234, 0.1);
        border-radius: 50%;
        border-top-color: #3d6eea;
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>

<!-- Header section with gradient background -->
<div class="orders-header">
    <div>
        <h2>Procesamiento de Pedidos Amazon</h2>
        <p>Importa y procesa pedidos de Amazon hacia BigBuy</p>
    </div>
    <div>
        <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i> Volver a Pedidos
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-7">
        <div class="form-card mb-4">
            <div class="form-card-header">
                <h5 class="mb-0">Procesamiento de Pedido</h5>
            </div>
            <div class="form-card-body">
                <form id="processOrderForm">
                    @csrf
                    <div class="mb-4">
                        <label for="amazon_order_id" class="form-label">ID de Pedido de Amazon</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="amazon_order_id" name="amazon_order_id" placeholder="Ej: 123-1234567-1234567" required>
                            <button class="btn btn-primary" type="submit" id="processButton">
                                <i class="bi bi-arrow-repeat me-2"></i> Procesar Pedido
                            </button>
                        </div>
                        <div class="form-text">Introduce el ID del pedido de Amazon que deseas procesar</div>
                    </div>
                </form>
                
                <!-- Indicador de carga -->
                <div class="loading-indicator" id="loadingIndicator">
                    <div class="loading-spinner"></div>
                    <p class="text-muted">Procesando pedido, espera un momento...</p>
                </div>
                
                <!-- Resultados -->
                <div class="result-card p-4 rounded-3 mb-3" id="resultSuccess">
                    <div class="d-flex align-items-start">
                        <div class="bg-white p-2 rounded-circle me-3 text-success">
                            <i class="bi bi-check-circle-fill fs-4"></i>
                        </div>
                        <div>
                            <h5 class="mb-2">¡Pedido procesado correctamente!</h5>
                            <p class="mb-2">El pedido ha sido importado y procesado con éxito.</p>
                            <div class="d-flex flex-wrap gap-2 mt-3">
                                <a href="#" id="viewOrderLink" class="btn btn-sm btn-primary">
                                    <i class="bi bi-eye me-1"></i> Ver Pedido
                                </a>
                                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="resetForm()">
                                    <i class="bi bi-arrow-counterclockwise me-1"></i> Procesar Otro
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="result-card p-4 rounded-3 mb-3" id="resultError">
                    <div class="d-flex align-items-start">
                        <div class="bg-white p-2 rounded-circle me-3 text-danger">
                            <i class="bi bi-exclamation-circle-fill fs-4"></i>
                        </div>
                        <div>
                            <h5 class="mb-2">Error al procesar el pedido</h5>
                            <p class="mb-2" id="errorMessage">No se pudo procesar el pedido. Verifica el ID e inténtalo de nuevo.</p>
                            <button type="button" class="btn btn-sm btn-outline-secondary mt-3" onclick="resetForm()">
                                <i class="bi bi-arrow-counterclockwise me-1"></i> Intentar Nuevamente
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-5">
        <div class="form-card mb-4">
            <div class="form-card-header">
                <h5 class="mb-0">Flujo de Procesamiento</h5>
            </div>
            <div class="form-card-body">
                <div class="steps-container">
                    <div class="step-item">
                        <div class="step-circle">1</div>
                        <div class="step-content">
                            <div class="step-title">Obtención de datos</div>
                            <div class="step-desc">Se recuperan los datos del pedido desde Amazon</div>
                        </div>
                    </div>
                    <div class="step-item">
                        <div class="step-circle">2</div>
                        <div class="step-content">
                            <div class="step-title">Creación del cliente</div>
                            <div class="step-desc">Se crea o actualiza la información del cliente</div>
                        </div>
                    </div>
                    <div class="step-item">
                        <div class="step-circle">3</div>
                        <div class="step-content">
                            <div class="step-title">Registro del pedido</div>
                            <div class="step-desc">Se crea el pedido en nuestra base de datos</div>
                        </div>
                    </div>
                    <div class="step-item">
                        <div class="step-circle">4</div>
                        <div class="step-content">
                            <div class="step-title">Envío a BigBuy</div>
                            <div class="step-desc">Se envía el pedido a BigBuy para su procesamiento</div>
                        </div>
                    </div>
                    <div class="step-item">
                        <div class="step-circle">5</div>
                        <div class="step-content">
                            <div class="step-title">Actualización de seguimiento</div>
                            <div class="step-desc">Se actualiza el pedido con la información de seguimiento</div>
                        </div>
                    </div>
                </div>
                
                <div class="alert alert-info mt-4">
                    <div class="d-flex">
                        <i class="bi bi-info-circle me-2"></i>
                        <div>
                            <h6 class="fw-bold mb-1">Información</h6>
                            <p class="mb-0">Este proceso puede tardar unos segundos en completarse.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('processOrderForm');
        const loadingIndicator = document.getElementById('loadingIndicator');
        const resultSuccess = document.getElementById('resultSuccess');
        const resultError = document.getElementById('resultError');
        const errorMessage = document.getElementById('errorMessage');
        const viewOrderLink = document.getElementById('viewOrderLink');
        
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Mostrar indicador de carga
            loadingIndicator.classList.add('show');
            
            // Ocultar resultados anteriores
            resultSuccess.classList.remove('show');
            resultError.classList.remove('show');
            
            // Obtener el ID del pedido
            const amazonOrderId = document.getElementById('amazon_order_id').value;
            
            // Simular procesamiento (en producción usaría fetch)
            setTimeout(function() {
                loadingIndicator.classList.remove('show');
                
                // En producción, aquí iría el código para procesar el pedido con la API real
                // Por ahora, simularemos una respuesta exitosa
                if (amazonOrderId.match(/\d{3}-\d{7}-\d{7}/)) {
                    resultSuccess.classList.add('show');
                    // En producción: viewOrderLink.href = '/orders/' + response.order_id;
                    viewOrderLink.href = "{{ route('orders.index') }}";
                } else {
                    resultError.classList.add('show');
                    errorMessage.textContent = "El formato del ID de pedido de Amazon no es válido. Debe tener el formato: 123-1234567-1234567";
                }
            }, 2000);
        });
    });
    
    function resetForm() {
        document.getElementById('amazon_order_id').value = '';
        document.getElementById('resultSuccess').classList.remove('show');
        document.getElementById('resultError').classList.remove('show');
        document.getElementById('amazon_order_id').focus();
    }
</script>
@endsection
