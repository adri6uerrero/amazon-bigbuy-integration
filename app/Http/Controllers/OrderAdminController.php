<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OrderAdminController extends Controller
{
    public function reopen($id)
    {
        $order = \App\Models\Order::findOrFail($id);
        $order->status = 'pendiente';
        $order->save();
        \App\Models\OrderLog::create([
            'order_id' => $order->id,
            'action' => 'reabrir',
            'description' => 'Pedido reabierto manualmente desde el panel',
        ]);
        return redirect()->route('orders.show', $order->id)->with('status', 'Pedido reabierto.');
    }

    public function resendEmail($id)
    {
        $order = \App\Models\Order::findOrFail($id);
        // Aquí iría la lógica real de reenvío de email (simulada)
        \App\Models\OrderLog::create([
            'order_id' => $order->id,
            'action' => 'reenviar_email',
            'description' => 'Correo reenviado al cliente (simulado)',
        ]);
        return redirect()->route('orders.show', $order->id)->with('status', 'Correo reenviado al cliente.');
    }

    public function cancel($id)
    {
        $order = \App\Models\Order::findOrFail($id);
        $order->status = 'cancelado';
        $order->save();
        \App\Models\OrderLog::create([
            'order_id' => $order->id,
            'action' => 'cancelar',
            'description' => 'Pedido cancelado manualmente desde el panel',
        ]);
        return redirect()->route('orders.show', $order->id)->with('status', 'Pedido cancelado.');
    }

    public function addNote(Request $request, $id)
    {
        $order = \App\Models\Order::findOrFail($id);
        $request->validate(['note' => 'required|string|max:1000']);
        \App\Models\OrderLog::create([
            'order_id' => $order->id,
            'action' => 'nota',
            'description' => $request->note,
        ]);
        return redirect()->route('orders.show', $order->id)->with('status', 'Nota añadida al historial.');
    }

    public function markShipped($id)
    {
        $order = \App\Models\Order::findOrFail($id);
        $order->status = 'enviado';
        $order->save();
        // Registrar log/historial
        \App\Models\OrderLog::create([
            'order_id' => $order->id,
            'action' => 'marcar_enviado',
            'description' => 'Pedido marcado como enviado manualmente desde el panel',
        ]);
        return redirect()->route('orders.show', $order->id)->with('status', 'Pedido marcado como enviado.');
    }

    public function create()
    {
        return view('order_create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email',
            'customer_address' => 'required|string',
            'amazon_order_id' => 'required|string|max:255',
            'status' => 'required|string',
            'tracking_number' => 'nullable|string',
            'items.*.sku' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        $customer = \App\Models\Customer::firstOrCreate([
            'email' => $validated['customer_email']
        ], [
            'name' => $validated['customer_name'],
            'address' => $validated['customer_address'],
        ]);

        $order = $customer->orders()->create([
            'amazon_order_id' => $validated['amazon_order_id'],
            'status' => $validated['status'],
            'tracking_number' => $validated['tracking_number'] ?? null,
        ]);

        foreach ($validated['items'] as $item) {
            $order->items()->create($item);
        }

        return redirect()->route('orders.index')->with('status', 'Pedido creado exitosamente.');
    }

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
