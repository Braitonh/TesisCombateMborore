<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://cdn.tailwindcss.com"></script>
        
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
        
        <style>
            body {
                font-family: 'Poppins', sans-serif;
            }
        </style>

        <title>{{ $title ?? 'Page Title' }}</title>
    </head>
    <body class="">
    {{-- HEADER FIJO ARRIBA --}}
    <header class="bg-orange-600 px-6 py-10 flex items-center justify-between -mt-5">
        <div class="flex items-center  ">
            <img src="{{ asset('images/logo.png') }}" alt="FastFoodApp Logo" class="h-16 w-auto rounded-full mr-2">
            <span class="text-white">Usuario: {{ auth()->user()->name }}</span>
        </div>

        <livewire:notification-bell />

    </header>

    {{-- CONTENIDO PRINCIPAL: SIDEBAR + MAIN --}}
    <div class="relative -mt-8 z-20 flex flex-1 overflow-hidden rounded-t-[2rem] bg-white">
        {{-- SIDEBAR --}}
        <aside class="w-64 border-r-2 p-5 flex flex-col sticky top-0 h-screen ">

            <nav>
                @php
                    $rol = auth()->user()->rol;
                @endphp

                <ul class="list-none">
                    @if ($rol !== 'user')
                        <x-menu-with-sub-menu 
                            id="menu-productos" 
                            icon="fa-solid fa-burger" 
                            name="Productos" 
                            :subMenus="[
                                ['id' => 'productos', 'route' => 'productos.index', 'name' => 'Lista de productos'],
                            ]"
                            :extraPatterns="['productos.*']"
                        />
                        <x-menu-with-sub-menu 
                            id="menu-pedidos" 
                            icon="fa-solid fa-cart-shopping"
                            name="Pedidos" 
                            :subMenus="[
                                    ['id' => 'pedidos', 'route' => 'pedidos.index', 'name' => 'Lista de pedidos'],
                                    ['id' => 'detalle', 'route' => 'pedidos.detalle', 'name' => 'Detalle de pedidos'],
                                    ['id' => 'estatus', 'route' => 'pedidos.estatus', 'name' => 'Estatus de pedidos'],
                                ]"
                            :extraPatterns="['pedidos.*']"

                        />
                        <x-menu-with-sub-menu 
                            id="menu-clientes" 
                            icon="fa-solid fa-users"
                            name="Clientes" 
                            :subMenus="[
                                    ['id' => 'clientes', 'route' => 'clientes.index', 'name' => 'Lista de clientes'],
                                ]"
                        />
                        <x-menu-with-sub-menu 
                            id="menu-usuarios" 
                            icon="fa-solid fa-user"
                            name="Usuarios" 
                            :subMenus="[
                                    ['id' => 'usuarios', 'route' => 'usuarios.index', 'name' => 'Lista de usuarios'],
                                    ['id' => 'notificaciones', 'route' => 'usuarios.notificaciones', 'name' => 'Notificaciones'],

                                ]"
                        />
                    @endif
                    @if ($rol !== 'admin')
                        <x-menu-with-sub-menu 
                        id="menu-pedidos" 
                        icon="fa-solid fa-cart-shopping"
                        name="Pedidos" 
                        :subMenus="[
                                ['id' => 'detalle', 'route' => 'pedidos.detalle', 'name' => 'Detalle de pedidos'],
                            ]"
                        />
                    @endif
        

                </ul>
            </nav>
            <div class="mt-auto">
                <form action="{{ route('logout') }}" method="POST" class="w-full ">
                    @csrf
                    <button type="submit"
                            class="block w-full py-2 px-4 mt-5 bg-red-600 text-center rounded text-white hover:bg-red-700 transition">
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        {{-- ÁREA DE CONTENIDO --}}
        <main class="flex-1 overflow-y-auto p-6">
        {{ $slot }}
        </main>
    </div>

    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/laravel-echo/1.11.1/echo.iife.js"></script>

    <script>
        window.Pusher = Pusher;

        window.Echo = new Echo({
            broadcaster: 'pusher',
            key: "app-key", // 👈 valor literal
            cluster: 'mt1',
            wsHost: window.location.hostname,
            wsPort: 6001,
            forceTLS: false,
            disableStats: true,
            enabledTransports: ['ws'],
        });
    </script>
    <script>
        Echo.channel('orders')
            .listen('.order.created', (e) => {
                console.log('[Echo] Evento recibido en JS:', e);
                Livewire.dispatch('echo:orders,order.created', e); // ⬅️ reenvía a Livewire
            });
    </script>
    <script>
        Echo.channel('notificaciones')
            .listen('.pedido.actualizado', (e) => {
                Livewire.dispatch('pedidoActualizado', {
                    pedido:   e,
                });
            });
    </script>
    </body>
</html>

<script>
    window.initMenuItem = function (checkboxSelector, itemSelector) {
        const checkbox = document.querySelector(checkboxSelector);
        const item = document.querySelector(itemSelector);

        if (checkbox) {
            checkbox.checked = true;

            const idSuffix = checkbox.id.replace('accordion-collapse', '');
            const svg = document.getElementById('svg-' + idSuffix);
            if (svg) {
                svg.classList.remove('rotate-180');
                svg.classList.add('rotate-0');
            }
        }

        if (item) {
            item.classList.add('font-semibold', 'text-gray-800');
        }
    };
</script>

