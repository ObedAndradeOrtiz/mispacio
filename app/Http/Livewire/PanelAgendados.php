<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Areas;
use Illuminate\Support\Facades\Auth;

class PanelAgendados extends Component
{
    public $presionado = 35;
    public $areas;
    public $sucursalId;
    public $sucursalName;

    protected $listeners = ['render' => 'render'];
    public function mount() {}

    public function render()
    {
        $this->areas = Areas::all();
        return view('livewire.panel-agendados');
    }
}
