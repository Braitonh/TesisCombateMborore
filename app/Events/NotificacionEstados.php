<?php

namespace App\Events;

use App\Models\Pedido;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotificacionEstados implements ShouldBroadcastNow
{

    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Pedido $pedido;

    public function __construct(Pedido $pedido)
    {
        $this->pedido = $pedido;
    }

    public function broadcastOn()
    {
        return new Channel('notificaciones');
    }

    // ← Este método le dice a Echo bajo qué nombre emitir
    public function broadcastAs(): string
    {
        return 'pedido.actualizado';
    }

    public function broadcastWith(): array
    {
        return [
            'pedido' => $this->pedido
        ];
    }


}
