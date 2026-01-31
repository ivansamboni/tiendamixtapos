<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Credit extends Model
{
    protected $fillable = ['sale_id', 'total_credito', 'saldo', 'fecha_limite'];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function registrarAbono($monto, $observaciones = null)
    {
        $this->payments()->create([
            'monto' => $monto,
            'fecha_abono' => now(),
            'observaciones' => $observaciones,
        ]);

        $this->saldo -= $monto;
        $this->save();
    }

}
