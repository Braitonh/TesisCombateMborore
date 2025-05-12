<?php

namespace App\Livewire\Backoffice\Users;

use App\Models\User;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class UsersFormComponent extends Component
{
    use WithFileUploads;              // ← usar trait

    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $rol = '';
    public ?User $usuario = null;
    public $avatar;                     // ← propiedad para el archivo


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
            'avatar'   => 'nullable|image|max:1024',  // ← valida JPG/PNG hasta 1MB

        ];

        if ($this->usuario) {
            $rules['password'] = 'min:8|required';
        }

        return $rules;
    }

    public function save()
    {
        $data = $this->validate();

        // Si el usuario sube un avatar, lo guardamos en public/avatars
        if ($this->avatar) {
            $path = $this->avatar->store('public/avatars');
            $data['avatar'] = str_replace('public/', 'storage/', $path);
        }

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
