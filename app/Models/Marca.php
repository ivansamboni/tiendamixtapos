<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
class Marca extends Model
{
    protected $fillable = ['nombre','descripcion'];
    use HasFactory;

    public function producto(): HasMany
    {
        return $this->hasMany(Producto::class);
    }
   
    protected static function booted()
        {
            
        }
}
