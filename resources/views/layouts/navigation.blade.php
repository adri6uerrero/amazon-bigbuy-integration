
<style>
/* Dashboard Layout Styles */
.dashboard-wrapper {
    display: flex;
    min-height: 100vh;
}

/* Sidebar Styles */
.sidebar {
    width: 240px;
    background: #23283b;
    color: #fff;
    position: fixed;
    left: 0;
    top: 0;
    bottom: 0;
    z-index: 1030;
    transition: all 0.3s;
    box-shadow: 0 0 20px rgba(0,0,0,0.1);
}

.sidebar-brand {
    display: flex;
    align-items: center;
    padding: 20px 25px;
    border-bottom: 1px solid rgba(255,255,255,0.1);
    height: 70px;
}

.sidebar-brand img, .sidebar-brand svg {
    height: 32px;
    margin-right: 10px;
}

.sidebar-brand h4 {
    font-size: 18px;
    margin: 0;
    font-weight: 500;
    color: white;
}

.sidebar-menu {
    padding: 20px 0;
}

.sidebar-menu-section {
    margin-bottom: 15px;
}

.sidebar-menu-item {
    display: flex;
    align-items: center;
    padding: 12px 25px;
    color: rgba(255,255,255,0.7);
    text-decoration: none;
    transition: all 0.3s;
    border-left: 3px solid transparent;
}

.sidebar-menu-item:hover, .sidebar-menu-item.active {
    color: white;
    background: rgba(255,255,255,0.05);
    border-left-color: #3d6eea;
}

.sidebar-menu-item i {
    font-size: 18px;
    margin-right: 10px;
    width: 24px;
    text-align: center;
}

.sidebar-menu-title {
    padding: 10px 25px;
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: rgba(255,255,255,0.5);
    font-weight: 600;
    margin-bottom: 5px;
}

.sidebar-menu-items {
    list-style: none;
    padding: 0;
    margin: 0;
}

.sidebar-menu-item {
    margin-bottom: 2px;
}

.sidebar-menu-link {
    display: flex;
    align-items: center;
    padding: 12px 25px;
    color: rgba(255,255,255,0.7);
    text-decoration: none;
    transition: all 0.2s;
    border-left: 3px solid transparent;
}

.sidebar-menu-link:hover {
    background: rgba(255,255,255,0.05);
    color: white;
}

.sidebar-menu-link.active {
    background: rgba(61, 110, 234, 0.2);
    color: #6ed6fd;
    border-left: 3px solid #6ed6fd;
}

.sidebar-menu-icon {
    margin-right: 12px;
    font-size: 18px;
    width: 22px;
    text-align: center;
}

.main-content {
    flex: 1;
    margin-left: 240px;
    background: #f5f7fa;
    min-height: 100vh;
    transition: all 0.3s;
}

.sidebar-footer {
    border-top: 1px solid rgba(255,255,255,0.1);
    padding: 15px 25px;
    position: absolute;
    bottom: 0;
    width: 100%;
    margin-top: 30px;
}

.sidebar-footer-link {
    display: flex;
    align-items: center;
    color: rgba(255,255,255,0.7);
    text-decoration: none;
    font-size: 14px;
}

.sidebar-footer-link:hover {
    color: white;
}

.sidebar-footer-icon {
    margin-right: 10px;
}

.sidebar-user {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 15px 25px;
    border-top: 1px solid rgba(255,255,255,0.1);
}

.sidebar-user-info {
    display: flex;
    align-items: center;
}

.sidebar-user-avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    object-fit: cover;
    margin-right: 12px;
    border: 2px solid rgba(255,255,255,0.2);
}

.sidebar-user-name {
    font-size: 14px;
    font-weight: 500;
    margin: 0;
    color: white;
}

.sidebar-user-role {
    font-size: 12px;
    margin: 0;
    color: rgba(255,255,255,0.6);
}

.sidebar-user-menu {
    margin-top: 12px;
    display: flex;
    justify-content: space-between;
}

.sidebar-user-link {
    color: rgba(255,255,255,0.6);
    font-size: 12px;
    text-decoration: none;
    transition: all 0.2s;
}

.sidebar-user-link:hover {
    color: white;
}

.sidebar-toggle {
    background: none;
    border: none;
    color: rgba(255,255,255,0.7);
    font-size: 24px;
    cursor: pointer;
    transition: all 0.2s;
    margin-right: 12px;
}

.sidebar-toggle:hover {
    color: white;
}

@media (max-width: 768px) {
    .sidebar {
        transform: translateX(-100%);
    }
    .sidebar.show {
        transform: translateX(0);
    }
    .main-content {
        margin-left: 0 !important;
    }
}
</style>

