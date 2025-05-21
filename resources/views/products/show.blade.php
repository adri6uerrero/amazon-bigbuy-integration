@extends('layouts.app')

@section('content')
<style>
    /* Header styles */
    .product-header {
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
    
    .product-header h2 {
        font-size: 1.5rem;
        font-weight: 500;
        margin: 0;
    }
    
    .product-header p {
        margin: 0;
        opacity: 0.9;
        font-size: 0.85rem;
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
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 20px;
        border-bottom: 1px solid #f5f7fa;
    }
    
    .detail-card-title {
        font-size: 1.1rem;
        font-weight: 600;
        margin: 0;
        color: #333;
    }
    
    .detail-card-actions {
        display: flex;
        gap: 10px;
    }
    
    /* Product image styles */
    .product-image-container {
        position: relative;
        overflow: hidden;
        border-radius: 15px;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f8f9fa;
    }
    
    .product-image {
        width: 100%;
        height: auto;
        object-fit: contain;
        max-height: 300px;
    }
    
    /* Property list styles */
    .property-list {
        padding: 20px;
    }
    
    .property-list dl {
        margin-bottom: 0;
    }
    
    .property-list dt {
        font-size: 0.85rem;
        color: #6c757d;
        margin-bottom: 5px;
    }
    
    .property-list dd {
        font-size: 1rem;
        font-weight: 500;
        color: #333;
        margin-bottom: 15px;
    }
    
    .property-list dd:last-child {
        margin-bottom: 0;
    }
    
    /* Platform card styles */
    .platform-card {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 15px;
        margin-bottom: 15px;
    }
    
    .platform-card:last-child {
        margin-bottom: 0;
    }
    
    .platform-name {
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
    }
    
    .platform-name i {
        margin-right: 10px;
        font-size: 1.2rem;
    }
    
    .platform-property {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
        font-size: 0.95rem;
    }
    
    .platform-property:last-child {
        margin-bottom: 0;
    }
    
    .platform-property span:first-child {
        color: #6c757d;
    }
    
    .platform-property span:last-child {
        font-weight: 500;
        color: #333;
    }
    
    /* Compare section styles */
    .compare-list {
        padding: 0;
        list-style: none;
        margin: 0;
    }
    
    .compare-item {
        padding: 15px 20px;
        border-bottom: 1px solid #f5f7fa;
        display: flex;
        align-items: center;
    }
    
    .compare-item:last-child {
        border-bottom: none;
    }
    
    .compare-property {
        flex: 1;
        font-size: 0.95rem;
        color: #6c757d;
    }
    
    .compare-amazon, .compare-bigbuy {
        flex: 1;
        text-align: center;
        font-weight: 500;
        color: #333;
    }
    
    .compare-indicator {
        width: 80px;
        text-align: center;
        font-size: 1.2rem;
    }
    
    .compare-indicator .equal {
        color: #28a745;
    }
    
    .compare-indicator .not-equal {
        color: #dc3545;
    }
    
    /* Sync button styles */
    .sync-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 10px 20px;
        border-radius: 10px;
        background: linear-gradient(135deg, #42b883, #347474);
        color: white;
        border: none;
        font-weight: 500;
        transition: all 0.3s ease;
        text-decoration: none;
    }
    
    .sync-button:hover {
        box-shadow: 0 4px 10px rgba(66, 184, 131, 0.3);
        transform: translateY(-2px);
        color: white;
    }
    
    .sync-button i {
        margin-right: 8px;
    }
    
    /* Status badge styles */
    .status-badge {
        padding: 6px 12px;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 500;
        margin-bottom: 15px;
        display: inline-block;
    }
    
    .status-badge.synced {
        background: linear-gradient(135deg, #42b883, #347474);
        color: white;
    }
    
    .status-badge.price-synced {
        background: linear-gradient(135deg, #00c6ff, #0072ff);
        color: white;
    }
    
    .status-badge.stock-synced {
        background: linear-gradient(135deg, #f7b733, #fc4a1a);
        color: white;
    }
    
    .status-badge.not-synced {
        background: linear-gradient(135deg, #eb3349, #f45c43);
        color: white;
    }
    
    /* Action buttons */
    .action-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 8px 15px;
        border-radius: 10px;
        background: #f8f9fa;
        color: #495057;
        border: none;
        font-size: 0.9rem;
        font-weight: 500;
        transition: all 0.2s;
        text-decoration: none;
    }
    
    .action-btn:hover {
        background: #e9ecef;
        color: #212529;
    }
    
    .action-btn.primary {
        background-color: #e6f0ff;
        color: #3d6eea;
    }
    
    .action-btn.primary:hover {
        background-color: #d1e3ff;
    }
    
    .action-btn.danger {
        background-color: #f8d7da;
        color: #dc3545;
    }
    
    .action-btn.danger:hover {
        background-color: #f5c6cb;
    }
    
    .action-btn i {
        margin-right: 5px;
    }
    
    /* Description container */
    .description-container {
        padding: 20px;
        font-size: 0.95rem;
        color: #495057;
        line-height: 1.6;
    }
</style>

<div class="container-fluid">
    <!-- Header section with gradient background -->
    <div class="product-header">
        <div>
            <h2>{{ $product->name }}</h2>
            <p>Detalle y comparativa del producto</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('products.edit', $product) }}" class="btn btn-light rounded-pill px-4">
                <i class="bi bi-pencil me-2"></i>Editar
            </a>
            <a href="{{ route('products.index') }}" class="btn btn-light rounded-pill px-4">
                <i class="bi bi-arrow-left me-2"></i>Volver
            </a>
        </div>
    </div>
    
    <div class="row">
        <!-- Product image and basic info -->
        <div class="col-lg-4">
            <div class="detail-card mb-4">
                <div class="product-image-container p-4">
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="product-image">
                </div>
            </div>
            
            <div class="detail-card">
                <div class="detail-card-header">
                    <h5 class="detail-card-title">Información Básica</h5>
                </div>
                <div class="property-list">
                    <dl class="row mb-0">
                        <dt class="col-12">SKU</dt>
                        <dd class="col-12">{{ $product->sku }}</dd>
                        
                        <dt class="col-12">Categoría</dt>
                        <dd class="col-12">{{ $product->category }}</dd>
                        
                        <dt class="col-12">Estado</dt>
                        <dd class="col-12">
                            <span class="badge {{ $product->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                                {{ $product->status === 'active' ? 'Activo' : ($product->status === 'inactive' ? 'Inactivo' : 'Borrador') }}
                            </span>
                        </dd>
                        
                        <dt class="col-12">Precio Base</dt>
                        <dd class="col-12">{{ number_format($product->price, 2) }}€</dd>
                        
                        <dt class="col-12">Stock Total</dt>
                        <dd class="col-12">{{ $product->stock }} unidades</dd>
                        
                        <dt class="col-12">Peso</dt>
                        <dd class="col-12">{{ $product->weight ?? 'No especificado' }} kg</dd>
                        
                        @if($product->dimensions)
                            <dt class="col-12">Dimensiones</dt>
                            <dd class="col-12">
                                {{ $product->dimensions['width'] ?? 0 }} × 
                                {{ $product->dimensions['height'] ?? 0 }} × 
                                {{ $product->dimensions['depth'] ?? 0 }} cm
                            </dd>
                        @endif
                    </dl>
                </div>
            </div>
        </div>
        
        <!-- Product details and platforms comparison -->
        <div class="col-lg-8">
            <!-- Sync status and actions -->
            <div class="detail-card mb-4">
                <div class="detail-card-header">
                    <h5 class="detail-card-title">Estado de Sincronización</h5>
                    
                    @if($product->sync_status !== 'sincronizado')
                        <button type="button" class="sync-button" data-bs-toggle="modal" data-bs-target="#syncModal">
                            <i class="bi bi-arrow-repeat"></i> Sincronizar Ahora
                        </button>
                    @endif
                </div>
                <div class="property-list">
                    @php
                        $syncStatus = $product->sync_status;
                        $statusClass = match($syncStatus) {
                            'sincronizado' => 'synced',
                            'precio_desincronizado' => 'price-synced',
                            'stock_desincronizado' => 'stock-synced',
                            'desincronizado' => 'not-synced',
                        };
                        
                        $statusLabel = match($syncStatus) {
                            'sincronizado' => 'Sincronizado',
                            'precio_desincronizado' => 'Precio desincronizado',
                            'stock_desincronizado' => 'Stock desincronizado',
                            'desincronizado' => 'No sincronizado',
                        };
                    @endphp
                    
                    <span class="status-badge {{ $statusClass }}">{{ $statusLabel }}</span>
                    
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="platform-card">
                                <div class="platform-name">
                                    <i class="bi bi-amazon"></i> Amazon
                                </div>
                                <div class="platform-property">
                                    <span>ASIN:</span>
                                    <span>{{ $product->amazon_asin }}</span>
                                </div>
                                <div class="platform-property">
                                    <span>Precio:</span>
                                    <span>{{ number_format($product->amazon_price, 2) }}€</span>
                                </div>
                                <div class="platform-property">
                                    <span>Stock:</span>
                                    <span>{{ $product->amazon_stock }} unidades</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="platform-card">
                                <div class="platform-name">
                                    <i class="bi bi-bag"></i> BigBuy
                                </div>
                                <div class="platform-property">
                                    <span>ID:</span>
                                    <span>{{ $product->bigbuy_id }}</span>
                                </div>
                                <div class="platform-property">
                                    <span>Precio:</span>
                                    <span>{{ number_format($product->bigbuy_price, 2) }}€</span>
                                </div>
                                <div class="platform-property">
                                    <span>Stock:</span>
                                    <span>{{ $product->bigbuy_stock }} unidades</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Platforms comparison -->
            <div class="detail-card mb-4">
                <div class="detail-card-header">
                    <h5 class="detail-card-title">Comparativa de Plataformas</h5>
                </div>
                <ul class="compare-list">
                    <li class="compare-item">
                        <div class="compare-property">Propiedad</div>
                        <div class="compare-amazon">Amazon</div>
                        <div class="compare-indicator">Estado</div>
                        <div class="compare-bigbuy">BigBuy</div>
                    </li>
                    <li class="compare-item">
                        <div class="compare-property">Precio</div>
                        <div class="compare-amazon">{{ number_format($product->amazon_price, 2) }}€</div>
                        <div class="compare-indicator">
                            @if(abs($product->amazon_price - $product->bigbuy_price) < 0.01)
                                <i class="bi bi-check-circle equal"></i>
                            @else
                                <i class="bi bi-x-circle not-equal"></i>
                            @endif
                        </div>
                        <div class="compare-bigbuy">{{ number_format($product->bigbuy_price, 2) }}€</div>
                    </li>
                    <li class="compare-item">
                        <div class="compare-property">Stock</div>
                        <div class="compare-amazon">{{ $product->amazon_stock }}</div>
                        <div class="compare-indicator">
                            @if($product->amazon_stock == $product->bigbuy_stock)
                                <i class="bi bi-check-circle equal"></i>
                            @else
                                <i class="bi bi-x-circle not-equal"></i>
                            @endif
                        </div>
                        <div class="compare-bigbuy">{{ $product->bigbuy_stock }}</div>
                    </li>
                </ul>
            </div>
            
            <!-- Product description -->
            <div class="detail-card">
                <div class="detail-card-header">
                    <h5 class="detail-card-title">Descripción</h5>
                </div>
                <div class="description-container">
                    {{ $product->description }}
                </div>
            </div>
        </div>
    </div>
    
    <!-- Actions -->
    <div class="row mt-4 mb-5">
        <div class="col-12 d-flex justify-content-between">
            <div>
                <a href="{{ route('products.index') }}" class="action-btn">
                    <i class="bi bi-arrow-left"></i> Volver al listado
                </a>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('products.edit', $product) }}" class="action-btn primary">
                    <i class="bi bi-pencil"></i> Editar producto
                </a>
                <button type="button" class="action-btn danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                    <i class="bi bi-trash"></i> Eliminar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Sync Modal -->
