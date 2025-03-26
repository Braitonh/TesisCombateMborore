<?php

namespace App\Livewire\Backoffice\Users;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
class UsersComponent extends Component
{

    use WithPagination;

    public $buscador = '';
    public $showDeleteModal = false;
    public $userIdToDelete = null;

    protected $queryString = ['buscador'];

    public function confirmDelete($id)
    {
        $this->userIdToDelete = $id;
        $this->showDeleteModal = true;
    }   

    public function delete()
    {
        $user = User::findOrFail($this->userIdToDelete);
        $user->delete();

        $this->showDeleteModal = false;
        $this->userIdToDelete = null;

        session()->flash('success', 'Usuario eliminado correctamente.');
    }

    public function cancelDelete()
    {
        $this->showDeleteModal = false;
        $this->userIdToDelete = null;
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
        $this->buscador = '';
        $this->resetPage();
    }

    public function actualizarOrden($ids)
    {
        foreach ($ids as $index => $id) {
            User::where('id', $id)->update(['posicion' => $index + 1]);
        }
        session()->flash('success', 'Usuario actualizado correctamente.');

    }
    
    public function render()
    {
        $usuarios = User::whereNull('deleted_at')
            ->when($this->buscador, fn($q) =>
                $q->where('name', 'like', '%' . $this->buscador . '%')
            )
            ->orderBy('posicion')
            ->paginate(10);

        return view('livewire.backoffice.users.users-component', compact('usuarios'));
    }
    
    
}
