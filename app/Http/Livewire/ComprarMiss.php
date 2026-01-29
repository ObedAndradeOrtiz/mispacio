<?php

namespace App\Http\Livewire;

use Livewire\Component;

class ComprarMiss extends Component
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
        return view('livewire.comprar-miss');
    }
    public function comprar()
    {
        $numeroTelefono = '59177393205';
$mensaje = "¡Hola soy " . $this->nombre . " y quiero asistir al evento " . $this->eventoseleccionado . "\nDeseo comprar una cantidad de " . $this->cantidad . " entradas";
$enlaceWhatsApp = 'https://wa.me/' . $numeroTelefono  . '?text=' . rawurlencode($mensaje);
return redirect()->to($enlaceWhatsApp);


        // $numeroTelefono = '59177393205';
        // $mensaje = "¡Hola soy" . $this->nombre . "y quiero asisitir al evento" . $this->eventoseleccionado . '\\n' . "Deseo comprar una cantidad de " . $this->cantidad . "entradas";
        // $enlaceWhatsApp = 'https://wa.me/' . $numeroTelefono  . '?text=' . urlencode($mensaje);
        // return redirect()->to($enlaceWhatsApp);
    }
}