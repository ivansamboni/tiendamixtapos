<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = [
        'user_id',
        'purchase_order_id',
        'tipo_gasto',
        'monto',
        'fecha',
        'descripcion',
    ];
    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
