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

class ListaEstadistica extends Component
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

    public function mount()
    {
        $this->botonRecepcion = "pagos";
        $this->fechaInicioMes = date("Y-m-01");
        $this->fechaActual = date("Y-04-30");
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
        // if (Auth::user()->rol == 'Jefe Marketing y Publicidad') {
        //     $this->botonRecepcion = 'citas';
        // }


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

        // $mesSeleccionado = Carbon::now()->subMonth()->month; // Mes anterior
        // $this->diasDelMes = [];

        // $numeroDias = Carbon::now()->subMonth()->daysInMonth;

        for ($dia = 1; $dia <= $numeroDias; $dia++) {
            $carbonDate = Carbon::create(2025, $mesSeleccionado, $dia);
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
        $this->obtenerTotalesPorMes();
    }
    public function obtenerTotalesPorMes()
    {
        $anioActual = date('Y');
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

        return view('livewire.estadistica.lista-estadistica');
    }
}