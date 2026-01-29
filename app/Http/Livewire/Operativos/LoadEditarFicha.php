<?php

namespace App\Http\Livewire\Operativos;

use App\Models\Operativos;
use Livewire\Component;

class LoadEditarFicha extends Component
{
    public $operativo;
    public $loadComponent = false;

    public function mount($operativo)
    {
        $this->operativo = Operativos::find($operativo);
    }

    public function loadComponent()
    {
        $this->loadComponent = true;
    }
    public function render()
    {
        return view('livewire.operativos.load-editar-ficha');
    }
}
