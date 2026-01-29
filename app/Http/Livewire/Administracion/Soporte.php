<?php

namespace App\Http\Livewire\Administracion;

use App\Http\Livewire\Area;
use App\Models\Areas;
use App\Models\HistorialCambios;
use App\Models\Productos;
use App\Models\registroinventario;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Soporte extends Component
{
    public $mostrarOpciones = true;
    public $opcionSeleccionada;
    public $sucursales;
    public $sucursalSeleccionada;
    public $ventaId;
    public $mensaje;
    public $crear = false;
    public function mount()
    {
        $this->sucursales = Areas::where('estado', 'Activo')->get();
    }
    public function render()
    {
        return view('livewire.administracion.soporte');
    }
    public function seleccionarOpcion($opcion)
    {
        $this->opcionSeleccionada = $opcion;
        if ($opcion === 'sucursal') {
            $this->sucursales = Areas::where('estado', 'Activo')->get();
        }
    }
    public function cambiarSucursal($idsucursal)
    {

        $user = User::find(Auth::user()->id);
        $contador = HistorialCambios::where('idusuario', $user->id)->where('fecha', date('Y-m-d'))->count();
        if ($contador < 2) {
            $user->sucseleccionada = $idsucursal;
            $user->sesionsucursal = $idsucursal;
            $sucursal = Areas::find($idsucursal);
            $user->sucursal = $sucursal->area;
            $user->save();
            $cambio = new HistorialCambios;
            $cambio->tipo = 'sucursal';
            $cambio->idusuario = $user->id;
            $cambio->idsucursal = $sucursal->id;
            $cambio->nombreusuario = $user->name;
            $cambio->nombresucursal = $sucursal->area;
            $cambio->tipo = 'cambio-sucursal';
            $cambio->fecha = date('Y-m-d');
            $cambio->estado = 'Activo';
            $cambio->save();
            return redirect()->route('dashboard');
        } else {
            $this->emit('error', '¡No puedes realizar más cambios!');
        }
    }
    public function eliminarVenta()
    {
        $registro = registroinventario::find($this->ventaId);
        if ($registro) {
            $producto = Productos::find($registro->idproducto);
            $producto->cantidad = $producto->cantidad + $registro->cantidad;
            $producto->save();
            $registro->delete();
            $cambio = new HistorialCambios;
            $user = User::find(Auth::user()->id);
            $cambio->tipo = 'sucursal';
            $cambio->idusuario = $user->id;
            $cambio->nombreusuario = $user->name;
            $cambio->tipo = 'cambio-venta';
            $cambio->fecha = date('Y-m-d');
            $cambio->estado = 'Activo';
            $cambio->save();
            $this->crear = false;
            $this->emit('alert', '¡Venta eliminada!');
        } else {
            $this->emit('error', '¡No existe esta venta!');
        }
    }
}