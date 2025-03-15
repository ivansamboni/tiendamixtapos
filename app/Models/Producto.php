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
        'precio_venta',
         'precio_compra',
         'ganancia',
        'stock',
        'stock_minimo',
        'iva',
        'ibua',
        'codigo_barras',
        'proveedor_id',
        'img1',        
    ];
    public function categoria()
    {
        return $this->belongsTo(Category::class, 'categoria_id');
    }
       public function marca(): BelongsTo
    {
        return $this->belongsTo(Marca::class);
    }
    public function proveedor(): BelongsTo
    {
        return $this->belongsTo(Seller::class);
    }

    public function details()
    {
        return $this->hasMany(Sale_detail::class);
    }

    public function purchasedetails()
    {
        return $this->hasMany(Purchase_detail::class);
    }
    public function ajustedetails()
    {
        return $this->hasMany(Ajuste_detail::class);
    }
    protected static function booted()
    {
                
    }
}


