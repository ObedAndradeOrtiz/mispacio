<?php

namespace App\Http\Livewire\Area;

use App\Models\Areas;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class EditarArea extends Component
{
    public $area;
    public $openArea = false;
    public $openArea2 = false;
    public $openArea3 = false;
    public $nombre;
    public $telefono;
    protected $rules = [
        'area.area' => 'required',
        'area.telefono' => 'required',
        'area.porcentaje' => 'required',
        'area.ticket' => 'required'
    ];
    public function mount(Areas $area)
    {
        $this->area = $area;
    }
    public function render()
    {
        return view('livewire.area.editar-area');
    }
    public function guardartodo()
    {
        $this->area->save();
        $this->reset(['openArea']);
        $this->emitTo('area.list-area', 'render');
        $this->emit('alert', '¡Area editada satisfactoriamente!');
    }
    public function inactivar()
    {
        $this->area->estado = "Inactivo";
        $this->area->save();
        $this->reset(['openArea2']);

        $this->emitTo('area.list-area', 'render');
        $this->emit('alert', '¡Area ' . $this->area->area . ' inactiva!');
    }
    public function activar()
    {
        $this->area->estado = "Activo";
        $this->area->save();
        $this->reset(['openArea3']);
        $this->emitTo('area.list-area', 'render');
        $this->emit('alert', '¡Area ' . $this->area->area . ' Activada!');
    }
    public function cancelar()
    {
        $this->reset(['openArea2', 'openArea', 'openArea3']);
    }
}
