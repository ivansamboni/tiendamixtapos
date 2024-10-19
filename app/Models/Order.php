<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = ['nombres', 'apellidos', 'cedula', 'email', 'telefono', 
    'departamento', 'ciudad','direccion', 'comprobante_pago','total','estado'];

    public function details()
    {
        return $this->hasMany(Order_detail::class);
    }
}
