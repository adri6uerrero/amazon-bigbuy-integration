@extends('layouts.app')

@section('content')
<style>
    /* Header styles */
    .products-header {
        background: linear-gradient(135deg, #3d6eea 0%, #6e47ef 100%);
        color: white;
        padding: 20px 30px;
        border-radius: 15px;
        margin-bottom: 25px;
        box-shadow: 0 4px 20px rgba(61, 110, 234, 0.15);
    }
    
    .products-header h2 {
        font-size: 1.5rem;
        font-weight: 500;
        margin: 0;
    }
    
    .products-header p {
        margin: 0;
        opacity: 0.9;
        font-size: 0.85rem;
    }
    
    /* Filter bar styles */
    .filter-bar {
        background: white;
        border-radius: 15px;
        padding: 15px 20px;
        margin-bottom: 25px;
        box-shadow: 0 3px 10px rgba(0,0,0,0.03);
        border: 1px solid rgba(230, 230, 250, 0.7);
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
    }
    
    .search-box {
        flex-grow: 1;
        max-width: 350px;
        position: relative;
    }
    
    .search-box input {
        width: 100%;
        padding: 8px 15px 8px 40px;
        border-radius: 10px;
        border: 1px solid #e9ecef;
        background-color: #f8f9fa;
        transition: all 0.3s ease;
    }
    
    .search-box input:focus {
        background-color: white;
        border-color: #3d6eea;
        box-shadow: 0 0 0 3px rgba(61, 110, 234, 0.1);
        outline: none;
    }
    
    .search-box i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
    }
    
    .filter-select {
        padding: 8px 15px;
        border-radius: 10px;
        border: 1px solid #e9ecef;
        background-color: #f8f9fa;
        color: #495057;
        appearance: none;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 0.75rem center;
        background-size: 16px 12px;
        padding-right: 30px;
        min-width: 150px;
    }
    
    .filter-select:focus {
        border-color: #3d6eea;
        box-shadow: 0 0 0 3px rgba(61, 110, 234, 0.1);
        outline: none;
    }
    
    /* Product card styles */
    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
    }
    
    .product-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.03);
        overflow: hidden;
        border: 1px solid rgba(230, 230, 250, 0.7);
        transition: all 0.3s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    
    .product-card:hover {
        box-shadow: 0 8px 25px rgba(61, 110, 234, 0.1);
        transform: translateY(-3px);
    }
    
    .product-image {
        width: 100%;
        height: 180px;
        object-fit: cover;
    }
    
    .product-content {
        padding: 20px;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
    }
    
    .product-name {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 5px;
        color: #333;
    }
    
    .product-category {
        font-size: 0.85rem;
        color: #6c757d;
        margin-bottom: 10px;
    }
    
    .product-prices {
        margin-top: auto;
        margin-bottom: 15px;
    }
    
    .product-price {
        font-size: 1.2rem;
        font-weight: 600;
        color: #333;
    }
    
    .product-price-diff {
        font-size: 0.85rem;
        margin-left: 8px;
    }
    
    .price-up {
        color: #dc3545;
    }
    
    .price-down {
        color: #28a745;
    }
    
    .product-stock {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        border-top: 1px solid #f1f3f5;
        font-size: 0.9rem;
    }
    
    .stock-badge {
        padding: 4px 10px;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 500;
    }
    
    .stock-badge.in-stock {
        background-color: #e6f7ed;
        color: #28a745;
    }
    
    .stock-badge.low-stock {
        background-color: #fff3cd;
        color: #ffc107;
    }
    
    .stock-badge.out-stock {
        background-color: #f8d7da;
        color: #dc3545;
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
    .product-actions {
        display: flex;
        justify-content: space-between;
        margin-top: 10px;
    }
    
    .action-btn {
        flex: 1;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 8px 10px;
        border-radius: 10px;
        background: #f8f9fa;
        color: #495057;
        border: none;
        font-size: 0.85rem;
        font-weight: 500;
        transition: all 0.2s;
        margin: 0 5px;
        cursor: pointer;
    }
    
    .action-btn:first-child {
        margin-left: 0;
    }
    
    .action-btn:last-child {
        margin-right: 0;
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
    
    .action-btn.success {
        background-color: #e6f7ed;
        color: #28a745;
    }
    
    .action-btn.success:hover {
        background-color: #d1eedf;
    }
    
    .action-btn.warning {
        background-color: #fff3cd;
        color: #ffc107;
    }
    
    .action-btn.warning:hover {
        background-color: #ffe69c;
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
    
    /* Empty state styling */
    .empty-state {
        text-align: center;
        padding: 50px 20px;
        background: white;
        border-radius: 16px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.03);
        border: 1px solid rgba(230, 230, 250, 0.7);
    }
    
    .empty-state i {
        font-size: 3rem;
        color: #6c757d;
        margin-bottom: 20px;
        display: block;
    }
    
    .empty-state h3 {
        font-size: 1.2rem;
        margin-bottom: 10px;
        color: #495057;
    }
    
    .empty-state p {
        color: #6c757d;
        margin-bottom: 20px;
    }
    
    /* Pagination styling */
    .pagination-container {
        margin-top: 30px;
        display: flex;
        justify-content: flex-end;
    }
    
    .pagination {
        display: flex;
        padding-left: 0;
        list-style: none;
        border-radius: 0.25rem;
    }
    
    .page-item:first-child .page-link {
        border-top-left-radius: 10px;
        border-bottom-left-radius: 10px;
    }
    
    .page-item:last-child .page-link {
        border-top-right-radius: 10px;
        border-bottom-right-radius: 10px;
    }
    
    .page-link {
        position: relative;
        display: block;
        padding: 0.5rem 0.75rem;
        margin-left: -1px;
        line-height: 1.25;
        color: #3d6eea;
        background-color: #fff;
        border: 1px solid #dee2e6;
        transition: all 0.2s ease;
    }
    
    .page-link:hover {
        z-index: 2;
        color: #6e47ef;
        text-decoration: none;
        background-color: #e9ecef;
        border-color: #dee2e6;
    }
    
    .page-item.active .page-link {
        z-index: 3;
        color: #fff;
        background: linear-gradient(135deg, #3d6eea, #6e47ef);
        border-color: #3d6eea;
    }
    
    .page-item.disabled .page-link {
        color: #6c757d;
        pointer-events: none;
        cursor: auto;
        background-color: #fff;
        border-color: #dee2e6;
    }
</style>

<div class="container-fluid">
    <!-- Header section with gradient background -->
    <div class="products-header d-flex justify-content-between align-items-center">
        <div>
            <h2>Catálogo de Productos</h2>
            <p>Gestión de productos y sincronización entre plataformas</p>
        </div>
        <a href="{{ route('products.create') }}" class="btn btn-light rounded-pill px-4">
            <i class="bi bi-plus me-2"></i>Nuevo Producto
        </a>
    </div>
    
    <!-- Filter bar -->
    <div class="filter-bar">
        <div class="search-box">
            <i class="bi bi-search"></i>
            <input type="text" placeholder="Buscar productos..." 
                   form="filter-form" name="search" value="{{ request('search') }}">
        </div>
        
        <form id="filter-form" action="{{ route('products.index') }}" method="GET" class="d-flex align-items-center gap-2">
            <select class="filter-select" name="category">
                <option value="all" {{ request('category') == 'all' ? 'selected' : '' }}>Todas las categorías</option>
                @foreach($categories as $category)
                    <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                        {{ $category }}
                    </option>
                @endforeach
            </select>
            
            <select class="filter-select" name="sync_status">
                <option value="all" {{ request('sync_status') == 'all' ? 'selected' : '' }}>Todos los estados</option>
                <option value="sincronizado" {{ request('sync_status') == 'sincronizado' ? 'selected' : '' }}>Sincronizados</option>
                <option value="precio_desincronizado" {{ request('sync_status') == 'precio_desincronizado' ? 'selected' : '' }}>Precio desincronizado</option>
                <option value="stock_desincronizado" {{ request('sync_status') == 'stock_desincronizado' ? 'selected' : '' }}>Stock desincronizado</option>
                <option value="desincronizado" {{ request('sync_status') == 'desincronizado' ? 'selected' : '' }}>Completamente desincronizados</option>
            </select>
            
            <button type="submit" class="btn btn-primary rounded-pill px-4">Filtrar</button>
            
            @if(request('search') || request('category') != 'all' || request('sync_status') != 'all')
                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary rounded-pill px-3">
                    <i class="bi bi-x-circle me-1"></i>Limpiar
                </a>
            @endif
        </form>
    </div>
    
    @if($products->count() > 0)
        <!-- Products grid -->
        <div class="product-grid">
            @foreach($products as $product)
                <div class="product-card">
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="product-image">
                    
                    <div class="product-content">
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
                            
                            $stockStatus = 'in-stock';
                            $stockLabel = 'En stock';
                            
                            if($product->stock <= 0) {
                                $stockStatus = 'out-stock';
                                $stockLabel = 'Sin stock';
                            } elseif($product->stock < 10) {
                                $stockStatus = 'low-stock';
                                $stockLabel = 'Stock bajo';
                            }
                        @endphp
                        
                        <span class="status-badge {{ $statusClass }}">{{ $statusLabel }}</span>
                        
                        <h3 class="product-name">{{ $product->name }}</h3>
                        <div class="product-category">{{ $product->category }}</div>
                        
                        <div class="product-prices">
                            <span class="product-price">{{ number_format($product->price, 2) }}€</span>
                            
                            @if($product->price_difference != 0)
                                <span class="product-price-diff {{ $product->price_difference > 0 ? 'price-up' : 'price-down' }}">
                                    {{ $product->price_difference > 0 ? '+' : '' }}{{ number_format($product->price_difference, 2) }}€
                                </span>
                            @endif
                        </div>
                        
                        <div class="product-stock">
                            <span>Stock: {{ $product->stock }}</span>
                            <span class="stock-badge {{ $stockStatus }}">{{ $stockLabel }}</span>
                        </div>
                        
                        <div class="product-actions">
                            <a href="{{ route('products.show', $product) }}" class="action-btn primary">
                                <i class="bi bi-eye"></i> Ver
                            </a>
                            <a href="{{ route('products.edit', $product) }}" class="action-btn">
                                <i class="bi bi-pencil"></i> Editar
                            </a>
                            @if($syncStatus !== 'sincronizado')
                                <button type="button" class="action-btn success" data-bs-toggle="modal" data-bs-target="#syncModal{{ $product->id }}">
                                    <i class="bi bi-arrow-repeat"></i> Sync
                                </button>
                                
                                <!-- Sync Modal -->
                                <div class="modal fade" id="syncModal{{ $product->id }}" tabindex="-1" aria-hidden="true">
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
                                                            <input class="form-check-input" type="radio" name="platform" id="amazon{{ $product->id }}" value="amazon" checked>
                                                            <label class="form-check-label" for="amazon{{ $product->id }}">
                                                                Amazon → Actualizar con datos de BigBuy
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="platform" id="bigbuy{{ $product->id }}" value="bigbuy">
                                                            <label class="form-check-label" for="bigbuy{{ $product->id }}">
                                                                BigBuy → Actualizar con datos de Amazon
                                                            </label>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="mb-3">
                                                        <label class="form-label">Datos a sincronizar:</label>
                                                        <div class="form-check mb-2">
                                                            <input class="form-check-input" type="radio" name="type" id="both{{ $product->id }}" value="both" checked>
                                                            <label class="form-check-label" for="both{{ $product->id }}">
                                                                Precio y stock
                                                            </label>
                                                        </div>
                                                        <div class="form-check mb-2">
                                                            <input class="form-check-input" type="radio" name="type" id="price{{ $product->id }}" value="price">
                                                            <label class="form-check-label" for="price{{ $product->id }}">
                                                                Solo precio
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="type" id="stock{{ $product->id }}" value="stock">
                                                            <label class="form-check-label" for="stock{{ $product->id }}">
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
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Pagination -->
        <div class="pagination-container">
            {{ $products->links() }}
        </div>
    @else
        <!-- Empty state -->
        <div class="empty-state">
            <i class="bi bi-box"></i>
            <h3>No hay productos disponibles</h3>
            <p>Aún no has añadido ningún producto a tu catálogo o no hay coincidencias con los filtros aplicados.</p>
            <a href="{{ route('products.create') }}" class="btn btn-primary rounded-pill px-4">
                <i class="bi bi-plus me-2"></i>Añadir primer producto
            </a>
        </div>
    @endif
</div>
@endsection
