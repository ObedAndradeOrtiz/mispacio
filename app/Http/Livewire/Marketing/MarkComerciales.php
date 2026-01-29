<?php

namespace App\Http\Livewire\Marketing;

use Livewire\Component;
use App\Models\Areas;
use App\Models\cuentacomercial;
use App\Models\Tratamiento;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use App\Models\registroinventario;
use App\Models\Productos;
use App\Models\tarjetas;

class MarkComerciales extends Component
{
    use WithPagination;
    public $areaseleccionada;
    public $usuarioseleccionado;
    public $cuentaseleccionado;
    public $fechaInicioMes;
    public $fechaActual;
    public $areas;
    public $cuentas;
    public $tarjetas;
    public $creartarjeta = false;
    protected $listeners = ['render' => 'render', 'inactivar' => 'inactivar'];
    public function mount()
    {
        $this->fechaInicioMes = Carbon::now()->toDateString();
        $this->fechaActual = Carbon::now()->toDateString();
    }
    public function render()
    {
        $this->tarjetas = tarjetas::where('estado', 'Activo')->orderBy('nombretarjeta', 'desc')->get();
        $this->cuentas = cuentacomercial::where('estado', 'Activo')->orderBy('id', 'desc')->get();
        $this->areas = Areas::where('estado', 'Activo')->get();
        $users = User::where('rol', '!=', 'Cliente')->orderBy('id', 'desc')->get();
        return view('livewire.marketing.mark-comerciales', compact('users'));
    }
    public function inactivar($idcuenta)
    {
        $cuenta = cuentacomercial::find($idcuenta);
        $cuenta->delete();
        $this->render();
    }
}
