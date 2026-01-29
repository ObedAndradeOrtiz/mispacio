<?php

namespace App\Http\Livewire\Registros;

use App\Models\Areas;
use App\Models\Productos;
use App\Models\registroinventario;
use App\Models\User;
use Illuminate\Support\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;

class RegCompras extends Component
{
    use WithPagination;
    public $fechaInicioMes;
    public $fechaActual;
    public $areas;
    public $busqueda;
    public $areaseleccionada;
    public $usuarioseleccionado;
    protected $listeners = ['render' => 'render', 'eliminarCompra' => 'eliminarCompra'];
    public function mount()
    {
        $this->fechaInicioMes = Carbon::now()->toDateString();
        $this->fechaActual = Carbon::now()->toDateString();
    }
    public function render()
    {
        $this->areas = Areas::where('estado', 'Activo')->get();
        $users = User::where('rol', '!=', 'Cliente')->orderBy('id', 'desc')->get();
        return view('livewire.registros.reg-compras', compact('users'));
    }
    public function eliminarCompra($idcompra)
    {
        $traspaso = registroinventario::find($idcompra);
        $producto = Productos::find($traspaso->idproducto);
        $producto->cantidad = $producto->cantidad - $traspaso->cantidad;
        $producto->save();
        $traspaso->delete();
        $this->render();
        $this->emit('alert', '¡Compra eliminado satisfactoriamente!');
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