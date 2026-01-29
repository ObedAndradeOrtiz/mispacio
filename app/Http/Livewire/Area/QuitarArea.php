<?php

namespace App\Http\Livewire\Area;

use App\Models\Areas;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
class QuitarArea extends Component
{
    public function render()
    {
        return view('livewire.area.quitar-area');
    }
    public $area;
    public $open=false;
    public $nombre;
    public $telefono;
    protected $rules=[
     'area.area'=>'required',
     'area.telefono'=>'required'
    ];
    public function mount(Areas $area ){
        $this->area=$area;
    }
    public function guardartodo(){
        $this->area->estado="Inactivo";
        $this->area->save();
        $this->reset(['open']);
        $this->emitTo('area.list-area','render');
    }
    public function cancelar(){
        $this->reset(['open']);
    }
}
