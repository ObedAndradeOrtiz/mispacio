<?php

namespace App\Http\Livewire\Marketing;

use App\Models\MensajesFacebook;
use Livewire\Component;

class MarkMensajes extends Component
{
    public $fechaini;
    public $mensajes;
    public function render()
    {
        $this->mensajes = MensajesFacebook::get();
        return view('livewire.marketing.mark-mensajes');
    }
}
