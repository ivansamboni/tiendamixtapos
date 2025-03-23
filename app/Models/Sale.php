<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Str;


class Sale extends Model
{
    use HasFactory;
  
    protected $fillable = ['cliente_id', 'user_id', 'tipo_pago', 'impuesto', 'total'];

    public function details()
    {
        return $this->hasMany(Sale_detail::class);
    }
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'cliente_id')->withTrashed();
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id')->withTrashed();
    }

    protected static function boot()
{
    parent::boot();
    static::creating(function ($sale) {
        if (!$sale->uuid) {
            $sale->uuid = Str::uuid();
        }
    });
}
}
