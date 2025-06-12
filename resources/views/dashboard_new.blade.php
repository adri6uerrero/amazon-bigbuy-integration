@extends('layouts.app')
@section('content')
<style>
    /* Header styles */w
    .dashboard-header {
        background: linear-gradient(135deg, #3d6eea 0%, #6e47ef 100%);
        color: white;
        padding: 20px 30px;
        border-radius: 15px;
        margin-bottom: 25px;
        box-shadow: 0 4px 20px rgba(61, 110, 234, 0.15);
    }
    
    .dashboard-header h2 {
        font-size: 1.5rem;
        font-weight: 500;
        margin: 0;
    }
    
    .dashboard-header p {
        margin: 0;
        opacity: 0.9;
        font-size: 0.85rem;
    }
    
    .dashboard-search-box {
        background: rgba(255,255,255,0.15);
        border: none;
        border-radius: 50px;
        padding: 8px 15px;
        color: white;
        width: 250px;
    }
    
    .dashboard-search-box::placeholder {
        color: rgba(255,255,255,0.7);
    }
    
    .user-profile {
        display: flex;
        align-items: center;
    }
    
    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        border: 2px solid rgba(255,255,255,0.8);
    }
    
    .user-info {
        text-align: right;
        line-height: 1.2;
        margin-right: 10px;
    }
    
    .user-name {
        font-weight: 500;
    }
    
    .user-role {
        font-size: 0.75rem;
        opacity: 0.8;
    }
    
    /* Card styles */
    .dashboard-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.04);
        margin-bottom: 20px;
        overflow: hidden;
        height: 100%;
        transition: all 0.3s ease;
        border: 1px solid rgba(230, 230, 230, 0.5);
    }
    
    .dashboard-card:hover {
        box-shadow: 0 8px 25px rgba(61, 110, 234, 0.1);
        transform: translateY(-3px);
    }
    
    .dashboard-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 20px;
        border-bottom: 1px solid #f5f5f5;
    }
    
    .dashboard-card-title {
        font-size: 15px;
        font-weight: 600;
        margin: 0;
        color: #333;
    }
    
    /* KPI Card Styles */
    .kpi-card {
        padding: 20px;
        text-align: center;
        height: 100%;
    }
    
    .kpi-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px;
        font-size: 24px;
    }
    
    .icon-orders {
        background: linear-gradient(135deg, #3d6eea, #6e47ef);
        color: white;
    }
    
    .icon-products {
        background: linear-gradient(135deg, #42b883, #3d6eea);
        color: white;
    }
    
    .icon-revenue {
        background: linear-gradient(135deg, #00c6ff, #0072ff);
        color: white;
    }
    
    .icon-customers {
        background: linear-gradient(135deg, #f7b733, #fc4a1a);
        color: white;
    }
    
    .icon-sync {
        background: linear-gradient(135deg, #ff3e9d, #0e1f40);
        color: white;
    }
    
    .kpi-value {
        font-size: 2rem;
        font-weight: 700;
        margin: 10px 0;
        background: linear-gradient(135deg, #3d6eea, #6e47ef);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    
    .kpi-label {
        color: #718096;
        font-size: 0.9rem;
        margin-bottom: 15px;
    }
    
    .kpi-change {
        display: inline-flex;
        align-items: center;
        font-weight: 500;
        font-size: 0.85rem;
        padding: 4px 10px;
        border-radius: 50px;
    }
    
    .kpi-up {
        background-color: rgba(66, 184, 131, 0.1);
        color: #42b883;
    }
    
    .kpi-down {
        background-color: rgba(235, 51, 73, 0.1);
        color: #eb3349;
    }
    
    /* Chart container styles */
    .chart-container {
        padding: 15px;
        position: relative;
        height: 300px;
    }
    
    /* Status widget styles */
    .status-widget {
        padding: 20px;
    }
    
    .status-item {
        display: flex;
        align-items: center;
        margin-bottom: 15px;
    }
    
    .status-item:last-child {
        margin-bottom: 0;
    }
    
    .status-color {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        margin-right: 10px;
    }
    
    .status-label {
        flex: 1;
        font-size: 0.9rem;
        color: #4b5563;
    }
    
    .status-value {
        font-weight: 600;
        color: #333;
    }
    
    .status-progress {
        height: 8px;
        border-radius: 20px;
        overflow: hidden;
        background-color: #f1f5f9;
        margin-top: 5px;
    }
    
    .progress-bar {
        height: 100%;
        border-radius: 20px;
    }
    
    /* Activity list styles */
    .activity-list {
        padding: 0;
        list-style: none;
        margin: 0;
        height: 315px;
        overflow-y: auto;
    }
    
    .activity-item {
        padding: 15px 20px;
        border-bottom: 1px solid #f5f7fa;
        display: flex;
        align-items: flex-start;
    }
    
    .activity-icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
        font-size: 1rem;
        flex-shrink: 0;
    }
    
    .activity-content {
        flex: 1;
    }
    
    .activity-title {
        font-weight: 500;
        color: #333;
        margin-bottom: 3px;
        font-size: 0.95rem;
    }
    
    .activity-time {
        font-size: 0.8rem;
        color: #718096;
    }
    
    /* Quick actions styles */
    .quick-actions {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
        padding: 20px;
    }
    
    .quick-action-btn {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 15px;
        border-radius: 12px;
        background: #f8fafc;
        text-decoration: none;
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
    }
    
    .quick-action-btn:hover {
        background: white;
        box-shadow: 0 8px 15px rgba(0,0,0,0.05);
        transform: translateY(-3px);
        border-color: #e2e8f0;
    }
    
    .quick-action-icon {
        font-size: 24px;
        margin-bottom: 10px;
        color: #3d6eea;
    }
    
    .quick-action-title {
        font-weight: 500;
        color: #333;
        font-size: 0.9rem;
        text-align: center;
    }
    
    /* Syncing Status Styles */
    .sync-status {
        padding: 20px;
    }
    
    .sync-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
        padding-bottom: 15px;
        border-bottom: 1px solid #f5f7fa;
    }
    
    .sync-item:last-child {
        margin-bottom: 0;
        padding-bottom: 0;
        border-bottom: none;
    }
    
    .sync-platform {
        display: flex;
        align-items: center;
    }
    
    .sync-platform-icon {
        width: 30px;
        height: 30px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 10px;
        background: linear-gradient(135deg, #3d6eea, #6e47ef);
        color: white;
        font-size: 16px;
    }
    
    .sync-platform-name {
        font-weight: 500;
        color: #333;
        font-size: 0.95rem;
    }
    
    .sync-status-badge {
        padding: 4px 10px;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 500;
    }
    
    .sync-connected {
        background-color: rgba(66, 184, 131, 0.1);
        color: #42b883;
    }
    
    .sync-pending {
        background-color: rgba(255, 159, 67, 0.1);
        color: #ff9f43;
    }
    
    .sync-error {
        background-color: rgba(235, 51, 73, 0.1);
        color: #eb3349;
    }
</style>

<div class="container-fluid">
    <!-- Header section with gradient background -->
    <div class="dashboard-header d-flex justify-content-between align-items-center">
        <div>
            <h2>Panel de Control</h2>
            <p>Gestión de integración Amazon-BigBuy</p>
        </div>
        <div class="d-flex align-items-center">
            <input type="text" class="dashboard-search-box me-3" placeholder="Buscar...">
            <div class="user-profile">
                <div class="user-info">
                    <div class="user-name">Admin</div>
                    <div class="user-role">Administrador</div>
                </div>
                <img src="https://ui-avatars.com/api/?name=Admin&background=0D8ABC&color=fff" alt="User Avatar" class="user-avatar">
            </div>
        </div>
    </div>
    
    <!-- KPI Cards Row -->
    <div class="row">
        <div class="col-md-3">
            <div class="dashboard-card">
                <div class="kpi-card">
                    <div class="kpi-icon icon-orders">
                        <i class="bi bi-box"></i>
                    </div>
                    <div class="kpi-value">124</div>
                    <p class="kpi-label">Pedidos Totales</p>
                    <span class="kpi-change kpi-up">
                        <i class="bi bi-arrow-up-short me-1"></i>12.5%
                    </span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="dashboard-card">
                <div class="kpi-card">
                    <div class="kpi-icon icon-products">
                        <i class="bi bi-cart"></i>
                    </div>
                    <div class="kpi-value">87</div>
                    <p class="kpi-label">Productos Activos</p>
                    <span class="kpi-change kpi-up">
                        <i class="bi bi-arrow-up-short me-1"></i>5.3%
                    </span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="dashboard-card">
                <div class="kpi-card">
                    <div class="kpi-icon icon-revenue">
                        <i class="bi bi-currency-euro"></i>
                    </div>
                    <div class="kpi-value">8.542€</div>
                    <p class="kpi-label">Ingresos del Mes</p>
                    <span class="kpi-change kpi-up">
                        <i class="bi bi-arrow-up-short me-1"></i>7.2%
                    </span>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="dashboard-card">
                <div class="kpi-card">
                    <div class="kpi-icon icon-customers">
                        <i class="bi bi-people"></i>
                    </div>
                    <div class="kpi-value">43</div>
                    <p class="kpi-label">Clientes</p>
                    <span class="kpi-change kpi-up">
                        <i class="bi bi-arrow-up-short me-1"></i>3.8%
                    </span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Charts & Status Row -->
    <div class="row">
        <!-- Sales Chart -->
        <div class="col-lg-8">
            <div class="dashboard-card">
                <div class="dashboard-card-header">
                    <h5 class="dashboard-card-title">Evolución de Pedidos</h5>
                    <div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn btn-outline-secondary active">7 días</button>
                        <button type="button" class="btn btn-outline-secondary">30 días</button>
                        <button type="button" class="btn btn-outline-secondary">1 año</button>
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="salesChart"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Order Status -->
        <div class="col-lg-4">
            <div class="dashboard-card">
                <div class="dashboard-card-header">
                    <h5 class="dashboard-card-title">Estado de Pedidos</h5>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-light dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                            Este mes
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <li><a class="dropdown-item" href="#">Este mes</a></li>
                            <li><a class="dropdown-item" href="#">Último mes</a></li>
                            <li><a class="dropdown-item" href="#">Este año</a></li>
                        </ul>
                    </div>
                </div>
                <div class="status-widget">
                    <div class="status-item">
                        <div class="status-color" style="background-color: #6e47ef;"></div>
                        <div class="status-label">Pendientes</div>
                        <div class="status-value">32</div>
                    </div>
                    <div class="status-progress">
                        <div class="progress-bar" style="width: 32%; background: linear-gradient(to right, #6e47ef, #8662f6);"></div>
                    </div>
                    
                    <div class="status-item mt-4">
                        <div class="status-color" style="background-color: #3d6eea;"></div>
                        <div class="status-label">Procesando</div>
                        <div class="status-value">17</div>
                    </div>
                    <div class="status-progress">
                        <div class="progress-bar" style="width: 17%; background: linear-gradient(to right, #3d6eea, #5c8aee);"></div>
                    </div>
                    
                    <div class="status-item mt-4">
                        <div class="status-color" style="background-color: #00c6ff;"></div>
                        <div class="status-label">Enviados</div>
                        <div class="status-value">45</div>
                    </div>
                    <div class="status-progress">
                        <div class="progress-bar" style="width: 45%; background: linear-gradient(to right, #00c6ff, #33d1ff);"></div>
                    </div>
                    
                    <div class="status-item mt-4">
                        <div class="status-color" style="background-color: #42b883;"></div>
                        <div class="status-label">Entregados</div>
                        <div class="status-value">28</div>
                    </div>
                    <div class="status-progress">
                        <div class="progress-bar" style="width: 28%; background: linear-gradient(to right, #42b883, #60ca9b);"></div>
                    </div>
                    
                    <div class="status-item mt-4">
                        <div class="status-color" style="background-color: #fc4a1a;"></div>
                        <div class="status-label">Cancelados</div>
                        <div class="status-value">2</div>
                    </div>
                    <div class="status-progress">
                        <div class="progress-bar" style="width: 2%; background: linear-gradient(to right, #fc4a1a, #fc6e48);"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Activity & Platform Status Row -->
    <div class="row">
        <!-- Recent Activity -->
        <div class="col-lg-6">
            <div class="dashboard-card">
                <div class="dashboard-card-header">
                    <h5 class="dashboard-card-title">Actividad Reciente</h5>
                    <a href="#" class="btn btn-sm btn-light">Ver todo</a>
                </div>
                <ul class="activity-list">
                    <li class="activity-item">
                        <div class="activity-icon" style="background-color: rgba(61, 110, 234, 0.1); color: #3d6eea;">
                            <i class="bi bi-box"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-title">Nuevo pedido #AB12345 recibido</div>
                            <div class="activity-time">Hace 15 minutos</div>
                        </div>
                    </li>
                    <li class="activity-item">
                        <div class="activity-icon" style="background-color: rgba(66, 184, 131, 0.1); color: #42b883;">
                            <i class="bi bi-check-circle"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-title">Pedido #CD98765 enviado correctamente</div>
                            <div class="activity-time">Hace 45 minutos</div>
                        </div>
                    </li>
                    <li class="activity-item">
                        <div class="activity-icon" style="background-color: rgba(255, 159, 67, 0.1); color: #ff9f43;">
                            <i class="bi bi-arrow-repeat"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-title">Sincronización de inventario completada</div>
                            <div class="activity-time">Hace 1 hora</div>
                        </div>
                    </li>
                    <li class="activity-item">
                        <div class="activity-icon" style="background-color: rgba(110, 71, 239, 0.1); color: #6e47ef;">
                            <i class="bi bi-cart-plus"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-title">Nuevo producto agregado: Auriculares Bluetooth</div>
                            <div class="activity-time">Hace 3 horas</div>
                        </div>
                    </li>
                    <li class="activity-item">
                        <div class="activity-icon" style="background-color: rgba(235, 51, 73, 0.1); color: #eb3349;">
                            <i class="bi bi-exclamation-circle"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-title">Alerta: Stock bajo en 5 productos</div>
                            <div class="activity-time">Hace 5 horas</div>
                        </div>
                    </li>
                    <li class="activity-item">
                        <div class="activity-icon" style="background-color: rgba(61, 110, 234, 0.1); color: #3d6eea;">
                            <i class="bi bi-person"></i>
                        </div>
                        <div class="activity-content">
                            <div class="activity-title">Nuevo cliente registrado: Laura Martínez</div>
                            <div class="activity-time">Ayer, 18:45</div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        
        <!-- Quick Actions & Platform Status -->
        <div class="col-lg-6">
            <div class="row">
                <!-- Quick Actions -->
                <div class="col-md-12 mb-4">
                    <div class="dashboard-card">
                        <div class="dashboard-card-header">
                            <h5 class="dashboard-card-title">Acciones Rápidas</h5>
                        </div>
                        <div class="quick-actions">
                            <a href="{{ route('orders.create') }}" class="quick-action-btn">
                                <i class="bi bi-plus-circle quick-action-icon"></i>
                                <span class="quick-action-title">Nuevo Pedido</span>
                            </a>
                            <a href="{{ route('products.create') }}" class="quick-action-btn">
                                <i class="bi bi-cart-plus quick-action-icon"></i>
                                <span class="quick-action-title">Añadir Producto</span>
                            </a>
                            <a href="{{ route('orders.process.form') }}" class="quick-action-btn">
                                <i class="bi bi-arrow-repeat quick-action-icon"></i>
                                <span class="quick-action-title">Procesar Amazon</span>
                            </a>
                            <a href="{{ route('reports.index') }}" class="quick-action-btn">
                                <i class="bi bi-bar-chart quick-action-icon"></i>
                                <span class="quick-action-title">Ver Informes</span>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Platform Status -->
                <div class="col-md-12">
                    <div class="dashboard-card">
                        <div class="dashboard-card-header">
                            <h5 class="dashboard-card-title">Estado de Plataformas</h5>
                        </div>
                        <div class="sync-status">
                            <div class="sync-item">
                                <div class="sync-platform">
                                    <div class="sync-platform-icon">
                                        <i class="bi bi-amazon"></i>
                                    </div>
                                    <div class="sync-platform-name">Amazon</div>
                                </div>
                                <span class="sync-status-badge sync-connected">
                                    <i class="bi bi-check-circle me-1"></i>Conectado
                                </span>
                            </div>
                            <div class="sync-item">
                                <div class="sync-platform">
                                    <div class="sync-platform-icon">
                                        <i class="bi bi-bag"></i>
                                    </div>
                                    <div class="sync-platform-name">BigBuy</div>
                                </div>
                                <span class="sync-status-badge sync-connected">
                                    <i class="bi bi-check-circle me-1"></i>Conectado
                                </span>
                            </div>
                            <div class="sync-item">
                                <div class="sync-platform">
                                    <div class="kpi-icon icon-sync" style="width: 30px; height: 30px; font-size: 16px; margin: 0 10px 0 0;">
                                        <i class="bi bi-arrow-repeat"></i>
                                    </div>
                                    <div class="sync-platform-name">Sincronización</div>
                                </div>
                                <span class="sync-status-badge sync-pending">
                                    <i class="bi bi-clock me-1"></i>Último: 45m
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Ventas Chart
        const salesCtx = document.getElementById('salesChart').getContext('2d');
        
        const salesChart = new Chart(salesCtx, {
            type: 'line',
            data: {
                labels: ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'],
                datasets: [{
                    label: 'Pedidos',
                    data: [5, 8, 12, 14, 10, 15, 8],
                    borderColor: '#3d6eea',
                    backgroundColor: 'rgba(61, 110, 234, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#6e47ef',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5
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
                            drawBorder: false,
                            borderDash: [5, 5]
                        },
                        ticks: {
                            precision: 0
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
    });
</script>
@endsection
