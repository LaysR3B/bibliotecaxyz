<?php

namespace App\Http\Controllers;

use App\Models\Libro;
use Illuminate\Http\Request;

class LibroController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $libros = Libro::orderBy('id', 'desc')->get();
        return view('frontend.libros', compact('libros'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'codigo' => 'required|string|max:20|unique:libros,codigo',
            'titulo' => 'required|string|max:255',
            'autor' => 'required|string|max:255',
            'categoria' => 'required|string|max:255',
            'ejemplares' => 'required|integer|min:0',
            'area_estante' => 'required|string|max:255',
        ]);

        Libro::create($request->all());

        return redirect()->route('libros.index')
            ->with('success', 'Libro agregado exitosamente.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'codigo' => 'required|string|max:20|unique:libros,codigo,' . $id,
            'titulo' => 'required|string|max:255',
            'autor' => 'required|string|max:255',
            'categoria' => 'required|string|max:255',
            'ejemplares' => 'required|integer|min:0',
            'area_estante' => 'required|string|max:255',
        ]);

        $libro = Libro::findOrFail($id);
        $libro->update($request->all());

        return redirect()->route('libros.index')
            ->with('success', 'Libro actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $libro = Libro::findOrFail($id);
        $libro->delete();

        return redirect()->route('libros.index')
            ->with('success', 'Libro eliminado exitosamente.');
    }
}
