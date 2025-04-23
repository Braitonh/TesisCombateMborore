<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order;


    /**
     * Create a new event instance.
     */
    public function __construct($order)
    {
        $this->order = $order;
    }
    
    public function broadcastOn()
    {
        return new Channel('orders');
    }

    public function broadcastWith()
    {
        return ['order' => $this->order];
    }

    // Nombre del evento en JS (por defecto será "OrderCreated")
    public function broadcastAs(): string
    {
        return 'order.created';
    }
}
