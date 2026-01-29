<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Areas;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class PanelTratamiento extends Component
{
    public $presionado = 12;
    public $areas;
    protected $listeners = ['render' => 'render'];
    public $sucursalId;
    public $sucursalName;
    public function mount() {}

    public function render()
    {
        $this->areas = Areas::all();
        return view('livewire.panel-tratamiento');
    }
}
