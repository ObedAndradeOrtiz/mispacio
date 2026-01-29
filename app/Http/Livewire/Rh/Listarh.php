<?php

namespace App\Http\Livewire\Rh;

use Livewire\Component;

class Listarh extends Component
{
    public $botonRecepcion = 'planillasemanal';
    public function render()
    {
        return view('livewire.rh.listarh');
    }
}
