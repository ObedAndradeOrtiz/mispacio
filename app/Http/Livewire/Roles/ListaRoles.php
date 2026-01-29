<?php

namespace App\Http\Livewire\Roles;

use Livewire\Component;
use App\Models\Roles;
use App\Models\RolesVista;
class ListaRoles extends Component
{
    public $actividad="Activo";
    public $roles;
    protected $listeners = ['render'=>'render'];
    public function mount(){

    }
    public function render()
    {
        $this->roles=Roles::all();
        return view('livewire.roles.lista-roles');
    }
    public function guardartodo($id)
    {
        $vistaRole = RolesVista::findOrFail($id);
        // Cambiar el estado
        $vistaRole->estado = ($vistaRole->estado == 'Activo') ? 'Inactivo' : 'Activo';
        $vistaRole->save();
        $this->emit('saved','¡Rol cambiado!');
    }

}