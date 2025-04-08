<div class="container mx-auto">
    <div class="flex mb-6">
        <a href="{{ route('productos.index') }}" class="flex items-center text-gray-700 hover:text-gray-900">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="currentColor" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M15 8a.75.75 0 01-.75.75H4.56l3.22 3.22a.75.75 0 11-1.06 1.06l-4.5-4.5a.75.75 0 010-1.06l4.5-4.5a.75.75 0 111.06 1.06L4.56 7.25H14.25A.75.75 0 0115 8z" clip-rule="evenodd"/>
            </svg>
        </a>
        <h1 class="text-2xl font-bold">
            {{ $producto ? 'Editar Producto' : 'Crear Producto' }}
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
            <label class="block text-gray-700">Precio</label>
            <input type="number" wire:model="precio" step="0.01" class="w-full px-4 py-2 border rounded-md">
            @error('precio') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-gray-700">Stock</label>
            <input type="number" wire:model="stock" class="w-full px-4 py-2 border rounded-md">
            @error('stock') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-gray-700">Imagen</label>
            <input type="file" wire:model="imagen" class="w-full px-4 py-2 border rounded-md" accept="image/*">
            @error('imagen') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        
            {{-- Vista previa de la imagen temporal (nueva) o existente --}}
            <div class="mt-2">
                @if ($imagen)
                    <img src="{{ $imagen->temporaryUrl() }}" class="h-32 object-cover rounded">
                @elseif ($producto?->imagen)
                    <img src="{{ asset($producto->imagen) }}" class="h-32 object-cover rounded">
                @endif
            </div>
        </div>
        

        <div>
            <label class="flex items-center cursor-pointer">
                <div class="relative">
                    <input type="checkbox" wire:model="activo" class="sr-only peer">
                    <div class="w-11 h-6 bg-gray-300 rounded-full peer peer-checked:bg-blue-500 transition-colors"></div>
                    <div class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow-md peer-checked:translate-x-full transition-transform"></div>
                </div>
                <span class="ml-3 text-gray-700">Activo</span>
            </label>
            @error('activo') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="text-right">
            <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-md hover:bg-blue-600">
                {{ $producto ? 'Actualizar' : 'Guardar' }} Producto
            </button>
        </div>
    </form>

    <x-spinner />
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        initMenuItem('#accordion-collapsemenu-productos', '#productos');
    });
</script>

