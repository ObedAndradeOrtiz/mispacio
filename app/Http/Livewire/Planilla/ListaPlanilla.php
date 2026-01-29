<?php

namespace App\Http\Livewire\Planilla;

use App\Models\Planilla;
use App\Models\PlanillaSueldo;
use App\Models\User;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ListaPlanilla extends Component
{
    public $fechaInicioMes;
    public $fechaActual;
    public $planillas;
    public function mount()
    {
        $this->fechaInicioMes = Carbon::now()->toDateString();
        $this->fechaActual = Carbon::now()->toDateString();
    }
    public function render()
    {
        $this->planillas = Planilla::whereBetween('fecha', [$this->fechaInicioMes, $this->fechaActual])->get();
        return view('livewire.planilla.lista-planilla');
    }
    public function crearPlanilla()
    {
        $nueva = new Planilla;
        $nueva->fecha = Carbon::now()->toDateString();
        $nueva->semana = "SIN SELECCIONAR";
        $nueva->trabajadores = User::where('estado', 'Activo')->where('rol', '!=', 'Cliente')->count();
        $nueva->haberbasico = "0";
        $nueva->horasdias = "0";
        $nueva->horasextras = "0";
        $nueva->bonos = "0";
        $nueva->anticipo = "0";
        $nueva->pagado = "0";
        $nueva->responsable = Auth::user()->name;
        $nueva->estado = "POR PAGAR";
        $nueva->save();

        $usuarios = User::where('estado', 'Activo')->where('rol', '!=', 'Cliente')->where('rol', '!=', 'TARJETAS')->where('rol', '!=', 'mbq')->get();
        foreach ($usuarios as $usuario) {
            $sueldo = new PlanillaSueldo;
            $sueldo->idplanilla = $nueva->id;
            $sueldo->idusuario = $usuario->id;
            $sueldo->nombre = $usuario->name;
            $sueldo->fecha = Carbon::now()->toDateString();
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
        $this->render();

        $this->emit('alert', '¡Nueva planilla disponible');
    }
}
