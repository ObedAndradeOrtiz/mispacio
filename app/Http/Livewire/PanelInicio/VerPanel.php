<?php

namespace App\Http\Livewire\PanelInicio;

use App\Models\Areas;
use Illuminate\Support\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\Operativos;
use App\Models\registrocaja;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;


class VerPanel extends Component
{
    use WithPagination;
    public $agendados;
    public $confirmados;
    public $fechaInicioMes;
    public $fechaActual;
    public $restantes;
    public $opcion = 0;
    public $usuarioseleccionado;
    public $nombremasobtenido;
    public $cantidadmasvendido;
    public $resultados;
    public $resultadosAnterior;
    public $areas;
    public $areaseleccionada;
    protected $listeners = ['eliminarCierreCajaFunc' => 'eliminarCierreCajaFunc'];
    public function mount()
    {
        $this->areas = Areas::where('estado', 'Activo')->get();
        $this->areaseleccionada = Auth::user()->sucursal;
        $this->usuarioseleccionado = Auth::user()->id;
        $this->fechaInicioMes = Carbon::now()->toDateString();
        $this->fechaActual = Carbon::now()->toDateString();
    }
    public function setOpcion($num)
    {
        $this->opcion = $num;
    }
    public function render()
    {
        // Asegúrate de que el driver de sesión sea "database"
        $sessions = DB::connection(config('session.connection'))
            ->table(config('session.table', 'sessions'))
            ->orderBy('last_activity', 'desc')
            ->get();


        $hoy = date('Y-m-d');
        $this->agendados = Operativos::where('area', Auth::user()->sucursal)->whereBetween('fecha', [$this->fechaInicioMes, $this->fechaActual])->count();
        $this->confirmados = Operativos::where('area',  Auth::user()->sucursal)
            ->join('registropagos', DB::raw("CAST(operativos.idempresa AS TEXT)"), '=', DB::raw("CAST(registropagos.idcliente AS TEXT)"))
            ->where('registropagos.sucursal', 'ilike', '%' . Auth::user()->sucursal . '%')
            ->whereBetween('operativos.fecha', [$this->fechaInicioMes, $this->fechaActual])
            ->whereBetween('registropagos.fecha', [$this->fechaInicioMes, $this->fechaActual])
            ->distinct('registropagos.idcliente')
            ->count();
        $this->restantes = $this->agendados - $this->confirmados;
        $pagados = DB::table('pagos')->select(DB::raw('CAST(SUM(cantidad) AS DECIMAL(10,2)) as total'))->first()->total;
        $porpagar = DB::table('pagos')->where('estado', 'Inactivo')->select(DB::raw('CAST(SUM(cantidad) AS DECIMAL(10,2)) as total'))->first()->total;
        return view('livewire.panel-inicio.ver-panel', compact('pagados', 'porpagar'));
    }
    public function eliminarCierreCajaFunc($idcaja)
    {
        $caja = registrocaja::find($idcaja);
        $caja->delete();
        $this->render();
    }
}
