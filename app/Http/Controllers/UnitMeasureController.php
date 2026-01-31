<?php

namespace App\Http\Controllers;
use App\Models\UnitMeasure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
class UnitMeasureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $unitmeasure = UnitMeasure::orderBy("nombre", "asc")->get();
        return response()->json($unitmeasure);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|unique:unit_measures',            
        ]);

        $unitmeasure = UnitMeasure::create($request->all());
        return response($unitmeasure, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $unitmeasure = UnitMeasure::find($id);
        return response($unitmeasure);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $unitmeasure = UnitMeasure::find($id);

        $request->validate([
            'nombre' => 'required',           
        ]);

        $unitmeasure->fill($request->all())->save();

        return response($unitmeasure, Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $unitmeasure = UnitMeasure::find($id);
        $unitmeasure->delete();
    }
}
