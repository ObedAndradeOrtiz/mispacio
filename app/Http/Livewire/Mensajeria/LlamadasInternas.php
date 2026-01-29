<?php

namespace App\Http\Livewire\Mensajeria;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class LlamadasInternas extends Component
{
    public $myName = '';
    public $userlist = [];

    public function mount()
    {
        $this->userlist = User::where('estado', 'Activo')->where('rol', '!=', 'Cliente')->orderBy('name')->get();
    }

    public function render()
    {
        return view('livewire.mensajeria.llamadas-internas');
    }
}
