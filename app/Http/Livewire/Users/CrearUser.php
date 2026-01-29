<?php

namespace App\Http\Livewire\Users;

use Livewire\Component;
use App\Models\User;
use App\Models\Areas;
use App\Models\Roles;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Hash;

class CrearUser extends Component
{
    public $llamada;
    public $name;
    public $email;
    public $password;
    public $password2;
    public $telefono;
    public $responsable;
    public $estado = "Activo";
    public $crear = false;
    public $sueldo = "0";
    public $cuenta;
    public $sucursal;
    public $areas;
    public $rol;
    public $roles;
    public $horainicio;
    public $horafin;
    public $fechainicio;
    use WithFileUploads;
    public $image;
    public function guardartodo()
    {
        $nuevo = new User;
        $nuevo->name = $this->name;
        $nuevo->email = $this->email;
        $nuevo->rol = $this->rol;
        $nuevo->tesoreria = "Inactivo";
        $nuevo->telefono = $this->telefono;
        $nuevo->sueldo = $this->sueldo;
        $nuevo->cuentaahorro = $this->cuenta;
        $nuevo->password = Hash::make($this->password);
        if ($this->sueldo == "0") {
            $nuevo->sueldo = "0";
        } else {
            $nuevo->sueldo = $this->sueldo;
        }
        $nuevo->estado = "Activo";
        $nuevo->sucursal = $this->sucursal;
        $nuevo->horainicio = $this->horainicio;
        $nuevo->horafin = $this->horafin;
        $nuevo->fechainicio = $this->fechainicio;
        $nuevo->save();
        $this->reset(['crear']);
        $this->emitTo('users.lista-user', 'render');
        $this->emit('alert', '¡Usuario creado satisfactoriamente!');
    }
    public function render()
    {
        $this->roles = Roles::where('estado', 'Activo')->get();
        $this->areas = Areas::where('estado', 'Activo')->get();
        return view('livewire.users.crear-user');
    }
}
