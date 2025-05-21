@extends('layouts.app')

@section('content')
<style>
    /* Header styles */
    .reports-header {
        background: linear-gradient(135deg, #3d6eea 0%, #6e47ef 100%);
        color: white;
        padding: 20px 30px;
        border-radius: 15px;
        margin-bottom: 25px;
        box-shadow: 0 4px 20px rgba(61, 110, 234, 0.15);
    }
    
    .reports-header h2 {
        font-size: 1.5rem;
        font-weight: 500;
        margin: 0;
    }
    
    .reports-header p {
        margin: 0;
        opacity: 0.9;
        font-size: 0.85rem;
    }
    
    /* Card styles */
    .stats-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 5px 15px rgba(61, 110, 234, 0.06);
        overflow: hidden;
        border: 1px solid rgba(230, 230, 250, 0.7);
        margin-bottom: 25px;
        transition: all 0.3s ease;
        height: 100%;
    }
    
    .stats-card:hover {
        box-shadow: 0 8px 25px rgba(61, 110, 234, 0.1);
        transform: translateY(-3px);
    }
    
    .card-header {
        padding: 15px 20px;
        border-bottom: 1px solid #f5f7fa;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: white;
    }
    
    .card-header h5 {
        font-size: 1rem;
        font-weight: 600;
        color: #333;
        margin: 0;
        display: flex;
        align-items: center;
    }
    
    .card-header h5 i {
        margin-right: 10px;
        font-size: 1.1rem;
        color: #3d6eea;
    }
    
    .metric-card {
        padding: 20px;
        text-align: center;
        transition: all 0.3s ease;
        height: 100%;
    }
    
    .metric-value {
        font-size: 2.2rem;
        font-weight: 600;
        margin: 10px 0;
        background: linear-gradient(135deg, #3d6eea, #6e47ef);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    
    .metric-label {
        color: #718096;
        font-size: 0.9rem;
        margin-bottom: 0;
    }
    
    .metric-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px;
        font-size: 1.5rem;
    }
    
    .icon-orders {
        background: linear-gradient(135deg, #3d6eea, #6e47ef);
        color: white;
    }
    
    .icon-products {
        background: linear-gradient(135deg, #00c6ff, #0072ff);
        color: white;
    }
    
    .icon-revenue {
        background: linear-gradient(135deg, #42b883, #3d6eea);
        color: white;
    }
    
    .icon-customers {
        background: linear-gradient(135deg, #ff3e9d, #0e1f40);
        color: white;
    }
    
    .icon-pending {
        background: linear-gradient(135deg, #f7b733, #fc4a1a);
        color: white;
    }
    
    .icon-shipped {
        background: linear-gradient(135deg, #00c6ff, #0072ff);
        color: white;
    }
    
    .icon-platform {
        background: linear-gradient(135deg, #6e47ef, #3d6eea);
        color: white;
    }
    
    /* Chart card styles */
    .chart-container {
        padding: 15px;
        height: 300px;
        position: relative;
    }
    
    /* Period selector styles */
    .period-selector {
        display: inline-flex;
        background: #f8fafc;
        border-radius: 10px;
        padding: 2px;
        border: 1px solid #e2e8f0;
    }
    
    .period-option {
        padding: 8px 15px;
        font-size: 0.85rem;
        font-weight: 500;
        color: #718096;
        border-radius: 8px;
        transition: all 0.2s;
        cursor: pointer;
    }
    
    .period-option.active {
        background: white;
        color: #3d6eea;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }
    
    /* Tab styles */
    .report-tabs .nav-link {
        padding: 15px 20px;
        border: none;
        border-bottom: 3px solid transparent;
        color: #718096;
        font-weight: 500;
        transition: all 0.2s;
    }
    
    .report-tabs .nav-link.active {
        border-bottom-color: #3d6eea;
        color: #3d6eea;
        background: transparent;
    }
    
    .report-tabs .nav-link:hover:not(.active) {
        border-bottom-color: #e2e8f0;
    }
    
    /* Legend styles */
    .custom-legend {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        margin-top: 15px;
    }
    
    .legend-item {
        display: flex;
        align-items: center;
        margin-right: 20px;
        margin-bottom: 10px;
    }
    
    .legend-color {
        width: 12px;
        height: 12px;
        border-radius: 3px;
        margin-right: 8px;
    }
    
    .legend-label {
        font-size: 0.8rem;
        color: #718096;
    }
    
    /* Table styles */
    .top-table {
        width: 100%;
    }
    
    .top-table th {
        padding: 12px 15px;
        font-weight: 500;
        color: #718096;
        font-size: 0.85rem;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .top-table td {
        padding: 12px 15px;
        vertical-align: middle;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .top-table tr:last-child td {
        border-bottom: none;
    }
    
    .top-table tr:hover {
        background-color: #f9fafc;
    }
    
    /* Badges */
    .platform-badge {
        display: inline-block;
        padding: 5px 10px;
        font-size: 0.75rem;
        font-weight: 500;
        border-radius: 30px;
    }
    
    .platform-badge.amazon {
        background: linear-gradient(135deg, #ff9900, #ffb144);
        color: white;
    }
    
    .platform-badge.bigbuy {
        background: linear-gradient(135deg, #3d6eea, #6e47ef);
        color: white;
    }
    
    .platform-badge.both {
        background: linear-gradient(135deg, #42b883, #00c6ff);
        color: white;
    }
</style>

<div class="container-fluid">
    <!-- Header section with gradient background -->
    <div class="reports-header d-flex justify-content-between align-items-center">
        <div>
            <h2>Estadísticas e Informes</h2>
            <p>Análisis detallado del rendimiento del negocio</p>
        </div>
        <div class="d-flex align-items-center">
            <div class="period-selector me-3">
                <a href="{{ route('reports.index', ['period' => 'week']) }}" class="period-option {{ $period == 'week' ? 'active' : '' }}">7 días</a>
                <a href="{{ route('reports.index', ['period' => 'month']) }}" class="period-option {{ $period == 'month' ? 'active' : '' }}">30 días</a>
                <a href="{{ route('reports.index', ['period' => 'quarter']) }}" class="period-option {{ $period == 'quarter' ? 'active' : '' }}">3 meses</a>
                <a href="{{ route('reports.index', ['period' => 'year']) }}" class="period-option {{ $period == 'year' ? 'active' : '' }}">1 año</a>
            </div>
            <div class="dropdown">
                <button class="btn btn-light dropdown-toggle" type="button" id="exportDropdown" data-bs-toggle="dropdown">
                    <i class="bi bi-download me-2"></i>Exportar
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="exportDropdown">
                    <li><a class="dropdown-item" href="#"><i class="bi bi-file-earmark-excel me-2"></i>Excel</a></li>
                    <li><a class="dropdown-item" href="#"><i class="bi bi-file-earmark-pdf me-2"></i>PDF</a></li>
                    <li><a class="dropdown-item" href="#"><i class="bi bi-file-earmark-text me-2"></i>CSV</a></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- KPIs Row -->
    <div class="row mb-4">
        <!-- Pedidos Totales -->
        <div class="col-md-3">
            <div class="stats-card">
                <div class="metric-card">
                    <div class="metric-icon icon-orders">
                        <i class="bi bi-box"></i>
                    </div>
                    <div class="metric-value">{{ number_format($stats['total_orders']) }}</div>
                    <p class="metric-label">Pedidos Totales</p>
                    <div class="small text-muted">{{ number_format($stats['orders_period']) }} en el período actual</div>
                </div>
            </div>
        </div>
        
        <!-- Ingresos -->
        <div class="col-md-3">
            <div class="stats-card">
                <div class="metric-card">
                    <div class="metric-icon icon-revenue">
                        <i class="bi bi-currency-euro"></i>
                    </div>
                    <div class="metric-value">{{ number_format($stats['revenue_period'], 2) }} €</div>
                    <p class="metric-label">Ingresos</p>
                    <div class="small text-muted">{{ number_format($stats['avg_order_value'], 2) }} € valor promedio</div>
                </div>
            </div>
        </div>
        
        <!-- Productos -->
        <div class="col-md-3">
            <div class="stats-card">
                <div class="metric-card">
                    <div class="metric-icon icon-products">
                        <i class="bi bi-cart"></i>
                    </div>
                    <div class="metric-value">{{ number_format($stats['total_products']) }}</div>
                    <p class="metric-label">Productos</p>
                    <div class="small text-muted">{{ number_format($platform_stats['synced_products']) }} sincronizados</div>
                </div>
            </div>
        </div>
        
        <!-- Clientes -->
        <div class="col-md-3">
            <div class="stats-card">
                <div class="metric-card">
                    <div class="metric-icon icon-customers">
                        <i class="bi bi-people"></i>
                    </div>
                    <div class="metric-value">{{ number_format($stats['total_customers']) }}</div>
                    <p class="metric-label">Clientes</p>
                    <div class="small text-muted">+{{ number_format(array_sum($customer_growth['new'])) }} nuevos este trimestre</div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Estado de Pedidos Row -->
    <div class="row mb-4">
        <!-- Pendientes -->
        <div class="col-md-3">
            <div class="stats-card">
                <div class="metric-card">
                    <div class="metric-icon" style="background: linear-gradient(135deg, #ff9e43, #ff7a01); color: white;">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                    <div class="metric-value" style="background: linear-gradient(135deg, #ff9e43, #ff7a01); -webkit-background-clip: text;">
                        {{ number_format($stats['pending_orders']) }}
                    </div>
                    <p class="metric-label">Pedidos Pendientes</p>
                </div>
            </div>
        </div>
        
        <!-- En Proceso -->
        <div class="col-md-3">
            <div class="stats-card">
                <div class="metric-card">
                    <div class="metric-icon" style="background: linear-gradient(135deg, #2196F3, #4481eb); color: white;">
                        <i class="bi bi-arrow-repeat"></i>
                    </div>
                    <div class="metric-value" style="background: linear-gradient(135deg, #2196F3, #4481eb); -webkit-background-clip: text;">
                        {{ number_format($stats['shipped_orders']) }}
                    </div>
                    <p class="metric-label">Pedidos Enviados</p>
                </div>
            </div>
        </div>
        
        <!-- Entregados -->
        <div class="col-md-3">
            <div class="stats-card">
                <div class="metric-card">
                    <div class="metric-icon" style="background: linear-gradient(135deg, #42b883, #1f8057); color: white;">
                        <i class="bi bi-check2-circle"></i>
                    </div>
                    <div class="metric-value" style="background: linear-gradient(135deg, #42b883, #1f8057); -webkit-background-clip: text;">
                        {{ number_format($stats['delivered_orders']) }}
                    </div>
                    <p class="metric-label">Pedidos Entregados</p>
                </div>
            </div>
        </div>
        
        <!-- Cancelados -->
        <div class="col-md-3">
            <div class="stats-card">
                <div class="metric-card">
                    <div class="metric-icon" style="background: linear-gradient(135deg, #f05252, #bd3737); color: white;">
                        <i class="bi bi-x-circle"></i>
                    </div>
                    <div class="metric-value" style="background: linear-gradient(135deg, #f05252, #bd3737); -webkit-background-clip: text;">
                        {{ number_format($stats['canceled_orders']) }}
                    </div>
                    <p class="metric-label">Pedidos Cancelados</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Gráficos Principales -->
    <div class="row">
        <!-- Evolución de Pedidos/Ingresos -->
        <div class="col-md-8 mb-4">
            <div class="stats-card">
                <div class="card-header">
                    <h5><i class="bi bi-graph-up"></i>Evolución de Pedidos e Ingresos</h5>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-light dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="bi bi-sliders me-1"></i>Opciones
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#" onclick="toggleChartSeries('orders'); return false;">Mostrar/Ocultar Pedidos</a></li>
                            <li><a class="dropdown-item" href="#" onclick="toggleChartSeries('revenue'); return false;">Mostrar/Ocultar Ingresos</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#">Exportar Gráfico</a></li>
                        </ul>
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="ordersChart"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Estado de Pedidos -->
        <div class="col-md-4 mb-4">
            <div class="stats-card">
                <div class="card-header">
                    <h5><i class="bi bi-pie-chart"></i>Estado de Pedidos</h5>
                </div>
                <div class="chart-container">
                    <canvas id="statusChart"></canvas>
                </div>
                <div class="custom-legend">
                    @foreach($status_distribution as $status => $count)
                        @php
                            $color = match($status) {
                                'Pendientes' => '#ff9e43',
                                'En proceso' => '#4481eb',
                                'Enviados' => '#00c6ff',
                                'Entregados' => '#42b883',
                                'Cancelados' => '#f05252',
                                'Con errores' => '#ef4444',
                                default => '#718096'
                            };
                        @endphp
                        <div class="legend-item">
                            <div class="legend-color" style="background-color: {{ $color }}"></div>
                            <div class="legend-label">{{ $status }}: {{ $count }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