<div class="modal fade" id="syncModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Sincronizar Producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Selecciona cómo deseas sincronizar el producto <strong>{{ $product->name }}</strong>:</p>
                
                <div class="mb-3">
                    <p class="mb-2">Estado actual:</p>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <strong>Amazon:</strong> {{ number_format($product->amazon_price, 2) }}€ 
                            ({{ $product->amazon_stock }} unidades)
                        </li>
                        <li>
                            <strong>BigBuy:</strong> {{ number_format($product->bigbuy_price, 2) }}€ 
                            ({{ $product->bigbuy_stock }} unidades)
                        </li>
                    </ul>
                </div>
                
                <form action="{{ route('products.sync', $product) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Dirección de sincronización:</label>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="platform" id="amazon" value="amazon" checked>
                            <label class="form-check-label" for="amazon">
                                Amazon → Actualizar con datos de BigBuy
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="platform" id="bigbuy" value="bigbuy">
                            <label class="form-check-label" for="bigbuy">
                                BigBuy → Actualizar con datos de Amazon
                            </label>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Datos a sincronizar:</label>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="type" id="both" value="both" checked>
                            <label class="form-check-label" for="both">
                                Precio y stock
                            </label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="type" id="price" value="price">
                            <label class="form-check-label" for="price">
                                Solo precio
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="type" id="stock" value="stock">
                            <label class="form-check-label" for="stock">
                                Solo stock
                            </label>
                        </div>
                    </div>
                    
                    <div class="text-end">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Sincronizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro de que deseas eliminar el producto <strong>{{ $product->name }}</strong>?</p>
                <p class="text-danger">Esta acción no se puede deshacer.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form action="{{ route('products.destroy', $product) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
