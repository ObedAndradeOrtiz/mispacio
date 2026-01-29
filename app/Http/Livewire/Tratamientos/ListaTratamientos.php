<?php

namespace App\Http\Livewire\Tratamientos;

use Livewire\Component;
use App\Models\ListaTratamiento;
use App\Models\Tratamiento;
use App\Models\Paquete;
use Livewire\WithPagination;

class ListaTratamientos extends Component
{
    use WithPagination;
    public $actividad = "Activo";
    public $paquetes;
    public $vistaTratamiento = "lista";
    public $busquedapaquetes;
    public $busqueda;
    protected $listeners = ['render' => 'render'];
    public function render()
    {
        $this->paquetes = Paquete::where('estado', $this->actividad)->where('nombre', 'ilike', '%' . $this->busquedapaquetes . '%')->orderBy('id', 'desc')->get();
        $tratamientos = Tratamiento::where('estado', $this->actividad)->where('nombre', 'ilike', '%' . $this->busqueda . '%')->orderBy('id', 'desc')->paginate(10);

        return view('livewire.tratamientos.lista-tratamientos', compact('tratamientos'));
    }
}
