<?php

namespace App\Http\Livewire\Estadistica;

use App\Models\Areas;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Twilio\Rest\Api\V2010\Account\Usage\Record\ThisMonthList;

class Resumen extends Component
{
    public $areas;
    public $fechaInicioMes;
    public $fechaActual;
    public $areaslist = [];
    public $sumaingresos = [];
    public $sumafarmacia = [];
    public $sumatotal = [];
    public $areasmes = [];
    public $diasDelMes = [];
    public $diasennumero = [];
    public $sumasucursales = [];
    public $sumaingresosdia = [];
    public $sumafarmaciadia = [];
    public $listaasistencia = [];
    public $agendadoslist = [];
    public $confirmadoslist = [];
    public $agendadoslistuser = [];
    public $confirmadoslistuser = [];
    public $usuarioslist = [];
    public $citasagendas = [];
    public $confirmadossucu = [];
    public function mount()
    {
        $this->fechaInicioMes = date("Y-m-01");
        $this->fechaActual = now()->format('Y-m-d');

        $this->areas = Areas::where('estado', 'Activo')->get();
        foreach ($this->areas as $area) {
            $registros = DB::table('registropagos')
                ->whereBetween('fecha', [$this->fechaInicioMes, $this->fechaActual])
                ->where('sucursal', $area->area)
                ->get();
            $suma = 0;
            foreach ($registros as $registro) {
                $suma += (float) $registro->monto;
            }

            $this->sumaingresos[] =  $suma;
        }
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

            $this->sumafarmacia[] =  $suma;
        }
        foreach ($this->areas as $area) {
            $registros = DB::table('registropagos')
                ->whereBetween('fecha', [$this->fechaInicioMes, $this->fechaActual])
                ->where('sucursal', $area->area)
                ->get();
            $suma = 0;
            foreach ($registros as $registro) {
                $suma += (float) $registro->monto;
            }
            $registros = DB::table('registroinventarios')
                ->whereBetween('fecha', [$this->fechaInicioMes, $this->fechaActual])
                ->where('sucursal', $area->area)
                ->where(function ($query) {
                    $query->where('motivo', 'compra')
                        ->orWhere('motivo', 'farmacia');
                })
                ->get();
            foreach ($registros as $registro) {
                $suma += (float) $registro->precio;
            }
            $this->areaslist[] = $area->area . ':' . $suma;
        }
        $mesSeleccionado = Carbon::now()->month;

        $this->diasDelMes = [];


        $numeroDias = Carbon::now()->daysInMonth;

        for ($dia = 1; $dia <= $numeroDias; $dia++) {
            $carbonDate = Carbon::create(null, $mesSeleccionado, $dia);
            $nombreDia = $carbonDate->locale('es')->dayName;
            $nombreMes = $carbonDate->locale('es')->monthName;
            $this->diasDelMes[] = "{$nombreDia} {$dia} de {$nombreMes}";
            $this->diasennumero[] = $carbonDate->format('Y-m-d');
        }
        foreach ($this->areas as $area) {
            $this->sumasucursales = [];
            foreach ($this->diasennumero as $dia) {
                $registros = DB::table('registropagos')
                    ->where('sucursal', $area->area)
                    ->where('fecha', $dia)
                    ->get();
                $suma = 0;
                foreach ($registros as $registro) {
                    $suma += (float) $registro->monto;
                }
                $registros = DB::table('registroinventarios')
                    ->where('sucursal', $area->area)
                    ->where('fecha', $dia)
                    ->where(function ($query) {
                        $query->where('motivo', 'compra')
                            ->orWhere('motivo', 'farmacia');
                    })
                    ->get();
                foreach ($registros as $registro) {
                    $suma += (float) $registro->precio;
                }
                $this->sumasucursales[] = $suma;
            }
            $this->areasmes[] = [
                'name' => $area->area,
                'data' =>  $this->sumasucursales
            ];
        }
        foreach ($this->diasennumero as $dia) {
            $registros = DB::table('registropagos')
                ->where('fecha', $dia)
                ->get();
            $suma = 0;
            foreach ($registros as $registro) {
                $suma += (float) $registro->monto;
            }


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
        $this->agendadoslist = [];
        $this->confirmadoslist = [];
        foreach ($this->diasennumero as $dia) {
            $agendados = DB::table('operativos')
                ->where('fecha', $dia)
                ->where('estado', 'Pendiente')
                ->count();
            $confirmados = DB::table('registropagos')
                ->where('fecha', $dia)
                ->distinct('idoperativo')
                ->count();
            $this->agendadoslist[] = $agendados;
            $this->confirmadoslist[] = $confirmados;
        }

        $users = User::where('estado', 'Activo')
            ->where('estado', 'Activo')
            ->where('rol', 'Recepcion')
            ->get();
        foreach ($users as $user) {
            $agendados = DB::table('operativos')
                ->whereBetween('fecha', [$this->fechaInicioMes, $this->fechaActual])
                ->where('responsable', $user->name)
                ->where('estado', 'Pendiente')
                ->count();
            $confirmados = DB::table('registropagos')
                ->where('responsable', $user->name)
                ->whereBetween('fecha', [$this->fechaInicioMes, $this->fechaActual])
                ->distinct('idoperativo')
                ->count();
            $cantidadCoincidencias = DB::table('operativos')
                ->join('calls', 'operativos.idllamada', '=', 'calls.id')->where('calls.responsable', $user->name)
                ->whereBetween('calls.fecha', [$this->fechaInicioMes, $this->fechaActual])
                ->join('registropagos', 'operativos.id', '=', 'registropagos.idoperativo')
                ->count();
            $this->agendadoslistuser[] = $agendados;
            $this->confirmadoslistuser[] = $confirmados + $cantidadCoincidencias;
        }

        foreach ($this->areas as $area) {
            $agendados = DB::table('operativos')
                ->where('area', $area->area)
                ->whereBetween('fecha', [$this->fechaInicioMes, $this->fechaActual])

                ->where('estado', 'Pendiente')
                ->count();
            $confirmados = DB::table('registropagos')
                ->where('sucursal', $area->area)

                ->whereBetween('fecha', [$this->fechaInicioMes, $this->fechaActual])
                ->distinct('idoperativo')
                ->count();
            $this->citasagendas[] = $agendados;
            $this->confirmadossucu[] = $confirmados;
        }
    }
    public function render()
    {
        $this->usuarioslist = User::where('estado', 'Activo')
            ->where('rol', 'Recepcion')
            ->get()
            ->pluck('name')
            ->map(function ($fullName) {
                $names = explode(' ', $fullName);
                return $names[0];
            })
            ->toArray();
        $this->areas = Areas::where('estado', 'Activo')->get();
        $this->areaslist = Areas::pluck('area')->toArray();
        return view('livewire.estadistica.resumen');
    }
}
