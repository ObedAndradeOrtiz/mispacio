<?php

namespace App\Http\Livewire\Almacen\Edicion;

use Livewire\Component;

class EditarProducto extends Component
{
    public function mount($producto) {
        
    }
    public function render()
    {
        return view('livewire.almacen.edicion.editar-producto');
    }
}