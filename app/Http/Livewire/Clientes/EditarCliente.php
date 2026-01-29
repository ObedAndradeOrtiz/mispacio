<?php

namespace App\Http\Livewire\Clientes;

use App\Events\EnviarMensaje;
use App\Models\HistorialCliente;
use App\Models\Operativos;
use App\Models\Paquete;
use App\Models\Tratamiento;
use App\Models\TratamientoCliente;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Areas;
use App\Models\User;

class EditarCliente extends Component
{
    public $iduser;
    public $usuario;
    public $openArea = false;
    public $openArea2 = false;
    public $openArea3 = false;
    public $openArea4 = false;
    public $openArea5 = false;
    public $openArea6 = false;
    public $nombre;
    public $telefono;
    public $cantidad;
    public $botonpaquete = false;
    public $tratamientos;
    public $paquetes;
    public $tratamientosSeleccionados = [];
    public $elegido;
    public $elegidopaquete;
    public $fechacita;
    public $hora;
    public $minuto;
    public $sexo;
    public $users;
    public $empresas;
    public $empresaseleccionada;
    public $ci;
    public $verificar;
    public $busquedatratamiento = '';
    public $notiene = false;

    protected $rules = [
        'usuario.name' => 'required',
        'usuario.estado' => 'required',
        'usuario.telefono' => 'required',
        'hora' => 'required',
        'minuto' => 'required',
        'tratamientosSeleccionados' => 'required',
        'empresaseleccionada' => 'required',
        'fechacita' => 'required',

    ];
    protected $listeners = ['render' => 'render', 'inactivar' => 'inactivar', 'activar' => 'activar'];

