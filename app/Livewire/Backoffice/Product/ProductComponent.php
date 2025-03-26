<?php

namespace App\Livewire\Backoffice\Product;

use App\Models\Producto;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class ProductComponent extends Component
{
    use WithPagination;
    
    public $buscador = '';
    
    protected $queryString = ['buscador'];
    public $showDeleteModal = false;
    public $productoIdToDelete = null;


    public function confirmDelete($id)
    {
        $this->productoIdToDelete = $id;
        $this->showDeleteModal = true;
    }   

    public function delete()
    {
        $producto = Producto::findOrFail($this->productoIdToDelete);
        $producto->delete();

        $this->showDeleteModal = false;
        $this->productoIdToDelete = null;

        session()->flash('success', 'Producto eliminado correctamente.');
    }
    
    public function cancelDelete()
    {
        $this->showDeleteModal = false;
        $this->productoIdToDelete = null;
    }


    public function updatingBuscador()
    {
        $this->resetPage();
    }

    public function buscar()
    {
        // solo resetea la página para que la búsqueda funcione desde la página 1
        $this->resetPage();
    }

    public function limpiar()
    {
        $this->resetPage();
        $this->buscador = '';
    }

    public function render()
    {
        $productos = Producto::whereNull('deleted_at')
            ->when($this->buscador, fn($q) =>
                $q->where('nombre', 'like', '%' . $this->buscador . '%')
            )
            ->paginate(10);
    
        return view('livewire.backoffice.product.product-component', compact('productos'));
    }
}
