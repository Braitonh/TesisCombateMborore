<div class="space-y-8 p-6 bg-white rounded-lg shadow">
    <section>
        <h2 class="font-semibold text-lg mb-4">Pedidos para delivery</h2>
        <div class="space-y-4">
            @if (session()->has('success'))
                <div class="mt-4 p-4 bg-green-100 text-green-800 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif
            @foreach($orderHistory as $order)
                <div class="grid grid-cols-1 md:grid-cols-6 gap-4 border rounded-lg p-4 items-center">
                    {{-- Col 1: ID + fecha --}}
                    <div>
                        <div class="font-medium">Pedido #{{ $order->id }}</div>
                    </div>

                    {{-- Col 2: Cliente --}}

                    <div class="flex items-center gap-2">
                        <img
                            src="{{ $order->repartidor->avatar
                                ? asset($order->repartidor->avatar)
                                : asset('images/default-avatar.png') }}"
                            class="w-8 h-8 rounded-full object-cover"
                            alt="Avatar de {{ $order->cliente->nombre }}"
                        />
                        <div>
                            <div class="font-medium">{{ $order->repartidor->name }}</div>
                            <div class="text-xs text-gray-500">
                                repartidor
                            </div>
                        </div>
                    </div>

                    {{-- Col 3: Total --}}
                    <div class="flex flex-col items-center gap-2">
                        <span class="text-lg text-gray-500">Total: </span>
                        <div class="font-semibold">${{ number_format($order->total,2) }}</div>

                    </div>

                    <div class="flex flex-col items-center gap-2">
                        <span class="text-lg text-gray-500">Dirrección</span>
                        <span class="ml-2 text-lg font-semibold"> <i class="fa-solid fa-location-dot text-orange-500 text-2xl"></i> {{ Str::limit($order->cliente->direccion, 30) }}</span>
                    </div>

                    <div class="flex flex-col items-center gap-2">
                        <span class="text-lg text-gray-500">Estado</span>
                        <span class="px-3 py-3 text-xl rounded border
                            {{ $order->estado === 'Completado'    ? 'bg-green-100 text-green-600 border-green-600'   : '' }}
                            {{ $order->estado === 'Delivery'  ? 'bg-orange-100 text-orange-600 border-orange-600' : '' }}
                          "
                        >
                          {{ $order->estado }}
                        </span>
                    </div>

                    <div class="flex flex-col items-center gap-2">

                    @if($order->estado === 'Delivery')
                            <span class="text-lg text-gray-500">Accion </span>

                            <button
                                wire:click="completeOrder({{ $order->id }})"
                                class="px-3 py-3  bg-green-500 hover:bg-green-600 text-white rounded"
                            >
                                Completar
                            </button>
                        @endif
                    </div>




                </div>
            @endforeach
        </div>
    </section>
</div>
