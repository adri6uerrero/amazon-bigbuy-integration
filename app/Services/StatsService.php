<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StatsService
{
    /**
     * Obtiene las estadísticas generales para el dashboard y reportes
     */
    public function getGeneralStats($start_date, $end_date)
    {
        return [
            'total_orders' => Order::count(),
            'total_customers' => Customer::count(),
            'total_products' => Product::count(),
            'orders_period' => Order::whereBetween('created_at', [$start_date, $end_date])->count(),
            'revenue_period' => Order::whereBetween('created_at', [$start_date, $end_date])
                                ->where('status', '!=', 'cancelado')
                                ->sum('total_amount'),
            'avg_order_value' => Order::where('status', '!=', 'cancelado')
                                ->avg('total_amount') ?? 0,
            'pending_orders' => Order::where('status', 'pendiente')->count(),
            'shipped_orders' => Order::where('status', 'enviado')->count(),
            'delivered_orders' => Order::where('status', 'entregado')->count(),
            'canceled_orders' => Order::where('status', 'cancelado')->count(),
            'orders_last_month' => Order::whereBetween('created_at', [Carbon::now()->subMonth(), Carbon::now()])->count(),
        ];
    }

    /**
     * Obtiene estadísticas de plataformas
     */
    public function getPlatformStats()
    {
        return [
            'amazon_products' => Product::whereNotNull('amazon_asin')->count(),
            'bigbuy_products' => Product::whereNotNull('bigbuy_id')->count(),
            'synced_products' => Product::whereNotNull('amazon_asin')->whereNotNull('bigbuy_id')->count(),
            'stock_issues' => Product::whereRaw('amazon_stock != bigbuy_stock')->count(),
            'price_issues' => Product::whereRaw('ABS(amazon_price - bigbuy_price) >= 0.01')->count(),
        ];
    }

    /**
     * Obtiene datos de órdenes a lo largo del tiempo
     */
    public function getOrdersOverTime($period)
    {
        $labels = [];
        $data = [];
        $revenue = [];
        
        switch ($period) {
            case 'week':
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
                for ($i = 29; $i >= 0; $i--) {
                    $date = Carbon::now()->subDays($i);
                    if ($i % 5 == 0) { // Solo mostrar cada 5 días para no saturar
                        $labels[] = $date->format('d M');
                    } else {
                        $labels[] = '';
                    }
                    $count = Order::whereDate('created_at', $date->toDateString())->count();
                    $data[] = $count;
                    $rev = Order::whereDate('created_at', $date->toDateString())
                             ->where('status', '!=', 'cancelado')
                             ->sum('total_amount');
                    $revenue[] = $rev;
                }
                break;
                
            case 'quarter':
                for ($i = 11; $i >= 0; $i--) {
                    $date = Carbon::now()->subWeeks($i);
                    $labels[] = 'Sem ' . $date->format('W');
                    $weekStart = $date->copy()->startOfWeek();
                    $weekEnd = $date->copy()->endOfWeek();
                    $count = Order::whereBetween('created_at', [$weekStart, $weekEnd])->count();
                    $data[] = $count;
                    $rev = Order::whereBetween('created_at', [$weekStart, $weekEnd])
                             ->where('status', '!=', 'cancelado')
                             ->sum('total_amount');
                    $revenue[] = $rev;
                }
                break;
                
            case 'year':
                for ($i = 11; $i >= 0; $i--) {
                    $date = Carbon::now()->subMonths($i);
                    $labels[] = $date->format('M');
                    $monthStart = $date->copy()->startOfMonth();
                    $monthEnd = $date->copy()->endOfMonth();
                    $count = Order::whereBetween('created_at', [$monthStart, $monthEnd])->count();
                    $data[] = $count;
                    $rev = Order::whereBetween('created_at', [$monthStart, $monthEnd])
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
     * Obtiene la distribución de órdenes por estado
     */
    public function getStatusDistribution()
    {
        $statuses = [
            'pendiente' => 'Pendientes',
            'en_proceso' => 'En proceso',
            'enviado' => 'Enviados',
            'entregado' => 'Entregados',
            'cancelado' => 'Cancelados',
            'error' => 'Con errores'
        ];
        
        $distribution = [];
        
        foreach ($statuses as $key => $label) {
            $count = Order::where('status', $key)->count();
            if ($count > 0) {
                $distribution[$label] = $count;
            }
        }
        
        return $distribution;
    }

    /**
     * Obtiene top clientes
     */
    public function getTopCustomers($limit = 5)
    {
        return Customer::withCount('orders')
                    ->orderBy('orders_count', 'desc')
                    ->take($limit)
                    ->get();
    }

    /**
     * Obtiene top productos
     */
    public function getTopProducts($limit = 5)
    {
        // En una implementación real esto vendría de una relación entre pedidos y productos
        // Para la demo, simulamos con datos aleatorios
        $products = Product::inRandomOrder()->take($limit)->get();
        
        foreach ($products as $product) {
            $product->sales_count = rand(10, 100);
            $product->revenue = $product->sales_count * $product->price;
        }
        
        return $products->sortByDesc('sales_count')->values();
    }
}
