<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Areas;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class PanelGestionClientes extends Component
{
    public $presionado = 13;
    public $areas;
    public $sucursalId;
    public $sucursalName;

    protected $listeners = ['render' => 'render'];
    public function mount() {}
    public function render()
    {
        $this->areas = Areas::all();
        return view('livewire.panel-gestion-clientes');
    }
}
