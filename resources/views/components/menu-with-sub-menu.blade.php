<li class="rounded-md group hover:bg-gray-200 mt-2">
    <div class="relative">

      @php
        $shouldExpand = collect($subMenus)->pluck('route')->contains(fn($r) => Route::is($r));
      @endphp

      <!-- Checkbox oculto que controla el estado del collapse -->
      <input type="checkbox" id="accordion-collapse{{ $id }}" class="hidden peer" {{ $shouldExpand ? 'checked' : '' }}>
  
      <!-- Label que actúa como botón para expandir/colapsar -->
      <label for="accordion-collapse{{ $id }}" 
        class="flex items-center p-3 cursor-pointer w-full peer-checked:bg-gray-200 rounded-t-md">
        <i class="{{ $icon }} text-lg mr-2"></i>
        {{ $name }}
        <span class="flex-1"></span>
        <!-- SVG que rota cuando el checkbox está marcado -->
        <svg id="svg-{{ $id }}" class="w-4 h-4 transition-transform duration-200 transform rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
      </label>
  
      <!-- Contenedor de los submenús -->
      <div class="pl-5 space-y-2 hidden peer-checked:block peer-checked:bg-gray-200 rounded-b-md">
        @foreach ($subMenus as $subMenu)
          <a id="{{ $subMenu['id'] }}" 
            href="{{ route($subMenu['route']) }}"
            class="block p-2 transition-all rounded-md
                    {{ request()->routeIs($subMenu['route']) ? 'font-semibold text-gray-800' : 'text-gray-700' }}">
            {{ $subMenu['name'] }}
          </a>
        @endforeach
      </div>
    </div>
  </li>
  
  <script>
  document.addEventListener('DOMContentLoaded', function() {
    // Selecciona el checkbox y el SVG usando el ID correspondiente.
    const checkbox = document.getElementById('accordion-collapse{{ $id }}');
    const svg = document.getElementById('svg-{{ $id }}');
    
    checkbox.addEventListener('change', function() {
      if (checkbox.checked) {
        // Si el collapse se expande, rotamos el SVG a 0 grados.
        svg.classList.remove('rotate-180');
        svg.classList.add('rotate-0');
      } else {
        // Si se colapsa, rotamos el SVG a 180 grados.
        svg.classList.remove('rotate-0');
        svg.classList.add('rotate-180');
      }
    });
  });
  </script>
  