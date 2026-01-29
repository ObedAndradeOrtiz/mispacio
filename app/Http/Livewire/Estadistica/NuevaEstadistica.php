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

class NuevaEstadistica extends Component
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
    public $año;
    public function mount()
    {
        $this->fechaDesde = Auth::user()->desde;
        $this->fechaHasta = Auth::user()->hasta;
        $this->año        = Auth::user()->ano ?? date('Y');
        $this->fechaDesde = Auth::user()->desde;
        $this->fechaHasta = Auth::user()->hasta;
        $this->año        = Auth::user()->ano ?? date('Y');
        if ($this->fechaDesde && $this->fechaHasta) {
            $this->fechaInicioMes = $this->fechaDesde;
            $this->fechaActual   = $this->fechaHasta;
        } else {
            $this->fechaInicioMes = $this->año . '-01-01';
            $this->fechaActual   = $this->año . '-12-31';
        }
        $this->areas = Areas::where('area', 'NOT ILIKE', '%ALMACEN%')->where('estado', 'Activo')->get();
        foreach (
            $this->areas->filter(function ($area) {
                return stripos($area->area, 'ALMACEN') === false;
            }) as $area
        ) {
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
        foreach (
            $this->areas->filter(function ($area) {
                return stripos($area->area, 'ALMACEN') === false;
            }) as $area
        ) {
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
        foreach (
            $this->areas->filter(function ($area) {
                return stripos($area->area, 'ALMACEN') === false;
            }) as $area
        ) {
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
        // $mesSeleccionado = Carbon::now()->month;
        // $this->diasDelMes = [];
        // $numeroDias = Carbon::now()->daysInMonth;

        $mesSeleccionado =  Carbon::parse($this->fechaDesde)->month; // Mes anterior
        $this->diasDelMes = [];

        $numeroDias = Carbon::parse($this->fechaHasta)->daysInMonth;

        for ($dia = 1; $dia <= $numeroDias; $dia++) {
            $carbonDate = Carbon::create($this->año, $mesSeleccionado, $dia);
            $nombreDia = $carbonDate->locale('es')->dayName;
            $nombreMes = $carbonDate->locale('es')->monthName;
            $this->diasDelMes[] = "{$nombreDia} {$dia} de {$nombreMes}";
            $this->diasennumero[] = $carbonDate->format('Y-m-d');
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
        $this->obtenerTotalesPorMes();
    }
    public function actualizarFecha()
    {
        $user = User::find(Auth::user()->id);
        $user->desde = $this->fechaDesde;
        $user->hasta = $this->fechaHasta;
        $user->ano = $this->año;
        $user->save();
        return redirect(request()->header('Referer'));
    }
    public function obtenerTotalesPorMes()
    {
        $this->meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        // Consulta para registropagos
        $resultadosPagos = DB::table('registropagos')
            ->select(DB::raw('EXTRACT(MONTH FROM TO_DATE(fecha, \'YYYY-MM-DD\')) as mes'), DB::raw('SUM(CAST(monto AS DECIMAL(10, 2))) as total_monto'))
            ->where('fecha', '>', $this->año . '-01-01')
            ->where('fecha', '<', $this->año . '-12-31')
            ->groupBy(DB::raw('EXTRACT(MONTH FROM TO_DATE(fecha, \'YYYY-MM-DD\'))'))
            ->orderBy(DB::raw('EXTRACT(MONTH FROM TO_DATE(fecha, \'YYYY-MM-DD\'))'))
            ->get();

        // Consulta para inventarios
        $resultadosInventarios = DB::table('registroinventarios')
            ->select(DB::raw('EXTRACT(MONTH FROM TO_DATE(fecha, \'YYYY-MM-DD\')) as mes'), DB::raw('SUM(CAST(precio AS DECIMAL(10, 2))) as total_precio'))
            ->whereIn('motivo', ['compra', 'farmacia'])
            ->where('fecha', '>', $this->año . '-01-01')
            ->where('fecha', '<', $this->año . '-12-31')
            ->groupBy(DB::raw('EXTRACT(MONTH FROM TO_DATE(fecha, \'YYYY-MM-DD\'))'))
            ->orderBy(DB::raw('EXTRACT(MONTH FROM TO_DATE(fecha, \'YYYY-MM-DD\'))'))
            ->get();

        // Inicializar un array para almacenar los totales por mes
        $totalesPorMes = array_fill(0, 12, 0);

        // Procesar resultados de registropagos
        foreach ($resultadosPagos as $resultado) {
            $indiceMes = $resultado->mes - 1;
            $totalesPorMes[$indiceMes] += $resultado->total_monto;
        }

        // Procesar resultados de inventarios
        foreach ($resultadosInventarios as $resultado) {
            $indiceMes = $resultado->mes - 1;
            $totalesPorMes[$indiceMes] += $resultado->total_precio;
        }

        // Asignar los totales por mes a la propiedad de la clase (o como lo necesites)
        $this->totalesPorMes = $totalesPorMes;


        // Imprimir el array de totales por mes
    }
    public function render()
    {
        $this->areas = Areas::where('estado', 'Activo')->where('area', 'NOT ILIKE', '%ALMACEN%')->get();
        return view('livewire.estadistica.nueva-estadistica');
    }
    public function cambiarAccion($accion)
    {
        $user = User::find(Auth::user()->id);
        $user->accion = $accion;
        $user->save();
        $this->emit('alert', '¡Cambiando de estadistica!');
        $this->emit('actualizarVista');
    }
}
