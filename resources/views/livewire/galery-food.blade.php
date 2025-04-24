<div class="bg-gray-100 min-h-screen">
    <div class="max-w-7xl mx-auto p-6 grid grid-cols-1 lg:grid-cols-3 gap-6">
  
      <!-- Sección principal -->
      <div class="lg:col-span-2 space-y-8">
  
        <!-- Banner Promocional -->
        <div class="bg-cover bg-center rounded-lg p-8" style="background-image: url('{{ asset('images/banner.png') }}');">
          <h2 class="text-3xl font-bold text-white">Promoción del Mes</h2>
          <p class="text-white">¡Disfruta de descuentos exclusivos!</p>
          <button class="mt-4 px-4 py-2 bg-red-500 text-white rounded">Ordenar ahora</button>
        </div>
  
        <!-- Categorías -->
        <div>
          <h3 class="font-semibold text-xl mb-4">Categorías</h3>
          <div class="flex gap-4">
            <button class="flex flex-col items-center bg-white rounded-lg shadow-md p-4 hover:shadow-xl">
              <img src="{{ asset('images/icono_pizza.svg') }}" class="h-10 mb-2">
              Pizzas
            </button>
            <button class="flex flex-col items-center bg-white rounded-lg shadow-md p-4 hover:shadow-xl">
              <img src="{{ asset('images/icono_hamburguesa.svg') }}" class="h-10 mb-2">
              Hamburguesas
            </button>
            <button class="flex flex-col items-center bg-white rounded-lg shadow-md p-4 hover:shadow-xl">
              <img src="{{ asset('images/icono_cafe.svg') }}" class="h-10 mb-2">
              Bebidas
            </button>
          </div>
        </div>
  
        <!-- Tarjetas de Productos -->
        <div>
            <div class="flex justify-between items-center mb-4">
              <h3 class="font-semibold text-xl">Productos destacados</h3>
              <div class="relative bg-blue-100 p-2 rounded-lg cursor-pointer" wire:click="toggleCartModal">
                <i class="fa-solid fa-cart-shopping text-blue-500 text-2xl animate-wiggle"></i>
                <div class="px-1 py-0.5 bg-blue-500 min-w-5 rounded-full text-center text-white text-xs absolute -top-2 -end-1 translate-x-1/4 text-nowrap">
                  <div @class(['absolute top-0 start-0 rounded-full -z-10 bg-blue-200 w-full h-full'])></div>
                  0
                </div>
              </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
              @foreach ($productos as $producto)
                @include('backoffice.pedidos.cardPedido', ['producto' => $producto])
              @endforeach
            </div>
          </div>
        </div>

      <div class="lg:col-span-1 flex flex-col gap-6">
        <!-- 1. Reseñas -->
        <div class="bg-white rounded-xl shadow-lg p-4 h-fit">
          <h3 class="font-semibold text-xl mb-4">Reseñas recientes</h3>
          <ul class="space-y-4">
            <li class="bg-gray-50 p-4 rounded-lg border-2 flex items-center justify-between">
                <div>
                <p class="font-semibold">Theresa Webb</p>
                <p class="text-sm text-gray-600">Mongolian beef: Delicioso y sabroso.</p>
                </div>
                <button>
                <svg class="h-6 w-6 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                </button>
            </li>
            <li class="bg-gray-50 p-4 rounded-lg border-2 flex items-center justify-between">
                <div>
                <p class="font-semibold">Theresa Webb</p>
                <p class="text-sm text-gray-600">Mongolian beef: Delicioso y sabroso.</p>
                </div>
                <button>
                <svg class="h-6 w-6 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                </button>
            </li>
            <li class="bg-gray-50 p-4 rounded-lg border-2 flex items-center justify-between">
                <div>
                <p class="font-semibold">Theresa Webb</p>
                <p class="text-sm text-gray-600">Mongolian beef: Delicioso y sabroso.</p>
                </div>
                <button>
                <svg class="h-6 w-6 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                </button>
            </li>
            <li class="bg-gray-50 p-4 rounded-lg border-2 flex items-center justify-between">
                <div>
                <p class="font-semibold">Theresa Webb</p>
                <p class="text-sm text-gray-600">Mongolian beef: Delicioso y sabroso.</p>
                </div>
                <button>
                <svg class="h-6 w-6 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                </button>
            </li>
          </ul>
        </div>
      
        <!-- 2. Resumen de compra -->
        {{-- <div class="bg-white rounded-xl shadow-lg p-4">
          <h3 class="font-semibold text-xl mb-4">Tu orden</h3>
          <ul class="divide-y divide-gray-200">
            <li class="py-2 flex justify-between">
              <span>Hamburguesa clásica</span>
              <span>$5.99</span>
            </li>
            <li class="py-2 flex justify-between">
                <span>Hamburguesa clásica</span>
                <span>$5.99</span>
            </li>
            <li class="py-2 flex justify-between">
            <span>Hamburguesa clásica</span>
            <span>$5.99</span>
            </li>
            <li class="py-2 flex justify-between">
            <span>Hamburguesa clásica</span>
            <span>$5.99</span>
            </li>
            <li class="py-2 flex justify-between">
            <span>Hamburguesa clásica</span>
            <span>$5.99</span>
            </li>            <li class="py-2 flex justify-between">
            <span>Hamburguesa clásica</span>
            <span>$5.99</span>
            </li>
            <li class="py-2 flex justify-between">
            <span>Hamburguesa clásica</span>
            <span>$5.99</span>
            </li>
            <li class="py-2 flex justify-between">
            <span>Hamburguesa clásica</span>
            <span>$5.99</span>
            </li>
            <!-- más ítems -->
          </ul>
          <div class="border-t pt-4 mt-4 flex justify-between font-semibold">
            <span>Total</span>
            <span>$14.98</span>
          </div>
          <button class="w-full mt-4 bg-blue-500 text-white py-2 rounded hover:bg-red-600">
            Pagar ahora
          </button>
        </div> --}}
      </div>

    </div>
      <!-- Footer -->
    <footer class="bg-white py-6 mt-8">
        <div class="max-w-7xl mx-auto px-6 text-center text-gray-600">
        © 2025 Nombre del Local. Todos los derechos reservados.
        </div>
    </footer>
  </div>
  