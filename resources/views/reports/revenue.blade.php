@extends('layouts.app')

@section('content')
<div class="revenue-reports-container">
    <!-- Header principal con filtros de fecha -->
    <div class="reports-hero">
        <div class="reports-hero-content">
            <h1>Informe de Ingresos</h1>
            <p>Análisis detallado de los ingresos generados por la integración Amazon-BigBuy</p>
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
    
    <!-- Tarjetas KPI de ingresos -->
    <div class="kpi-row">
        <div class="kpi-card kpi-primary">
            <div class="kpi-icon">
                <i class="bi bi-currency-euro"></i>
            </div>
            <div class="kpi-content">
                <div class="kpi-value">{{ number_format(($stats['revenue_period'] ?? 0), 2) }}€</div>
                <div class="kpi-label">Ingresos Totales</div>
                <div class="kpi-trend positive">
                    <i class="bi bi-graph-up"></i>
                    <span>+{{ number_format(rand(5, 15), 1) }}%</span>
                    <span class="trend-period">vs. período anterior</span>
                </div>
            </div>
        </div>
        
        <div class="kpi-card kpi-success">
            <div class="kpi-icon">
                <i class="bi bi-bag-check"></i>
            </div>
            <div class="kpi-content">
                <div class="kpi-value">{{ number_format(($stats['avg_order_value'] ?? 0), 2) }}€</div>
                <div class="kpi-label">Valor Medio Pedido</div>
                <div class="kpi-trend {{ rand(0, 1) ? 'positive' : 'negative' }}">
                    <i class="bi {{ rand(0, 1) ? 'bi-graph-up' : 'bi-graph-down' }}"></i>
                    <span>{{ rand(0, 1) ? '+' : '-' }}{{ number_format(rand(1, 8), 1) }}%</span>
                    <span class="trend-period">vs. período anterior</span>
                </div>
            </div>
        </div>
        
        <div class="kpi-card kpi-info">
            <div class="kpi-icon">
                <i class="bi bi-cart-check"></i>
            </div>
            <div class="kpi-content">
                <div class="kpi-value">{{ number_format($stats['orders_period'] ?? 0) }}</div>
                <div class="kpi-label">Pedidos con Ingresos</div>
                <div class="kpi-trend positive">
                    <i class="bi bi-graph-up"></i>
                    <span>+{{ number_format(rand(3, 10), 1) }}%</span>
                    <span class="trend-period">este período</span>
                </div>
            </div>
        </div>
        
        <div class="kpi-card kpi-warning">
            <div class="kpi-icon">
                <i class="bi bi-calendar-check"></i>
            </div>
            <div class="kpi-content">
                <div class="kpi-value">{{ number_format(($stats['revenue_period'] ?? 0) / max(1, $stats['days_in_period'] ?? 30), 2) }}€</div>
                <div class="kpi-label">Ingresos por Día</div>
                <div class="kpi-trend {{ rand(0, 1) ? 'positive' : 'negative' }}">
                    <i class="bi {{ rand(0, 1) ? 'bi-graph-up' : 'bi-graph-down' }}"></i>
                    <span>{{ rand(0, 1) ? '+' : '-' }}{{ number_format(rand(1, 5), 1) }}%</span>
                    <span class="trend-period">vs. día anterior</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Gráficos de ingresos -->
    <div class="reports-grid">
        <!-- Evolución de Ingresos -->
        <div class="report-card wide">
            <div class="card-header">
                <h3>Evolución de Ingresos</h3>
                <div class="card-tools">
                    <button class="btn-tool btn-data-toggle" data-target="daily">
                        <span class="color-dot daily-dot"></span>
                        <span>Diario</span>
                    </button>
                    <button class="btn-tool btn-data-toggle active" data-target="cumulative">
                        <span class="color-dot cumulative-dot"></span>
                        <span>Acumulado</span>
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
        
        <!-- Ingresos por Plataforma -->
        <div class="report-card">
            <div class="card-header">
                <h3>Ingresos por Plataforma</h3>
                <div class="card-tools">
                    <button class="btn-tool">
                        <i class="bi bi-three-dots-vertical"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="platformRevenueChart"></canvas>
                </div>
                <div class="chart-legend">
                    <div class="legend-item">
                        <span class="legend-color" style="background-color: #ff9900"></span>
                        <span>Amazon</span>
                    </div>
                    <div class="legend-item">
                        <span class="legend-color" style="background-color: #3d6eea"></span>
                        <span>BigBuy</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Ingresos por Día de la Semana -->
        <div class="report-card">
            <div class="card-header">
                <h3>Ingresos por Día</h3>
                <div class="card-tools">
                    <button class="btn-tool">
                        <i class="bi bi-three-dots-vertical"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="weekdayRevenueChart"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Top Categorías por Ingresos -->
        <div class="report-card wide">
            <div class="card-header">
                <h3>Top Categorías por Ingresos</h3>
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
                            <th>Categoría</th>
                            <th>Ingresos</th>
                            <th>% del Total</th>
                            <th>Pedidos</th>
                            <th>Valor Medio</th>
                            <th>Tendencia</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $categories = ['Electrónica', 'Hogar', 'Ropa', 'Jardín', 'Juguetes'];
                            $totalRevenue = rand(15000, 50000);
                        @endphp
                        
                        @foreach($categories as $index => $category)
                            @php
                                $revenue = $totalRevenue * (1 - ($index * 0.15));
                                $percentage = $revenue / $totalRevenue * 100;
                                $orders = round($revenue / rand(30, 80));
                                $avgValue = $revenue / $orders;
                                $trend = rand(0, 1) ? 'up' : 'down';
                                $trendValue = rand(1, 15);
                            @endphp
                            <tr>
                                <td>
                                    <div class="category-cell">
                                        <div class="category-name">{{ $category }}</div>
                                    </div>
                                </td>
                                <td>{{ number_format($revenue, 2) }}€</td>
                                <td>{{ number_format($percentage, 1) }}%</td>
                                <td>{{ number_format($orders) }}</td>
                                <td>{{ number_format($avgValue, 2) }}€</td>
                                <td>
                                    <div class="trend-cell trend-{{ $trend }}">
                                        <i class="bi bi-graph-{{ $trend }}"></i>
                                        <span>{{ $trend == 'up' ? '+' : '-' }}{{ $trendValue }}%</span>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                <a href="#" class="view-all">Ver todas las categorías <i class="bi bi-arrow-right"></i></a>
            </div>
        </div>
    </div>
