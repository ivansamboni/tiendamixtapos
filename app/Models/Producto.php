<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Producto extends Model
{
    use HasFactory;
    protected $fillable = [
        'nombre',
        'descripcion',
        'marca_id',
        'categoria_id',
        'subcategoria_id',
        'precio',
        'stock',
        'codigo_barras',
        'proveedor_id',
        'img1',
        'img2',
        'img3',
        'img4',
    ];
    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }
       public function marca(): BelongsTo
    {
        return $this->belongsTo(Marca::class);
    }
    public function proveedor(): BelongsTo
    {
        return $this->belongsTo(Proveedor::class);
    }

    protected static function booted()
    {
        static::creating(function ($producto) {
            $producto->slug = Str::slug($producto->nombre);  // Generar slug al crear
        });

        static::updating(function ($producto) {
            $producto->slug = Str::slug($producto->nombre);  // Actualizar slug si cambia el nombre
        });
    }
}


