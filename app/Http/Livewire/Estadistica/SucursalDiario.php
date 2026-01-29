<?php

namespace App\Http\Livewire\Estadistica;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Areas;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class SucursalDiario extends Component
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
            $registros = DB::table('registropagos')->where('fecha',  $this->fechaActual)
                ->where('sucursal', $area->area)
                ->get();
            $suma = 0;
            foreach ($registros as $registro) {
                $suma += (float) $registro->monto;
            }
            $registros = DB::table('registroinventarios')->where('fecha',  $this->fechaActual)
                ->where(function ($query) {
                    $query->where('motivo', 'compra')
                        ->orWhere('motivo', 'farmacia');
                })
                ->where('sucursal', $area->area)
                ->get();
            foreach ($registros as $registro) {
                $suma += (float) $registro->precio;
            }
            $this->sumasucursales[] = $suma;
        }
        foreach ($areas as $area) {
            $registros = DB::table('gastos')
                ->where('fechainicio', $this->fechaActual)
                ->where('area', $area->area)
                ->get();
            $suma = 0;
            foreach ($registros as $registro) {
                $suma += (float) $registro->cantidad;
            }
            $this->sumagastos[] = $suma;
        }
    }
    public function render()
    {
        $this->areaslist = Areas::pluck('area')->toArray();
        return view('livewire.estadistica.sucursal-diario');
    }
}