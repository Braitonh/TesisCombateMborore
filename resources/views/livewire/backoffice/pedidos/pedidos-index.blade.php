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

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        initMenuItem('#accordion-collapsemenu-pedidos', '#pedidos');
    });
</script>