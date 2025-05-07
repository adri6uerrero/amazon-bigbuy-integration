<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Order;

class Customer extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'email',
        'address',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
