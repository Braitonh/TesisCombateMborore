<div class="container mx-auto">
    <div class="flex mb-6">
        <a href="{{ route('usuarios.index') }}" class="flex items-center text-gray-700 hover:text-gray-900">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="currentColor" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M15 8a.75.75 0 01-.75.75H4.56l3.22 3.22a.75.75 0 11-1.06 1.06l-4.5-4.5a.75.75 0 010-1.06l4.5-4.5a.75.75 0 111.06 1.06L4.56 7.25H14.25A.75.75 0 0115 8z" clip-rule="evenodd"/>
            </svg>
        </a>
        <h1 class="text-2xl font-bold">
            {{ $usuario ? 'Editar usuario' : 'Crear usuario' }}
        </h1>
    </div>

    <form wire:submit="save" class="bg-white p-6 rounded shadow-md space-y-4">
        <div>
            <label class="block text-gray-700">Nombre</label>
            <input type="text" wire:model="name" class="w-full px-4 py-2 border rounded-md">
            @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-gray-700">Email</label>
            <input type="email" wire:model="email" class="w-full px-4 py-2 border rounded-md">
            @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <div>
            <label class="block text-gray-700">Password</label>
            <div x-data="{ show: false }" class="relative">
                <input 
                    :type="show ? 'text' : 'password'" 
                    wire:model="password"
                    class="w-full px-4 py-2 border rounded-md pr-10"
                >
                <button type="button" x-on:click="show = !show" class="absolute right-2 top-2 text-gray-600 hover:text-gray-800">
                    <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    <svg x-show="show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.963 9.963 0 012.293-3.95m1.664-1.664A9.953 9.953 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.966 9.966 0 01-4.293 5.746M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 3l18 18"/>
                    </svg>
                </button>
            </div>
            @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

        </div>
        @unless($usuario)
            <div>
                <label class="block text-gray-700">Confirmar password</label>
                <div x-data="{ show: false }" class="relative">
                    <input 
                        :type="show ? 'text' : 'password'" 
                        wire:model="password_confirmation"
                        class="w-full px-4 py-2 border rounded-md pr-10"
                    >
                    <button type="button" x-on:click="show = !show" class="absolute right-2 top-2 text-gray-600 hover:text-gray-800">
                        <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        <svg x-show="show" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.963 9.963 0 012.293-3.95m1.664-1.664A9.953 9.953 0 0112 5c4.477 0 8.268 2.943 9.542 7a9.966 9.966 0 01-4.293 5.746M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 3l18 18"/>
                        </svg>
                    </button>
                </div>
                @error('password_confirmation') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
        @endunless

        <div>
            <label class="block text-gray-700">Rol</label>
            <select wire:model="rol" class="w-full px-4 py-2 border rounded-md">
                <option value="">Seleccionar rol</option>
                <option value="admin">Administrador</option>
                <option value="user">Cocinero</option>
            </select>
            @error('rol') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="text-right">
            <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-md hover:bg-blue-600">
                {{ $usuario ? 'Actualizar' : 'Guardar' }} usuario
            </button>
        </div>
    </form>

    <x-spinner />
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        initMenuItem('#accordion-collapsemenu-usuarios', '#usuarios');
    });
</script>

