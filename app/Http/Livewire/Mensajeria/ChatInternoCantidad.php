<?php

namespace App\Http\Livewire\Mensajeria;

use Livewire\Component;

class ChatInternoCantidad extends Component
{
    protected $listeners = ['render'=>'render'];
    public function render()
    {
        return view('livewire.mensajeria.chat-interno-cantidad');
    }

}
