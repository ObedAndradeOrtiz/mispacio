<?php

namespace App\Http\Livewire\Mensajeria;

use App\Events\EnviarMensaje;
use Livewire\Component;
use App\Models\Calls;
use App\Models\Operativos;
use App\Models\Empresas;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Areas;
use Livewire\WithPagination;
use App\Models\Mensajes;

class ChatInterno extends Component
{
    public $iduser;
    public $presionado=false;
    public $busqueda;
    public $seleccionado=0;
    public $mensaje;
    protected $listeners = ['render'=>'render','enviar'=>'enviar'];
    public function render()
    {
        $usuarios=User::where('name','like', '%'.$this->busqueda.'%')-> where('estado','Activo')->where('id','!=',Auth::user()->id)-> where('rol','!=','Cliente')->get();
        return view('livewire.mensajeria.chat-interno', compact('usuarios'));
    }
    public function seleccionar($seleccion){
        $this->seleccionado=$seleccion;
    }
    public function enviar(){
        if($this->mensaje!=""){
            $mensaje=$this->mensaje;
            $mensaje=new Mensajes;
            $mensaje->idarea=4;
            $mensaje->idempresa=0;
            $mensaje->mensaje=$this->mensaje;
            $mensaje->receptor=$this->seleccionado;
            $mensaje->hora=date('h:i A');
            $mensaje->fecha=date('Y-m-d');
            $mensaje->emisor=Auth::user()->id;
            $mensaje->estado='A';
            $mensaje->save();
            $this->reset(['mensaje']);
            event(new EnviarMensaje($this->seleccionado,$this->mensaje));
        }
    }
}
