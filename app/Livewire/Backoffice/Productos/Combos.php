<?php

namespace App\Livewire\Backoffice\Productos;

use App\Models\Ofertas;
use Livewire\Component;

class Combos extends Component
{

    public $ofertas;
    public $showDeleteModal = false;
    public $ofertaAEliminar = null;

    public function mount()
    {
        $this->loadOfertas();
    }

    public function loadOfertas()
    {
        $this->ofertas = Ofertas::with(['productos'])
            ->where('activo', true)
            ->orderBy('posicion')
            ->get();
    }

    public function actualizarOrdenOfertas(array $orden)
    {
        foreach ($orden as $index => $id) {
            Ofertas::where('id', $id)->update(['posicion' => $index + 1]);
        }

        $this->loadOfertas();
        session()->flash('success', 'Oferta actualizada correctamente.');


    }

    public function confirmDelete($ofertaId)
    {
        $this->ofertaAEliminar = $ofertaId;
        $this->showDeleteModal = true;
    }

    public function delete()
    {
        if ($this->ofertaAEliminar) {
            $oferta = Ofertas::find($this->ofertaAEliminar);
            if ($oferta) {
                $oferta->delete();
            }
            $this->ofertaAEliminar = null;
            $this->showDeleteModal = false;
            $this->loadOfertas();
            session()->flash('success', 'Oferta eliminada correctamente.');
        }
    }

    public function cancelDelete()
    {
        $this->ofertaAEliminar = null;
        $this->showDeleteModal = false;
    }

    public function render()
    {
        return view('livewire.backoffice.productos.combos');
    }
}
