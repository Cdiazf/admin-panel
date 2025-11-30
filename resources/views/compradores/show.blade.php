@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto sm:px-6 lg:px-8 py-6">

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif

    <div class="mb-4">
        <a href="{{ route('compradores.index') }}" class="text-sm text-indigo-600 hover:underline">
            ← Volver al listado
        </a>
    </div>

    <div class="bg-white p-6 shadow sm:rounded-lg mb-6">
        <h2 class="text-lg font-semibold mb-3">Datos del comprador</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div>
                <div class="font-medium text-gray-500">Nombre completo</div>
                <div>{{ $comprador->nombre }}</div>
            </div>
            <div>
                <div class="font-medium text-gray-500">WhatsApp</div>
                <div>{{ $comprador->telefono }}</div>
            </div>
            <div>
                <div class="font-medium text-gray-500">Total a pagar</div>
                <div>S/. {{ number_format($comprador->total_a_pagar, 2) }}</div>
            </div>
            <div>
                <div class="font-medium text-gray-500">Monto pagado</div>
                <div class="text-green-700">S/. {{ number_format($comprador->monto_pagado, 2) }}</div>
            </div>
            <div>
                <div class="font-medium text-gray-500">Monto pendiente</div>
                <div class="text-red-600">S/. {{ number_format($comprador->monto_pendiente, 2) }}</div>
            </div>
        </div>
    </div>

    {{-- FORM NUEVO PAGO --}}
    <div class="bg-white p-6 shadow sm:rounded-lg mb-6">
        <h3 class="text-md font-semibold mb-3">Registrar nuevo pago</h3>

        <form method="POST" action="{{ route('pagos.store') }}" class="space-y-4">
            @csrf
            <input type="hidden" name="comprador_id" value="{{ $comprador->id }}">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Fecha</label>
                    <input type="date" name="fecha" value="{{ now()->toDateString() }}"
                           class="w-full border rounded px-3 py-2 text-sm" required>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Monto abonado</label>
                    <input type="number" step="0.01" name="monto"
                           class="w-full border rounded px-3 py-2 text-sm" required>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Método de pago</label>
                    <select name="metodo_pago" class="w-full border rounded px-3 py-2 text-sm" required>
                        <option value="Yape">Yape</option>
                        <option value="Plin">Plin</option>
                        <option value="Transferencia">Transferencia</option>
                        <option value="Efectivo">Efectivo</option>
                        <option value="Otro">Otro</option>
                    </select>
                </div>

                <div class="md:col-span-3">
                    <label class="block text-sm font-medium mb-1">Referencia / Nota</label>
                    <input type="text" name="referencia"
                           class="w-full border rounded px-3 py-2 text-sm">
                </div>
            </div>

            <button type="submit"
                    class="inline-flex items-center px-4 py-2 bg-emerald-600 border border-transparent rounded-md
                           font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700">
                Agregar pago
            </button>
        </form>
    </div>

    {{-- LISTA DE PAGOS --}}
    <div class="bg-white p-6 shadow sm:rounded-lg">
        <h3 class="text-md font-semibold mb-3">Historial de pagos</h3>

        @if($comprador->pagos->isEmpty())
            <div class="text-sm text-gray-500">Aún no hay pagos registrados.</div>
        @else
            <table class="min-w-full text-xs">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-2 py-1 text-left">Fecha</th>
                        <th class="px-2 py-1 text-right">Monto</th>
                        <th class="px-2 py-1 text-left">Método</th>
                        <th class="px-2 py-1 text-left">Referencia</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($comprador->pagos as $pago)
                        <tr class="border-t">
                            <td class="px-2 py-1">{{ $pago->fecha->format('d/m/Y') }}</td>
                            <td class="px-2 py-1 text-right">S/. {{ number_format($pago->monto, 2) }}</td>
                            <td class="px-2 py-1">{{ $pago->metodo_pago }}</td>
                            <td class="px-2 py-1">{{ $pago->referencia }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
@endsection
