<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    protected $fillable = [
        'factura_numero',
        'user_id',
        'seller_id',
        'tipo_pago',
        'order_date',
        'tipo _pago',
        'status',
        'total'
    ];

    public function purchasedetails()
    {
        return $this->hasMany(Purchase_detail::class, 'purchase_id');
    }
    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class, 'seller_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
