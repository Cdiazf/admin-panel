@extends('layouts.app')

@section('content')
<div class="w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">

    {{-- MENSAJE DE ÉXITO --}}
    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif

    {{-- FORMULARIO NUEVO COMPRADOR --}}
    <div class="mb-6 bg-white p-6 shadow rounded-lg">
        <h2 class="text-lg font-semibold mb-4">Registrar nueva entrada – Convención Cusco</h2>

        <form method="POST" action="{{ route('compradores.store') }}" class="space-y-4">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Nombre completo</label>
                    <input type="text" name="nombre" value="{{ old('nombre') }}"
                           class="w-full border rounded px-3 py-2 text-sm" required>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">WhatsApp</label>
                    <input type="text" name="telefono" value="{{ old('telefono') }}"
                           class="w-full border rounded px-3 py-2 text-sm">
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Fecha de abono</label>
                    <input type="date" name="fecha" value="{{ old('fecha', now()->toDateString()) }}"
                           class="w-full border rounded px-3 py-2 text-sm" required>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Monto abonado (primer pago)</label>
                    <input type="number" step="0.01" name="monto" value="{{ old('monto') }}"
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

                <div>
                    <label class="block text-sm font-medium mb-1">Total a pagar</label>
                    <input type="number" step="0.01" name="total_a_pagar"
                           value="{{ old('total_a_pagar', 127) }}"
                           class="w-full border rounded px-3 py-2 text-sm" required>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium mb-1">Referencia / Nota (opcional)</label>
                    <input type="text" name="referencia" value="{{ old('referencia') }}"
                           class="w-full border rounded px-3 py-2 text-sm">
                </div>
            </div>

            <button type="submit"
                class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 text-xs">
                Guardar entrada
            </button>
        </form>
    </div>

    {{-- LISTADO – MÓVIL --}}
    <div class="md:hidden space-y-3">
        @foreach($compradores as $c)
            <a href="{{ route('compradores.show', $c) }}"
               class="block bg-white p-4 shadow rounded-lg">
                <div class="font-semibold">{{ $c->nombre }}</div>
                <div class="text-sm text-gray-600">📞 {{ $c->telefono }}</div>
                <div class="text-sm">Total: S/. {{ number_format($c->total_a_pagar, 2) }}</div>
                <div class="text-sm text-green-700">Pagado: S/. {{ number_format($c->monto_pagado, 2) }}</div>
                <div class="text-sm text-red-600">Falta: S/. {{ number_format($c->monto_pendiente, 2) }}</div>
            </a>
        @endforeach

        <div class="mt-3">
            {{ $compradores->links() }}
        </div>
    </div>

    {{-- LISTADO – DESKTOP ESTILO GOOGLE SHEET --}}
    <div >

        {{-- FILTROS --}}
        <div class="flex justify-between items-center mb-4">

            <form method="GET" action="{{ route('compradores.index') }}" class="flex items-center space-x-2">

                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Buscar WhatsApp o nombre..."
                    class="border rounded px-3 py-2 text-sm w-64">

                <select name="per_page"
                        onchange="this.form.submit()"
                        class="border rounded px-2 py-2 text-sm">
                    <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                    <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20</option>
                    <option value="30" {{ request('per_page') == 30 ? 'selected' : '' }}>30</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                </select>

                <button class="bg-indigo-600 text-white px-4 py-2 rounded text-sm">
                    Buscar
                </button>

            </form>
        </div>

        {{-- TABLA --}}
        <div class="bg-blue p-4 shadow rounded-lg overflow-x-auto border border-gray-300">

            <table class="min-w-full text-xs border-collapse">

                <thead>
                    <tr class="bg-gray-100 border-b border-gray-300 text-gray-700">
                        <th class="px-3 py-2 text-left font-semibold border-r">Nombre asdad</th>
                        <th class="px-3 py-2 text-left font-semibold border-r">WhatsApp</th>
                        <th class="px-3 py-2 text-right font-semibold border-r">Total S/</th>
                        <th class="px-3 py-2 text-right font-semibold border-r">Pagado S/</th>
                        <th class="px-3 py-2 text-right font-semibold border-r">Falta S/</th>
                        <th class="px-3 py-2 text-center font-semibold border-r">N° pagos</th>
                        <th class="px-3 py-2 text-center font-semibold">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($compradores as $c)
                        <tr class="hover:bg-blue-50 transition border-b">

                            <td class="px-3 py-2 border-r">{{ $c->nombre }}</td>

                            <td class="px-3 py-2 border-r">{{ $c->telefono }}</td>

                            <td class="px-3 py-2 text-right border-r">
                                {{ number_format($c->total_a_pagar, 2) }}
                            </td>

                            <td class="px-3 py-2 text-right text-green-700 border-r">
                                {{ number_format($c->monto_pagado, 2) }}
                            </td>

                            <td class="px-3 py-2 text-right text-red-600 border-r">
                                {{ number_format($c->monto_pendiente, 2) }}
                            </td>

                            <td class="px-3 py-2 text-center border-r">
                                {{ $c->pagos->count() }}
                            </td>

                            <td class="px-3 py-2 text-center">
                                <a href="{{ route('compradores.show', $c) }}"
                                    class="text-indigo-600 hover:underline font-semibold">
                                    Ver detalle
                                </a>
                            </td>

                        </tr>
                    @endforeach
                </tbody>

            </table>

            <div class="mt-3">
                {{ $compradores->appends(request()->all())->links() }}
            </div>

        </div>
    </div>

</div>
@endsection
