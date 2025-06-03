<?php

namespace App\Livewire\Backoffice\Authcliente;

use Livewire\Component;
use App\Models\ClienteAuth;
use Illuminate\Support\Facades\Auth;

class ClienteLogin extends Component
{
    public $email = '';
    public $password = '';
    public $remember = false;

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required',
    ];

    public function login()
    {
        $this->validate();

        $clienteAuth = ClienteAuth::where('email', $this->email)->first();

        if (!$clienteAuth || !Auth::guard('cliente')->attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            $this->addError('email', 'Las credenciales proporcionadas son incorrectas.');
            return;
        }

        session()->regenerate();

        return redirect()->intended(route('tienda'));
    }

    public function render()
    {
        return view('livewire.backoffice.authcliente.cliente-login');
    }
}
