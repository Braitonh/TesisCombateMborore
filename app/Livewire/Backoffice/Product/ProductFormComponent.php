<?php

namespace App\Livewire\Backoffice\Product;

use App\Models\Producto;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProductFormComponent extends Component
{

    use WithFileUploads;


    #[Rule('required|string|max:255')]
    public string $nombre = '';

    #[Rule('required|string')]
    public string $descripcion = '';

    #[Rule('required|numeric|min:0')]
    public float $precio = 0;

    #[Rule('required|integer|min:0')]   
    public int $stock = 0;

    #[Rule('boolean')]
    public bool $activo = false;

    public ?Producto $producto = null;

    #[Rule('nullable|image|max:1024')] // 1MB máx
    public $imagen;

    public function mount(?int $productoId = null): void
    {
        if ($productoId) {
            $this->producto = Producto::findOrFail($productoId);
            $this->fill($this->producto->only(['nombre', 'descripcion', 'precio', 'stock', 'activo']));
        }
    }

    public function save()
    {
        $data = $this->validate();
        
        if ($this->imagen) {
            $path = $this->imagen->store('public/productos');
            $data['imagen'] = str_replace('public/', 'storage/', $path);
        }

        if ($this->producto) {
            $this->producto->update($data);
            session()->flash('success', 'Producto actualizado correctamente.');
        } else {
            Producto::create($data);
            session()->flash('success', 'Producto creado correctamente.');
        }

        sleep(1);
        return redirect()->route('productos.index');
    }

    public function render()
    {
        return view('livewire.backoffice.product.product-form-component');
    }
}
