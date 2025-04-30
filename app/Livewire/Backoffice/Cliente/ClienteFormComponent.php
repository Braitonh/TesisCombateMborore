<?php

namespace App\Livewire\Backoffice\Cliente;

use App\Models\Cliente;
use Livewire\Attributes\Rule;
use Livewire\Component;

class ClienteFormComponent extends Component
{
    public string $nombre = '';
    public string $email = '';
    public string $telefono = '';
    public string $direccion = '';
    public string $password = '';
    public string $password_confirmation = '';
    public ?Cliente $cliente = null;

    public function mount(?int $clienteId = null): void
    {
        if ($clienteId) {
            $this->cliente = Cliente::findOrFail($clienteId);
            $this->fill($this->cliente->only(['nombre', 'email', 'telefono', 'direccion']));
        }
    }

    public function rules(): array
    {
        return [
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:clientes,email,' . $this->cliente?->id,
            'telefono' => 'required|string',
            'direccion' => 'required|string',
        ];

        return $rules;
    }

    public function save()
    {
        $this->validate();
        if ($this->cliente) {
            $this->cliente->update($this->only(['nombre', 'email', 'telefono', 'direccion']));
            session()->flash('success', 'Cliente actualizado correctamente.');
        } else {
            Cliente::create($this->only(['nombre', 'email', 'telefono', 'direccion']));
            session()->flash('success', 'Cliente creado correctamente.');
        }

        sleep(1);
        return redirect()->route('clientes.index');
    }
    

    public function render()
    {
        return view('livewire.backoffice.cliente.cliente-form-component');
    }
}
