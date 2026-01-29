<?php

namespace App\Http\Livewire\Recepcionista;

use App\Models\Areas;
use App\Models\Calls;
use App\Models\Operativos;
use App\Models\User;
use App\Models\registropago;
use App\Models\TelefonoWss;
use App\Models\Tratamiento;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;


class ListaAgendados extends Component
{
    use WithPagination;
    public $open = false;
    public $opcion = 0;
    public $area;
    public $telefono;
    public $busqueda = "";
    public $actividad = "Pendiente";
    public $mostraroperativo = false;
    public $rangoseleccionado = "Personalizado";
    public $fechaInicioMes;
    public $fechaActual;
    public $areas;
    public $botonRecepcion = true;
    public $areaseleccionada = "";
    public $actividadBoton = 'Activo';
    public $busquedaCliente = '';
    public $agendados = 0;
    public $confirmados;
    public $dineroesperado;
    public $dinerobtenido;
    public $nuevosclientes;
    public $deuda;
    public $asistido = 0;
    public $botonagenda = "Agendados";
    public $areaseleccionadacalendario;
    public $nosasistido = 0;
    public $telefonosesion;
    public $recordar = false;
    public $telefonosWss;
    public $tratamientobuscado = "";
    public $tratamientos;
    protected $listeners = ['render' => 'render', 'operativos' => 'operativos'];
    public function mount()
    {
        if (Auth::user()->sucseleccionada) {
            $this->areaseleccionadacalendario = Auth::user()->sucseleccionada;
        } else {
            $this->areaseleccionadacalendario = 1;
            $user = User::find(Auth::user()->id);
            $user->sucseleccionada = $this->areaseleccionadacalendario;
        }
        $this->fechaInicioMes = Carbon::now()->toDateString();
        $this->fechaActual = Carbon::now()->toDateString();
        $this->areas = Areas::where('estado', 'Activo')->get();
        $this->areaseleccionada = Auth::user()->sucursal;
        $this->tratamientos = Tratamiento::where('estado', 'Activo')->get();
    }
    public function render()
    {
        if ($this->rangoseleccionado == "Todos") {
            $llamadas = Operativos::where(function ($query) {
                $query->OrWhere('empresa', 'ilike', '%' . $this->busqueda . '%');
                $query->OrWhere('telefono', 'ilike', '%' . $this->busqueda . '%');
            })
                ->where('area', 'ilike', '%' . $this->areaseleccionada)
                ->where('estado', $this->actividad)
                ->orderBy('hora', 'asc')
                ->paginate(10);
        } else {


            $llamadas = Operativos::join('historial_clientes as hc', 'operativos.idempresa', '=', 'hc.idcliente')
                ->where(function ($query) {
                    $query->orWhere('operativos.empresa', 'ilike', '%' . $this->busqueda . '%')
                        ->orWhere('operativos.telefono', 'ilike', '%' . $this->busqueda . '%');
                })
                ->where('operativos.area', 'ilike', '%' . $this->areaseleccionada . '%')
                ->where('operativos.estado', $this->actividad)
                ->whereBetween('operativos.fecha', [$this->fechaInicioMes, $this->fechaActual])
                ->where('hc.nombretratamiento', 'ilike', '%' . $this->tratamientobuscado . '%') // Filtro por nombre de tratamiento
                ->orderBy('operativos.hora', 'desc')
                ->select('operativos.*', 'hc.nombretratamiento') // Incluir columna de historial_clientes
                ->paginate(10);
        }
        return view('livewire.recepcionista.lista-agendados', compact('llamadas'));
    }
    public function copiarConsultaAlPortapapeles()
    {
        $resultados = DB::table('operativos')

            ->where('area', 'ilike', '%' . $this->areaseleccionada)
            ->whereBetween('fecha', [$this->fechaInicioMes, $this->fechaActual])
            ->orderBy('hora', 'asc')
            ->get();


        if ($this->botonagenda == 'Agendados') {
            if ($this->areaseleccionada == '') {
                $texto = "*LISTA DE AGENDADOS SUC. TODAS*\n";
            } else {
                $texto = "*LISTA DE AGENDADOS SUC." . $this->areaseleccionada . "*\n";
            }
        }
        if ($this->botonagenda == 'Asistidos') {
            if ($this->areaseleccionada == '') {
                $texto = "*LISTA DE ASISTIDOS SUC. TODAS*\n";
            } else {
                $texto = "*LISTA DE ASISTIDOS SUC." . $this->areaseleccionada . "*\n";
            }
        }
        if ($this->botonagenda == 'NoAsistidos') {
            if ($this->areaseleccionada == '') {
                $texto = "*LISTA DE NO ASISTIDOS SUC. TODAS*\n";
            } else {
                $texto = "*LISTA DE AGENDADOS SUC." . $this->areaseleccionada . "*\n";
            }
        }

        $contador = 1;
        foreach ($resultados as $resultado) {
            if ($this->botonagenda == 'Agendados') {
                $existeIdOperativo = DB::table('registropagos')
                    ->where(DB::raw("CAST(idcliente AS TEXT)"), $resultado->idempresa)
                    ->whereBetween('fecha', [$this->fechaInicioMes, $this->fechaActual])
                    ->exists();


                if ($existeIdOperativo) {
                    $texto .= $contador . '-' . $resultado->empresa . " - " . $resultado->telefono . ' - *ASISTIO* - ' . $resultado->hora . ' - ' . $resultado->fecha . "\n";
                } else {
                    $texto .= $contador . '-' . $resultado->empresa . " - " . $resultado->telefono . ' - *NO ASISTIO* - ' . $resultado->hora . ' - ' . $resultado->fecha . "\n";
                }
            }
            if ($this->botonagenda == 'Asistidos') {
                $existeIdOperativo = DB::table('registropagos')
                    ->where(DB::raw("CAST(idcliente AS TEXT)"), $resultado->idempresa)
                    ->whereBetween('fecha', [$this->fechaInicioMes, $this->fechaActual])
                    ->exists();

                if ($existeIdOperativo) {
                    $texto .= $contador . '-' . $resultado->empresa . " - " . $resultado->telefono . ' - *ASISTIO* - ' . $resultado->hora . ' - ' . $resultado->fecha . "\n";
                }
            }
            if ($this->botonagenda == 'NoAsistidos') {
                $existeIdOperativo = DB::table('registropagos')
                    ->where(DB::raw("CAST(idcliente AS TEXT)"), $resultado->idempresa)
                    ->whereBetween('fecha', [$this->fechaInicioMes, $this->fechaActual])
                    ->exists();
                if ($existeIdOperativo) {
                } else {
                    $texto .= $contador . '-' . $resultado->empresa . " - " . $resultado->telefono . ' - *NO ASISTIO* - ' . $resultado->hora . ' - ' . $resultado->fecha . "\n";
                }
            }
            $contador += 1;
        }
        // Escapa las comillas simples y dobles en el texto generado
        $texto = addslashes($texto);
        // Ejecuta el script JavaScript
        $this->emit('copiarTabla', $texto);
        $this->emit('alert', '¡Números copiados!');
    }
}