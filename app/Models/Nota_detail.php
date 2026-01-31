<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nota_detail extends Model
{
    protected $fillable = ['nota_compra_id', 'producto_id', 'cantidad', 
    'precio_unitario','iva','ibua','ipc','tax_rate'];
    public function nota()
    {
        return $this->belongsTo(NotasCompra::class, 'nota_compra_id');
    }
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }  

    
}
