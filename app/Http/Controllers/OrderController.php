<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\OrderRepository;
use App\Services\AmazonService;
use App\Services\BigBuyService;
use App\Models\Order;
use App\Models\Customer;
use App\Models\OrderLog;

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
    
    /**
     * Muestra la lista de pedidos
     */
    public function index(Request $request)
    {
        $query = Order::with(['customer', 'items']);
        
        // Aplicar filtros si se proporcionan
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where('amazon_order_id', 'like', "%$search%")
                  ->orWhereHas('customer', function($q) use ($search) {
                      $q->where('name', 'like', "%$search%")
                        ->orWhere('email', 'like', "%$search%");
                  });
        }
        
        if ($request->has('status') && $request->get('status') != '') {
            $query->where('status', $request->get('status'));
        }
        
        if ($request->has('date_range')) {
            switch ($request->get('date_range')) {
                case 'today':
                    $query->whereDate('created_at', now()->toDateString());
                    break;
                case 'week':
                    $query->where('created_at', '>=', now()->subWeek());
                    break;
                case 'month':
                    $query->where('created_at', '>=', now()->subMonth());
                    break;
                case 'year':
                    $query->where('created_at', '>=', now()->subYear());
                    break;
            }
        }
        
        // Para demostración, creamos algunos pedidos si no hay ninguno
        if ($query->count() == 0) {
            $this->createDemoOrders();
        }
        
        $orders = $query->orderBy('created_at', 'desc')->paginate(10);
        
        return view('orders.index', compact('orders'));
    }
    
    /**
     * Muestra el formulario para crear un nuevo pedido
     */
    public function create()
    {
        return view('orders.create');
    }
    
    /**
     * Almacena un nuevo pedido en la base de datos
     */
    public function store(Request $request)
    {
        // Validar datos del pedido
        $request->validate([
            'amazon_order_id' => 'required|unique:orders,amazon_order_id',
            'status' => 'required|in:pendiente,procesando,enviado,entregado,cancelado',
            'customer_name' => 'required',
            'customer_email' => 'required|email',
            'customer_address' => 'required',
            'items' => 'required|array|min:1',
        ]);
        
        // Crear o actualizar cliente
        $customer = Customer::updateOrCreate(
            ['email' => $request->customer_email],
            [
                'name' => $request->customer_name,
                'address' => $request->customer_address,
                'phone' => $request->customer_phone,
            ]
        );
        
        // Crear pedido
        $order = Order::create([
            'amazon_order_id' => $request->amazon_order_id,
            'status' => $request->status,
            'tracking_number' => $request->tracking_number,
            'customer_id' => $customer->id,
        ]);
        
        // Crear items del pedido
        foreach ($request->items as $item) {
            $order->items()->create([
                'sku' => $item['sku'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'name' => $item['name'] ?? null,
            ]);
        }
        
        // Registrar log de creación
        $order->logs()->create([
            'description' => 'Pedido creado manualmente',
            'user_id' => auth()->id(),
        ]);
        
        return redirect()->route('orders.show', $order->id)
                         ->with('success', 'Pedido creado correctamente');
    }
    
    /**
     * Muestra los detalles de un pedido específico
     */
    public function show(Order $order)
    {
        $order->load(['customer', 'items', 'logs']);
        return view('orders.show', compact('order'));
    }
    
    /**
     * Muestra el formulario para editar un pedido existente
     */
    public function edit(Order $order)
    {
        $order->load(['customer', 'items']);
        return view('orders.edit', compact('order'));
    }
    
    /**
     * Actualiza un pedido específico en la base de datos
     */
    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pendiente,procesando,enviado,entregado,cancelado',
        ]);
        
        // Si el estado es 'enviado', validar el número de seguimiento
        if ($request->status == 'enviado') {
            $request->validate([
                'tracking_number' => 'required',
                'carrier' => 'required',
            ]);
            
            $order->update([
                'status' => $request->status,
                'tracking_number' => $request->tracking_number,
                'carrier' => $request->carrier,
            ]);
        } else {
            $order->update(['status' => $request->status]);
        }
        
        // Registrar la actualización en el log
        if ($request->filled('notes')) {
            $order->logs()->create([
                'description' => "Estado actualizado a '{$request->status}'. Nota: {$request->notes}",
                'user_id' => auth()->id(),
            ]);
        } else {
            $order->logs()->create([
                'description' => "Estado actualizado a '{$request->status}'.",
                'user_id' => auth()->id(),
            ]);
        }
        
        return redirect()->route('orders.show', $order->id)
                         ->with('success', 'Pedido actualizado correctamente');
    }
    
    /**
     * Cancela un pedido
     */
    public function cancel(Request $request, Order $order)
    {
        $order->update(['status' => 'cancelado']);
        
        $reason = $request->cancel_reason;
        if ($reason === 'otro' && $request->has('other_reason')) {
            $reason = $request->other_reason;
        }
        
        $order->logs()->create([
            'description' => "Pedido cancelado. Motivo: $reason",
            'user_id' => auth()->id(),
        ]);
        
        return redirect()->route('orders.show', $order->id)
                         ->with('success', 'Pedido cancelado correctamente');
    }
    
    /**
     * Añade una nota a un pedido
     */
    public function addNote(Request $request, Order $order)
    {
        $request->validate(['note' => 'required']);
        
        $order->logs()->create([
            'description' => $request->note,
            'user_id' => auth()->id(),
        ]);
        
        return redirect()->route('orders.show', $order->id)
                         ->with('success', 'Nota añadida correctamente');
    }
    
    /**
     * Muestra el formulario para procesar pedidos de Amazon
     */
    public function showProcessForm()
    {
        return view('orders.process');
    }
    
    /**
     * Procesa un pedido de Amazon
     */
    public function processOrder(Request $request)
    {
        $request->validate([
            'amazon_order_id' => 'required|string',
        ]);
        
        $amazonOrderId = $request->input('amazon_order_id');
        
        try {
            // NOTA: Ya que no tenemos las APIs reales, usamos datos de demostración
            // En producción, se usaría el siguiente código:
            
            // 1. Obtener datos del pedido desde Amazon
            // $orderData = $this->amazonService->getOrderData($amazonOrderId);
            // if (!$orderData) {
            //     return response()->json(['error' => 'No se pudo obtener el pedido de Amazon'], 404);
            // }
            
            // Simulación para desarrollo
            $orderData = [
                'customer' => [
                    'name' => 'Cliente Demo',
                    'email' => 'cliente@ejemplo.com',
                    'address' => 'Calle Ejemplo 123, Ciudad Demo',
                ],
                'items' => [
                    [
                        'sku' => 'PROD-001',
                        'quantity' => 2,
                        'price' => 29.99,
                    ],
                    [
                        'sku' => 'PROD-002',
                        'quantity' => 1,
                        'price' => 49.99,
                    ],
                ],
            ];

            // 2. Crear o actualizar cliente
            $customer = Customer::updateOrCreate(
                ['email' => $orderData['customer']['email']],
                [
                    'name' => $orderData['customer']['name'],
                    'address' => $orderData['customer']['address'],
                ]
            );

            // 3. Crear pedido
            $order = Order::create([
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

            // 5. Simulación de envío a BigBuy
            // En producción: $bigBuyResponse = $this->bigBuyService->sendOrder([...]);
            $bigBuyResponse = [
                'success' => true,
                'order_id' => 'BB-' . rand(10000, 99999),
                'tracking_number' => 'TR' . rand(100000, 999999) . 'BB',
            ];

            // 6. Actualizar pedido con tracking
            if (isset($bigBuyResponse['tracking_number'])) {
                $order->update([
                    'status' => 'procesando',
                    'tracking_number' => $bigBuyResponse['tracking_number'],
                ]);
                
                // Registrar en el log
                $order->logs()->create([
                    'description' => "Pedido procesado automáticamente. Tracking: {$bigBuyResponse['tracking_number']}",
                    'user_id' => auth()->id(),
                ]);
                
                // En producción: $this->amazonService->updateOrderStatus($amazonOrderId, 'procesando', $bigBuyResponse['tracking_number']);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Pedido procesado correctamente',
                'order_id' => $order->id,
                'redirect_url' => route('orders.show', $order->id),
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar el pedido: ' . $e->getMessage(),
            ], 500);
        }
    }
    
    /**
     * Crear pedidos de demostración para visualizar las interfaces
     * Solo para desarrollo
     */
    private function createDemoOrders()
    {
        // Crear algunos clientes de ejemplo
        $customers = [
            ['name' => 'María López', 'email' => 'maria@example.com', 'address' => 'Calle Mayor 123, Madrid', 'phone' => '612345678'],
            ['name' => 'Carlos Rodríguez', 'email' => 'carlos@example.com', 'address' => 'Avenida Diagonal 456, Barcelona', 'phone' => '623456789'],
            ['name' => 'Laura García', 'email' => 'laura@example.com', 'address' => 'Plaza Nueva 78, Sevilla', 'phone' => '634567890'],
            ['name' => 'Javier Martínez', 'email' => 'javier@example.com', 'address' => 'Calle Gran Vía 56, Valencia', 'phone' => '645678901'],
        ];
        
        foreach ($customers as $customerData) {
            $customer = Customer::updateOrCreate(
                ['email' => $customerData['email']],
                $customerData
            );
            
            // Crear entre 1 y 3 pedidos por cliente
            $orderCount = rand(1, 3);
            for ($i = 0; $i < $orderCount; $i++) {
                $status = ['pendiente', 'procesando', 'enviado', 'entregado', 'cancelado'][rand(0, 4)];
                
                $order = Order::create([
                    'amazon_order_id' => 'AMZ-' . strtoupper(substr(md5(rand()), 0, 8)),
                    'status' => $status,
                    'tracking_number' => in_array($status, ['enviado', 'entregado']) ? 'TRK' . rand(10000000, 99999999) : null,
                    'customer_id' => $customer->id,
                    'created_at' => now()->subDays(rand(0, 30))->subHours(rand(0, 24))
                ]);
                
                // Añadir entre 1 y 5 productos por pedido
                $itemCount = rand(1, 5);
                $products = [
                    ['name' => 'Smartphone X23', 'sku' => 'SP-X23', 'price' => 499.99],
                    ['name' => 'Tablet Pro 11', 'sku' => 'TB-P11', 'price' => 349.50],
                    ['name' => 'Auriculares Bluetooth', 'sku' => 'AUR-BT3', 'price' => 79.95],
                    ['name' => 'Smartwatch Fit', 'sku' => 'SW-FIT2', 'price' => 129.00],
                    ['name' => 'Cargador rápido USB-C', 'sku' => 'CRG-USBC', 'price' => 24.99],
                    ['name' => 'Funda protectora', 'sku' => 'ACC-FP1', 'price' => 19.95],
                    ['name' => 'Adaptador HDMI', 'sku' => 'ADP-HDMI', 'price' => 15.50],
                ];
                
                for ($j = 0; $j < $itemCount; $j++) {
                    $product = $products[rand(0, count($products) - 1)];
                    $order->items()->create([
                        'sku' => $product['sku'],
                        'name' => $product['name'],
                        'quantity' => rand(1, 3),
                        'price' => $product['price']
                    ]);
                }
                
                // Añadir logs/notas al pedido
                $order->logs()->create([
                    'description' => 'Pedido recibido de Amazon',
                    'created_at' => $order->created_at
                ]);
                
                if ($status != 'pendiente') {
                    $order->logs()->create([
                        'description' => 'Pedido en proceso de preparación',
                        'created_at' => $order->created_at->addHours(rand(1, 5))
                    ]);
                }
                
                if (in_array($status, ['enviado', 'entregado'])) {
                    $order->logs()->create([
                        'description' => 'Pedido enviado. Número de seguimiento: ' . $order->tracking_number,
                        'created_at' => $order->created_at->addHours(rand(6, 24))
                    ]);
                }
                
                if ($status == 'entregado') {
                    $order->logs()->create([
                        'description' => 'Pedido entregado al cliente',
                        'created_at' => $order->created_at->addDays(rand(1, 3))
                    ]);
                }
                
                if ($status == 'cancelado') {
                    $reasons = ['Cliente solicita cancelación', 'Sin stock disponible', 'Error en la dirección'];
                    $order->logs()->create([
                        'description' => 'Pedido cancelado. Motivo: ' . $reasons[rand(0, 2)],
                        'created_at' => $order->created_at->addHours(rand(1, 12))
                    ]);
                }
            }
        }
    }
}
