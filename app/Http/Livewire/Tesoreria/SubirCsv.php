<?php

namespace App\Http\Livewire\Tesoreria;

use Livewire\Component;
use App\Models\Areas;
use App\Models\Productos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\registropago;
use Livewire\WithFileUploads;
use App\Models\Paquete;
use App\Models\Tratamiento;
use App\Models\Operativos;
use App\Models\HistorialCliente;
use App\Models\TratamientoCliente;
use App\Models\User;
use App\Models\Calls;
use Illuminate\Support\Facades\Auth;

class SubirCsv extends Component
{
    public $sucursal;
    public $areas;
    public $textoCSV;
    public $crear = false;
    public $datos = [];
    public $fecha;
    public function procesarTextoCSV()
    {
        $lineas = explode("\n", $this->textoCSV);
        foreach ($lineas as $linea) {
            if (preg_match('/^(domingo|lunes|martes|miércoles|jueves|viernes|sábado), \d{1,2} de \w+ de \d{4}/', $linea)) {
                $this->fecha = date('Y-m-d', strtotime($this->fecha . ' +1 day'));
                continue;
            }
            $datos = explode(';', $linea);
            $TRATAMIENTO_1 = "";
            $TRATAMIENTO_2 = "";
            $TRATAMIENTO_3 = "";
            $DESCRIPCION = "";
            $BANCO = "";
            $PAGO_Bs = "";
            $TELEFONO = "";
            list($RECIBO, $PACIENTE, $TELEFONO, $HORA, $EDAD, $SEXO, $TRATAMIENTO_1, $TRATAMIENTO_2, $TRATAMIENTO_3, $DESCRIPCION, $BANCO, $PAGO_Bs) = $datos;
            if ($PAGO_Bs) {
                $PAGO_Bs = intval($PAGO_Bs);
            }
            if ($TELEFONO != "") {
                $telefonopersonal = User::where('telefono', $TELEFONO)->where('name', 'ilike', '%' . $PACIENTE . '%')->exists();
                if ($telefonopersonal) {
                    $nuevo = User::where('telefono', $TELEFONO)->where('name', 'ilike', '%' . $PACIENTE . '%')->first();
                    $operativo = new Operativos;
                    $operativo->area = $this->sucursal;
                    $operativo->idempresa = $nuevo->id;
                    $operativo->empresa = $nuevo->name;
                    $operativo->fecha = $this->fecha;
                    $operativo->hora = $HORA;
                    if ($TELEFONO) {
                        $operativo->telefono = $TELEFONO;
                    }
                    $operativo->responsable = Auth::user()->name;
                    $operativo->cantidadtotal = "0";
                    $operativo->ingreso = "0";
                    $operativo->cantidadaregistrar = "0";
                    $operativo->encargado = "0";
                    $operativo->estado = "Pendiente";
                    $operativo->comentario = $DESCRIPCION;
                    $operativo->idllamada = '0';
                    $operativo->save();
                    $tratamientocliente = new TratamientoCliente;
                    $tratamientocliente->idllamada = "0";
                    $tratamientocliente->fecha = $this->fecha;
                    $tratamientocliente->estado = 'Activo';
                    $tratamientocliente->idoperativo = $operativo->id;
                    $tratamientocliente->save();
                    $tratamientosSeleccionados = [$TRATAMIENTO_1, $TRATAMIENTO_2, $TRATAMIENTO_3];
                    foreach ($tratamientosSeleccionados as $elemento) {
                        $tratamiento = Tratamiento::where('nombre',  $elemento)->first();
                        if ($tratamiento) {
                            $nuevo = new HistorialCliente;
                            $nuevo->idtratamiento = $tratamiento->id;
                            $nuevo->idllamada = "1";
                            $nuevo->nombretratamiento = $tratamiento->nombre;
                            $nuevo->idcliente = $operativo->idempresa;
                            $nuevo->costo = $tratamiento->costo;
                            $nuevo->fecha = $this->fecha;
                            $nuevo->idtratamientocliente = $tratamientocliente->id;
                            $nuevo->idoperativo = $operativo->id;
                            $nuevo->estado = 'Activo';
                            $nuevo->save();
                        }
                    }
                    $registro = new registropago;
                    $sucursal = Areas::where('area', $operativo->area)->first();
                    $registro->idsucursal = $sucursal->id;
                    $registro->sucursal = $sucursal->area;
                    $registro->idoperativo = $operativo->id;
                    $registro->nombrecliente = $operativo->empresa;
                    if (!empty($PAGO_Bs)) {
                        $registro->monto = $PAGO_Bs;
                    }
                    $registro->iduser = Auth::user()->id;
                    $registro->responsable = Auth::user()->name;
                    $registro->idcliente = $operativo->idempresa;
                    $registro->fecha = $this->fecha;
                    if ($BANCO != "") {
                        $registro->modo = "QR";
                    } else {
                        $registro->modo = "Efectivo";
                    }
                    $registro->motivo = "operativo";
                    $registro->estado = 'Activo';
                    $registro->save();
                } else {
                    $nuevo = new User;
                    $nuevo->name = $PACIENTE;
                    if ($TELEFONO) {
                        $nuevo->email = $TELEFONO;
                    }
                    $nuevo->rol = "Cliente";
                    $nuevo->medio = "OTRO";
                    $nuevo->tesoreria = "Inactivo";
                    if ($TELEFONO) {
                        $nuevo->telefono = $TELEFONO;
                    } else {
                        $nuevo->telefono = "0";
                    }
                    $nuevo->password = "********";
                    $nuevo->sueldo = "0";
                    $nuevo->estado = "Activo";
                    $nuevo->sucursal = "0";
                    if ($SEXO) {
                    }
                    $nuevo->sexo = $SEXO;
                    if ($EDAD) {
                        $nuevo->edad = $EDAD;
                    }
                    $nuevo->save();
                    $user = $nuevo;
                    $operativo = new Operativos;
                    $operativo->area = $this->sucursal;
                    $operativo->idempresa = $nuevo->id;
                    $operativo->empresa = $nuevo->name;
                    $operativo->fecha = $this->fecha;
                    $operativo->hora = $HORA;
                    if ($TELEFONO) {
                        $operativo->telefono = $TELEFONO;
                    }
                    $operativo->responsable = Auth::user()->name;
                    $operativo->cantidadtotal = "0";
                    $operativo->ingreso = "0";
                    $operativo->cantidadaregistrar = "0";
                    $operativo->encargado = "0";
                    $operativo->estado = "Pendiente";
                    $operativo->comentario = "0";
                    $operativo->idllamada = '0';
                    $operativo->save();
                    $tratamientocliente = new TratamientoCliente;
                    $tratamientocliente->idllamada = "0";
                    $tratamientocliente->fecha = $this->fecha;
                    $tratamientocliente->estado = 'Activo';
                    $tratamientocliente->idoperativo = $operativo->id;
                    $tratamientocliente->save();
                    $tratamientosSeleccionados = [$TRATAMIENTO_1, $TRATAMIENTO_2, $TRATAMIENTO_3];
                    foreach ($tratamientosSeleccionados as $elemento) {
                        if ($elemento) {
                            $tratamiento = Tratamiento::where('nombre', 'ilike', '%' . $elemento . '%')->first();
                            if ($tratamiento) {
                                $nuevo = new HistorialCliente;
                                $nuevo->idtratamiento = $tratamiento->id;
                                $nuevo->idllamada = "1";
                                $nuevo->nombretratamiento = $tratamiento->nombre;
                                $nuevo->idcliente = $user->id;
                                $nuevo->costo = $tratamiento->costo;
                                $nuevo->fecha = $this->fecha;
                                $nuevo->idtratamientocliente = $tratamientocliente->id;
                                $nuevo->idoperativo = $operativo->id;
                                $nuevo->estado = 'Activo';
                                $nuevo->save();
                            }
                        }
                    }
                    $registro = new registropago;
                    $sucursal = Areas::where('area', $operativo->area)->first();
                    $registro->idsucursal = $sucursal->id;
                    $registro->sucursal = $sucursal->area;
                    $registro->idoperativo = $operativo->id;
                    $registro->nombrecliente = $operativo->empresa;
                    if (!empty($PAGO_Bs)) {
                        $registro->monto = $PAGO_Bs;
                    }
                    $registro->iduser = Auth::user()->id;
                    $registro->responsable = Auth::user()->name;
                    $registro->idcliente = $operativo->idempresa;
                    $registro->fecha = $this->fecha;
                    if ($BANCO != "") {
                        $registro->modo = "QR";
                    } else {
                        $registro->modo = "Efectivo";
                    }
                    $registro->motivo = "operativo";
                    $registro->estado = 'Activo';
                    $registro->save();
                }
            }
        }
        $this->emit('alert', '¡Datos procesados!');
    }
    public function render()
    {
        $this->areas = Areas::where('estado', 'Activo')->get();

        return view('livewire.tesoreria.subir-csv');
    }
}
