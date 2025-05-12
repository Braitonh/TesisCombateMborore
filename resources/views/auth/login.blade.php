<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://cdn.tailwindcss.com"></script>
        
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        
        <style>
            body { font-family: 'Poppins', sans-serif; }
        </style>

        <title>{{ $title ?? 'Iniciar Sesión' }}</title>
    </head>
<body class="bg-gray-100">
    <div class="flex h-screen items-center justify-center">
        <div class="w-full max-w-md p-6 bg-white bg-opacity-90 rounded-2xl shadow-lg backdrop-blur">
            <!-- Logo y Nombre de la App -->
            <div class="flex justify-center mb-4">
                <img src="{{ asset('images/fastfood-logo.png') }}" alt="FastFoodApp Logo" class="h-32 w-auto">
            </div>
            <h1 class="text-2xl font-semibold mb-1 text-center text-blue-600">{{ $title ?? 'FastFoodApp' }}</h1>
            <p class="text-center text-gray-600 mb-6">¡Bienvenido de nuevo!</p>

            @if($errors->any())
                <div class="mb-4 text-red-700 text-center">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-4 relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400"><i class="fas fa-envelope"></i></span>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                        class="w-full px-10 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Correo electrónico">
                </div>

                <div class="mb-4 relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400"><i class="fas fa-lock"></i></span>
                    <input type="password" name="password" required
                        class="w-full px-10 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Contraseña">
                </div>

                <button type="submit"
                    class="w-full py-2 bg-blue-500 text-white rounded hover:bg-blue-700 transition">Iniciar Sesión</button>
            </form>
        </div>
    </div>
</body>
</html>
