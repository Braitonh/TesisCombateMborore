<?php

namespace App\Livewire;

use App\Models\Producto;
use Livewire\Component;

class ToggleSwitch extends Component
{
    public Producto $producto;
    public bool $activo;
    protected $listeners = ['refreshComponent' => '$refresh'];


    public function mount(Producto $producto)
    {
        $this->producto = $producto;
        $this->activo = (bool) $producto->activo;
    }

    public function toggle()
    {
        sleep(1);
        $this->activo = !$this->activo;
        $this->producto->update(['activo' => $this->activo]);
        session()->flash('message', 'Estado del producto actualizado correctamente.');
        $this->dispatch('refreshComponent');
        return redirect()->to(url('productos'));

    }


    public function render()
    {
        return view('livewire.toggle-switch');
    }
}
