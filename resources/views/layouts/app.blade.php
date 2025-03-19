<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    @livewireStyles
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>

</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar fijo -->
        <aside class="w-64 bg-white p-5 flex flex-col sticky top-0 h-screen">
            <h2 class="text-xl font-bold mb-5">📊 TailAdmin</h2>

            <nav>
                <ul class="list-none">
                    <x-menu-with-sub-menu 
                        id="menu-dashboard" 
                        icon="fa-solid fa-house"
                        name="Dashboard" 
                        :subMenus="[
                            ['id' => 'sub1', 'route' => 'welcome', 'name' => 'Inicio'],
                            ['id' => 'sub2', 'route' => 'welcome', 'name' => 'Perfil', 'role' => 'admin']
                        ]"
                    />
                    <x-menu-with-sub-menu 
                        id="menu-productos" 
                        icon="fas fa-home" 
                        name="Productos" 
                        :subMenus="[
                            ['id' => 'sub1', 'route' => 'welcome', 'name' => 'Lista de Productos'],
                            ['id' => 'sub2', 'route' => 'welcome', 'name' => 'Perfil', 'role' => 'admin']
                        ]"
                    />
                </ul>
            </nav>
            <div class="mt-auto">
                <a href="#" class="block py-2 px-4 mt-5 bg-red-600 text-center rounded">Logout</a>
            </div>
        </aside>
        
        <!-- Contenido desplazable -->
        <div class="flex-1 flex flex-col overflow-y-auto">
            <!-- Navbar -->
            <nav class="bg-white shadow p-4 flex justify-between items-center">
                <input type="text" placeholder="Buscar..." class="px-4 py-2 border rounded w-1/3">
                <div class="flex items-center space-x-4">
                    <span class="text-gray-600">Thomas Arree</span>
                    <img src="https://via.placeholder.com/40" class="rounded-full w-10 h-10" alt="User">
                </div>
            </nav>

            <!-- Contenido principal con desplazamiento -->
            <main class="p-6">
                @yield('content')
            </main>
        </div>
    </div>
    @livewireScripts

</body>

</html>