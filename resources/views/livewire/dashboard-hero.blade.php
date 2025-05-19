<div>
    <!-- Navbar -->
    <nav class="bg-white shadow-md sticky top-0 z-50">
        <div class=" px-2 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center space-x-2">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-8 w-8">
                    <span class="font-bold text-xl text-[#4C1300]">ThaiFood</span>
                </div>

                <!-- Links -->
                <div class="flex items-center space-x-6">
                    <a href="#inicio" class="text-gray-700 hover:text-orange-500 font-medium">Registrarse</a>
                    <a href="#ofertas" class="text-gray-700 hover:text-orange-500 font-medium">Iniciar sesión</a>
                    <div class="relative bg-red-100 p-2 rounded-lg cursor-pointer" wire:click="toggleCartModal">
                        <i class="fa-solid fa-cart-shopping text-red-500 text-2xl animate-wiggle"></i>
{{--                        <div class="px-1 py-0.5 bg-red-500 min-w-5 rounded-full text-center text-white text-xs absolute -top-2 -end-1 translate-x-1/4 text-nowrap">--}}
{{--                            <div @class(['absolute top-0 start-0 rounded-full -z-10 bg-red-200 w-full h-full' , 'animate-ping' => count($shoppingCart) > 0])></div>--}}
{{--                            {{count($shoppingCart)}}--}}
{{--                        </div>--}}
                    </div>
                </div>

                <!-- Botón para móviles (si querés usar Alpine u otro JS para menú móvil) -->
{{--                <div class="md:hidden">--}}
{{--                    <button class="text-gray-700 focus:outline-none">--}}
{{--                        <!-- Icono hamburguesa -->--}}
{{--                        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2"--}}
{{--                             viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">--}}
{{--                            <path d="M4 6h16M4 12h16M4 18h16"/>--}}
{{--                        </svg>--}}
{{--                    </button>--}}
{{--                </div>--}}
            </div>
        </div>
    </nav>
    <div class="space-y-6 p-4 ">

        {{-- Hero Principal --}}
        <div class="relative bg-gradient-to-r from-[#4C1300] to-[#800000] text-white rounded-xl overflow-hidden shadow-lg">
            {{-- Contenido principal --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 items-center gap-6 p-8 lg:p-12">
                {{-- Texto --}}
                <div class="space-y-4">
                    <div class="text-orange-400 font-bold text-xl">Delicious</div>
                    <h1 class="text-5xl font-extrabold leading-tight">
                        <span class="text-yellow-400">Thailand</span> Cuisine
                    </h1>
                    <span class="text-sm">is simpllarised in the 1g Lorem Ipsum passages, and more recently with deskto.</span>
                    <span class="text-sm">is simpllarised in the 1g Lorem Ipsum passages, and more recently with deskto.</span>
                    <span class="text-sm">is simpllarised in the 1g Lorem Ipsum passages, and more recently with deskto.</span>


                    {{-- Horarios --}}
                    <div class="flex flex-wrap items-center gap-2 mt-6">
                        <span class="bg-white text-[#4C1300] font-bold px-4 py-1 rounded-full text-sm">Horarios</span>
                        <span class="text-sm">Everyday 8AM – 22PM</span>
                    </div>
                </div>

                {{-- Imagen del plato --}}
                <div class="flex justify-end relative">
                    <img src="{{ asset('images/img.png') }}" alt="Indian dish"
                         class="h-[20rem] rounded-lg border-4 border-orange-200 shadow-lg" />
                    {{-- Adorno decorativo (hojas, especias...) puedes añadir con SVG o PNG extra --}}
                </div>
            </div>


        </div>

        {{-- Ofertas --}}
        <div class="flex justify-between items-start gap-6 px-4 w-full">
            <!-- Imagen izquierda -->
            <img src="{{ asset('images/img_2.png') }}" alt="..." class="my-auto object-contain">

            <!-- Contenedor principal de productos -->
            <div class="flex flex-col bg-white rounded-xl shadow-lg p-6 w-fit">
                <div class="flex justify-center mb-4">
                    <h3 class="font-semibold text-2xl text-center">Combos Ofertas</h3>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-4 gap-6">
                    @foreach ($productosOfertas as $producto)
                        <div class="rounded-lg shadow-md bg-white overflow-hidden flex flex-col h-full w-48">
                            <img src="{{ asset($producto->imagen ?? 'https://source.unsplash.com/random/300x300') }}"
                                 alt="Imagen producto" class="object-cover w-full h-50">

                            <div class="flex flex-col justify-between flex-1 p-3 text-sm">
                                <div>
                                    <h2 class="text-base font-bold text-gray-800">{{ $producto->nombre }}</h2>
                                    <p class="text-gray-600 mt-1">{{ $producto->descripcion }}</p>
                                </div>

                                <button
                                    wire:click="addToCard({{ $producto }})"
                                    type="button"
                                    class="mt-4 w-full bg-orange-500 hover:bg-orange-600 text-white font-medium py-1.5 rounded">
                                    Agregar al carrito
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Imagen derecha -->
            <img src="{{ asset('images/img_2.png') }}" alt="..." class="my-auto object-contain">
        </div>
        {{-- Destacados --}}

        <div class="flex flex-col justify-between items-center bg-white rounded-xl shadow-lg p-6">
            <h3 class="font-semibold text-xl mb-4">Todos los productos</h3>
            <div class="flex gap-4 mb-6">
                <!-- Pizzas -->
                <button wire:click="$set('filtroCategoria', 'pizzas')"
                        class="flex flex-col items-center rounded-lg shadow-md p-4 hover:shadow-xl
                {{ $filtroCategoria === 'pizzas' ? 'ring-2 ring-red-400' : 'bg-white' }}">
                    <img src="{{ asset('images/icono_pizza.svg') }}" class="h-10 mb-2">
                    Pizzas
                </button>

                <!-- Hamburguesas -->
                <button wire:click="$set('filtroCategoria', 'hamburguesas')"
                        class="flex flex-col items-center rounded-lg shadow-md p-4 hover:shadow-xl
                {{ $filtroCategoria === 'hamburguesas' ? ' ring-2 ring-red-400' : 'bg-white' }}">

                    <img src="{{ asset('images/icono_hamburguesa.svg') }}" class="h-10 mb-2">
                    Hamburguesas
                </button>

                <!-- Bebidas -->
                <button wire:click="$set('filtroCategoria', 'bebidas')"
                        class="flex flex-col items-center rounded-lg shadow-md p-4 hover:shadow-xl
                {{ $filtroCategoria === 'bebidas' ? 'ring-2 ring-red-400' : 'bg-white' }}">
                    <img src="{{ asset('images/icono_cafe.svg') }}" class="h-10 mb-2">
                    Bebidas
                </button>
            </div>

            <div class="grid grid-cols-8 gap-6">
                @foreach ($productos as $producto)
                    <div class="rounded-lg shadow-md bg-white overflow-hidden flex flex-col h-full w-48 mx-auto">
                        <img src="{{ asset($producto->imagen ?? 'https://source.unsplash.com/random/300x300') }}"
                             alt="Imagen producto" class="object-cover w-full h-50">

                        <div class="flex flex-col justify-between flex-1 p-3 text-sm">
                            <div>
                                <h2 class="text-base font-bold text-gray-800">{{ $producto->nombre }}</h2>
                                <p class="text-gray-600 mt-1">{{ $producto->descripcion }}</p>
                            </div>

                            <button
                                wire:click="addToCard({{$producto}})"
                                type="button"
                                class="mt-4 w-full bg-orange-500 hover:bg-orange-600 text-white font-medium py-1.5 rounded">
                                Agregar al carrito
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="flex justify-center mt-4">
                {{ $productos->links('pagination::custom') }}
            </div>
        </div>
        <!-- Footer -->
        <footer class="bg-white py-6 mt-8">
            <div class="max-w-7xl mx-auto px-6 text-center text-gray-600">
                © 2025 Nombre del Local. Todos los derechos reservados.
            </div>
        </footer>
    </div>

</div>
