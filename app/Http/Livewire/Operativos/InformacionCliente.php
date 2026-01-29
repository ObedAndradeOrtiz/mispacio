<?php

namespace App\Http\Livewire\Operativos;

use App\Models\Areas;
use App\Models\fichacliente;
use App\Models\Operativos;
use App\Models\Pagos;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\HistorialCliente;

class InformacionCliente extends Component
{
    public $misfichas;
    public $crear = false;
    public $eliminar = false;
    public $operativo;
    public $tratamiento = "";
    public $sesion = "1";
    public $fecha;
    public $user;
    public $info = false;
    public $idtratamientoseleccionado;

    public function mount($idoperativo)
    {
        $this->fecha = Carbon::now()->toDateString();
        $this->user = User::find(Auth::user()->id);
        $this->misfichas = fichacliente::where('idoperativo', $idoperativo)->get();
        $this->operativo = HistorialCliente::find($idoperativo);
    }
    public function render()
    {
        $this->misfichas = fichacliente::where('idoperativo', $this->operativo->id)->get();
        return view('livewire.operativos.informacion-cliente');
    }
    public function guardartodo()
    {
        $ficha = new fichacliente;
        $ficha->descripcion = $this->tratamiento;
        $ficha->sesion = $this->sesion;
        $ficha->idoperativo = $this->operativo->id;
        $ficha->fecha = $this->fecha;
        $ficha->responsable = Auth::user()->name;
        $ficha->iduser = Auth::user()->id;
        $ficha->save();
        $this->emit('alert', '¡Informacion agregada satisfactoriamente!');
        $this->render();
    }
    public function preeliminar($idtratamiento)
    {
        $this->idtratamientoseleccionado = $idtratamiento;
        $this->eliminar = true;
    }
    public function eliminarinformacion()
    {
        $tratamientohistorial = fichacliente::find($this->idtratamientoseleccionado);
        $tratamientohistorial->delete();
        $this->eliminar = false;
        $this->render();
        $this->emit('alert', '¡Informacion eliminada!');
    }
    public function imprimir()
    {
        $descriptionWidth = 30;
        $recepcion = explode(' ', Auth::user()->name);
        $cliente = User::find($this->operativo->idcliente);
        $area = Areas::find(Auth::user()->sesionsucursal);
        $text = "INFORMACION DE TRATAMIENTO" . "\nFecha: " . date('Y-m-d H:i:s')  . "\nCliente: " .
            $cliente->name . "\nRecepcionista: " . implode(' ', array_slice($recepcion, 0, 2)) .
            "\nTratamiento: " . $this->operativo->nombretratamiento .
            "\n-----------------------------------------------------------------------------" . str_pad("\nDESCRIPCIÓN", $descriptionWidth) . "" . "SESION\n-----------------------------------------------------------------------------\n";
        $historiales = DB::table('fichaclientes')
            ->where('idoperativo', $this->operativo->id)
            ->get();
        $costo = 0;
        $lineWidth = 44;
        $pricePosition = intval($lineWidth * 0.7);
        foreach ($historiales as $tratamiento) {
            $descripcion = substr($tratamiento->descripcion, 0, 20);
            $descripcion = str_pad($descripcion, $pricePosition - 1, ' ', STR_PAD_RIGHT);
            $precio = $tratamiento->sesion;
            $text .= $descripcion . $precio . "\n";
        }
        $text .= "



        -----------------------------------------------------------------------------

        \n";
        $data = [
            'id' => Auth::user()->sesionsucursal,
            'texto' => $text
        ];
        $url = "http://127.0.0.1:5001/imprimirticket";
        $response = Http::post($url, $data);
        $this->emit('alert', '¡Informacion enviada!');
    }
}
