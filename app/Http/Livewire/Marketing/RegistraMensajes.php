<?php

namespace App\Http\Livewire\Marketing;

use App\Models\cuentacomercial;
use App\Models\MensajesFacebook;
use Carbon\Carbon;
use Livewire\Component;

class RegistraMensajes extends Component
{
    public $crear = false;
    public $cuentacomercial;
    public $fecha;
    public $texto;
    public $cuentascomerciales;
    public function render()
    {
        $this->cuentascomerciales = cuentacomercial::where('estado', 'Activo')->get();
        return view('livewire.marketing.registra-mensajes');
    }
    public function guardartodo()
    {
        $lineas = explode("\n", $this->texto);
        $resultado = [];

        foreach ($lineas as $linea) {
            if (trim($linea) === "") continue; // Salta líneas vacías
            $partes = explode(",", $linea);

            // Procesar la primera parte
            $subpartes = explode(":", $partes[0]);
            $abreviatura = isset($subpartes[0]) ? str_replace('"', "", trim($subpartes[0])) : '';
            $tratamiento = isset($subpartes[1]) ? str_replace('"', "", trim($subpartes[1])) : '';
            $colaboracion = isset($subpartes[2]) ? str_replace('"', "", trim($subpartes[2])) : '';

            // Procesar el resto de las partes
            $estado = isset($partes[1]) ? trim($partes[1]) : '';
            $nivel = isset($partes[2]) ? trim($partes[2]) : '';
            $mensajes = isset($partes[3]) && trim($partes[3]) !== '' ? (int)trim($partes[3]) : 0;
            $resultado = isset($partes[4]) ? trim($partes[4]) : '';
            $costo = isset($partes[5]) && trim($partes[5]) !== '' ? trim($partes[5]) : '0';
            $importe = isset($partes[6]) && trim($partes[6]) !== '' ? trim($partes[6]) : '0';

            $datos = new MensajesFacebook;
            $datos->abreviatura = str_replace('"', "", $abreviatura);
            $datos->tratamiento = str_replace('"', "", trim($tratamiento));
            $datos->colaboracion = str_replace('"', "", trim($colaboracion));
            $datos->estado = $estado;
            $datos->nivel = $nivel;
            $datos->mensajes = $mensajes;
            $datos->resultado = $resultado;
            $datos->costo = $costo;
            $datos->importe = $importe;
            $datos->fecha = $this->fecha;
            $datos->idcuenta = $this->cuentacomercial;
            $datos->save();
        }
        $this->emit('alert', '¡Registrado exitosamente!');
        $this->render();
    }
}