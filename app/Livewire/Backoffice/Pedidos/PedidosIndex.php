<?php

namespace App\Livewire\Backoffice\Pedidos;

use App\Models\Pedido;
use Livewire\Component;
use Livewire\WithPagination;

class PedidosIndex extends Component
{

    use WithPagination;

    public $buscador = '';
    protected $queryString = ['buscador'];


    public function updatingBuscador()
    {
        $this->resetPage();
    }
    
    public function buscar()
    {
        $this->resetPage();
    }

    public function limpiar()
    {
        $this->resetPage();
        $this->buscador = '';
    }
    
    
    public function render()
    {
        $pedidos = Pedido::whereNull('deleted_at')
            ->when($this->buscador, fn($q) =>
                $q->where('fecha', 'like', '%' . $this->buscador . '%')
            )
            ->orderBy('id', 'desc') 
            ->paginate(10);
    
        return view('livewire.backoffice.pedidos.pedidos-index', compact('pedidos'));
    }
}
