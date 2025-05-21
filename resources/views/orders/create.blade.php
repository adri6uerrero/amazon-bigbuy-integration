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
        box-shadow: 0 0 0 0.25rem rgba(61, 110, 234, 0.15);
    }
    
    .form-label {
        font-weight: 500;
        margin-bottom: 8px;
        color: #374151;
    }
    
    .form-text {
        font-size: 0.75rem;
        color: #718096;
    }
    
    .product-item {
        background-color: #f9fafc;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 15px;
        position: relative;
    }
    
    .product-item .remove-item {
        position: absolute;
        top: 10px;
        right: 10px;
        background: none;
        border: none;
        color: #ef4444;
        cursor: pointer;
        font-size: 1.25rem;
        line-height: 1;
        opacity: 0.7;
        transition: opacity 0.2s;
    }
    
    .product-item .remove-item:hover {
        opacity: 1;
    }
    
    .add-product-btn {
        background: linear-gradient(to right, #f5f7ff, #eef2f7);
        color: #3d6eea;
        border: 1px dashed #3d6eea;
        border-radius: 12px;
        padding: 14px 18px;
        margin-top: 20px;
        width: 100%;
        transition: all 0.3s ease;
        font-weight: 600;
        box-shadow: 0 2px 5px rgba(61, 110, 234, 0.1);
    }
    
    .add-product-btn:hover {
        background: linear-gradient(to right, #edf2fd, #e5ecff);
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(61, 110, 234, 0.15);
    }
    
    .add-product-btn:active {
        transform: translateY(0);
        border-color: #3d6eea;
        color: #3d6eea;
    }
    
    .action-buttons .btn {
        border-radius: 12px;
        font-weight: 600;
        padding: 10px 24px;
        box-shadow: 0 4px 10px rgba(61, 110, 234, 0.2);
        transition: all 0.3s ease;
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
</style>

<!-- Header section with gradient background -->
<div class="orders-header d-flex justify-content-between align-items-center">
    <div>
        <div class="d-flex align-items-center mb-2">
            <a href="{{ route('orders.index') }}" class="text-white me-3">
                <i class="bi bi-arrow-left"></i>
            </a>
            <h2>Crear Nuevo Pedido</h2>
        </div>
        <p>Introducir manualmente un nuevo pedido en el sistema</p>
    </div>
</div>

<form action="{{ route('orders.store') }}" method="POST">
    @csrf
    
    <div class="row">
        <!-- Left column -->
        <div class="col-md-8">
            <!-- Order Information -->
            <div class="form-card mb-4">
                <div class="form-card-header">
                    <h5 class="mb-0">Información del Pedido</h5>
                </div>
                <div class="form-card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="amazon_order_id" class="form-label">ID de Pedido Amazon</label>
                            <input type="text" class="form-control @error('amazon_order_id') is-invalid @enderror" id="amazon_order_id" name="amazon_order_id" value="{{ old('amazon_order_id') }}" required>
                            @error('amazon_order_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Introduce el ID exacto del pedido de Amazon</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">Estado</label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="pendiente" {{ old('status') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                <option value="procesando" {{ old('status') == 'procesando' ? 'selected' : '' }}>Procesando</option>
                                <option value="enviado" {{ old('status') == 'enviado' ? 'selected' : '' }}>Enviado</option>
                                <option value="entregado" {{ old('status') == 'entregado' ? 'selected' : '' }}>Entregado</option>
                                <option value="cancelado" {{ old('status') == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row" id="tracking-container">
                        <div class="col-md-6 mb-3">
                            <label for="tracking_number" class="form-label">Número de Seguimiento</label>
                            <input type="text" class="form-control @error('tracking_number') is-invalid @enderror" id="tracking_number" name="tracking_number" value="{{ old('tracking_number') }}">
                            @error('tracking_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Opcional si el pedido no está enviado todavía</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="carrier" class="form-label">Transportista</label>
                            <select class="form-select @error('carrier') is-invalid @enderror" id="carrier" name="carrier">
                                <option value="">Seleccionar transportista...</option>
                                <option value="bigbuy_logistics" {{ old('carrier') == 'bigbuy_logistics' ? 'selected' : '' }}>BigBuy Logistics</option>
                                <option value="ups" {{ old('carrier') == 'ups' ? 'selected' : '' }}>UPS</option>
                                <option value="dhl" {{ old('carrier') == 'dhl' ? 'selected' : '' }}>DHL</option>
                                <option value="correos" {{ old('carrier') == 'correos' ? 'selected' : '' }}>Correos</option>
                            </select>
                            @error('carrier')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="notes" class="form-label">Notas del Pedido</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Información adicional o instrucciones especiales</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Products -->
            <div class="form-card mb-4">
                <div class="form-card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Productos</h5>
                    <span class="badge" style="background: linear-gradient(135deg, #3d6eea 0%, #6e47ef 100%); color: white; padding: 6px 12px; border-radius: 50px; font-weight: 600; font-size: 0.75rem;" id="product-count">0 productos</span>
                </div>
                <div class="form-card-body">
                    <div id="products-container">
                        <!-- Los productos se añadirán dinámicamente aquí -->
                    </div>
                    
                    <button type="button" class="add-product-btn" id="add-product">
                        <i class="bi bi-plus-circle me-2"></i> Añadir Producto
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Right column -->
        <div class="col-md-4">
            <!-- Customer Information -->
            <div class="form-card mb-4">
                <div class="form-card-header">
                    <h5 class="mb-0">Información del Cliente</h5>
                </div>
                <div class="form-card-body">
                    <div class="mb-3">
                        <label for="customer_name" class="form-label">Nombre Completo</label>
                        <input type="text" class="form-control @error('customer_name') is-invalid @enderror" id="customer_name" name="customer_name" value="{{ old('customer_name') }}" required>
                        @error('customer_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="customer_email" class="form-label">Email</label>
                        <input type="email" class="form-control @error('customer_email') is-invalid @enderror" id="customer_email" name="customer_email" value="{{ old('customer_email') }}" required>
                        @error('customer_email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="customer_phone" class="form-label">Teléfono</label>
                        <input type="tel" class="form-control @error('customer_phone') is-invalid @enderror" id="customer_phone" name="customer_phone" value="{{ old('customer_phone') }}">
                        @error('customer_phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="customer_address" class="form-label">Dirección de Envío</label>
                        <textarea class="form-control @error('customer_address') is-invalid @enderror" id="customer_address" name="customer_address" rows="3" required>{{ old('customer_address') }}</textarea>
                        @error('customer_address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="customer_city" class="form-label">Ciudad</label>
                            <input type="text" class="form-control @error('customer_city') is-invalid @enderror" id="customer_city" name="customer_city" value="{{ old('customer_city') }}" required>
                            @error('customer_city')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="customer_postcode" class="form-label">Código Postal</label>
                            <input type="text" class="form-control @error('customer_postcode') is-invalid @enderror" id="customer_postcode" name="customer_postcode" value="{{ old('customer_postcode') }}" required>
                            @error('customer_postcode')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="customer_country" class="form-label">País</label>
                        <select class="form-select @error('customer_country') is-invalid @enderror" id="customer_country" name="customer_country" required>
                            <option value="ES" {{ old('customer_country') == 'ES' ? 'selected' : '' }}>España</option>
                            <option value="FR" {{ old('customer_country') == 'FR' ? 'selected' : '' }}>Francia</option>
                            <option value="IT" {{ old('customer_country') == 'IT' ? 'selected' : '' }}>Italia</option>
                            <option value="DE" {{ old('customer_country') == 'DE' ? 'selected' : '' }}>Alemania</option>
                            <option value="PT" {{ old('customer_country') == 'PT' ? 'selected' : '' }}>Portugal</option>
                            <option value="UK" {{ old('customer_country') == 'UK' ? 'selected' : '' }}>Reino Unido</option>
                        </select>
                        @error('customer_country')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
            
            <!-- Order Summary -->
            <div class="form-card mb-4">
                <div class="form-card-header">
                    <h5 class="mb-0">Resumen del Pedido</h5>
                </div>
                <div class="form-card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <span id="subtotal">€0.00</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>IVA (21%):</span>
                        <span id="tax">€0.00</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between fw-bold">
                        <span>Total:</span>
                        <span id="total">€0.00</span>
                    </div>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="action-buttons d-grid gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg me-2"></i> Crear Pedido
                </button>
                <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x-lg me-2"></i> Cancelar
                </a>
            </div>
        </div>
    </div>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const addProductBtn = document.getElementById('add-product');
        const productsContainer = document.getElementById('products-container');
        const productCount = document.getElementById('product-count');
        const subtotalDisplay = document.getElementById('subtotal');
        const taxDisplay = document.getElementById('tax');
        const totalDisplay = document.getElementById('total');
        const statusSelect = document.getElementById('status');
        const trackingContainer = document.getElementById('tracking-container');
        
        let productCounter = 0;
        
        // Mostrar/ocultar campos de tracking según estado
        statusSelect.addEventListener('change', function() {
            if (this.value === 'enviado' || this.value === 'entregado') {
                trackingContainer.classList.remove('d-none');
            } else {
                trackingContainer.classList.add('d-none');
            }
        });
        
        // Inicializar visibilidad de campos de tracking
        if (statusSelect.value === 'enviado' || statusSelect.value === 'entregado') {
            trackingContainer.classList.remove('d-none');
        } else {
            trackingContainer.classList.add('d-none');
        }
        
        // Añadir producto
        addProductBtn.addEventListener('click', function() {
            const productItem = document.createElement('div');
            productItem.className = 'product-item';
            productItem.id = `product-${productCounter}`;
            
            productItem.innerHTML = `
                <button type="button" class="remove-item" data-product-id="${productCounter}">
                    <i class="bi bi-x-circle"></i>
                </button>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="items[${productCounter}][sku]" class="form-label">SKU</label>
                        <input type="text" class="form-control" id="items[${productCounter}][sku]" name="items[${productCounter}][sku]" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="items[${productCounter}][name]" class="form-label">Nombre del Producto</label>
                        <input type="text" class="form-control" id="items[${productCounter}][name]" name="items[${productCounter}][name]" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="items[${productCounter}][price]" class="form-label">Precio</label>
                        <div class="input-group">
                            <span class="input-group-text">€</span>
                            <input type="number" step="0.01" min="0" class="form-control product-price" id="items[${productCounter}][price]" name="items[${productCounter}][price]" required>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="items[${productCounter}][quantity]" class="form-label">Cantidad</label>
                        <input type="number" min="1" class="form-control product-quantity" id="items[${productCounter}][quantity]" name="items[${productCounter}][quantity]" value="1" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Total</label>
                        <div class="input-group">
                            <span class="input-group-text">€</span>
                            <input type="text" class="form-control product-total" readonly value="0.00">
                        </div>
                    </div>
                </div>
            `;
            
            productsContainer.appendChild(productItem);
            productCounter++;
            
            updateProductCount();
            
            // Eliminar producto
            const removeButtons = document.querySelectorAll('.remove-item');
            removeButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const productId = this.getAttribute('data-product-id');
                    const productToRemove = document.getElementById(`product-${productId}`);
                    productToRemove.remove();
                    updateProductCount();
                    calculateTotals();
                });
            });
            
            // Calcular total por producto
            const priceInputs = document.querySelectorAll('.product-price');
            const quantityInputs = document.querySelectorAll('.product-quantity');
            
            priceInputs.forEach(input => {
                input.addEventListener('input', function() {
                    calculateProductTotal(this);
                    calculateTotals();
                });
            });
            
            quantityInputs.forEach(input => {
                input.addEventListener('input', function() {
                    calculateProductTotal(this);
                    calculateTotals();
                });
            });
        });
        
        // Calcular total por producto
        function calculateProductTotal(input) {
            const productItem = input.closest('.product-item');
            const price = parseFloat(productItem.querySelector('.product-price').value) || 0;
            const quantity = parseInt(productItem.querySelector('.product-quantity').value) || 0;
            const total = price * quantity;
            
            productItem.querySelector('.product-total').value = total.toFixed(2);
        }
        
        // Calcular totales generales
        function calculateTotals() {
            const productTotals = document.querySelectorAll('.product-total');
            let subtotal = 0;
            
            productTotals.forEach(item => {
                subtotal += parseFloat(item.value) || 0;
            });
            
            const tax = subtotal * 0.21;
            const total = subtotal + tax;
            
            subtotalDisplay.textContent = `€${subtotal.toFixed(2)}`;
            taxDisplay.textContent = `€${tax.toFixed(2)}`;
            totalDisplay.textContent = `€${total.toFixed(2)}`;
        }
        
        // Actualizar contador de productos
        function updateProductCount() {
            const count = productsContainer.childElementCount;
            productCount.textContent = `${count} producto${count !== 1 ? 's' : ''}`;
        }
        
        // Añadir primer producto por defecto
        addProductBtn.click();
    });
</script>
@endsection
