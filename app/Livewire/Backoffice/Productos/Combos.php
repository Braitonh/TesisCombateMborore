<?php

namespace App\Livewire\Backoffice\Productos;

use App\Models\Ofertas;
use Livewire\Component;

class Combos extends Component
{

    public $ofertas;

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

    public function render()
    {
        return view('livewire.backoffice.productos.combos');
    }
}
