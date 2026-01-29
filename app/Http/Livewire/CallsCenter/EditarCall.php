<?php

namespace App\Http\Livewire\CallsCenter;

use App\Events\EnviarMensaje;
use App\Models\Areas;
use App\Models\Calls;
use App\Models\Empresas;
use App\Models\HistorialCliente;
use App\Models\ListaTratamiento;
use App\Models\Operativos;
use App\Models\Paquete;
use App\Models\Tratamiento;
use App\Models\TratamientoCliente;
use App\Models\User;
use  App\Models\registrollamadas;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Carbon\Carbon;

class EditarCall extends Component
{
    public $hora;
    public $minuto;
    public $llamada;
    public $elegido;
    public $paqueteelegido;
    public $openArea = false;
    public $openArea2 = false;
    public $openArea3 = false;
    public $openArea4 = false;
    public $openArea5 = false;
    public $openArea6 = false;
    public $nombre;
    public $telefono;
    public $cantidad = "";
    public $fecha = "";
    public $fechacita = "";
    public $paquetes;
    public $botonpaquete = false;
    public $tratamientosSeleccionados = [];
    public $tratamientos;
    public $ci;
    public $mistratamientos;
    public $busquedatratamiento = "";
    public $vermitratamiento = false;
    public $rellamar = false;
    public $comentariollamada = "";
    protected $rules = [
        'llamada.area' => 'required',
        'llamada.telefono' => 'required',
        'llamada.empresa' => 'required',
        'llamada.comentario' => 'required',
        'llamada.responsable' => 'required',
        'llamada.estado' => 'required',
        'llamada.fecha' => 'required',
        'llamada.fechacita' => 'required',
        'llamada.cantidad' => 'required',
        'llamada.encargado' => 'required',
        'llamada.ci' => 'required',
        'hora' => 'required',
        'minuto' => 'required',
        'fecha' => 'required',
        'fechacita' => 'required',
        'telefono' => 'required',
        'cantidad' => 'required',
        'nombre' => 'required',
        'elegido' => 'required',
    ];
    protected $listeners = ['render' => 'render', 'inactivar' => 'inactivar', 'activar' => 'activar', 'rellamarnumero' => 'rellamarnumero'];
    public function mount($idllamada)
    {
        $this->llamada = Calls::find($idllamada);
        $this->nombre = $this->llamada->empresa;

        $this->mistratamientos = HistorialCliente::where('estado', 'Activo')->where('idllamada', $this->llamada->id)->get();
        foreach ($this->mistratamientos as $valor) {
            array_push($this->tratamientosSeleccionados, $valor->idtratamiento);
        }
    }
    public function toggleTratamiento($id)
    {
        if (in_array($id, $this->tratamientosSeleccionados)) {
            $this->tratamientosSeleccionados = array_diff($this->tratamientosSeleccionados, [$id]);
        } else {
            $this->tratamientosSeleccionados[] = $id;
        }
    }
    public function render()
    {
        $this->tratamientos = Tratamiento::where('estado', 'Activo')->where('nombre', 'ilike', '%' . $this->busquedatratamiento . '%')->orderBy('nombre', 'asc')->get();
        $this->paquetes = Paquete::where('estado', 'Activo')->get();
        $areas = Areas::where('estado', 'Activo')->where(function ($query) {
                $query->whereNull('almacen')
                    ->orWhere('almacen', '');
            })->orderBy('id', 'desc')->get();
        $users = User::where('estado', 'Activo')->where('rol', '!=', 'Cliente')->orderBy('id', 'desc')->get();
        return view('livewire.calls-center.editar-call', compact('areas', 'users'));
    }
    public function cambiarhora($hora)
    {
        $this->hora = $hora;
    }
    public function guardartodo()
    {
        if ($this->fechacita != "") {
            $this->llamada->fechacita = $this->fechacita;
        }
        $this->llamada->hora = $this->hora . ':' . $this->minuto;
        $this->llamada->save();

        $this->reset(['openArea']);
        $this->emitTo('calls-center.lista-call', 'render');
        $this->emit('alert', '¡Llamada editada satisfactoriamente!');
    }
    public function confirmarycrear()
    {
        $this->llamada->estado = "Confirmado";
        $this->llamada->save();
        $operativo = Operativos::where('idllamada', $this->llamada->id)->first();
        $operativo->estado = 'Confirmado';
        $operativo->save();
        $historiales = HistorialCliente::where('idllamada', $this->llamada->id)->get();
        $tratamientos = TratamientoCliente::where('idllamada', $this->llamada->id)->get();
        foreach ($tratamientos as $tratamiento) {
            $tratamiento = TratamientoCliente::find($tratamiento->id);
            $tratamiento->estado = 'Activo';
            $tratamiento->save();
        }
        foreach ($historiales as $historial) {
            $historial = HistorialCliente::find($historial->id);
            $historial->estado = 'Activo';
            $historial->save();
        }

        $this->emitTo('calls-center.lista-call', 'render');
        $this->emit('alert', '¡Cita confirmada satisfactoriamente!');
    }
    public function guardarllamada()
    {
        $this->tratamientosSeleccionados = array_filter($this->tratamientosSeleccionados, function ($value) {
            // Retorna true si el valor no es vacío ni nulo
            return !empty($value);
        });

        if ((!empty($this->tratamientosSeleccionados) && $this->hora && $this->minuto && $this->fechacita) != "" && ($this->llamada->empresa != "" && $this->llamada->empresa != "Sin nombre")) {
            $this->llamada->encargado = $this->elegido;
            $this->llamada->fechacita = $this->fechacita;
            $this->llamada->estado = "Pendiente";
            $this->hora = sprintf('%02d', $this->hora);
            $this->llamada->hora = $this->hora . ':' . $this->minuto;
            $this->llamada->save();
            $user = new User;
            $user->name = strtoupper($this->llamada->empresa);
            $user->medio = $this->llamada->modo;
            $user->estado = 'Activo';
            $user->rol = 'Cliente';
            $user->email = $this->llamada->ci;
            $user->password = "0000";
            $user->telefono = $this->llamada->telefono;
            if ($this->llamada->ci != "") {
                $user->ci = $this->llamada->ci;
            }
            $user->save();

            $operativo = new Operativos;
            $this->llamada->encargado = $this->elegido;
            $operativo->area = $this->llamada->area;
            $operativo->idempresa = $user->id;
            $operativo->empresa = $user->name;
            $operativo->fecha = $this->llamada->fechacita;
            $this->hora = sprintf('%02d', $this->hora);
            $operativo->hora = $this->hora . ':' . $this->minuto;
            $operativo->telefono = $this->llamada->telefono;
            $operativo->responsable = Auth::user()->name;
            $operativo->cantidadtotal = "0";
            $operativo->ingreso = "0";
            $operativo->cantidadaregistrar = "0";
            $operativo->encargado = $this->elegido;
            $operativo->estado = "Pendiente";
            $operativo->ci = $this->llamada->ci;
            $operativo->comentario = "0";
            $operativo->idllamada = $this->llamada->id;
            $operativo->save();

            if ($this->botonpaquete) {
                $paquete = Paquete::find($this->paqueteelegido);
                $lista = ListaTratamiento::where('estado', 'Activo')->where('idpaquete', $paquete->id)->get();
                $tratamientocliente = new TratamientoCliente;
                $tratamientocliente->idllamada = $this->llamada->id;
                $tratamientocliente->idcliente = $user->id;
                $tratamientocliente->fecha = $this->fechacita;
                $tratamientocliente->estado = 'Inactivo';
                $tratamientocliente->idoperativo = $operativo->id;
                $tratamientocliente->save();
                foreach ($lista as $item) {
                    $nuevo = new HistorialCliente;
                    $nuevo->idtratamiento = $item->idtratamiento;
                    $nuevo->idllamada = $this->llamada->id;
                    $nuevo->fecha = $this->fechacita;
                    $nuevo->idcliente = $user->id;
                    $nuevo->idtratamientocliente = $tratamientocliente->id;
                    $nuevo->estado = 'Inactivo';
                    $nuevo->idoperativo = $operativo->id;
                    $nuevo->save();
                }
            } else {
                $tratamientocliente = new TratamientoCliente;
                $tratamientocliente->idllamada = $this->llamada->id;
                $tratamientocliente->fecha = $this->fechacita;
                $tratamientocliente->estado = 'Inactivo';
                $tratamientocliente->idoperativo = $operativo->id;
                $tratamientocliente->save();
                foreach ($this->tratamientosSeleccionados as $elemento) {
                    $tratamiento = Tratamiento::find($elemento);
                    if ($tratamiento) {
                        $nuevo = new HistorialCliente;
                        $nuevo->idtratamiento = $tratamiento->id;
                        $nuevo->idllamada = $this->llamada->id;
                        $nuevo->idcliente = $user->id;
                        $nuevo->fecha = $this->fechacita;
                        $nuevo->costo = $tratamiento->costo;
                        $nuevo->nombretratamiento = $tratamiento->nombre;
                        $nuevo->idtratamientocliente = $tratamientocliente->id;
                        $nuevo->idoperativo = $operativo->id;
                        $nuevo->estado = 'Inactivo';
                        $nuevo->save();
                    }
                }
            }

            $this->reset(['openArea', 'openArea2', 'openArea3', 'openArea4']);
            $this->emitTo('calls-center.lista-call', 'render');
            $this->emit('alert', '¡Cita Pendiente guardada!');
        } else {
            $this->emitTo('calls-center.lista-call', 'render');
            $this->emit('error', '¡Algo anda mal! - VERIFICAR NOMBRE, FECHA, HORA , TELEFONO O TRATAMIENTO');
        }
    }
    public function guardaroperativo()
    {
        if (($this->hora && $this->minuto && $this->fecha) != "") {
            $this->llamada->estado = "Confirmado";
            $this->llamada->save();
            $empresa = new Empresas;
            $empresa->area = $this->llamada->area;
            $empresa->empresa = $this->llamada->empresa;
            $empresa->fecha = $this->fecha;
            $this->hora = sprintf('%02d', $this->hora);
            $empresa->hora = $this->hora . ':' . $this->minuto;
            $empresa->telefono = $this->llamada->telefono;
            $empresa->responsable = Auth::user()->name;
            $empresa->cantidadtotal = $this->llamada->cantidad;
            $empresa->ingreso = "0";
            $empresa->cantidadaregistrar = "0";
            $empresa->encargado = $this->elegido;
            if ($this->llamada->comentario == "") {
                $empresa->comentario = "";
            } else {
                $empresa->comentario = $this->llamada->comentario;
            }
            $empresa->estado = "Pendiente";
            $empresa->save();
            $nuevo = new Operativos;
            $nuevo->area = $this->llamada->area;
            $nuevo->idempresa = $empresa->id;
            $nuevo->empresa = $this->llamada->empresa;
            $nuevo->fecha = $this->fecha;
            $this->hora = sprintf('%02d', $this->hora);
            $nuevo->hora = $this->hora . ':' . $this->minuto;
            $nuevo->telefono = $this->llamada->telefono;
            $nuevo->responsable = Auth::user()->name;
            $nuevo->cantidadtotal = $this->llamada->cantidad;
            $nuevo->ingreso = "0";
            $nuevo->cantidadaregistrar = "0";
            $nuevo->encargado = $this->elegido;
            if ($this->llamada->comentario == "") {
                $nuevo->comentario = "";
            } else {
                $nuevo->comentario = $this->llamada->comentario;
            }
            $nuevo->estado = "Pendiente";
            $nuevo->save();
            $this->reset(['openArea', 'openArea2', 'openArea3', 'openArea4', 'openArea5']);
            $this->emitTo('calls-center.lista-call', 'render');
            $this->emit('alert', '¡Operativo confirmado satisfactoriamente!');
        } else {
            $this->emitTo('calls-center.lista-call', 'render');
            $this->emit('error', '¡Algo anda mal!');
        }
    }
    public function inactivar($idCall)
    {
        $llamada = Calls::find($idCall);
        $llamada->estado = "Inactivo";
        $llamada->save();
        $operativo = Operativos::where('idllamada', $llamada->id)->first();
        if ($operativo) {
            $operativo->estado = 'Inactivo';
            $operativo->save();
        }
        event(new EnviarMensaje('inactivar', 'llamada'));
        $this->reset(['openArea2']);
        $this->emitTo('calls-center.lista-call', 'render');
    }
    public function activar($idCall)
    {
        $llamada = Calls::find($idCall);
        $tratamiento = TratamientoCliente::where('idllamada', $llamada->id)->first();

        if ($tratamiento) {
            $llamada->estado = "Pendiente";
            $llamada->save();
            $operativo = Operativos::where('idllamada', $llamada->id)->first();
            $operativo->estado = 'Pendiente';
            $operativo->save();
        } else {
            $llamada->estado = "llamadas";
            $llamada->save();
        }

        $this->reset(['openArea3']);
        $this->emitTo('calls-center.lista-call', 'render');
    }
    public function activarLlamada()
    {

        $this->llamada->estado = "llamadas";
        $this->llamada->save();
        $this->reset(['openArea3']);
        $this->emit('alert', '¡Llamada desactivada!');
        $this->emitTo('calls-center.lista-call', 'render');
    }
    public function cancelar()
    {
        $this->reset(['openArea2', 'openArea', 'openArea3', 'openArea4', 'rellamar']);
    }
    public function rellamar()
    {

        if ($this->llamada) {
            $this->llamada->cantidad = $this->llamada->cantidad + 1;
            $this->llamada->comentario = $this->comentariollamada;
            $this->llamada->save();
            $registro = new registrollamadas();
            $registro->idllamada = $this->llamada->id;
            $registro->telefono = $this->llamada->telefono;
            $registro->iduser = Auth::user()->id;
            $registro->responsable = Auth::user()->name;
            $registro->fecha = Carbon::now()->toDateString();
            $registro->sucursal = '1';
            $registro->estado = 'Activo';
            $registro->save();
        }
        $this->reset(['openArea2', 'openArea', 'openArea3', 'openArea4', 'rellamar']);
        $this->emit('alert', '¡Re-Llamada guardada!');
    }
    public function enviarcompra()
    {
        $numeroTelefono = '591' . $this->llamada->telefono;
        $mensaje = "¡Hola!";
        $enlaceWhatsApp = 'https://wa.me/' . $numeroTelefono . '?text=' . urlencode($mensaje);
        return redirect()->to($enlaceWhatsApp);
    }
}
