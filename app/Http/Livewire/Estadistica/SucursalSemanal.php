<?php

namespace App\Http\Livewire\Estadistica;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Areas;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SucursalSemanal extends Component
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
        $lunes = $fechainicial->startOfWeek(); // startOfWeek() establece la fecha al lunes de la semana actual
        $lunes2 = $lunes->toDateString();
        // Obtener la fecha del martes, miércoles, jueves, viernes, sábado y domingo
        $martes = $lunes->copy()->addDays(1)->toDateString();
        $miercoles = $lunes->copy()->addDays(2)->toDateString();
        $jueves = $lunes->copy()->addDays(3)->toDateString();
        $viernes = $lunes->copy()->addDays(4)->toDateString();
        $sabado = $lunes->copy()->addDays(5)->toDateString();
        $domingo = $lunes->copy()->addDays(6)->toDateString();

        $this->fechaActual = Carbon::now()->toDateString();
        $areas = Areas::all();
        foreach ($areas as $area) {
            $registros = DB::table('registropagos')
                ->where('sucursal', $area->area)
                ->where('fecha',  $lunes2)
                ->get();
            $suma = 0;
            foreach ($registros as $registro) {
                $suma += (float) $registro->monto;
            }
            $registros = DB::table('registroinventarios')
                ->where('fecha',  $lunes2)
                ->where(function ($query) {
                    $query->where('motivo', 'compra')
                        ->orWhere('motivo', 'farmacia');
                })
                ->where('sucursal',  $area->area)
                ->get();
            foreach ($registros as $registro) {
                $suma += (float) $registro->precio;
            }
            $this->sumalunes[] = $suma;
        }
        foreach ($areas as $area) {
            $registros = DB::table('registropagos')
                ->where('sucursal', $area->area)
                ->where('fecha',  $martes)
                ->get();
            $suma = 0;
            foreach ($registros as $registro) {
                $suma += (float) $registro->monto;
            }
            $registros = DB::table('registroinventarios')
                ->where('fecha',  $martes)
                ->where(function ($query) {
                    $query->where('motivo', 'compra')
                        ->orWhere('motivo', 'farmacia');
                })
                ->where('sucursal',  $area->area)
                ->get();
            foreach ($registros as $registro) {
                $suma += (float) $registro->precio;
            }
            $this->sumamartes[] = $suma;
        }
        foreach ($areas as $area) {
            $registros = DB::table('registropagos')
                ->where('sucursal', $area->area)
                ->where('fecha',  $miercoles)
                ->get();
            $suma = 0;
            foreach ($registros as $registro) {
                $suma += (float) $registro->monto;
            }
            $registros = DB::table('registroinventarios')
                ->where('fecha',  $miercoles)
                ->where(function ($query) {
                    $query->where('motivo', 'compra')
                        ->orWhere('motivo', 'farmacia');
                })
                ->where('sucursal',  $area->area)
                ->get();
            foreach ($registros as $registro) {
                $suma += (float) $registro->precio;
            }
            $this->sumamiercoles[] = $suma;
        }
        foreach ($areas as $area) {
            $registros = DB::table('registropagos')
                ->where('sucursal', $area->area)
                ->where('fecha',  $jueves)
                ->get();
            $suma = 0;
            foreach ($registros as $registro) {
                $suma += (float) $registro->monto;
            }
            $registros = DB::table('registroinventarios')
                ->where('fecha',  $jueves)
                ->where(function ($query) {
                    $query->where('motivo', 'compra')
                        ->orWhere('motivo', 'farmacia');
                })
                ->where('sucursal',  $area->area)
                ->get();
            foreach ($registros as $registro) {
                $suma += (float) $registro->precio;
            }
            $this->sumajueves[] = $suma;
        }
        foreach ($areas as $area) {
            $registros = DB::table('registropagos')
                ->where('sucursal', $area->area)
                ->where('fecha',  $viernes)
                ->get();
            $suma = 0;
            foreach ($registros as $registro) {
                $suma += (float) $registro->monto;
            }
            $registros = DB::table('registroinventarios')
                ->where('fecha',  $viernes)
                ->where(function ($query) {
                    $query->where('motivo', 'compra')
                        ->orWhere('motivo', 'farmacia');
                })
                ->where('sucursal',  $area->area)
                ->get();
            foreach ($registros as $registro) {
                $suma += (float) $registro->precio;
            }
            $this->sumaviernes[] = $suma;
        }
        foreach ($areas as $area) {
            $registros = DB::table('registropagos')
                ->where('sucursal', $area->area)
                ->where('fecha',  $sabado)
                ->get();
            $suma = 0;
            foreach ($registros as $registro) {
                $suma += (float) $registro->monto;
            }
            $registros = DB::table('registroinventarios')
                ->where('fecha',  $sabado)
                ->where(function ($query) {
                    $query->where('motivo', 'compra')
                        ->orWhere('motivo', 'farmacia');
                })
                ->where('sucursal',  $area->area)
                ->get();
            foreach ($registros as $registro) {
                $suma += (float) $registro->precio;
            }
            $this->sumasabado[] = $suma;
        }
        foreach ($areas as $area) {
            $registros = DB::table('registropagos')
                ->where('sucursal', $area->area)
                ->where('fecha',  $domingo)
                ->get();
            $suma = 0;
            foreach ($registros as $registro) {
                $suma += (float) $registro->monto;
            }
            $registros = DB::table('registroinventarios')
                ->where('fecha',  $domingo)
                ->where(function ($query) {
                    $query->where('motivo', 'compra')
                        ->orWhere('motivo', 'farmacia');
                })
                ->where('sucursal',  $area->area)
                ->get();
            foreach ($registros as $registro) {
                $suma += (float) $registro->precio;
            }
            $this->sumadomingo[] = $suma;
        }
    }
    public function render()
    {
        $this->areaslist = Areas::pluck('area')->toArray();

        return view('livewire.estadistica.sucursal-semanal');
    }
}
