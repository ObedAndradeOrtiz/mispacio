<?php

namespace App\Http\Livewire\Marketing;

use Livewire\Component;
use App\Models\Abreviaturas;
use App\Models\Areas;
use Illuminate\Support\Facades\Auth;

class MarkAbreviaturas extends Component
{
    public $abreviaturas;
    public $areas;
    public $sucursalseleccionada;
    protected $listeners = ['render' => 'render','eliminar'=>'eliminar'];
    public function mount()
    {
        $this->sucursalseleccionada = Auth::user()->sesionsucursal;
    }
    public function render()
    {
        $this->abreviaturas = Abreviaturas::where('estado', 'Activo')->where('idsucursal', $this->sucursalseleccionada)->get();
        $this->areas = Areas::where('estado', 'Activo')->get();
        return view('livewire.marketing.mark-abreviaturas');
    }
}