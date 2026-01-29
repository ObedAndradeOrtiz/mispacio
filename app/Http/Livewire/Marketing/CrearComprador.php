<?php

namespace App\Http\Livewire\Marketing;

use Livewire\Component;
use App\Models\User;
use App\Models\Registrocompramiss;

class CrearComprador extends Component
{
    public $nombre;
    public $email;
    public $telefono;
    public $cantidad = 0;
    public $evento;
    public $crear;
    public $modo;
    public $eventoseleccionado;
    public $total;

    public function render()
    {
        return view('livewire.marketing.crear-comprador');
    }
    public function guardar()
    {
        $nuevo = new User;
        $nuevo->name = $this->nombre;
        $nuevo->email =  $this->email;
        $nuevo->rol = "CompradorMiss";
        $nuevo->telefono = $this->telefono;
        $nuevo->password = "********";
        $nuevo->estado = "Activo";
        $nuevo->sucursal = "0";
        $nuevo->save();
        $idcliente = $nuevo->id;
        $nuevo = new Registrocompramiss;
        $nuevo->idcliente = $idcliente;
        $nuevo->nombre = $this->nombre;
        $nuevo->email =  $this->email;
        $nuevo->telefono = $this->telefono;
        $nuevo->cantidad = $this->cantidad;
        $nuevo->estado = "Activo";
        $nuevo->modo = $this->modo;
        $nuevo->monto = $this->cantidad * 70;
        if ($this->eventoseleccionado == "evento1") {
            $nuevo->fecha = "";
        } else {
            $nuevo->fecha = "";
        }
        $nuevo->modo = $this->modo;
        $nuevo->save();
        $this->emit('alert', '¡Comprador creado satisfactoriamente!');
    }
}
