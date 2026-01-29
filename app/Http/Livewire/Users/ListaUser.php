<?php

namespace App\Http\Livewire\Users;

use App\Models\Calls;
use App\Models\User;
use App\Models\Areas;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\activacion;
use App\Models\Roles;
use Illuminate\Support\Facades\Hash;
use Livewire\WithPagination;

class ListaUser extends Component
{
    use WithPagination;
    public $open = false;
    public $user;
    public $telefono;
    public $busqueda = "";
    public $actividad = "Activo";
    public $areas;
    public $areaseleccionada = '';
    public $activado;
    public $fechaInicioMes;
    public $fechaActual;
    public $estadoUser = 'Activo';
    public $rolseleccionado = '';
    public $roles;
    protected $listeners = ['render' => 'render', 'configurarSistema' => 'configurarSistema'];
    public function mount()
    {
        $this->fechaInicioMes = date("Y-m-01");
        $this->fechaActual = now()->format('Y-m-d');
    }
    public function render()
    {

        $this->areas = Areas::where('estado', 'Activo')->get();
        if ($this->estadoUser == 'todos') {
            $users = User::where('sucursal', 'ilike', '%' . $this->areaseleccionada . '%')
                ->where('name', 'ilike', '%' . $this->busqueda . '%')
                ->where('rol', 'ilike', '%' . $this->rolseleccionado . '%')
                ->where('rol', '!=', 'Cliente')
                ->orderByRaw("CASE WHEN path IS NULL OR path = '' THEN 1 ELSE 0 END")
                ->orderBy('name', 'asc')
                ->paginate(10);
        } else {
            $users = User::where('sucursal', 'ilike', '%' . $this->areaseleccionada . '%')
                ->where('name', 'ilike', '%' . $this->busqueda . '%')
                ->where('rol', 'ilike', '%' . $this->rolseleccionado . '%')
                ->where('rol', '!=', 'TARJETAS')
                ->where('rol', '!=', 'mbq')
                ->where('rol', '!=', 'Cliente')
                ->where('estado', $this->estadoUser)
                ->orderByRaw("CASE WHEN path IS NULL OR path = '' THEN 1 ELSE 0 END")
                ->orderBy('name', 'asc')
                ->paginate(10);
        }

        $this->roles = Roles::where('estado', 'Activo')->get();
        return view('livewire.users.lista-user', compact('users'));
    }
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
        $this->emit('alert', '¡Aspirante creada satisfactoriamente!');
    }
}
