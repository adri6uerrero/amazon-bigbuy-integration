<?php

namespace App\Services;

class AmazonService
{
    public function getOrderData($amazonOrderId)
    {
        // Simulación: devolver datos de pedido como si vinieran de Amazon
        return [
            'amazon_order_id' => $amazonOrderId,
            'customer' => [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'address' => '123 Main St, City, Country',
            ],
            'items' => [
                ['sku' => 'SKU123', 'quantity' => 2, 'price' => 19.99],
                ['sku' => 'SKU456', 'quantity' => 1, 'price' => 49.99],
            ],
            'status' => 'pending',
            'created_at' => now()->subDays(1),
        ];
    }

    public function updateOrderStatus($amazonOrderId, $status, $trackingNumber = null)
    {
        // Simulación: siempre devolver éxito
        // Puedes agregar lógica para simular error si lo deseas
        return [
            'success' => true,
            'message' => 'Estado actualizado en Amazon (simulado)',
            'order_id' => $amazonOrderId,
            'status' => $status,
            'tracking_number' => $trackingNumber,
        ];
    }
}
