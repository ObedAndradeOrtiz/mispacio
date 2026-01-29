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
use App\Models\transacciones;

class MarkTransacciones extends Component
{
    use WithPagination;
    public $areaseleccionada;
    public $usuarioseleccionado;
    public $cuentaseleccionado;
    public $fechaInicioMes;
    public $fechaActual;
    public $areas;
    public $cuentas;
    public $creartransaccion = false;
    public $registrotransacciones;
    public $registrotransaccionestotal;
    protected $listeners = ['render' => 'render', 'deleteTransaccion'  => 'deleteTransaccion'];
    public function mount()
    {
        $this->fechaInicioMes = Carbon::now()->toDateString();
        $this->fechaActual = Carbon::now()->toDateString();
    }
    public function render()
    {

        $this->cuentas = cuentacomercial::where('estado', 'Activo')->paginate(10);
        $this->areas = Areas::where('estado', 'Activo')->get();
        $users = User::where('rol', '!=', 'Cliente')->orderBy('id', 'desc')->get();
        return view('livewire.marketing.mark-transacciones', compact('users'));
    }
}
