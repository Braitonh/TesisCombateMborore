<?php

namespace App\Livewire\Backoffice\Pedidos;

use App\Events\OrderCreated;
use App\Models\Cliente;
use App\Models\Pedido;
use App\Models\Producto;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;

class PedidosForm extends Component
{

    public ?int $cliente_id = null;

    public ?Cliente $cliente = null;

    public $buscador = '';

    public int $cartCount = 0;

    public bool $showModal = false;

    public bool $showAddProductModal = false;

    public bool $showSuccessAlert  = false;
    
    public array $shoppingCart = [];

    public array $cantidades = [];

    //Cliente
    public ?string $nombre = null;
    public ?string $email = null;
    public ?string $telefono = null;
    public ?string $direccion = null;
    public ?bool $clienteEncontrado = null;

    public function savePedido()
    {
        if (empty($this->shoppingCart)) {
            $this->addError('carrito', 'El carrito está vacío.');
            return;
        }
    
        $hayDatosCliente = $this->nombre || $this->email || $this->telefono || $this->direccion;

        if (!$hayDatosCliente) {
            $this->addError('cliente', 'Debe seleccionar un cliente o ingresar los datos para crear uno nuevo.');
            return;
        }
    
        DB::beginTransaction();
    
        try {
            // Crear cliente si no se encontró y se escribieron datos
            if (!$this->cliente && $hayDatosCliente) {
                
                $this->validate([
                    'nombre' => 'required|string|max:255',
                    'email' => 'required|email|unique:clientes,email,',
                    'telefono' => 'required|string|max:50',
                    'direccion' => 'required|string|max:255',
                ]);

                $cliente = Cliente::create([
                    'nombre' => $this->nombre,
                    'email' => $this->email,
                    'telefono' => $this->telefono,
                    'direccion' => $this->direccion,
                ]);


    
                $this->cliente_id = $cliente->id;
                $this->cliente = $cliente;
                $this->clienteEncontrado = true;
            }

            

    
            $pedido = Pedido::create([
                'cliente_id' => $this->cliente_id,
                'fecha' => now(),
                'total' => $this->total,
                'estado' => 'Recibido',
                'iniciado_en' => now()
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
            $this->reset(['shoppingCart', 'cantidades', 'showModal', 'cliente', 'buscador', 'nombre', 'email', 'telefono', 'direccion', 'clienteEncontrado']);
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
    

    public function buscarCliente() 
    {
        $this->cliente = Cliente::whereNull('deleted_at')
        ->where('telefono', $this->buscador)
        ->first();

        if ($this->cliente) {
            $this->cliente_id = $this->cliente->id;
            $this->nombre = $this->cliente->nombre;
            $this->email = $this->cliente->email;
            $this->telefono = $this->cliente->telefono;
            $this->direccion = $this->cliente->direccion;
            $this->clienteEncontrado = true;
        } else {
            $this->reset(['cliente_id', 'nombre', 'email', 'telefono', 'direccion']);
            $this->clienteEncontrado = false;
        }
    }

    public function limpiarBuscador()
    {
        $this->buscador = '';
        $this->clienteEncontrado = null;
        $this->nombre = '';
        $this->email = '';
        $this->telefono = '';
        $this->direccion = '';
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

    public function closeModelKeppBuying()
    {
        $this->showAddProductModal = false;        
    }

    public function openModalShoppingCart()
    {
        $this->showAddProductModal = false;      
        $this->showModal = !$this->showModal;
         
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

    public function eliminar($id)
    {
        unset($this->cantidades[$id]);
        unset($this->shoppingCart[$id]);
        
        if(empty($this->cantidades) && empty($this->shoppingCart)){
            $this->toggleCartModal();
        }

    }

    #[On('alertHidden')] // Livewire 3 listener con atributo
    public function alertHidden(): void
    {
        $this->showSuccessAlert = false;
    }

    #[On('clienteSeleccionado')]
    public function actualizarCliente($id)
    {
        $this->cliente_id = $id;
    }



    public function render()
    {
        $productos = Producto::whereNull('deleted_at')
            ->where('activo', true)
            ->orderBy('posicion')
            ->get();

        // $clientes = Cliente::orderBy('nombre')->get();
    
        return view('livewire.backoffice.pedidos.pedidos-form', compact('productos'));
    }
}
