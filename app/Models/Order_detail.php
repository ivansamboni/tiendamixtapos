<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order_detail extends Model
{
    use HasFactory;
    protected $fillable = ['orden_id', 'producto_id', 'cantidad', 'precio_unitario','subtotal'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Relación: Un detalle tiene un producto
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
