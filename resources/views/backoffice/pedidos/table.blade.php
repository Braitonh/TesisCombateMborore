    
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
                    <th class=" px-6 text-left py-3  text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody id="pedidos-table" class="bg-white divide-y divide-gray-200">
                @foreach ($pedidos as $pedido)
                    <tr class="hover:bg-gray-50" wire:key="pedido-{{ $pedido->id }}">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $pedido->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $pedido->cliente_id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">$ {{ $pedido->total }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $pedido->estado }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $pedido->fecha }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <div class="flex items-center justify-start space-x-3">
                                <a href="{{ asset('storage/tickets/pedido_' . $pedido->id . '.pdf') }}" target="_blank" class="text-blue-500 hover:text-blue-600">
                                    <svg class="w-5 h-5 " fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z" />
                                    </svg>
                                </a>
                                
                                <button wire:click='openModal({{ $pedido->id }})' class="text-blue-500 hover:text-blue-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </button>
                                <button wire:click="confirmDelete({{ $pedido->id }})"
                                    class="text-red-600 hover:text-red-900">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                                @if ($pedido->estado === 'Sin confirmar')
                                <button wire:click='iniciarPedido({{ $pedido->id }})'  class="inline-flex items-center px-3 py-1.5 bg-green-500 text-white text-xs font-medium rounded hover:bg-green-600 transition">
                                    Confirmar
                                </button>
                                @endif
                            </div>
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>   
    </div>
    <div class="flex justify-center mt-4">
        {{ $pedidos->links('pagination::custom') }}
    </div>


@else
    <div class="bg-white rounded-lg shadow p-6 text-center">
        <p class="text-gray-500">No hay pedidos disponibles</p>
    </div>
@endif


