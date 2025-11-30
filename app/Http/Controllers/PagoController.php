<?php

namespace App\Http\Controllers;

use App\Models\Compradores;
use App\Models\Pagos;
use Illuminate\Http\Request;

class PagoController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'comprador_id' => ['required', 'exists:compradores,id'],
            'monto'        => ['required', 'numeric', 'min:0'],
            'fecha'        => ['required', 'date'],
            'metodo_pago'  => ['required', 'string', 'max:50'],
            'referencia'   => ['nullable', 'string', 'max:255'],
        ]);

        $comprador = Compradores::findOrFail($data['comprador_id']);

        Pagos::create($data);

        $comprador->recalcularMontos();

        return redirect()
            ->route('compradores.show', $comprador)
            ->with('success', 'Pago registrado correctamente.');
    }
}
