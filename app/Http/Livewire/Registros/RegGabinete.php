<?php

namespace App\Http\Livewire\Registros;

use App\Models\Areas;
use App\Models\Tratamiento;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use App\Models\registroinventario;
use App\Models\Productos;
use Illuminate\Support\Facades\Http;

class RegGabinete extends Component
{
    use WithPagination;
    public $open = false;
    public $user;
    public $telefono;
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
    public $modopago = '';
    public $busqueda = '';
    public $busquedagabi = '';
    public $busquedafarma = '';
    protected $listeners = ['render' => 'render', 'eliminarGabinete' => 'eliminar'];

    public function mount()
    {
        $this->fechaInicioMes = Carbon::now()->toDateString();
        $this->fechaActual = Carbon::now()->toDateString();
    }
    public function render()
    {
        $registroinvc = DB::table('registroinventarios')
            ->where('motivo', 'personal')
            ->where('nombreproducto', 'ilike', '%' . $this->busquedagabi . '%')
            ->where('sucursal', 'ilike', '%' . $this->areaseleccionada . '%')
            ->where('fecha', '<=', $this->fechaActual)
            ->where('fecha', '>=', $this->fechaInicioMes)
            ->count();

        $this->areas = Areas::where('estado', 'Activo')->get();
        $users = User::where('rol', '!=', 'Cliente')->orderBy('id', 'desc')->get();
        return view('livewire.registros.reg-gabinete', compact('registroinvc'));
    }
    public function eliminar($idpro)
    {
        $registro = registroinventario::find($idpro);
        $producto = Productos::find($registro->idproducto);
        $producto->cantidad = $producto->cantidad + $registro->cantidad;
        $producto->save();
        $registro->delete();
        $this->emit('alert', '¡Registro eliminado!');
        $this->emitTo('registros.reg-gabinete', 'render');
        $this->emitTo('registros.reg-producto', 'render');
        $this->emitTo('registros.lista-registros', 'render');
    }
}
