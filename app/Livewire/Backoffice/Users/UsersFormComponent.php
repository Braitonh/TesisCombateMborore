<?php

namespace App\Livewire\Backoffice\Users;

use App\Models\User;
use Illuminate\Validation\Rule;
use Livewire\Component;

class UsersFormComponent extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $rol = '';
    public ?User $usuario = null;

    public function mount(?int $userId = null): void
    {
        if ($userId) {
            $this->usuario = User::find($userId);
            $this->fill($this->usuario->only(['name', 'email', 'rol']));
        }
    }

    public function rules(): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($this->usuario?->id),
            ],
            'rol' => 'required',
            'password' => 'required|min:8|confirmed',
        ];

        if ($this->usuario) {
            $rules['password'] = 'min:8|required';
        }
    
        return $rules;
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
