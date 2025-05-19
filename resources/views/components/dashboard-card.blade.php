{{--<div class="bg-gray-800 text-white p-4 rounded-lg flex flex-col items-center">--}}
{{--    <img src="{{ $imagen }}" class="w-24 h-24 object-cover rounded mb-2">--}}
{{--    <span class="bg-orange-500 px-2 py-1 text-xs rounded-full">{{ $badge }}</span>--}}
{{--    <h4 class="mt-2 font-semibold">{{ $titulo }}</h4>--}}
{{--    <p class="text-sm">{{ $tiempo }} • {{ $calorias }}</p>--}}
{{--    <a href="#" class="mt-auto inline-block text-orange-300 hover:text-white">--}}
{{--        <i class="fas fa-arrow-right-circle"></i>--}}
{{--    </a>--}}
{{--</div>--}}
<div class="bg-white rounded-xl shadow-md p-4 flex flex-col items-center space-y-4 hover:shadow-lg transition-all duration-300">
    {{-- Imagen centrada en círculo --}}
    <div class="w-24 h-24 rounded-full overflow-hidden border-2 border-gray-200">
        <img src="{{ $imagen }}" alt="Plato" class="w-full h-full object-cover">
    </div>

    {{-- Descripción breve --}}
    <p class="text-center text-sm text-gray-600 leading-snug">
        Lorem ipsum dolor sit amet consectetur. Dig Nissim molestie.
    </p>

    {{-- Precio y botón --}}
    <div class="w-full flex items-center justify-between mt-auto">
        <span class="text-lg font-bold text-gray-800">#5,000</span>
        <button class="bg-green-500 text-white p-2 rounded-full hover:bg-green-600">
            <i class="fas fa-plus"></i>
        </button>
    </div>
</div>

