<?php

namespace App\Livewire\Backoffice\Users;

use App\Models\EstadoPedidoLog;
use App\Models\Pedido;
use Illuminate\Support\Collection;
use Livewire\Attributes\On;
use Livewire\Component;

class NotificacionsList extends Component
{
    public array $notifications = [];

    public function mount()
    {
        $this->notifications = EstadoPedidoLog::whereDate('cambiado_en', today())
            ->orderBy('cambiado_en', 'desc')
            ->get()
            ->map(fn($log) => (object)[
                'type'       => 'order',
                'title'      => "Pedido #{$log->pedido_id}",
                'subtitle'   => "Estado: {$log->estado_nuevo}",
                'body'       => "El estado del pedido cambió de '{$log->estado_anterior}' a '{$log->estado_nuevo}'.",
                'created_at' => $log->cambiado_en,
            ])
            ->toArray();
    }

    // Cuando llegue el broadcast 'pedido.actualizado'…
    #[On('pedidoActualizado')]
    public function handlePedidoActualizado($pedido)
    {
        $p = $pedido['pedido'];

        $note = (object)[
            'type'       => 'order',
            'title'      => "Pedido #{$p['id']}",
            'subtitle'   => "Estado: {$p['estado']}",
            'body'       => "El pedido cambió a '{$p['estado']}'.",
            'created_at' => now(),
        ];

        array_unshift($this->notifications, $note);
    }
    

        public function render()
        {
            return view('livewire.backoffice.users.notificacions-list', [
                'groups' => ['Hoy' => $this->notifications],
            ]);
        }
}
