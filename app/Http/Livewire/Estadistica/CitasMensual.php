<?php

namespace App\Http\Livewire\Estadistica;

use Livewire\Component;
use App\Models\Areas;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CitasMensual extends Component
{
    public $areaslist;
    public $sumasucursales = [];
    public $suma1 = [];
    public $suma2 = [];
    public $suma3 = [];
    public $suma4 = [];
    public $suma5 = [];
    public $sumalunes = [];
    public $sumamartes = [];
    public $sumamiercoles = [];
    public $sumajueves = [];
    public $sumaviernes = [];
    public $sumasabado = [];
    public $sumadomingo = [];
    public $fechaInicioMes;
    public $fechaActual;
    public $inicioSemana1;
    public $inicioSemana2;
    public $inicioSemana3;
    public $inicioSemana4;
    public $inicioSemana5;

    public $finSemana1;
    public $finSemana2;
    public $finSemana3;
    public $finSemana4;
    public $finSemana5;


    public function mount()
    {
        $inicioMes = Carbon::now()->startOfMonth();

        $inicioSemana1 = $inicioMes;
        $finSemana1 = $inicioSemana1->copy()->endOfWeek();

        $inicioSemana2 = $finSemana1->copy()->addDay();
        $finSemana2 = $inicioSemana2->copy()->endOfWeek();

        $inicioSemana3 = $finSemana2->copy()->addDay();
        $finSemana3 = $inicioSemana3->copy()->endOfWeek();

        $inicioSemana4 = $finSemana3->copy()->addDay();
        $finSemana4 = $inicioSemana4->copy()->endOfWeek();
        $inicioSemana5 = $finSemana4->copy()->addDay();
        $finSemana5 = $inicioSemana5->copy()->endOfWeek();
        $this->inicioSemana1 = $inicioSemana1->toDateString();
        $this->inicioSemana2 = $inicioSemana2->toDateString();
        $this->inicioSemana3 = $inicioSemana3->toDateString();
        $this->inicioSemana4 = $inicioSemana4->toDateString();
        $this->inicioSemana5 = $inicioSemana5->toDateString();

        $this->finSemana1 = $finSemana1->toDateString();
        $this->finSemana2 = $finSemana2->toDateString();
        $this->finSemana3 = $finSemana3->toDateString();
        $this->finSemana4 = $finSemana4->toDateString();
        $this->finSemana5 = $finSemana5->toDateString();

        $inicioSemana1 = $inicioSemana1->toDateString();

        $inicioSemana2 = $inicioSemana2->toDateString();
        $inicioSemana3 = $inicioSemana3->toDateString();
        $inicioSemana4 = $inicioSemana4->toDateString();
        $inicioSemana5 = $inicioSemana5->toDateString();

        $finSemana1 = $finSemana1->toDateString();
        $finSemana2 = $finSemana2->toDateString();
        $finSemana3 = $finSemana3->toDateString();
        $finSemana4 = $finSemana4->toDateString();
        $finSemana5 = $finSemana5->toDateString();



        $this->fechaActual = Carbon::now()->toDateString();
        $areas = Areas::all();
        foreach ($areas as $area) {
            $registros = DB::table('registropagos')
                ->where('sucursal', $area->area)
                ->whereBetween('fecha', [$inicioSemana1,  $finSemana1])
                ->distinct('idcliente')
                ->count();
            $this->suma1[] = $registros;
        }
        foreach ($areas as $area) {
            $registros = DB::table('registropagos')
                ->where('sucursal', $area->area)
                ->whereBetween('fecha', [$inicioSemana2,  $finSemana2])
                ->distinct('idcliente')
                ->count();

            $this->suma2[] = $registros;
        }
        foreach ($areas as $area) {
            $registros = DB::table('registropagos')
                ->where('sucursal', $area->area)
                ->whereBetween('fecha', [$inicioSemana3,  $finSemana3])
                ->distinct('idcliente')
                ->count();

            $this->suma3[] =  $registros;
        }
        foreach ($areas as $area) {
            $registros = DB::table('registropagos')
                ->where('sucursal', $area->area)
                ->whereBetween('fecha', [$inicioSemana4,  $finSemana4])
                ->distinct('idcliente')
                ->count();

            $this->suma4[] =  $registros;
        }
        foreach ($areas as $area) {
            $registros = DB::table('registropagos')
                ->where('sucursal', $area->area)
                ->whereBetween('fecha', [$inicioSemana5,  $finSemana5])
                ->distinct('idcliente')
                ->count();

            $this->suma5[] =  $registros;
        }
    }
    public function render()
    {
        $this->areaslist = Areas::pluck('area')->toArray();
        return view('livewire.estadistica.citas-mensual');
    }
}
