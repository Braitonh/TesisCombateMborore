<div class="container mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Todos los Pedidos</h1>
        <a href="{{ route('pedidos.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md inline-flex items-center">
            <i class="fas fa-plus mr-2"></i> Nuevo Pedido
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

        @forelse ($pedidos as $pedido)
        <div wire:poll.5s class="shadow-lg rounded-2xl p-6 border {{ $pedido->tiempo_estado }}">
            {{-- <div> --}}
            <div class="mb-4">
                    <h2 class="text-xl font-bold text-gray-800 mb-2">Pedido #{{ $pedido->id }}</h2>
                    <p class="text-sm text-gray-600">Cliente: <span class="font-medium text-gray-800">{{ $pedido->cliente->nombre }}</span></p>
                    <p class="text-sm text-gray-600">Hora del pedido: {{ \Carbon\Carbon::parse($pedido->updated_at)->format('H:i:s') }}</p>
                    <p class="text-sm text-gray-600">Estado: {{ $pedido->estado }}</p>
                    <p class="text-sm text-gray-600">Total: ${{ number_format($pedido->total, 2) }}</p>
                </div>

                <div>
                    <h4 class="text-md font-semibold text-gray-700 mb-2">Productos:</h4>
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm text-left text-gray-700 border border-gray-200 rounded">
                            @php
                                $bgColor = 'bg-white'; // valor por defecto

                                if (str_contains($pedido->tiempo_estado, 'red')) {
                                    $bgColor = 'bg-red-100';
                                } elseif (str_contains($pedido->tiempo_estado, 'yellow')) {
                                    $bgColor = 'bg-yellow-100';
                                } elseif (str_contains($pedido->tiempo_estado, 'green')) {
                                    $bgColor = 'bg-green-100';
                                }
                            @endphp
                            <thead class="{{ $bgColor }}">
                                <tr>
                                    <th class="px-3 py-2">Nombre</th>
                                    <th class="px-3 py-2">Cantidad</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pedido->productos as $producto)
                                    <tr class="border-t border-gray-200">
                                        <td class="px-3 py-2">{{ $producto->nombre }}</td>
                                        <td class="px-3 py-2">{{ $producto->pivot->cantidad }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
                <div class="mt-2">
                    <button  wire:click="completarPedido({{ $pedido->id }})"
                            class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
                        Completar Pedido
                    </button>
                </div>
            </div>
        @empty
            <p class="text-gray-400">No hay pedidos</p>
        @endforelse
    </div>


    <div wire:loading wire:target="completarPedido" >
        <x-spinner />
    </div>

</div>



<script>
    document.addEventListener('DOMContentLoaded', function () {
        initMenuItem('#accordion-collapsemenu-pedidos', '#detalle');
    });
</script>
