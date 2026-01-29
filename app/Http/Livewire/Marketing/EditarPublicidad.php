<?php

namespace App\Http\Livewire\Marketing;

use App\Models\publicidades;
use Livewire\Component;

class EditarPublicidad extends Component
{
    public $editar = false;
    public $publicidad;
    protected $rules = [
        'publicidad.fechainicio' => 'required',
        'publicidad.fechafin' => 'required',
        'publicidad.estado' => 'required',
        'publicidad.hora' => 'required',
        'publicidad.fecharango' => 'required',
    ];
    public function mount($idpublicidad)
    {
        $this->publicidad = publicidades::find($idpublicidad);
    }
    public function render()
    {
        return view('livewire.marketing.editar-publicidad');
    }
    public function guardar()
    {
        $this->publicidad->save();
        $this->emitTo('crm.panel-mensajes', 'render');
        $this->emit('alert', '¡Publicidad editada satisfactoriamente!');
        $this->emitTo('marketing.marketing', 'render');
    }
}