

<div class="overflow-x-auto bg-white rounded-lg shadow" x-data>
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="w-4"></th> <!-- Ícono ☰ -->
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rol</th>
                <th class="py-3 mr-6 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
            </tr>
        </thead>
        
        <tbody id="usuarios-table" class="bg-white divide-y divide-gray-200">
            @foreach ($usuarios as $usuario)
            <tr class="hover:bg-gray-50 cursor-move select-none" data-id="{{ $usuario->id }}" wire:key="usuario-{{ $usuario->id }}">
                <td class="px-4 text-gray-400 drag-handle">☰</td> <!-- Nuevo handle -->
                <td class="px-6 py-4 text-sm text-gray-900">{{ $usuario->name }}</td>
                <td class="px-6 py-4 text-sm text-gray-900">{{ $usuario->email }}</td>
                <td class="px-6 py-4 text-sm text-gray-900">{{ $usuario->rol }}</td>
                <td>
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('usuarios.edit', $usuario->id) }}" class="text-blue-600 hover:text-blue-900">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </a>
                        <button wire:click="confirmDelete({{ $usuario->id }})" class="text-red-600 hover:text-red-900">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>
                </td>
            </tr>

            @endforeach
        </tbody>
    </table>
</div>
<div class="flex justify-center mt-4">
    {{ $usuarios->links('pagination::custom') }}
</div>
<div wire:loading wire:target="actualizarOrden" >
    <x-spinner />
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
            const table = document.getElementById('usuarios-table');
            if (!table) return;

            if (!table.classList.contains('sortable-applied')) {
                new Sortable(table, {
                    animation: 150,
                    ghostClass: 'bg-blue-100',
                    onEnd: function (evt) {
                        const orden = Array.from(evt.to.children).map(row => row.dataset.id);
                        @this.call('actualizarOrden', orden);
                    },
                });

                table.classList.add('sortable-applied');
            }
    });
</script>

