<?php

namespace App\Livewire;

use App\Events\OrderCreated;
use App\Models\Cliente;
use App\Models\Pedido;
use App\Models\Producto;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;

class GaleryFood extends Component
{
    public array $shoppingCart = [];
    public array $cantidades = [];
    public bool $showSuccessAlert  = false;
    public bool $showModal = false;
    public $buscador = '';

    //Cliente
    public ?int $cliente_id = null;
    public ?string $nombre = null;
    public ?string $email = null;
    public ?string $telefono = null;
    public ?string $direccion = null;



    public function getTotalProperty()
    {
        return collect($this->shoppingCart)->sum(fn($p) => $p->precio * $this->cantidades[$p->id]);
    }

    public function toggleCartModal(): void
    {
        sleep(0.5);
        $this->showModal = !$this->showModal;

        if (!$this->showModal) {
            $this->resetErrorBag(); // Borra todos los errores validados (como 'cliente')
        }

    }

    public function addToCard(Producto $producto)
    {
        $id = $producto->id;

        if (!isset($this->cantidades[$id])) {
            $this->shoppingCart[$id] = $producto;
            $this->cantidades[$id] = 1;
            $this->showSuccessAlert = true;
            $this->dispatch('hide-success-alert');
        }else{
            $this->showSuccessAlert = false;
        }

    }
    
    #[On('alertHidden')]
    public function alertHidden(): void
    {
        $this->showSuccessAlert = false;
    }

    public function incrementar($id)
    {
        $this->cantidades[$id]++;
    }

    public function decrementar($id)
    {
        if ($this->cantidades[$id] > 1) {
            $this->cantidades[$id]--;
        }
    }

    public function eliminar($id)
    {
        unset($this->cantidades[$id]);
        unset($this->shoppingCart[$id]);
        
        if(empty($this->cantidades) && empty($this->shoppingCart)){
            $this->toggleCartModal();
        }

    }

    public function savePedido()
    {
        if (empty($this->shoppingCart)) {
            $this->addError('carrito', 'El carrito está vacío.');
            return;
        }
    
        $hayDatosCliente = $this->nombre || $this->email || $this->telefono || $this->direccion;

        if (!$hayDatosCliente) {
            $this->addError('cliente', 'Debe ingresar los datos para del cliente');
            return;
        }

        $cliente = Cliente::where('telefono', $this->telefono)->first();

    
        DB::beginTransaction();
    
        try {
            // Crear cliente si no se encontró
            if ($hayDatosCliente && !$cliente) {
                
                $this->validate([
                    'nombre' => 'required|string|max:255',
                    'email' => 'required',
                    'telefono' => 'required|string|max:50',
                    'direccion' => 'required|string|max:255',
                ]);

                $cliente = Cliente::create([
                    'nombre' => $this->nombre,
                    'email' => $this->email,
                    'telefono' => $this->telefono,
                    'direccion' => $this->direccion,
                    'password' => 'root'
                ]);
            }

            $cliente->nombre = $this->nombre;
            $cliente->direccion = $this->direccion;
            $cliente->save();

            $pedido = Pedido::create([
                'cliente_id' => $cliente->id,
                'fecha' => now(),
                'total' => $this->total,
                'estado' => 'Nuevo',
            ]);
    
            foreach ($this->shoppingCart as $producto) {
                $cantidad = $this->cantidades[$producto->id] ?? 1;
                $precioUnitario = $producto->precio;
                $subtotal = $precioUnitario * $cantidad;
    
                $pedido->productos()->attach($producto->id, [
                    'cantidad' => $cantidad,
                    'precio_unitario' => $precioUnitario,
                    'subtotal' => $subtotal,
                ]);
            }
    
            DB::commit();
    
            // Generar PDF del ticket
            $pedido->load('cliente', 'productos');
            $pdf = Pdf::loadView('tickets.pedido', ['pedido' => $pedido]);
            $pdfPath = storage_path("app/public/tickets/pedido_{$pedido->id}.pdf");
            $pdf->save($pdfPath);
    
            session()->flash('ticket_path', asset("storage/tickets/pedido_{$pedido->id}.pdf"));
            $this->reset(['shoppingCart', 'cantidades', 'showModal', 'nombre', 'email', 'telefono', 'direccion']);
            session()->flash('success', 'Pedido guardado exitosamente. Ticket generado.');
            
            event(new OrderCreated($pedido->toArray()));

        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            $this->addError('pedido', 'Error: ' . $e->getMessage());
        }
    }



    public function render()
    {
        $productos = Producto::whereNull('deleted_at')
        ->where('activo', true)
        ->orderBy('posicion')
        ->get();

        return view('livewire.galery-food', compact('productos'));
    }
}
