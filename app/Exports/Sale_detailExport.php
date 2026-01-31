<?php

namespace App\Exports;

use App\Models\Sale_detail;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class Sale_detailExport implements FromCollection, WithHeadings, WithMapping
{
    protected $fechaini;
    protected $fechafin;
    protected $id;

    public function __construct($fechaini = null, $fechafin = null, $id = null)
    {
        $this->fechaini = $fechaini ? Carbon::parse($fechaini)->startOfDay() : null;
        $this->fechafin = $fechafin ? Carbon::parse($fechafin)->endOfDay() : null;
        $this->id = $id;
    }

    public function collection()
    {
        return Sale_detail::with(['producto', 'sale'])
            ->whereHas('sale', function ($query) {
                if ($this->fechaini && $this->fechafin) {
                    $query->whereBetween('created_at', [$this->fechaini, $this->fechafin]);
                }

                if ($this->id) {
                    $query->where('id', $this->id);
                }
            })
            ->get();
    }

    public function headings(): array
    {
        return [
            'Factura No.',
            'Código',           
            'Producto',
            'Cantidad',
            'Precio Unitario',
            'IVA',
            'IBUA',
            'IMPOCONSUMO',
            'Fecha Venta',
        ];
    }

    public function map($detalle): array
    {
        return [
            optional($detalle->sale)->factura_numero ?? '',
            optional($detalle->producto)->codigo_barras ?? '',           
            optional($detalle->producto)->nombre ?? '',
            $detalle->cantidad,
            number_format($detalle->precio_unitario, 2),
            number_format($detalle->iva ?? 0, 2),
            number_format($detalle->ibua ?? 0, 2),
            number_format($detalle->ipc ?? 0, 2),
            optional($detalle->sale->created_at)->format('d/m/Y'),
        ];

    }
}

