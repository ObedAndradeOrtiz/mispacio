<?php

namespace App\Http\Livewire\Estadistica;

use App\Models\Areas;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class IngresosVentaMes extends Component
{
    public $sumafarmacia = [];
    public $areas;
    public $diasDelMes;
    public $fechaDesde;
    public $fechaHasta;
    public $diasennumero;
    public $fechaInicioMes;
    public $fechaActual;
    public $areaslist;
    public $sumaingresosdia;
    public function render()
    {
        $this->diasDelMes = [];
        $this->fechaDesde = Auth::user()->desde;
        $this->fechaHasta = Auth::user()->hasta;
        if ($this->fechaDesde != null && $this->fechaHasta != null) {
            $this->fechaInicioMes = $this->fechaDesde;;
            $this->fechaActual = $this->fechaHasta;
        } else {
            $this->fechaInicioMes = date("Y-m-01");
            $this->fechaActual = date("Y-m-d");
        }
        $mesSeleccionado =  Carbon::parse($this->fechaDesde)->month; // Mes anterior
        $numeroDias = Carbon::parse($this->fechaHasta)->daysInMonth;
        for ($dia = 1; $dia <= $numeroDias; $dia++) {
            $carbonDate = Carbon::create(2025, $mesSeleccionado, $dia);
            $nombreDia = $carbonDate->locale('es')->dayName;
            $nombreMes = $carbonDate->locale('es')->monthName;
            $this->diasDelMes[] = "{$nombreDia} {$dia} de {$nombreMes}";
            $this->diasennumero[] = $carbonDate->format('Y-m-d');
        }
        $this->areas = Areas::where('estado', 'Activo')->where('area', '!=', 'ALMACEN CENTRAL')->get();
        foreach ($this->areas as $area) {
            $registros = DB::table('registroinventarios')
                ->whereBetween('fecha', [$this->fechaInicioMes, $this->fechaActual])
                ->where('sucursal', $area->area)
                ->where(function ($query) {
                    $query->where('motivo', 'compra')
                        ->orWhere('motivo', 'farmacia');
                })
                ->get();
            $suma = 0;
            foreach ($registros as $registro) {
                $suma += (float) $registro->precio;
            }
            $this->areaslist[] = $area->area . ':' . $suma;
            $this->sumafarmacia[] =  $suma;
        }
        foreach ($this->diasennumero as $dia) {
            $suma = 0;
            $registros = DB::table('registroinventarios')
                ->where('fecha', $dia)
                ->where(function ($query) {
                    $query->where('motivo', 'compra')
                        ->orWhere('motivo', 'farmacia');
                })
                ->get();
            foreach ($registros as $registro) {
                $suma += (float) $registro->precio;
            }
            $this->sumaingresosdia[] = $suma;
        }
        for ($dia = 1; $dia <= $numeroDias; $dia++) {
            $carbonDate = Carbon::create(2025, $mesSeleccionado, $dia);
            $nombreDia = $carbonDate->locale('es')->dayName;
            $nombreMes = $carbonDate->locale('es')->monthName;
            $this->diasDelMes[] = "{$nombreDia} {$dia} de {$nombreMes}";
            $this->diasennumero[] = $carbonDate->format('Y-m-d');
        }
        return view('livewire.estadistica.ingresos-venta-mes');
    }
}
