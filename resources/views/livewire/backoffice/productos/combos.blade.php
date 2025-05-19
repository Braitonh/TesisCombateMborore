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
                        wire:click="addToCard({{ $oferta->id }})"
                        type="button"
                        class="mt-6 w-full bg-orange-500 hover:bg-orange-600 text-white font-semibold py-2 rounded">
                        Ver oferta
                    </button>
                </div>
            </div>
        @endforeach
        <div wire:loading  >
            <x-spinner />
        </div>
    </div>

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
