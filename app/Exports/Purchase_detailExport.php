<?php

namespace App\Exports;

use App\Models\Purchase_detail;
use Maatwebsite\Excel\Concerns\FromCollection;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class Purchase_detailExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    protected $fechaini;
    protected $fechafin;

    public function __construct($fechaini, $fechafin)
    {
        $this->fechaini = Carbon::parse($fechaini)->startOfDay();
        $this->fechafin = Carbon::parse($fechafin)->endOfDay();
    }
    public function collection()
    {
        return Purchase_detail::with(['producto', 'purchase',])
            ->whereHas('purchase', function ($query) {
                $query->whereBetween('created_at', [$this->fechaini, $this->fechafin]);
            })
            ->get();
    }

    public function headings(): array
    {
        return [
            'Factura',
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
            $detalle->purchase->factura_numero,
            $detalle->producto->nombre ?? 'Sin nombre',
            $detalle->cantidad,
            number_format($detalle->precio_unitario, 2),
            number_format($detalle->iva ?? 0, 2),
            number_format($detalle->ibua ?? 0, 2),
            number_format($detalle->ipc ?? 0, 2),
            optional($detalle->purchase->created_at)->format('d/m/Y'),
        ];
    }

}
