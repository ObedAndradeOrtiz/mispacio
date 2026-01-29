<?php

namespace App\Http\Livewire\Estadistica;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LlamadasSemanalAsistidos extends Component
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
            $registros = DB::table('operativos')
                ->join('calls', 'operativos.idllamada', '=', 'calls.id')->where('calls.responsable', $user->name)
                ->where('calls.fecha', $lunes2)
                ->join('registropagos', 'operativos.id', '=', 'registropagos.idoperativo')
                ->count();
            $this->sumalunes[] = $registros;
        }
        foreach ($users as $user) {
            $registros = DB::table('operativos')
                ->join('calls', 'operativos.idllamada', '=', 'calls.id')->where('calls.responsable', $user->name)
                ->where('calls.fecha', $martes)
                ->join('registropagos', 'operativos.id', '=', 'registropagos.idoperativo')
                ->count();

            $this->sumamartes[] = $registros;
        }
        foreach ($users as $user) {
            $registros = DB::table('operativos')
                ->join('calls', 'operativos.idllamada', '=', 'calls.id')->where('calls.responsable', $user->name)
                ->where('calls.fecha', $miercoles)
                ->join('registropagos', 'operativos.id', '=', 'registropagos.idoperativo')
                ->count();
            $this->sumamiercoles[] = $registros;
        }
        foreach ($users as $user) {
            $registros = DB::table('operativos')
                ->join('calls', 'operativos.idllamada', '=', 'calls.id')->where('calls.responsable', $user->name)
                ->where('calls.fecha', $jueves)
                ->join('registropagos', 'operativos.id', '=', 'registropagos.idoperativo')
                ->count();
            $this->sumajueves[] = $registros;
        }
        foreach ($users as $user) {
            $registros = DB::table('operativos')
                ->join('calls', 'operativos.idllamada', '=', 'calls.id')->where('calls.responsable', $user->name)
                ->where('calls.fecha', $viernes)
                ->join('registropagos', 'operativos.id', '=', 'registropagos.idoperativo')
                ->count();
            $this->sumaviernes[] = $registros;
        }
        foreach ($users as $user) {
            $registros = DB::table('operativos')
                ->join('calls', 'operativos.idllamada', '=', 'calls.id')->where('calls.responsable', $user->name)
                ->where('calls.fecha', $sabado)
                ->join('registropagos', 'operativos.id', '=', 'registropagos.idoperativo')
                ->count();
            $this->sumasabado[] = $registros;
        }
        foreach ($users as $user) {
            $registros = DB::table('operativos')
                ->join('calls', 'operativos.idllamada', '=', 'calls.id')->where('calls.responsable', $user->name)
                ->where('calls.fecha', $domingo)
                ->join('registropagos', 'operativos.id', '=', 'registropagos.idoperativo')
                ->count();
            $this->sumadomingo[] = $registros;
        }
    }
    public function render()
    {
        return view('livewire.estadistica.llamadas-semanal-asistidos');
    }
}
