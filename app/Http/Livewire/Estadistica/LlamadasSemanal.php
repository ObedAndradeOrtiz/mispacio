<?php

namespace App\Http\Livewire\Estadistica;

use Livewire\Component;
use App\Models\Areas;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LlamadasSemanal extends Component
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
    public $dia;

    public function mount()
    {
        $fechainicial = Carbon::now();
        $users = DB::table("users")
            ->where(function ($query) {
                $query->where('rol', 'Recepcion')
                    ->orWhere('rol', 'CallCenter');
            })
            ->where('estado', 'Activo')
            ->get();

        foreach ($users as $user) {
            $nombreCompleto = $user->name;
            $partesNombre = explode(' ', $nombreCompleto);
            $primerNombre = $partesNombre[0];
            $this->areaslist[] = $primerNombre;
        }
        $lunes = $fechainicial->startOfWeek();
        $lunes2 = $lunes->toDateString();

        $martes = $lunes->copy()->addDays(1)->toDateString();
        $miercoles = $lunes->copy()->addDays(2)->toDateString();
        $jueves = $lunes->copy()->addDays(3)->toDateString();
        $viernes = $lunes->copy()->addDays(4)->toDateString();
        $sabado = $lunes->copy()->addDays(5)->toDateString();
        $domingo = $lunes->copy()->addDays(6)->toDateString();
        $this->fechaActual = Carbon::now()->toDateString();
        foreach ($users as $user) {
            $registros = DB::table('calls')
                ->where('fecha', $lunes2)
                ->where('responsable', $user->name)
                ->count();
            $this->sumalunes[] = $registros;
            $registros = DB::table('calls')
                ->where('fecha',  $martes)
                ->where('responsable', $user->name)
                ->count();
            $this->sumamartes[] = $registros;
            $registros = DB::table('calls')
                ->where('responsable', $user->name)
                ->where('fecha',  $miercoles)
                ->count();
            $this->sumamiercoles[] = $registros;
            $registros = DB::table('calls')
                ->where('responsable', $user->name)
                ->where('fecha',  $jueves)

                ->count();
            $this->sumajueves[] = $registros;
            $registros = DB::table('calls')
                ->where('responsable', $user->name)
                ->where('fecha',  $viernes)

                ->count();
            $this->sumaviernes[] = $registros;
            $registros = DB::table('calls')
                ->where('responsable', $user->name)
                ->where('fecha',  $sabado)

                ->count();
            $this->sumasabado[] = $registros;
            $registros = DB::table('calls')
                ->where('responsable', $user->name)
                ->where('fecha',  $domingo)

                ->count();
            $this->sumadomingo[] = $registros;
        }
    }
    public function render()
    {
        return view('livewire.estadistica.llamadas-semanal');
    }
}
