<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Areas;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class PanelEmpleados extends Component
{
    public $presionado = 0;
    public $areas;
    protected $listeners = ['render' => 'render'];
    public $sucursalId;
    public $sucursalName;
    public function mount($idsucursal)
    {
        $this->sucursalId = $idsucursal;
        if ($this->sucursalId == 0 || $this->sucursalId == null) {
            $this->sucursalName = "SPA MIORA GENERAL";
            $usuario = User::find(Auth::user()->id);
            $usuario->sucursal = "CENTRAL URBARI";
            $usuario->sesionsucursal = 1;
            $usuario->save();
        } else {
            $area = Areas::find($idsucursal);
            $this->sucursalName = $area->area;
            $area = Areas::find($idsucursal);
            $usuario = User::find(Auth::user()->id);
            $usuario->sucursal = $area->area;
            $usuario->sesionsucursal = $area->id;
            $usuario->save();
        }
    }
    public function render()
    {
        $this->areas = Areas::all();
        return view('livewire.panel-empleados');
    }
}
