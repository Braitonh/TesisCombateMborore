<?php

namespace App\Livewire;

use App\Models\Producto;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Cliente;
use App\Models\Pedido;
use App\Events\OrderCreated;
use App\Events\NotificacionEstados;
use App\Models\User;
use App\Models\Ofertas;


class DashboardHero extends Component
{
    use WithPagination;

    public array $shoppingCart = [];
    public array $cantidades = [];
    public bool $showSuccessAlert  = false;
    public bool $showModal = false;
    public $buscador = '';
    public float $subtotal = 0;
    public ?float $envio = 0;

    public $repartidores;
    public $repartidor_id = null;

    public string $filtroCategoria = '';

    public $cliente = null;
    public $nombre = '';
    public $email = '';
    public $telefono = '';
    public $direccion = '';


    public function mount()
    {
        $this->repartidores = User::where('rol', 'Repartidor')->get();
    }

    public function toggleCartModal(): void
    {
        sleep(0.5);
        $this->showModal = !$this->showModal;


        if (!$this->showModal) {
            $this->resetErrorBag();
        }

        if ($this->showModal && Auth::guard('cliente')->check()) {
            $this->cliente = Auth::guard('cliente')->user()->cliente;

            $this->nombre = $this->cliente->nombre;
            $this->email = $this->cliente->email;
            $this->telefono = $this->cliente->telefono;
            $this->direccion = $this->cliente->direccion;
        }
    }

    public function getTotalProperty()
    {
        return $this->subTotal + ($this->envio ?? 0);
    }

    public function getSubTotalProperty()
    {
        $total = 0;

        foreach ($this->shoppingCart as $key => $item) {
            $cantidad = $this->cantidades[$key] ?? 1;

            if (str_starts_with($key, 'oferta_')) {
                // Sumar solo el precio de la oferta por la cantidad
                $total += $item->precio * $cantidad;
            } else {
                // Producto individual
                $total += $item->precio * $cantidad;
            }
        }

        return $total;
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
            return $this->addError('carrito', 'El carrito está vacío.');
        }

        $hayDatosCliente = $this->nombre || $this->email || $this->telefono || $this->direccion;

        if (!$this->cliente && !$hayDatosCliente) {
            return $this->addError('cliente', 'Debe seleccionar un cliente o ingresar los datos para crear uno nuevo.');
        }

        DB::beginTransaction();

        try {
            // Crear cliente si no existe

            if(Auth::guard('cliente')->check()){
                $this->cliente = Auth::guard('cliente')->user()->cliente;
            }else{

                $cliente = Cliente::where('email', $this->email)->first();

                if(!$cliente)
                {
                    $this->validate([
                        'nombre'   => 'required|string|max:255',
                        'email'    => 'required|email|unique:clientes,email',
                        'telefono' => 'required|string|max:50',
                        'direccion'=> 'required|string|max:255',
                    ]);

                    $this->cliente = Cliente::create([
                        'nombre'   => $this->nombre,
                        'email'    => $this->email,
                        'telefono' => $this->telefono,
                        'direccion'=> $this->direccion,
                    ]);
                }else{
                    $this->cliente = $cliente;
                    $cliente->update([
                        'nombre'   => $this->nombre,
                        'email'    => $this->email,
                        'telefono' => $this->telefono,
                        'direccion'=> $this->direccion,
                    ]);
                }
            }

            // Crear pedido
            $pedido = Pedido::create([
                'cliente_id'    => $this->cliente->id,
                'repartidor_id' => $this->repartidor_id,
                'total'         => $this->total,
                'estado'        => 'Recibido',
                'fecha'         => now(),
                'tipo'          => $this->envio > 0 ? 'Con envío' : 'Sin envío',
            ]);

            // Agregar productos u ofertas al pedido
            foreach ($this->shoppingCart as $key => $item) {
                $cantidad = $this->cantidades[$key] ?? 1;

                if (str_starts_with($key, 'oferta_')) {
                    $pedido->ofertas()->attach($item->id, [
                        'cantidad'      => $cantidad,
                        'precio_oferta' => $item->precio,
                    ]);

                    foreach ($item->productos as $producto) {
                        $cantidadProducto = $cantidad * ($producto->pivot->cantidad ?? 1);
                        $precioUnitario   = $producto->precio * (1 - ($item->descuento ?? 0) / 100);
                        $subtotal         = $precioUnitario * $cantidadProducto;

                        $pedido->productos()->attach($producto->id, [
                            'cantidad'        => $cantidadProducto,
                            'precio_unitario' => $precioUnitario,
                            'subtotal'        => $subtotal,
                        ]);
                    }
                } else {
                    $precioUnitario = $item->precio;
                    $subtotal       = $precioUnitario * $cantidad;

                    $pedido->productos()->attach($item->id, [
                        'cantidad'        => $cantidad,
                        'precio_unitario' => $precioUnitario,
                        'subtotal'        => $subtotal,
                    ]);
                }
            }

            DB::commit();

            // Limpiar formulario y carrito
            $this->reset([
                'shoppingCart', 'cantidades', 'showModal',
                'cliente', 'buscador', 'nombre', 'email', 'telefono',
                'direccion', 'repartidor_id', 'envio'
            ]);

            session()->flash('success', 'Pedido guardado exitosamente.');

            // Notificaciones/eventos
            event(new OrderCreated($pedido->toArray()));
            event(new NotificacionEstados($pedido));


        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            DB::rollBack();
            dd([
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'trace'   => $e->getTraceAsString(),
            ]);
            report($e);
            $this->addError('pedido', 'Error: ' . $e->getMessage());
        }
    }

    public function addOfertaToCart($ofertaId)
    {
        $oferta = \App\Models\Ofertas::with('productos')->find($ofertaId);
        if (!$oferta) return;

        $key = 'oferta_' . $oferta->id;

        if (!isset($this->shoppingCart[$key])) {
            $this->shoppingCart[$key] = $oferta;
            $this->cantidades[$key] = 1;
            $this->showSuccessAlert = true;
            $this->dispatch('hide-success-alert');
        } else {
            $this->showSuccessAlert = false;
        }
    }

    #[On('alertHidden')] // Livewire 3 listener con atributo
    public function alertHidden(): void
    {
        $this->showSuccessAlert = false;
    }

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

        $ofertas = Ofertas::where('activo', true)->limit(4)->orderBy('posicion')->get();



        return view('livewire.dashboard-hero', compact('productosOfertas', 'productos', 'ofertas'));
    }
}
