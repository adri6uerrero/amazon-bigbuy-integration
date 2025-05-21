@extends('layouts.app')

@section('content')
<style>
    /* Form styles */
    .form-header {
        background: linear-gradient(135deg, #3d6eea 0%, #6e47ef 100%);
        color: white;
        padding: 20px 30px;
        border-radius: 15px;
        margin-bottom: 25px;
        box-shadow: 0 4px 20px rgba(61, 110, 234, 0.15);
    }
    
    .form-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.04);
        overflow: hidden;
        transition: all 0.3s ease;
        border: 1px solid rgba(230, 230, 230, 0.5);
        margin-bottom: 30px;
    }
    
    .form-card-header {
        padding: 20px 25px;
        border-bottom: 1px solid #f5f5f5;
        background: rgba(249, 250, 251, 0.5);
    }
    
    .form-card-title {
        font-size: 18px;
        font-weight: 600;
        color: #333;
        margin: 0;
    }
    
    .form-card-body {
        padding: 25px;
    }
    
    .section-title {
        font-size: 16px;
        font-weight: 600;
        color: #3d6eea;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .form-label {
        font-weight: 500;
        margin-bottom: 8px;
        color: #4b5563;
    }
    
    .form-control, .form-select {
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        padding: 10px 15px;
        transition: all 0.2s;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #3d6eea;
        box-shadow: 0 0 0 3px rgba(61, 110, 234, 0.1);
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #3d6eea 0%, #6e47ef 100%);
        border: none;
        border-radius: 8px;
        padding: 10px 20px;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(61, 110, 234, 0.2);
    }
    
    .btn-secondary {
        background: #f3f4f6;
        border: 1px solid #e5e7eb;
        color: #4b5563;
        border-radius: 8px;
        padding: 10px 20px;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .btn-secondary:hover {
        background: #e5e7eb;
        transform: translateY(-2px);
    }
    
    .platform-badge {
        padding: 8px 12px;
        border-radius: 30px;
        font-size: 0.85rem;
        font-weight: 500;
        margin-bottom: 15px;
        display: inline-block;
    }
    
    .badge-amazon {
        background: linear-gradient(135deg, #ff9900, #ffb144);
        color: white;
    }
    
    .badge-bigbuy {
        background: linear-gradient(135deg, #3d6eea, #6e47ef);
        color: white;
    }
    
    .is-invalid {
        border-color: #dc3545;
    }
    
    .invalid-feedback {
        color: #dc3545;
        font-size: 0.85rem;
        margin-top: 5px;
    }
    
    /* Dimensions input styling */
    .dimensions-group {
        display: flex;
        gap: 10px;
    }
    
    .dimensions-group .form-control {
        text-align: center;
    }
    
    .dimension-label {
        font-size: 0.75rem;
        text-align: center;
        color: #6b7280;
        margin-top: 5px;
    }
</style>

<!-- Header -->
<div class="form-header d-flex justify-content-between align-items-center">
    <div>
        <h2>Crear Nuevo Producto</h2>
        <p>Añadir un nuevo producto al catálogo</p>
    </div>
    <a href="{{ route('products.index') }}" class="btn btn-light">
        <i class="bi bi-arrow-left me-2"></i>Volver al Listado
    </a>
</div>

<!-- Product Form -->
<form action="{{ route('products.store') }}" method="POST">
    @csrf
    
    <div class="row">
        <!-- Basic Information Card -->
        <div class="col-md-8">
            <div class="form-card">
                <div class="form-card-header">
                    <h5 class="form-card-title">Información Básica</h5>
                </div>
                <div class="form-card-body">
                    <div class="mb-4">
                        <label for="name" class="form-label">Nombre del Producto *</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="description" class="form-label">Descripción *</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="5" required>{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="sku" class="form-label">SKU *</label>
                            <input type="text" class="form-control @error('sku') is-invalid @enderror" id="sku" name="sku" value="{{ old('sku') }}" required>
                            @error('sku')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Código único de producto</small>
                        </div>
                        
                        <div class="col-md-6 mb-4">
                            <label for="category" class="form-label">Categoría *</label>
                            <input type="text" class="form-control @error('category') is-invalid @enderror" id="category" name="category" value="{{ old('category') }}" required>
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="status" class="form-label">Estado *</label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Activo</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactivo</option>
                                <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Borrador</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-4">
                            <label for="price" class="form-label">Precio Base (€) *</label>
                            <input type="number" step="0.01" min="0" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price') }}" required>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-4">
                            <label for="weight" class="form-label">Peso (kg)</label>
                            <input type="number" step="0.01" min="0" class="form-control @error('weight') is-invalid @enderror" id="weight" name="weight" value="{{ old('weight') }}">
                            @error('weight')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-8 mb-4">
                            <label class="form-label">Dimensiones (cm)</label>
                            <div class="dimensions-group">
                                <div>
                                    <input type="number" step="0.1" min="0" class="form-control @error('dimensions.width') is-invalid @enderror" id="width" name="dimensions[width]" value="{{ old('dimensions.width') }}">
                                    <div class="dimension-label">Ancho</div>
                                    @error('dimensions.width')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div>
                                    <input type="number" step="0.1" min="0" class="form-control @error('dimensions.height') is-invalid @enderror" id="height" name="dimensions[height]" value="{{ old('dimensions.height') }}">
                                    <div class="dimension-label">Alto</div>
                                    @error('dimensions.height')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div>
                                    <input type="number" step="0.1" min="0" class="form-control @error('dimensions.depth') is-invalid @enderror" id="depth" name="dimensions[depth]" value="{{ old('dimensions.depth') }}">
                                    <div class="dimension-label">Profund.</div>
                                    @error('dimensions.depth')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label for="image_url" class="form-label">URL de Imagen</label>
                        <input type="url" class="form-control @error('image_url') is-invalid @enderror" id="image_url" name="image_url" value="{{ old('image_url') }}">
                        @error('image_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Dirección web de la imagen del producto</small>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Platform Information Card -->
        <div class="col-md-4">
            <div class="form-card">
                <div class="form-card-header">
                    <h5 class="form-card-title">Información de Plataformas</h5>
                </div>
                <div class="form-card-body">
                    <!-- Amazon Information -->
                    <div class="section-title">
                        <span class="platform-badge badge-amazon">
                            <i class="bi bi-amazon me-2"></i>Amazon
                        </span>
                    </div>
                    
                    <div class="mb-4">
                        <label for="amazon_asin" class="form-label">ASIN</label>
                        <input type="text" class="form-control @error('amazon_asin') is-invalid @enderror" id="amazon_asin" name="amazon_asin" value="{{ old('amazon_asin') }}">
                        @error('amazon_asin')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-6 mb-4">
                            <label for="amazon_price" class="form-label">Precio (€) *</label>
                            <input type="number" step="0.01" min="0" class="form-control @error('amazon_price') is-invalid @enderror" id="amazon_price" name="amazon_price" value="{{ old('amazon_price') }}" required>
                            @error('amazon_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-6 mb-4">
                            <label for="amazon_stock" class="form-label">Stock *</label>
                            <input type="number" min="0" class="form-control @error('amazon_stock') is-invalid @enderror" id="amazon_stock" name="amazon_stock" value="{{ old('amazon_stock') }}" required>
                            @error('amazon_stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- BigBuy Information -->
                    <div class="section-title mt-4">
                        <span class="platform-badge badge-bigbuy">
                            <i class="bi bi-bag me-2"></i>BigBuy
                        </span>
                    </div>
                    
                    <div class="mb-4">
                        <label for="bigbuy_id" class="form-label">ID BigBuy</label>
                        <input type="text" class="form-control @error('bigbuy_id') is-invalid @enderror" id="bigbuy_id" name="bigbuy_id" value="{{ old('bigbuy_id') }}">
                        @error('bigbuy_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-6 mb-4">
                            <label for="bigbuy_price" class="form-label">Precio (€) *</label>
                            <input type="number" step="0.01" min="0" class="form-control @error('bigbuy_price') is-invalid @enderror" id="bigbuy_price" name="bigbuy_price" value="{{ old('bigbuy_price') }}" required>
                            @error('bigbuy_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-6 mb-4">
                            <label for="bigbuy_stock" class="form-label">Stock *</label>
                            <input type="number" min="0" class="form-control @error('bigbuy_stock') is-invalid @enderror" id="bigbuy_stock" name="bigbuy_stock" value="{{ old('bigbuy_stock') }}" required>
                            @error('bigbuy_stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Stock Information -->
                    <div class="mb-4">
                        <label for="stock" class="form-label">Stock Total *</label>
                        <input type="number" min="0" class="form-control @error('stock') is-invalid @enderror" id="stock" name="stock" value="{{ old('stock') }}" required>
                        @error('stock')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Stock interno/real del producto</small>
                    </div>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('products.index') }}" class="btn btn-secondary">
                    <i class="bi bi-x-circle me-2"></i>Cancelar
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle me-2"></i>Guardar Producto
                </button>
            </div>
        </div>
    </div>
</form>

<script>
    // Auto-calculate total stock from Amazon and BigBuy
    document.addEventListener('DOMContentLoaded', function() {
        const amazonStock = document.getElementById('amazon_stock');
        const bigbuyStock = document.getElementById('bigbuy_stock');
        const totalStock = document.getElementById('stock');
        
        // Function to update total stock
        const updateTotalStock = () => {
            const amazon = parseInt(amazonStock.value) || 0;
            const bigbuy = parseInt(bigbuyStock.value) || 0;
            totalStock.value = amazon + bigbuy;
        };
        
        // Add event listeners
        amazonStock.addEventListener('input', updateTotalStock);
        bigbuyStock.addEventListener('input', updateTotalStock);
        
        // Initialize with any existing values
        updateTotalStock();
    });
</script>
@endsection
