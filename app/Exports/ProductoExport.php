<?php

namespace App\Exports;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use App\Models\Producto;
use Maatwebsite\Excel\Concerns\FromCollection;

class ProductoExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Producto::all();
    }

    public function headings(): array
    {
        return [            
            'codigo_barras',           
            'nombre',
            'cantidad',
            'unidad_medida',           
            'precio_compra',
            'precio_venta',
            'stock',
            'stock_minimo',           
        ];
    }

    public function map($producto): array
    {
        return [
            $producto->codigo_barras,
            $producto->nombre,
            $producto->cantidad ?? '',
            $producto->unidad_medida ?? '',           
            $producto->precio_compra ?? '',
            $producto->precio_venta ?? '',
            $producto->stock ?? '',
            $producto->stock_minimo ?? '',           
        ];
    }
}

