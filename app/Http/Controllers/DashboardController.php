<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Calculamos solo los datos que necesitamos para el dashboard simplificado
        $totalOrders = Order::count();
        
        // Calculamos el total de ventas
        $totalSales = Order::with('items')->get()->sum(function($order) {
            return $order->items->sum(function($item) {
                return $item->price * $item->quantity;
            });
        });
        
        // Obtenemos pedidos por estado para el gráfico
        $ordersByStatus = Order::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')->pluck('total', 'status');
        
        // Simplificamos los datos necesarios para nuestro dashboard
        $salesRevenue = $totalSales;
        
        // Renderizamos directamente la vista de dashboard con estilo cartera digital
        return view('dashboard', [
            'salesRevenue' => $salesRevenue
        ]);
    }
}
