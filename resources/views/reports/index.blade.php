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
    
    .icon-customers {
        background: linear-gradient(135deg, #42b883, #3d6eea);
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
    
    /* Chart card styles */
    .chart-container {
        padding: 20px;
        height: 300px;
    }
    
    /* Customer list styles */
    .customer-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .customer-item {
        display: flex;
        align-items: center;
        padding: 12px 20px;
        border-bottom: 1px solid #f5f7fa;
        transition: background-color 0.2s ease;
    }
    
    .customer-item:hover {
        background-color: #f9fafc;
    }
    
    .customer-avatar {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #3d6eea, #6e47ef);
        color: white;
        font-weight: 600;
        margin-right: 15px;
    }
    
    .customer-info {
        flex-grow: 1;
    }
    
    .customer-name {
        font-weight: 500;
        color: #333;
        margin-bottom: 3px;
    }
    
    .customer-email {
        font-size: 0.8rem;
        color: #718096;
    }
    
    .customer-orders-badge {
        background: linear-gradient(135deg, #42b883, #3d6eea);
        color: white;
        padding: 5px 10px;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: 500;
    }
    
    /* Filter tabs styles */
    .filter-tabs {
        display: flex;
        margin-bottom: 25px;
        overflow-x: auto;
        padding-bottom: 5px;
    }
    
    .filter-tab {
        padding: 10px 20px;
        border-radius: 10px;
        font-size: 0.9rem;
        font-weight: 500;
        color: #718096;
        margin-right: 10px;
        background: #f7f9fc;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
        white-space: nowrap;
    }
    
    .filter-tab.active {
        background: linear-gradient(135deg, #3d6eea, #6e47ef);
        color: white;
        box-shadow: 0 4px 10px rgba(61, 110, 234, 0.2);
    }
    
    .filter-tab:hover:not(.active) {
        background: #e9f0fd;
        color: #3d6eea;
    }
</style>

<div class="container-fluid">
    <!-- Header section with gradient background -->
    <div class="reports-header d-flex justify-content-between align-items-center">
        <div>
            <h2>Estadísticas e Informes</h2>
            <p>Análisis de rendimiento Amazon-BigBuy</p>
        </div>
        <div class="d-flex">
            <div class="dropdown me-2">
                <button class="btn btn-light dropdown-toggle rounded-pill px-4" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-file-earmark-arrow-down me-2"></i>Exportar
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    <li><a class="dropdown-item" href="{{ route('reports.generate', 'csv') }}">CSV</a></li>
                    <li><a class="dropdown-item" href="{{ route('reports.generate', 'pdf') }}">PDF</a></li>
                    <li><a class="dropdown-item" href="{{ route('reports.generate', 'excel') }}">Excel</a></li>
                </ul>
            </div>
            <div class="dropdown">
                <button class="btn btn-light dropdown-toggle rounded-pill px-4" type="button" id="periodDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-calendar-range me-2"></i>Este mes
                </button>
                <ul class="dropdown-menu" aria-labelledby="periodDropdown">
                    <li><a class="dropdown-item" href="#">Este mes</a></li>
                    <li><a class="dropdown-item" href="#">Último mes</a></li>
                    <li><a class="dropdown-item" href="#">Últimos 3 meses</a></li>
                    <li><a class="dropdown-item" href="#">Este año</a></li>
                </ul>
            </div>
        </div>
    </div>
    
    <!-- Filter tabs -->
    <div class="filter-tabs">
        <button class="filter-tab active">Visión General</button>
        <button class="filter-tab">Pedidos</button>
        <button class="filter-tab">Clientes</button>
        <button class="filter-tab">Productos</button>
        <button class="filter-tab">Ingresos</button>
    </div>
    
    <!-- Metrics overview -->
    <div class="row">
        <div class="col-md-3">
            <div class="stats-card">
                <div class="metric-card">
                    <div class="metric-icon icon-orders">
                        <i class="bi bi-box"></i>
                    </div>
                    <div class="metric-value">{{ number_format($stats['total_orders']) }}</div>
                    <p class="metric-label">Pedidos totales</p>
                    <div class="mt-3 text-center">
                        <span class="badge bg-success rounded-pill">
                            <i class="bi bi-graph-up"></i> +{{ number_format($stats['orders_last_month'] / max(1, $stats['total_orders']) * 100, 1) }}%
                        </span>
                        <small class="text-muted d-block mt-1">este mes</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <div class="metric-card">
                    <div class="metric-icon icon-customers">
                        <i class="bi bi-people"></i>
                    </div>
                    <div class="metric-value">{{ number_format($stats['total_customers']) }}</div>
                    <p class="metric-label">Clientes totales</p>
                    <div class="mt-3 text-center">
                        <span class="text-primary">
                            <i class="bi bi-arrow-repeat"></i> {{ number_format($stats['total_orders'] / max(1, $stats['total_customers']), 1) }}
                        </span>
                        <small class="text-muted d-block mt-1">pedidos/cliente</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <div class="metric-card">
                    <div class="metric-icon icon-pending">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                    <div class="metric-value">{{ number_format($stats['pending_orders']) }}</div>
                    <p class="metric-label">Pedidos pendientes</p>
                    <div class="mt-3 text-center">
                        <span class="text-warning">
                            {{ number_format($stats['pending_orders'] / max(1, $stats['total_orders']) * 100, 1) }}%
                        </span>
                        <small class="text-muted d-block mt-1">del total</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <div class="metric-card">
                    <div class="metric-icon icon-shipped">
                        <i class="bi bi-truck"></i>
                    </div>
                    <div class="metric-value">{{ number_format($stats['shipped_orders']) }}</div>
                    <p class="metric-label">Pedidos enviados</p>
                    <div class="mt-3 text-center">
                        <span class="text-info">
                            {{ number_format($stats['shipped_orders'] / max(1, $stats['total_orders']) * 100, 1) }}%
                        </span>
                        <small class="text-muted d-block mt-1">del total</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Charts section -->
    <div class="row">
        <div class="col-md-8">
            <div class="stats-card">
                <div class="card-header">
                    <h5>Evolución de Pedidos</h5>
                    <div>
                        <button class="btn btn-sm btn-light rounded-pill">Mensual</button>
                        <button class="btn btn-sm btn-light rounded-pill">Diario</button>
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="ordersChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stats-card">
                <div class="card-header">
                    <h5>Estado de Pedidos</h5>
                </div>
                <div class="chart-container">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Top customers and conversions -->
    <div class="row">
        <div class="col-md-6">
            <div class="stats-card">
                <div class="card-header">
                    <h5>Top Clientes</h5>
                    <a href="{{ route('customers.index') }}" class="btn btn-sm btn-light rounded-pill">Ver todos</a>
                </div>
                <ul class="customer-list">
                    @forelse($top_customers as $customer)
                    <li class="customer-item">
                        <div class="customer-avatar">
                            {{ strtoupper(substr($customer->name, 0, 1)) }}
                        </div>
                        <div class="customer-info">
                            <div class="customer-name">{{ $customer->name }}</div>
                            <div class="customer-email">{{ $customer->email }}</div>
                        </div>
                        <div class="customer-orders-badge">
                            {{ $customer->orders_count }} pedidos
                        </div>
                    </li>
                    @empty
                    <li class="customer-item text-center py-4">
                        <div class="text-muted">No hay datos disponibles</div>
                    </li>
                    @endforelse
                </ul>
            </div>
        </div>
        <div class="col-md-6">
            <div class="stats-card">
                <div class="card-header">
                    <h5>Tasas de Conversión</h5>
                </div>
                <div class="p-4">
                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Pedidos Completados</span>
                            <span class="fw-bold">{{ number_format(($stats['shipped_orders'] + $stats['delivered_orders']) / max(1, $stats['total_orders']) * 100, 1) }}%</span>
                        </div>
                        <div class="progress" style="height: 10px; border-radius: 10px;">
                            <div class="progress-bar bg-success" role="progressbar" 
                                 style="width: {{ ($stats['shipped_orders'] + $stats['delivered_orders']) / max(1, $stats['total_orders']) * 100 }}%; 
                                        border-radius: 10px; background: linear-gradient(135deg, #42b883, #3d6eea) !important;" 
                                 aria-valuenow="{{ ($stats['shipped_orders'] + $stats['delivered_orders']) / max(1, $stats['total_orders']) * 100 }}" 
                                 aria-valuemin="0" 
                                 aria-valuemax="100"></div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Pedidos Pendientes</span>
                            <span class="fw-bold">{{ number_format($stats['pending_orders'] / max(1, $stats['total_orders']) * 100, 1) }}%</span>
                        </div>
                        <div class="progress" style="height: 10px; border-radius: 10px;">
                            <div class="progress-bar" role="progressbar" 
                                 style="width: {{ $stats['pending_orders'] / max(1, $stats['total_orders']) * 100 }}%; 
                                        border-radius: 10px; background: linear-gradient(135deg, #f7b733, #fc4a1a) !important;" 
                                 aria-valuenow="{{ $stats['pending_orders'] / max(1, $stats['total_orders']) * 100 }}" 
                                 aria-valuemin="0" 
                                 aria-valuemax="100"></div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Pedidos Cancelados</span>
                            <span class="fw-bold">{{ number_format($stats['canceled_orders'] / max(1, $stats['total_orders']) * 100, 1) }}%</span>
                        </div>
                        <div class="progress" style="height: 10px; border-radius: 10px;">
                            <div class="progress-bar bg-danger" role="progressbar" 
                                 style="width: {{ $stats['canceled_orders'] / max(1, $stats['total_orders']) * 100 }}%; 
                                        border-radius: 10px; background: linear-gradient(135deg, #eb3349, #f45c43) !important;" 
                                 aria-valuenow="{{ $stats['canceled_orders'] / max(1, $stats['total_orders']) * 100 }}" 
                                 aria-valuemin="0" 
                                 aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Configuración de gráficos con Chart.js
        
        // Gráfico de evolución mensual de pedidos
        const ordersCtx = document.getElementById('ordersChart').getContext('2d');
        const monthlyData = @json(array_column($monthly_orders, 'count'));
        const monthlyLabels = @json(array_column($monthly_orders, 'month'));
        
        const ordersChart = new Chart(ordersCtx, {
            type: 'line',
            data: {
                labels: monthlyLabels,
                datasets: [{
                    label: 'Pedidos',
                    data: monthlyData,
                    backgroundColor: 'rgba(61, 110, 234, 0.1)',
                    borderColor: '#3d6eea',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#6e47ef',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7
                }]
            },
            options: {
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
                            drawBorder: false,
                            borderDash: [5, 5]
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
        
        // Gráfico de distribución de estados
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        const statusLabels = Object.keys(@json($status_distribution));
        const statusData = Object.values(@json($status_distribution));
        
        const statusColors = [
            'rgba(110, 71, 239, 0.8)',   // Morado
            'rgba(61, 110, 234, 0.8)',   // Azul
            'rgba(66, 184, 131, 0.8)',   // Verde
            'rgba(247, 183, 51, 0.8)',   // Amarillo
            'rgba(236, 51, 73, 0.8)'     // Rojo
        ];
        
        const statusChart = new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: statusLabels,
                datasets: [{
                    data: statusData,
                    backgroundColor: statusColors,
                    borderWidth: 0,
                    hoverOffset: 10
                }]
            },
            options: {
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            boxWidth: 15,
                            padding: 15,
                            font: {
                                size: 12
                            }
                        }
                    }
                },
                cutout: '65%'
            }
        });
    });
</script>
@endsection
