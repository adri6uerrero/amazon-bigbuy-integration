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
    
    .form-card:hover {
        box-shadow: 0 8px 25px rgba(61, 110, 234, 0.1);
        transform: translateY(-3px);
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
    
    .btn-danger {
        background: linear-gradient(135deg, #f43f5e, #e11d48);
        border: none;
        border-radius: 8px;
        padding: 10px 20px;
        font-weight: 500;
        transition: all 0.3s ease;
        color: white;
    }
    
    .btn-danger:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(225, 29, 72, 0.2);
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
    
    /* Product preview */
    .product-preview {
        position: relative;
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 20px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }
    
    .product-image {
        width: 100%;
        height: 180px;
        object-fit: cover;
        border-radius: 12px;
        background-color: #f5f7fa;
    }
    
    .product-image-placeholder {
        width: 100%;
        height: 180px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f5f7fa;
        border-radius: 12px;
        color: #6b7280;
        font-size: 14px;
    }
    
    .product-id {
        position: absolute;
        top: 10px;
        right: 10px;
        background: rgba(0,0,0,0.6);
        color: white;
        font-size: 0.8rem;
        padding: 4px 8px;
        border-radius: 4px;
    }
</style>

<!-- Header -->
<div class="form-header d-flex justify-content-between align-items-center">
    <div>
        <h2>Editar Producto</h2>
        <p>Actualizar información del producto #{{ $product->id }}</p>
    </div>
    <div>
        <a href="{{ route('products.show', $product) }}" class="btn btn-light me-2">
            <i class="bi bi-eye me-1"></i>Ver Detalles
        </a>
        <a href="{{ route('products.index') }}" class="btn btn-light">
            <i class="bi bi-arrow-left me-1"></i>Volver
        </a>
    </div>
</div>

<!-- Product Form -->
<form action="{{ route('products.update', $product) }}" method="POST">
    @csrf
    @method('PUT')
    
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
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $product->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-4">
                        <label for="description" class="form-label">Descripción *</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="5" required>{{ old('description', $product->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="sku" class="form-label">SKU *</label>
                            <input type="text" class="form-control @error('sku') is-invalid @enderror" id="sku" name="sku" value="{{ old('sku', $product->sku) }}" required>
                            @error('sku')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Código único de producto</small>
                        </div>
                        
                        <div class="col-md-6 mb-4">
                            <label for="category" class="form-label">Categoría *</label>
                            <input type="text" class="form-control @error('category') is-invalid @enderror" id="category" name="category" value="{{ old('category', $product->category) }}" required>
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="status" class="form-label">Estado *</label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="active" {{ old('status', $product->status) == 'active' ? 'selected' : '' }}>Activo</option>
                                <option value="inactive" {{ old('status', $product->status) == 'inactive' ? 'selected' : '' }}>Inactivo</option>
                                <option value="draft" {{ old('status', $product->status) == 'draft' ? 'selected' : '' }}>Borrador</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-4">
                            <label for="price" class="form-label">Precio Base (€) *</label>
                            <input type="number" step="0.01" min="0" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price', $product->price) }}" required>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-4">
                            <label for="weight" class="form-label">Peso (kg)</label>
                            <input type="number" step="0.01" min="0" class="form-control @error('weight') is-invalid @enderror" id="weight" name="weight" value="{{ old('weight', $product->weight) }}">
                            @error('weight')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-8 mb-4">
                            <label class="form-label">Dimensiones (cm)</label>
                            <div class="dimensions-group">
                                <div>
                                    <input type="number" step="0.1" min="0" class="form-control @error('dimensions.width') is-invalid @enderror" id="width" name="dimensions[width]" value="{{ old('dimensions.width', $product->dimensions['width'] ?? '') }}">
                                    <div class="dimension-label">Ancho</div>
                                    @error('dimensions.width')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div>
                                    <input type="number" step="0.1" min="0" class="form-control @error('dimensions.height') is-invalid @enderror" id="height" name="dimensions[height]" value="{{ old('dimensions.height', $product->dimensions['height'] ?? '') }}">
                                    <div class="dimension-label">Alto</div>
                                    @error('dimensions.height')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div>
                                    <input type="number" step="0.1" min="0" class="form-control @error('dimensions.depth') is-invalid @enderror" id="depth" name="dimensions[depth]" value="{{ old('dimensions.depth', $product->dimensions['depth'] ?? '') }}">
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
                        <input type="url" class="form-control @error('image_url') is-invalid @enderror" id="image_url" name="image_url" value="{{ old('image_url', $product->image_url) }}">
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
            <!-- Product Preview -->
            <div class="form-card mb-4">
                <div class="form-card-header">
                    <h5 class="form-card-title">Vista Previa</h5>
                </div>
                <div class="form-card-body">
                    <div class="product-preview">
                        @if($product->image_url)
                            <img src="{{ $product->image_url }}" class="product-image" id="preview-image" alt="{{ $product->name }}">
                        @else
                            <div class="product-image-placeholder" id="preview-placeholder">
                                <i class="bi bi-image me-2"></i> Sin imagen
                            </div>
                        @endif
                        <div class="product-id">ID: {{ $product->id }}</div>
                    </div>
                    <p class="mb-2"><strong>SKU:</strong> {{ $product->sku }}</p>
                    <p class="mb-0"><strong>Creado:</strong> {{ $product->created_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>
            
            <!-- Platform Info -->
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
                        <input type="text" class="form-control @error('amazon_asin') is-invalid @enderror" id="amazon_asin" name="amazon_asin" value="{{ old('amazon_asin', $product->amazon_asin) }}">
                        @error('amazon_asin')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-6 mb-4">
                            <label for="amazon_price" class="form-label">Precio (€) *</label>
                            <input type="number" step="0.01" min="0" class="form-control @error('amazon_price') is-invalid @enderror" id="amazon_price" name="amazon_price" value="{{ old('amazon_price', $product->amazon_price) }}" required>
                            @error('amazon_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-6 mb-4">
                            <label for="amazon_stock" class="form-label">Stock *</label>
                            <input type="number" min="0" class="form-control @error('amazon_stock') is-invalid @enderror" id="amazon_stock" name="amazon_stock" value="{{ old('amazon_stock', $product->amazon_stock) }}" required>
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
                        <input type="text" class="form-control @error('bigbuy_id') is-invalid @enderror" id="bigbuy_id" name="bigbuy_id" value="{{ old('bigbuy_id', $product->bigbuy_id) }}">
                        @error('bigbuy_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-6 mb-4">
                            <label for="bigbuy_price" class="form-label">Precio (€) *</label>
                            <input type="number" step="0.01" min="0" class="form-control @error('bigbuy_price') is-invalid @enderror" id="bigbuy_price" name="bigbuy_price" value="{{ old('bigbuy_price', $product->bigbuy_price) }}" required>
                            @error('bigbuy_price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-6 mb-4">
                            <label for="bigbuy_stock" class="form-label">Stock *</label>
                            <input type="number" min="0" class="form-control @error('bigbuy_stock') is-invalid @enderror" id="bigbuy_stock" name="bigbuy_stock" value="{{ old('bigbuy_stock', $product->bigbuy_stock) }}" required>
                            @error('bigbuy_stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Stock Information -->
                    <div class="mb-4">
                        <label for="stock" class="form-label">Stock Total *</label>
                        <input type="number" min="0" class="form-control @error('stock') is-invalid @enderror" id="stock" name="stock" value="{{ old('stock', $product->stock) }}" required>
                        @error('stock')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Stock interno/real del producto</small>
                    </div>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="d-flex justify-content-between mt-4">
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                    <i class="bi bi-trash me-2"></i>Eliminar
                </button>
                <div>
                    <a href="{{ route('products.show', $product) }}" class="btn btn-secondary me-2">Cancelar</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle me-2"></i>Guardar Cambios
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirmar Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro de que deseas eliminar este producto?</p>
                <p><strong>{{ $product->name }}</strong> (SKU: {{ $product->sku }})</p>
                <p class="text-danger"><i class="bi bi-exclamation-triangle me-2"></i>Esta acción no se puede deshacer.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form action="{{ route('products.destroy', $product) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Eliminar Producto</button>
                </form>
            </div>
        </div>
    </div>
</div>

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
        
        // Image preview handling
        const imageUrl = document.getElementById('image_url');
        const previewImage = document.getElementById('preview-image');
        const previewPlaceholder = document.getElementById('preview-placeholder');
        
        imageUrl.addEventListener('input', function() {
            const url = this.value;
            if (url) {
                if (previewImage) {
                    previewImage.src = url;
                    previewImage.style.display = 'block';
                    if (previewPlaceholder) {
                        previewPlaceholder.style.display = 'none';
                    }
                } else {
                    // Create image if it doesn't exist
                    const newImage = document.createElement('img');
                    newImage.src = url;
                    newImage.id = 'preview-image';
                    newImage.className = 'product-image';
                    newImage.alt = document.getElementById('name').value;
                    
                    if (previewPlaceholder) {
                        previewPlaceholder.parentNode.replaceChild(newImage, previewPlaceholder);
                    }
                }
            } else {
                // Show placeholder when URL is empty
                if (previewImage) {
                    previewImage.style.display = 'none';
                    if (previewPlaceholder) {
                        previewPlaceholder.style.display = 'flex';
                    } else {
                        // Create placeholder if it doesn't exist
                        const newPlaceholder = document.createElement('div');
                        newPlaceholder.id = 'preview-placeholder';
                        newPlaceholder.className = 'product-image-placeholder';
                        newPlaceholder.innerHTML = '<i class="bi bi-image me-2"></i> Sin imagen';
                        
                        previewImage.parentNode.replaceChild(newPlaceholder, previewImage);
                    }
                }
            }
        });
    });
</script>
@endsection
