<?php

namespace App\Http\Livewire\Registros;

use App\Models\Calls;
use App\Models\Operativos;
use App\Models\Empresas;
use App\Models\Pagos;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Areas;
use App\Models\registroinventario;
use App\Models\Productos;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MisRegistros extends Component
{
    public $openAreaGasto = false;
    public $fechaInicioMes;
    public $fechaActual;
    public $usersData;
    public $registroinv;
     protected $listeners = ['render' => 'render', 'eliminar' => 'eliminar'];
    public function mount()
    {
        $this->fechaInicioMes = Carbon::now()->toDateString();
        $this->fechaActual = Carbon::now()->toDateString();

    }
    public function render()
    {
        $this->registroinv = registroinventario::where('motivo', 'personal')->where('iduser', Auth::user()->id)->where('fecha', '<=', $this->fechaActual)
        ->where('fecha', '>=', $this->fechaInicioMes)->get();
        return view('livewire.registros.mis-registros');
    }
    public function eliminar($idpro)
    {
        $registro = registroinventario::find($idpro);
        $producto = Productos::find($registro->idproducto);
        $producto->cantidad = $producto->cantidad + $registro->cantidad;
        $producto->save();
        $registro->delete();
        $this->emitTo('registros.reg-producto', 'render');
        $this->emitTo('registros.lista-registros', 'render');
    }
}
