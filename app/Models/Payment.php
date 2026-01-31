<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = ['caja_id','credit_id','metodo_pago', 'monto', 'fecha_abono', 'observaciones'];

    public function credito()
    {
        return $this->belongsTo(Credit::class);
    }
    
}
