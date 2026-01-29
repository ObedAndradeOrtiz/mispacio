<?php

namespace App\Http\Livewire\Tratamientos;

use App\Models\ListaTratamiento;
use App\Models\Paquete;
use App\Models\Tratamiento;
use Livewire\Component;

class EditarPaquete extends Component
{
    public $paquete;
    public $tratamientos;
    public $tratamientosgenerales;
    public $openArea = false;
    public $tratamientosSeleccionados = [];
    protected $rules = [
        'paquete.nombre' => 'required',
        'paquete.descripcion' => 'required',
        'paquete.costo' => 'required',
    ];
    public function mount($idpaquete)
    {
        $this->paquete = Paquete::find($idpaquete);
        $this->tratamientos = ListaTratamiento::where('estado', 'Activo')->where('idpaquete', $this->paquete->id)->get();
        foreach ($this->tratamientos as $valor) {
            array_push($this->tratamientosSeleccionados, $valor->idtratamiento);
        }
        $this->tratamientosgenerales = Tratamiento::where('estado', 'Activo')->get();
    }
    public function render()
    {
        return view('livewire.tratamientos.editar-paquete');
    }
    public function toggleTratamiento($id)
    {
        if (in_array($id, $this->tratamientosSeleccionados)) {
            // Si el tratamiento está seleccionado, quitarlo del array
            $this->tratamientosSeleccionados = array_diff($this->tratamientosSeleccionados, [$id]);
        } else {
            // Si el tratamiento no está seleccionado, agregarlo al array
            $this->tratamientosSeleccionados[] = $id;
        }
    }

    public function inactivarpaquete()
    {
        $this->paquete->estado = "Inactivo";
        $this->paquete->save();
        $this->emit('alert', '¡Tratamiendo desactivado satisfactoriamente!');
        $this->emitTo('tratamientos.lista-tratamientos', 'render');
    }
    public function activarpaquete()
    {
        $this->paquete->estado = "Activo";
        $this->paquete->save();
        $this->emit('alert', '¡Paquete activado satisfactoriamente!');
        $this->emitTo('tratamientos.lista-tratamientos', 'render');
    }
    public function guardartodo()
    {
        $this->paquete->save();
        $this->openArea = false;
        $mistratamientos = ListaTratamiento::where('idpaquete', $this->paquete->id)->get();

        foreach ($mistratamientos as $elemento) {
            $elemento->estado = "Inactivo";
            $elemento->save();
        }
        $contador = 0;
        foreach ($this->tratamientosSeleccionados as $idTratamiento) {
            $tratamiento = ListaTratamiento::where('idtratamiento', $idTratamiento)
                ->where('idpaquete', $this->paquete->id)
                ->get();
            if ($tratamiento->isNotEmpty()) {
                foreach ($tratamiento as $item) {
                    $nuevo = ListaTratamiento::find($item->id);
                    $nuevo->estado = 'Activo';
                    $nuevo->save();
                }
            } else {
                $tratamiento = Tratamiento::find($idTratamiento);
                $lista = new ListaTratamiento;
                $lista->nombre = $tratamiento->nombre;
                $lista->idtratamiento = $tratamiento->id;
                $lista->idpaquete = $this->paquete->id;
                $lista->estado = 'Activo';
                $lista->save();

            }
        }

        $this->tratamientosSeleccionados = [];
        $this->mount($this->paquete->id);
        $this->emit('alert', '¡Paquete editado satisfactoriamente!');
        $this->emitTo('tratamientos.lista-tratamientos', 'render');
    }
}
