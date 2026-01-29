<?php

namespace App\Http\Livewire\Clientes;

use App\Models\Calls;
use App\Models\HistorialCliente;
use App\Models\ListaTratamiento;
use App\Models\Pagos;
use App\Models\Operativos;
use App\Models\Empresas;
use App\Models\Paquete;
use App\Models\Tratamiento;
use App\Models\TratamientoCliente;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Areas;

use Carbon\Carbon;
use Livewire\WithPagination;

class CrearCliente extends Component
{
    public $llamada;
    public $name = "";
    public $edad;
    public $beneficiario = "";
    public $email;
    public $password;
    public $password2;
    public $telefono = '591';
    public $responsable;
    public $estado = "Activo";
    public $crear = false;
    public $sueldo = "0";
    public $abono = 0;
    public $cuota1 = 0;
    public $cuota2 = 0;
    public $empresaseleccionada;
    public $cuota3 = 0;
    public $cuota4 = 0;
    public $cuotascantidad = 0;
    public $busqueda;
    public $ci = "";
    public $cuotasmanual = 1;
    public $tipo = false;
    public $fechainicio;
    public $divisionreal = 0;
    public $botonpaquete = false;
    public $tratamientos;
    public $paquetes;
    public $tratamientosSeleccionados = [];
    public $elegido;
    public $elegidopaquete;
    public $fechacita;
    public $hora;
    public $minuto;
    public $sexo = "femenino";
    public $users;
    public $busquedatratamiento;
    public $mododepago = "Efectivo";
    public $modoingreso = 'Facebook';
    protected $rules = [
        'empresaseleccionada' => 'required',
        'name' => 'required',
        'sexo' => 'required',
        'fechacita' => 'required',
        'hora' => 'required',
        'minuto' => 'required',
        'tratamientosSeleccionados' => 'required',

    ];
    public function mount()
    {
        $this->fechacita = Carbon::now()->toDateString();
    }
    public function guardartodo()
    {
        $this->tratamientosSeleccionados = array_filter($this->tratamientosSeleccionados, function ($value) {
            // Retorna true si el valor no es vacío ni nulo
            return !empty($value);
        });
        $this->validate();
        $telefonoExistente = Calls::where('telefono', $this->telefono)->exists();
        $telefonopersonal = User::where('telefono', $this->telefono)->exists();
        if ($telefonoExistente) {
            $this->emitTo('calls-center.lista-call', 'render');
            $this->emit('error', '¡Telefono ya registrado!');
        } else {
            if ($telefonopersonal) {
                $this->crear = false;
                $this->emitTo('calls-center.lista-call', 'render');
                $this->emit('error', '¡Telefono ya registrado!');
            } else {
                if (($this->name  && $this->telefono && $this->empresaseleccionada) != "") {
                    $nuevo = new User;
                    $nuevo->name = $this->name;
                    $nuevo->email =  $this->ci;
                    $nuevo->rol = "Cliente";
                    $nuevo->medio = $this->modoingreso;
                    $nuevo->tesoreria = "Inactivo";
                    if ($this->ci != "") {
                        $nuevo->ci = $this->ci;
                    }
                    if ($this->telefono) {
                        $nuevo->telefono = $this->telefono;
                    } else {
                        $nuevo->telefono = "0";
                    }
                    $nuevo->password = "********";
                    if ($this->sueldo == "0") {
                        $nuevo->sueldo = "0";
                    } else {
                        $nuevo->sueldo = $this->sueldo;
                    }
                    $nuevo->estado = "Activo";
                    $nuevo->sucursal = "0";
                    $nuevo->sexo = $this->sexo;
                    $nuevo->edad = $this->edad;
                    $nuevo->save();
                    $user = $nuevo;
                    $operativo = new Operativos;
                    $operativo->area = $this->empresaseleccionada;
                    $operativo->idempresa = $nuevo->id;
                    $operativo->empresa = $nuevo->name;
                    $operativo->fecha = $this->fechacita;
                    $this->hora = sprintf('%02d', $this->hora);
                    $operativo->hora = $this->hora . ':' . $this->minuto;
                    $operativo->telefono = $this->telefono;
                    $operativo->responsable = Auth::user()->name;
                    $operativo->cantidadtotal = "0";
                    $operativo->ingreso = "0";
                    $operativo->cantidadaregistrar = "0";
                    $operativo->encargado = "0";
                    $operativo->estado = "Pendiente";
                    $operativo->ci = $this->ci;
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
                            $nuevo->idllamada = "1";
                            $nuevo->nombretratamiento = $tratamiento->nombre;
                            $nuevo->idcliente = $user->id;
                            $nuevo->costo = $tratamiento->costo;
                            $nuevo->fecha = $this->fechacita;
                            $nuevo->idtratamientocliente = $tratamientocliente->id;
                            $nuevo->idoperativo = $operativo->id;
                            $nuevo->estado = 'Inactivo';
                            $nuevo->save();
                        }
                    }
                    // for ($i = 0; $i < $this->cuotasmanual; $i++) {
                    //     if ($i == 0) {
                    //         $pago = new Pagos;
                    //         $pago->estado = 'Pendiente';
                    //         $pago->area = $operativo->area;
                    //         $pago->iduser = $user->id;
                    //         $pago->fecha = Carbon::now()->toDateString();
                    //         $pago->cantidad = $this->cuota1;
                    //         $pago->pagado = $this->cuota1;
                    //         $pago->idoperativo = $operativo->id;
                    //         $pago->save();
                    //     }
                    //     if ($i == 1) {
                    //         $pago = new Pagos;
                    //         $pago->estado = 'Pendiente';
                    //         $pago->area = $operativo->area;
                    //         $pago->iduser = $user->id;
                    //         $pago->fecha = Carbon::now()->toDateString();
                    //         $pago->cantidad = $this->cuota2;
                    //         $pago->pagado = $this->cuota2;
                    //         $pago->idoperativo = $operativo->id;
                    //         $pago->save();
                    //     }
                    //     if ($i == 2) {
                    //         $pago = new Pagos;
                    //         $pago->estado = 'Pendiente';
                    //         $pago->area = $operativo->area;
                    //         $pago->iduser = $user->id;
                    //         $pago->fecha = Carbon::now()->toDateString();
                    //         $pago->cantidad = $this->cuota3;
                    //         $pago->pagado = $this->cuota3;
                    //         $pago->idoperativo = $operativo->id;
                    //         $pago->save();
                    //     }
                    //     if ($i == 3) {
                    //         $pago = new Pagos;
                    //         $pago->estado = 'Pendiente';
                    //         $pago->area = $operativo->area;
                    //         $pago->iduser = $user->id;
                    //         $pago->fecha = Carbon::now()->toDateString();
                    //         $pago->cantidad = $this->cuota4;
                    //         $pago->pagado = $this->cuota4;
                    //         $pago->idoperativo = $operativo->id;
                    //         $pago->save();
                    //     }
                    // }
                    $this->reset([
                        'crear',
                        'llamada',
                        'name',
                        'beneficiario',
                        'email',
                        'password',
                        'password2',
                        'telefono',
                        'responsable',
                        'estado',
                        'crear',
                        'sueldo',
                        'abono',
                        'cuota1',
                        'cuota2',
                        'empresaseleccionada',
                        'cuota3',
                        'cuotascantidad',
                        'busqueda',
                        'ci',
                        'cuotasmanual',
                        'tipo',
                        'fechainicio',
                        'divisionreal',
                    ]);
                    $this->emitTo('clientes.lista-clientes', 'render');
                    $this->emitTo('recepcionista.lista-recepcion', 'render');
                    $this->emit('alert', '¡Cliente creado satisfactoriamente!');
                } else {
                    $this->emitTo('clientes.crear-cliente', 'render');
                    $this->emit('error', '¡Algo anda mal!');
                }
            }
        }
    }
    protected $listeners = ['render' => 'render', 'inactivar' => 'inactivar', 'activar' => 'activar'];
    public function render()
    {
        $this->users = User::where('estado', 'Activo')->where('rol', '!=', 'Cliente')->orderBy('id', 'desc')->get();
        $this->tratamientos = Tratamiento::where('estado', 'Activo')->where('nombre', 'ilike', '%' . $this->busquedatratamiento . '%')->orderBy('nombre', 'asc')->get();
        $this->paquetes = Paquete::where('estado', 'Activo')->get();
        if ($this->busqueda == "") {
            $empresas = Areas::where('estado', 'Activo')->orderBy('id', 'desc')->get();
        } else {
            $empresas = Areas::where('empresa', 'like', '%' . $this->busqueda . '%')->where('estado', 'Activo')->orderBy('id', 'desc')->get();
        }
        return view('livewire.clientes.crear-cliente', compact('empresas'));
    }
    public function dividirabono()
    {
        if ($this->abono <= 300) {
            $this->cuota1 = $this->abono;
            $this->divisionreal = $this->abono;
            $this->cuotascantidad = 1;
            $this->cuota2 = 0;
            $this->cuota3 = 0;
        }
        if ($this->abono > 300 && $this->abono < 599) {
            $this->divisionreal = number_format($this->abono / 2, 2);
            $this->cuota1 = round($this->abono / 2);
            $this->cuota2 = round($this->abono / 2) + ($this->abono - (round($this->abono / 2) * 2));
            $this->cuota3 = 0;
            $this->cuotascantidad = 2;
        }
        if ($this->abono >= 600) {
            $this->divisionreal = number_format($this->abono / 3, 2);
            $this->cuota1 = round($this->abono / 3);
            $this->cuota2 = round($this->abono / 3);
            $this->cuota3 = round($this->abono / 3) + ($this->abono - (round($this->abono / 3) * 3));
            $this->cuotascantidad = 3;
        }
        $this->emitTo('clientes.crear-cliente', 'render');
        $this->tipo = true;
    }
}
