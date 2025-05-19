<?php

namespace App\Livewire;

use App\Models\Producto;
use Livewire\Component;
use Livewire\WithPagination;

class DashboardHero extends Component
{
    use WithPagination;

    public string $filtroCategoria = '';

    public function render()
    {
        $query = Producto::whereNull('deleted_at')
            ->where('activo', true);

        $productosOfertas = Producto::whereNull('deleted_at')
            ->where('activo', true)
            ->orderBy('posicion')
            ->limit(4)
            ->get();

        if ($this->filtroCategoria !== '') {
            $query->where('categoria', $this->filtroCategoria);
        }

        $productos = $query->orderBy('posicion')->paginate(8);


        return view('livewire.dashboard-hero', compact('productosOfertas', 'productos'));
    }
}
