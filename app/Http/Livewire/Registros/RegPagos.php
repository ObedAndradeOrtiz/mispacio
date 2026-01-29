<?php

namespace App\Http\Livewire\Registros;


use App\Models\Areas;
use App\Models\Operativos;
use App\Models\Pagos;
use App\Models\registropago;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use App\Models\registroinventario;

class RegPagos extends Component
{
    use WithPagination;
    public $open = false;
    public $user;
    public $telefono;
    public $busquedaqr = "";
    public $busquedaefec = "";
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
    public $usuarioseleccionado = '';
    public $registro;
    public $editar = false;
    protected $listeners = ['render' => 'render', 'eliminar' => 'eliminar'];
    protected $rules = [
        'registro.monto' => 'required',
        'registro.modo' => 'required',
        'registro.fecha' => 'required',
    ];
    public function mount()
    {
        $this->fechaInicioMes = Carbon::now()->toDateString();
        $this->fechaActual = Carbon::now()->toDateString();
        $this->registroinv = registroinventario::where('motivo', 'personal')->where('iduser', Auth::user()->id)->where('fecha', '<=', $this->fechaActual)
            ->where('fecha', '>=', $this->fechaInicioMes)->get();
    }
    public function render()
    {
        $total_monto_citas = DB::table('registropagos')
            ->where('nombrecliente', 'ilike', '%' . $this->busquedaefec . '%')
            ->where('sucursal', 'ilike', '%' . $this->areaseleccionada . '%')
            ->where('responsable', 'ilike', '%' . $this->usuarioseleccionado . '%')
            ->where('fecha', '<=', $this->fechaActual)
            ->where('fecha', '>=', $this->fechaInicioMes)
            ->where('modo', 'ilike', '%Efectivo%')
            ->paginate(5);
        $total_monto_qr_lista = DB::table('registropagos')
            ->where('nombrecliente', 'ilike', '%' . $this->busquedaqr . '%')
            ->where('sucursal', 'ilike', '%' . $this->areaseleccionada . '%')
            ->where('responsable', 'ilike', '%' . $this->usuarioseleccionado . '%')
            ->where('estado', 'Activo')
            ->where('modo', 'ilike', '%qr%')
            ->where('fecha', '<=', $this->fechaActual)
            ->where('fecha', '>=', $this->fechaInicioMes)
            ->paginate(5);
        $total_monto_citasc = DB::table('registropagos')
            ->where('nombrecliente', 'ilike', '%' . $this->busquedaefec . '%')
            ->where('sucursal', 'ilike', '%' . $this->areaseleccionada . '%')
            ->where('responsable', 'ilike', '%' . $this->usuarioseleccionado . '%')
            ->where('fecha', '<=', $this->fechaActual)
            ->where('fecha', '>=', $this->fechaInicioMes)
            ->where('modo', 'ilike', '%Efectivo%')
            ->count();
        $total_monto_qr_listac = DB::table('registropagos')
            ->where('nombrecliente', 'ilike', '%' . $this->busquedaqr . '%')
            ->where('sucursal', 'ilike', '%' . $this->areaseleccionada . '%')
            ->where('responsable', 'ilike', '%' . $this->usuarioseleccionado . '%')
            ->where('estado', 'Activo')
            ->where('modo', 'ilike', '%qr%')
            ->where('fecha', '<=', $this->fechaActual)
            ->where('fecha', '>=', $this->fechaInicioMes)
            ->count();
        $this->areas = Areas::where('estado', 'Activo')->get();
        $users = User::where('rol', '!=', 'Cliente')->get();
        return view('livewire.registros.reg-pagos', compact('users', 'total_monto_citas', 'total_monto_qr_lista', 'total_monto_citasc', 'total_monto_qr_listac'));
    }
    public function guardartodo()
    {
        $this->validate();
        $this->registro->save();
        $this->editar = false;
        $this->emitTo('registros.reg-pagos', 'render');
        $this->emitTo('registros.lista-registros', 'render');
        $this->emit('alert', '¡Pago editado!');
    }
    public function editarpago($idregistro)
    {
        $this->registro = registropago::find($idregistro);
        $this->editar = true;
    }
    public function eliminar($idregistro)
    {
        $registro = registropago::find($idregistro);
        $registro->delete();
        $this->emitTo('registros.reg-pagos', 'render');
        $this->emitTo('registros.lista-registros', 'render');
    }
}