<div class="container mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-gray-500 text-2xl font-bold">Todas las ofertas</h1>
        <a href="{{ route('productos.combos.crear') }}" class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-md inline-flex items-center">
            <i class="fas fa-plus mr-2"></i> Nueva Oferta
        </a>
    </div>
    @if (session()->has('success'))
        <div class="mt-4 p-4 bg-green-100 text-green-800 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif
    <div id="ofertas-grid"  class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach ($ofertas as $oferta)
            <div

                class="rounded-lg shadow-md bg-white overflow-hidden flex flex-col h-full cursor-move"
                data-id="{{ $oferta->id }}"
                wire:key="oferta-{{ $oferta->id }}"
            >
                <img src="{{ asset($oferta->imagen ?? 'https://source.unsplash.com/random/300x300') }}"
                     alt="Imagen producto" class="object-cover w-full h-auto">

                <div class="flex flex-col justify-between flex-1 p-4">
                    <div class="flex flex-col gap-3">
                        <h2 class="text-xl font-bold text-gray-800">
                            <span class="font-light">Nombre </span>{{ $oferta->nombre }}
                        </h2>
                        <p class="text-gray-800 font-bold">
                            <span class="font-light">Descripción </span>{{ $oferta->descripcion }}
                        </p>
                        <h2 class="text-xl font-bold text-gray-800">
                            <span class="font-light">Precio </span> ${{ $oferta->precio }}
                        </h2>
                    </div>

                    <button
                        wire:click="confirmDelete({{ $oferta->id }})"
                        type="button"
                        class="mt-6 mb-4 w-full bg-red-500 hover:bg-red-600 text-white font-semibold py-2 rounded">
                        Eliminar
                    </button>

                    <a href="{{ route('productos.combos.edit', $oferta->id) }}"
                        type="button"
                        class="text-center w-full bg-orange-500 hover:bg-orange-600 text-white font-semibold py-2 rounded">
                        Ver oferta
                    </a>
                </div>
            </div>
        @endforeach
        <div wire:loading  >
            <x-spinner />
        </div>
    </div>

        <!-- Modal de Confirmación -->
        @if($showDeleteModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full" id="delete-modal">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3 text-center">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                        <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mt-4">Confirmar Eliminación</h3>
                    <div class="mt-2 px-7 py-3">
                        <p class="text-sm text-gray-500">
                            ¿Está seguro que desea eliminar esta oferta? Esta acción no se puede deshacer.
                        </p>
                    </div>
                    <div class="items-center px-4 py-3">
                        <button wire:click="delete" class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                            Eliminar
                        </button>
                        <button wire:click="cancelDelete" class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 ml-3">
                            Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endif

</div>




<script>
    document.addEventListener('DOMContentLoaded', function ()
    {
        const table = document.getElementById('ofertas-grid');
        if (!table || table.classList.contains('sortable-applied')) return;

        new Sortable(table, {
            animation: 150,
            ghostClass: 'bg-blue-100',
            onEnd: function (evt) {
                const orden = Array.from(evt.to.children).map(row => row.dataset.id);
            @this.call('actualizarOrdenOfertas', orden);
            },
        });

        table.classList.add('sortable-applied');
    });
</script>
