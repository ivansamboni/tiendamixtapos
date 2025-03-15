<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase_detail extends Model
{
    
    protected $fillable = ['purchase_id', 'producto_id', 'cantidad', 
    'precio_unitario','iva'];
    public function purchase()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }


}
