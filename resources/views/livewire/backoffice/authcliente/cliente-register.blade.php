<div class="flex justify-center items-center min-h-screen bg-white">
    <div class="flex w-full max-w-5xl shadow-lg rounded-lg overflow-hidden">

        <!-- Imagen -->
        <div class="w-1/2 bg-[#ea7321] flex items-center justify-center p-10">
            <img src="{{ asset('images/logo.png') }}" alt="Comida Rápida" class="max-h-[400px] w-auto">
        </div>

        <!-- Formulario de Registro -->
        <div class="w-1/2 bg-white p-10">
            <div class="flex items-center space-x-2 mb-6">
                <div class="text-3xl">🍔</div>
                <h1 class="text-2xl font-bold text-gray-800">Bienvenido</h1>
            </div>
            <h2 class="text-xl font-semibold mb-6">Crear una cuenta</h2>

            <form wire:submit.prevent="register" class="space-y-4">
                <div>
                    <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre completo</label>
                    <input wire:model="nombre" id="nombre" type="text" required class="p-2 mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500 sm:text-sm" placeholder="Juan Pérez">
                    @error('nombre') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Correo electrónico</label>
                    <input wire:model="email" id="email" type="email" required class="p-2 mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500 sm:text-sm" placeholder="correo@ejemplo.com">
                    @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Contraseña</label>
                    <input wire:model="password" id="password" type="password" required class="p-2 mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500 sm:text-sm" placeholder="••••••••">
                    @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmar contraseña</label>
                    <input wire:model="password_confirmation" id="password_confirmation" type="password" required class="p-2 mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500 sm:text-sm" placeholder="••••••••">
                </div>

                <div>
                    <label for="telefono" class="block text-sm font-medium text-gray-700">Teléfono</label>
                    <input wire:model="telefono" id="telefono" type="text" required class="p-2 mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500 sm:text-sm" placeholder="+52 123 456 7890">
                    @error('telefono') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="direccion" class="block text-sm font-medium text-gray-700">Dirección</label>
                    <input wire:model="direccion" id="direccion" type="text" required class="p-2 mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500 sm:text-sm" placeholder="Calle 123, Ciudad">
                    @error('direccion') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>


                <button type="submit" class="w-full bg-orange-500 hover:bg-orange-600 text-white py-2 px-4 rounded-md text-sm font-semibold transition duration-150">
                    Registrarse
                </button>

                <div class="text-center text-sm mt-4">
                    ¿Ya tienes una cuenta? <a href="{{ route('authClienteLogin') }}" class="text-orange-600 hover:underline">Inicia sesión</a>
                </div>
            </form>
        </div>
    </div>
</div>
