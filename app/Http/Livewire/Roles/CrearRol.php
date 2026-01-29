<?php

namespace App\Http\Livewire\Roles;

use Livewire\Component;
use App\Models\Roles;
use App\Models\RolesVista;

class CrearRol extends Component
{
    public $nombrerol;
    public $descripcion;
    public $crear = false;
    public function render()
    {
        return view('livewire.roles.crear-rol');
    }
    public function guardartodo()
    {
        $nuevo = new Roles;
        $nuevo->rol = $this->nombrerol;
        $nuevo->descripcion = $this->descripcion;
        $nuevo->estado = "Activo";
        $nuevo->save();
        for ($i = 1; $i <= 7; $i++) {
            if ($i == 1) {
                $nuevoRolVista = new RolesVista;
                $nuevoRolVista->vista = "Administrador";
                $nuevoRolVista->idrol = $nuevo->id;
                $nuevoRolVista->namerol = $this->nombrerol;
                $nuevoRolVista->estado = "Inactivo";
                $nuevoRolVista->save();
            }
            if ($i == 2) {
                $nuevoRolVista = new RolesVista;
                $nuevoRolVista->vista = "CallCenter";
                $nuevoRolVista->idrol = $nuevo->id;
                $nuevoRolVista->namerol = $this->nombrerol;
                $nuevoRolVista->estado = "Inactivo";
                $nuevoRolVista->save();
            }
            if ($i == 3) {
                $nuevoRolVista = new RolesVista;
                $nuevoRolVista->vista = "Clientes";
                $nuevoRolVista->idrol = $nuevo->id;
                $nuevoRolVista->namerol = $this->nombrerol;
                $nuevoRolVista->estado = "Inactivo";
                $nuevoRolVista->save();
            }
            if ($i == 4) {
                $nuevoRolVista = new RolesVista;
                $nuevoRolVista->vista = "Empleados";
                $nuevoRolVista->idrol = $nuevo->id;
                $nuevoRolVista->namerol = $this->nombrerol;
                $nuevoRolVista->estado = "Inactivo";
                $nuevoRolVista->save();
            }
            if ($i == 5) {
                $nuevoRolVista = new RolesVista;
                $nuevoRolVista->vista = "Tratamientos";
                $nuevoRolVista->idrol = $nuevo->id;
                $nuevoRolVista->namerol = $this->nombrerol;
                $nuevoRolVista->estado = "Inactivo";
                $nuevoRolVista->save();
            }
            if ($i == 6) {
                $nuevoRolVista = new RolesVista;
                $nuevoRolVista->vista = "Recepcion";
                $nuevoRolVista->idrol = $nuevo->id;
                $nuevoRolVista->namerol = $this->nombrerol;
                $nuevoRolVista->estado = "Inactivo";
                $nuevoRolVista->save();
            }
            if ($i == 7) {
                $nuevoRolVista = new RolesVista;
                $nuevoRolVista->vista = "Inventario";
                $nuevoRolVista->idrol = $nuevo->id;
                $nuevoRolVista->namerol = $this->nombrerol;
                $nuevoRolVista->estado = "Inactivo";
                $nuevoRolVista->save();
            }
        }
        $this->crear = false;
        $this->reset(['nombrerol', 'descripcion']);
        $this->emitTo('roles.lista-roles', 'render');
        $this->emitTo('panel', 'render');
        $this->emitTo('administrador', 'render');
        $this->emit('alert', '¡Rol creado satisfactoriamente!');
    }
}
