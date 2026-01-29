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

class ListaLlamadas extends Component
{
    use WithPagination;
    public $open = false;
    public $area;
    public $telefono;
    public $busqueda = "";
    public $actividad = "llamadas";
    public $mostraroperativo = false;
    public $rangoseleccionado = "Personalizado";
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
        $this->responsables = User::where('estado', 'Activo')->whereIn('rol', ['Callcenter', 'Recepcion'])->get();
        if (Auth::user()->sucseleccionada) {
            $this->areaseleccionadacalendario = Auth::user()->sucseleccionada;
        } else {
            $this->areaseleccionadacalendario = 1;
            $user = User::find(Auth::user()->id);
            $user->sucseleccionada = $this->areaseleccionadacalendario;
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
        $llamadas = null;
        if ($this->rangoseleccionado == "Todos") {
            $llamadas = DB::table('calls')
                ->when($this->responsableseleccionado, function ($query) {
                    return $query->where('calls.responsable', 'like',  $this->responsableseleccionado);
                })
                ->when($this->areaseleccionada, function ($query) {
                    return $query->where('calls.area', 'like',  $this->areaseleccionada);
                })
                ->when($this->busqueda, function ($query) {
                    return $query->where('calls.telefono', 'like',  $this->busqueda);
                })
                ->when($this->actividad, function ($query) {
                    return $query->where('calls.estado', 'like',  $this->actividad);
                })
                ->distinct('calls.id');




            $llamadas = $llamadas->orderBy('id', 'desc')
                ->paginate(10);
        } else {
            $llamadas = DB::table('calls')
                ->when($this->responsableseleccionado, function ($query) {
                    return $query->where('calls.responsable', 'like',  $this->responsableseleccionado);
                })
                ->when($this->areaseleccionada, function ($query) {
                    return $query->where('calls.area', 'like',  $this->areaseleccionada);
                })
                ->when($this->busqueda, function ($query) {
                    return $query->where('calls.telefono', 'like',  $this->busqueda);
                })
                ->when($this->actividad, function ($query) {
                    return $query->where('calls.estado', 'like',  $this->actividad);
                })
                ->whereBetween('calls.fecha', [$this->fechaInicioMes, $this->fechaActual]);

            $llamadas = $llamadas->orderBy('id', 'desc')
                ->paginate(10);
        }
        return view('livewire.calls-center.lista-llamadas', compact('llamadas'));
    }
    public function copiarConsultaAlPortapapeles()
    {
        if ($this->rangoseleccionado == "Todos") {
            $llamadas = DB::table('calls')
                ->when($this->responsableseleccionado, function ($query) {
                    return $query->where('calls.responsable', 'like',  $this->responsableseleccionado);
                })
                ->when($this->areaseleccionada, function ($query) {
                    return $query->where('calls.area', 'like',  $this->areaseleccionada);
                })
                ->when($this->busqueda, function ($query) {
                    return $query->where('calls.telefono', 'like',  $this->busqueda);
                })
                ->distinct('calls.id');
            $llamadas = $llamadas->orderBy('id', 'desc')
                ->get();
        } else {
            $llamadas = DB::table('calls')
                ->when($this->responsableseleccionado, function ($query) {
                    return $query->where('calls.responsable', 'like',  $this->responsableseleccionado);
                })
                ->when($this->areaseleccionada, function ($query) {
                    return $query->where('calls.area', 'like',  $this->areaseleccionada);
                })
                ->when($this->busqueda, function ($query) {
                    return $query->where('calls.telefono', 'like',  $this->busqueda);
                })
                ->when($this->actividad, function ($query) {
                    return $query->where('calls.estado', 'like',  $this->actividad);
                })
                ->whereBetween('calls.fecha', [$this->fechaInicioMes, $this->fechaActual]);

            $llamadas = $llamadas->orderBy('id', 'desc')
                ->get();
        }
        $texto = "";
        foreach ($llamadas as $resultado) {
            $texto .= $resultado->telefono . "\n";
        }
        $texto = addslashes($texto);
        $this->emit('copiarTabla', $texto);
        $this->emit('alert', '¡Números copiados!');
    }
}
