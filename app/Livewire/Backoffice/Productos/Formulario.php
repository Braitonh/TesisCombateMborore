<?php

namespace App\Livewire\Backoffice\Productos;

use App\Models\Ofertas;
use App\Models\Producto;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class Formulario extends Component
{

    use WithFileUploads;
    public array $shoppingCart = [];

    public array $cantidades = [];
    public ?Ofertas $oferta = null;

    public array $productosDisponibles = [];

    public $imagen;
    public string $nombre = '';
    public string $descripcion = '';
    public float $descuento = 0; // porcentaje: 0 a 100


    public function mount($ofertaId = null)
    {
        $this->productosDisponibles = Producto::all()->toArray();

        if ($ofertaId) {
            $this->oferta = Ofertas::with('productos')->find($ofertaId);
            if ($this->oferta) {
                $this->nombre = $this->oferta->nombre;
                $this->descripcion = $this->oferta->descripcion;
                $this->descuento = $this->oferta->descuento ?? 0;
                // Si tienes campo 'activo', puedes cargarlo también
                // $this->activo = $this->oferta->activo ?? false;

                foreach ($this->oferta->productos as $producto) {
                    $this->shoppingCart[$producto->id] = $producto;
                    $this->cantidades[$producto->id] = $producto->pivot->cantidad;
                }
            }
        }
    }

    public function incrementar(int $productoId)
    {
        if (isset($this->cantidades[$productoId])) {
            $this->cantidades[$productoId]++;
        }
    }

    public function decrementar(int $productoId)
    {
        if (isset($this->cantidades[$productoId]) && $this->cantidades[$productoId] > 1) {
            $this->cantidades[$productoId]--;
        }
    }

    public function eliminar(int $productoId)
    {
        unset($this->shoppingCart[$productoId], $this->cantidades[$productoId]);
    }

    public function agregarProducto(int $productoId)
    {
        $producto = Producto::find($productoId);
        if (!$producto) return;

        if (!array_key_exists($productoId, $this->shoppingCart)) {
            $this->shoppingCart[$productoId] = $producto;
            $this->cantidades[$productoId] = 1;
        }
    }

    public function getPrecioTotalProperty()
    {
        $total = 0;
        foreach ($this->shoppingCart as $productoId => $producto) {
            $cantidad = $this->cantidades[$productoId] ?? 1;
            $total += $producto->precio * $cantidad;
        }

        if ($this->descuento >= 0) {
            $total -= ($total * ($this->descuento / 100));
        }

        return round($total, 2);
    }

    public function save()
    {

        $this->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'imagen' => ($this->oferta && $this->oferta->imagen) ? 'nullable|image|max:1024' : 'required|image|max:1024',
            'descuento' => 'required|numeric|min:0|max:100',
            'shoppingCart' => function ($attribute, $value, $fail) {
                if (empty($value)) {
                    $fail('Debe agregar al menos un producto a la oferta');
                }
            },
        ], [
            'nombre.required' => 'El nombre de la oferta es obligatorio',
        ]);

        // Guardar o actualizar la oferta
        $oferta = $this->oferta ?? new Ofertas();
        $oferta->nombre = $this->nombre;
        $oferta->descripcion = $this->descripcion;
        $oferta->precio = $this->precioTotal;
        $oferta->descuento = $this->descuento;
        // Manejo de imagen
        if ($this->imagen) {
            $path = $this->imagen->store('ofertas', 'public');
            $oferta->imagen = 'storage/' . $path;
        }

        $oferta->save();

        // Sincronizar productos y cantidades
        $pivotData = [];
        foreach ($this->shoppingCart as $productoId => $producto) {
            $pivotData[$productoId] = ['cantidad' => $this->cantidades[$productoId] ?? 1];
        }

        $oferta->productos()->sync($pivotData);

        session()->flash('success', 'La oferta fue guardada correctamente.');

        return redirect()->route('productos.combos');
    }


    public function render()
    {
        return view('livewire.backoffice.productos.formulario');
    }

}
