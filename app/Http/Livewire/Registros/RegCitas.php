<?php

namespace App\Http\Livewire\Registros;


use App\Models\Areas;
use App\Models\Operativos;
use App\Models\Pagos;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use App\Models\registroinventario;

class RegCitas extends Component
{
    use WithPagination;
    public $busquedacli = '';
    public $botonClick = 'Agendado';
    public $open = false;
    public $user;
    public $telefono;
    public $busqueda = "";
    public $actividad = "Activo";
    public $areas;
    public $areaseleccionada = '';
    public $tiporegistro = "llamadas";
    public $botonRecepcion = 'llamada';
    public $fechaInicioMes;
    public $fechaActual;
    public $tratamientoMasRepetido;
    public $cantidadRepetido;
    public $tratamientoMasRepetido2;
    public $cantidadRepetido2;
    public $tratamientoMasRepetido3;
    public $cantidadRepetido3;
    public $tratamientoMasRepetido4;
    public $cantidadRepetido4;
    public $registroinv;
    public $operativo;
    public $editar = false;
    public $usuarioseleccionado = '';
    private  $miscitaslista;
    public  $miscitaslistacount;
    public $registros;
    public $tiposeleccionado = 'Agendado';
    protected $listeners = ['render' => 'render', 'inactivar' => 'inactivar', 'activar' => 'activar'];
    protected $rules = [
        'operativo.cantidad' => 'required',
        'operativo.pagado' => 'required',
        'operativo.fecha' => 'required',
    ];
    public function mount()
    {

        $this->fechaInicioMes = Carbon::now()->toDateString();
        $this->fechaActual = Carbon::now()->toDateString();
        $this->registroinv = registroinventario::where('motivo', 'personal')->where('iduser', Auth::user()->id)->where('fecha', '<=', $this->fechaActual)
            ->where('fecha', '>=', $this->fechaInicioMes)->get();
        $this->registros = Operativos::where('ingreso', '1')->get();
    }
    public function render()
    {
        $miscitaslista = DB::table('operativos')->where(function ($query) {
            $query->OrWhere('empresa', 'ilike', '%' .  $this->busquedacli . '%');
            $query->OrWhere('telefono', 'ilike', '%' .  $this->busquedacli . '%');
        })
            ->where('area', 'ilike', '%' .  $this->areaseleccionada . '%')
            ->where('responsable', 'ilike', '%' .  $this->usuarioseleccionado . '%')
            ->where('fecha', '>=',  $this->fechaInicioMes)
            ->where('fecha', '<=',  $this->fechaActual)
            ->paginate(10);
        $miscitaslistasinpag = DB::table('operativos')->where(function ($query) {
            $query->OrWhere('empresa', 'ilike', '%' .  $this->busquedacli . '%');
            $query->OrWhere('telefono', 'ilike', '%' .  $this->busquedacli . '%');
        })
            ->where('area', 'ilike', '%' .  $this->areaseleccionada . '%')
            ->where('responsable', 'ilike', '%' .  $this->usuarioseleccionado . '%')
            ->where('fecha', '>=',  $this->fechaInicioMes)
            ->where('fecha', '<=',  $this->fechaActual)
            ->get();
        $this->miscitaslistacount = DB::table('operativos')
            ->where(function ($query) {
                $query->OrWhere('empresa', 'ilike', '%' .  $this->busquedacli . '%');
                $query->OrWhere('telefono', 'ilike', '%' .  $this->busquedacli . '%');
            })
            ->where('area', 'ilike', '%' .  $this->areaseleccionada . '%')
            ->where('responsable', 'ilike', '%' .  $this->usuarioseleccionado . '%')
            ->where('fecha', '>=',  $this->fechaInicioMes)
            ->where('fecha', '<=',  $this->fechaActual)
            ->count();
        $this->areas = Areas::where('estado', 'Activo')->get();
        $this->registros = Operativos::where('ingreso', '1')
            ->where(function ($query) {
                $query->OrWhere('empresa', 'ilike', '%' .  $this->busquedacli . '%');
                $query->OrWhere('telefono', 'ilike', '%' .  $this->busquedacli . '%');
            })
            ->where('area', 'ilike', '%' .  $this->areaseleccionada . '%')
            ->where('responsable', 'ilike', '%' .  $this->usuarioseleccionado . '%')
            ->where('fecha', '>=',  $this->fechaInicioMes)
            ->where('fecha', '<=',  $this->fechaActual)->get();
        $users = User::where('rol', '!=', 'Cliente')->orderBy('id', 'desc')->get();
        return view('livewire.registros.reg-citas', compact('users', 'miscitaslista', 'miscitaslistasinpag'));
    }
    public function editarcita($idoperativo)
    {
        $this->operativo = Pagos::where('idoperativo', $idoperativo)->first();
        $this->editar = true;
    }

    public function guardartodo()
    {
        $this->operativo->save();
        $this->emit('alert', '¡Cita editada satisfactoriamente!');
    }
    public function inactivar($idCall)
    {
        $operativo = Operativos::find($idCall);
        DB::table('pagos')->where('idoperativo', $operativo->id)->delete();
        DB::table('registropagos')->where('idoperativo', $operativo->id)->delete();
        DB::table('historial_clientes')->where('idoperativo', $operativo->id)->delete();
        $operativo->delete();
        $this->emitTo('registros.reg-citas', 'render');
        $this->emit('alert', '¡Cita eliminada!');
    }
    public function activar($idCall)
    {
        $operativo = Operativos::find($idCall);
        $operativo->cantidadtotal = 0;
        $operativo->save();
        $this->emitTo('registros.reg-citas', 'render');
        $this->emit('alert', '¡Cita activada!');
    }
}
