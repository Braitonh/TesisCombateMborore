@php
    $color = $color ?? 'blue';
    $bgClass = "bg-{$color}-500";
    $hoverClass = "hover:bg-{$color}-600";
@endphp

<div class="rounded-lg shadow-md bg-white overflow-hidden flex flex-col h-full">
    <img src="{{ asset($producto->imagen ?? 'https://source.unsplash.com/random/300x300') }}"
         alt="Imagen producto" class="object-cover w-full h-auto">

    <div class="flex flex-col justify-between flex-1 p-4">
        <div>
            <h2 class="text-xl font-bold text-gray-800">{{ $producto->nombre }}</h2>
            <p class="text-gray-600 mt-2">{{ $producto->descripcion }}</p>
        </div>

        <button
            wire:click="addToCard({{$producto}})"
            type="button"
            class="mt-6 w-full {{ $bgClass }} {{ $hoverClass }} text-white font-semibold py-2 rounded">
            Agregar al carrito
        </button>
    </div>
</div>
