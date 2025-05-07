<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\BigBuyService;

class BigBuyController extends Controller
{
    protected $bigBuyService;

    public function __construct(BigBuyService $bigBuyService)
    {
        $this->bigBuyService = $bigBuyService;
    }

    public function store(Request $request)
    {
        // Enviar pedido a BigBuy
        $response = $this->bigBuyService->sendOrder($request->all());
        return response()->json($response);
    }
}