    public function mount($iduser)
    {
        $user = User::find($iduser);
        $this->empresas = Areas::where('estado', 'Activo')->get();
        $this->usuario = $user;
        $this->ci = $this->usuario->ci;
        $this->fechacita = date("Y-m-d");
        $this->notiene = Operativos::where('idempresa', $this->usuario->id)->exists();
    }
    public function render()
    {
        $this->users = User::where('estado', 'Activo')->where('rol', '!=', 'Cliente')->orderBy('id', 'desc')->get();
        $this->tratamientos = Tratamiento::where('estado', 'Activo')->where('nombre', 'ilike', '%' . $this->busquedatratamiento . '%')->orderBy('nombre', 'asc')->get();
        $this->paquetes = Paquete::where('estado', 'Activo')->get();
        $this->verificar = Operativos::where('empresa', $this->usuario->name)->where('fecha', $this->fechacita)->exists();
        return view('livewire.clientes.editar-cliente');
    }
    public function guardartodo()
    {
        if ($this->ci) {
            $this->usuario->ci = $this->ci;
        }
        $this->usuario->save();
        $this->reset(['openArea']);
        $this->emitTo('clientes.lista-clientes', 'render');
        $this->emit('alert', '¡Cliente editado satisfactoriamente!');
    }
    public function guardarCita()
    {
        $this->tratamientosSeleccionados = array_filter($this->tratamientosSeleccionados, function ($value) {
            // Retorna true si el valor no es vacío ni nulo
            return !empty($value);
        });
        if ($this->verificar) {
            $operativo = Operativos::where('empresa', $this->usuario->name)->where('fecha', date("Y-m-d"))->first();
            $tratamientocliente = new TratamientoCliente;
            $tratamientocliente->idllamada = "0";
            $tratamientocliente->fecha = $this->fechacita;
            $tratamientocliente->estado = 'Inactivo';
            $tratamientocliente->idoperativo = $operativo->id;
            $tratamientocliente->save();
            foreach ($this->tratamientosSeleccionados as $elemento) {
                $tratamiento = Tratamiento::find($elemento);
                if ($tratamiento) {
                    $nuevo = new HistorialCliente;
                    $nuevo->idtratamiento = $tratamiento->id;
                    $nuevo->idllamada = "0";
                    $nuevo->idcliente = $this->usuario->id;
                    $nuevo->fecha = $this->fechacita;
                    $nuevo->idtratamientocliente = $tratamientocliente->id;
                    $nuevo->idoperativo = $operativo->id;
                    $nuevo->estado = 'Inactivo';
                    $nuevo->nombretratamiento = $tratamiento->nombre;
                    $nuevo->costo = $tratamiento->costo;
                    $nuevo->save();
                }
            }
        } else {
            $this->validate();
            $operativo = new Operativos;
            $operativo->area = $this->empresaseleccionada;
            $operativo->idempresa = $this->usuario->id;
            $operativo->empresa = $this->usuario->name;
            $operativo->fecha = $this->fechacita;
            $this->hora = sprintf('%02d', $this->hora);
            $operativo->hora = $this->hora . ':' . $this->minuto;
            $operativo->telefono = $this->usuario->telefono;
            $operativo->responsable = Auth::user()->name;
            $operativo->cantidadtotal = "0";
            $operativo->ingreso = "0";
            $operativo->cantidadaregistrar = "0";
            $operativo->encargado = $this->elegido;
            $operativo->estado = "Pendiente";
            $operativo->ci = $this->usuario->ci;
            $operativo->comentario = "0";
            $operativo->idllamada = '0';
            $operativo->save();
            $tratamientocliente = new TratamientoCliente;
            $tratamientocliente->idllamada = "0";
            $tratamientocliente->fecha = $this->fechacita;
            $tratamientocliente->estado = 'Inactivo';
            $tratamientocliente->idoperativo = $operativo->id;
            $tratamientocliente->save();
            foreach ($this->tratamientosSeleccionados as $elemento) {
                $tratamiento = Tratamiento::find($elemento);
                if ($tratamiento) {
                    $nuevo = new HistorialCliente;
                    $nuevo->idtratamiento = $tratamiento->id;
                    $nuevo->idllamada = "0";
                    $nuevo->idcliente = $this->usuario->id;
                    $nuevo->fecha = $this->fechacita;
                    $nuevo->idtratamientocliente = $tratamientocliente->id;
                    $nuevo->idoperativo = $operativo->id;
                    $nuevo->estado = 'Inactivo';
                    $nuevo->nombretratamiento = $tratamiento->nombre;
                    $nuevo->costo = $tratamiento->costo;
                    $nuevo->save();
                }
            }
        }


        $this->reset(['openArea6']);
        $this->emit('alert', '¡Cita creada satisfactoriamente!');
    }

    public function inactivar()
    {
        $this->usuario->delete();
        $this->usuario->save();
        $this->reset(['openArea2']);
        $this->emitTo('clientes.lista-clientes', 'render');
        $this->emitTo('users.lista-user', 'render');
    }
    public function activar($idCall)
    {
        $llamada = User::find($idCall);
        $llamada->estado = "Activo";
        $llamada->save();
        $this->reset(['openArea3']);
        $this->emitTo('users.lista-user', 'render');
        $this->emitTo('clientes.lista-clientes', 'render');
    }
    public function cancelar()
    {
        $this->reset(['openArea2', 'openArea', 'openArea3', 'openArea4']);
    }
    public function descargarcontrato()
    {
        if ($this->usuario->firma == "") {
            $this->emit('error', '¡Por favor pirmero crear contrato!');
        }
    }
    public function enviarws($id)
    {
        $user = User::find($id);
        $numero = $user->telefono;
        $mensaje = "Gracias por confiar en Bolivian Business Center, puede revisar su contrato mediante el siguiente link: https://bolivianbusinesscenter.com.bo/contrato/" . $id;
        $cadena_modificada = str_replace(" ", "%20", $mensaje);
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://panel.rapiwha.com/send_message.php?apikey=AQ2SH0KJBK1TJ1LMCGJQ&number=591" . $numero . "&text=" . $cadena_modificada,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
        $this->reset(['openArea', 'openArea2', 'openArea', 'openArea3', 'openArea4', 'openArea5', 'openArea6']);
        $this->emit('alert', '¡Mensaje enviado satisfactoriamente!');
    }
}
