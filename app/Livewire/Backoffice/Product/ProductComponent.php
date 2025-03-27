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

    public function actualizarOrden($ids)
    {
        foreach ($ids as $index => $id) {
            Producto::where('id', $id)->update(['posicion' => $index + 1]);
        }
        session()->flash('success', 'Producto actualizado correctamente.');

    }

    public function render()
    {
        $productos = Producto::whereNull('deleted_at')
            ->when($this->buscador, fn($q) =>
                $q->where('nombre', 'like', '%' . $this->buscador . '%')
            )
            ->orderBy('posicion', 'asc')
            ->paginate(10);
    
        return view('livewire.backoffice.product.product-component', compact('productos'));
    }
}
