<?php

namespace App\Livewire\Backoffice\Users;

use App\Models\User;
use Livewire\Attributes\Rule;
use Livewire\Component;

class UsersFormComponent extends Component
{
    #[Rule('required|string|max:255')]
    public string $name = '';

    #[Rule('required|email|unique:users,email')]
    public string $email = '';

    #[Rule('required|min:8|confirmed')]
    public string $password = '';

    public string $password_confirmation = '';

    // #[Rule('required|in:admin,user')]
    #[Rule('required')]
    public string $rol = '';

    public ?User $usuario = null;

    public function mount(?int $userId = null): void
    {
        if ($userId) {
            $this->usuario = User::find($userId);
            $this->fill($this->usuario->only(['name', 'email', 'rol']));
        }
    }

    public function save()
    {
        $data = $this->validate();

        if ($this->usuario) {
            $this->usuario->update($data);
            session()->flash('success', 'Usuario actualizado correctamente.');
        } else {
            User::create($data);
            session()->flash('success', 'Usuario creado correctamente.');
        }

        sleep(1);

        return redirect()->route('usuarios.index');
    }            
    
    

    public function render()
    {
        return view('livewire.backoffice.users.users-form-component');
    }
}
