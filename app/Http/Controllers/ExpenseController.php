<?php
namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    /**
     * Listar todos los gastos
     */
    public function index(Request $request)
    {
        $query = Expense::query();     

        if ($request->filled('fecha')) {
            $searchTerm = '%' . $request->fecha . '%';
            $query->where('apertura', 'LIKE', $searchTerm);
        }
        // Guardamos la paginación en una variable antes de retornarla
        $gastos = $query->with('purchaseOrder','user')
        ->orderBy('fecha', 'desc')->paginate(20);

        return response()->json($gastos);
    }

    /**
     * Crear un gasto manual (no necesariamente ligado a factura)
     */
    public function store(Request $request)
    {
        $request->validate([    
            'user_id' => 'required',
            'tipo_gasto' => 'required|string|max:100',
            'monto' => 'required|numeric|min:0',
            'fecha' => 'required|date',
            'descripcion' => 'nullable|string',
            'purchase_order_id' => 'nullable|exists:purchase_orders,id',
        ]);

        $expense = Expense::create($request->all());

        return response()->json([
            'message' => 'Gasto registrado correctamente',
            'expense' => $expense
        ], 201);
    }

    /**
     * Mostrar un gasto específico
     */
    public function show($id)
    {
        $expense = Expense::with('purchaseOrder')->findOrFail($id);
        return response()->json($expense);
    }

    /**
     * Actualizar un gasto
     */
    public function update(Request $request, $id)
    {
        $expense = Expense::findOrFail($id);

        $request->validate([
            'tipo_gasto' => 'sometimes|string|max:100',
            'monto' => 'sometimes|numeric|min:0',
            'fecha' => 'sometimes|date',
            'descripcion' => 'nullable|string',
            'purchase_order_id' => 'nullable|exists:purchase_orders,id',
        ]);

        $expense->update($request->all());

        return response()->json([
            'message' => 'Gasto actualizado correctamente',
            'expense' => $expense
        ]);
    }

    /**
     * Eliminar un gasto
     */
    public function destroy($id)
    {
        $expense = Expense::findOrFail($id);
        $expense->delete();

        return response()->json(['message' => 'Gasto eliminado correctamente']);
    }

    /**
     * Crear gasto automáticamente desde una factura
     */
    public function createFromPurchaseOrder($purchaseOrderId)
    {
        $purchaseOrder = PurchaseOrder::findOrFail($purchaseOrderId);

        // Verificamos si ya existe gasto asociado
        if ($purchaseOrder->expense) {
            return response()->json([
                'message' => 'Ya existe un gasto para esta factura',
                'expense' => $purchaseOrder->expense
            ], 400);
        }

        $expense = Expense::create([
            'purchase_order_id' => $purchaseOrder->id,
            'tipo_gasto' => 'Compra mercadería', // puedes cambiar según lógica
            'monto' => $purchaseOrder->total,
            'fecha' => $purchaseOrder->order_date,
            'descripcion' => 'Factura de compra N° ' . $purchaseOrder->factura_numero,
        ]);

        return response()->json([
            'message' => 'Gasto generado desde factura',
            'expense' => $expense
        ], 201);
    }
}
