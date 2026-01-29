<?php

namespace App\Http\Livewire\Estadistica;


use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Areas;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CitasDiario extends Component
{
    public $areaslist;
    public $sumasucursales = [];
    public $sumagastos = [];
    public $fechaInicioMes;
    public $fechaActual;
    public function mount()
    {
        $this->fechaActual = Carbon::now()->toDateString();
        $areas = Areas::all();
        foreach ($areas as $area) {
            $registros = DB::table('operativos')
                ->where('fecha',  $this->fechaActual)
                ->where('area', $area->area)
                ->distinct('idempresa')
                ->count();

            $this->sumasucursales[] =  $registros;
        }
        foreach ($areas as $area) {
            $registros = DB::table('registropagos')
                ->where('fecha', $this->fechaActual)
                ->where('sucursal', $area->area)
                ->distinct('idcliente')
                ->count();

            $this->sumagastos[] =  $registros;
        }
    }
    public function render()
    {
        $this->areaslist = Areas::pluck('area')->toArray();
        return view('livewire.estadistica.citas-diario');
    }
}