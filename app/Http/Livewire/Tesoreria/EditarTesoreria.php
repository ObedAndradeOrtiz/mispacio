<?php

namespace App\Http\Livewire\Tesoreria;

use Livewire\Component;
use App\Models\Areas;
use App\Models\Gastos;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class EditarTesoreria extends Component
{
    public $openArea=false;
    public $areapri;
    public $fechainicio="";
    public $monto="";
    public $comentario;
    public function mount(Areas $area ){
        $this->areapri=$area;
    }
    public function render()
    {
        return view('livewire.tesoreria.editar-tesoreria');
    }
    public function confirmar(){
        if(( $this->fechainicio &&  $this->monto)!="" ){ 
            $nuevo=new Gastos;
            $nuevo->idarea=$this->areapri->id;
            $nuevo->area=$this->areapri->area;
            $nuevo->fechainicio=$this->fechainicio;
            $nuevo->cantidad=$this->monto;  
            $nuevo->empresa=$this->comentario;
            $nuevo->iduser= Auth::user()->id;
            $nuevo->nameuser= Auth::user()->name;
            $nuevo->save();
            $this->reset(['openArea','fechainicio','monto','comentario']);
            $this->emitTo('tesoreria.lista-tesoreria','render');
            $this->emit('alert','¡Gasto guardado satisfactoriamente!'); 
       }
       else{
        $this->emit('error','¡Algo anda mal!');
       }
   }
}
