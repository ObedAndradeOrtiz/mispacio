<?php

namespace App\Http\Livewire\Inmuebles;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Areas;
use Illuminate\Support\Facades\DB;

class EditarInmuebles extends Component
{
    protected $rules = [
        'producto.nombre' => 'required',
        'producto.descripcion' => 'required',
        'producto.precio' => 'required',
        'producto.cantidad' => 'required',
        'producto.estado' => 'required',
        'producto.sucursal' => 'required'

    ];
    public $producto;
    public $image;
    public $editar = false;
    public $empresas;
    public function mount($producto)
    {
        $this->producto = $producto;
    }
    public function render()
    {
        $this->empresas = Areas::where('estado', 'Activo')->get();
        return view('livewire.inmuebles.editar-inmuebles');
    }
    public function guardaredicion()
    {

        if ($this->image) {
            $image = $this->image->store('public/inmueble');
            $image = 'inmueble/' . basename($image);
            $this->producto->path = $image;
        }

        $this->producto->save();
        $this->editar = false;
        $this->emitTo('inmuebles.lista-inmuebles', 'render');
        $this->emit('alert', '¡Inmueble editado satisfactoriamente!');
    }
    public function desactivar()
    {
        $this->producto->estado = 'Inactivo';
        $this->producto->save();
        $this->emitTo('inmuebles.lista-inmuebles', 'render');
        $this->emit('alert', '¡Inmueble descativado!');
    }

    public function activar()
    {
        $this->producto->estado = 'Activo';
        $this->producto->save();
        $this->emitTo('inventario.lista-inventario', 'render');
        $this->emit('alert', '¡Inmueble activado!');
    }
}
