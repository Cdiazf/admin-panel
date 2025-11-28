<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Models\Gasto;
use Illuminate\Http\Request;

class GastoController extends Controller
{
    public function index(Request $request)
    {
        $eventos = Evento::all();

        // Si se selecciona un evento, cargamos sus gastos
        $gastos = collect();
        if ($request->evento_id) {
            $gastos = Gasto::where('evento_id', $request->evento_id)->get();
        }

        return view('gastos.index', compact('eventos', 'gastos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'evento_id' => 'required',
            'concepto'  => 'required',
            'monto'     => 'required|numeric',
            'fecha'     => 'nullable|date',
        ]);

        Gasto::create($request->all());

        return back()->with('success', 'Gasto registrado correctamente');
    }

    public function update(Request $request, Gasto $gasto)
    {
        $request->validate([
            'concepto' => 'required',
            'monto'    => 'required|numeric',
            'fecha'    => 'nullable|date',
        ]);

        $gasto->update($request->all());

        return back()->with('success', 'Gasto actualizado correctamente');
    }

    public function destroy(Gasto $gasto)
    {
        $gasto->delete();

        return back()->with('success', 'Gasto eliminado correctamente');
    }
}
