<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AmazonService;
use App\Repositories\OrderRepository;

class AmazonController extends Controller
{
    protected $amazonService;
    protected $orderRepository;

    public function __construct(AmazonService $amazonService, OrderRepository $orderRepository)
    {
        $this->amazonService = $amazonService;
        $this->orderRepository = $orderRepository;
    }

    public function show($id)
    {
        // Obtener datos del pedido desde Amazon API
        $orderData = $this->amazonService->getOrderData($id);
        return response()->json($orderData);
    }
}
