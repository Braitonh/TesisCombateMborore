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
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar fijo -->
        <aside class="w-64 bg-white p-5 flex flex-col sticky top-0 h-screen">
            <h2 class="text-xl font-bold mb-5">📊 TailAdmin</h2>

            <nav>
                <ul class="list-none">

                    <x-menu-with-sub-menu 
                        id="menu-productos" 
                        icon="fa-solid fa-burger" 
                        name="Productos" 
                        :subMenus="[
                            ['id' => 'productos', 'route' => 'productos.index', 'name' => 'Lista de productos'],
                        ]"
                    />
                    <x-menu-with-sub-menu 
                    id="menu-pedidos" 
                    icon="fa-solid fa-cart-shopping"
                    name="Pedidos" 
                    :subMenus="[
                            ['id' => 'pedidos', 'route' => 'pedidos.index', 'name' => 'Lista de pedidos'],
                            ['id' => 'detalle', 'route' => 'pedidos.detalle', 'name' => 'Detalle de pedidos'],
                        ]"
                    />
                    <x-menu-with-sub-menu 
                    id="menu-usuarios" 
                    icon="fa-solid fa-user"
                    name="Usuarios" 
                    :subMenus="[
                            ['id' => 'usuarios', 'route' => 'usuarios.index', 'name' => 'Lista de usuarios'],
                        ]"
                    />
                    <x-menu-with-sub-menu 
                    id="menu-clientes" 
                    icon="fa-solid fa-users"
                    name="Clientes" 
                    :subMenus="[
                            ['id' => 'clientes', 'route' => 'clientes.index', 'name' => 'Lista de clientes'],
                        ]"
                    />
                </ul>
            </nav>
            <div class="mt-auto">
                <form action="{{ route('logout') }}" method="POST" class="w-full">
                    @csrf
                    <button type="submit"
                            class="block w-full py-2 px-4 mt-5 bg-red-600 text-center rounded text-white hover:bg-red-700 transition">
                        Logout
                    </button>
                </form>
            </div>
        </aside>
        
        <!-- Contenido desplazable -->
        <div class="flex-1 flex flex-col overflow-y-auto">
            <!-- Navbar -->
            <nav class="bg-white shadow p-4 flex justify-between items-center">
                <input type="text" placeholder="Buscar..." class="px-4 py-2 border rounded w-1/3">
                <div class="flex items-center space-x-4">
                    <span class="text-gray-600">Brainton hemsouvanh</span>
                    {{-- <img src="https://via.placeholder.com/40" class="rounded-full w-10 h-10" alt="User"> --}}
                </div>
            </nav>

            <!-- Contenido principal con desplazamiento -->
            <main class="p-4">
                {{ $slot }}
            </main>

        </div>
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

