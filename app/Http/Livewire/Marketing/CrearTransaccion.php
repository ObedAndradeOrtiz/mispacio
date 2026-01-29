<?php

namespace App\Http\Livewire\Marketing;

use App\Models\cuentacomercial;
use App\Models\tarjetas;
use App\Models\transacciones;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CrearTransaccion extends Component
{
    public $creartransaccion = false;
    public $cuentas;
    public $cuentaseleccionado;
    public $tarjetas;
    public $codigo = 0;
    public $cantidadde = 0;
    public $cantidaddeuso = 0;
    public $tarjetap;
    public $tarjetae;
    public $fecha;
    public $aumento = 0;
    public $cantidaaumnento = 0;
    public $opcion = '';
    public $devolucion = false;
    public $motivo = "";
    public $selectedOption;
    public $tipocambio = "BS";
    public $cantidadusd = 11.30;

    protected $rules = [
        'cantidadde' => 'required',
        'codigo' => 'required',
        'fecha' => 'required',
        'cantidaddeuso' => 'required',
        'tarjetap' => 'required',
        'tarjetae' => 'required',
        'cuentaseleccionado' => 'required',
    ];
    public function mount()
    {
        $this->fecha = Carbon::now()->toDateString();
    }
    public function render()
    {
        $this->tarjetas = tarjetas::where('estado', 'Activo')->orderBy('nombretarjeta')->get();
        $this->cuentas = cuentacomercial::where('estado', 'Activo')->get();
        return view('livewire.marketing.crear-transaccion');
    }
    public function updatedSelectedOption($value)
    {
        if ($value === 'creartransaccion') {
            $this->creartransaccion = true;
            $this->aumento = false;
        } elseif ($value === 'aumento') {
            $this->creartransaccion = false;
            $this->aumento = true;
        }
    }
    public function guardartodo()
    {
        if ($this->opcion == "pagosPublicidad") {
            $transaccion = new transacciones;
            $cuentaemisora = tarjetas::find($this->tarjetae);
            if ($this->cantidaddeuso > $cuentaemisora->saldo) {
                $this->emit('error', '¡Saldo insuficiente, recargue!');
            } else {
                if ($this->cantidaddeuso >= 0) {
                    $cuentacomercial = cuentacomercial::find($this->cuentaseleccionado);
                    $transaccion->idtarjetaprincipal = $cuentaemisora->id;
                    $transaccion->tarjetaprincipal = $cuentaemisora->nombretarjeta;
                    $transaccion->idtarjeta = $cuentaemisora->id;
                    $transaccion->tarjeta = $cuentaemisora->nombretarjeta;
                    $transaccion->idcuenta = $cuentacomercial->id;
                    $transaccion->nombrecuenta = $cuentacomercial->nombrecuenta;
                    $transaccion->iduser = Auth::user()->id;
                    $transaccion->responsable = Auth::user()->name;
                    $transaccion->fecha = $this->fecha;
                    $transaccion->motivo = 'PAGO DE PUBLICIDAD';
                    $transaccion->codigo = $this->codigo;
                    if ($this->tipocambio == "USD") {
                        $transaccion->monto = $this->cantidaddeuso * $this->cantidadusd;
                        $transaccion->montouso =  $this->cantidaddeuso * $this->cantidadusd;;
                    } else {
                        $transaccion->monto = $this->cantidaddeuso;
                        $transaccion->montouso =  $this->cantidaddeuso;
                    }

                    $transaccion->idmotivo = 3;
                    $transaccion->estado = 'Activo';
                    $transaccion->save();
                    $cuentaemisora->saldo = $cuentaemisora->saldo - $this->cantidaddeuso;
                    $cuentaemisora->save();
                    $this->emit('alert', '¡Transacción creada satisfactoriamente!');
                    $this->emitTo('marketing.marketing', 'render');
                } else {
                    $this->emit('error', '¡Numeros invalidos!');
                }
            }
        }
        if ($this->opcion == "pagosPublicidadenvio") {
            $transaccion = new transacciones;
            $cuentaemisora = tarjetas::find($this->tarjetae);
            if ($this->cantidaddeuso > $cuentaemisora->saldo) {
                $this->emit('error', '¡Saldo insuficiente, recargue!');
            } else {
                if ($this->cantidaddeuso >= 0) {
                    $cuentareceptora = tarjetas::find($this->tarjetap);
                    $cuentacomercial = cuentacomercial::find($this->cuentaseleccionado);
                    $transaccion->idtarjetaprincipal = $cuentaemisora->id;
                    $transaccion->tarjetaprincipal = $cuentaemisora->nombretarjeta;
                    $transaccion->idtarjeta = $cuentareceptora->id;
                    $transaccion->tarjeta = $cuentareceptora->nombretarjeta;
                    $transaccion->idcuenta = $cuentacomercial->id;
                    $transaccion->nombrecuenta = $cuentacomercial->nombrecuenta;
                    $transaccion->iduser = Auth::user()->id;
                    $transaccion->responsable = Auth::user()->name;
                    $transaccion->fecha = $this->fecha;
                    $transaccion->motivo = 'PAGO DE PUBLICIDAD';
                    $transaccion->codigo = $this->codigo;
                    if ($this->tipocambio == "USD") {
                        $transaccion->monto = $this->cantidaddeuso * $this->cantidadusd;
                        $transaccion->montouso =  $this->cantidadde * $this->cantidadusd;
                        $cuentaemisora->saldo = $cuentaemisora->saldo - ($this->cantidaddeuso * $this->cantidadusd);
                        $cuentaemisora->save();
                        $cuentareceptora->saldo = ($cuentareceptora->saldo + ($this->cantidaddeuso * $this->cantidadusd)) - ($this->cantidadde * $this->cantidadusd);
                        $cuentareceptora->save();
                    } else {
                        $transaccion->monto = $this->cantidaddeuso;
                        $transaccion->montouso =  $this->cantidadde;
                        $cuentaemisora->saldo = $cuentaemisora->saldo - $this->cantidaddeuso;
                        $cuentaemisora->save();
                        $cuentareceptora->saldo = ($cuentareceptora->saldo + $this->cantidaddeuso) - $this->cantidadde;
                        $cuentareceptora->save();
                    }
                    $transaccion->idmotivo = 4;
                    $transaccion->estado = 'Activo';
                    $transaccion->save();

                    $this->emit('alert', '¡Transacción creada satisfactoriamente!');
                    $this->emitTo('marketing.marketing', 'render');
                } else {
                    $this->emit('error', '¡Numeros invalidos!');
                }
            }
        }
        if ($this->opcion == "envioSaldo") {
            $transaccion = new transacciones;
            $cuentaemisora = tarjetas::find($this->tarjetae);
            if ($this->cantidaddeuso > $cuentaemisora->saldo) {
                $this->emit('error', '¡Saldo insuficiente, recargue!');
            } else {
                if ($this->cantidaddeuso >= 0) {
                    $cuentareceptora = tarjetas::find($this->tarjetap);
                    $cuentacomercial = cuentacomercial::find($this->cuentaseleccionado);
                    $transaccion->idtarjetaprincipal = $cuentaemisora->id;
                    $transaccion->tarjetaprincipal = $cuentaemisora->nombretarjeta;
                    $transaccion->idtarjeta = $cuentareceptora->id;
                    $transaccion->tarjeta = $cuentareceptora->nombretarjeta;
                    $transaccion->iduser = Auth::user()->id;
                    $transaccion->responsable = Auth::user()->name;
                    $transaccion->fecha = $this->fecha;
                    $transaccion->motivo = 'ENVIO DE SALDO';
                    $transaccion->monto = $this->cantidaddeuso;
                    $transaccion->montouso =  $this->cantidaddeuso;

                    if ($this->tipocambio == "USD") {
                        $transaccion->monto = $this->cantidaddeuso * $this->cantidadusd;
                        $transaccion->montouso =  $this->cantidaddeuso * $this->cantidadusd;
                    } else {
                        $transaccion->monto = $this->cantidaddeuso;
                        $transaccion->montouso = $this->cantidaddeuso;
                    }
                    $transaccion->idmotivo = 5;
                    $transaccion->estado = 'Activo';
                    $transaccion->save();
                    if ($this->tipocambio == "USD") {

                        $cuentaemisora->saldo = $cuentaemisora->saldo - ($this->cantidaddeuso * $this->cantidadusd);
                        $cuentaemisora->save();
                        $cuentareceptora->saldo = $cuentareceptora->saldo + ($this->cantidaddeuso * $this->cantidadusd);
                        $cuentareceptora->save();
                    } else {
                        $cuentaemisora->saldo = $cuentaemisora->saldo - $this->cantidaddeuso;
                        $cuentaemisora->save();
                        $cuentareceptora->saldo = $cuentareceptora->saldo + $this->cantidaddeuso;
                        $cuentareceptora->save();
                    }

                    $this->emit('alert', '¡Transacción creada satisfactoriamente!');
                    $this->emitTo('marketing.marketing', 'render');
                } else {
                    $this->emit('error', '¡Numeros invalidos!');
                }
            }
        }
        if ($this->opcion == "verificacion") {
            $transaccion = new transacciones;
            $cuentaemisora = tarjetas::find($this->tarjetae);
            if ($this->cantidaddeuso > $cuentaemisora->saldo) {
                $this->emit('error', '¡Saldo insuficiente, recargue!');
            } else {
                if ($this->cantidaddeuso > 0) {
                    $cuentareceptora = tarjetas::find($this->tarjetap);
                    $cuentacomercial = cuentacomercial::find($this->cuentaseleccionado);
                    $transaccion->idtarjetaprincipal = $cuentaemisora->id;
                    $transaccion->tarjetaprincipal = $cuentaemisora->nombretarjeta;
                    $transaccion->idtarjeta = $cuentaemisora->id;
                    $transaccion->tarjeta = $cuentaemisora->nombretarjeta;
                    $transaccion->iduser = Auth::user()->id;
                    $transaccion->responsable = Auth::user()->name;
                    $transaccion->fecha = $this->fecha;
                    $transaccion->motivo = 'VERIFICACION TARJETA';
                    $transaccion->monto = $this->cantidaddeuso;
                    $transaccion->montouso =  $this->cantidaddeuso;
                    $transaccion->idmotivo = 6;
                    $transaccion->estado = 'Activo';
                    $transaccion->save();
                    $cuentaemisora->saldo = $cuentaemisora->saldo - $this->cantidaddeuso;
                    $cuentaemisora->save();
                    $this->emit('alert', '¡Transacción creada satisfactoriamente!');
                    $this->emitTo('marketing.marketing', 'render');
                } else {
                    $this->emit('error', '¡Numeros invalidos!');
                }
            }
        }
        if ($this->opcion == "seguro") {
            $transaccion = new transacciones;
            $cuentaemisora = tarjetas::find($this->tarjetae);
            if ($this->cantidaddeuso > $cuentaemisora->saldo) {
                $this->emit('error', '¡Saldo insuficiente, recargue!');
            } else {
                if ($this->cantidaddeuso > 0) {
                    $cuentareceptora = tarjetas::find($this->tarjetap);
                    $cuentacomercial = cuentacomercial::find($this->cuentaseleccionado);
                    $transaccion->idtarjetaprincipal = $cuentaemisora->id;
                    $transaccion->tarjetaprincipal = $cuentaemisora->nombretarjeta;
                    $transaccion->idtarjeta = $cuentaemisora->id;
                    $transaccion->tarjeta = $cuentaemisora->nombretarjeta;
                    $transaccion->iduser = Auth::user()->id;
                    $transaccion->responsable = Auth::user()->name;
                    $transaccion->fecha = $this->fecha;
                    $transaccion->motivo = 'SEGURO DE TARJETA';
                    $transaccion->monto = $this->cantidaddeuso;
                    $transaccion->montouso =  $this->cantidaddeuso;
                    $transaccion->idmotivo = 7;
                    $transaccion->estado = 'Activo';
                    $transaccion->save();
                    $cuentaemisora->saldo = $cuentaemisora->saldo - $this->cantidaddeuso;
                    $cuentaemisora->save();
                    $this->emit('alert', '¡Transacción creada satisfactoriamente!');
                    $this->emitTo('marketing.marketing', 'render');
                } else {
                    $this->emit('error', '¡Numeros invalidos!');
                }
            }
        }
        if ($this->opcion == "otro") {
            $transaccion = new transacciones;
            $cuentaemisora = tarjetas::find($this->tarjetae);
            if ($this->cantidaddeuso > $cuentaemisora->saldo) {
                $this->emit('error', '¡Saldo insuficiente, recargue!');
            } else {
                if ($this->cantidaddeuso > 0) {
                    $transaccion->nombrecuenta = $this->motivo;
                    $cuentareceptora = tarjetas::find($this->tarjetap);
                    $cuentacomercial = cuentacomercial::find($this->cuentaseleccionado);
                    $transaccion->idtarjetaprincipal = $cuentaemisora->id;
                    $transaccion->tarjetaprincipal = $cuentaemisora->nombretarjeta;
                    $transaccion->idtarjeta = $cuentaemisora->id;
                    $transaccion->tarjeta = $cuentaemisora->nombretarjeta;
                    $transaccion->iduser = Auth::user()->id;
                    $transaccion->responsable = Auth::user()->name;
                    $transaccion->fecha = $this->fecha;
                    $transaccion->motivo = 'OTRO';
                    if ($this->tipocambio == "USD") {
                        $transaccion->monto = $this->cantidaddeuso * $this->cantidadusd;
                        $transaccion->montouso =  $this->cantidaddeuso * $this->cantidadusd;
                    } else {
                        $transaccion->monto = $this->cantidaddeuso;
                        $transaccion->montouso =  $this->cantidaddeuso;
                    }

                    $transaccion->idmotivo = 8;
                    $transaccion->estado = 'Activo';
                    $transaccion->save();
                    if ($this->tipocambio == "USD") {
                        $cuentaemisora->saldo = $cuentaemisora->saldo - ($this->cantidaddeuso * $this->cantidadusd);
                    } else {
                        $cuentaemisora->saldo = $cuentaemisora->saldo - $this->cantidaddeuso;
                    }

                    $cuentaemisora->save();
                    $this->emit('alert', '¡Transacción creada satisfactoriamente!');
                    $this->emitTo('marketing.marketing', 'render');
                } else {
                    $this->emit('error', '¡Numeros invalidos!');
                }
            }
        }
    }
    public function guardaraumento()
    {
        if ($this->cantidaaumnento >= 0) {
            $transaccion = new transacciones;
            $cuentareceptora = tarjetas::find($this->tarjetap);
            $transaccion->idtarjetaprincipal = $cuentareceptora->id;
            $transaccion->tarjetaprincipal = $cuentareceptora->nombretarjeta;
            $transaccion->idtarjeta = $cuentareceptora->id;
            $transaccion->tarjeta = $cuentareceptora->nombretarjeta;
            $transaccion->idcuenta = 0;
            $transaccion->nombrecuenta = "INTERNO";
            $transaccion->iduser = Auth::user()->id;
            $transaccion->responsable = Auth::user()->name;
            $transaccion->fecha = $this->fecha;
            $transaccion->motivo = 'AUMENTO DE SALDO';
            $transaccion->codigo = 0;
            $transaccion->monto = $this->cantidaaumnento;
            $transaccion->montouso = 0;
            $transaccion->idmotivo = 2;
            $transaccion->estado = 'Activo';
            $transaccion->save();
            $cuentareceptora->saldo = $cuentareceptora->saldo + $this->cantidaaumnento;
            $cuentareceptora->save();
            $this->emitTo('marketing.marketing', 'render');
            $this->emit('alert', '¡Transacción creada satisfactoriamente!');
        } else {
            $this->emit('error', '¡Numero invalido!');
        }
    }
    public function guardardevolucion()
    {
        if ($this->cantidaaumnento >= 0) {
            $transaccion = new transacciones;
            $cuentareceptora = tarjetas::find($this->tarjetap);
            $cuentaemisora = tarjetas::find($this->tarjetae);
            $transaccion->idtarjetaprincipal =  $cuentaemisora->id;
            $transaccion->tarjetaprincipal =  $cuentaemisora->nombretarjeta;
            $transaccion->idtarjeta = $cuentareceptora->id;
            $transaccion->tarjeta = $cuentareceptora->nombretarjeta;
            $transaccion->idcuenta = 0;
            $transaccion->nombrecuenta = "INTERNO";
            $transaccion->iduser = Auth::user()->id;
            $transaccion->responsable = Auth::user()->name;
            $transaccion->fecha = $this->fecha;
            $transaccion->motivo = 'DEVOLUCION';
            $transaccion->codigo = 0;
            $transaccion->monto = $this->cantidaaumnento;
            $transaccion->montouso = 0;
            $transaccion->idmotivo = 3;
            $transaccion->estado = 'Activo';
            $transaccion->save();
            $cuentaemisora->saldo = $cuentaemisora->saldo - $this->cantidaaumnento;
            $cuentareceptora->saldo = $cuentareceptora->saldo + $this->cantidaaumnento;
            $cuentareceptora->save();
            $cuentaemisora->save();
            $this->emitTo('marketing.marketing', 'render');
            $this->emit('alert', '¡Transacción creada satisfactoriamente!');
        } else {
            $this->emit('error', '¡Numero invalido!');
        }
    }
}