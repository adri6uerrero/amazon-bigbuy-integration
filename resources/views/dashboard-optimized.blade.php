@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Header section with gradient background -->
    <div class="dashboard-header d-flex justify-content-between align-items-center">
        <div>
            <h2>Dashboard</h2>
            <p>Resumen de actividad y rendimiento</p>
        </div>
        <div class="period-selector">
            <a href="#" class="period-option active">7 días</a>
            <a href="#" class="period-option">30 días</a>
            <a href="#" class="period-option">3 meses</a>
            <a href="#" class="period-option">1 año</a>
        </div>
    </div>
    
    <!-- KPIs Row -->
    <div class="row mb-4">
        <!-- Usando nuestro componente x-stats-card -->
        <div class="col-md-3">
            <x-stats-card 
                title="Pedidos Totales" 
                value="{{ number_format($stats['total_orders'] ?? 150) }}" 
                icon="box" 
                color="blue" 
                subtext="{{ number_format($stats['orders_period'] ?? 25) }} en el período actual"
            />
        </div>
        
        <div class="col-md-3">
            <x-stats-card 
                title="Ingresos" 
                value="{{ number_format($stats['revenue_period'] ?? 4500, 2) }} €" 
                icon="currency-euro" 
                color="green" 
                subtext="{{ number_format($stats['avg_order_value'] ?? 75, 2) }} € valor promedio"
            />
        </div>
        
        <div class="col-md-3">
            <x-stats-card 
                title="Productos" 
                value="{{ number_format($stats['total_products'] ?? 220) }}" 
                icon="cart" 
                color="purple" 
                subtext="{{ number_format($platform_stats['synced_products'] ?? 180) }} sincronizados"
            />
        </div>
        
        <div class="col-md-3">
            <x-stats-card 
                title="Clientes" 
                value="{{ number_format($stats['total_customers'] ?? 80) }}" 
                icon="people" 
                color="orange" 
            />
        </div>
    </div>
    
    <!-- Gráficos Principales -->
    <div class="row">
        <!-- Evolución de Pedidos/Ingresos -->
        <div class="col-md-8 mb-4">
            <x-chart-card title="Evolución de Pedidos e Ingresos" icon="graph-up" id="ordersChart">
                <x-slot:options>
                    <li><a class="dropdown-item" href="#">Mostrar/Ocultar Pedidos</a></li>
                    <li><a class="dropdown-item" href="#">Mostrar/Ocultar Ingresos</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="#">Exportar Gráfico</a></li>
                </x-slot:options>
            </x-chart-card>
        </div>
        
        <!-- Estado de Pedidos -->
        <div class="col-md-4 mb-4">
            <x-chart-card title="Estado de Pedidos" icon="pie-chart" id="statusChart">
                <!-- Leyenda personalizada -->
                @foreach(['Pendientes' => '#ff9e43', 'En proceso' => '#4481eb', 'Enviados' => '#00c6ff', 'Entregados' => '#42b883', 'Cancelados' => '#f05252'] as $status => $color)
                    <div class="legend-item">
                        <div class="legend-color" style="background-color: {{ $color }}"></div>
                        <div class="legend-label">{{ $status }}: {{ rand(5, 30) }}</div>
                    </div>
                @endforeach
            </x-chart-card>
        </div>
    </div>
    
    <!-- Más información del dashboard -->
    <div class="row">
        <!-- Últimos pedidos -->
        <div class="col-md-6 mb-4">
            <div class="stats-card">
                <div class="card-header">
                    <h5><i class="bi bi-list-check"></i>Últimos Pedidos</h5>
                    <a href="{{ route('orders.index') }}" class="btn btn-sm btn-light">Ver todos</a>
                </div>
                <div class="p-3">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Cliente</th>
                                <th>Fecha</th>
                                <th>Estado</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @for($i = 1; $i <= 5; $i++)
                                <tr>
                                    <td>#{{ 1000 + $i }}</td>
                                    <td>Cliente {{ $i }}</td>
                                    <td>{{ now()->subDays(rand(1, 10))->format('d/m/Y') }}</td>
                                    <td>
                                        @php
                                            $statuses = ['pendiente', 'en_proceso', 'enviado', 'entregado', 'cancelado'];
                                            $status = $statuses[array_rand($statuses)];
                                            $statusClasses = [
                                                'pendiente' => 'status-pending',
                                                'en_proceso' => 'status-processing',
                                                'enviado' => 'status-shipped',
                                                'entregado' => 'status-delivered',
                                                'cancelado' => 'status-cancelled'
                                            ];
                                            $statusLabels = [
                                                'pendiente' => 'Pendiente',
                                                'en_proceso' => 'En proceso',
                                                'enviado' => 'Enviado',
                                                'entregado' => 'Entregado',
                                                'cancelado' => 'Cancelado'
                                            ];
                                        @endphp
                                        <span class="status-badge {{ $statusClasses[$status] }}">
                                            {{ $statusLabels[$status] }}
                                        </span>
                                    </td>
                                    <td>{{ number_format(rand(50, 500), 2) }} €</td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Top Productos -->
        <div class="col-md-6 mb-4">
            <div class="stats-card">
                <div class="card-header">
                    <h5><i class="bi bi-award"></i>Top Productos</h5>
                    <a href="{{ route('products.index') }}" class="btn btn-sm btn-light">Ver todos</a>
                </div>
                <div class="p-3">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Ventas</th>
                                <th>Plataformas</th>
                                <th>Ingresos</th>
                            </tr>
                        </thead>
                        <tbody>
                            @for($i = 1; $i <= 5; $i++)
                                <tr>
                                    <td>Producto {{ $i }}</td>
                                    <td>{{ rand(10, 100) }}</td>
                                    <td>
                                        @php
                                            $platforms = ['amazon', 'bigbuy', 'both'];
                                            $platform = $platforms[array_rand($platforms)];
                                        @endphp
                                        
                                        @if($platform == 'amazon')
                                            <span class="platform-badge amazon">Amazon</span>
                                        @elseif($platform == 'bigbuy')
                                            <span class="platform-badge bigbuy">BigBuy</span>
                                        @else
                                            <span class="platform-badge both">Ambas</span>
                                        @endif
                                    </td>
                                    <td>{{ number_format(rand(1000, 5000), 2) }} €</td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gráfico de órdenes e ingresos
    const ordersCtx = document.getElementById('ordersChart').getContext('2d');
    const ordersChart = new Chart(ordersCtx, {
        type: 'line',
        data: {
            labels: ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'],
            datasets: [
                {
                    label: 'Pedidos',
                    data: [12, 19, 15, 17, 22, 24, 18],
                    borderColor: '#3d6eea',
                    backgroundColor: 'rgba(61, 110, 234, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                },
                {
                    label: 'Ingresos (€)',
                    data: [1200, 1900, 1500, 1700, 2200, 2400, 1800],
                    borderColor: '#42b883',
                    backgroundColor: 'rgba(66, 184, 131, 0.0)',
                    borderWidth: 2,
                    tension: 0.4,
                    yAxisID: 'y1'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Pedidos'
                    }
                },
                y1: {
                    beginAtZero: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Ingresos (€)'
                    },
                    grid: {
                        drawOnChartArea: false
                    }
                }
            }
        }
    });
    
    // Gráfico de estados
    const statusCtx = document.getElementById('statusChart').getContext('2d');
    const statusChart = new Chart(statusCtx, {
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
});
</script>

<style>
/* Estilos específicos para badges de plataformas */
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
@endsection
