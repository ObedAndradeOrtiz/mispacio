<?php

namespace App\Http\Livewire\Mensajeria;

use Livewire\Component;
use App\Models\User;
use App\Models\Mensajes;
use Illuminate\Support\Facades\Auth;
class ChatGeneral extends Component
{
    public $iduser;
    public $presionado=false;
    public $busqueda;
    public $seleccionado=0;
    protected $listeners = ['render'=>'render','enviar'=>'enviar','cambiar'=>'cambiar'];
    public function mount($iduser ){
        $this->iduser=$iduser;
    }
    public function render()
    {
        $usuarios=User::where('name','like', '%'.$this->busqueda.'%')-> where('estado','Activo')->where('id','!=',Auth::user()->id)->get();
        return view('livewire.mensajeria.chat-general',compact('usuarios'));
    }
    public function cambiar($seleccion){
        $this->seleccionado=$seleccion;
    }
}
