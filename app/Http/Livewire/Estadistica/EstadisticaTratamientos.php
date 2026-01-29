<?php

namespace App\Http\Livewire\Estadistica;

use App\Models\Areas;
use Livewire\Component;

class EstadisticaTratamientos extends Component
{
    public $areas;
    public function render()
    {
        $this->areas = Areas::where('estado', 'Activo')->get();
        return view('livewire.estadistica.estadistica-tratamientos');
    }
}
