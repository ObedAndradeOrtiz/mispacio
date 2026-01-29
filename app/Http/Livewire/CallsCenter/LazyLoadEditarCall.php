<?php

namespace App\Http\Livewire\CallsCenter;

use Livewire\Component;

class LazyLoadEditarCall extends Component
{
    public $idllamada;
    public $loadComponent = false;

    public function mount($idllamada)
    {
        $this->idllamada = $idllamada;
    }

    public function loadComponent()
    {
        $this->loadComponent = true;
    }

    public function render()
    {
        return view('livewire.calls-center.lazy-load-editar-call');
    }
}