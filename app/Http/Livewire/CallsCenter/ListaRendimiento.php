<?php

namespace App\Http\Livewire\CallsCenter;

use App\Models\Areas;
use App\Models\Roles;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ListaRendimiento extends Component
{

    public $open = false;
    public $user;
    public $telefono;
    public $busqueda = "";
    public $actividad = "Activo";
    public $areas;
    public $areaseleccionada = '';
    public $activado;
    public $fechaInicioMes;
    public $fechaActual;
    public $estadoUser = 'Activo';
    public $rolseleccionado = 'Recepcion';
    public $roles;
    public $opcion = 3;
    public $ver = false;
    public $modo = "";
    public $totalgastos = 0;
    public $totalsumamonto = 0;
    public $totalsumainv = 0;
    public $totalsumainventario = 0;
    public $listaenganches;
    public $vermisventas = false;
    public $responsableseleccionado = 1;
    public $personalesConVentas;
    protected $listeners = ['render' => 'render', 'configurarSistema' => 'configurarSistema'];
    public $personales;
    public  $fechaActualMasUno;

    public $fecha_desde;
    public $fecha_hasta;
    public $totalFechas = null;
    public function mount()
    {
        $this->fechaInicioMes = date("Y-m-01");
        $this->fechaActual = now()->format('Y-m-d');
        $this->fecha_desde = now()->format('Y-m-d');
        $this->fecha_hasta = now()->format('Y-m-d');
    }
    public function render()
    {
        $this->areas = Areas::where('estado', 'Activo')
            ->where('area', 'NOT ILIKE', '%ALMACEN%')
            ->get();
        if (Auth::user()->rol == 'Administrador') {
            $users = User::where('sucursal', 'ilike', "%{$this->areaseleccionada}%")
                ->where('name', 'ilike', "%{$this->busqueda}%")
                ->whereIn('rol', ['Recepcion', 'CallCenter'])
                ->orderBy('rol', 'asc')
                ->paginate(150);
        } else {
            $users = User::where('sucursal', 'ilike', "%{$this->areaseleccionada}%")
                ->where('name', 'ilike', "%{$this->busqueda}%")
                ->where('rol', 'CallCenter')
                ->orderBy('name', 'asc')
                ->paginate(150);
        }
        $fechaActual = $this->fechaActual;
        $fechaInicioMes = $this->fechaInicioMes;
        $fechaActualMasUno = date('Y-m-d', strtotime($fechaActual . ' +1 day'));
        $this->fechaActualMasUno = $fechaActualMasUno;
        $this->personalesConVentas = DB::table('users as u')
            ->joinSub(function ($query) use ($fechaInicioMes,  $fechaActualMasUno) {
                $query->from('registroinventarios')
                    ->select(
                        'iduser',
                        DB::raw("TO_CHAR(created_at, 'YYYY-MM-DD HH24:MI') as hora_sin_segundos"),
                        DB::raw('SUM(CAST(precio AS DECIMAL) ) as total_venta')
                    )
                    ->whereIn('motivo', ['compra', 'farmacia'])
                    ->whereRaw("CAST(precio AS DECIMAL) >= 30")
                    ->whereBetween('created_at', [$fechaInicioMes,  $fechaActualMasUno])
                    ->groupBy('iduser', DB::raw("TO_CHAR(created_at, 'YYYY-MM-DD HH24:MI')"))
                    ->havingRaw('SUM(CAST(precio AS DECIMAL) ) >= 100');
            }, 'sub', 'sub.iduser', '=', 'u.id')
            ->select(
                'u.id',
                'u.name as nombre_usuario',
                DB::raw('SUM(sub.total_venta) as monto_aprobado'),
                DB::raw('ROUND(SUM(sub.total_venta) * 0.04, 2) as monto_ganado')
            )
            ->groupBy('u.id', 'u.name')
            ->orderByDesc('monto_aprobado')
            ->get();



        $noasistidos = DB::table('operativos as o')
            ->selectRaw('DISTINCT ON (o.idempresa) o.*')
            ->leftJoin('registropagos as rp', function ($join) {
                $join->on('o.idempresa', '=', DB::raw('CAST(rp.idcliente AS TEXT)'));
            })
            ->whereNull('rp.id')
            ->where('o.area', 'ilike', '%' . $this->areaseleccionada . '%')
            ->whereBetween('o.fecha', [$this->fechaInicioMes, $this->fechaActual])
            ->orderBy('o.idempresa') // Obligatorio por DISTINCT ON
            ->orderBy('o.created_at')
            ->paginate(15);


        $this->roles = Roles::where('estado', 'Activo')
            ->whereNotIn('rol', ['Recursos Humanos', 'Editor', 'Sistema', 'Administrador', 'TARJETAS', 'Cliente', 'Contador', 'INVENTARIO', 'Asist. Administrativo', 'Jefe Marketing y Publicidad'])
            ->get();

        return view('livewire.calls-center.lista-rendimiento', compact('users', 'noasistidos'));
    }
}