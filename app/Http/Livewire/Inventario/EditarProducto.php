<?php


namespace App\Http\Livewire\Inventario;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Areas;
use App\Models\Inventario;
use App\Models\Productos;
use Illuminate\Support\Facades\DB;
use App\Models\registroinventario;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class EditarProducto extends Component
{
    protected $rules = [
        'producto.nombre' => 'required',
        'producto.descripcion' => 'required',
        'producto.path' => 'required',
        'producto.precio' => 'required',
        'producto.cantidad' => 'required',
        'producto.estado' => 'required',
        'producto.sucursal' => 'required',
        'producto.expiracion' => 'required',
        'producto.idinventario' => 'required',
        'producto.inicio' => 'required',
        'aux.cantidad' => 'required'

    ];
    public $producto;
    public $idinventario;
    public $image;
    public $editar = false;
    public $empresas;
    public $cantidadanterior;
    public $aux;
    public $expiracion;
    public $nombreandterior = '';
    public function mount($producto)
    {
        $this->producto = $producto;
        $this->aux = $producto;
        $this->cantidadanterior = $producto->cantidad;
        $this->expiracion = $this->producto->expiracion;
        $this->nombreandterior = $this->producto->nombre;
        $this->idinventario = $this->producto->idinventario;
    }
    public function render()
    {
        $this->empresas = Areas::where('estado', 'Activo')->get();
        return view('livewire.inventario.editar-producto');
    }
    public function guardaredicion()
    {

        if ($this->image) {
            $image = $this->image->store('public/productos');
            $image = 'productos/' . basename($image);
            $this->producto->path = $image;
        }
        $registro = new registroinventario;
        $sucursal = Areas::where('area', $this->producto->sucursal)->first();
        $registro->idsucursal = $sucursal->id;
        $registro->motivo = 'Modificaciones';
        $registro->cantidad = $this->producto->cantidad;
        $registro->sucursal = $this->producto->sucursal;
        $registro->idproducto = $this->producto->id;
        $registro->modo =  $this->aux->cantidad;
        $registro->nombreproducto = $this->producto->nombre;

        $nombres = Productos::where('nombre', $this->nombreandterior)->get();
        foreach ($nombres as $item) {
            $item->nombre = $this->producto->nombre;
            $item->idinventario = $this->idinventario;
            $item->precio = $this->producto->precio;
            $item->save();
        }
        $registro->iduser = Auth::user()->id;
        $registro->fecha = Carbon::now()->toDateString();
        $registro->estado = 'Activo';
        $registro->save();
        $this->producto->save();
        $this->editar = false;
        $this->emitTo('inventario.lista-inventario', 'render');
        $this->emit('alert', '¡Producto editado satisfactoriamente!');
    }
    public function desactivar()
    {

        $productos = Productos::where('nombre', $this->producto->nombre)->get();
        foreach ($productos as $producto) {
            $producto->delete();
        }
        $inventario = Inventario::where('nombre', $this->producto->nombre)->first();
        $inventario->delete();
        $this->emitTo('inventario.lista-inventario', 'render');
        $this->emit('alert', '¡Productos eliminados!');
    }

    public function activar()
    {
        $this->producto->estado = 'Activo';
        $this->producto->save();
        $this->emitTo('inventario.lista-inventario', 'render');
        $this->emit('alert', '¡Producto activado!');
    }
}
