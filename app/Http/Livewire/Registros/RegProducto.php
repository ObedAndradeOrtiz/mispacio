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

class RegProducto extends Component
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
    public $registro;
    public $editar;
    protected $listeners = ['render' => 'render', 'eliminar' => 'eliminar', 'editarProducto' => 'editarProducto'];
    protected $rules = [
        'registro.precio' => 'required',
        'registro.modo' => 'required',
        'registro.fecha' => 'required',
        'registro.cantidad' => 'required',
    ];
    public function mount()
    {
        $this->fechaInicioMes = Carbon::now()->toDateString();
        $this->fechaActual = Carbon::now()->toDateString();
    }
    public function render()
    {

        $total_inventario_farmacia = DB::table('registroinventarios')
            ->where('motivo', 'farmacia')
            ->where('sucursal', 'ilike', '%' . $this->areaseleccionada . '%')
            ->where('nombreproducto', 'ilike', '%' . $this->busquedafarma . '%')
            ->where('fecha', '<=', $this->fechaActual)
            ->where('fecha', '>=', $this->fechaInicioMes)
            ->orderBy('created_at', 'desc')
            ->paginate(5);
        $registroinv = DB::table('registroinventarios')
            ->where('motivo', 'personal')
            ->where('nombreproducto', 'ilike', '%' . $this->busquedagabi . '%')
            ->where('sucursal', 'ilike', '%' . $this->areaseleccionada . '%')
            ->where('fecha', '<=', $this->fechaActual)
            ->where('fecha', '>=', $this->fechaInicioMes)
            ->orderBy('created_at', 'desc')
            ->paginate(5);
        $registroinvcompra = DB::table('registroinventarios')
            ->whereIn('motivo', ['compra', 'farmacia'])
            ->where('nombreproducto', 'ilike', '%' . $this->busqueda . '%')
            ->where('modo', 'ilike', '%' . $this->modopago . '%')
            ->where('sucursal', 'ilike', '%' . $this->areaseleccionada . '%')
            ->where('fecha', '<=', $this->fechaActual)
            ->where('fecha', '>=', $this->fechaInicioMes)
            ->orderBy('created_at', 'desc')
            ->paginate(5);
        $registroimpresiones = DB::table('traspasostexts')
            ->where('motivo', 'venta')
            ->where('sucursal_origen', $this->areaseleccionada)

            ->orderBy('fecha', 'desc')
            ->paginate(15);
        $total_inventario_farmaciac = DB::table('registroinventarios')
            ->where('motivo', 'farmacia')
            ->where('sucursal', 'ilike', '%' . $this->areaseleccionada . '%')
            ->where('nombreproducto', 'ilike', '%' . $this->busquedafarma . '%')
            ->where('fecha', '<=', $this->fechaActual)
            ->where('fecha', '>=', $this->fechaInicioMes)
            ->count();
        $registroinvc = DB::table('registroinventarios')
            ->where('motivo', 'personal')
            ->where('nombreproducto', 'ilike', '%' . $this->busquedagabi . '%')
            ->where('modo', 'ilike', '%' . $this->modopago . '%')
            ->where('sucursal', 'ilike', '%' . $this->areaseleccionada . '%')
            ->where('fecha', '<=', $this->fechaActual)
            ->where('fecha', '>=', $this->fechaInicioMes)
            ->count();
        $registroinvcomprac = DB::table('registroinventarios')
            ->where('motivo', 'compra')
            ->where('nombreproducto', 'ilike', '%' . $this->busqueda . '%')
            ->where('modo', 'ilike', '%' . $this->modopago . '%')
            ->where('sucursal', 'ilike', '%' . $this->areaseleccionada . '%')
            ->where('fecha', '<=', $this->fechaActual)
            ->where('fecha', '>=', $this->fechaInicioMes)
            ->count();

        $this->areas = Areas::where('estado', 'Activo')->get();
        $users = User::where('rol', '!=', 'Cliente')->orderBy('id', 'desc')->get();
        return view('livewire.registros.reg-producto', compact('registroimpresiones', 'registroinv', 'registroinvcompra', 'total_inventario_farmaciac', 'users', 'total_inventario_farmacia', 'registroinvcomprac', 'registroinvc'));
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
    public function editarProducto($idregistro)
    {
        $this->registro = registroinventario::find($idregistro);
        $this->editar = true;
    }
    public function guardartodo()
    {
        $this->validate();
        $this->registro->save();
        $this->editar = false;
        $this->emitTo('registros.reg-producto', 'render');
        $this->emitTo('registros.lista-registros', 'render');
        $this->emit('alert', '¡Venta editada!');
    }
    public function imprimirTrapaso($idtraspaso)
    {

        $text = DB::table('traspasostexts')
            ->where('id', $idtraspaso)
            ->pluck('texto')
            ->first(); // Usamos first() para obtener el primer resultado

        $data = [
            'id' => Auth::user()->sesionsucursal,
            'texto' => $text
        ];
        $url = "http://127.0.0.1:5001/imprimirticket";
        $response = Http::post($url, $data);
        $this->render();
        $this->emit('alert', '¡Impreso satisfactoriamente!');
    }
}