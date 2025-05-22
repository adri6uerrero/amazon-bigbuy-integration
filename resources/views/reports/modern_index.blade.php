@extends('layouts.app')

@section('content')
<div class="reports-container">
    <!-- Header principal con navegación de pestañas -->
    <div class="reports-hero">
        <div class="reports-hero-content">
            <h1>Panel de Informes</h1>
            <p>Análisis detallado del rendimiento y estadísticas de integración Amazon-BigBuy</p>
            
            <div class="report-tabs mt-4">
                <button class="tab-btn active" data-tab="overview">
                    <i class="bi bi-grid-1x2"></i> Vista general
                </button>
                <button class="tab-btn" data-tab="sales">
                    <i class="bi bi-graph-up"></i> Ventas
                </button>
                <button class="tab-btn" data-tab="products">
                    <i class="bi bi-box"></i> Productos
                </button>
                <button class="tab-btn" data-tab="customers">
                    <i class="bi bi-people"></i> Clientes
                </button>
            </div>
        </div>
        
        <div class="reports-filters">
            <div class="date-range-selector">
                <div class="range-item active" data-range="week">7 días</div>
                <div class="range-item" data-range="month">30 días</div>
                <div class="range-item" data-range="quarter">3 meses</div>
                <div class="range-item" data-range="year">1 año</div>
                <div class="range-item" data-range="custom">
                    <i class="bi bi-calendar3"></i>
                </div>
            </div>
            
            <div class="action-buttons">
                <button class="btn-action btn-export">
                    <i class="bi bi-download"></i>
                    <span>Exportar</span>
                </button>
                <button class="btn-action btn-refresh">
                    <i class="bi bi-arrow-repeat"></i>
                </button>
            </div>
        </div>
    </div>
    
    <!-- Contenido de pestañas -->
    <div class="tab-content active" id="overview">
        <!-- Fila de KPIs -->
        <div class="kpi-row">
            <div class="kpi-card kpi-primary">
                <div class="kpi-icon">
                    <i class="bi bi-cart"></i>
                </div>
                <div class="kpi-content">
                    <div class="kpi-value">{{ number_format($stats['total_orders'] ?? 0) }}</div>
                    <div class="kpi-label">Pedidos Totales</div>
                    <div class="kpi-trend positive">
                        <i class="bi bi-graph-up"></i>
                        <span>+{{ number_format(($stats['orders_last_month'] ?? 0) / max(1, ($stats['total_orders'] ?? 1)) * 100, 1) }}%</span>
                        <span class="trend-period">este mes</span>
                    </div>
                </div>
            </div>
            
            <div class="kpi-card kpi-success">
                <div class="kpi-icon">
                    <i class="bi bi-currency-euro"></i>
                </div>
                <div class="kpi-content">
                    <div class="kpi-value">{{ number_format(($stats['revenue_period'] ?? 0), 2) }}€</div>
                    <div class="kpi-label">Ingresos</div>
                    <div class="kpi-trend positive">
                        <i class="bi bi-graph-up"></i>
                        <span>+{{ number_format(rand(5, 15), 1) }}%</span>
                        <span class="trend-period">vs. anterior</span>
                    </div>
                </div>
            </div>
            
            <div class="kpi-card kpi-info">
                <div class="kpi-icon">
                    <i class="bi bi-people"></i>
                </div>
                <div class="kpi-content">
                    <div class="kpi-value">{{ number_format($stats['total_customers'] ?? 0) }}</div>
                    <div class="kpi-label">Clientes</div>
                    <div class="kpi-trend positive">
                        <i class="bi bi-graph-up"></i>
                        <span>+{{ number_format(rand(3, 8), 1) }}%</span>
                        <span class="trend-period">este mes</span>
                    </div>
                </div>
            </div>
            
            <div class="kpi-card kpi-warning">
                <div class="kpi-icon">
                    <i class="bi bi-star"></i>
                </div>
                <div class="kpi-content">
                    <div class="kpi-value">{{ number_format(($stats['avg_order_value'] ?? 0), 2) }}€</div>
                    <div class="kpi-label">Valor Medio</div>
                    <div class="kpi-trend {{ rand(0, 1) ? 'positive' : 'negative' }}">
                        <i class="bi {{ rand(0, 1) ? 'bi-graph-up' : 'bi-graph-down' }}"></i>
                        <span>{{ rand(0, 1) ? '+' : '-' }}{{ number_format(rand(1, 5), 1) }}%</span>
                        <span class="trend-period">vs. anterior</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Gráficos principales -->
        <div class="reports-grid">
            <!-- Evolución de Pedidos e Ingresos -->
            <div class="report-card wide">
                <div class="card-header">
                    <h3>Evolución de Pedidos e Ingresos</h3>
                    <div class="card-tools">
                        <button class="btn-tool btn-data-toggle" data-target="orders">
                            <span class="color-dot orders-dot"></span>
                            <span>Pedidos</span>
                        </button>
                        <button class="btn-tool btn-data-toggle active" data-target="revenue">
                            <span class="color-dot revenue-dot"></span>
                            <span>Ingresos</span>
                        </button>
                        <button class="btn-tool">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>
            
            <!-- Estado de Pedidos -->
            <div class="report-card">
                <div class="card-header">
                    <h3>Estado de Pedidos</h3>
                    <div class="card-tools">
                        <button class="btn-tool">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="statusChart"></canvas>
                    </div>
                    <div class="chart-legend">
                        @foreach(['Pendientes' => '#ff9e43', 'En proceso' => '#4481eb', 'Enviados' => '#00c6ff', 'Entregados' => '#42b883', 'Cancelados' => '#f05252'] as $status => $color)
                            <div class="legend-item">
                                <span class="legend-color" style="background-color: {{ $color }}"></span>
                                <span>{{ $status }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            
            <!-- Distribución por Plataforma -->
            <div class="report-card">
                <div class="card-header">
                    <h3>Distribución por Plataforma</h3>
                    <div class="card-tools">
                        <button class="btn-tool">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="platformChart"></canvas>
                    </div>
                    <div class="chart-legend">
                        <div class="legend-item">
                            <span class="legend-color" style="background-color: #ff9900"></span>
                            <span>Solo Amazon</span>
                        </div>
                        <div class="legend-item">
                            <span class="legend-color" style="background-color: #3d6eea"></span>
                            <span>Solo BigBuy</span>
                        </div>
                        <div class="legend-item">
                            <span class="legend-color" style="background-color: #42b883"></span>
                            <span>Ambas</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Ventas por Día -->
            <div class="report-card">
                <div class="card-header">
                    <h3>Ventas por Día</h3>
                    <div class="card-tools">
                        <button class="btn-tool">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="weekdayChart"></canvas>
                    </div>
                </div>
            </div>
            
            <!-- Top Productos -->
            <div class="report-card wide">
                <div class="card-header">
                    <h3>Top Productos</h3>
                    <div class="card-tools">
                        <button class="btn-tool">
                            <i class="bi bi-arrow-down-up"></i>
                        </button>
                        <button class="btn-tool">
                            <i class="bi bi-three-dots-vertical"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <table class="report-table">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Categoría</th>
                                <th>Ventas</th>
                                <th>Ingresos</th>
                                <th>Plataforma</th>
                                <th>Tendencia</th>
                            </tr>
                        </thead>
                        <tbody>
                            @for($i = 1; $i <= 5; $i++)
                                @php
                                    $platforms = ['Amazon', 'BigBuy', 'Ambas'];
                                    $platform = $platforms[array_rand($platforms)];
                                    $categories = ['Electrónica', 'Hogar', 'Ropa', 'Jardín', 'Juguetes'];
                                    $category = $categories[array_rand($categories)];
                                    $sales = rand(15, 120);
                                    $revenue = $sales * rand(20, 80);
                                    $trend = rand(0, 1) ? 'up' : 'down';
                                    $trendValue = rand(1, 15);
                                @endphp
                                <tr>
                                    <td>
                                        <div class="product-cell">
                                            <div class="product-icon">{{ strtoupper(substr("Producto $i", 0, 1)) }}</div>
                                            <div class="product-info">
                                                <div class="product-name">Producto {{ $i }}</div>
                                                <div class="product-sku">SKU-{{ 1000 + $i }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $category }}</td>
                                    <td>{{ number_format($sales) }}</td>
                                    <td>{{ number_format($revenue, 2) }}€</td>
                                    <td>
                                        <span class="platform-badge platform-{{ strtolower($platform) }}">{{ $platform }}</span>
                                    </td>
                                    <td>
                                        <div class="trend-cell trend-{{ $trend }}">
                                            <i class="bi bi-graph-{{ $trend }}"></i>
                                            <span>{{ $trend == 'up' ? '+' : '-' }}{{ $trendValue }}%</span>
                                        </div>
                                    </td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <a href="#" class="view-all">Ver todos los productos <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>
        </div>
        
        <!-- Panel de crecimiento de clientes -->
        <div class="customer-growth-panel">
            <div class="panel-header">
                <h3>Crecimiento de Clientes</h3>
                <p>Análisis de adquisición y retención de clientes</p>
            </div>
            
            <div class="panel-grid">
                <div class="panel-card">
                    <div class="stat-header">
                        <h4>Nuevos Clientes</h4>
                        <span class="badge badge-success">+{{ rand(5, 25) }}%</span>
                    </div>
                    <div class="stat-value">{{ rand(10, 50) }}</div>
                    <div class="stat-footer">Últimos 30 días</div>
                </div>
                
                <div class="panel-card">
                    <div class="stat-header">
                        <h4>Tasa de Retención</h4>
                        <span class="badge {{ rand(0, 1) ? 'badge-success' : 'badge-warning' }}">{{ rand(0, 1) ? '+' : '-' }}{{ rand(1, 5) }}%</span>
                    </div>
                    <div class="stat-value">{{ rand(65, 90) }}%</div>
                    <div class="stat-footer">Últimos 30 días</div>
                </div>
                
                <div class="panel-card">
                    <div class="stat-header">
                        <h4>Valor de Vida</h4>
                        <span class="badge badge-success">+{{ rand(5, 15) }}%</span>
                    </div>
                    <div class="stat-value">{{ rand(100, 300) }}€</div>
                    <div class="stat-footer">Promedio por cliente</div>
                </div>
                
                <div class="panel-card">
                    <div class="stat-header">
                        <h4>Coste Adquisición</h4>
                        <span class="badge badge-warning">-{{ rand(1, 10) }}%</span>
                    </div>
                    <div class="stat-value">{{ rand(15, 40) }}€</div>
                    <div class="stat-footer">Por cliente nuevo</div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Contenido de otras pestañas -->
    <div class="tab-content" id="sales">Contenido de ventas en desarrollo...</div>
    <div class="tab-content" id="products">Contenido de productos en desarrollo...</div>
    <div class="tab-content" id="customers">Contenido de clientes en desarrollo...</div>
