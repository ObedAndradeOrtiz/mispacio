<?php

namespace App\Http\Livewire\Tesoreria;

use App\Models\Areas;
use Livewire\Component;

class ListaEgresos extends Component
{
    public $areas;
    public $fechaInicioMes;
    public $fechaActual;
    public function mount()
    {
        $this->fechaActual = date('Y-m-d');
        $this->fechaInicioMes = date('Y-m-01');
    }
    public function render()
    {

        $this->areas = Areas::where('estado', 'Activo')->get();
        return view('livewire.tesoreria.lista-egresos');
    }
}
