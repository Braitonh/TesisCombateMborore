<?php

namespace App\Livewire\Backoffice\Productos;

use App\Models\Ofertas;
use Livewire\Attributes\Rule;
use Livewire\Component;

class Formulario extends Component
{
    public array $shoppingCart = [];

    public array $cantidades = [];
    public ?Ofertas $oferta = null;

    #[Rule('nullable|image|max:1024')] // 1MB máx
    public $imagen;

    public function render()
    {
        return view('livewire.backoffice.productos.formulario');
    }
}
