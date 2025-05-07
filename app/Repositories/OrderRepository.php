<?php

namespace App\Repositories;

use App\Models\Order;

class OrderRepository
{
    public function create(array $data)
    {
        return Order::create($data);
    }

    public function find($id)
    {
        return Order::with(['items', 'customer'])->find($id);
    }

    public function updateTracking($orderId, $trackingNumber)
    {
        $order = Order::findOrFail($orderId);
        $order->tracking_number = $trackingNumber;
        $order->status = 'enviado';
        $order->save();
        return $order;
    }

    public function updateStatus($orderId, $status)
    {
        $order = Order::findOrFail($orderId);
        $order->status = $status;
        $order->save();
        return $order;
    }

    // Otros métodos CRUD según necesidad
}
