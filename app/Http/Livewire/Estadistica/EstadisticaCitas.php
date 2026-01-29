<?php

namespace App\Http\Livewire\Estadistica;

use App\Models\Calls;
use App\Models\Operativos;
use App\Models\Empresas;
use App\Models\Pagos;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Areas;
use App\Models\registropago;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EstadisticaCitas extends Component
{
    use WithPagination;
    public $botonRecepcion;
    public  $total_si_pagado = 0;
    public $total_ingresado = 0;
    public $open = false;
    public $area;
    public $modo = "";
    public $telefono;
    public $busqueda = "";
    public $actividad = "Areas";
    public $tipos = "Sueldo";
    public $idempresa;
    public $verclienteslogic = false;
    public $seguridad = true;
    public $contra = "";
    public $activar = false;
    public $openAreaGasto = false;
    public $tipogasto;
    public $modopago;
    public $fechaInicioMes;
    public $fechaActual;
    public $control = 'general';
    public $seleccionmodo = 'Por fecha';
    public $empresaseleccionada = '';
    public $tipoingreso = 'ingresoexterno';
    protected $listeners = ['render' => 'render'];
    public $openAreaImage = false;
    public $rutaImagen;
    public $sucursal;
    public $consultaarea;
    public $areaslist;
    public  $sumasucursales = [];
    public $sumagastos = [];
    public $areas;
    public $sumaingresos = [];
    public $sumafarmacia = [];
    public $sumatotal = [];
    public $areasmes = [];
    public $diasDelMes = [];
    public $diasennumero = [];
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
    public $nombretratamientos = [];
    public $cantidades = [];
    public $nombreproductos = [];
    public $cantidadesproductos = [];
    public $totalesPorMes = [];
    public $meses;
    public $nombrevendedoras = [];
    public $cantidadvendido = [];
    public $nombremasobtenido = [];
    public $cantidadmasvendido = [];
    public $nombretratamientosganado = [];
    public $cantidadesganado = [];
    public $fechaDesde;
    public $fechaHasta;
    public $agendadosPorArea = [];
    public $confirmadosPorArea = [];
    public function mount()
    {
        $this->fechaDesde = Auth::user()->desde;
        $this->fechaHasta = Auth::user()->hasta;
        if ($this->fechaDesde != null && $this->fechaHasta != null) {
            $this->fechaInicioMes = $this->fechaDesde;;
            $this->fechaActual = $this->fechaHasta;
        } else {
            $this->fechaInicioMes = date("Y-m-01");
            $this->fechaActual = date("Y-m-d");
        }
        $this->botonRecepcion = "pagos";
        $this->nombretratamientos = [];
        $this->cantidades = [];
        $resultados = DB::table('historial_clientes')
            ->select('nombretratamiento', DB::raw('COUNT(DISTINCT idoperativo) as cantidad'))
            ->where('nombretratamiento', '!=', 'CONSULTA')
            ->whereBetween('fecha', [$this->fechaInicioMes, $this->fechaActual])
            ->groupBy('nombretratamiento')
            ->orderBy('cantidad', 'desc')

            ->get();
        // Separar los resultados en dos arrays
        foreach ($resultados as $resultado) {
            $this->nombretratamientos[] = $resultado->nombretratamiento;
            $this->cantidades[] = $resultado->cantidad;
        }
        $resultados = DB::table('historial_clientes')
            ->select('historial_clientes.nombretratamiento', DB::raw('SUM(CAST(registropagos.monto AS DECIMAL(10, 2))) as total_ingreso'))
            ->join('registropagos', DB::raw('CAST(historial_clientes.idoperativo AS INTEGER)'), '=', 'registropagos.idoperativo')
            ->whereBetween('historial_clientes.fecha', [$this->fechaInicioMes, $this->fechaActual])
            ->where('historial_clientes.nombretratamiento', '!=', 'CONSULTA')
            ->groupBy('historial_clientes.nombretratamiento')
            ->orderBy('total_ingreso', 'desc')
            ->get();
        foreach ($resultados as $resultado) {
            $this->nombretratamientosganado[] = $resultado->nombretratamiento;
            $this->cantidadesganado[] = $resultado->total_ingreso;
        }
        $this->areas = Areas::where('estado', 'Activo')->where('area', '!=', 'ALMACEN CENTRAL')->get();
        foreach ($this->areas as $area) {
            $this->areaslist[] = $area->area;
        }






        // if (Auth::user()->rol == 'Jefe Marketing y Publicidad') {
        //     $this->botonRecepcion = 'citas';
        // }






        // $mesSeleccionado = Carbon::now()->month;

        // $this->diasDelMes = [];


        // $numeroDias = Carbon::now()->daysInMonth;

        $mesSeleccionado = Carbon::parse($this->fechaDesde)->month; // Mes anterior
        $this->diasDelMes = [];

        $numeroDias = Carbon::parse($this->fechaHasta)->daysInMonth;

        for ($dia = 1; $dia <= $numeroDias; $dia++) {
            $carbonDate = Carbon::create(2025, $mesSeleccionado, $dia);
            $nombreDia = $carbonDate->locale('es')->dayName;
            $nombreMes = $carbonDate->locale('es')->monthName;
            $this->diasDelMes[] = "{$nombreDia} {$dia} de {$nombreMes}";
            $this->diasennumero[] = $carbonDate->format('Y-m-d');
        }


        $this->agendadoslist = [];
        $this->confirmadoslist = [];
        foreach ($this->diasennumero as $dia) {
            $agendados = DB::table('operativos')
                ->where('fecha', $dia)

                ->count();
            $confirmados = DB::table('registropagos')
                ->where('fecha', $dia)
                ->distinct('idoperativo')
                ->count();
            $this->agendadoslist[] = $agendados;
            $this->confirmadoslist[] = $confirmados;
        }
        $this->agendadosPorArea = [];
        $this->confirmadosPorArea = [];

        foreach ($this->areas as $area) {
            $agendados = [];
            $confirmados = [];

            foreach ($this->diasennumero as $dia) {
                $agendadosDia = DB::table('operativos')
                    ->where('area', $area->area) // o $area->nombre según tu estructura
                    ->where('fecha', $dia)
                    ->count();

                $confirmadosDia = DB::table('registropagos')
                    ->where('sucursal', $area->area)
                    ->where('fecha', $dia)
                    ->distinct('idoperativo')
                    ->count();

                $agendados[] = $agendadosDia;
                $confirmados[] = $confirmadosDia;
            }

            $this->agendadosPorArea[$area->area] = $agendados;
            $this->confirmadosPorArea[$area->area] = $confirmados;
        }
        $users = User::where('estado', 'Activo')
            ->where('estado', 'Activo')
            ->where('rol', 'Recepcion')
            ->get();
        foreach ($users as $user) {
            $agendados = DB::table('operativos')
                ->whereBetween('fecha', [$this->fechaInicioMes, $this->fechaActual])
                ->where('responsable', $user->name)

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
    public function actualizarFecha()
    {
        $user = User::find(Auth::user()->id);
        $user->desde = $this->fechaDesde;
        $user->hasta = $this->fechaHasta;
        $user->save();
        return redirect(request()->header('Referer'));
    }
    public function render()
    {
        $this->usuarioslist =
            User::where('estado', 'Activo')
            ->where('rol', 'Recepcion')
            ->get()
            ->pluck('name')
            ->map(function ($fullName) {
                $names = explode(' ', $fullName);
                return $names[0];
            })
            ->toArray();
        return view('livewire.estadistica.estadistica-citas');
    }
}
