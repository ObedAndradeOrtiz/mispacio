<?php

namespace App\Http\Livewire\Operativos;

use App\Models\Operativos;
use Livewire\Component;

class FichaCliente extends Component
{
    public $operativo;
    public function  mount($idoperativo)
    {
        $this->operativo = Operativos::find($idoperativo);
    }
    public function render()
    {
        return view('livewire.operativos.ficha-cliente');
    }
}
