<div class="flex justify-center items-center min-h-screen bg-white">
    <div class="flex w-full max-w-5xl shadow-lg rounded-lg overflow-hidden">

        <!-- Imagen -->
        <div class="w-1/2 bg-[#ea7321] flex items-center justify-center p-10">
            <img src="{{ asset('images/logo.png') }}" alt="Comida Rápida" class="max-h-[400px] w-auto">
        </div>

        <!-- Formulario -->
        <div class="w-1/2 bg-white p-10">
            <div class="flex items-center space-x-2 mb-6">
                <div class="text-3xl">🍔</div>
                <h1 class="text-2xl font-bold text-gray-800">Bienvenido</h1>
            </div>
            <h2 class="text-xl font-semibold mb-6">Iniciar sesión</h2>

            <form wire:submit="login" class="space-y-4">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Correo electrónico</label>
                    <input wire:model="email" id="email" type="email" required class="p-2 mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500 sm:text-sm" placeholder="admin@example.com">
                    @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Contraseña</label>
                    <input wire:model="password" id="password" type="password" required class="p-2 mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500 sm:text-sm" placeholder="••••••••">
                    @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>



                <button type="submit" class="w-full bg-orange-500 hover:bg-orange-600 text-white py-2 px-4 rounded-md text-sm font-semibold transition duration-150">
                    Iniciar sesión
                </button>

                <div class="text-center text-sm mt-2">
                    <a  href="{{ route('authClienteLogin') }}" class="text-orange-600 hover:underline">¿Olvidaste tu contraseña?</a>
                </div>

                <div class="text-center text-sm mt-4">
                    ¿NO tienes una cuenta? <a href="{{ route('authClienteRegister') }}" class="text-orange-600 hover:underline">Registrate</a>
                </div>
            </form>
        </div>
    </div>
</div>
