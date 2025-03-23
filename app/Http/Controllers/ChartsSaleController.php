<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use DB;
use App\Models\Sale;
use App\Models\Producto;
use App\Models\Sale_detail;
use Illuminate\Http\Request;


class ChartsSaleController extends Controller
{
    public function ventasStats()
    {

        $dia = Sale::whereDate('created_at', today())
            ->with('details.producto') // Carga la relación
            ->get()
            ->flatMap(function ($sale) {
                return $sale->details->map(function ($detail) {
                    return $detail->producto->ganancia * $detail->cantidad;
                });
            })->sum();

        $gananciasPorDia = Sale::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->with('details.producto')
            ->get()
            ->flatMap(fn($sale) => $sale->details->map(fn($detail) => [
                'dia' => $sale->created_at->format('d-m-Y'),
                'ganancia' => $detail->producto->ganancia * $detail->cantidad
            ]))
            ->groupBy('dia')
            ->map(fn($ventas) => $ventas->sum('ganancia'))
            ->toArray();

        $gananciasPorDiaMes = Sale::whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->with('details.producto')
            ->get()
            ->flatMap(fn($sale) => $sale->details->map(fn($detail) => [
                'dia' => $sale->created_at->format('d-m-Y'),
                'ganancia' => $detail->producto->ganancia * $detail->cantidad
            ]))
            ->groupBy('dia')
            ->map(fn($ventas) => $ventas->sum('ganancia'))
            ->toArray();

        $semana = Sale::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->with('details.producto')
            ->get()
            ->flatMap(fn($sale) => $sale->details->map(fn($detail) => $detail->producto->ganancia * $detail->cantidad))
            ->sum();

        $mes = Sale::whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->with('details.producto')
            ->get()
            ->flatMap(fn($sale) => $sale->details->map(fn($detail) => $detail->producto->ganancia * $detail->cantidad))
            ->sum();

        $topProductos = Sale_detail::select('producto_id', DB::raw('SUM(cantidad) as total_vendido'))
            ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->groupBy('producto_id') // Agrupa por producto
            ->orderByDesc('total_vendido') // Ordena de mayor a menor
            ->with('producto') // Carga la relación del producto
            ->limit(15) // Limita a los 10 más vendidos
            ->get();

       
            $topStockBajo = Producto::where('stock', '<', '10')
            ->orderBy('stock', 'asc')->limit(15)->get();

        return response()->json([
            'gananciaHoy' => $dia,
            'gananciaSemana' => $semana,
            'gananciaMes' => $mes,
            'masVendido' => $topProductos,
            'gananciasPorDia' => $gananciasPorDia,
            'gananciasPorDiaMes' => $gananciasPorDiaMes,
            'topStockBajo' => $topStockBajo
        ]);

    }

   
}
