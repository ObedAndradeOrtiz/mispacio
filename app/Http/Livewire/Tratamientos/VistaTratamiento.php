<?php

namespace App\Http\Livewire\Tratamientos;

use App\Models\Tratamiento;
use Livewire\Component;

class VistaTratamiento extends Component
{
    public $tratamiento;
    public $openArea = false;
    public $editar = false;
    protected $rules = [
        'tratamiento.nombre' => 'required',
        'tratamiento.descripcion' => 'required',
        'tratamiento.costo' => 'required',
        'tratamiento.hora' => 'required',
    ];
    public function mount($idtratamiento)
    {
        $this->tratamiento = Tratamiento::find($idtratamiento);
    }
    public function render()
    {
        return view('livewire.tratamientos.vista-tratamiento');
    }
    public function inactivarTratamiento()
    {
        $this->tratamiento->delete();
        $this->emit('alert', '¡Tratamiendo desactivado satisfactoriamente!');
        $this->emitTo('tratamientos.lista-tratamientos', 'render');
    }
    public function activarTratamiento()
    {
        $this->tratamiento->estado = "Activo";
        $this->tratamiento->save();
        $this->emit('alert', '¡Tratamiendo activado satisfactoriamente!');
        $this->emitTo('tratamientos.lista-tratamientos', 'render');
    }
    public function guardartodo()
    {
        $this->tratamiento->save();
        $this->openArea = false;
        $this->editar = false;
        $this->emit('alert', '¡Tratamiendo editado satisfactoriamente!');
        $this->emitTo('tratamientos.lista-tratamientos', 'render');
    }
}
