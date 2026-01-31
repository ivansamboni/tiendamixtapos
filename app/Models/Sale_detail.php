<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale_detail extends Model
{
    use HasFactory;
    protected $fillable = ['sale_id', 'producto_id', 'cantidad', 'precio_unitario','iva','ibua','ipc'];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
    // Relación: Un detalle tiene un producto
  
}
