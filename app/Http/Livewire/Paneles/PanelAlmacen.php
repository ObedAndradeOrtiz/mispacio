<?php

namespace App\Http\Livewire\Paneles;

use App\Models\Areas;
use Livewire\Component;

class PanelAlmacen extends Component
{
    public $areas;
    public $sucursalName;
    public function render()
    {
        $this->areas = Areas::all();
        return view('livewire.paneles.panel-almacen');
    }
}
