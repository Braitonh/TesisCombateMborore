<?php

namespace App\Livewire\Backoffice\Pedidos;

use App\Models\Pedido;
use Livewire\Component;

class PedidosEstatus extends Component
{

    public $status = 'Recibido';            // 'in', 'prepared', 'delivered'
    public $selectedOrderId = null;   // la orden que estamos viendo

    // Obtiene las órdenes según el tab activo
    public function getOrdersProperty()
    {
        return Pedido::with(['cliente', 'productos'])
            ->where('estado', $this->status)
            ->whereDate('fecha', today())
            ->orderBy('id', 'desc')
            ->get();
    }

    // Devuelve la orden seleccionada
    public function getSelectedOrderProperty()
    {
        return $this->selectedOrderId
            ? Pedido::with('productos','cliente')->find($this->selectedOrderId)
            : null;
    }

    // Cambiar de tab
    public function setStatus($newStatus)
    {
        $this->status = $newStatus;
        $this->selectedOrderId = null;
    }

    // Seleccionar una orden de la lista
    public function selectOrder($id)
    {
        $this->selectedOrderId = $id;
    }

    // Acción de aceptar orden
    public function acceptOrder()
    {
        if ($order = $this->selectedOrder) {
            $order->update(['estado' => 'Elaboracion']);
            $this->setStatus('Elaboracion');
        }
    }

    public function render()
    {
        return view('livewire.backoffice.pedidos.pedidos-estatus');
    }
}