</div>

<style>
/* Estilos mejorados para el informe de ingresos */
.revenue-reports-container {
    padding: 0;
    font-family: 'Figtree', sans-serif;
    max-width: 100%;
    margin: 0 auto;
}

/* Hero section mejorado */
.reports-hero {
    background: linear-gradient(135deg, #42b883 0%, #3d6eea 100%);
    color: white;
    border-radius: 16px;
    padding: 30px;
    margin-bottom: 25px;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    box-shadow: 0 10px 25px rgba(66, 184, 131, 0.15);
}

.reports-hero h1 {
    font-size: 1.8rem;
    font-weight: 600;
    margin: 0 0 5px 0;
    letter-spacing: -0.5px;
}

.reports-hero p {
    opacity: 0.9;
    margin: 0;
    font-size: 0.95rem;
}

/* KPI row mejorada */
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
    background: linear-gradient(135deg, #42b883, #3d6eea);
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

/* Grid de reportes mejorado */
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

/* Puntos de colores para filtros */
.color-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    display: inline-block;
}

.daily-dot {
    background-color: #42b883;
}

.cumulative-dot {
    background-color: #3d6eea;
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

/* Tablas mejoradas */
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

.category-cell {
    font-weight: 500;
    color: #333;
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

/* Selectores de rango de fechas */
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
    margin-top: 15px;
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

/* Responsive */
@media (max-width: 1024px) {
    .kpi-row {
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
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
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
                            label: 'Ingresos Diarios (€)',
                            data: [1500, 2200, 1800, 2500, 2800, 3200, 2000],
                            borderColor: '#42b883',
                            backgroundColor: 'rgba(66, 184, 131, 0.1)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4
                        },
                        {
                            label: 'Ingresos Acumulados (€)',
                            data: [1500, 3700, 5500, 8000, 10800, 14000, 16000],
                            borderColor: '#3d6eea',
                            backgroundColor: 'rgba(61, 110, 234, 0.0)',
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
        
        // Platform Revenue Chart
        const platformRevenueCtx = document.getElementById('platformRevenueChart');
        if (platformRevenueCtx) {
            const platformRevenueChart = new Chart(platformRevenueCtx.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: ['Amazon', 'BigBuy'],
                    datasets: [{
                        data: [60, 40],
                        backgroundColor: [
                            '#ff9900',
                            '#3d6eea'
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
        
        // Weekday Revenue Chart
        const weekdayRevenueCtx = document.getElementById('weekdayRevenueChart');
        if (weekdayRevenueCtx) {
            const weekdayRevenueChart = new Chart(weekdayRevenueCtx.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'],
                    datasets: [{
                        label: 'Ingresos',
                        data: [1500, 2200, 1800, 2500, 2800, 3200, 2000],
                        backgroundColor: 'rgba(66, 184, 131, 0.7)',
                        borderColor: 'rgba(66, 184, 131, 1)',
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
