<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Areas;
use Illuminate\Support\Facades\Auth;

class Llamadas extends Component
{
    public $presionado = 3;
    public $areas;
    public $sucursalId;
    public $sucursalName;
    public $sucursal;

    protected $listeners = ['render' => 'render'];
    public function mount()
    {

        $area = Areas::find(Auth::user()->sesionsucursal);
        $this->sucursalName = $area->area;

        $this->sucursal = $this->sucursalName;
    }
    public function render()
    {
        $this->areas = Areas::all();
        return view('livewire.llamadas');
    }
}
