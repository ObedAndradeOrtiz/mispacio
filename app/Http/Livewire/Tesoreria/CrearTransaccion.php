<?php

namespace App\Http\Livewire\Tesoreria;

use Livewire\Component;

class CrearTransaccion extends Component
{
    public $creartransaccion = false;
    public function render()
    {
        return view('livewire.tesoreria.crear-transaccion');
    }
}
