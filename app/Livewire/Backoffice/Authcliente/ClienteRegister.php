<?php

namespace App\Livewire\Backoffice\Authcliente;

use Livewire\Component;
use App\Models\Cliente;
use App\Models\ClienteAuth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ClienteRegister extends Component
{

    public $nombre = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $telefono = '';
    public $direccion = '';

    protected $rules = [
        'nombre' => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:cliente_auths',
        'password' => 'required|string|min:8|confirmed',
        'telefono' => 'required|string',
        'direccion' => 'required|string',
    ];

    public function register()
    {
        $this->validate();

        // Crear el cliente
        $cliente = Cliente::create([
            'nombre' => $this->nombre,
            'email' => $this->email,
            'telefono' => $this->telefono,
            'direccion' => $this->direccion,
        ]);

        // Crear la autenticación del cliente
        $clienteAuth = ClienteAuth::create([
            'cliente_id' => $cliente->id,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);

        // Iniciar sesión automáticamente
        Auth::guard('cliente')->login($clienteAuth);

        session()->regenerate();

        return redirect()->route('tienda');
    }

    public function render()
    {
        return view('livewire.backoffice.authcliente.cliente-register');
    }
}
