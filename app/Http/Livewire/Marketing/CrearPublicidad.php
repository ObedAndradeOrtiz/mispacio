<?php

namespace App\Http\Livewire\Marketing;

use App\Models\Areas;
use App\Models\campana;
use App\Models\cuentacomercial;
use App\Models\publicidades;
use App\Models\TelefonoWss;
use Carbon\Carbon;
use Livewire\Component;

class CrearPublicidad extends Component
{
    public $cuentas;
    public $crearpublicidad = false;
    public $elegido;
    public $fechainicio;
    public $comentario;
    public $tipo;
    public $campañas;
    public $campañaelegida;
    public $cuentaelegida;
    public $areas;
    public $areaelegida;
    public $hora;
    public $minuto;
    public $frecuencia = "frec0";
    public $sucursal = "suc0";
    public $tipocliente = "tip0";
    public $asistencia = "siasis";
    public $fechafinal;
    public $numero = "";
    public $fecharango;
    public function render()
    {
        $this->campañas = campana::where('estado', 'Activo')->get();
        $this->cuentas = TelefonoWss::where('estado', 'Activo')->get();
        $this->areas = Areas::where('estado', 'Activo')->get();
        return view('livewire.marketing.crear-publicidad');
    }
    public function guardartodo()
    {
        $nuevo = new publicidades;
        if ($this->fecharango) {
            $nuevo->fecharango = $this->fecharango;
        }

        $nuevo->fechainicio = $this->fechainicio;
        $nuevo->fechafin = $this->fechafinal;
        $nuevo->motivo = $this->comentario;
        $nuevo->idcampana = $this->campañaelegida;
        if ($this->campañaelegida != 0) {
            $campaña = campana::find($this->campañaelegida);
            $nuevo->nombrecampana = $campaña->tipo;
        }
        if ($this->sucursal != 'suc0') {
            $nuevo->idcuenta = $this->cuentaelegida;
            $cuenta = TelefonoWss::find($this->cuentaelegida);
            $nuevo->nombrecuenta = $cuenta->nombre;
        }

        $nuevo->frecuencia = $this->frecuencia;
        $nuevo->estado = 'Activo';
        $this->hora = sprintf('%02d', $this->hora);
        $nuevo->hora = $this->hora . ':' . $this->minuto;
        $nuevo->motivo = $this->comentario;
        $nuevo->tipocliente = $this->tipocliente;
        $nuevo->asistencia = $this->asistencia;
        $nuevo->enviosucursal = $this->sucursal;
        $nuevo->numero = $this->numero;
        $nuevo->save();
        $this->emitTo('crm.panel-mensajes', 'render');
        $this->emit('alert', '¡Publicidad agregada satisfactoriamente!');
    }
}