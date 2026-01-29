<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Areas;
use Illuminate\Support\Facades\Auth;

class PanelCrm extends Component
{
    public $areas;
    protected $listeners = ['render' => 'render'];
    public $sucursalId;
    public $sucursalName;
    public function mount() {}
    public function render()
    {

        $this->areas = Areas::all();
        return view('livewire.panel-crm');
    }
}
