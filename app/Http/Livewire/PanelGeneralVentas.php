<?php

namespace App\Http\Livewire;

use App\Models\Areas;
use Livewire\Component;

class PanelGeneralVentas extends Component
{
    public $areas;
    protected $listeners = ['render' => 'render'];
    public $sucursalId;
    public $sucursalName;
    public $idoperativo;
    public function mount() {}
    public function render()
    {
        $this->idoperativo;
        $this->areas = Areas::all();
        return view('livewire.panel-general-ventas');
    }
}
