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
        'tipo_compra',
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
    public function notas()
    {
        return $this->hasMany(NotasCompra::class);
    }

    public function getTotalConNotasAttribute()
    {
        $credito = $this->notas()->where('tipo', 'credito')->sum('monto');
        $debito = $this->notas()->where('tipo', 'debito')->sum('monto');

        return $this->total + $debito - $credito;
    }

    public function expense()
    {
        return $this->hasOne(Expense::class, 'purchase_order_id');
    }

}
