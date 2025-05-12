<?php

namespace App\Livewire\Backoffice\Delivery;

use App\Events\NotificacionEstados;
use App\Models\Pedido;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Delivery extends Component
{
    public ?Pedido $ongoingOrder;
    public $orderHistory;

    public function mount()
    {
        $this->loadOrders();
    }

    public function loadOrders()
    {
        $user = Auth::user();

        $query = Pedido::with(['cliente', 'productos', 'repartidor'])
            ->whereDate('fecha', today())
            ->where('tipo', 'Con envío')
            ->whereIn('estado', ['Delivery', 'Completado']);

        if ($user->rol !== 'admin') {
            $query->where('repartidor_id', $user->id);
        }

        $this->orderHistory = $query->get();
    }

    public function completeOrder(int $orderId)
    {
        $pedido = Pedido::findOrFail($orderId);

        // Solo si está en Delivery
        if ($pedido->estado === 'Delivery') {
            $pedido->update(['estado' => 'Completado']);
            session()->flash('success', "Pedido #{$orderId} completado.");
            event(new NotificacionEstados($pedido));

        }

        $this->loadOrders();
    }

    public function render()
    {
        return view('livewire.backoffice.delivery.delivery');
    }
}
