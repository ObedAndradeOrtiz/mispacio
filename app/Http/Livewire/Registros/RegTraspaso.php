<?php

namespace App\Http\Livewire\Registros;

use Livewire\Component;
use App\Models\Areas;
use App\Models\Produccion;
use App\Models\Tratamiento;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use App\Models\registroinventario;
use App\Models\Productos;
use App\Models\trasapasostext;
use Illuminate\Support\Facades\Http;

class RegTraspaso extends Component
{
    use WithPagination;
    public $fechaInicioMes;
    public $fechaActual;
    public $areas;
    public $busqueda;
    public $areaseleccionada;
    public $usuarioseleccionado;
    public $verbau = false;
    public $impresionticket;
    public function mount()
    {
        $this->fechaInicioMes = Carbon::now()->toDateString();
        $this->fechaActual = Carbon::now()->toDateString();
    }
    public function render()
    {
        $registroimpresiones = DB::table('traspasostexts')
            ->where('motivo', 'traspaso')
            ->where('sucursal_destino', 'ilike', '%' . $this->areaseleccionada . '%')
            ->orderBy('fecha', 'desc')
            // ->where('fecha', '>=', $this->fechaInicioMes)
            ->paginate(10);
        $this->areas = Areas::where('estado', 'Activo')->get();
        $users = User::where('rol', '!=', 'Cliente')->orderBy('id', 'desc')->get();
        return view('livewire.registros.reg-traspaso', compact('users', 'registroimpresiones'));
    }
    public function eliminarTrapaso($idtraspaso)
    {

        $registro = registroinventario::find($idtraspaso);
        if ($registro->sucursal == "ALMACEN DE PRODUCCION") {
            $nuevaroduccion = Produccion::find($registro->idcliente);
            $nuevoproducto = Productos::where('sucursal', $registro->modo)->where('nombre', $registro->nombreproducto)->first();
            $nuevoproducto->cantidad = $nuevoproducto->cantidad - $registro->cantidad;
            $nuevoproducto->save();
            $nuevaroduccion->cantidad_actual = $nuevaroduccion->cantidad_actual + $registro->cantidad;
            $nuevaroduccion->exportado = $nuevaroduccion->exportado - $registro->cantidad;
            $nuevaroduccion->save();
            $registro->delete();
        } else {
            $traspaso = registroinventario::find($idtraspaso);
            $producto = Productos::find($traspaso->idproducto);
            $producto->cantidad = $producto->cantidad + $traspaso->cantidad;
            $producto->save();
            $producto = Productos::where('nombre',  $producto->nombre)->where('sucursal', $traspaso->modo)->first();
            $producto->cantidad = $producto->cantidad - $traspaso->cantidad;
            $producto->save();
            $traspaso->delete();
        }

        $this->emitTo('registros.reg-producto', 'render');
        $this->emitTo('registros.lista-registros', 'render');
        $this->emit('alert', '¡Traspaso eliminado!');

        $this->render();
        $this->emit('alert', '¡Trapaso eliminado satisfactoriamente!');
    }
    public function verTraspaso($idtraspaso)
    {
        $this->impresionticket = $idtraspaso;
        $this->verbau = true;
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
        $this->emit('alert', '¡Trapaso impreso satisfactoriamente!');
    }
}
