<div class="flex space-x-6 p-6">

    {{-- IZQUIERDA: Tabs + Lista --}}
    <div class="w-1/3 bg-white rounded shadow p-4">
      {{-- Tabs --}}
      <div class="flex mb-4 border-b bg-gray-100 rounded-lg">
        @foreach(['Recibido'=>'Recibido','Elaboracion'=>'Elaboracion','Delivery'=>'Delivery', 'Completado' => 'Completado'] as $key => $label)
          <button
            wire:click="setStatus('{{ $key }}')"
            class="rounded-lg flex-1 py-2 text-center 
                   {{ $status === $key ? 'bg-orange-500 text-white' : 'text-gray-600' }}">
            {{ $label }}
          </button>
        @endforeach
      </div>
  
      {{-- Lista de órdenes --}}
      <div class="space-y-3">
        @forelse($this->orders as $order)
          <div 
            wire:click="selectOrder({{ $order->id }})"
            class="cursor-pointer flex justify-between items-center 
                   p-3 border rounded hover:bg-gray-50
                   {{ $selectedOrderId === $order->id ? 'border-1 border-orange-400' : '' }}">
            <div>
              <h4 class="font-semibold">Order #{{ $order->id }}</h4>
              <p class="text-sm text-gray-500">{{ $order->updated_at->format('d M, Y H:i') }}</p>
            </div>
            <div class="text-orange-500 font-semibold">${{ number_format($order->total,2) }}</div>
          </div>
        @empty
          <p class="text-center text-gray-400">No hay pedidos</p>
        @endforelse
      </div>
    </div>

    
    {{-- PANEL DERECHO --}}
    <div class="flex-1 bg-white rounded shadow p-6">
        @php
          /** @var \App\Models\Pedido $o */
          $o = $this->selectedOrder;
        @endphp
    
        @if($o)
          {{-- Encabezado --}}
          <h2 class="text-2xl font-bold mb-4">Detalles del Pedido</h2>
          <div class="flex justify-between items-center">
            <div>
              <h3 class="font-semibold">Pedido #{{ $o->id }}</h3>
              {{-- formatea la fecha --}}
              <p class="text-sm text-gray-500">
                {{ \Carbon\Carbon::parse($o->updated_at)->format('d M, Y H:i') }}
              </p>
            </div>
            @if ($o->estado === 'Recibido')
                <div class="flex items-center space-x-4">
                    <button wire:click="acceptOrder"
                            class="px-4 py-2 bg-orange-500 text-white rounded">
                    Confirmar
                    </button>
                </div>
            @endif

          </div>
    
          {{-- DIRECCIÓN --}}
          <div class="mt-6 border-t pt-4 flex">
            <div class="w-1/3">
              <p class="font-semibold">Dirección</p>
              {{-- usa la relación cliente --}}
              <p class="text-gray-600 flex items-center">
                <span class="material-icons text-orange-500 mr-1">place</span>
                {{ $o->cliente->direccion }}
              </p>
              {{-- si tuvieras notas en la tabla pedidos, por ejemplo columna 'notas' --}}
              @if(isset($o->notas))
                <p class="text-sm text-gray-500">{{ $o->notas }}</p>
              @endif
            </div>
    
            {{-- TIEMPO ESTIMADO --}}
            <div class="w-1/3">
              <p class="font-semibold">Repartidor</p>
              <p class="text-gray-600">{{$o->productos[0]->nombre }}</p>
            </div>
    
            {{-- MÉTODO DE PAGO --}}
            <div class="w-1/3">
              <p class="font-semibold">Pago</p>
              {{-- columna 'metodo_pago' en pedidos --}}
              <p class="text-gray-600">Efectivo</p>
            </div>
          </div>
    
          {{-- LISTA DE PRODUCTOS --}}
          <div class="mt-6">
            <p class="font-semibold mb-2">Productos</p>
            <ul class="space-y-4">
              @foreach($o->productos as $item)
                {{-- $item es App\Models\Producto, los campos de pivot quedan en $item->pivot --}}
                <li class="flex items-center justify-between">
                  <div class="flex items-center space-x-3">
                    {{-- ruta a la imagen --}}
                    <img src="{{ asset($item->imagen) }}"
                         class="w-20 h-20 object-cover rounded border" />
                    <div>
                      <p class="font-medium">{{ $item->nombre }}</p>
                      {{-- cantidad desde pivot --}}
                      <p class="text-sm text-gray-500">
                        unidades: {{ $item->pivot->cantidad }}
                      </p>
                    </div>
                  </div>
                  {{-- subtotal desde pivot --}}
                  <div class="text-orange-500 font-semibold">
                    +${{ number_format($item->pivot->subtotal, 2) }}
                  </div>
                </li>
              @endforeach
            </ul>
          </div>
    
          {{-- TOTAL --}}
          <div class="mt-6 flex justify-end">
            <p class="text-xl font-bold">
              Total: ${{ number_format($o->total, 2) }}
            </p>
          </div>
    
        @else
          <p class="text-center text-gray-400">
            Selecciona un pedido a la izquierda
          </p>
        @endif
      </div>

  </div>
  