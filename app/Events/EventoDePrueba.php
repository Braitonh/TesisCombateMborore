<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EventoDePrueba implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public string $mensaje
    ) {}

    public function broadcastOn()
    {
        return new Channel('canal-prueba');
    }

    public function broadcastAs()
    {
        return 'evento.prueba';
    }

    public function broadcastWith()
    {
        return ['mensaje' => $this->mensaje];
    }
}
