<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;
use App\Services\StatsService;

class ReportController extends Controller
{
    protected $statsService;
    
    public function __construct(StatsService $statsService)
    {
        $this->statsService = $statsService;
    }
    
    /**
     * Muestra el panel de estadísticas e informes
     */
    public function index(Request $request)
    {
        // Obtener parámetros de filtro
        $period = $request->input('period', 'month');
        $start_date = null;
        $end_date = Carbon::now();
        
        switch ($period) {
            case 'week':
                $start_date = Carbon::now()->subDays(7);
                break;
            case 'month':
                $start_date = Carbon::now()->startOfMonth();
                break;
            case 'quarter':
                $start_date = Carbon::now()->subMonths(3);
                break;
            case 'year':
                $start_date = Carbon::now()->startOfYear();
                break;
            default:
                $start_date = Carbon::now()->startOfMonth();
        }
        
        // Obtener todas las estadísticas usando el servicio centralizado
        $stats = $this->statsService->getGeneralStats($start_date, $end_date);
        $platform_stats = $this->statsService->getPlatformStats();
        $monthly_orders = $this->statsService->getOrdersOverTime($period);
        $status_distribution = $this->statsService->getStatusDistribution();
        $top_customers = $this->statsService->getTopCustomers(5);
        $top_products = $this->statsService->getTopProducts(5);
        
        // Datos adicionales para gráficos avanzados que siguen siendo específicos de este controlador
        $daily_revenue = $this->getDailyRevenue();
        $category_distribution = $this->getProductCategoryDistribution();
        $platform_distribution = $this->getPlatformDistribution();
        $customer_growth = $this->getCustomerGrowth();
        $customer_retention = $this->getCustomerRetention();
        $sales_by_day = $this->getSalesByDayOfWeek();
        
        return view('reports.modern_index', [
            'stats' => $stats,
            'period' => $period,
            'start_date_formatted' => $start_date->format('Y-m-d'),
            'end_date_formatted' => $end_date->format('Y-m-d'),
            'platform_stats' => $platform_stats,
            'monthly_orders' => $monthly_orders,
            'daily_revenue' => $daily_revenue,
            'status_distribution' => $status_distribution,
            'category_distribution' => $category_distribution,
            'platform_distribution' => $platform_distribution,
            'top_customers' => $top_customers,
            'customer_growth' => $customer_growth,
            'customer_retention' => $customer_retention,
            'sales_by_day' => $sales_by_day,
            'top_products' => $top_products
        ]);
    }
    
    /**
     * Obtiene los datos de pedidos a lo largo del tiempo según el período seleccionado
     */
    private function getOrdersOverTime($period = 'month')
    {
        $labels = [];
        $data = [];
        $revenue = [];
        
        switch ($period) {
            case 'week':
                // Últimos 7 días
                for ($i = 6; $i >= 0; $i--) {
                    $date = Carbon::now()->subDays($i);
                    $labels[] = $date->format('D');
                    
                    $count = Order::whereDate('created_at', $date->toDateString())->count();
                    $data[] = $count;
                    
                    $rev = Order::whereDate('created_at', $date->toDateString())
                            ->where('status', '!=', 'cancelado')
                            ->sum('total_amount');
                    $revenue[] = $rev;
                }
                break;
                
            case 'month':
                // Últimos 30 días agrupados por semana
                for ($i = 3; $i >= 0; $i--) {
                    $startWeek = Carbon::now()->subWeeks($i)->startOfWeek();
                    $endWeek = Carbon::now()->subWeeks($i)->endOfWeek();
                    $labels[] = 'S' . (4-$i) . ': ' . $startWeek->format('d') . '-' . $endWeek->format('d M');
                    
                    $count = Order::whereBetween('created_at', [$startWeek, $endWeek])->count();
                    $data[] = $count;
                    
                    $rev = Order::whereBetween('created_at', [$startWeek, $endWeek])
                            ->where('status', '!=', 'cancelado')
                            ->sum('total_amount');
                    $revenue[] = $rev;
                }
                break;
                
            case 'quarter':
                // Últimos 3 meses
                for ($i = 2; $i >= 0; $i--) {
                    $month = Carbon::now()->subMonths($i);
                    $labels[] = $month->format('M Y');
                    
                    $count = Order::whereMonth('created_at', $month->month)
                            ->whereYear('created_at', $month->year)
                            ->count();
                    $data[] = $count;
                    
                    $rev = Order::whereMonth('created_at', $month->month)
                            ->whereYear('created_at', $month->year)
                            ->where('status', '!=', 'cancelado')
                            ->sum('total_amount');
                    $revenue[] = $rev;
                }
                break;
                
            case 'year':
                // Últimos 6 meses
                for ($i = 5; $i >= 0; $i--) {
                    $month = Carbon::now()->subMonths($i);
                    $labels[] = $month->format('M Y');
                    
                    $count = Order::whereMonth('created_at', $month->month)
                            ->whereYear('created_at', $month->year)
                            ->count();
                    $data[] = $count;
                    
                    $rev = Order::whereMonth('created_at', $month->month)
                            ->whereYear('created_at', $month->year)
                            ->where('status', '!=', 'cancelado')
                            ->sum('total_amount');
                    $revenue[] = $rev;
                }
                break;
        }
        
        return [
            'labels' => $labels,
            'orders' => $data,
            'revenue' => $revenue
        ];
    }
    
