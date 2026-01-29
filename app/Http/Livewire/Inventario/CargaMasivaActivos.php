<?php

namespace App\Http\Livewire\Inventario;

use App\Models\Areas;
use App\Models\Productos;
use App\Models\Inmueble;
use DateTime;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithFileUploads;

class CargaMasivaActivos extends Component
{
    use WithFileUploads;
    public $sucursal;
    public $areas;
    public $crear = false;
    protected $listeners = ['crearCambiado' => 'cambiarCrearFalse', 'datosRecibidosInm' => 'datosRecibidosInm'];
    public function datosRecibidosInm($data)
    {
        $this->areas = Areas::where('estado', 'Activo')->get();
        $htmlTable = $data['tablaHTML'];

        $dom = new \DOMDocument();
        $dom->loadHTML($htmlTable);
        $filas = $dom->getElementsByTagName('tr');

        for ($i = 1; $i < $filas->length; $i++) {
            $datosFila = $filas->item($i)->getElementsByTagName('td');
            $SUCURSAL = $datosFila->item(0)->nodeValue;
            $TIPO = $datosFila->item(1)->nodeValue;
            $NOMBRE = $datosFila->item(2)->nodeValue;
            $DETALLE = $datosFila->item(3)->nodeValue;
            $AREA = $datosFila->item(4)->nodeValue;
            $FECHA = $datosFila->item(5)->nodeValue;
            $PRECIO = $datosFila->item(6)->nodeValue;
            $CANTIDAD = $datosFila->item(7)->nodeValue;
            $ESTADO = $datosFila->item(8)->nodeValue;
            $producto = new Inmueble;
            $sucursalpeque = Areas::where('area', 'ilike', '%' . $SUCURSAL . '%')->first();
            $producto->cantidad = $CANTIDAD;
            $producto->sucursal =  $sucursalpeque->area;
            $producto->tipo = $TIPO;
            $producto->nombre = $NOMBRE;
            $producto->descripcion = $DETALLE;
            $producto->area = $AREA;
            $producto->fecha = $FECHA;
            $producto->precio = $PRECIO;
            $producto->cantidad = $CANTIDAD;
            $producto->estado = $ESTADO;
            $producto->save();
        }

        $this->crear = false;
        $this->emitTo('inventario.lista-inventario', 'render');
        $this->emit('alert', '¡Inventario actuliazado!');
    }

    public function render()
    {
        $this->areas = Areas::where('estado', 'Activo')->get();
        return view('livewire.inventario.carga-masiva-activos');
    }
}