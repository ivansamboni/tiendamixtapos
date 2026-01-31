<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Producto;

class AjustarProductosConIVA extends Command
{
    protected $signature = 'actualizar:productos-ajuste';
    protected $description = 'Ajusta precio_venta de productos con redondeo e impuestos incluidos';

    public function handle()
    {
        $productos = Producto::with('iva', 'ibua', 'ipc')->get();
        $this->info("Total productos a procesar: " . $productos->count());

        foreach ($productos as $producto) {
            $precioBase = floatval($producto->precio_compra ?? 0);
            $ventaActual = floatval($producto->precio_venta ?? 0);

            if ($precioBase == 0 || $ventaActual == 0) {
                $this->warn("Producto ID {$producto->id} tiene valores nulos, se omite.");
                continue;
            }

            // Porcentajes
            $iva = $producto->iva?->valor ?? 0;
            $ibua = $producto->ibua?->valor ?? 0;
            $ipc = $producto->ipc?->valor ?? 0;

            $iva /= 100;
            $ibua /= 100;
            $ipc /= 100;
            $totalImpuesto = $iva + $ibua + $ipc;

            if ($totalImpuesto == -1) continue;

            // Redondeo
            $precioConImpuesto = $ventaActual * (1 + $totalImpuesto);

            $precioRedondeado = $precioConImpuesto < 100
                ? round($precioConImpuesto, 2)
                : round($precioConImpuesto / 100) * 100;

            $nuevoPrecioBase = $precioRedondeado / (1 + $totalImpuesto);

            $producto->precio_venta = round($nuevoPrecioBase, 2);
            $producto->precio_final = $precioRedondeado;
            $producto->ganancia = $producto->precio_venta - $precioBase;

            $this->info("Actualizando producto ID {$producto->id} -> nuevo precio: {$producto->precio_venta}, final: {$producto->precio_final}");

            $producto->save();
        }

        $this->info("✅ Todos los productos han sido actualizados correctamente.");
    }
}
