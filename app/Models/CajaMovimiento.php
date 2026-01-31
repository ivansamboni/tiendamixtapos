<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CajaMovimiento extends Model
{
    protected $fillable = [
        'user_id',
        'caja_id',
        'tipo',
        'monto',
        'descripcion',
        'referencia'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id')->withTrashed();
    }
    public function caja() {
        return $this->belongsTo(Caja::class, 'caja_id');
    }
}
