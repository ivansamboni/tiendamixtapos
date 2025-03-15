<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ajuste_detail extends Model
{
    protected $fillable = ['ajuste_id', 'producto_id', 'stock_cambio'];
    public function ajuste()
    {
        return $this->belongsTo(Ajuste::class);
    }
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
