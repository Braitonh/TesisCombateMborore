<?php

namespace App\Livewire\Backoffice\Pedidos;

use App\Models\Pedido;
use Livewire\Attributes\On;
use Livewire\Component;

class Detalle extends Component
{
    public $pedidos;

    public function mount()
    {
        $this->cargarPedidos();
    }

    public function cargarPedidos()
    {
        $this->pedidos = Pedido::with(['cliente', 'productos'])
            ->where('estado', 'Elaboracion')
            ->whereDate('fecha', today())
            ->orderBy('id', 'desc')
            ->get();
    }

    #[On('echo:orders,order.created')]
    public function handleNewOrder()
    {
        // Recarga la lista completa desde la base de datos
        $this->cargarPedidos();

    }

    public function completarPedido($pedidoId)
    {
        $pedido = Pedido::findOrFail($pedidoId);
        $pedido->estado = "Delivery";
        $pedido->save();
        $this->cargarPedidos();

    }


    public function render()
    {
        return view('livewire.backoffice.pedidos.detalle', [
            'pedidos' => $this->pedidos
        ]);
    }
}
