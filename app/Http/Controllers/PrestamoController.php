<?php

namespace App\Http\Controllers;

use App\Models\Libro;
use App\Models\Prestamo;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PrestamoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $prestamos = Prestamo::with(['user', 'libro'])->orderBy('fecha_prestamo', 'desc')->get();
        $usuarios = User::orderBy('name')->get(['id', 'name']);
        $libros = Libro::orderBy('titulo')->get(['id', 'titulo']);

        return view('frontend.prestamos', [
            'prestamos' => $prestamos,
            'usuarios' => $usuarios,
            'libros' => $libros,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'estado' => 'required|in:Pendiente,Devuelto',
            'libro_id' => 'required|exists:libros,id',
            'fecha_prestamo' => 'required|date',
            'fecha_devolucion' => 'nullable|date|after_or_equal:fecha_prestamo',
        ]);

        if ($data['estado'] === 'Devuelto' && empty($data['fecha_devolucion'])) {
            $data['fecha_devolucion'] = Carbon::now()->toDateString();
        }

        Prestamo::create($data);

        return redirect()->route('prestamos.index')
            ->with('success', 'Préstamo registrado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'estado' => 'required|in:Pendiente,Devuelto',
            'libro_id' => 'required|exists:libros,id',
            'fecha_prestamo' => 'required|date',
            'fecha_devolucion' => 'nullable|date|after_or_equal:fecha_prestamo',
        ]);

        if ($data['estado'] === 'Devuelto' && empty($data['fecha_devolucion'])) {
            $data['fecha_devolucion'] = Carbon::now()->toDateString();
        }

        $prestamo = Prestamo::findOrFail($id);
        $prestamo->update($data);

        return redirect()->route('prestamos.index')
            ->with('success', 'Préstamo actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $prestamo = Prestamo::findOrFail($id);
        $prestamo->delete();

        return redirect()->route('prestamos.index')
            ->with('success', 'Préstamo eliminado exitosamente.');
    }

    public function reportes()
    {
        $topPrestamos = Prestamo::select('user_id', DB::raw('COUNT(*) as total'))
            ->with('user:id,name')
            ->groupBy('user_id')
            ->orderByDesc('total')
            ->take(10)
            ->get()
            ->filter(fn($item) => $item->user);

        $topPrestamosChart = [
            'labels' => $topPrestamos->pluck('user.name')->toArray(),
            'values' => $topPrestamos->pluck('total')->toArray(),
        ];

        $fechaVencido = Carbon::now()->subDays(15);
        $prestamosVencidos = Prestamo::with('user:id,name')
            ->where('estado', 'Pendiente')
            ->whereDate('fecha_prestamo', '<=', $fechaVencido)
            ->get()
            ->groupBy('user_id')
            ->map(function ($items) {
                $user = $items->first()->user;
                if (! $user) {
                    return null;
                }

                return [
                    'name' => $user->name,
                    'total' => $items->count(),
                ];
            })
            ->filter()
            ->values();

        $prestamosVencidosChart = [
            'labels' => $prestamosVencidos->pluck('name')->toArray(),
            'values' => $prestamosVencidos->pluck('total')->toArray(),
        ];

        $limiteInactivo = Carbon::now()->subDays(60);
        $usuariosInactivos = User::orderBy('name')->get(['id', 'name'])
            ->filter(function ($user) use ($limiteInactivo) {
                return ! $user->prestamos()->where('fecha_prestamo', '>=', $limiteInactivo)->exists();
            })
            ->values();

        $usuariosInactivosChart = [
            'labels' => $usuariosInactivos->pluck('name')->toArray(),
            'values' => $usuariosInactivos->map(fn () => 1)->toArray(),
        ];

        $chartData = [
            'masPrestamos' => $topPrestamosChart,
            'vencidos' => $prestamosVencidosChart,
            'inactivos' => $usuariosInactivosChart,
        ];

        return view('frontend.reportes', [
            'topPrestamos' => $topPrestamos,
            'prestamosVencidos' => $prestamosVencidos,
            'usuariosInactivos' => $usuariosInactivos,
            'chartData' => $chartData,
        ]);
    }
}
