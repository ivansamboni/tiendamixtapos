<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    protected $fillable = ['nombre','descripcion'];
    use HasFactory;

    public function producto(): HasMany
    {
        return $this->hasMany(Producto::class);
    }

}
