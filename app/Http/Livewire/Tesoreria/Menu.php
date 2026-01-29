<?php

namespace App\Http\Livewire\Tesoreria;

use Livewire\Component;

class Menu extends Component
{
    public $actividad = 'Areas';
    public function render()
    {
        return view('livewire.tesoreria.menu');
    }
}
