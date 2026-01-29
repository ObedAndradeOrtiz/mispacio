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

class EstadisticaVentas extends Component
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
    public $mes;
    public $opcion = 0;
    public $areas;

    public function obtenerNombreDelMes($numeroMes)
    {
        $nombresMeses = [
            1 => 'Enero',
            2 => 'Febrero',
            3 => 'Marzo',
            4 => 'Abril',
            5 => 'Mayo',
            6 => 'Junio',
            7 => 'Julio',
            8 => 'Agosto',
            9 => 'Septiembre',
            10 => 'Octubre',
            11 => 'Noviembre',
            12 => 'Diciembre',
        ];

        return $nombresMeses[(int) $numeroMes] ?? 'Mes inválido';
    }

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
        $this->areas = Areas::where('estado', 'Activo')->where('area', '!=', 'ALMACEN CENTRAL')->get();
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

            $suma = 0;

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
        $mesSeleccionado =  Carbon::parse($this->fechaDesde)->month; // Mes anterior
        $this->diasDelMes = [];
        $numeroDias = Carbon::parse($this->fechaHasta)->daysInMonth;
        for ($dia = 1; $dia <= $numeroDias; $dia++) {
            $carbonDate = Carbon::create(2025, $mesSeleccionado, $dia);
            $nombreDia = $carbonDate->locale('es')->dayName;
            $nombreMes = $carbonDate->locale('es')->monthName;
            $this->diasDelMes[] = "{$nombreDia} {$dia} de {$nombreMes}";
            $this->diasennumero[] = $carbonDate->format('Y-m-d');
        }
        foreach ($this->diasennumero as $dia) {
            $suma = 0;
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
        $this->fechaDesde = Auth::user()->desde;
        $this->fechaHasta = Auth::user()->hasta;
        if ($this->fechaDesde != null && $this->fechaHasta != null) {
            $this->fechaInicioMes = $this->fechaDesde;;
            $this->fechaActual = $this->fechaHasta;
            $this->mes = $this->obtenerNombreDelMes(Carbon::parse($this->fechaDesde)->month);
        } else {
            $this->fechaInicioMes = date("Y-m-01");
            $this->fechaActual = date("Y-m-d");
            $this->mes = $this->obtenerNombreDelMes(Carbon::parse($this->fechaInicioMes)->month);
        }

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
        $resultados = DB::table('users as u')
            ->select('u.id as id_usuario', 'u.name as nombre_usuario', DB::raw('SUM(CAST(r.precio AS DECIMAL(10, 2))) as total_ingreso'))
            ->join('registroinventarios as r', 'u.id', '=', 'r.iduser')
            ->where('u.rol', '!=', 'Cliente')
            ->whereIn('r.motivo', ['compra', 'farmacia'])
            // ->where('u.estado', 'Activo')
            ->whereBetween('r.fecha', [$this->fechaInicioMes, $this->fechaActual])
            ->groupBy('u.id', 'u.name')
            ->orderBy('total_ingreso', 'desc')

            ->get();
        foreach ($resultados as $resultado) {
            $this->nombremasobtenido[] = $resultado->nombre_usuario;
            $this->cantidadmasvendido[] = $resultado->total_ingreso;
        }

        $this->nombreproductos = [];
        $this->cantidadesproductos = [];
        $resultados = DB::table('registroinventarios')
            ->select('nombreproducto', DB::raw('COUNT(*) as cantidad'))
            ->whereIn('motivo', ['compra', 'farmacia'])
            ->whereBetween('fecha', [$this->fechaInicioMes, $this->fechaActual])
            ->groupBy('nombreproducto')
            ->orderByDesc('cantidad')
            ->limit(20)
            ->get();
        foreach ($resultados as $resultado) {
            $this->nombreproductos[] = $resultado->nombreproducto;
            $this->cantidadesproductos[] = $resultado->cantidad;
        }
        $this->nombrevendedoras = [];
        $this->cantidadvendido = [];
        $resultados = DB::table('users as u')
            ->select('u.id as id_usuario', 'u.name as nombre_usuario', DB::raw('COUNT(r.ID) as total_registros'))
            ->join('registroinventarios as r', 'u.id', '=', 'r.iduser')
            ->where('u.rol', '!=', 'Cliente')
            ->whereIn('r.motivo', ['compra', 'farmacia'])
            ->where('u.estado', 'Activo')
            ->whereBetween('r.fecha', [$this->fechaInicioMes, $this->fechaActual])
            ->groupBy('u.id', 'u.name')
            ->orderBy('total_registros', 'desc')
            ->limit(10)
            ->get();

        foreach ($resultados as $resultado) {
            $this->nombrevendedoras[] = $resultado->nombre_usuario;
            $this->cantidadvendido[] = $resultado->total_registros;
        }
    }
    public function obtenerTotalesPorMes()
    {
        $this->meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        // Consulta para registropagos
        $resultadosPagos = DB::table('registropagos')
            ->select(DB::raw('EXTRACT(MONTH FROM TO_DATE(fecha, \'YYYY-MM-DD\')) as mes'), DB::raw('SUM(CAST(monto AS DECIMAL(10, 2))) as total_monto'))
            ->where('fecha', '>', '2025-01-01')
            ->where('fecha', '<', '2025-12-31')
            ->groupBy(DB::raw('EXTRACT(MONTH FROM TO_DATE(fecha, \'YYYY-MM-DD\'))'))
            ->orderBy(DB::raw('EXTRACT(MONTH FROM TO_DATE(fecha, \'YYYY-MM-DD\'))'))
            ->get();

        // Consulta para inventarios
        $resultadosInventarios = DB::table('registroinventarios')
            ->select(DB::raw('EXTRACT(MONTH FROM TO_DATE(fecha, \'YYYY-MM-DD\')) as mes'), DB::raw('SUM(CAST(precio AS DECIMAL(10, 2))) as total_precio'))
            ->whereIn('motivo', ['compra', 'farmacia'])
            ->where('fecha', '>', '2025-01-01')
            ->where('fecha', '<', '2025-12-31')
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
    public function actualizarFecha()
    {
        $user = User::find(Auth::user()->id);
        $user->desde = $this->fechaDesde;
        $user->hasta = $this->fechaHasta;
        $user->save();
        return redirect(request()->header('Referer'));
    }
    public function setOpcion($num)
    {
        $this->opcion = $num;
        $this->emit('renderGraficos');
        $this->render();
    }
    public function render()
    {
        $this->areas = Areas::where('estado', 'Activo')->where('area', '!=', 'ALMACEN CENTRAL')->get();
        return view('livewire.estadistica.estadistica-ventas');
    }
}