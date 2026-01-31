<?php

namespace App\Models;

use App\Constants\PaymentConstants;
use App\Constants\CodesDianConstants;
use Illuminate\Database\Eloquent\Model;

class NotasCompra extends Model
{
    protected $appends = [
        'metodo_pago_nombre',
        'correcion_concepto_nombre'
    ];

    protected $fillable = [
        'referencia_proveedor',
        'numero_nota',
        'fecha_emision',
        'tipo',
        'purchase_id',
        'seller_id',
        'motivo',
        'total',
        'payment_method_code',
        'numbering_range_id',
        'correction_concept_code',
        'bill_id_factus',
        'uuid_factus',
        'xml_url',
        'pdf_url',
    ];

    public function purchase()
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function notadetails()
    {
        return $this->hasMany(Nota_detail::class, 'nota_compra_id');
    }



    public function getMetodoPagoNombreAttribute()
    {
        return PaymentConstants::METODOS_PAGO[$this->payment_method_code] ?? null;
    }

    public function getCorrecionConceptoNombreAttribute()
    {
        return CodesDianConstants::MOTIVOS_NOTA[$this->correction_concept_code] ?? null;
    }
}
