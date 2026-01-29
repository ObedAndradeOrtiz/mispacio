<?php

namespace App\Http\Livewire\Marketing;

use App\Models\cuentacomercial;
use App\Models\User;
use Livewire\Component;

class CrearCuenta extends Component
{
    public $crearcuenta = false;
    public $name = '';
    public $comentario = '';
    public $elegido = 0;
    public function render()
    {
        $users = User::where('estado', 'Activo')->where('rol', '!=', 'Cliente')->orderBy('id', 'desc')->get();
        return view('livewire.marketing.crear-cuenta', compact('users'));
    }
    public function guardartodo()
    {
        $cuenta = new cuentacomercial;
        $cuenta->nombrecuenta = $this->name;
        $cuenta->iduser = $this->elegido;
        $usuario = User::find($this->elegido);
        $cuenta->responsable = $usuario->name;
        $cuenta->motivo = $this->comentario;
        $cuenta->estado = 'Activo';
        $cuenta->save();
        $this->emitTo('marketing.mark-campanas', 'render');
        $this->emit('alert', '¡Cuenta agregada satisfactoriamente!');
    }
}
