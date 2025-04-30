<div class="container mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Lista de Pedidos</h1>
        <a href="{{ route('pedidos.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md inline-flex items-center">
            <i class="fas fa-plus mr-2"></i> Nuevo Pedido
        </a>
    </div>

    <div class="mb-4">
        <input 
            type="text" 
            wire:model.defer="buscador"
            placeholder="Buscar pedido..."
            class="w-1/2 sm:w-1/2 px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-transparent"
        >
        <button wire:click="buscar" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md inline-flex items-center">
            <i class="fas fa-search mr-2"></i> Buscar
        </button>
        <button wire:click="limpiar" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md inline-flex items-center">
            <i class="fas fa-trash mr-2"></i> Limpiar
        </button>
    </div>

    @if (session()->has('success'))
    <div class="mt-4 p-4 bg-green-100 text-green-800 rounded mb-4">
        {{ session('success') }}
    </div>
    @endif

    @include('backoffice.pedidos.table', ['pedidos' => $pedidos])

    @if ($showModal)
        @include('venta.modalToEditPedido')
    @endif

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
                        ¿Está seguro que desea eliminar este pedido? Esta acción no se puede deshacer.
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
    document.addEventListener('DOMContentLoaded', function () {
        initMenuItem('#accordion-collapsemenu-pedidos', '#pedidos');
    });
</script>