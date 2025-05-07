<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OrderAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('customer');
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('amazon_order_id', 'like', "%$search%")
                  ->orWhere('tracking_number', 'like', "%$search%")
                  ->orWhereHas('customer', function($q2) use ($search) {
                      $q2->where('name', 'like', "%$search%")
                         ->orWhere('email', 'like', "%$search%")
                         ->orWhere('address', 'like', "%$search%") ;
                  });
            });
        }
        $orders = $query->orderByDesc('created_at')->paginate(15)->withQueryString();
        return view('orders', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with(['customer', 'items'])->findOrFail($id);
        return view('order_detail', compact('order'));
    }

    public function retry($id)
    {
        // Aquí se podría reintentar el procesamiento real
        // Por ahora, solo redirige con mensaje de éxito
        return redirect()->route('orders.index')->with('status', 'Procesamiento manual solicitado para el pedido #' . $id);
    }
}
