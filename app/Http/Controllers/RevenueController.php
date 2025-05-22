<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Services\StatsService;

class RevenueController extends Controller
{
    protected $statsService;
    
    public function __construct(StatsService $statsService)
    {
        $this->statsService = $statsService;
    }
    
    /**
     * Muestra el informe de ingresos
     */
    public function index(Request $request)
    {
        // Obtener parámetros de filtro
        $period = $request->input('period', 'month');
        $start_date = null;
        $end_date = Carbon::now();
        
        // Establecer fechas según el período seleccionado
        switch ($period) {
            case 'week':
                $start_date = Carbon::now()->subDays(7);
                break;
            case 'month':
                $start_date = Carbon::now()->subDays(30);
                break;
            case 'quarter':
                $start_date = Carbon::now()->subMonths(3);
                break;
            case 'year':
                $start_date = Carbon::now()->subYear();
                break;
            case 'custom':
                $start_date = $request->has('start_date') 
                    ? Carbon::createFromFormat('Y-m-d', $request->input('start_date')) 
                    : Carbon::now()->subDays(30);
                $end_date = $request->has('end_date')
                    ? Carbon::createFromFormat('Y-m-d', $request->input('end_date'))
                    : Carbon::now();
                break;
        }
        
        // Obtener estadísticas para el dashboard
        $stats = [
            'total_orders' => Order::count(),
            'total_customers' => Customer::count(),
            'orders_period' => Order::whereBetween('created_at', [$start_date, $end_date])->count(),
            'revenue_period' => Order::whereBetween('created_at', [$start_date, $end_date])
                ->where('status', '!=', 'cancelado')
                ->sum('total_amount'),
            'days_in_period' => $start_date->diffInDays($end_date) + 1,
            'avg_order_value' => Order::whereBetween('created_at', [$start_date, $end_date])
                ->where('status', '!=', 'cancelado')
                ->avg('total_amount') ?? 0,
        ];
        
        return view('reports.revenue', [
            'stats' => $stats,
            'period' => $period,
            'start_date_formatted' => $start_date->format('Y-m-d'),
            'end_date_formatted' => $end_date->format('Y-m-d')
        ]);
    }
}
