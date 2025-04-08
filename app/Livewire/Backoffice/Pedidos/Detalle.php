<?php

namespace App\Livewire\Backoffice\Pedidos;

use App\Models\Pedido;
use Livewire\Component;

class Detalle extends Component
{
    public $pedidos;

    public function mount()
    {
        $this->pedidos = Pedido::with(['cliente', 'productos'])
        ->orderBy('id', 'desc')
        ->get();
    }


    
    public function render()
    {
        return view('livewire.backoffice.pedidos.detalle',[
            'pedidos' => $this->pedidos
        ]);
    }
}
