<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory, SoftDeletes;
     protected $fillable = ['tipoidentificacion', 'numidentificacion', 'nombres', 'apellidos', 'telefono', 'email', 'ubicacion'];
     protected $dates = ['deleted_at']; 
     public function sales()
     {
         return $this->hasMany(Sale::class);
     }

}
