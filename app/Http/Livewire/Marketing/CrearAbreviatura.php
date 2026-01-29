<?php

namespace App\Http\Livewire\Marketing;

use App\Models\Abreviaturas;
use App\Models\Areas;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CrearAbreviatura extends Component
{
    public $abreviatura = "";
    public $areaseleccionada = "";
    public $areas;
    public $crear = false;
    public function mount()
    {
        $this->areaseleccionada = Auth::user()->sesionsucursal;
    }
    public function render()
    {
        $this->areas = Areas::where('estado', 'Activo')->get();
        return view('livewire.marketing.crear-abreviatura');
    }
    public function guardartodo()
    {
        $abreviatura = new Abreviaturas;
        $abreviatura->estado = 'Activo';
        $abreviatura->idsucursal = $this->areaseleccionada;
        $area = Areas::find($this->areaseleccionada);
        $abreviatura->sucursal = $area->area;
        $abreviatura->abreviatura = $this->abreviatura;
        $abreviatura->iduser = Auth::user()->id;
        $abreviatura->user = Auth::user()->name;
        $abreviatura->save();
        $this->emitTo('marketing.mark-abreviaturas', 'render');
        $this->emit('alert', '¡Abreviatura agregada satisfactoriamente!');
    }
}
