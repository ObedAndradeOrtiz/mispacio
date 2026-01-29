<?php

namespace App\Http\Livewire\Paneles;

use App\Models\Areas;
use Livewire\Component;

class PanelAlmacenTraspaso extends Component
{
    public $areas;
    public $sucursalName;
    public function render()
    {
        $this->areas = Areas::all();
        return view('livewire.paneles.panel-almacen-traspaso');
    }
}