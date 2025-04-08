<?php

namespace App\Livewire\Backoffice\Cliente;

use App\Models\Cliente;
use Livewire\Component;
use Livewire\WithPagination;

class ClienteComponent extends Component
{
    use WithPagination;

    public $buscador = '';
    public $showDeleteModal = false;
    public $clienteIdToDelete = null;

    protected $queryString = ['buscador'];
    
    public function confirmDelete($id)
    {
        $this->clienteIdToDelete = $id;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        $cliente = Cliente::findOrFail($this->clienteIdToDelete);
        $cliente->delete();

        $this->cancelDelete();

        session()->flash('success', 'Cliente eliminado correctamente.');
    }
    
    public function cancelDelete()
    {
        $this->showDeleteModal = false;
        $this->clienteIdToDelete = null;
    }

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
    

    public function actualizarOrden($ids)
    {
        foreach ($ids as $index => $id) {
            Cliente::where('id', $id)->update(['posicion' => $index + 1]);
        }
        session()->flash('success', 'Cliente actualizado correctamente.');
    }
    

    public function render()    
    {
        $clientes = Cliente::whereNull('deleted_at')
            ->when($this->buscador, fn($q) =>
                $q->where('nombre', 'like', '%' . $this->buscador . '%')
            )
            ->paginate(10);

        return view('livewire.backoffice.cliente.cliente-component', compact('clientes'));
    }
}
