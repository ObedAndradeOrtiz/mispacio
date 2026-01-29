<?php

namespace App\Http\Livewire\Marketing;

use Livewire\Component;

class Mbq extends Component
{
    public $busqueda = '';
    public function render()
    {
        return view('livewire.marketing.mbq');
    }
}
