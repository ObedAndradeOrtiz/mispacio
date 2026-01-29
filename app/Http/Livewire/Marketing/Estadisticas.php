<?php

namespace App\Http\Livewire\Marketing;

use Livewire\Component;

class Estadisticas extends Component
{
    public $botonRecepcion;
    public function mount()
    {
        $this->botonRecepcion = "citas";
    }
    public function render()
    {
        return view('livewire.marketing.estadisticas');
    }
}
