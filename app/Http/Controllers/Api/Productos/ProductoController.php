<?php

namespace App\Http\Controllers\Api\Productos;
use App\Exports\ProductoExport;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Controller;
use App\Models\Producto;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProductoImport;




class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Producto::query();
        $prodCant = 0;
        if ($request->filled('categoria_id')) {
            $query->where('categoria_id', $request->categoria_id);
        }
        if ($request->filled('proveedor_id')) {
            $query->where('proveedor_id', $request->proveedor_id);
        }
        $productos = $query->with('categoria', 'proveedor', 'iva', 'ibua', 'ipc')
            ->orderBy('nombre', 'asc')
            ->paginate(50);


        $prodCant = Producto::count();
        return response()->json([
            'productos' => $productos,
            'total' => $prodCant,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    // 1. Validar primero (Si falla, no procesamos la imagen innecesariamente)
    $request->validate([
        'nombre' => 'required|unique:productos',
        'stock' => 'required|numeric',
        'stock_minimo' => 'required|numeric',
        'precio_compra' => 'required|numeric',
        'precio_venta' => 'required|numeric',
        'codigo_barras' => 'required|unique:productos,codigo_barras',
        'img1' => 'nullable|image|max:2048' // Validación recomendada para fotos
    ]);

    // 2. Capturar valores numéricos
    $precioCompra = floatval($request->input('precio_compra'));
    $precioVenta = floatval($request->input('precio_venta'));

    // 3. Validar lógica de precios (Corregido el error de paréntesis)
    if ($precioCompra >= $precioVenta) {
        return response()->json([
            "message" => "El precio de compra no puede ser mayor o igual al precio de venta"
        ], 422); 
    }

    // 4. Procesar imagen después de validar todo lo demás
    $file_name1 = '';
    if ($request->hasFile('img1')) {
        $file = $request->file('img1');
        $timestamp = now()->format('His');
        $file_name1 = $timestamp . '_' . $file->getClientOriginalName();
        $file->move(public_path('archivos/folder_img_product'), $file_name1);
    }

    // 5. Crear el objeto y asignar valores
    $producto = new Producto();
    $producto->nombre = $request->input('nombre');
    $producto->cantidad = $request->input('cantidad');
    $producto->unidad_medida = $request->input('unidad_medida');
    $producto->descripcion = $request->input('descripcion');
    $producto->marca_id = $request->input('marca_id');
    $producto->categoria_id = $request->input('categoria_id');
    $producto->precio_venta = $precioVenta;
    $producto->precio_compra = $precioCompra;
    $producto->precio_final = $request->input('precio_final');
    
    // Cálculo de ganancia
    $producto->ganancia = $precioVenta - $precioCompra;

    $producto->iva_id = $request->input('iva_id');
    $producto->ibua_id = $request->input('ibua_id');
    $producto->ipc_id = $request->input('ipc_id');
    $producto->stock = $request->input('stock');
    $producto->stock_minimo = $request->input('stock_minimo');
    $producto->proveedor_id = $request->input('proveedor_id');
    $producto->codigo_barras = $request->input('codigo_barras');
    $producto->img1 = $file_name1;

    $producto->save();

    return response()->json($producto, 201); // 201 es el código para "Created"
}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $producto = Producto::find($id);
        return response($producto);
    }
    public function loadimg(Request $request)
    {
        $file_name1 = '';
        $file_name2 = '';
        $file_name3 = '';
        $file_name4 = '';

        $id = $request->input('id');

        $producto = Producto::findOrFail($id);

        // Manejar la actualización del archivo si se ha subido uno nuevo
        if ($request->hasFile('img1')) {
            // Eliminar la imagen anterior si existe
            if ($producto->img1 && file_exists(public_path('archivos/folder_img_product/' . $producto->img1))) {
                unlink(public_path('archivos/folder_img_product/' . $producto->img1));
            }
            // Subir la nueva imagen
            $file = $request->file('img1');
            $timestamp = now()->format('His');
            $file_name1 = $timestamp . $file->getClientOriginalName();
            $file->move(public_path('archivos/folder_img_product'), $file_name1);

            // Actualizar el campo img1
            $producto->img1 = $file_name1;
        }
        if ($request->hasFile('img2')) {
            // Eliminar la imagen anterior si existe
            if ($producto->img2 && file_exists(public_path('archivos/folder_img_product/' . $producto->img2))) {
                unlink(public_path('archivos/folder_img_product/' . $producto->img2));
            }
            // Subir la nueva imagen
            $file = $request->file('img2');
            $timestamp = now()->format('His');
            $file_name2 = $timestamp . $file->getClientOriginalName();
            $file->move(public_path('archivos/folder_img_product'), $file_name2);

            // Actualizar el campo img1
            $producto->img2 = $file_name2;
        }
        if ($request->hasFile('img3')) {
            // Eliminar la imagen anterior si existe
            if ($producto->img3 && file_exists(public_path('archivos/folder_img_product/' . $producto->img3))) {
                unlink(public_path('archivos/folder_img_product/' . $producto->img3));
            }
            // Subir la nueva imagen
            $file = $request->file('img3');
            $timestamp = now()->format('His');
            $file_name3 = $timestamp . $file->getClientOriginalName();
            $file->move(public_path('archivos/folder_img_product'), $file_name3);

            // Actualizar el campo img1
            $producto->img3 = $file_name3;
        }
        if ($request->hasFile('img4')) {
            // Eliminar la imagen anterior si existe
            if ($producto->img4 && file_exists(public_path('archivos/folder_img_product/' . $producto->img4))) {
                unlink(public_path('archivos/folder_img_product/' . $producto->img4));
            }
            // Subir la nueva imagen
            $file = $request->file('img4');
            $timestamp = now()->format('His');
            $file_name4 = $timestamp . $file->getClientOriginalName();
            $file->move(public_path('archivos/folder_img_product'), $file_name4);

            // Actualizar el campo img1
            $producto->img4 = $file_name4;
        }

        $producto->save();
    }
    /**
     * Update the specified resource in storage.
     */
   public function update(Request $request, Producto $producto)
{
    // 1. Validar datos de entrada (agregué numeric para asegurar que sean números)
    $request->validate([
        'nombre' => 'required|unique:productos,nombre,' . $producto->id,
        'codigo_barras' => 'required|unique:productos,codigo_barras,' . $producto->id,
        'stock' => 'required|numeric',
        'stock_minimo' => 'required|numeric',
        'precio_compra' => 'required|numeric',
        'precio_venta' => 'required|numeric',
    ]);

    // 2. Capturar los precios del request para comparar los NUEVOS valores
    $nuevoPrecioCompra = floatval($request->input('precio_compra'));
    $nuevoPrecioVenta = floatval($request->input('precio_venta'));

    // 3. Validar lógica de negocio (usando los nuevos valores)
    if ($nuevoPrecioCompra >= $nuevoPrecioVenta) {
        return response()->json([
            "message" => "El precio de compra no puede ser mayor o igual al precio de venta"
        ], 422); // 422 es el código estándar para errores de validación
    }

    // 4. Asignación de valores
    $producto->nombre = $request->input('nombre');
    $producto->cantidad = $request->input('cantidad');
    $producto->unidad_medida = $request->input('unidad_medida');
    $producto->descripcion = $request->input('descripcion');
    $producto->marca_id = $request->input('marca_id');
    $producto->categoria_id = $request->input('categoria_id');
    $producto->precio_venta = $nuevoPrecioVenta;
    $producto->precio_compra = $nuevoPrecioCompra;
    $producto->precio_final = $request->input('precio_final');
    
    // Calculamos la ganancia con los valores ya validados
    $producto->ganancia = $nuevoPrecioVenta - $nuevoPrecioCompra;

    $producto->iva_id = $request->input('iva_id');
    $producto->ibua_id = $request->input('ibua_id');
    $producto->ipc_id = $request->input('ipc_id');
    $producto->stock = $request->input('stock');
    $producto->stock_minimo = $request->input('stock_minimo');
    $producto->proveedor_id = $request->input('proveedor_id');
    $producto->codigo_barras = $request->input('codigo_barras');

    // 5. Guardar los cambios
    $producto->save();

    return response()->json($producto, 200);
}
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $producto = Producto::find($id);
        $producto->delete();
    }

    public function exportProductos()
    {
        return Excel::download(
            new ProductoExport,
            'productos.xlsx'
        );
    }

    public function importProductoExcel(Request $request)
    {
        $request->validate([
            'archivo' => 'required|file|mimes:xlsx,csv,txt',
        ]);

        try {
            Excel::import(new ProductoImport, $request->file('archivo'));
            return response()->json(['mensaje' => 'Se importó con éxito']);
        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'Error al importar el archivo',
                'detalle' => $e->getMessage()
            ], 500);
        }
    }

}
