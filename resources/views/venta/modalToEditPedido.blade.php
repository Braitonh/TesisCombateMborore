<div class="fixed inset-0 z-50 bg-black bg-opacity-50 flex justify-center items-start pt-10 overflow-y-auto">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-6xl p-6 grid grid-cols-1 lg:grid-cols-3 gap-4">
        <div class="col-span-2 space-y-6 overflow-y-auto max-h-[70vh] pr-2">
            <div>
                <label class="text-lg font-semibold mb-2">Agregar producto</label>
                <div class="flex gap-2 items-center">
                    <select wire:model="productoSeleccionado" class="w-full px-4 py-2 border rounded-md mt-2">
                        <option value="">-- Buscar producto --</option>
                        @foreach ($productosDisponibles as $producto)
                            <option value="{{ $producto->id }}">{{ $producto->nombre }}</option>
                        @endforeach
                    </select>
                    <button type="button" wire:click="agregarProducto" class="px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">
                        Agregar
                    </button>
                </div>
            </div>
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
        <form  wire:submit.prevent="savePedido"  class="flex flex-col gap-y-4">
            <div class="bg-gray-50 border rounded-lg p-4 h-fit">
                <h2 class="text-lg font-semibold mb-2">Datos del cliente</h2>
                    <div >
                        <div>
                            <label class="block text-gray-700">Nombre</label>
                            <input type="text" wire:model="nombre" class="w-full px-4 py-2 border rounded-md">
                            @error('nombre') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                
                        <div>
                            <label class="block text-gray-700">Email</label>
                            <input  wire:model="email" class="w-full px-4 py-2 border rounded-md">
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

                <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white py-2 rounded-md">
                    Confirmar compra
                </button>

            </div>
        </form>
        
        <div class="col-span-1 row-start-3 col-start-3 flex justify-end">
            <button wire:click="toggleCartModal" class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600">
                Cerrar
            </button>
        </div> 


    </div>

</div>