<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Areas;
use Illuminate\Support\Facades\Auth;

class Marketing extends Component
{
    public $presionado = 21;
    public $areas;
    public $sucursalId;
    public $sucursalName;
    protected $listeners = ['render' => 'render'];
    public function mount() {}
    public function render()
    {
        $this->areas = Areas::all();
        return view('livewire.marketing');
    }
}
