<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Mostrar un listado de productos.
     */
    public function index(Request $request)
    {
        $query = Product::query();
        
        // Filtrar por categoría
        if ($request->has('category') && $request->category != 'all') {
            $query->where('category', $request->category);
        }
        
        // Filtrar por estado de sincronización
        if ($request->has('sync_status') && $request->sync_status != 'all') {
            // Como sync_status es un atributo calculado, filtramos manualmente
            if ($request->sync_status === 'sincronizado') {
                $query->whereRaw('ABS(amazon_price - bigbuy_price) < 0.01')
                      ->whereRaw('amazon_stock = bigbuy_stock');
            } elseif ($request->sync_status === 'precio_desincronizado') {
                $query->whereRaw('ABS(amazon_price - bigbuy_price) >= 0.01')
                      ->whereRaw('amazon_stock = bigbuy_stock');
            } elseif ($request->sync_status === 'stock_desincronizado') {
                $query->whereRaw('ABS(amazon_price - bigbuy_price) < 0.01')
                      ->whereRaw('amazon_stock != bigbuy_stock');
            } elseif ($request->sync_status === 'desincronizado') {
                $query->whereRaw('ABS(amazon_price - bigbuy_price) >= 0.01')
                      ->whereRaw('amazon_stock != bigbuy_stock');
            }
        }
        
        // Búsqueda por nombre, SKU o ASIN
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('amazon_asin', 'like', "%{$search}%")
                  ->orWhere('bigbuy_id', 'like', "%{$search}%");
            });
        }
        
        // Ordenar resultados
        $sort_by = $request->sort_by ?? 'name';
        $sort_dir = $request->sort_dir ?? 'asc';
        
        if (in_array($sort_by, ['name', 'price', 'stock', 'created_at'])) {
            $query->orderBy($sort_by, $sort_dir);
        }
        
        // Para demostración, creamos algunos productos de ejemplo si no hay ninguno
        $this->createDemoProductsIfEmpty();
        
        $products = $query->paginate(10);
        
        // Obtener categorías únicas para el filtro
        $categories = Product::select('category')->distinct()->pluck('category');
        
        return view('products.index', compact('products', 'categories'));
    }

    /**
     * Mostrar el formulario para crear un producto nuevo.
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Almacenar un producto recién creado.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'sku' => 'required|string|max:100|unique:products',
            'amazon_asin' => 'nullable|string|max:100',
            'bigbuy_id' => 'nullable|string|max:100',
            'price' => 'required|numeric|min:0',
            'amazon_price' => 'required|numeric|min:0',
            'bigbuy_price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'amazon_stock' => 'required|integer|min:0',
            'bigbuy_stock' => 'required|integer|min:0',
            'status' => 'required|in:active,inactive,draft',
            'category' => 'required|string|max:100',
            'image_url' => 'nullable|url|max:255',
            'weight' => 'nullable|numeric|min:0',
            'dimensions' => 'nullable|array',
        ]);
        
        $product = Product::create($validated);
        
        return redirect()->route('products.show', $product)
                         ->with('success', 'Producto creado correctamente.');
    }

    /**
     * Mostrar los detalles de un producto específico.
     */
    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    /**
     * Mostrar el formulario para editar un producto.
     */
    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    /**
     * Actualizar un producto específico.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'sku' => 'required|string|max:100|unique:products,sku,' . $product->id,
            'amazon_asin' => 'nullable|string|max:100',
            'bigbuy_id' => 'nullable|string|max:100',
            'price' => 'required|numeric|min:0',
            'amazon_price' => 'required|numeric|min:0',
            'bigbuy_price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'amazon_stock' => 'required|integer|min:0',
            'bigbuy_stock' => 'required|integer|min:0',
            'status' => 'required|in:active,inactive,draft',
            'category' => 'required|string|max:100',
            'image_url' => 'nullable|url|max:255',
            'weight' => 'nullable|numeric|min:0',
            'dimensions' => 'nullable|array',
        ]);
        
        $product->update($validated);
        
        return redirect()->route('products.show', $product)
                         ->with('success', 'Producto actualizado correctamente.');
    }

    /**
     * Sincronizar un producto con Amazon o BigBuy.
     */
    public function sync(Request $request, Product $product)
    {
        $platform = $request->platform;
        $type = $request->type; // price, stock o both
        
        // Aquí simularíamos la sincronización con la API correspondiente
        // Para fines de demostración, simplemente actualizamos los valores
        
        if ($platform === 'amazon') {
            if ($type === 'price' || $type === 'both') {
                $product->amazon_price = $product->bigbuy_price;
            }
            if ($type === 'stock' || $type === 'both') {
                $product->amazon_stock = $product->bigbuy_stock;
            }
        } elseif ($platform === 'bigbuy') {
            if ($type === 'price' || $type === 'both') {
                $product->bigbuy_price = $product->amazon_price;
            }
            if ($type === 'stock' || $type === 'both') {
                $product->bigbuy_stock = $product->amazon_stock;
            }
        }
        
        $product->save();
        
        return redirect()->back()->with('success', "Producto sincronizado con {$platform} correctamente.");
    }

    /**
     * Eliminar un producto específico.
     */
    public function destroy(Product $product)
    {
        $product->delete();
        
        return redirect()->route('products.index')
                         ->with('success', 'Producto eliminado correctamente.');
    }
    
    /**
     * Crear productos de demostración para pruebas si no hay ninguno en la base de datos.
     */
    private function createDemoProductsIfEmpty()
    {
        if (Product::count() > 0) {
            return;
        }
        
        $categories = ['Electrónica', 'Hogar', 'Ropa', 'Deportes', 'Juguetes', 'Libros'];
        $statuses = ['active', 'inactive', 'draft'];
        
        $products = [
            [
                'name' => 'Auriculares Bluetooth',
                'description' => 'Auriculares inalámbricos con cancelación de ruido',
                'category' => 'Electrónica',
                'image_url' => 'https://dummyimage.com/600x400/3d6eea/ffffff&text=Auriculares',
            ],
            [
                'name' => 'Teclado Mecánico RGB',
                'description' => 'Teclado gaming con interruptores mecánicos y retroiluminación RGB',
                'category' => 'Electrónica',
                'image_url' => 'https://dummyimage.com/600x400/6e47ef/ffffff&text=Teclado',
            ],
            [
                'name' => 'Smart TV 4K 55"',
                'description' => 'Televisor LED 4K con Android TV y asistente de voz',
                'category' => 'Electrónica',
                'image_url' => 'https://dummyimage.com/600x400/42b883/ffffff&text=TV',
            ],
            [
                'name' => 'Robot Aspirador',
                'description' => 'Robot aspirador inteligente con mapeo y control por app',
                'category' => 'Hogar',
                'image_url' => 'https://dummyimage.com/600x400/3d6eea/ffffff&text=Aspirador',
            ],
            [
                'name' => 'Zapatillas Running',
                'description' => 'Zapatillas deportivas con amortiguación y transpirables',
                'category' => 'Deportes',
                'image_url' => 'https://dummyimage.com/600x400/6e47ef/ffffff&text=Zapatillas',
            ],
            [
                'name' => 'Set de Sartenes Antiadherentes',
                'description' => 'Juego de 3 sartenes con recubrimiento antiadherente',
                'category' => 'Hogar',
                'image_url' => 'https://dummyimage.com/600x400/42b883/ffffff&text=Sartenes',
            ],
            [
                'name' => 'Chaqueta Impermeable',
                'description' => 'Chaqueta ligera con membrana impermeable y transpirable',
                'category' => 'Ropa',
                'image_url' => 'https://dummyimage.com/600x400/3d6eea/ffffff&text=Chaqueta',
            ],
            [
                'name' => 'Juego de Mesa Estrategia',
                'description' => 'Juego de estrategia para 2-4 jugadores a partir de 12 años',
                'category' => 'Juguetes',
                'image_url' => 'https://dummyimage.com/600x400/6e47ef/ffffff&text=Juego',
            ],
            [
                'name' => 'Novela Histórica',
                'description' => 'Bestseller ambientado en la Europa del siglo XVI',
                'category' => 'Libros',
                'image_url' => 'https://dummyimage.com/600x400/42b883/ffffff&text=Libro',
            ],
            [
                'name' => 'Mochila Portátil',
                'description' => 'Mochila con compartimento acolchado para portátil y múltiples bolsillos',
                'category' => 'Ropa',
                'image_url' => 'https://dummyimage.com/600x400/3d6eea/ffffff&text=Mochila',
            ],
        ];
        
        foreach ($products as $product_data) {
            $price = rand(1000, 50000) / 100;
            $amazon_price_diff = (rand(-500, 500) / 100);
            $stock = rand(5, 100);
            $stock_diff = rand(-10, 10);
            
            Product::create([
                'name' => $product_data['name'],
                'description' => $product_data['description'],
                'sku' => 'SKU-' . strtoupper(substr(md5(uniqid()), 0, 8)),
                'amazon_asin' => 'B' . strtoupper(substr(md5(uniqid()), 0, 9)),
                'bigbuy_id' => 'BB-' . strtoupper(substr(md5(uniqid()), 0, 6)),
                'price' => $price,
                'amazon_price' => $price + $amazon_price_diff,
                'bigbuy_price' => $price,
                'stock' => $stock,
                'amazon_stock' => $stock + $stock_diff,
                'bigbuy_stock' => $stock,
                'status' => $statuses[array_rand($statuses)],
                'category' => $product_data['category'],
                'image_url' => $product_data['image_url'],
                'weight' => rand(50, 5000) / 100,
                'dimensions' => [
                    'width' => rand(5, 100),
                    'height' => rand(5, 100),
                    'depth' => rand(5, 100),
                ],
            ]);
        }
    }
}