<div class="dashboard-wrapper">
    <!-- Sidebar -->
    <aside class="sidebar">
        <!-- Logo & Brand -->
        <div class="sidebar-brand">
            <i class="bi bi-boxes me-2"></i>
            <h4>AmazonBigBuy</h4>
        </div>
        
        <!-- Menu Items -->
        <nav class="sidebar-menu">
            <!-- Dashboard Section -->
            <div class="sidebar-menu-section">
                <div class="sidebar-menu-title">General</div>
                <ul class="sidebar-menu-items">
                    <li class="sidebar-menu-item">
                        <a href="{{ route('dashboard') }}" class="sidebar-menu-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i class="bi bi-grid-1x2-fill sidebar-menu-icon"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="sidebar-menu-item">
                        <a href="{{ route('orders.index') }}" class="sidebar-menu-link {{ request()->routeIs('orders.*') ? 'active' : '' }}">
                            <i class="bi bi-box-seam-fill sidebar-menu-icon"></i>
                            <span>Pedidos</span>
                        </a>
                    </li>
                </ul>
            </div>
            
            <!-- E-commerce Section -->
            <div class="sidebar-menu-section">
                <div class="sidebar-menu-title">E-commerce</div>
                <ul class="sidebar-menu-items">
                    <li class="sidebar-menu-item">
                        <a href="{{ route('products.index') }}" class="sidebar-menu-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
                            <i class="bi bi-cart-fill sidebar-menu-icon"></i>
                            <span>Productos</span>
                        </a>
                    </li>
                    <li class="sidebar-menu-item">
                        <a href="{{ route('customers.index') }}" class="sidebar-menu-link {{ request()->routeIs('customers.*') ? 'active' : '' }}">
                            <i class="bi bi-people-fill sidebar-menu-icon"></i>
                            <span>Clientes</span>
                        </a>
                    </li>
                    <li class="sidebar-menu-item">
                        <a href="{{ route('reports.index') }}" class="sidebar-menu-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                            <i class="bi bi-bar-chart-fill sidebar-menu-icon"></i>
                            <span>Informes</span>
                        </a>
                    </li>
                </ul>
            </div>
            
            <!-- Integraciones Section -->
            <div class="sidebar-menu-section">
                <div class="sidebar-menu-title">Integraciones</div>
                <ul class="sidebar-menu-items">
                    <li class="sidebar-menu-item">
                        <a href="#" class="sidebar-menu-link">
                            <i class="bi bi-person-lines-fill sidebar-menu-icon"></i>
                            Base de Clientes
                        </a>
                    </li>
                    <li class="sidebar-menu-item">
                        <a href="#" class="sidebar-menu-link">
                            <i class="bi bi-chat-dots sidebar-menu-icon"></i>
                            Mensajes
                        </a>
                    </li>
                </ul>
            </div>
            
            <!-- Administración Section -->
            <div class="sidebar-menu-section" style="margin-bottom: 30px;">
                <div class="sidebar-menu-title">Administración</div>
                <ul class="sidebar-menu-items">
                    <li class="sidebar-menu-item">
                        <a href="{{ route('config.index') }}" class="sidebar-menu-link {{ request()->routeIs('config.*') ? 'active' : '' }}">
                            <i class="bi bi-gear sidebar-menu-icon"></i>
                            <span>Configuración</span>
                        </a>
                    </li>
                    <li class="sidebar-menu-item">
                        <a href="#" class="sidebar-menu-link">
                            <i class="bi bi-graph-up sidebar-menu-icon"></i>
                            Informes
                        </a>
                    </li>
                </ul>
            </div>
            
            <!-- Espaciador -->
            <div style="height: 60px;"></div>
        </nav>
        
        <!-- Footer -->
        <div class="sidebar-footer" style="background-color: #1c2132; border-top: 1px solid rgba(255,255,255,0.15);">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="sidebar-footer-link" style="background:transparent; border:none; width:100%; text-align:left; cursor:pointer; padding: 10px; transition: all 0.3s ease;">
                    <i class="bi bi-box-arrow-right sidebar-footer-icon" style="margin-right: 12px;"></i>
                    Cerrar Sesión
                </button>
            </form>
        </div>
    </aside>
    
    <!-- Main Content Area -->
    <main class="main-content">
        @yield('content')
    </main>
</div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            @if(Auth::check() && Auth::user()->role === 'admin')
                <x-responsive-nav-link :href="route('users.index')" :active="request()->routeIs('users.index')">
                    {{ __('Usuarios') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">
    @if(Auth::check())
        {{ Auth::user()->name }}
    @else
        Invitado
    @endif
</div>
<div class="font-medium text-sm text-gray-500">
    @if(Auth::check())
        {{ Auth::user()->email }}
    @else
        -
    @endif
</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
