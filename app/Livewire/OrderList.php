<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;

class OrderList extends Component
{

    public int $count = 0;

    #[On('echo:orders,order.created')]
    public function handleNewOrder(array $payload = [])
    {
        $this->count++;
        // si quisieras ver el detalle:
        // logger()->info('Nuevo pedido:', $payload['order']);
    }

    public function render()
    {
        return view('livewire.order-list', [
            'count' => $this->count,
        ]);
    }
}
