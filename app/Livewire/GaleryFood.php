<?php

namespace App\Livewire;

use App\Models\Producto;
use Livewire\Component;

class GaleryFood extends Component
{
    public function render()
    {
        $productos = Producto::whereNull('deleted_at')
        ->where('activo', true)
        ->orderBy('posicion')
        ->get();

        return view('livewire.galery-food', compact('productos'));
    }
}
