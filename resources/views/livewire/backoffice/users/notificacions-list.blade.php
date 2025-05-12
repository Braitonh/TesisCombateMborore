<div class="space-y-6">

  <h1 class="text-gray-500 text-2xl font-bold">Notificaciones</h1>

  {{-- Contenedor general con scroll --}}
  <div class="border rounded-lg overflow-hidden">

    {{-- Aquí definimos la zona desplazable --}}
    <div class="max-h-[40rem] overflow-y-auto">
      @foreach($groups as $label => $items)
        <div class="px-6 py-4 bg-gray-50 font-semibold text-gray-700 border-b">
          {{ $label }}
        </div>

        <div class="relative">
          @foreach($items as $i => $note)
            <div class="flex items-start px-6 py-4 relative border-b">
              {{-- Icono --}}
              <div class="flex-shrink-0 w-10 h-10 rounded-full bg-orange-500 flex items-center justify-center text-white">
                @if($note->type === 'order')
                  <i class="fas fa-utensils"></i>
                @elseif($note->type === 'review')
                  <i class="fas fa-star"></i>
                @else
                  <i class="fas fa-bell"></i>
                @endif
              </div>
              {{-- Contenido --}}
              <div class="ml-4 flex-1">
                <h3 class="font-semibold text-gray-900">{{ $note->title }}</h3>
                <p class="text-sm text-gray-600">
                  <strong class="font-medium">{{ $note->subtitle }}</strong>
                </p>
                <p class="text-sm text-gray-500 mt-1">{{ $note->body }}</p>
              </div>
              {{-- Fecha exacta --}}
              <div class="ml-4 text-xs text-gray-400 whitespace-nowrap">
                {{ \Carbon\Carbon::parse($note->created_at)->format('h:i A') }}
              </div>
            </div>
          @endforeach
        </div>
      @endforeach
    </div>

  </div>

</div>
