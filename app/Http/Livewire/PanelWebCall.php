<?php

namespace App\Http\Livewire;

use App\Models\Areas;
use Livewire\Component;

class PanelWebCall extends Component
{
    public $presionado = 70;
    public $areas;
    public $sucursalId;
    public $sucursalName;

    protected $listeners = ['render' => 'render'];
    public function mount() {}

    public function render()
    {
        $this->areas = Areas::all();
        return view('livewire.panel-web-call');
    }
}