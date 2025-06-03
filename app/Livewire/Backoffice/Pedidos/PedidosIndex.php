<?php

namespace App\Livewire\Backoffice\Pedidos;

use App\Events\OrderCreated;
use App\Models\Cliente;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\User;
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

    public $repartidores;
    public ?int $repartidor_id = null;

    public ?float $envio = 0;

    public ?Pedido $pedido;

    public ?Cliente $cliente = null;

    //Cliente
    public ?string $nombre = null;
    public ?string $email = null;
    public ?string $telefono = null;
    public ?string $direccion = null;


    public function mount()
    {
        $this->productosDisponibles = Producto::where('activo', true)->get();
        $this->repartidores = User::where('rol', 'Repartidor')->get();
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

    public function incrementar($key)
    {
        $this->cantidades[$key]++;
    }

    public function decrementar($key)
    {
        if ($this->cantidades[$key] > 1) {
            $this->cantidades[$key]--;
        }
    }

    public function eliminar($key)
    {
        unset($this->cantidades[$key]);
        unset($this->shoppingCart[$key]);
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
        $pedido = Pedido::with(['productos', 'ofertas', 'cliente'])->findOrFail($id);
        $this->pedido = $pedido;

        $this->shoppingCart = [];
        $this->cantidades = [];

        // 1. Cargar ofertas
        foreach ($pedido->ofertas as $oferta) {
            $key = 'oferta_' . $oferta->id;
            $this->shoppingCart[$key] = $oferta;
            $this->cantidades[$key] = $oferta->pivot->cantidad;
        }

        // 2. Cargar productos individuales (que NO están en ninguna oferta)
        // Obtén los IDs de productos que ya están en ofertas
        $productosEnOfertas = [];
        foreach ($pedido->ofertas as $oferta) {
            foreach ($oferta->productos as $producto) {
                $productosEnOfertas[] = $producto->id;
            }
        }

        foreach ($pedido->productos as $producto) {
            if (!in_array($producto->id, $productosEnOfertas)) {
                $this->shoppingCart[$producto->id] = $producto;
                $this->cantidades[$producto->id] = $producto->pivot->cantidad;
            }
        }

        $this->cliente_id = $pedido->cliente_id;
        $this->cliente = $pedido->cliente;

        if ($pedido->cliente) {
            $this->nombre = $pedido->cliente->nombre;
            $this->email = $pedido->cliente->email;
            $this->telefono = $pedido->cliente->telefono;
            $this->direccion = $pedido->cliente->direccion;
        }

        if ($pedido->repartidor_id){
            $this->repartidor_id = $pedido->repartidor_id;
            $this->envio = $pedido->total - $this->getTotalProperty();
        }

        $this->showModal = !$this->showModal;
        if (!$this->showModal) {
            $this->resetErrorBag();
        }
    }

    public function toggleCartModal(): void
    {
        sleep(0.5);
        $this->showModal = !$this->showModal;

        $this->reset(['shoppingCart', 'cantidades', 'nombre', 'email', 'telefono', 'direccion', 'cliente', 'cliente_id', 'pedido','envio']);
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
        $total = 0;
        foreach ($this->shoppingCart as $key => $item) {
            $cantidad = $this->cantidades[$key] ?? 1;
            $total += $item->precio * $cantidad;
        }
        return $total + ($this->envio ?? 0);
    }

    public function getSubTotalProperty()
    {
        $total = 0;
        foreach ($this->shoppingCart as $key => $item) {
            $cantidad = $this->cantidades[$key] ?? 1;
            $total += $item->precio * $cantidad;
        }
        return $total;
    }

    public function savePedido()
    {
        DB::beginTransaction();

        try {
            // Eliminar relaciones anteriores
            $this->pedido->productos()->detach();
            $this->pedido->ofertas()->detach();

            foreach ($this->shoppingCart as $key => $item) {
                $cantidad = $this->cantidades[$key] ?? 1;

                if (str_starts_with($key, 'oferta_')) {
                    // 1. Guardar la oferta en la tabla pivote
                    $this->pedido->ofertas()->attach($item->id, [
                        'cantidad' => $cantidad,
                        'precio_oferta' => $item->precio,
                    ]);

                    // 2. Guardar los productos de la oferta (con descuento si corresponde)
                    $descuento = $item->descuento ?? 0;
                    foreach ($item->productos as $producto) {
                        $cantidadProducto = $cantidad * ($producto->pivot->cantidad ?? 1);
                        $precioUnitario = $producto->precio * (1 - ($descuento / 100));
                        $subtotal = $precioUnitario * $cantidadProducto;

                        $this->pedido->productos()->attach($producto->id, [
                            'cantidad' => $cantidadProducto,
                            'precio_unitario' => $precioUnitario,
                            'subtotal' => $subtotal,
                        ]);
                    }
                } else {
                    // Producto individual
                    $precioUnitario = $item->precio;
                    $subtotal = $precioUnitario * $cantidad;

                    $this->pedido->productos()->attach($item->id, [
                        'cantidad' => $cantidad,
                        'precio_unitario' => $precioUnitario,
                        'subtotal' => $subtotal,
                    ]);
                }
            }

            $total = $this->getTotalProperty();

            $this->pedido->update([
                'total' => $total,
                'repartidor_id' => $this->repartidor_id,
                'tipo' => $this->repartidor_id ? 'Con envío' : 'Sin envío'
            ]);
            DB::commit();

            // Generar PDF del ticket
            $this->pedido->load('cliente', 'productos');
            $pdf = Pdf::loadView('tickets.pedido', ['pedido' => $this->pedido]);
            $pdfPath = storage_path("app/public/tickets/pedido_{$this->pedido->id}.pdf");
            $pdf->save($pdfPath);

            session()->flash('ticket_path', asset("storage/tickets/pedido_{$this->pedido->id}.pdf"));
            $this->reset(['shoppingCart', 'cantidades', 'showModal', 'nombre', 'email', 'telefono', 'direccion', 'cliente', 'buscador', 'cliente_id', 'pedido', 'envio']);
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
