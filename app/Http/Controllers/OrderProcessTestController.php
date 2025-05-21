<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AmazonService;
use App\Services\BigBuyService;

class OrderProcessTestController extends Controller
{
    public function simulate(Request $request)
    {
        $amazonOrderId = $request->input('amazon_order_id', 'AMZ-TEST-001');

        $amazonService = app(AmazonService::class);
        $bigBuyService = app(BigBuyService::class);

        // 1. Obtener datos del pedido de Amazon (simulado)
        $orderData = $amazonService->getOrderData($amazonOrderId);

        // 2. Enviar pedido a BigBuy (simulado)
        $bigBuyResponse = $bigBuyService->sendOrder($orderData);

        // 3. Actualizar estado y tracking en Amazon (simulado)
        $updateAmazon = $amazonService->updateOrderStatus(
            $amazonOrderId,
            'enviado',
            $bigBuyResponse['tracking_number'] ?? null
        );

        // 4. Si el flujo fue exitoso, actualizar el pedido real en la base de datos
        $success = ($bigBuyResponse['success'] ?? false) && ($updateAmazon['success'] ?? false);
        if ($success) {
            $order = \App\Models\Order::where('amazon_order_id', $amazonOrderId)->first();
            if ($order) {
                $order->status = 'enviado';
                $order->tracking_number = $bigBuyResponse['tracking_number'] ?? null;
                $order->save();
            }
        }
        // 5. Mostrar resultado en pantalla
        return view('order_simulation', [
            'amazonOrderId' => $amazonOrderId,
            'orderData' => $orderData,
            'bigBuyResponse' => $bigBuyResponse,
            'updateAmazon' => $updateAmazon,
        ]);
    }
}
