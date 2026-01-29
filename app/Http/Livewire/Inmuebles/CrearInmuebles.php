<?php

namespace App\Http\Livewire\Inmuebles;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Productos;
use App\Models\Areas;
use App\Models\Inmueble;

class CrearInmuebles extends Component
{
    public $nombre;
    public $descripcion;
    public $cantidad;
    public $precio;
    public $image;
    public $crear=false;
    public $empresas;
    public $sucursal;
    use WithFileUploads;
    public function render()
    {
        $this->empresas= Areas::where('estado','Activo')->get();
        return view('livewire.inmuebles.crear-inmuebles');
    }
    public function guardartodo(){
        $producto= new Inmueble;
        $producto->estado='Activo';
        $producto->nombre=$this->nombre;
        $producto->descripcion=$this->descripcion;
        $producto->precio=$this->precio;
        $producto->sucursal=$this->sucursal;
        $producto->cantidad=$this->cantidad;
        if ($this->image) {
            $image = $this->image->store('public/inmueble');
            $image = 'inmueble/' . basename($image);
            $producto->path = $image;
            $producto->save();
            $this->emit('alert', '¡Inmueble creado satisfactoriamente!');
        } else {
            $producto->save();
            $this->emit('alert', '¡Inmueble creado satisfactoriamente!');
        }
        $this->emitTo('inmuebles.lista-inmuebles','render');
        $this->crear = false;
    }
}