    /**
     * Obtiene los ingresos diarios para el gráfico de tendencias
     */
    private function getDailyRevenue()
    {
        $dates = [];
        $revenue = [];
        
        // Últimos 14 días
        for ($i = 13; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dates[] = $date->format('d M');
            
            $rev = Order::whereDate('created_at', $date->toDateString())
                    ->where('status', '!=', 'cancelado')
                    ->sum('total_amount');
            $revenue[] = $rev;
        }
        
        return [
            'labels' => $dates,
            'data' => $revenue
        ];
    }
    
    /**
     * Obtiene datos para el gráfico de distribución de estados
     */
    private function getStatusDistribution()
    {
        return Order::select('status', DB::raw('count(*) as total'))
                    ->groupBy('status')
                    ->get()
                    ->mapWithKeys(function ($item) {
                        // Traducir estados a español para la visualización
                        $label = match($item->status) {
                            'pendiente' => 'Pendientes',
                            'procesando' => 'En proceso',
                            'enviado' => 'Enviados',
                            'entregado' => 'Entregados',
                            'cancelado' => 'Cancelados',
                            'error' => 'Con errores',
                            default => ucfirst($item->status)
                        };
                        
                        return [$label => $item->total];
                    })
                    ->toArray();
    }
    
    /**
     * Genera un reporte específico según el tipo solicitado
     */
    public function generateReport(Request $request, $type)
    {
        // Aquí se implementaría la lógica para exportar diferentes tipos de reportes
        // como CSV, PDF, etc.
        
        return back()->with('info', 'Funcionalidad en desarrollo');
    }
    
    /**
     * Obtiene la distribución de productos por categoría
     */
    private function getProductCategoryDistribution()
    {
        return Product::select('category', DB::raw('count(*) as total'))
                    ->groupBy('category')
                    ->orderBy('total', 'desc')
                    ->get()
                    ->take(8)
                    ->pluck('total', 'category')
                    ->toArray();
    }
    
    /**
     * Obtiene la distribución de productos por plataforma
     */
    private function getPlatformDistribution()
    {
        $total = Product::count();
        $amazon_only = Product::whereNotNull('amazon_asin')
                           ->whereNull('bigbuy_id')
                           ->count();
        $bigbuy_only = Product::whereNull('amazon_asin')
                           ->whereNotNull('bigbuy_id')
                           ->count();
        $both = Product::whereNotNull('amazon_asin')
                    ->whereNotNull('bigbuy_id')
                    ->count();
        $none = $total - $amazon_only - $bigbuy_only - $both;
        
        return [
            'Solo Amazon' => $amazon_only,
            'Solo BigBuy' => $bigbuy_only,
            'Ambas plataformas' => $both,
            'Sin plataforma' => $none
        ];
    }
    
    /**
     * Obtiene datos de crecimiento de clientes por mes
     */
    private function getCustomerGrowth()
    {
        $months = [];
        $newCustomers = [];
        $totalCustomers = [];
        
        // Últimos 6 meses
        $total = 0;
        
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $months[] = $month->format('M Y');
            
            $new = Customer::whereMonth('created_at', $month->month)
                           ->whereYear('created_at', $month->year)
                           ->count();
            $total += $new;
            
            $newCustomers[] = $new;
            $totalCustomers[] = $total;
        }
        
        return [
            'labels' => $months,
            'new' => $newCustomers,
            'total' => $totalCustomers
        ];
    }
    
    /**
     * Analiza la retención de clientes
     */
    private function getCustomerRetention()
    {
        // Simulamos la tasa de retención para demostración
        // En una implementación real esto vendría de un análisis más complejo
        $months = [];
        $retention = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $months[] = $month->format('M Y');
            
            // Simula una tasa de retención entre 70% y 90%
            $retention[] = 70 + rand(0, 20);
        }
        
        return [
            'labels' => $months,
            'retention' => $retention
        ];
    }
    
    /**
     * Obtiene datos de ventas por día de la semana
     */
    private function getSalesByDayOfWeek()
    {
        $days = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
        $counts = [];
        $revenue = [];
        
        // Para cada día de la semana (1 = lunes, 7 = domingo)
        for ($i = 1; $i <= 7; $i++) {
            $count = Order::whereRaw("DAYOFWEEK(created_at) = ?", [($i % 7) + 1])
                        ->count();
            $counts[] = $count;
            
            $rev = Order::whereRaw("DAYOFWEEK(created_at) = ?", [($i % 7) + 1])
                        ->where('status', '!=', 'cancelado')
                        ->sum('total_amount');
            $revenue[] = $rev;
        }
        
        return [
            'labels' => $days,
            'orders' => $counts,
            'revenue' => $revenue
        ];
    }
    
    /**
     * Obtiene los productos más vendidos
     */
    private function getTopProducts()
    {
        // En una implementación real esto vendría de una relación entre pedidos y productos
        // Para la demo, simulamos con datos aleatorios
        $products = Product::inRandomOrder()->take(5)->get();
        
        foreach ($products as $product) {
            $product->sales_count = rand(10, 100);
            $product->revenue = $product->sales_count * $product->price;
        }
        
        return $products->sortByDesc('sales_count')->values();
    }
}
