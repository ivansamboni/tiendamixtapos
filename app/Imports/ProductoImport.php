<?php

namespace App\Imports;

use App\Models\Producto;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


class ProductoImport implements ToModel, WithHeadingRow
{

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

    public function model(array $row)
    {
        return new Producto([
            'codigo_barras'  => $row['codigo_barras'] ?? null,
            'nombre'         => $row['nombre'] ?? null,
            'cantidad'       => $row['cantidad'] ?? 0,
            'unidad_medida'  => $row['unidad_medida'] ?? '',
            'precio_compra'  => $row['precio_compra'] ?? 0,
            'precio_venta'   => $row['precio_venta'] ?? 0,
            'stock'          => $row['stock'] ?? 0,
            'stock_minimo'   => $row['stock_minimo'] ?? 0,
        ]);
    }
}
