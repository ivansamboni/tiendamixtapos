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
    public function gananciasfecha()
    {
        $dia = Sale::whereDate('created_at', today())
            ->with('details.producto')
            ->get()
            ->flatMap(function ($sale) {
                return $sale->details->map(function ($detail) {
                    return round($detail->producto->ganancia * $detail->cantidad, 2);
                });
            })->sum();
    
        $gananciasPorDia = Sale::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->with('details.producto')
            ->get()
            ->flatMap(fn($sale) => $sale->details->map(fn($detail) => [
                'dia' => $sale->created_at->format('d-m-Y'),
                'ganancia' => round($detail->producto->ganancia * $detail->cantidad, 2)
            ]))
            ->groupBy('dia')
            ->map(fn($ventas) => round(collect($ventas)->sum('ganancia'), 2))
            ->toArray();
    
        $gananciasPorDiaMes = Sale::whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->with('details.producto')
            ->get()
            ->flatMap(fn($sale) => $sale->details->map(fn($detail) => [
                'dia' => $sale->created_at->format('d-m-Y'),
                'ganancia' => round($detail->producto->ganancia * $detail->cantidad, 2)
            ]))
            ->groupBy('dia')
            ->map(fn($ventas) => round(collect($ventas)->sum('ganancia'), 2))
            ->toArray();
    
        $semana = Sale::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->with('details.producto')
            ->get()
            ->flatMap(fn($sale) => $sale->details->map(fn($detail) => round($detail->producto->ganancia * $detail->cantidad, 2)))
            ->sum();
    
        $mes = Sale::whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->with('details.producto')
            ->get()
            ->flatMap(fn($sale) => $sale->details->map(fn($detail) => round($detail->producto->ganancia * $detail->cantidad, 2)))
            ->sum();
    
        $topProductos = Sale_detail::select('producto_id', DB::raw('SUM(cantidad) as total_vendido'))
            ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->groupBy('producto_id')
            ->orderByDesc('total_vendido')
            ->with('producto')
            ->limit(15)
            ->get();
    
        $topStockBajo = Producto::where('stock', '<', '10')
            ->orderBy('stock', 'asc')->limit(15)->get();
    
        return response()->json([
            'gananciaHoy' => round($dia, 2),
            'gananciaSemana' => round($semana, 2),
            'gananciaMes' => round($mes, 2),
            'masVendido' => $topProductos,
            'gananciasPorDia' => $gananciasPorDia,
            'gananciasPorDiaMes' => $gananciasPorDiaMes,
            'topStockBajo' => $topStockBajo
        ]);       

    }

    public function ventasfecha()
    {
        $ventaHoy = Sale::whereDate('created_at', today())->sum('total');
    
        $ventaSemana = Sale::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->sum('total');
    
        $ventaMes = Sale::whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->sum('total');
    
     
            $ventasPorDia = Sale::selectRaw("DATE_FORMAT(created_at, '%d-%m-%Y') as fecha, SUM(total) as total")
            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->groupBy('fecha')
            ->orderBy('fecha')
            ->get()
            ->pluck('total', 'fecha');
        
        $ventasPorDiaMes = Sale::selectRaw("DATE_FORMAT(created_at, '%d-%m-%Y') as fecha, SUM(total) as total")
            ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->groupBy('fecha')
            ->orderBy('fecha')
            ->get()
            ->pluck('total', 'fecha');
    
        return response()->json([
            'ventaHoy' => $ventaHoy,
            'ventaSemana' => $ventaSemana,
            'ventaMes' => $ventaMes,
            'ventasPorDia' => $ventasPorDia,
            'ventasPorDiaMes' => $ventasPorDiaMes
        ]);
    }
    

}
