<?php

namespace App\Http\Livewire\Mensajeria;

use Livewire\Component;
use App\Models\mensajeexterno;
class ChatBotInterno extends Component
{
    public $respuesta=0;
    public $tema;
    public $nombre;
    public $telefono;
    public $mensaje;
    public $email;
    public function render()
    {
        return view('livewire.mensajeria.chat-bot-interno');
    }
    public function tema($tema){
     $this->respuesta=1;
     $this->tema=$tema;
    }
    public function guardar(){
        $this->respuesta=2;
        $nuevo = new mensajeexterno;
        $nuevo->fecha=date('Y-m-d H:i:s');
        $nuevo->tema=$this->tema;
        $nuevo->nombre=$this->nombre;
        $nuevo->telefono=$this->telefono;
        $nuevo->mensaje=$this->mensaje;
        $nuevo->email=$this->email;
        $nuevo->save();
        $this-> tema="";
        $this-> nombre="";
        $this-> telefono="";
        $this-> mensaje="";
        $this-> email="";
    }
}
