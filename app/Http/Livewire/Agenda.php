<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Calls;
use Illuminate\Support\Facades\Auth;
use Jenssegers\Agent\Agent;
use App\Models\Areas;

class Agenda extends Component
{
    public $presionado = 5;
    public $areas;
    public $sucursalId;
    public $sucursalName;

    protected $listeners = ['render' => 'render'];
    public function mount() {}
    public function render()
    {
        $this->areas = Areas::all();
        return view('livewire.agenda');
    }
}
