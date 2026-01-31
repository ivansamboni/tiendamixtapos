<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\PurchaseOrder;
use App\Models\Expense;
use App\Models\Credit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BalanceController extends Controller
{
    public function balance(Request $request)
    {
        $request->validate([
            'fechaini' => 'required|date',
            'fechafin' => 'required|date|after_or_equal:fechaini',
        ]);

        $fechaini = Carbon::parse($request->input('fechaini'))->startOfDay();
        $fechafin = Carbon::parse($request->input('fechafin'))->endOfDay();

        // Mes
        $ventasMes = Sale::whereBetween('created_at', [$fechaini, $fechafin])
            ->with('details.producto')
            ->get()
            ->flatMap(fn($sale) => $sale->details->map(fn($detail) => [
                'venta' => $detail->precio_unitario * $detail->cantidad,
                'costo' => $detail->producto->precio_compra * $detail->cantidad,
            ]));

        $totalVentasMes = $ventasMes->sum('venta');
        $totalCostoMes = $ventasMes->sum('costo');
        $gananciaMes = $totalVentasMes - $totalCostoMes;

        // ========================
        // 2. Compras y Gastos
        // ========================
        $comprasMes = PurchaseOrder::where('tipo_compra', 'Costo')
            ->whereBetween('created_at', [$fechaini, $fechafin])
            ->sum('total');

        $gastosMes = Expense::whereBetween('created_at', [$fechaini, $fechafin])
            ->sum('monto');

        // ========================
        // 3. Balance final (Ganancia – Gastos)
        // ========================
        $balanceMes = $gananciaMes - $gastosMes;
        $totalCreditosPendientes = Credit::whereBetween('created_at', [$fechaini, $fechafin])
            ->sum('saldo');

        $balanceCajaMes = $balanceMes - $totalCreditosPendientes;

        return response()->json([
            'ventasMes' => round($totalVentasMes, 2),
            'costoMes' => round($totalCostoMes, 2),
            'gananciaMes' => round($gananciaMes, 2),
            'creditosPendientes' => round($totalCreditosPendientes, 2),
            'comprasMes' => round($comprasMes, 2), // inversión en stock
            'gastosMes' => round($gastosMes, 2),   // gastos operativos
            'balanceMes' => round($balanceMes, 2), // ganancia neta después de gastos
            'balanceCajaMes' => round($balanceCajaMes, 2),
        ]);
    }
}
