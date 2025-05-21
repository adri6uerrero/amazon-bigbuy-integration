<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'description',
        'sku',
        'amazon_asin',
        'bigbuy_id',
        'price',
        'amazon_price',
        'bigbuy_price',
        'stock',
        'amazon_stock',
        'bigbuy_stock',
        'status',
        'category',
        'image_url',
        'weight',
        'dimensions'
    ];
    
    /**
     * Los atributos que deben convertirse a tipos nativos.
     *
     * @var array
     */
    protected $casts = [
        'price' => 'float',
        'amazon_price' => 'float',
        'bigbuy_price' => 'float',
        'stock' => 'integer',
        'amazon_stock' => 'integer',
        'bigbuy_stock' => 'integer',
        'dimensions' => 'array',
    ];
    
    /**
     * Obtener órdenes relacionadas con este producto.
     */
    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_items')
                    ->withPivot('quantity', 'price')
                    ->withTimestamps();
    }
    
    /**
     * Obtener el estado de sincronización entre Amazon y BigBuy
     */
    public function getSyncStatusAttribute()
    {
        // Verificar precio
        $price_synced = abs($this->amazon_price - $this->bigbuy_price) < 0.01;
        
        // Verificar stock
        $stock_synced = $this->amazon_stock == $this->bigbuy_stock;
        
        if ($price_synced && $stock_synced) {
            return 'sincronizado';
        } elseif ($price_synced) {
            return 'stock_desincronizado';
        } elseif ($stock_synced) {
            return 'precio_desincronizado';
        } else {
            return 'desincronizado';
        }
    }
    
    /**
     * Obtener diferencia de precio entre Amazon y BigBuy
     */
    public function getPriceDifferenceAttribute()
    {
        return $this->amazon_price - $this->bigbuy_price;
    }
    
    /**
     * Obtener diferencia de stock entre Amazon y BigBuy
     */
    public function getStockDifferenceAttribute()
    {
        return $this->amazon_stock - $this->bigbuy_stock;
    }
    
    /**
     * Verificar si el producto está activo en ambas plataformas
     */
    public function getIsActiveAttribute()
    {
        return $this->status === 'active';
    }
}
