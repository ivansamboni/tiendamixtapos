<?php

namespace App\Http\Controllers\Api\Productos;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\Controller;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;


class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $productos = Producto::with(['categoria', 'marca', 'proveedor'])
            ->leftJoin('categories', 'productos.categoria_id', '=', 'categories.id')
            ->leftJoin('marcas', 'productos.marca_id', '=', 'marcas.id')
            ->leftJoin('sellers', 'productos.proveedor_id', '=', 'sellers.id')
            ->orderBy('productos.nombre', 'asc')
            ->select('productos.*')
            ->paginate(20);

        return response()->json($productos);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $file_name1 = '';     

        if ($request->hasFile('img1')) {
            $file = $request->file('img1');
            $timestamp = now()->format('His');
            $file_name1 = $timestamp . $file->getClientOriginalName();
            $file->move(public_path('archivos/folder_img_product'), $file_name1);
        }
       

        $request->validate([
            'nombre' => 'required|unique:productos',
            'stock' => 'required',
            'stock_minimo' => 'required',
            'codigo_barras' => 'unique:productos',
        ]);


        $producto = new Producto();
        $producto->nombre = $request->input('nombre');
        $producto->descripcion = $request->input('descripcion');
        $producto->marca_id = $request->input('marca_id');
        $producto->categoria_id = $request->input('categoria_id');
        $producto->precio_venta = $request->input('precio_venta');
        $producto->precio_compra = $request->input('precio_compra');
        $producto->ganancia = $request->input('ganancia');
        $producto->iva = $request->input('iva');
        $producto->ibua = $request->input('ibua');
        $producto->stock = $request->input('stock');
        $producto->stock_minimo = $request->input('stock_minimo');
        $producto->proveedor_id = $request->input('proveedor_id');
        $producto->codigo_barras = $request->input('codigo_barras');
        $producto->img1 = $file_name1;

        $producto->save();



        return response($producto, Response::HTTP_CREATED);
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

        // Validar los datos de entrada
        $request->validate([
            'nombre' => 'required|unique:productos,nombre,' . $producto->id,
        ]);

        // Actualizar los demás campos
        $producto->nombre = $request->input('nombre');
        $producto->descripcion = $request->input('descripcion');
        $producto->marca_id = $request->input('marca_id');
        $producto->categoria_id = $request->input('categoria_id');        
        $producto->precio_venta = $request->input('precio_venta');
        $producto->precio_compra = $request->input('precio_compra');
        $producto->ganancia = $request->input('ganancia');
        $producto->iva = $request->input('iva');
        $producto->ibua = $request->input('ibua');
        $producto->stock = $request->input('stock');
        $producto->stock_minimo = $request->input('stock_minimo');
        $producto->proveedor_id = $request->input('proveedor_id');
        $producto->codigo_barras = $request->input('codigo_barras');

        // Guardar los cambios
        $producto->save();

        return response($producto, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $producto = Producto::find($id);
        $producto->delete();
    }
}
