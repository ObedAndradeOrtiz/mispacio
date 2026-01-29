<?php

namespace App\Http\Livewire\Tratamientos;

use Livewire\Component;
use App\Models\ListaTratamiento;
use App\Models\Tratamiento;
use App\Models\Paquete;


class CrearListaTratamiento extends Component
{
    public $crear = false;
    public $nombre="";
    public $descripcion="";
    public $tratamientos;
    public $costo;
    public $tratamientosSeleccionados = [];
    public function render()
    {
        $this->tratamientos = Tratamiento::where('estado', 'Activo')->get();
        return view('livewire.tratamientos.crear-lista-tratamiento');
    }
    public function guardartodo()
    {
        if (trim($this->nombre) != "" && trim($this->descripcion) != "" && !empty($this->tratamientosSeleccionados)) {
            $paquete = new Paquete;
            $paquete->nombre = $this->nombre;
            $paquete->descripcion = $this->descripcion;
            $paquete->estado='Activo';
            $paquete->costo=$this->costo;
            $paquete->save();
            foreach ($this->tratamientosSeleccionados as $elemento) {
                $lista=new ListaTratamiento;
                $tratamiento=Tratamiento::find($elemento);
                if( $tratamiento){
                    $lista->nombre=$tratamiento->nombre;
                    $lista->idtratamiento=$tratamiento->id;
                    $lista->idpaquete=$paquete->id;
                    $lista->estado='Activo';
                    $lista->save();
                }
            }

            $this->crear=false;
            $this->tratamientosSeleccionados = [];
            $this->emit('alert','¡Paquete creado satisfactoriamente!');
            $this->emitTo('tratamientos.lista-tratamientos','render');
        }
        else{

            $this->emit('error','¡Error de guardado!');
        }


    }
}
