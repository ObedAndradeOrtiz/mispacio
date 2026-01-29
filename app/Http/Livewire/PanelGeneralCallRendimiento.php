<?php

namespace App\Http\Livewire;

use App\Models\Areas;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class PanelGeneralCallRendimiento extends Component
{
    public $presionado = 126;
    public $areas;
    public $sucursalId;
    public $sucursalName;
    public $sucursal;
    public $tipo;

    protected $listeners = ['render' => 'render'];
    public function mount()
    {
        $area = Areas::find(Auth::user()->sesionsucursal);
        $this->sucursalName = $area->area;
        $this->sucursal = $this->sucursalName;
        $this->tipo = 'pagos';
    }
    public function render()
    {
        $this->areas = Areas::all();
        return view('livewire.panel-general-call-rendimiento');
    }
}
