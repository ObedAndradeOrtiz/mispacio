<?php

namespace App\Http\Livewire\Marketing;

use App\Models\tarjetas;
use App\Models\User;
use Livewire\Component;

class CrearTarjeta extends Component
{
    public $creartarjeta = false;
    public $crearcuenta = false;
    public $name;
    public $comentario;
    public $elegido;
    public $saldo = 0;
    public $banco;
    public $numero = 0;
    protected $rules = [
        'elegido' => 'required',
        'name' => 'required',
        'banco' => 'required',
        'numero' => 'required',
    ];
    public function render()
    {
        $users = User::where('estado', 'Activo')->where('rol', '!=', 'Cliente')->orderBy('id', 'desc')->get();
        return view('livewire.marketing.crear-tarjeta', compact('users'));
    }
    public function guardartodo()
    {
        $this->validate();
        $telefonoExistente = tarjetas::where('nombretarjeta', $this->name)->where('estado', 'Activo')->exists();
        if ($telefonoExistente) {
            $this->emitTo('marketing.mark-tarjetas', 'render');
            $this->emit('error', '¡Tarjeta ya registrado!');
        } else {
            $cuenta = new tarjetas;
            $cuenta->nombretarjeta = $this->name;
            $cuenta->iduser = $this->elegido;
            $usuario = User::find($this->elegido);
            $cuenta->responsable = $usuario->name;
            $cuenta->motivo = $this->comentario;
            $cuenta->saldo = $this->saldo;
            $cuenta->banco = $this->banco;
            $cuenta->estado = 'Activo';
            $cuenta->numero = $this->numero;
            $cuenta->save();
            $this->emitTo('marketing.mark-tarjetas', 'render');
            $this->emit('alert', '¡Tarjeta agregada satisfactoriamente!');
        }
    }
}
