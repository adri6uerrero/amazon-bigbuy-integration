<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\OrderRepository;
use App\Services\AmazonService;
use App\Services\BigBuyService;

class OrderController extends Controller
{
    protected $orderRepository;
    protected $amazonService;
    protected $bigBuyService;

    public function __construct(OrderRepository $orderRepository, AmazonService $amazonService, BigBuyService $bigBuyService)
    {
        $this->orderRepository = $orderRepository;
        $this->amazonService = $amazonService;
        $this->bigBuyService = $bigBuyService;
    }

    public function processOrder(Request $request)
    {
        $amazonOrderId = $request->input('amazon_order_id');
        // 1. Obtener datos del pedido desde Amazon
        $orderData = $this->amazonService->getOrderData($amazonOrderId);
        if (!$orderData) {
            return response()->json(['error' => 'No se pudo obtener el pedido de Amazon'], 404);
        }

        // 2. Crear o actualizar cliente
        $customer = \App\Models\Customer::updateOrCreate(
            ['email' => $orderData['customer']['email']],
            [
                'name' => $orderData['customer']['name'],
                'address' => $orderData['customer']['address'],
            ]
        );

        // 3. Crear pedido
        $order = $this->orderRepository->create([
            'amazon_order_id' => $amazonOrderId,
            'status' => 'pendiente',
            'customer_id' => $customer->id,
        ]);

        // 4. Crear items
        foreach ($orderData['items'] as $item) {
            $order->items()->create([
                'sku' => $item['sku'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }

        // 5. Enviar pedido a BigBuy
        $bigBuyResponse = $this->bigBuyService->sendOrder([
            'order' => $order,
            'customer' => $customer,
            'items' => $order->items,
        ]);

        // 6. Si BigBuy responde con tracking, actualizar pedido y Amazon
        if (isset($bigBuyResponse['tracking_number'])) {
            $this->orderRepository->updateTracking($order->id, $bigBuyResponse['tracking_number']);
            $this->amazonService->updateOrderStatus($amazonOrderId, 'enviado', $bigBuyResponse['tracking_number']);
        }

        return response()->json([
            'order' => $order->load(['items', 'customer']),
            'bigbuy_response' => $bigBuyResponse,
        ]);
    }
}
