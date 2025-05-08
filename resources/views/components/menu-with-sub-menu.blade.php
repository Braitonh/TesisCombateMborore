@props([
  'id', 
  'icon', 
  'name', 
  'subMenus', 
  // Nuevo prop: rutas adicionales a tratar como activas
  'extraRoutes' => [],
  // También podrías aceptar patrones, ej. 'productos.*'
  'extraPatterns' => [],
])

@php
  // 1) Rutas definidas en subMenus
  $routes = collect($subMenus)->pluck('route')->toArray();
  // 2) Añadimos las extra
  $routes = array_merge($routes, (array) $extraRoutes);

  // Comprobación de patrones (opcional)
  $isPatternMatch = false;
  foreach ((array) $extraPatterns as $pattern) {
      if (request()->routeIs($pattern)) {
          $isPatternMatch = true;
          break;
      }
  }

  // Finalmente, vemos si la ruta actual coincide
  $isActive = collect($routes)->contains(fn($r) => request()->routeIs($r)) 
              || $isPatternMatch;
@endphp

<li class="mt-2">
  <div x-data="{ open: @json($isActive) }" class="rounded-md overflow-hidden">
    <button
      @click="open = !open"
      :class="open 
        ? 'bg-orange-500 text-white' 
        : 'bg-white text-gray-500 hover:bg-orange-500 hover:text-white'"
      class="w-full flex items-center px-2 py-2 transition-colors"
    >
      <i class="{{ $icon }} text-lg mr-3"></i>
      <span class="font-medium">{{ $name }}</span>
      <span class="flex-1"></span>
      <svg
        :class="open ? 'rotate-0' : 'rotate-180'"
        class="w-4 h-4 transform transition-transform duration-200"
        fill="none"
        stroke="currentColor"
        viewBox="0 0 24 24"
      >
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M19 9l-7 7-7-7" />
      </svg>
    </button>

    <ul x-show="open" x-collapse class="">
      @foreach ($subMenus as $sub)
        <li>
          <a
            href="{{ route($sub['route']) }}"
            class="block px-6 py-2 text-xs transition-colors
                   {{ request()->routeIs($sub['route'])
                       ? 'font-semibold text-orange-500 bg-white'
                       : 'text-gray-700 hover:text-orange-500 hover:bg-white' }}"
          >
            - {{ $sub['name'] }}
          </a>
        </li>
      @endforeach
    </ul>
  </div>
</li>
