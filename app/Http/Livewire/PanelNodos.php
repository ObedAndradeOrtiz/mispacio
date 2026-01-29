<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Areas;

class PanelNodos extends Component
{
    public $areas;
    public $sucursalId;
    public $sucursalName;
    public function render()
    {
        $this->areas = Areas::all();
        return view('livewire.panel-nodos');
    }
}