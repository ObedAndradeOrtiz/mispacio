<?php

namespace App\Http\Livewire\Estadistica;

use Livewire\Component;
use App\Models\Areas;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CitasSemanal extends Component
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
                $registros = DB::table('registropagos')
                    ->where('sucursal', $area->area)
                    ->where('fecha',  $dia)
                    ->distinct('idcliente')
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

        // $this->fechaActual = Carbon::now()->toDateString();
        // $areas = Areas::all();
        // foreach ($areas as $area) {
        //     $registros = DB::table('registropagos')
        //         ->where('sucursal', $area->area)
        //         ->where('fecha',  $lunes2)
        //         ->distinct('idcliente')
        //         ->count();
        //     $registros = $registros;
        //     $this->sumalunes[] = $registros;
        // }
        // foreach ($areas as $area) {
        //     $registros = DB::table('registropagos')
        //         ->where('sucursal', $area->area)
        //         ->where('fecha',  $martes)
        //         ->distinct('idcliente')
        //         ->count();

        //     $this->sumamartes[] = $registros;
        // }
        // foreach ($areas as $area) {
        //     $registros = DB::table('registropagos')
        //         ->where('sucursal', $area->area)
        //         ->where('fecha',  $miercoles)
        //         ->distinct('idcliente')
        //         ->count();
        //     $this->sumamiercoles[] = $registros;
        // }
        // foreach ($areas as $area) {
        //     $registros = DB::table('registropagos')
        //         ->where('sucursal', $area->area)
        //         ->where('fecha',  $jueves)
        //         ->distinct('idcliente')
        //         ->count();
        //     $this->sumajueves[] = $registros;
        // }
        // foreach ($areas as $area) {
        //     $registros = DB::table('registropagos')
        //         ->where('sucursal', $area->area)
        //         ->where('fecha',  $viernes)
        //         ->distinct('idcliente')
        //         ->count();
        //     $this->sumaviernes[] = $registros;
        // }
        // foreach ($areas as $area) {
        //     $registros = DB::table('registropagos')
        //         ->where('sucursal', $area->area)
        //         ->where('fecha',  $sabado)
        //         ->distinct('idcliente')
        //         ->count();
        //     $this->sumasabado[] = $registros;
        // }
        // foreach ($areas as $area) {
        //     $registros = DB::table('registropagos')
        //         ->where('sucursal', $area->area)
        //         ->where('fecha',  $domingo)
        //         ->distinct('idcliente')
        //         ->count();
        //     $this->sumadomingo[] = $registros;
        // }
    }
    public function render()
    {
        return view('livewire.estadistica.citas-semanal');
    }
}
