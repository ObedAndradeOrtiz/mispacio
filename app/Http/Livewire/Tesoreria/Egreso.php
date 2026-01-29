<?php

namespace App\Http\Livewire\Tesoreria;

use App\Models\Calls;
use App\Models\Operativos;
use App\Models\Empresas;
use App\Models\User;
use Livewire\Component;
use App\Models\Gastos;
use Illuminate\Support\Facades\Auth;
use App\Models\Areas;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Egreso extends Component
{
    use WithPagination;
    public $open = false;
    public $area;
    public $modo = "Todos";
    public $telefono;
    public $busqueda = "";
    public $actividad = "Areas";
    public $tipos = "Historial";
    public $idempresa;
    public $verclienteslogic = false;
    public $seguridad = true;
    public $contra = "";
    public $activar = false;
    public $openAreaGasto = false;
    public $tipogasto;
    public $modopago = "QR";
    public $fechagasto;
    public $montoegreso;
    public $destinario;
    public $comentario = "";
    public $modogeneral = "Todos";
    public $fechaInicioMes;
    public $fechaActual;
    public $sucursal;
    public $cartera = 'Caja';
    protected $listeners = ['render' => 'render'];
    public function mount()
    {
        $this->fechaInicioMes = Carbon::now()->toDateString();
        $this->fechaActual = Carbon::now()->toDateString();
        $this->fechagasto = Carbon::now()->toDateString();
    }
    public function render()
    {
        $areas = Areas::where('estado', 'Activo')->orderBy('id', 'desc')->get();
        $usersl = User::where('name', 'like', '%' . $this->busqueda . '%')->where('estado', 'Activo')->where('rol', '!=', 'Cliente')->get();
        return view('livewire.tesoreria.egreso', compact('usersl', 'areas'));
    }
    public function confirmar()
    {

        if (($this->montoegreso && $this->modopago && $this->tipogasto && $this->fechagasto) != "") {
            $nuevo = new Gastos;

            $nuevo->idarea = $this->sucursal;
            $miarea = Areas::find($this->sucursal);
            $nuevo->area = $miarea->area;
            $nuevo->modo = $this->modopago;
            $nuevo->fechainicio = $this->fechagasto;
            $nuevo->cantidad = $this->montoegreso;
            $nuevo->empresa = $this->comentario;
            $nuevo->pertence = $this->cartera;
            $usuarionuevo = User::where('name', $this->destinario)->get();
            $idnew = 0;
            foreach ($usuarionuevo as $gg) {
                $idnew = $gg->id;
            }
            $nuevo->iduser =  Auth::user()->id;
            $nuevo->nameuser = Auth::user()->name;
            $nuevo->tipo = $this->tipogasto;
            if ($this->tipogasto == "SUELDO") {

                $nuevo->numero = $idnew;
            }
            if ($this->tipogasto == "ADELANTO AL PERSONAL") {

                $nuevo->numero = $idnew;
            }
            if ($this->tipogasto == "Bono") {
                $nuevo->tipo = $this->tipogasto;
                $nuevo->pertence = $this->destinario;
            }
            $nuevo->save();
            $this->reset(['openAreaGasto', 'montoegreso', 'modopago', 'tipogasto', 'fechagasto']);
            $this->emitTo('tesoreria.egreso-interno', 'render');
            $this->emit('alert', '¡Egreso guardado satisfactoriamente!');
        } else {
            $this->emit('error', '¡Algo anda mal!');
        }
    }
}
