<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Seller extends Model
{    
    
    use HasFactory;
     protected $fillable = ['tipoidentificacion', 'numidentificacion', 'nombres', 'apellidos', 'telefono', 'email', 'ubicacion'];

    public function producto(): HasMany
    {
        return $this->hasMany(Producto::class);
    }


}
