<?php

namespace App\Livewire;

use App\Models\Pedido;
use Livewire\Attributes\On;
use Livewire\Component;

class NotificationBell extends Component
{

    public int $count = 0;

        public function mount()
        {
            $this->count = Pedido::where('estado', 'Nuevo')
                ->whereDate('fecha', today())
                ->count();
        }

    #[On('echo:orders,order.created')]
    public function handleNewOrder()
    {
        $this->incrementCount();
    }

    public function incrementCount()
    {
        $pedidos = Pedido::where('estado', 'Nuevo')
        ->whereDate('fecha', today())
        ->count();

        if($pedidos >= 1){
            $this->count++;
        }
    }

    public function viewNotification()
    {
        $pedidos = Pedido::where('estado', 'Nuevo')
            ->whereDate('fecha', today())
            ->get();

        foreach($pedidos as $pedido){

            $pedido->estado = 'Sin confirmar';
            $pedido->save();
        }

        return $this->redirectRoute('pedidos.index');
    }

    public function render()
    {
        return view('livewire.notification-bell');
    }
}
