<?php

namespace App\Services;

class BigBuyService
{
    public function sendOrder(array $orderData)
    {
        // Simulación: responder como si BigBuy aceptara el pedido y devolviera un número de seguimiento
        return [
            'success' => true,
            'tracking_number' => 'BB123456789ES',
            'message' => 'Pedido enviado a BigBuy (simulado)',
            'order_reference' => $orderData['amazon_order_id'] ?? null,
        ];
    }

    public function getStockAndPrices(array $skus)
    {
        // Simulación: devolver stock y precios ficticios
        $result = [];
        foreach ($skus as $sku) {
            $result[] = [
                'sku' => $sku,
                'stock' => rand(0, 100),
                'price' => rand(10, 100) + 0.99,
            ];
        }
        return $result;
    }
}
