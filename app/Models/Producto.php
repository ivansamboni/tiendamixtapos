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
        'cantidad',
        'unidad_medida',
        'descripcion',
        'marca_id',
        'categoria_id',
        'subcategoria_id',
        'precio_venta',
        'precio_compra',
        'precio_final',
        'ganancia',
        'stock',
        'stock_minimo',
        'iva_id',
        'ibua_id',
        'ipc_id',
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

    public function notadetails()
    {
        return $this->hasMany(Nota_detail::class);
    }
    public function iva()
    {
        return $this->belongsTo(Impuesto::class, 'iva_id');
    }

    public function ibua()
    {
        return $this->belongsTo(Impuesto::class, 'ibua_id');
    }

    public function ipc()
    {
        return $this->belongsTo(Impuesto::class, 'ipc_id');
    }
    protected static function booted()
    {

    }
}


