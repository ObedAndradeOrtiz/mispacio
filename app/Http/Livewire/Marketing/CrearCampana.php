<?php

namespace App\Http\Livewire\Marketing;

use App\Models\campana;
use App\Models\HistorialCampana;
use App\Models\Tratamiento;
use Livewire\Component;
use Livewire\WithFileUploads;

class CrearCampana extends Component
{
    use WithFileUploads;
    public $name;
    public $comentario;
    public $crearcuenta = false;
    public $image;
    public $opcion;
    public $tratamientos;
    public $tratamientosSeleccionados = [];
    public $busquedatratamiento = '';
    public $tipotratamiento = "todos";
    public function render()
    {
        $this->tratamientos = Tratamiento::where('estado', 'Activo')->where('nombre', 'ilike', '%' . $this->busquedatratamiento . '%')->orderBy('nombre', 'asc')->get();
        return view('livewire.marketing.crear-campana');
    }
    public function guardartodo()
    {
        $nuevo = new campana;
        if ($this->image) {
            $image = $this->image->store('public/camp');
            $image = 'camp/' . basename($image);
            $nuevo->path = $image;
        }

        $nuevo->tipo = $this->name;
        $nuevo->comentario = $this->comentario;
        $nuevo->estado = 'Activo';
        $nuevo->save();
        if ($this->tipotratamiento == "todos") {
            $nuevo->cliente = "elegir";
        } else {
            $nuevo->cliente = "todos";
            foreach ($this->tratamientosSeleccionados as $tratamientos) {
                $nuevohis = new HistorialCampana;
                $nuevohis->idtratamiento = $tratamientos;
                $tratamientosSeleccionado = Tratamiento::find($tratamientos);
                $nuevohis->nombretratamiento = $tratamientosSeleccionado->nombre;
                $nuevohis->idcampana = $nuevo->id;
                $nuevohis->save();
            }
        }
        $this->emitTo('marketing.mark-campanas', 'render');
        $this->emit('alert', '¡Campaña agregada satisfactoriamente!');
    }
}