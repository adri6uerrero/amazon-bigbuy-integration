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
        <div class="reports-grid">
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
