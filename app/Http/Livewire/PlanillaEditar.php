<?php

namespace App\Http\Livewire;

use App\Models\Planilla;
use App\Models\PlanillaSueldo;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Illuminate\Support\Str;

class PlanillaEditar extends Component
{
    public $fechapago = "";
    public $semana;
    public $pagotota = 0;
    public $bonos = [];
    public $anticipos = 0;
    public $responsable;
    public $planilla;
    public $planillas;
    public $estado;
    public $haberbasico = [];
    public $totalHaberBasico = 0;
    public $totalBonos = 0;
    public $totalSueldoHora = 0;
    public $totalHorasDias = 0;
    public $totalDiasTrabajados = 0;
    public $totalHorasExtras = 0;
    public $totalAnticipo = 0;
    public $totalPagado = 0;

    public $idcargo = [];
    public $sucursal = [];
    public $haber = [];
    public $sueldohora = [];
    public $horasdias = [];
    public $diastrabajados = [];
    public $horasextras = [];
    public $anticipo = [];
    public $pagado = [];
    public $idplanilla;

    public function mount($idplanilla)
    {
        $this->idplanilla = $idplanilla;
        $this->planilla = Planilla::find($idplanilla);
        $this->estado = $this->planilla->estado;
        $this->fechapago = $this->planilla->fecha;
        $this->semana = $this->planilla->semana;
        $this->planillas = PlanillaSueldo::where('idplanilla', $this->planilla->id)->orderBy('id', 'asc')->get();
        foreach ($this->planillas as $plan) {
            $this->haberbasico[$plan->id] = $plan->haberbasico;
            $this->bonos[$plan->id] = $plan->bonos;

            $this->sucursal[$plan->id] = $plan->sucursal;
            $this->sueldohora[$plan->id] = $plan->sueldohora;
            $this->horasdias[$plan->id] = $plan->horasdias;
            $this->diastrabajados[$plan->id] = $plan->diastrabajados;
            $this->horasextras[$plan->id] = $plan->horasextras;
            $this->anticipo[$plan->id] = $plan->anticipo;
            $this->pagado[$plan->id] = $plan->pagado;
        }
        $this->totalHaberBasico = array_sum($this->haberbasico);
        $this->totalBonos = array_sum($this->bonos);
        $this->totalSueldoHora = array_sum($this->sueldohora);
        $this->totalHorasDias = array_sum($this->horasdias);
        $this->totalDiasTrabajados = array_sum($this->diastrabajados);
        $this->totalHorasExtras = array_sum($this->horasextras);
        $this->totalAnticipo = array_sum($this->anticipo);
        $this->totalPagado = array_sum($this->pagado);
    }
    public function updated($field)
    {
        if (Str::startsWith($field, ['pagado', 'horasdias', 'sueldohora', 'horasextras', 'anticipo', 'bonos', 'haberbasico', 'diastrabajados'])) {


            $planillaId = explode('.', $field)[1];
            $horasdias = floatval($this->horasdias[$planillaId] ?? 0);
            $sueldohora = floatval($this->sueldohora[$planillaId] ?? 0);
            $horasextras = floatval($this->horasextras[$planillaId] ?? 0);
            $anticipo = floatval($this->anticipo[$planillaId] ?? 0);
            $bonos = floatval($this->bonos[$planillaId] ?? 0);
            $multi = ($horasdias * $sueldohora);
            $resultado = $multi  + $bonos + $horasextras - $anticipo;
            $this->pagado[$planillaId] = $resultado;
            $this->totalHaberBasico = array_sum($this->haberbasico);
            $this->totalBonos = array_sum($this->bonos);
            $this->totalSueldoHora = array_sum($this->sueldohora);
            $this->totalHorasDias = array_sum($this->horasdias);
            $this->totalDiasTrabajados = array_sum($this->diastrabajados);
            $this->totalHorasExtras = array_sum($this->horasextras);
            $this->totalAnticipo = array_sum($this->anticipo);
            $this->totalPagado = array_sum($this->pagado);
        }
    }

    public function render()
    {

        return view('livewire.planilla-editar');
    }
    public function guardarplanilla()
    {

        foreach ($this->planillas as $planilla) {
            $haberbasico = floatval($this->haberbasico[$planilla->id] ?? 0);
            $bonos = floatval($this->bonos[$planilla->id] ?? 0);
            $horasdias = floatval($this->horasdias[$planilla->id] ?? 0);
            $sueldohora = floatval($this->sueldohora[$planilla->id] ?? 0);
            $horasextras = floatval($this->horasextras[$planilla->id] ?? 0);
            $anticipo = floatval($this->anticipo[$planilla->id] ?? 0);
            $diastrabajados = floatval($this->diastrabajados[$planilla->id] ?? 0);
            $multi = ($horasdias * $sueldohora);
            $resultado = $multi  + $bonos + $horasextras - $anticipo;
            $planilla->haberbasico = $haberbasico;
            $planilla->bonos = $bonos;
            $planilla->sueldohora = $sueldohora;
            $planilla->horasdias = $horasdias;
            $planilla->diastrabajados = $diastrabajados;
            $planilla->horasextras = $horasextras;
            $planilla->anticipo = $anticipo;
            $planilla->pagado = $resultado;
            $planilla->responsable = Auth::user()->name;
            $planilla->save();
        }
        $this->planilla->pagado = $this->totalPagado;
        $this->planilla->estado = $this->estado;
        $this->planilla->fecha = $this->fechapago;
        $this->planilla->semana = $this->semana;
        $this->planilla->save();
        $this->render();
        $this->emit('alert', '¡Planilla guardada');
    }
    public function actualizarplanilla()
    {
        $usuarios = User::where('estado', 'Activo')->where('rol', '!=', 'Cliente')->get();
        foreach ($usuarios as $usuario) {
            $verificar = PlanillaSueldo::where('idplanilla', $this->planilla->id)->where('idusuario', $usuario->id)->exists();
            if ($verificar) {
            } else {
                $sueldo = new PlanillaSueldo;
                $sueldo->idplanilla = $this->idplanilla;
                $sueldo->idusuario = $usuario->id;
                $sueldo->nombre = $usuario->name;
                $sueldo->fecha = $this->planilla->fecha;
                $sueldo->descripcion = "";
                $sueldo->idcargo = $usuario->rol;
                $sueldo->idsucursal = $usuario->sesionsucursal;
                $sueldo->sucursal = $usuario->sucursal;
                $sueldo->haberbasico = "0";
                $sueldo->sueldohora = "0";
                $sueldo->horasdias = "0";
                $sueldo->diastrabajados = "0";
                $sueldo->horasextras = "0";
                $sueldo->bonos = "0";
                $sueldo->anticipo = "0";
                $sueldo->pagado = "0";
                $sueldo->responsable = Auth::user()->name;
                $sueldo->save();
            }
        }
        $this->emit('alert', '¡Planilla recargada!');
    }
}
