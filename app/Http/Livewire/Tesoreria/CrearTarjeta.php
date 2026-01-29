<?php
namespace App\Http\Livewire\Tesoreria;

use Livewire\Component;

class CrearTarjeta extends Component
{
    public $creartarjeta = false;
    public function render()
    {
        return view('livewire.marketing.crear-tarjeta');
    }
}