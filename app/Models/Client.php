<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;
     protected $fillable = ['tipoidentificacion', 'numidentificacion', 'nombres', 'apellidos', 'telefono', 'email', 'ubicacion'];
     public function sales()
     {
         return $this->hasMany(Sale::class);
     }

}