</div>

<style>
/* Estilos modernos para el rediseño de informes */
.reports-container {
    padding: 0;
    font-family: 'Figtree', sans-serif;
}

/* Hero section con gradiente */
.reports-hero {
    background: linear-gradient(135deg, #3d6eea 0%, #6e47ef 100%);
    color: white;
    border-radius: 16px;
    padding: 30px;
    margin-bottom: 25px;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    box-shadow: 0 10px 25px rgba(61, 110, 234, 0.15);
}

.reports-hero h1 {
    font-size: 1.8rem;
    font-weight: 600;
    margin: 0 0 5px 0;
}

.reports-hero p {
    opacity: 0.9;
    margin: 0;
    font-size: 0.95rem;
}

/* Tabs de navegación */
.report-tabs {
    display: flex;
    gap: 5px;
}

.tab-btn {
    background: rgba(255, 255, 255, 0.15);
    border: none;
    color: white;
    padding: 8px 16px;
    border-radius: 8px;
    font-size: 0.9rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    gap: 8px;
}

.tab-btn i {
    font-size: 1rem;
}

.tab-btn:hover {
    background: rgba(255, 255, 255, 0.25);
}

.tab-btn.active {
    background: white;
    color: #3d6eea;
}

/* Filtros y acciones */
.reports-filters {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 15px;
}

.date-range-selector {
    display: flex;
    background: rgba(255, 255, 255, 0.15);
    border-radius: 8px;
}

.range-item {
    padding: 8px 12px;
    color: rgba(255, 255, 255, 0.9);
    cursor: pointer;
    font-size: 0.85rem;
    transition: all 0.2s ease;
    border-radius: 8px;
}

.range-item:hover {
    background: rgba(255, 255, 255, 0.1);
}

.range-item.active {
    background: white;
    color: #3d6eea;
    font-weight: 500;
}

.action-buttons {
    display: flex;
    gap: 10px;
}

.btn-action {
    background: rgba(255, 255, 255, 0.15);
    border: none;
    color: white;
    padding: 8px 15px;
    border-radius: 8px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.2s ease;
}

.btn-action:hover {
    background: rgba(255, 255, 255, 0.25);
    transform: translateY(-2px);
}

/* Contenido de pestañas */
.tab-content {
    display: none;
    padding-top: 10px;
}

.tab-content.active {
    display: block;
}

/* KPI Cards */
.kpi-row {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    margin-bottom: 25px;
}

.kpi-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.04);
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 20px;
    transition: all 0.3s ease;
    border: 1px solid rgba(230, 230, 250, 0.7);
}

