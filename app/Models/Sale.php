<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use App\Constants\PaymentConstants;
use Str;


class Sale extends Model
{
    use HasFactory;
    protected $appends = [
        'forma_pago_nombre',
        'metodo_pago_nombre',
    ];
    protected $fillable = ['factura_numero', 'cliente_id', 'caja_id', 'user_id', 'forma_pago', 'metodo_pago', 'impuesto', 'total'];

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
    public function caja()
    {
        return $this->belongsTo(Caja::class, 'caja_id');
    }
    public function credit()
    {
        return $this->hasOne(Credit::class);
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

    public function getFormaPagoNombreAttribute()
    {
        return PaymentConstants::FORMAS_PAGO[$this->forma_pago] ?? 'No definido';
    }

    public function getMetodoPagoNombreAttribute()
    {
        return PaymentConstants::METODOS_PAGO[$this->metodo_pago] ?? null;
    }
}
