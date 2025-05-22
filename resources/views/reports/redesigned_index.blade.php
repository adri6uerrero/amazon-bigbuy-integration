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
