<?php

namespace App\Http\Livewire\Estadistica;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LlamadasDiario extends Component
{
    public $areaslist;
    public $sumasucursales = [];
    public $sumagastos = [];
    public $fechaInicioMes;
    public $fechaActual;
    public $llamadas = [];
    public $agendados = [];
    public $asistidos = [];
    public $remarketing = [];
    public $mesllamadas = [];
    public $mesagendados = [];
    public $mesasistidos = [];
    public $mesremarketing = [];
    public $fechaFinMes;
    public function mount()
    {
        $this->fechaActual = Carbon::now()->toDateString();
        $this->fechaInicioMes = date("Y-m-01");
        $this->fechaFinMes = date("Y-m-t");
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
            $registros = DB::table('calls')
                ->where('fecha',  $this->fechaActual)
                ->where('responsable', $user->name)
                ->count();
            $this->llamadas[] =  $registros;
            $registros = DB::table('calls')
                ->where('fecha',  $this->fechaActual)
                ->where('responsable', $user->name)
                ->where('estado', 'Pendiente')
                ->count();
            $this->agendados[] =  $registros;
            $registros = DB::table('registrollamadas')
                ->where('fecha',  $this->fechaActual)
                ->where('responsable', $user->name)
                ->where('sucursal', '1')
                ->count();
            $this->remarketing[] =  $registros;
            $cantidadCoincidencias = DB::table('operativos')
                ->join('calls', 'operativos.idllamada', '=', 'calls.id')->where('calls.responsable', $user->name)
                ->where('calls.fecha', $this->fechaActual)
                ->join('registropagos', 'operativos.id', '=', 'registropagos.idoperativo')
                ->count();
            $this->asistidos[] = $cantidadCoincidencias;
            $registros = DB::table('calls')
                ->whereBetween('fecha', [$this->fechaInicioMes, $this->fechaActual])
                ->where('responsable', $user->name)
                ->count();
            $this->mesllamadas[] =  $registros;
            $registros = DB::table('calls')
                ->whereBetween('fecha', [$this->fechaInicioMes, $this->fechaActual])
                ->where('responsable', $user->name)
                ->where('estado', 'Pendiente')
                ->count();
            $this->mesagendados[] =  $registros;
            $registros = DB::table('registrollamadas')
                ->whereBetween('fecha', [$this->fechaInicioMes, $this->fechaFinMes])
                ->where('responsable', $user->name)
                ->where('sucursal', '1')
                ->count();
            $this->mesremarketing[] =  $registros;
            $cantidadCoincidencias = DB::table('calls')
                ->where('calls.responsable', $user->name)
                ->whereBetween('calls.fecha', [$this->fechaInicioMes, $this->fechaActual])
                ->join('operativos', 'operativos.idllamada', '=', 'calls.id')
                ->join('registropagos', 'operativos.id', '=', 'registropagos.idoperativo')
                ->orderBy('calls.id', 'desc')
                ->count();
            $this->mesasistidos[] = $cantidadCoincidencias;
        }
    }
    public function render()
    {

        return view('livewire.estadistica.llamadas-diario');
    }
}
