<?php

namespace App\Http\Livewire\Estadistica;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Areas;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Ingresodinamico extends Component
{
    public $areaslist;
    public $sumasucursales = [];
    public $sumagastos = [];
    public $fechaInicioMes;
    public $fechaActual;
    public $totalingreso = 0;
    public $totalegreso = 0;
    public function mount()
    {
        $this->fechaInicioMes = date("Y-m-01");
        $this->fechaActual = now()->format('Y-m-d');
    }
    public function actualizarGrafico()
    {
        $areas = Areas::all();
        $this->sumasucursales = [];
        $this->sumagastos = [];
        $this->totalingreso = 0;
        foreach ($areas as $area) {
            $registros = DB::table('registropagos')
                ->whereBetween('fecha', [$this->fechaInicioMes, $this->fechaActual])
                ->where('sucursal', $area->area)
                ->get();
            $suma = 0;
            foreach ($registros as $registro) {
                $suma += (float) $registro->monto;
            }
            $registros = DB::table('registroinventarios')->whereBetween('fecha', [$this->fechaInicioMes, $this->fechaActual])
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
                ->whereBetween('fechainicio', [$this->fechaInicioMes, $this->fechaActual])
                ->where('area', $area->area)
                ->get();
            $suma = 0;
            foreach ($registros as $registro) {
                $suma += (float) $registro->cantidad;
            }
            $this->sumagastos[] = $suma;
        }

        $this->areaslist = Areas::pluck('area')->toArray();
        $this->emit('alert', 'Grafico Actulizado');
        $this->emit('actualizarGrafico', [
            'labels' => $this->areaslist,
            'datasets' => [
                [
                    'label' => 'EFECTIVO',
                    'data' => $this->sumasucursales,
                    'backgroundColor' => 'rgba(0, 99, 132, 0.6)',
                    'borderColor' => 'rgba(0, 99, 132, 1)'
                ],
                [
                    'label' => 'GASTOS',
                    'data' => $this->sumagastos,

                    'backgroundColor' => 'rgba(99, 132, 0, 0.6)',
                    'borderColor' => 'rgba(99, 132, 0, 1)'
                ]
            ]
        ]);
    }

    public function render()
    {

        return view('livewire.estadistica.ingresodinamico');
    }
}