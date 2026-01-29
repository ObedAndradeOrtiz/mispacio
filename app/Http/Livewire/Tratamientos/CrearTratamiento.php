<?php

namespace App\Http\Livewire\Tratamientos;

use Livewire\Component;
use App\Models\ListaTratamiento;
use App\Models\Tratamiento;

class CrearTratamiento extends Component
{
    public $nombre;
    public $descripcion;
    public $costo;
    public $crear=false;
    public function render()
    {
        return view('livewire.tratamientos.crear-tratamiento');
    }
    public function guardartodo(){
        $tratamiento=new Tratamiento;
        $tratamiento->nombre=$this->nombre;
        $tratamiento->descripcion=$this->descripcion;
        $tratamiento->estado='Activo';
        $tratamiento->costo=$this->costo;
        $tratamiento->save();
        $this->crear=false;
        $this->emit('alert','¡Tratamiendo creado satisfactoriamente!');
        $this->emitTo('tratamientos.lista-tratamientos','render');
    }
}
