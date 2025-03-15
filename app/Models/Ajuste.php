<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Ajuste extends Model
{
    protected $fillable = [
        'factura_numero',
        'user_id',            
        'order_date',        
        'status',
        'descripcion'
    ];

    public function ajustedetails()
    {
        return $this->hasMany(Ajuste_detail::class, 'ajuste_id');
    }    

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
