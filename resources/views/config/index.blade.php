@extends('layouts.app')

@section('content')
<style>
    /* Header styles */
    .config-header {
        background: linear-gradient(135deg, #3d6eea 0%, #6e47ef 100%);
        color: white;
        padding: 20px 30px;
        border-radius: 15px;
        margin-bottom: 25px;
        box-shadow: 0 4px 20px rgba(61, 110, 234, 0.15);
    }
    
    .config-header h2 {
        font-size: 1.5rem;
        font-weight: 500;
        margin: 0;
    }
    
    .config-header p {
        margin: 0;
        opacity: 0.9;
        font-size: 0.85rem;
    }
    
    /* Card styles */
    .config-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.04);
        overflow: hidden;
        transition: all 0.3s ease;
        border: 1px solid rgba(230, 230, 230, 0.5);
        margin-bottom: 30px;
    }
    
    .config-card:hover {
        box-shadow: 0 8px 25px rgba(61, 110, 234, 0.1);
        transform: translateY(-3px);
    }
    
    .config-card-header {
        padding: 20px 25px;
        border-bottom: 1px solid #f5f5f5;
        background: rgba(249, 250, 251, 0.5);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .config-card-title {
        font-size: 18px;
        font-weight: 600;
        color: #333;
        margin: 0;
        display: flex;
        align-items: center;
    }
    
    .config-card-body {
        padding: 25px;
    }
    
    .platform-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
        font-size: 1.4rem;
        flex-shrink: 0;
        color: white;
    }
    
    .icon-amazon {
        background: linear-gradient(135deg, #ff9900, #ffb144);
    }
    
    .icon-bigbuy {
        background: linear-gradient(135deg, #3d6eea, #6e47ef);
    }
    
    .icon-sync {
        background: linear-gradient(135deg, #42b883, #00c6ff);
    }
    
    .icon-general {
        background: linear-gradient(135deg, #6e47ef, #ff3e9d);
    }
    
    /* Form styles */
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
    
    .input-group-text {
        border-radius: 8px 0 0 8px;
        border: 1px solid #e2e8f0;
        background-color: #f8fafc;
    }
    
    .form-floating > .form-control {
        height: calc(3.5rem + 2px);
        padding: 1rem 0.75rem;
    }
    
    .form-floating > label {
        padding: 1rem 0.75rem;
    }
    
    /* Button styles */
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
    
    .btn-outline-primary {
        border: 1px solid #3d6eea;
        color: #3d6eea;
        border-radius: 8px;
        padding: 10px 20px;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .btn-outline-primary:hover {
        background: linear-gradient(135deg, #3d6eea 0%, #6e47ef 100%);
        border-color: transparent;
        color: white;
        transform: translateY(-2px);
    }
    
    .btn-sync {
        background: linear-gradient(135deg, #42b883, #00c6ff);
        color: white;
        border: none;
        border-radius: 8px;
        padding: 10px 20px;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .btn-sync:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(66, 184, 131, 0.2);
    }
    
    /* Switch toggle style */
    .form-check-input {
        width: 3em;
        height: 1.5em;
        margin-top: 0.25em;
        vertical-align: top;
        background-color: #fff;
        background-repeat: no-repeat;
        background-position: center;
        background-size: contain;
        border: 1px solid #e2e8f0;
        appearance: none;
        -webkit-print-color-adjust: exact;
        color-adjust: exact;
        transition: background-color 0.15s ease-in-out, 
                  background-position 0.15s ease-in-out, 
                  border-color 0.15s ease-in-out,
                  box-shadow 0.15s ease-in-out,
                  transform 0.15s ease-in-out;
    }

    .form-check-input:checked {
        background-color: #3d6eea;
        border-color: #3d6eea;
    }
    
    .form-switch .form-check-input {
        width: 3em;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3E%3Ccircle r='3' fill='rgba%280, 0, 0, 0.25%29'/%3E%3C/svg%3E");
        background-position: left center;
        border-radius: 2em;
        transition: background-position 0.15s ease-in-out, transform 0.15s ease-in-out;
    }
    
    .form-switch .form-check-input:checked {
        background-position: right center;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3E%3Ccircle r='3' fill='%23fff'/%3E%3C/svg%3E");
    }
    
    .form-switch .form-check-input:focus {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3E%3Ccircle r='3' fill='rgba%280, 0, 0, 0.25%29'/%3E%3C/svg%3E");
    }
    
    .form-switch .form-check-input:checked:focus {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3E%3Ccircle r='3' fill='%23fff'/%3E%3C/svg%3E");
    }
    
    /* Tab styles */
    .nav-tabs {
        border-bottom: 1px solid #e5e7eb;
        margin-bottom: 25px;
    }
    
    .nav-tabs .nav-link {
        margin-bottom: -1px;
        border: none;
        border-bottom: 3px solid transparent;
        color: #6b7280;
        font-weight: 500;
        padding: 10px 20px;
        transition: all 0.2s ease;
    }
    
    .nav-tabs .nav-link:hover {
        border-color: transparent;
        color: #3d6eea;
    }
    
    .nav-tabs .nav-link.active {
        color: #3d6eea;
        background-color: transparent;
        border-color: #3d6eea;
    }
    
    /* Badge styles */
    .connection-status {
        display: inline-flex;
        align-items: center;
        padding: 6px 12px;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: 600;
    }
    
    .status-connected {
        background-color: rgba(66, 184, 131, 0.1);
        color: #42b883;
    }
    
    .status-not-connected {
        background-color: rgba(240, 82, 82, 0.1);
        color: #f05252;
    }
</style>

<!-- Header -->
<div class="config-header d-flex justify-content-between align-items-center">
    <div>
        <h2>Panel de Configuración</h2>
        <p>Gestiona las integraciones y preferencias del sistema</p>
    </div>
    <a href="{{ route('dashboard') }}" class="btn btn-light">
        <i class="bi bi-arrow-left me-2"></i>Volver al Dashboard
    </a>
</div>

<div class="container-fluid px-0">
    <!-- Navigation Tabs -->
    <ul class="nav nav-tabs" id="configTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="amazon-tab" data-bs-toggle="tab" data-bs-target="#amazon" type="button" role="tab" aria-controls="amazon" aria-selected="true">
                <i class="bi bi-amazon me-2"></i>Amazon
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="bigbuy-tab" data-bs-toggle="tab" data-bs-target="#bigbuy" type="button" role="tab" aria-controls="bigbuy" aria-selected="false">
                <i class="bi bi-bag me-2"></i>BigBuy
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="sync-tab" data-bs-toggle="tab" data-bs-target="#sync" type="button" role="tab" aria-controls="sync" aria-selected="false">
                <i class="bi bi-arrow-repeat me-2"></i>Sincronización
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button" role="tab" aria-controls="general" aria-selected="false">
                <i class="bi bi-gear me-2"></i>General
            </button>
        </li>
    </ul>
    
    <!-- Tab Content -->
    <div class="tab-content" id="configTabContent">
        <!-- Amazon Tab -->
        <div class="tab-pane fade show active" id="amazon" role="tabpanel" aria-labelledby="amazon-tab">
            <div class="config-card">
                <div class="config-card-header">
                    <h5 class="config-card-title">
                        <div class="platform-icon icon-amazon">
                            <i class="bi bi-amazon"></i>
                        </div>
                        Configuración de Amazon
                    </h5>
                    <div class="connection-status {{ !empty($amazonConfig['api_key']) ? 'status-connected' : 'status-not-connected' }}" id="amazon-status">
                        <i class="bi {{ !empty($amazonConfig['api_key']) ? 'bi-check-circle-fill' : 'bi-x-circle-fill' }} me-2"></i>
                        {{ !empty($amazonConfig['api_key']) ? 'Conectado' : 'No conectado' }}
                    </div>
                </div>
                <div class="config-card-body">
                    <form action="{{ route('config.update.amazon') }}" method="POST" id="amazonForm">
                        @csrf
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label for="amazon_api_key" class="form-label">API Key</label>
                                <input type="text" class="form-control @error('api_key') is-invalid @enderror" id="amazon_api_key" name="api_key" value="{{ $amazonConfig['api_key'] ?? '' }}" required>
                                @error('api_key')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Clave de acceso para la API de Amazon Marketplace</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="amazon_api_secret" class="form-label">API Secret</label>
                                <input type="password" class="form-control @error('api_secret') is-invalid @enderror" id="amazon_api_secret" name="api_secret" value="{{ $amazonConfig['api_secret'] ?? '' }}" required>
                                @error('api_secret')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Clave secreta para la API de Amazon Marketplace</small>
                            </div>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label for="amazon_marketplace_id" class="form-label">Marketplace</label>
                                <select class="form-select @error('marketplace_id') is-invalid @enderror" id="amazon_marketplace_id" name="marketplace_id" required>
                                    @foreach($marketplaces as $id => $name)
                                        <option value="{{ $id }}" {{ ($amazonConfig['marketplace_id'] ?? '') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                                @error('marketplace_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="amazon_sync_interval" class="form-label">Intervalo de Sincronización (minutos)</label>
                                <input type="number" min="15" max="1440" class="form-control @error('sync_interval') is-invalid @enderror" id="amazon_sync_interval" name="sync_interval" value="{{ $amazonConfig['sync_interval'] ?? 60 }}" required>
                                @error('sync_interval')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Tiempo entre sincronizaciones automáticas</small>
                            </div>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="amazon_auto_sync" name="auto_sync" value="1" {{ ($amazonConfig['auto_sync'] ?? false) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="amazon_auto_sync">Sincronización Automática</label>
                                </div>
                                <small class="text-muted d-block mt-2">Habilitar sincronización automática con Amazon</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="amazon_notify_changes" name="notify_changes" value="1" {{ ($amazonConfig['notify_changes'] ?? false) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="amazon_notify_changes">Notificar Cambios</label>
                                </div>
                                <small class="text-muted d-block mt-2">Recibir notificaciones cuando haya cambios</small>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-outline-primary test-connection" data-platform="amazon">
                                <i class="bi bi-speedometer2 me-2"></i>Probar Conexión
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-2"></i>Guardar Configuración
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- BigBuy Tab -->
        <div class="tab-pane fade" id="bigbuy" role="tabpanel" aria-labelledby="bigbuy-tab">
            <div class="config-card">
                <div class="config-card-header">
                    <h5 class="config-card-title">
                        <div class="platform-icon icon-bigbuy">
                            <i class="bi bi-bag"></i>
                        </div>
                        Configuración de BigBuy
                    </h5>
                    <div class="connection-status {{ !empty($bigbuyConfig['api_key']) ? 'status-connected' : 'status-not-connected' }}" id="bigbuy-status">
                        <i class="bi {{ !empty($bigbuyConfig['api_key']) ? 'bi-check-circle-fill' : 'bi-x-circle-fill' }} me-2"></i>
                        {{ !empty($bigbuyConfig['api_key']) ? 'Conectado' : 'No conectado' }}
                    </div>
                </div>
                <div class="config-card-body">
                    <form action="{{ route('config.update.bigbuy') }}" method="POST" id="bigbuyForm">
                        @csrf
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label for="bigbuy_api_key" class="form-label">API Key</label>
                                <input type="text" class="form-control @error('api_key') is-invalid @enderror" id="bigbuy_api_key" name="api_key" value="{{ $bigbuyConfig['api_key'] ?? '' }}" required>
                                @error('api_key')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Clave de acceso para la API de BigBuy</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="bigbuy_account_id" class="form-label">ID de Cuenta</label>
                                <input type="text" class="form-control @error('account_id') is-invalid @enderror" id="bigbuy_account_id" name="account_id" value="{{ $bigbuyConfig['account_id'] ?? '' }}" required>
                                @error('account_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Identificador de su cuenta en BigBuy</small>
                            </div>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label for="bigbuy_sync_interval" class="form-label">Intervalo de Sincronización (minutos)</label>
                                <input type="number" min="15" max="1440" class="form-control @error('sync_interval') is-invalid @enderror" id="bigbuy_sync_interval" name="sync_interval" value="{{ $bigbuyConfig['sync_interval'] ?? 60 }}" required>
                                @error('sync_interval')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Tiempo entre sincronizaciones automáticas</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-check form-switch mt-4">
                                    <input class="form-check-input" type="checkbox" id="bigbuy_auto_sync" name="auto_sync" value="1" {{ ($bigbuyConfig['auto_sync'] ?? false) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="bigbuy_auto_sync">Sincronización Automática</label>
                                </div>
                                <small class="text-muted d-block mt-2">Habilitar sincronización automática con BigBuy</small>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="bigbuy_notify_changes" name="notify_changes" value="1" {{ ($bigbuyConfig['notify_changes'] ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label" for="bigbuy_notify_changes">Notificar Cambios</label>
                            </div>
                            <small class="text-muted d-block mt-2">Recibir notificaciones cuando haya cambios</small>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-outline-primary test-connection" data-platform="bigbuy">
                                <i class="bi bi-speedometer2 me-2"></i>Probar Conexión
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-2"></i>Guardar Configuración
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Sync Tab -->
        <div class="tab-pane fade" id="sync" role="tabpanel" aria-labelledby="sync-tab">
            <div class="config-card">
                <div class="config-card-header">
                    <h5 class="config-card-title">
                        <div class="platform-icon icon-sync">
                            <i class="bi bi-arrow-repeat"></i>
                        </div>
                        Configuración de Sincronización
                    </h5>
                    <button class="btn btn-sync" id="sync-now-btn">
                        <i class="bi bi-arrow-clockwise me-2"></i>Sincronizar Ahora
                    </button>
                </div>
                <div class="config-card-body">
                    <form action="{{ route('config.update.sync') }}" method="POST" id="syncForm">
                        @csrf
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="auto_sync_products" name="auto_sync_products" value="1" {{ ($syncConfig['auto_sync_products'] ?? false) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="auto_sync_products">Sincronización Automática de Productos</label>
                                </div>
                                <small class="text-muted d-block mt-2">Sincronizar automáticamente precios, stock e información de productos</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="auto_sync_orders" name="auto_sync_orders" value="1" {{ ($syncConfig['auto_sync_orders'] ?? false) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="auto_sync_orders">Sincronización Automática de Pedidos</label>
                                </div>
                                <small class="text-muted d-block mt-2">Sincronizar automáticamente pedidos y actualizaciones de estado</small>
                            </div>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label for="sync_schedule" class="form-label">Programación de Sincronización</label>
                                <select class="form-select @error('sync_schedule') is-invalid @enderror" id="sync_schedule" name="sync_schedule" required>
                                    <option value="hourly" {{ ($syncConfig['sync_schedule'] ?? '') == 'hourly' ? 'selected' : '' }}>Cada hora</option>
                                    <option value="daily" {{ ($syncConfig['sync_schedule'] ?? '') == 'daily' ? 'selected' : '' }}>Diariamente</option>
                                    <option value="manual" {{ ($syncConfig['sync_schedule'] ?? '') == 'manual' ? 'selected' : '' }}>Manual</option>
                                </select>
                                @error('sync_schedule')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Frecuencia para sincronizar datos entre plataformas</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="sync_conflicts" class="form-label">Resolución de Conflictos</label>
                                <select class="form-select @error('sync_conflicts') is-invalid @enderror" id="sync_conflicts" name="sync_conflicts" required>
                                    <option value="auto_resolve" {{ ($syncConfig['sync_conflicts'] ?? '') == 'auto_resolve' ? 'selected' : '' }}>Automática</option>
                                    <option value="manual" {{ ($syncConfig['sync_conflicts'] ?? '') == 'manual' ? 'selected' : '' }}>Manual</option>
                                </select>
                                @error('sync_conflicts')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Cómo resolver conflictos de datos entre plataformas</small>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="price_variation_threshold" class="form-label">Umbral de Variación de Precio (%)</label>
                            <input type="number" min="0" max="100" class="form-control @error('price_variation_threshold') is-invalid @enderror" id="price_variation_threshold" name="price_variation_threshold" value="{{ $syncConfig['price_variation_threshold'] ?? 5 }}" required>
                            @error('price_variation_threshold')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Porcentaje máximo de variación de precio permitido antes de requerir aprobación manual</small>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <button type="button" class="btn btn-outline-primary" id="sync-products-btn" data-type="products">
                                    <i class="bi bi-box me-2"></i>Sincronizar Productos
                                </button>
                            </div>
                            <div class="col-md-6 mb-3">
                                <button type="button" class="btn btn-outline-primary" id="sync-orders-btn" data-type="orders">
                                    <i class="bi bi-receipt me-2"></i>Sincronizar Pedidos
                                </button>
                            </div>
                        </div>
                        
                        <hr class="my-4">
                        
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-2"></i>Guardar Configuración
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- General Tab -->
        <div class="tab-pane fade" id="general" role="tabpanel" aria-labelledby="general-tab">
            <div class="config-card">
                <div class="config-card-header">
                    <h5 class="config-card-title">
                        <div class="platform-icon icon-general">
                            <i class="bi bi-gear"></i>
                        </div>
                        Configuración General
                    </h5>
                </div>
                <div class="config-card-body">
                    <form action="{{ route('config.update.general') }}" method="POST" id="generalForm">
                        @csrf
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label for="company_name" class="form-label">Nombre de la Empresa</label>
                                <input type="text" class="form-control @error('company_name') is-invalid @enderror" id="company_name" name="company_name" value="{{ $generalConfig['company_name'] ?? '' }}" required>
                                @error('company_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="company_email" class="form-label">Email de Contacto</label>
                                <input type="email" class="form-control @error('company_email') is-invalid @enderror" id="company_email" name="company_email" value="{{ $generalConfig['company_email'] ?? '' }}" required>
                                @error('company_email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3">
                                <label for="default_currency" class="form-label">Moneda Predeterminada</label>
                                <select class="form-select @error('default_currency') is-invalid @enderror" id="default_currency" name="default_currency" required>
                                    <option value="EUR" {{ ($generalConfig['default_currency'] ?? '') == 'EUR' ? 'selected' : '' }}>Euro (EUR)</option>
                                    <option value="USD" {{ ($generalConfig['default_currency'] ?? '') == 'USD' ? 'selected' : '' }}>Dólar Estadounidense (USD)</option>
                                    <option value="GBP" {{ ($generalConfig['default_currency'] ?? '') == 'GBP' ? 'selected' : '' }}>Libra Esterlina (GBP)</option>
                                </select>
                                @error('default_currency')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email_digest" class="form-label">Resumen por Email</label>
                                <select class="form-select @error('email_digest') is-invalid @enderror" id="email_digest" name="email_digest" required>
                                    <option value="daily" {{ ($generalConfig['email_digest'] ?? '') == 'daily' ? 'selected' : '' }}>Diario</option>
                                    <option value="weekly" {{ ($generalConfig['email_digest'] ?? '') == 'weekly' ? 'selected' : '' }}>Semanal</option>
                                    <option value="realtime" {{ ($generalConfig['email_digest'] ?? '') == 'realtime' ? 'selected' : '' }}>Tiempo Real</option>
                                </select>
                                @error('email_digest')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="notifications_email" name="notifications_email" value="1" {{ ($generalConfig['notifications_email'] ?? false) ? 'checked' : '' }}>
                                <label class="form-check-label" for="notifications_email">Notificaciones por Email</label>
                            </div>
                            <small class="text-muted">Recibir notificaciones por email sobre cambios, actualizaciones y eventos</small>
                        </div>
                        
                        <hr class="my-4">
                        
                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-2"></i>Guardar Configuración
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Estado de Conexión -->
<div class="modal fade" id="connectionModal" tabindex="-1" aria-labelledby="connectionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="connectionModalLabel">Estado de Conexión</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="connection-loading" class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Probando conexión...</span>
                    </div>
                    <p class="mt-3">Probando conexión, por favor espera...</p>
                </div>
                <div id="connection-result" class="d-none">
                    <div id="connection-success" class="alert alert-success d-none" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        <span id="success-message">Conexión establecida correctamente.</span>
                    </div>
                    <div id="connection-error" class="alert alert-danger d-none" role="alert">
                        <i class="bi bi-x-circle-fill me-2"></i>
                        <span id="error-message">Error al conectar. Verifica tus credenciales.</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Sincronización -->
<div class="modal fade" id="syncModal" tabindex="-1" aria-labelledby="syncModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="syncModalLabel">Sincronización</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="sync-loading" class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Iniciando sincronización...</span>
                    </div>
                    <p class="mt-3">Iniciando proceso de sincronización, por favor espera...</p>
                </div>
                <div id="sync-result" class="d-none">
                    <div id="sync-success" class="alert alert-success d-none" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        <span id="sync-success-message">Sincronización iniciada correctamente.</span>
                    </div>
                    <div id="sync-error" class="alert alert-danger d-none" role="alert">
                        <i class="bi bi-x-circle-fill me-2"></i>
                        <span id="sync-error-message">Error al iniciar la sincronización.</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Manejador para probar conexión
        const testConnectionButtons = document.querySelectorAll('.test-connection');
        testConnectionButtons.forEach(button => {
            button.addEventListener('click', function() {
                const platform = this.dataset.platform;
                const connectionModal = new bootstrap.Modal(document.getElementById('connectionModal'));
                
                // Resetear el modal
                document.getElementById('connection-loading').classList.remove('d-none');
                document.getElementById('connection-result').classList.add('d-none');
                document.getElementById('connection-success').classList.add('d-none');
                document.getElementById('connection-error').classList.add('d-none');
                
                // Mostrar modal
                connectionModal.show();
                
                // Simular llamada AJAX para probar la conexión
                setTimeout(() => {
                    // Ocultar loader
                    document.getElementById('connection-loading').classList.add('d-none');
                    document.getElementById('connection-result').classList.remove('d-none');
                    
                    // Simular éxito (80% de probabilidad)
                    const success = Math.random() < 0.8;
                    
                    if (success) {
                        document.getElementById('connection-success').classList.remove('d-none');
                        document.getElementById('success-message').textContent = `Conexión con ${platform === 'amazon' ? 'Amazon' : 'BigBuy'} establecida correctamente.`;
                        
                        // Actualizar el estado en la interfaz
                        const statusBadge = document.getElementById(`${platform}-status`);
                        statusBadge.innerHTML = '<i class="bi bi-check-circle-fill me-2"></i>Conectado';
                        statusBadge.className = 'connection-status status-connected';
                    } else {
                        document.getElementById('connection-error').classList.remove('d-none');
                        document.getElementById('error-message').textContent = `Error al conectar con ${platform === 'amazon' ? 'Amazon' : 'BigBuy'}. Verifica tus credenciales.`;
                    }
                }, 2000);
            });
        });
        
        // Manejador para sincronización
        const syncButtons = document.querySelectorAll('#sync-products-btn, #sync-orders-btn, #sync-now-btn');
        syncButtons.forEach(button => {
            button.addEventListener('click', function() {
                const type = this.dataset.type || 'all';
                const syncModal = new bootstrap.Modal(document.getElementById('syncModal'));
                
                // Resetear el modal
                document.getElementById('sync-loading').classList.remove('d-none');
                document.getElementById('sync-result').classList.add('d-none');
                document.getElementById('sync-success').classList.add('d-none');
                document.getElementById('sync-error').classList.add('d-none');
                
                // Mostrar modal
                syncModal.show();
                
                // Simular llamada AJAX para iniciar sincronización
                setTimeout(() => {
                    // Ocultar loader
                    document.getElementById('sync-loading').classList.add('d-none');
                    document.getElementById('sync-result').classList.remove('d-none');
                    
                    // Siempre éxito en la demo
                    document.getElementById('sync-success').classList.remove('d-none');
                    
                    let message = '';
                    switch (type) {
                        case 'products':
                            message = 'Sincronización de productos iniciada. Recibirás una notificación cuando finalice.';
                            break;
                        case 'orders':
                            message = 'Sincronización de pedidos iniciada. Recibirás una notificación cuando finalice.';
                            break;
                        default:
                            message = 'Sincronización completa iniciada. Recibirás una notificación cuando finalice.';
                    }
                    
                    document.getElementById('sync-success-message').textContent = message;
                }, 2000);
            });
        });
    });
</script>
@endsection
