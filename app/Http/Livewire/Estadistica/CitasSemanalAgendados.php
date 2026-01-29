<?php

namespace App\Http\Livewire\Estadistica;

use Livewire\Component;
use App\Models\Areas;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CitasSemanalAgendados extends Component
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
    public $data = [];
    public $areasmes = [];
    public $areas;
    public $dias;

    public function mount()
    {
        $this->areas = Areas::where('estado', 'Activo')->get();
        $fechainicial = Carbon::now();
        $lunes = $fechainicial->startOfWeek();
        $lunes2 = $lunes->toDateString();
        $martes = $lunes->copy()->addDays(1)->toDateString();
        $miercoles = $lunes->copy()->addDays(2)->toDateString();
        $jueves = $lunes->copy()->addDays(3)->toDateString();
        $viernes = $lunes->copy()->addDays(4)->toDateString();
        $sabado = $lunes->copy()->addDays(5)->toDateString();
        $domingo = $lunes->copy()->addDays(6)->toDateString();
        $this->dias = [$lunes2, $martes, $miercoles, $jueves, $viernes, $sabado, $domingo];
        foreach ($this->areas as $area) {
            $this->sumasucursales = [];
            foreach ($this->dias as $dia) {
                $registros = DB::table('operativos')
                    ->where('fecha',  $dia)
                    ->where('area', $area->area)
                    ->distinct('idempresa')
                    ->count();
                $this->sumasucursales[] = $registros;
            }
            $this->areasmes[] = [
                'name' => $area->area,
                'data' =>  $this->sumasucursales
            ];
        }
        $this->dias = [];
        $this->dias = ['Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado', 'Domingo'];
    }
    public function render()
    {

        return view('livewire.estadistica.citas-semanal-agendados');
    }
}
