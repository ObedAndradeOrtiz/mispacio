<?php

namespace App\Http\Livewire\Area;

use App\Models\Areas;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ListArea extends Component
{
    public $open = false;
    public $area;
    public $telefono;
    public $busqueda = "";
    public $actividad = "Activo";
    protected $listeners = ['render' => 'render'];
    public function render()
    {
        if ($this->actividad == "Activo") {
            $areas = Areas::where('area', 'like', '%' . $this->busqueda . '%')
            ->where(function ($query) {
                $query->whereNull('almacen')
                    ->orWhere('almacen', '');
            })->
            where('estado', 'Activo')->orderBy('id', 'desc')->get();
        } else {
            $areas = Areas::where('area', 'like', '%' . $this->busqueda . '%')->where('estado', 'Inactivo')->orderBy('id', 'desc')->get();
        }
        return view('livewire.area.list-area', compact('areas'));
    }
    public function seleccionar($id)
    {
        $this->area = Areas::where('id', $id)->get();
    }
}