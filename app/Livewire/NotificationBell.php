<?php

namespace App\Livewire;

use App\Models\Pedido;
use Livewire\Attributes\On;
use Livewire\Component;

class NotificationBell extends Component
{

    public int $count = 0;

    // #[On('echo:orders,order.created')]
    #[On('pedidoActualizado')]
    public function handleNewOrder()
    {
        $this->incrementCount();
    }

    public function incrementCount()
    {
        $this->count++;
    }

    public function viewNotification()
    {

        return $this->redirectRoute('usuarios.notificaciones');
    }

    public function render()
    {
        return view('livewire.notification-bell');
    }
}
