<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight">
            {{ __('Gastos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Selector de Evento --}}
            <div class="mb-6 bg-white p-6 shadow sm:rounded-lg">
                <form method="GET" action="{{ route('gastos.index') }}">
                    <label class="block mb-2 font-medium">Seleccionar Evento</label>

                    <select name="evento_id" 
                            class="border-gray-300 rounded-md w-full" 
                            onchange="this.form.submit()">
                        <option value="">-- Seleccionar Evento --</option>

                        @foreach($eventos as $evento)
                            <option value="{{ $evento->id }}" 
                                {{ request('evento_id') == $evento->id ? 'selected' : '' }}>
                                {{ $evento->nombre }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>

            @if(request('evento_id'))
            
                {{-- Formulario para agregar gasto --}}
                <div class="mb-6 bg-white p-6 shadow sm:rounded-lg">
                    <form method="POST" action="{{ route('gastos.store') }}">
                        @csrf

                        <input type="hidden" name="evento_id" value="{{ request('evento_id') }}">

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            
                            <div>
                                <label class="block mb-2 font-medium">Concepto</label>
                                <input type="text" name="concepto" class="border-gray-300 rounded-md w-full">
                            </div>

                            <div>
                                <label class="block mb-2 font-medium">Monto</label>
                                <input type="number" step="0.01" name="monto" class="border-gray-300 rounded-md w-full">
                            </div>

                            <div>
                                <label class="block mb-2 font-medium">Fecha (opcional)</label>
                                <input type="date" name="fecha" class="border-gray-300 rounded-md w-full">
                            </div>

                        </div>

                        <button class="mt-4 px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                            Registrar Gasto
                        </button>
                    </form>
                </div>

                {{-- Tabla de gastos --}}
                <div class="bg-white p-6 shadow sm:rounded-lg">
                    <h3 class="text-lg font-bold mb-4">Gastos Registrados</h3>

                    <table class="w-full border-collapse">
    <thead>
        <tr class="border-b">
            <th class="p-2 text-left">#</th>
            <th class="p-2 text-left">Concepto</th>
            <th class="p-2 text-left">Monto</th>
            <th class="p-2 text-left">Fecha</th>
            <th class="p-2 text-left">Acciones</th>
        </tr>
    </thead>

    <tbody>
        @forelse($gastos as $index => $gasto)
        <tr class="border-b">
            <td class="p-2">{{ $index + 1 }}</td>
            <td class="p-2">{{ $gasto->concepto }}</td>
            <td class="p-2">S/ {{ number_format($gasto->monto, 2) }}</td>
            <td class="p-2">{{ $gasto->fecha ?? '—' }}</td>

            <td class="px-4 py-2 flex gap-3">

                <!-- Editar -->
                <button 
                    onclick='openEdit(@json($gasto))'
                    class="text-yellow-600 hover:text-yellow-800 transition"
                    title="Editar"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" 
                        fill="none" 
                        viewBox="0 0 24 24" 
                        stroke-width="1.5" 
                        stroke="currentColor" 
                        class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16.862 4.487a2.1 2.1 0 113.2 2.72l-.42.42-3.2-3.2.42-.42zM4.5 17.25l9.75-9.75 3.2 3.2-9.75 9.75H4.5v-3.2z" />
                    </svg>
                </button>

                <!-- Eliminar -->
                <form method="POST" action="{{ route('gastos.destroy', $gasto->id) }}">
                    @csrf
                    @method('DELETE')
                    <button 
                        onclick="return confirm('¿Eliminar este gasto?')"
                        class="text-red-600 hover:text-red-800 transition"
                        title="Eliminar"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" 
                            fill="none" 
                            viewBox="0 0 24 24" 
                            stroke-width="1.5" 
                            stroke="currentColor" 
                            class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M6 7.5h12M10 11v6m4-6v6m1.5-10.5l-.5 12a1.5 1.5 0 01-1.5 1.5H10a1.5 1.5 0 01-1.5-1.5l-.5-12m6-3h-3m0 0h-3m3 0V3m0 1.5h3" />
                        </svg>
                    </button>
                </form>

</td>

        </tr>
        @empty
        <tr>
            <td colspan="5" class="p-4 text-center text-gray-500">
                No hay gastos registrados.
            </td>
        </tr>
        @endforelse
    </tbody>
</table>

                </div>

            @endif
            {{-- Total --}}
            <div class="mt-4 p-4 bg-gray-50 border rounded-lg text-right">
                <span class="text-lg font-bold">Total Gastado:</span>
                <span class="text-xl font-extrabold text-indigo-700">S/ {{ number_format($total, 2) }}</span>
            </div>
        </div>
    </div>




    {{-- Modal de edición --}}
<div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center p-4">
    <div class="bg-white p-6 rounded shadow w-full max-w-md">

        <h2 class="text-xl font-bold mb-4">Editar Gasto</h2>

        <form method="POST" id="editForm">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="block mb-1 font-medium">Concepto</label>
                <input id="editConcepto" name="concepto" type="text" class="w-full border rounded p-2">
            </div>

            <div class="mb-3">
                <label class="block mb-1 font-medium">Monto</label>
                <input id="editMonto" name="monto" type="number" step="0.01" class="w-full border rounded p-2">
            </div>

            <div class="mb-3">
                <label class="block mb-1 font-medium">Fecha</label>
                <input id="editFecha" name="fecha" type="date" class="w-full border rounded p-2">
            </div>

            <div class="flex justify-end gap-2">
                <button type="button"
                    onclick="closeEdit()"
                    class="px-3 py-1 bg-gray-300 rounded">
                    Cancelar
                </button>

                <button class="px-3 py-1 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                    Guardar
                </button>
            </div>
        </form>
    </div>
</div>
<script>
    function openEdit(gasto) {
        const modal = document.getElementById('editModal');
        modal.classList.remove('hidden');

        document.getElementById('editConcepto').value = gasto.concepto;
        document.getElementById('editMonto').value = gasto.monto;
        document.getElementById('editFecha').value = gasto.fecha ? gasto.fecha.substring(0, 10) : '';

        document.getElementById('editForm').action = '/gastos/' + gasto.id;
    }

    function closeEdit() {
        document.getElementById('editModal').classList.add('hidden');
    }
</script>



</x-app-layout>
