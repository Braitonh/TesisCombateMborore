<div x-data="{ open: false }" class="relative w-full">
    <label class="block text-sm font-medium text-gray-700">Seleccionar Cliente</label>

    <input wire:model="search" x-on:focus="open = true" x-on:click.outside="open = false"
        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Buscar cliente...">

    <ul x-show="open"
        class="absolute z-10 mt-1 w-full bg-white border border-gray-200 rounded-md shadow-lg max-h-60 overflow-y-auto"
        x-transition>
        @forelse($clientesFiltrados as $cliente)
            <li wire:click="select({{ $cliente['id'] }})" x-on:click="open = false"
                class="cursor-pointer px-4 py-2 hover:bg-gray-100">
                {{ $cliente['nombre'] }}
            </li>
        @empty
            <li class="px-4 py-2 text-gray-500">No encontrado</li>
        @endforelse
    </ul>
</div>