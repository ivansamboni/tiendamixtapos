<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Categoria extends Model
{
    use HasFactory;
    protected $fillable = ['nombre', 'descripcion'];

    public function producto(): HasMany
    {
        return $this->hasMany(Producto::class);
    }

    protected static function booted()
    {
        static::creating(function ($categoria) {
            $categoria->slug = Str::slug($categoria->nombre);  // Generar slug al crear
        });

        static::updating(function ($categoria) {
            $categoria->slug = Str::slug($categoria->nombre);  // Actualizar slug si cambia el nombre
        });
    }

}
