<div class="container">
    <div class="flex mb-6">
        <a href="{{ route('productos.combos') }}" class="flex items-center text-gray-700 hover:text-gray-900">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="currentColor" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M15 8a.75.75 0 01-.75.75H4.56l3.22 3.22a.75.75 0 11-1.06 1.06l-4.5-4.5a.75.75 0 010-1.06l4.5-4.5a.75.75 0 111.06 1.06L4.56 7.25H14.25A.75.75 0 0115 8z" clip-rule="evenodd"/>
            </svg>
        </a>
        <h1 class="text-2xl font-bold">
            {{ $oferta ? 'Editar Oferta' : 'Crear Oferta' }}
        </h1>
    </div>

    <form wire:submit="save" class="bg-white p-6 rounded shadow-md space-y-4">
        <div>
            <label class="block text-gray-700">Nombre</label>
            <input type="text" wire:model="nombre" class="w-full px-4 py-2 border rounded-md">
            @error('nombre') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-gray-700">Descripción</label>
            <textarea wire:model="descripcion" rows="3" class="w-full px-4 py-2 border rounded-md"></textarea>
            @error('descripcion') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-gray-700">Imagen</label>
            <input type="file" wire:model="imagen" class="w-full px-4 py-2 border rounded-md" accept="image/*">
            @error('imagen') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

            {{-- Vista previa de la imagen temporal (nueva) o existente --}}
            <div class="mt-2">
                @if ($imagen)
                    <img src="{{ $imagen->temporaryUrl() }}" class="h-32 object-cover rounded">
                @elseif ($oferta?->imagen)
                    <img src="{{ asset($oferta->imagen) }}" class="h-32 object-cover rounded">
                @endif
            </div>
        </div>

        <div>
            <label class="block text-gray-700">Descuento (%)</label>
            <input type="number" wire:model="descuento" step="0.01" min="0" max="100" class="w-full px-4 py-2 border rounded-md">
            @error('descuento') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>


        <div>
            <label class="block text-gray-700">Agregar Producto</label>
            <select wire:change="agregarProducto($event.target.value)" class="w-full px-4 py-2 border rounded-md">
                <option value="">-- Seleccionar producto --</option>
                @foreach ($productosDisponibles as $producto)
                    @if (!isset($shoppingCart[$producto['id']]))
                        <option value="{{ $producto['id'] }}">{{ $producto['nombre'] }} - ${{ number_format($producto['precio'], 0, ',', '.') }}</option>
                    @endif
                @endforeach
            </select>
        </div>

        <div class="col-span-2 space-y-6 overflow-y-auto max-h-[70vh] pr-2">
            @foreach ($shoppingCart as $producto)
                <div class="border rounded-lg">
                    <div class="p-4 space-y-4">
                        <div class="flex items-center gap-4">
                            <img src="{{ asset($producto->imagen) }}" class="w-20 h-20 object-cover rounded" />
                            <div class="flex-1">
                                <div class="font-medium">{{ $producto['nombre'] }}</div>
                                <div class="flex items-center gap-2 mt-2">
                                    <button type="button" wire:click="decrementar({{ $producto->id }})" class="px-2 py-1 bg-gray-200 rounded">-</button>
                                    <div class="text-sm text-gray-500">Unidades {{ $cantidades[$producto->id] ?? 0 }}</div>
                                    <button type="button" wire:click="incrementar({{ $producto->id }})" class="px-2 py-1 bg-gray-200 rounded">+</button>
                                </div>
                                <div>
                                    <button type="button" wire:click="eliminar({{ $producto->id }})" class="mt-2 text-red-500 hover:text-red-700">
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

        <div class="text-right text-lg font-semibold text-gray-800">
            Total con descuento: ${{ number_format($this->precioTotal, 0, ',', '.') }}
        </div>

        <div class="text-right">
            <button type="submit" class="bg-orange-500 text-white px-6 py-2 rounded-md hover:bg-orange-600">
                {{ $oferta ? 'Actualizar' : 'Guardar' }} Oferta
            </button>
        </div>
    </form>
    <div wire:loading  >
        <x-spinner />
    </div>
</div>

