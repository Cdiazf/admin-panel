<?php

namespace App\Http\Controllers;

use App\Models\Compradores;
use App\Models\Pago;
use App\Models\Pagos;
use Illuminate\Http\Request;

class CompradorController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10);

        $compradores = Compradores::with('pagos')
            ->when($search, function ($q) use ($search) {
                return $q->where('telefono', 'LIKE', "%{$search}%")
                    ->orWhere('nombre', 'LIKE', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->appends(['search' => $search, 'per_page' => $perPage]);

        return view('compradores.index', compact('compradores', 'search', 'perPage'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre'         => ['required', 'string', 'max:255'],
            'telefono'       => ['nullable', 'string', 'max:50'],
            'total_a_pagar'  => ['required', 'numeric', 'min:0'],
            'monto'          => ['required', 'numeric', 'min:0'],
            'fecha'          => ['required', 'date'],
            'metodo_pago'    => ['required', 'string', 'max:50'],
            'referencia'     => ['nullable', 'string', 'max:255'],
        ]);

        // Crear comprador
        $comprador = Compradores::create([
            'nombre'        => $data['nombre'],
            'telefono'      => $data['telefono'] ?? null,
            'total_a_pagar' => $data['total_a_pagar'],
            'monto_pagado'  => 0,
        ]);

        // Crear primer pago
        Pagos::create([
            'comprador_id' => $comprador->id,
            'monto'        => $data['monto'],
            'fecha'        => $data['fecha'],
            'metodo_pago'  => $data['metodo_pago'],
            'referencia'   => $data['referencia'] ?? null,
        ]);

        $comprador->recalcularMontos();

        return redirect()
            ->route('compradores.index')
            ->with('success', 'Entrada registrada correctamente.');
    }

    public function show(Compradores $comprador)
    {
        $comprador->load('pagos');

        return view('compradores.show', compact('comprador'));
    }
}
