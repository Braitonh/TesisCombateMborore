@if ($productos->count() > 0)
    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descripción</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Precio</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($productos as $producto)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $producto->nombre }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $producto->descripcion }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${{ number_format($producto->precio, 2) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $producto->stock }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <div class="flex items-center space-x-3">
                                {{-- Toggle Switch --}}
                                @livewire('toggle-switch', ['producto' => $producto])

                                <!-- Botón Editar -->
                                <a href="{{ route('productos.index', $producto->id) }}" 
                                   class="text-blue-600 hover:text-blue-900">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>

                                <!-- Botón Eliminar -->
                                <button onclick="eliminarProducto({{ $producto->id }})"
                                        class="text-red-600 hover:text-red-900">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>


@else
    <div class="bg-white rounded-lg shadow p-6 text-center">
        <p class="text-gray-500">No hay productos disponibles</p>
    </div>
@endif