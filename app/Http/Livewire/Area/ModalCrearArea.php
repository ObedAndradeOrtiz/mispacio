<?php

namespace App\Http\Livewire\Area;

use Livewire\Component;
use App\Models\Areas;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ModalCrearArea extends Component
{

    public $area;
    public $fecha;
    public $telefono;
    public $responsable;
    public $estado="Activo";
    public $crear=false;
    public $porcentaje;
    public $direccion;
    public function render()
    {
        return view('livewire.area.modal-crear-area');
    }
    public function guardartodo(){
        $nuevo=new Areas;
        $nuevo->porcentaje=$this->porcentaje;
        $nuevo->area=$this->area;
        $nuevo->fecha=Carbon::now()->toDateString();;
        $nuevo->telefono=$this->telefono;
        $nuevo->responsable= Auth::user()->name;
        $nuevo->estado="Activo";
        $nuevo->save();
        $this->reset(['crear','area','telefono']);
        $this->emitTo('area.list-area','render');
        $this->emitTo('area','render');
        $this->emit('alert','¡Area creada satisfactoriamente!');
    }
}
