<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Caja extends Model
{
    protected $fillable = [
        'user_id',
        'caja_numero',
        'monto_inicial',
        'monto_final',
        'observaciones'
    ];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id')->withTrashed();
    }
    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class, 'caja_id');
    }
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'caja_id');
    }

    public function movimientos(): HasMany
    {
        return $this->hasMany(CajaMovimiento::class, 'caja_id');
    }
    protected static function boot()
    {
        parent::boot();

        static::updating(function ($caja) {
            // Evita que se actualice la apertura
            $caja->apertura = $caja->getOriginal('apertura');
        });
    }
}
