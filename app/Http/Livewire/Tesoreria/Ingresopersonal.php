<?php

namespace App\Http\Livewire\Tesoreria;

use App\Models\Calls;
use App\Models\Operativos;
use App\Models\Empresas;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Areas;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class Ingresopersonal extends Component
{
    use WithPagination;
    public $open = false;
    public $area;
    public $modo = "Todos";
    public $telefono;
    public $busqueda = "";
    public $actividad = "Areas";
    public $tipos = "Sueldo";
    public $idempresa;
    public $verclienteslogic = false;
    public $seguridad = true;
    public $contra = "";
    public $activar = false;
    public $openAreaGasto = false;
    public $tipogasto;
    public $modopago;
    public $fechaInicioMes;
    public $fechaActual;
    public $gastos_total;
    public $restante = 0;
    public $obtenido_area;
    public $gastos_suma;
    protected $listeners = ['render' => 'render'];
    public function mount()
    {
        $this->fechaInicioMes = Carbon::now()->startOfMonth()->toDateString();
        $this->fechaActual = Carbon::now()->toDateString();
    }
    public function render()
    {
        $usersl = User::where('rol', 'Interno')->where('empresa', 'BBC')->get();
        $areas = Areas::where('area', 'like', '%' . $this->busqueda . '%')->where('estado', 'Activo')->orderBy('id', 'desc')->latest()->limit(10)->get();
        $user = User::find(Auth::user()->id);
        $areas = Areas::where('area', 'like', '%' . $this->busqueda . '%')->where('estado', 'Activo')->orderBy('id', 'desc')->latest()->limit(10)->get();
        return view('livewire.tesoreria.ingresopersonal', compact('areas', 'usersl'));
    }
    public function confirmar()
    {
        if ($this->contra == "tesoreria") {
            $this->seguridad = false;
            if ($this->activar == true) {
                $user = User::find(Auth::user()->id);
                $user->tesoreria = "Activo";
                $user->save();
            }
        } else {
            $this->emit('error', '¡Contraseña incorrecta!');
        }
    }
}
