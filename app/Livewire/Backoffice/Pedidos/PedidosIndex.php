<?php

namespace App\Livewire\Backoffice\Pedidos;

use App\Events\OrderCreated;
use App\Models\Cliente;
use App\Models\Pedido;
use App\Models\Producto;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class PedidosIndex extends Component
{

    use WithPagination;

    public $buscador = '';
    protected $queryString = ['buscador'];
    public $showModal = false;
    public $shoppingCart = [];
    public array $cantidades = [];
    public ?int $cliente_id = null;
    public $productoSeleccionado = null;
    public $productosDisponibles = [];
    public $pedidoIdToDelete = null;
    public $showDeleteModal = false;

    

    public Pedido $pedido;

    public ?Cliente $cliente = null;
    
    //Cliente
    public ?string $nombre = null;
    public ?string $email = null;
    public ?string $telefono = null;
    public ?string $direccion = null;

    public function mount()
    {
        $this->productosDisponibles = Producto::where('activo', true)->get();
    }

    public function confirmDelete($id)
    {
        $this->pedidoIdToDelete = $id;
        $this->showDeleteModal = true;
    }   

    public function delete()
    {
        $pedido = Pedido::findOrFail($this->pedidoIdToDelete);
        $pedido->delete();

        $this->showDeleteModal = false;
        $this->pedidoIdToDelete = null;

        session()->flash('success', 'Pedido eliminado correctamente.');
    }
    
    public function cancelDelete()
    {
        $this->showDeleteModal = false;
        $this->pedidoIdToDelete = null;
    }

    public function agregarProducto()
    {
        if (!$this->productoSeleccionado) return;

        $producto = Producto::find($this->productoSeleccionado);

        if (!$producto) return;

        // Si ya está en el carrito, suma 1
        if (isset($this->shoppingCart[$producto->id])) {
            $this->cantidades[$producto->id]++;
        } else {
            $this->shoppingCart[$producto->id] = $producto;
            $this->cantidades[$producto->id] = 1;
        }

        $this->productoSeleccionado = null; // limpiar selección
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

    public function updatingBuscador()
    {
        $this->resetPage();
    }
    
    public function buscar()
    {
        $this->resetPage();
    }

    public function limpiar()
    {
        $this->resetPage();
        $this->buscador = '';
    }
    public function openModal(?int $id): void
    {
        sleep(0.5);
        $pedido = Pedido::with('productos', 'cliente')->find($id);
        
        $this->shoppingCart = [];
        $this->cantidades = [];
    
        foreach ($pedido->productos as $producto) {
            $this->shoppingCart[$producto->id] = $producto;
            $this->cantidades[$producto->id] = $producto->pivot->cantidad;
        }
    
        $this->cliente_id = $pedido->cliente_id;
        $this->cliente = $pedido->cliente;
    
        if ($pedido->cliente) {
            $this->nombre = $pedido->cliente->nombre;
            $this->email = $pedido->cliente->email;
            $this->telefono = $pedido->cliente->telefono;
            $this->direccion = $pedido->cliente->direccion;
        }
    
        $this->showModal = !$this->showModal;
        $this->pedido = $pedido;
        if (!$this->showModal) {
            $this->resetErrorBag();
        }
    }

    public function toggleCartModal(): void
    {
        sleep(0.5);
        $this->showModal = !$this->showModal;

        $this->reset(['shoppingCart', 'cantidades', 'nombre', 'email', 'telefono', 'direccion', 'cliente', 'cliente_id', 'pedido']);
        $this->resetErrorBag();

    }

    public function iniciarPedido($id)
    {
        $pedido = Pedido::findOrFail($id);
        $pedido->estado = 'Iniciado';
        $pedido->iniciado_en = now();
        $pedido->save();
        event(new OrderCreated($pedido->toArray()));

    }

    public function getTotalProperty()
    {
        return collect($this->shoppingCart)->sum(fn($p) => $p->precio * $this->cantidades[$p->id]);
    }

    public function savePedido()
    {

        DB::beginTransaction();
    
        try {

        // Eliminar productos anteriores del pedido (importante para editar)
        $this->pedido->productos()->detach();

        // Agregar los productos actuales del carrito
        foreach ($this->shoppingCart as $producto) {
            $cantidad = $this->cantidades[$producto->id] ?? 1;
            $precioUnitario = $producto->precio;
            $subtotal = $precioUnitario * $cantidad;

            $this->pedido->productos()->attach($producto->id, [
                'cantidad' => $cantidad,
                'precio_unitario' => $precioUnitario,
                'subtotal' => $subtotal,
            ]);
        }

        $total = collect($this->shoppingCart)->sum(fn($p) => $p->precio * $this->cantidades[$p->id]);
        $this->pedido->update(['total' => $total,]);
        DB::commit();
    
            // Generar PDF del ticket
        $this->pedido->load('cliente', 'productos');
        $pdf = Pdf::loadView('tickets.pedido', ['pedido' => $this->pedido]);
        $pdfPath = storage_path("app/public/tickets/pedido_{$this->pedido->id}.pdf");
        $pdf->save($pdfPath);

        session()->flash('ticket_path', asset("storage/tickets/pedido_{$this->pedido->id}.pdf"));
        $this->reset(['shoppingCart', 'cantidades', 'showModal', 'nombre', 'email', 'telefono', 'direccion']);
        session()->flash('success', 'Pedido guardado exitosamente. Ticket generado.');
        
        event(new OrderCreated($this->pedido->toArray()));

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
        $pedidos = Pedido::whereNull('deleted_at')
            ->when($this->buscador, fn($q) =>
                $q->where('id', 'like', '%' . $this->buscador . '%')
            )
            ->orderBy('id', 'desc') 
            ->paginate(10);
    
        return view('livewire.backoffice.pedidos.pedidos-index', compact('pedidos'));
    }
}
