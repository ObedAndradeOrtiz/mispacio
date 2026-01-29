<?php

namespace App\Http\Livewire\Inventario;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Productos;
use App\Models\Areas;
use App\Models\registroinventario;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CrearProducto extends Component
{
    public $nombre;
    public $descripcion;
    public $cantidad;
    public $precio;
    public $image;
    public $crear = false;
    public $empresas;
    public $sucursal;
    use WithFileUploads;
    public function render()
    {
        $this->empresas = Areas::where('estado', 'Activo')->get();
        return view('livewire.inventario.crear-producto');
    }
    public function guardartodo()
    {
        // Obtener el idinventario más alto
        $ultimoIdInventario = Productos::max('idinventario');

        // Si no existe un idinventario, inicia desde 1
        $nuevoIdInventario = $ultimoIdInventario ? $ultimoIdInventario + 1 : 1;
        foreach ($this->empresas as $empresa) {
            $producto = Productos::where('nombre', 'ilike', '%' . $this->nombre)
                ->where('sucursal', 'ilike', '%' .  $empresa->area . '%')
                ->first();
            if ($producto) {
            } else {

                $producto = new Productos;
                $producto->estado = 'Activo';
                $producto->nombre = $this->nombre;
                $producto->descripcion = $this->descripcion;
                $producto->precio = $this->precio;
                $producto->sucursal = $empresa->area;
                $producto->idinventario = $nuevoIdInventario;
                $producto->fechainicio =  Carbon::now()->toDateString();
                if ($empresa->area == $this->sucursal) {
                    $producto->cantidad = $this->cantidad;
                    $producto->inicio = $this->cantidad;
                    $producto->save();
                    $registro = new registroinventario;
                    $sucursal = Areas::where('area', $this->sucursal)->first();
                    $registro->iduser = Auth::user()->id;
                    $registro->idsucursal = $sucursal->id;
                    $registro->motivo = 'Creacion';
                    $registro->precio = $this->precio;
                    $registro->fecha = Carbon::now()->toDateString();

                    $registro->estado = 'Activo';
                    $registro->cantidad = $this->cantidad;
                    $registro->sucursal = $this->sucursal;
                    $registro->idproducto = $producto->id;
                    $registro->nombreproducto = $this->nombre;
                    $registro->save();
                } else {

                    $producto->inicio = 0;
                    $producto->cantidad = 0;
                    $producto->save();
                }
            }
        }
        $this->emitTo('inventario.lista-inventario', 'render');
        $this->crear = false;
    }
}