.kpi-card:hover {
    box-shadow: 0 8px 25px rgba(61, 110, 234, 0.1);
    transform: translateY(-3px);
}

.kpi-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.6rem;
    color: white;
    flex-shrink: 0;
}

.kpi-primary .kpi-icon {
    background: linear-gradient(135deg, #3d6eea, #6e47ef);
}

.kpi-success .kpi-icon {
    background: linear-gradient(135deg, #42b883, #3d6eea);
}

.kpi-info .kpi-icon {
    background: linear-gradient(135deg, #00c6ff, #0072ff);
}

.kpi-warning .kpi-icon {
    background: linear-gradient(135deg, #ff9e43, #ff7a01);
}

.kpi-content {
    flex-grow: 1;
}

.kpi-value {
    font-size: 1.8rem;
    font-weight: 600;
    color: #333;
    line-height: 1.2;
}

.kpi-label {
    color: #718096;
    font-size: 0.9rem;
    margin-bottom: 8px;
}

.kpi-trend {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 0.85rem;
}

.kpi-trend.positive {
    color: #42b883;
}

.kpi-trend.negative {
    color: #f05252;
}

.trend-period {
    opacity: 0.7;
    margin-left: 2px;
}

/* Grid de reportes */
.reports-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
    margin-bottom: 25px;
}

.report-card {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.04);
    transition: all 0.3s ease;
    border: 1px solid rgba(230, 230, 250, 0.7);
    height: 100%;
    display: flex;
    flex-direction: column;
}

.report-card.wide {
    grid-column: span 2;
}

.report-card:hover {
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

.card-header h3 {
    font-size: 1rem;
    font-weight: 600;
    color: #333;
    margin: 0;
    display: flex;
    align-items: center;
}

.card-tools {
    display: flex;
    gap: 10px;
    align-items: center;
}

.btn-tool {
    background: transparent;
    border: none;
    font-size: 0.9rem;
    color: #718096;
    padding: 5px 10px;
    cursor: pointer;
    border-radius: 6px;
    display: flex;
    align-items: center;
    gap: 5px;
    transition: all 0.2s ease;
}

.btn-tool:hover {
    background: #f5f7fa;
    color: #3d6eea;
}

.btn-tool.active {
    background: rgba(61, 110, 234, 0.1);
    color: #3d6eea;
}

.color-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    display: inline-block;
}

.orders-dot {
    background-color: #3d6eea;
}

.revenue-dot {
    background-color: #42b883;
}

.card-body {
    padding: 20px;
    flex-grow: 1;
}

.card-body.p-0 {
    padding: 0;
}

.chart-container {
    width: 100%;
    height: 260px;
    position: relative;
}

.chart-legend {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 15px;
    margin-top: 10px;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.85rem;
    color: #718096;
}

.legend-color {
    width: 10px;
    height: 10px;
    border-radius: 3px;
}

/* Tablas de datos */
.report-table {
    width: 100%;
    border-collapse: collapse;
}

.report-table th {
    padding: 12px 15px;
    font-weight: 500;
    color: #718096;
    font-size: 0.85rem;
    text-align: left;
    border-bottom: 1px solid #f0f0f0;
    background: #fafafa;
}

.report-table td {
    padding: 12px 15px;
    vertical-align: middle;
    border-bottom: 1px solid #f0f0f0;
    font-size: 0.9rem;
}

.report-table tr:last-child td {
    border-bottom: none;
}

.report-table tr:hover {
    background-color: #f9fafc;
}

.product-cell {
    display: flex;
    align-items: center;
    gap: 12px;
}

.product-icon {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    background: linear-gradient(135deg, #3d6eea, #6e47ef);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 600;
    font-size: 0.9rem;
}

.product-name {
    font-weight: 500;
    color: #333;
}

.product-sku {
    font-size: 0.75rem;
    color: #718096;
}

.platform-badge {
    display: inline-block;
    padding: 5px 10px;
    font-size: 0.75rem;
    font-weight: 500;
    border-radius: 30px;
}

.platform-amazon {
    background: linear-gradient(135deg, #ff9900, #ffb144);
    color: white;
}

.platform-bigbuy {
    background: linear-gradient(135deg, #3d6eea, #6e47ef);
    color: white;
}

.platform-ambas {
    background: linear-gradient(135deg, #42b883, #00c6ff);
    color: white;
}

.trend-cell {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 0.85rem;
    font-weight: 500;
}

.trend-up {
    color: #42b883;
}

.trend-down {
    color: #f05252;
}

.card-footer {
    padding: 12px 20px;
    border-top: 1px solid #f5f7fa;
    font-size: 0.9rem;
}

.view-all {
    color: #3d6eea;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 5px;
    font-weight: 500;
    transition: all 0.2s ease;
}

.view-all:hover {
    color: #6e47ef;
}

/* Panel de crecimiento */
.customer-growth-panel {
    background: white;
    border-radius: 16px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.04);
    margin-bottom: 25px;
    border: 1px solid rgba(230, 230, 250, 0.7);
    transition: all 0.3s ease;
    overflow: hidden;
}

.customer-growth-panel:hover {
    box-shadow: 0 8px 25px rgba(61, 110, 234, 0.1);
    transform: translateY(-3px);
}

.panel-header {
    background: linear-gradient(135deg, #00c6ff, #0072ff);
    color: white;
    padding: 20px;
    border-radius: 16px 16px 0 0;
}

.panel-header h3 {
    margin: 0;
    font-size: 1.1rem;
    font-weight: 600;
}

.panel-header p {
    margin: 5px 0 0 0;
    opacity: 0.9;
    font-size: 0.85rem;
}

.panel-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 0;
}

.panel-card {
    padding: 20px;
    text-align: center;
    transition: all 0.3s ease;
    position: relative;
}

.panel-card:not(:last-child)::after {
    content: '';
    position: absolute;
    right: 0;
    top: 20px;
    bottom: 20px;
    width: 1px;
    background: #f0f0f0;
}

.panel-card:hover {
    background: #fafafa;
}

.stat-header {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    margin-bottom: 10px;
}

.stat-header h4 {
    margin: 0;
    font-size: 0.9rem;
    font-weight: 500;
    color: #718096;
}

.badge {
    padding: 3px 8px;
    border-radius: 30px;
    font-size: 0.75rem;
    font-weight: 500;
    color: white;
}

.badge-success {
    background: linear-gradient(135deg, #42b883, #3db489);
}

.badge-warning {
    background: linear-gradient(135deg, #ff9e43, #ff7a01);
}

.stat-value {
    font-size: 1.8rem;
    font-weight: 600;
    color: #333;
    margin: 10px 0;
}

.stat-footer {
    font-size: 0.8rem;
    color: #718096;
}

/* Responsive */
@media (max-width: 1024px) {
    .kpi-row, .panel-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .reports-grid {
        grid-template-columns: 1fr;
    }
    
    .report-card.wide {
        grid-column: auto;
    }
}

@media (max-width: 768px) {
    .reports-hero {
        flex-direction: column;
        gap: 20px;
    }
    
    .reports-filters {
        width: 100%;
        align-items: stretch;
    }
    
    .panel-grid {
        grid-template-columns: 1fr;
    }
    
    .panel-card:not(:last-child)::after {
        display: none;
    }
    
    .panel-card:not(:last-child) {
        border-bottom: 1px solid #f0f0f0;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tab navigation
    document.querySelectorAll('.tab-btn').forEach(button => {
        button.addEventListener('click', function() {
            // Remove active class from all tabs
            document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(content => content.classList.remove('active'));
            
            // Add active class to clicked tab
            this.classList.add('active');
            document.getElementById(this.dataset.tab).classList.add('active');
        });
    });
    
    // Date range selector
    document.querySelectorAll('.range-item').forEach(item => {
        item.addEventListener('click', function() {
            document.querySelectorAll('.range-item').forEach(i => i.classList.remove('active'));
            this.classList.add('active');
        });
    });
    
    // Solo inicializamos los gráficos si Chart.js está disponible
    if (typeof Chart !== 'undefined') {
        // Revenue Chart
        const revenueCtx = document.getElementById('revenueChart');
        if (revenueCtx) {
            const revenueChart = new Chart(revenueCtx.getContext('2d'), {
                type: 'line',
                data: {
                    labels: ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'],
                    datasets: [
                        {
                            label: 'Pedidos',
                            data: [15, 22, 18, 25, 28, 32, 20],
                            borderColor: '#3d6eea',
                            backgroundColor: 'rgba(61, 110, 234, 0.1)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4
                        },
                        {
                            label: 'Ingresos (€)',
                            data: [1500, 2200, 1800, 2500, 2800, 3200, 2000],
                            borderColor: '#42b883',
                            backgroundColor: 'rgba(66, 184, 131, 0.0)',
                            borderWidth: 2,
                            tension: 0.4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        },
                        x: {
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        }
                    }
                }
            });
        }
        
        // Status Chart
        const statusCtx = document.getElementById('statusChart');
        if (statusCtx) {
            const statusChart = new Chart(statusCtx.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: ['Pendientes', 'En proceso', 'Enviados', 'Entregados', 'Cancelados'],
                    datasets: [{
                        data: [15, 10, 25, 45, 5],
                        backgroundColor: [
                            '#ff9e43',
                            '#4481eb',
                            '#00c6ff',
                            '#42b883',
                            '#f05252'
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    cutout: '70%'
                }
            });
        }
        
        // Platform Chart
        const platformCtx = document.getElementById('platformChart');
        if (platformCtx) {
            const platformChart = new Chart(platformCtx.getContext('2d'), {
                type: 'pie',
                data: {
                    labels: ['Solo Amazon', 'Solo BigBuy', 'Ambas'],
                    datasets: [{
                        data: [40, 35, 25],
                        backgroundColor: [
                            '#ff9900',
                            '#3d6eea',
                            '#42b883'
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        }
        
        // Weekday Chart
        const weekdayCtx = document.getElementById('weekdayChart');
        if (weekdayCtx) {
            const weekdayChart = new Chart(weekdayCtx.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'],
                    datasets: [{
                        label: 'Ventas',
                        data: [65, 59, 80, 81, 90, 55, 40],
                        backgroundColor: 'rgba(61, 110, 234, 0.7)',
                        borderColor: 'rgba(61, 110, 234, 1)',
                        borderWidth: 0,
                        borderRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }
    }
});
</script>
@endsection
