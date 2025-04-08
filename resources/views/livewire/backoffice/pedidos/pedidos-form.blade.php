<div class="container mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Todos los productos</h1>
        <div class="relative bg-teal-100 p-2 rounded-lg cursor-pointer" wire:click="toggleCartModal">
            <i class="fa-solid fa-cart-shopping text-teal-600 text-2xl animate-wiggle"></i>
            <div class="px-1 py-0.5 bg-teal-500 min-w-5 rounded-full text-center text-white text-xs absolute -top-2 -end-1 translate-x-1/4 text-nowrap">
                <div @class(['absolute top-0 start-0 rounded-full -z-10 bg-teal-200 w-full h-full', 'animate-ping' => count($shoppingCart) > 0,])></div>
                {{count($shoppingCart)}}
            </div>
        </div>
    </div>

    @if ($showSuccessAlert)
        <div
            id="success-alert"
            class="fixed top-4 right-4 bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded shadow-lg z-50 transition-all duration-300"
        >
            <strong class="font-semibold">¡Éxito!</strong> Producto agregado al carrito.
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach ($productos as $producto)
            @include('backoffice.pedidos.cardPedido', ['producto' => $producto])
        @endforeach
    </div>

    @if ($showModal && !empty($shoppingCart))
        <div class="fixed inset-0 z-50 bg-black bg-opacity-50 flex justify-center items-start pt-10 overflow-y-auto">
            <div class="bg-white rounded-lg shadow-lg w-full max-w-6xl p-6 grid grid-cols-1 lg:grid-cols-3 gap-4">
                <div class="col-span-2 space-y-6 overflow-y-auto max-h-[70vh] pr-2">
                    @foreach ($shoppingCart as $producto)
                        <div class="border rounded-lg">
                            <div class="p-4 space-y-4">
                                <div class="flex items-center gap-4">
                                    <img src="{{ asset($producto->imagen) }}" class="w-20 h-20 object-cover rounded" />
                                    <div class="flex-1">
                                        <div class="font-medium">{{ $producto['nombre'] }}</div>
                                        <div class="flex items-center gap-2 mt-2">
                                            <button wire:click="decrementar({{ $producto->id }})" class="px-2 py-1 bg-gray-200 rounded">-</button>
                                            <div class="text-sm text-gray-500">Unidades {{ $cantidades[$producto->id] ?? 0 }}</div>
                                            <button wire:click="incrementar({{ $producto->id }})" class="px-2 py-1 bg-gray-200 rounded">+</button>
                                        </div>
                                        <div>
                                            <button wire:click="eliminar({{ $producto->id }})" class="mt-2 text-red-500 hover:text-red-700">
                                                <i class=" fa-solid fa-trash"></i>
                                            </button>
                                            <span class="text-xs text-gray-500">Eliminar</span>
                                        </div>

                                    </div>
                                    <div class="text-right font-bold text-gray-700">
         
                                        <div>${{ number_format($producto->precio * ($cantidades[$producto->id] ?? 1), 0, ',', '.') }}</div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Resumen de compra --}}
                <div class="flex flex-col gap-y-4">
                    <div class="bg-gray-50 border rounded-lg p-4 h-fit">
                        <h2 class="text-lg font-semibold mb-4">Datos del cliente</h2>
                        <div class="mb-4 display flex flex-direccion row space-x-2">
                            <input 
                                type="text" 
                                wire:model.defer="buscador"
                                placeholder="Buscar cliente..."
                                class="w-1/2 sm:w-1/2 px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent"
                            >
                            <button wire:click="buscarCliente" class="w-10 h-10 bg-blue-500 hover:bg-blue-600 text-white rounded-full flex justify-center items-center">
                                <i class="fas fa-search text-white text-lg"></i>
                            </button>

                            <button wire:click="limpiarBuscador" class="w-10 h-10 bg-gray-500 hover:bg-gray-600 text-white rounded-full flex justify-center items-center">
                                <i class="fas fa-trash text-lg"></i>
                            </button>

                        </div>
                        @if ($clienteEncontrado === true)
                            <div class="mt-4 p-4 bg-green-100 text-green-800 rounded mb-4">Cliente encontrado</div>
                        @elseif ($clienteEncontrado === false)
                            <div class="mt-4 p-4 bg-red-100 text-red-800 rounded mb-4">❌ Cliente no encontrado</div>
                        @endif
                        <div wire:submit="save" class="">
                            <div>
                                <label class="block text-gray-700">Nombre</label>
                                <input type="text" wire:model="nombre" class="w-full px-4 py-2 border rounded-md">
                                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                    
                            <div>
                                <label class="block text-gray-700">Email</label>
                                <input type="email" wire:model="email" class="w-full px-4 py-2 border rounded-md">
                                @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                    
                            <div>
                                <label class="block text-gray-700">Telefono</label>
                                <input type="text" wire:model="telefono" class="w-full px-4 py-2 border rounded-md">
                                @error('telefono') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                    
                            <div>
                                <label class="block text-gray-700">Direccion</label>
                                <input type="text" wire:model="direccion" class="w-full px-4 py-2 border rounded-md">
                                @error('direccion') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 border rounded-lg p-4 h-fit">
                        <h2 class="text-lg font-semibold mb-4">Resumen de compra</h2>
    
                        <div class="flex justify-between text-sm mb-2">
                            <span>Envíos</span>
                            <span class="text-green-600">Gratis</span>
                        </div>
    
                        <hr class="my-2" />
    
                        <div class="flex justify-between font-bold text-lg mb-4">
                            <span>Total</span>
                            <span>
                                Total: ${{ number_format($this->total, 0, ',', '.') }}
                            </span>
                        </div>

                        @error('cliente')
                            <div class="mb-4 bg-red-100 border border-red-300 text-red-700 px-4 py-2 rounded">
                                {{ $message }}
                            </div>
                        @enderror
    
                        <button wire:click='savePedido' class="w-full bg-blue-500 hover:bg-blue-600 text-white py-2 rounded-md">
                            Confirmar compra
                        </button>
    
                    </div>
                </div>
                
                <div class="col-span-1 row-start-3 col-start-3 flex justify-end">
                    <button wire:click="toggleCartModal" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
                        Cerrar
                    </button>
                </div>


            </div>

        </div>
    @endif

    <div wire:loading wire:target="toggleCartModal" >
        <x-spinner />
    </div>


</div>

<script>
    window.addEventListener('hide-success-alert', () => {
        setTimeout(() => {
            const alert = document.getElementById('success-alert');
            if (alert) {
                alert.classList.add('opacity-0', 'translate-y-2');
                setTimeout(() => {
                    alert.style.visibility = 'hidden';
                    window.Livewire.dispatch('alertHidden'); // Livewire 3 style

                }, 100); // espera a que termine la animación CSS
            }
        }, 1000);
    });
</script>