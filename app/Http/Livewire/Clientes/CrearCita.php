<?php

namespace App\Http\Livewire\Clientes;

use Livewire\Component;
use App\Events\EnviarMensaje;
use App\Models\HistorialCliente;
use App\Models\Operativos;
use App\Models\Paquete;
use App\Models\Tratamiento;
use App\Models\TratamientoCliente;
use Illuminate\Support\Facades\Auth;
use App\Models\Areas;
use App\Models\User;
use PhpOffice\PhpSpreadsheet\Calculation\Engine\Operands\Operand;

class CrearCita extends Component
{
    public $iduser;
    public $usuario;
    public $crear = false;
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
    public $operativo;
    public $idllamada;
    public $verificar;

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

    public function mount($idcall)
    {
        $this->idllamada = $idcall;
        $this->operativo = Operativos::where('telefono', $this->idllamada)->first();
        $user = User::find($this->operativo->idempresa);
        $this->empresas = Areas::where('estado', 'Activo')->get();
        $this->usuario = $user;
        $this->verificar = Operativos::where('empresa', $this->usuario->name)->where('fecha', date("Y-m-d"))->exists();
    }
    public function render()
    {
        $this->users = User::where('estado', 'Activo')->where('rol', '!=', 'Cliente')->orderBy('id', 'desc')->get();
        $this->tratamientos = Tratamiento::where('estado', 'Activo')->get();
        $this->paquetes = Paquete::where('estado', 'Activo')->get();
        return view('livewire.clientes.crear-cita');
    }
    public function guardartodo()
    {
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



        $verificarcita = Operativos::where('empresa', $this->usuario->name)->where('fecha', date("Y-m-d"))->exists();
        if ($verificarcita) {
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



        $this->reset(['crear']);
        $this->emit('alert', '¡Cita creada satisfactoriamente!');
    }
}
