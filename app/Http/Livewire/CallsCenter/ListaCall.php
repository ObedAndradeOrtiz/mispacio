<?php

namespace App\Http\Livewire\CallsCenter;

use App\Models\Areas;
use App\Models\Calls;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class ListaCall extends Component
{
    use WithPagination;
    public $open = false;
    public $area;
    public $telefono;
    public $busqueda = "";
    public $actividad = "llamadas";
    public $mostraroperativo = false;
    public $rangoseleccionado = "Diario";
    public $fechaInicioMes;
    public $fechaActual;
    public $areas;
    public $crear = false;
    public $areaseleccionada = "";
    public $realizadas = 0;
    public $agendadas = 0;
    public $confirmadas = 0;
    public $faltantes = 0;
    public $misllamadas = 0;
    public $misagendados = 0;
    public $opcion = 0;
    public $eventos = [];
    public $areaseleccionadacalendario;
    public $responsableseleccionado = "";
    public $responsables;
    public $asistidos;

    protected $listeners = ['render' => 'render', 'operativos' => 'operativos'];
    public function mount()
    {
        $this->responsables = User::where('estado', 'Activo')->where('rol', '!=', 'Cliente')->get();
        if (Auth::user()->sucseleccionada) {
            $this->areaseleccionadacalendario = Auth::user()->sucseleccionada;
        } else {
            $this->areaseleccionadacalendario = 1;
            $user = User::find(Auth::user()->id);
            $user->sucseleccionada = $this->areaseleccionadacalendario;
        }

        $this->eventos = [];
        $all_events = DB::table('operativos')->where('area', Auth::user()->sucursal)->get();
        foreach ($all_events as $event) {
            $this->eventos[] = [
                'title' => 'CITA: ' . $event->empresa,
                'start' => $event->fecha . ' ' . $event->hora,
                'end' => $event->fecha . ' ' . $event->hora,
                'color' => '#167D27',
            ];
        }
        $this->fechaInicioMes = Carbon::now()->toDateString();
        $this->fechaActual = Carbon::now()->toDateString();
        $this->areas = Areas::where('estado', 'Activo')->where(function ($query) {
            $query->whereNull('almacen')
                ->orWhere('almacen', '');
        })->get();
    }

    public function render()
    {
        $this->callrender();
        $llamadas = null;
        $listaasistidos = null;
        if ($this->rangoseleccionado == "Todos") {
            $llamadas = DB::table('calls')
                ->when($this->responsableseleccionado, function ($query) {
                    return $query->where('calls.responsable', $this->responsableseleccionado);
                })
                ->when($this->areaseleccionada, function ($query) {
                    return $query->where('calls.area', $this->areaseleccionada);
                })
                ->when($this->busqueda, function ($query) {
                    return $query->where('calls.telefono', 'like',  $this->busqueda);
                })
                ->distinct('calls.id'); // Asegura que los IDs de calls sean únicos

            $llamadas = $llamadas->where('calls.estado', 'llamadas');

            $llamadas = $llamadas->orderBy('id', 'desc')
                ->paginate(5);


            $this->realizadas = $llamadas->total(); // Total de resultados
            $this->asistidos = DB::table('calls')
                ->join('operativos', 'operativos.idllamada', '=', 'calls.id')
                ->join('registropagos', 'operativos.id', '=', 'registropagos.idoperativo')
                ->when($this->responsableseleccionado, function ($query) {
                    return $query->where('calls.responsable', $this->responsableseleccionado);
                })
                ->when($this->areaseleccionada, function ($query) {
                    return $query->where('calls.area',  $this->areaseleccionada);
                })
                ->when($this->busqueda, function ($query) {
                    return $query->where('calls.telefono', 'like',  $this->busqueda);
                })
                ->orderBy('calls.id', 'desc')
                ->count();
            $listaasistidos = DB::table('calls')
                ->join('operativos', 'operativos.idllamada', '=', 'calls.id')
                ->join('registropagos', 'operativos.id', '=', 'registropagos.idoperativo')
                ->when($this->responsableseleccionado, function ($query) {
                    return $query->where('calls.responsable', 'like',  $this->responsableseleccionado);
                })
                ->when($this->areaseleccionada, function ($query) {
                    return $query->where('calls.area', 'like',  $this->areaseleccionada);
                })
                ->when($this->busqueda, function ($query) {
                    return $query->where('calls.telefono', 'like',  $this->busqueda);
                })
                ->orderBy('calls.id', 'desc')
                ->paginate(5);
            $this->agendadas = DB::table('calls')
                ->when($this->responsableseleccionado, function ($query) {
                    return $query->where('calls.responsable', 'like',  $this->responsableseleccionado);
                })
                ->when($this->areaseleccionada, function ($query) {
                    return $query->where('calls.area', 'like',  $this->areaseleccionada);
                })
                ->when($this->busqueda, function ($query) {
                    return $query->where('calls.telefono', 'like',  $this->busqueda);
                })
                ->where('calls.estado', "Pendiente")
                ->count();
        } else {
            $llamadas = DB::table('calls')
                ->when($this->responsableseleccionado, function ($query) {
                    return $query->where('calls.responsable', $this->responsableseleccionado);
                })
                ->when($this->areaseleccionada, function ($query) {
                    return $query->where('calls.area',  $this->areaseleccionada);
                })
                ->when($this->busqueda, function ($query) {
                    return $query->where('calls.telefono', 'like',  $this->busqueda);
                })
                ->whereBetween('calls.fecha', [$this->fechaInicioMes, $this->fechaActual]);

            $llamadas = $llamadas->where('calls.estado', 'llamadas');

            $llamadas = $llamadas->orderBy('id', 'desc')
                ->paginate(5);


            $this->realizadas = $llamadas->total(); // Total de resultados
            $this->agendadas = DB::table('calls')
                ->when($this->responsableseleccionado, function ($query) {
                    return $query->where('calls.responsable', 'like',  $this->responsableseleccionado);
                })
                ->when($this->areaseleccionada, function ($query) {
                    return $query->where('calls.area', 'like',  $this->areaseleccionada);
                })
                ->when($this->busqueda, function ($query) {
                    return $query->where('calls.telefono', 'like',  $this->busqueda);
                })
                ->where('calls.estado', "Pendiente")
                ->whereBetween('calls.fecha', [$this->fechaInicioMes, $this->fechaActual])->orderBy('id', 'desc')
                ->count();


            $this->asistidos = DB::table('calls')
                ->join('operativos', 'operativos.idllamada', '=', 'calls.id')
                ->join('registropagos', 'operativos.id', '=', 'registropagos.idoperativo')
                ->when($this->responsableseleccionado, function ($query) {
                    return $query->where('calls.responsable', 'like',  $this->responsableseleccionado);
                })
                ->when($this->areaseleccionada, function ($query) {
                    return $query->where('calls.area', 'like',  $this->areaseleccionada);
                })
                ->whereBetween('calls.fecha', [$this->fechaInicioMes, $this->fechaActual])
                ->orderBy('calls.id', 'desc')
                ->count();

            $listaasistidos = DB::table('calls')
                ->join('operativos', 'operativos.idllamada', '=', 'calls.id')
                ->join('registropagos', 'operativos.id', '=', 'registropagos.idoperativo')
                ->when($this->responsableseleccionado, function ($query) {
                    return $query->where('calls.responsable', 'like',  $this->responsableseleccionado);
                })
                ->when($this->areaseleccionada, function ($query) {
                    return $query->where('calls.area', 'like',  $this->areaseleccionada);
                })
                ->when($this->busqueda, function ($query) {
                    return $query->where('calls.telefono', 'like',  $this->busqueda);
                })
                ->whereBetween('calls.fecha', [$this->fechaInicioMes, $this->fechaActual])
                ->orderBy('calls.id', 'desc')
                ->paginate(5);
        }
        return view('livewire.calls-center.lista-call', compact('llamadas', 'listaasistidos'));
    }
    public function setOpcion($num)
    {

        if ($num == 0) {
            $this->actividad = "llamadas";
        }
        if ($num == 1) {
            $this->actividad = "Pendiente";
        }
        if ($num == 2) {
        }
        $this->opcion = $num;
    }
    public function callrender()
    {
        switch ($this->rangoseleccionado) {
            case "Diario":
                $this->fechaInicioMes = Carbon::now()->toDateString();
                $this->fechaActual = Carbon::now()->toDateString();
                break;
            case "Semanal":
                $this->fechaInicioMes = Carbon::now()->subDays(5)->toDateString();
                $this->fechaActual = Carbon::now()->subDays(5)->toDateString();
                break;
            case "Mensual":
                $this->fechaInicioMes = Carbon::now()->subDays(30)->toDateString();
                $this->fechaActual = Carbon::now()->subDays(30)->toDateString();
                break;
            case "Ayer":
                $this->fechaInicioMes = Carbon::now()->subDays(1)->toDateString();
                $this->fechaActual = Carbon::now()->subDays(1)->toDateString();
                break;
            case "Personalizado":
                $this->realizadas = Calls::where('area',  $this->areaseleccionada)->where('responsable',  $this->responsableseleccionado)->whereBetween('fecha', [$this->fechaInicioMes, $this->fechaActual])->where('estado', 'llamadas')->count();
                $this->agendadas = Calls::where('area',  $this->areaseleccionada)->where('responsable',  $this->responsableseleccionado)->whereBetween('fecha', [$this->fechaInicioMes, $this->fechaActual])->where('estado', 'Pendiente')->count();
                break;
            case "Todos":
                $this->realizadas = Calls::where('estado', 'llamadas')->count();
                $this->agendadas = Calls::where('estado', 'Pendiente')->count();
                break;
            default:
                echo "Opción no válida";
        };
    }

    public function actulizargrafico()
    {
        $user = User::find(Auth::user()->id);
        $user->sucseleccionada = $this->areaseleccionadacalendario;
        $user->save();
        $this->emit('updateIframe');
        $this->render();
    }
    public function copiarConsultaAlPortapapeles()
    {
        $resultados = DB::table('calls')
            ->select('empresa', 'telefono')
            ->where('area',  $this->areaseleccionada)
            ->where('estado', 'Pendiente')
            ->orderBy('id', 'desc')
            ->whereBetween('fecha', [$this->fechaInicioMes, $this->fechaActual])
            ->get();
        // Formatea los resultados en una cadena de texto
        $texto = "LISTA DE AGENDADOS DEL DIA\n";
        foreach ($resultados as $resultado) {
            $texto .= $resultado->empresa . " - " . $resultado->telefono . "\n";
        }
        // Escapa las comillas simples y dobles en el texto generado
        $texto = addslashes($texto);
        // Ejecuta el script JavaScript
        $this->emit('copiarTabla', $texto);
    }
}
