    
    @if ($pedidos->count() > 0)
    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>  
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Id</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente Id</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody id="pedidos-table" class="bg-white divide-y divide-gray-200">
                @foreach ($pedidos as $pedido)
                    <tr class="hover:bg-gray-50" wire:key="pedido-{{ $pedido->id }}">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $pedido->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $pedido->cliente_id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $pedido->total }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $pedido->estado }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $pedido->fecha }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <a href="{{ asset('storage/tickets/pedido_' . $pedido->id . '.pdf') }}" target="_blank" class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white text-xs font-medium rounded hover:bg-blue-700 transition">
                                Ver detalle
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>   
    </div>
    <div class="flex justify-center mt-4">
        {{ $pedidos->links('pagination::custom') }}
    </div>
    <div wire:loading wire:target="actualizarOrden" >
        <x-spinner />
    </div>

@else
    <div class="bg-white rounded-lg shadow p-6 text-center">
        <p class="text-gray-500">No hay pedidos disponibles</p>
    </div>
@endif


