<?php

namespace App\Http\Livewire\Users;

use App\Models\User;
use Livewire\Component;

class EditarNumero extends Component
{
    public $numero;
    public $usuario;
    protected $rules = [
        'numero' => 'numeric', // Puedes agregar reglas de validación aquí
    ];
    public function mount($iduser)
    {
        $this->usuario = User::find($iduser);
        $this->numero = $this->usuario->memorandum;
    }

    public function updatedNumero($value)
    {
        if ($this->numero != '') {
            $this->validateOnly('numero');
            $this->guardarNumero();
        }
    }

    public function guardarNumero()
    {
        $this->usuario->memorandum = $this->numero;
        $this->usuario->save();
        $this->emitTo('users.lista-user', 'render');
        $this->emit('alert', '¡Memorandum editado satisfactoriamente!');
    }
    public function render()
    {
        return view('livewire.users.editar-numero');
    }
}