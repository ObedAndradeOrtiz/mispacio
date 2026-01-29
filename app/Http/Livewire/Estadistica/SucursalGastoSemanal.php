<?php

namespace App\Http\Livewire\Estadistica;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Areas;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SucursalGastoSemanal extends Component
{
    public $areaslist;
    public $sumasucursales = [];
    public $sumagastos = [];
    public $sumalunes = [];
    public $sumamartes = [];
    public $sumamiercoles = [];
    public $sumajueves = [];
    public $sumaviernes = [];
    public $sumasabado = [];
    public $sumadomingo = [];
    public $fechaInicioMes;
    public $fechaActual;

    public function mount()
    {
        $fechainicial = Carbon::now();
        $lunes = $fechainicial->startOfWeek();
        $lunes2 = $lunes->toDateString();
        $martes = $lunes->copy()->addDays(1)->toDateString();
        $miercoles = $lunes->copy()->addDays(2)->toDateString();
        $jueves = $lunes->copy()->addDays(3)->toDateString();
        $viernes = $lunes->copy()->addDays(4)->toDateString();
        $sabado = $lunes->copy()->addDays(5)->toDateString();
        $domingo = $lunes->copy()->addDays(6)->toDateString();
        $this->fechaActual = Carbon::now()->toDateString();
        $areas = Areas::all();
        foreach ($areas as $area) {
            $registros = DB::table('gastos')
                ->where('area', $area->area)
                ->where('fechainicio',  $lunes)
                ->get();

            $suma = 0;
            foreach ($registros as $registro) {
                $suma += (float) $registro->cantidad;
            }
            $this->sumalunes[] = $suma;
        }
        foreach ($areas as $area) {
            $registros = DB::table('gastos')
                ->where('area', $area->area)
                ->where('fechainicio',  $martes)
                ->get();
            $suma = 0;
            foreach ($registros as $registro) {
                $suma += (float) $registro->cantidad;
            }
            $this->sumamartes[] = $suma;
        }
        foreach ($areas as $area) {
            $registros = DB::table('gastos')
                ->where('area', $area->area)
                ->where('fechainicio',  $miercoles)
                ->get();
            $suma = 0;
            foreach ($registros as $registro) {
                $suma += (float) $registro->cantidad;
            }
            $this->sumamiercoles[] = $suma;
        }
        foreach ($areas as $area) {
            $registros = DB::table('gastos')
                ->where('area', $area->area)
                ->where('fechainicio',  $jueves)
                ->get();
            $suma = 0;
            foreach ($registros as $registro) {
                $suma += (float) $registro->cantidad;
            }
            $this->sumajueves[] = $suma;
        }
        foreach ($areas as $area) {
            $registros = DB::table('gastos')
                ->where('area', $area->area)
                ->where('fechainicio',  $viernes)
                ->get();
            $suma = 0;
            foreach ($registros as $registro) {
                $suma += (float) $registro->cantidad;
            }
            $this->sumaviernes[] = $suma;
        }
        foreach ($areas as $area) {
            $registros = DB::table('gastos')
                ->where('area', $area->area)
                ->where('fechainicio',  $sabado)
                ->get();
            $suma = 0;
            foreach ($registros as $registro) {
                $suma += (float) $registro->cantidad;
            }
            $this->sumasabado[] = $suma;
        }
        foreach ($areas as $area) {
            $registros = DB::table('gastos')
                ->where('area', $area->area)
                ->where('fechainicio',  $domingo)
                ->get();
            $suma = 0;
            foreach ($registros as $registro) {
                $suma += (float) $registro->cantidad;
            }
            $this->sumadomingo[] = $suma;
        }
    }
    public function render()
    {
        $this->areaslist = Areas::pluck('area')->toArray();
        return view('livewire.estadistica.sucursal-gasto-semanal');
    }
}
