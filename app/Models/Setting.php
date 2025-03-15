<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'nombre',
        'nit',
        'telefonos',
        'email',
        'direccion',
        'ciudad',
        'logotipo',   
        'iva', 
        'ibua',     
    ];
}
