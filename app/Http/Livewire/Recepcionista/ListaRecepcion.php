<?php

namespace App\Http\Livewire\Recepcionista;

use App\Models\Areas;
use App\Models\Calls;
use App\Models\Operativos;
use App\Models\User;
use App\Models\registropago;
use App\Models\TelefonoWss;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class ListaRecepcion extends Component
{
    use WithPagination;
    public $open = false;
    public $opcion = 0;
    public $area;
    public $telefono;
    public $busqueda = "";
    public $actividad = "Pendiente";
    public $mostraroperativo = false;
    public $rangoseleccionado = "Diario";
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
    protected $listeners = ['render' => 'render', 'operativos' => 'operativos'];
    public function actulizargrafico()
    {
        $user = User::find(Auth::user()->id);
        $user->sucseleccionada = $this->areaseleccionadacalendario;
        $user->save();
        $this->emit('updateIframe');
        $this->render();
    }
    public function setOpcion($num)
    {
        $this->opcion = $num;
    }
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
    }
    public function render()
    {
        $this->citasrender();
        $horaActual = now()->format('H:i');

        $this->telefonosWss = TelefonoWss::where('sucursal', $this->areaseleccionada)->get();
        if ($this->rangoseleccionado == "Todos") {
            $llamadas = Operativos::where(function ($query) {
                $query->OrWhere('empresa', 'ilike', '%' . $this->busqueda . '%');
                $query->OrWhere('telefono', 'ilike', '%' . $this->busqueda . '%');
            })
                ->orderBy('hora', 'asc')
                ->paginate(10);

            // $llamadasasistidas = Operativos::where(function ($query) {
            //     $query->OrWhere('empresa', 'ilike', '%' . $this->busqueda . '%');
            //     $query->OrWhere('telefono', 'ilike', '%' . $this->busqueda . '%');
            // })
            //     ->where('area', 'ilike', '%' . $this->areaseleccionada)
            //     ->join('registropagos', 'operativos.id', '=', 'registropagos.idoperativo')
            //     ->orderBy('hora', 'asc')
            //     ->paginate(10);
            // $llamadasnoasistidas = Operativos::where(function ($query) {
            //     $query->orWhere('empresa', 'ilike', '%' . $this->busqueda . '%');
            //     $query->orWhere('telefono', 'ilike', '%' . $this->busqueda . '%');
            // })
            //     ->where('area',  $this->areaseleccionada)
            //     ->whereNotExists(function ($subquery) {
            //         $subquery->select(DB::raw(1))
            //             ->from('registropagos')
            //             ->whereRaw('operativos.id = registropagos.idoperativo');
            //     })
            //     ->orderBy('hora', 'asc')
            //     ->paginate(10);
        } else {



            $llamadas = Operativos::where(function ($query) {
                $query->orWhere('empresa', 'ilike', '%' . $this->busqueda . '%')
                    ->orWhere('telefono', 'ilike', '%' . $this->busqueda . '%');
            })
                ->where('area', 'ilike', '%' . $this->areaseleccionada)
                ->whereBetween('fecha', [$this->fechaInicioMes, $this->fechaActual])
                ->orderByRaw("CASE WHEN hora >= ? THEN 0 ELSE 1 END, hora ASC", [$horaActual])
                ->paginate(8);

            // $llamadasasistidas = Operativos::where('area', 'ilike', '%' . $this->areaseleccionada . '%')
            //     ->join('registropagos', DB::raw("CAST(operativos.idempresa AS TEXT)"), '=', DB::raw("CAST(registropagos.idcliente AS TEXT)"))
            //     ->where('registropagos.sucursal', $this->areaseleccionada)
            //     ->whereBetween('operativos.fecha', [$this->fechaInicioMes, $this->fechaActual])
            //     ->whereBetween('registropagos.fecha', [$this->fechaInicioMes, $this->fechaActual])
            //     ->distinct('registropagos.idcliente')
            //     ->paginate(10);

            // $llamadasnoasistidas = Operativos::where(function ($query) {
            //     $query->orWhere('empresa', 'ilike', '%' . $this->busqueda . '%');
            //     $query->orWhere('telefono', 'ilike', '%' . $this->busqueda . '%');
            // })
            //     ->where('area',  $this->areaseleccionada)

            //     ->whereBetween('fecha', [$this->fechaInicioMes, $this->fechaActual])
            //     ->whereNotExists(function ($subquery) {
            //         $subquery->select(DB::raw(1))
            //             ->from('registropagos')
            //             ->whereRaw(DB::raw("CAST(operativos.idempresa AS TEXT) = CAST(registropagos.idcliente AS TEXT)"))
            //             ->whereBetween('fecha', [$this->fechaInicioMes, $this->fechaActual]);
            //     })
            //     ->orderBy('hora', 'asc')
            //     ->paginate(10);
        }
        return view('livewire.recepcionista.lista-recepcion', compact('llamadas'));
    }
    public function enviarRecordatorio()
    {
        $horaActual = date('H:i');

        // Definir el rango de 8:00 AM a 8:30 AM
        $horaInicio = '08:00';
        $horaFin = '08:50';

        // Comparar si la hora actual está dentro del rango
        if ($horaActual >= $horaInicio && $horaActual < $horaFin) {
            $llamadasnoasistidas = Operativos::where('area', $this->areaseleccionada)
                ->where('estado', $this->actividad)
                ->whereBetween('fecha', [$this->fechaInicioMes, $this->fechaActual])
                ->whereNotExists(function ($subquery) {
                    $subquery->select(DB::raw(1))
                        ->from('registropagos')
                        ->whereRaw(DB::raw("CAST(operativos.idempresa AS TEXT) = CAST(registropagos.idcliente AS TEXT)"))
                        ->whereBetween('fecha', [$this->fechaInicioMes, $this->fechaActual]);
                })
                ->orderBy('hora', 'asc')
                ->get();

            foreach ($llamadasnoasistidas as $sinllamar) {
                $telefono = $sinllamar->telefono;

                // Verificar si el número ya tiene el prefijo 591
                if (substr($telefono, 0, 3) != '591' && strlen($telefono) == 8 && (substr($telefono, 0, 1) == '6' || substr($telefono, 0, 1) == '7')) {
                    // Si tiene 8 dígitos y empieza con 6 o 7, agregar el prefijo 591
                    $telefono = '591' . $telefono;
                } elseif (strlen($telefono) < 8) {
                    // Si el número tiene menos de 8 dígitos, no enviamos el mensaje
                    continue;
                }

                // Si el número ya tiene 591, lo dejamos tal cual
                if (substr($telefono, 0, 3) == '591' && strlen($telefono) == 11) {
                    // Número válido con 591
                } else {
                    // Si el número tiene más de 8 dígitos y no comienza con 591, es extranjero, no lo enviamos
                    continue;
                }
                $verificar = TelefonoWss::find($this->telefonosesion);
                if ($verificar->sucursal == 'CENTRAL URBARI') {
                    if ($sinllamar->hora == "00:00") {
                        $response = Http::post('http://localhost:3000/send-message', [
                            'sessionName' => $this->telefonosesion,
                            'toPhoneNumber' =>  $telefono,
                            'messageBody' => "Buenos días " . $sinllamar->empresa . " Le hablo de Cosmetología y Estética MIORA, *RECORDARLE* que el día de hoy tiene una cita pendiente, _Cualquier inconveniente o duda que tenga *Estoy para Responderle !!*_ Recordarle traer su *Carnet de Identidad* para poder ingresar al edificio porfavor🙌🏻"
                        ]);
                    } else {
                        $response = Http::post('http://localhost:3000/send-message', [
                            'sessionName' => $this->telefonosesion,
                            'toPhoneNumber' =>  $telefono,
                            'messageBody' => "Buenos días " . $sinllamar->empresa . " Le hablo de Cosmetología y Estética MIORA, *RECORDARLE* que el día de hoy tiene una cita a las " . $sinllamar->hora . " - _Cualquier inconveniente o duda que tenga *Estoy para Responderle !!*_ Recordarle traer su *Carnet de Identidad* para poder ingresar al edificio porfavor🙌🏻"
                        ]);
                    }
                    sleep(1);
                } else {
                    if ($sinllamar->hora == "00:00") {
                        $response = Http::post('http://localhost:3000/send-message', [
                            'sessionName' => $this->telefonosesion,
                            'toPhoneNumber' => $telefono,
                            'messageBody' => "Buenos días " . $sinllamar->empresa . " Le hablo de Cosmetología y Estética MIORA, *RECORDARLE* que el día de hoy tiene una cita pendiente, _Cualquier inconveniente o duda que tenga *Estoy para Responderle !!*_"
                        ]);
                    } else {
                        $response = Http::post('http://localhost:3000/send-message', [
                            'sessionName' => $this->telefonosesion,
                            'toPhoneNumber' => $telefono,
                            'messageBody' => "Buenos días " . $sinllamar->empresa . " Le hablo de Cosmetología y Estética MIORA, *RECORDARLE* que el día de hoy tiene una cita a las " . $sinllamar->hora . " - _Cualquier inconveniente o duda que tenga *Estoy para Responderle !!*_"
                        ]);
                    }
                    sleep(1);
                }
                // Enviar mensaje con el número validado


                if ($response->successful()) {
                    // Manejo de éxito
                } else {
                    // Manejo de error
                }
            }

            $this->emit('alert', '¡Envío finalizado!');
        } else {
            $this->emit('error', '¡Estas fuera de horario!');
        }
    }
    public function citasrender()
    {
        switch ($this->rangoseleccionado) {
            case "Diario":
                $this->fechaInicioMes = Carbon::now()->toDateString();
                $this->fechaActual = Carbon::now()->toDateString();
                // $this->agendados = Operativos::where('area', 'ilike', '%' . $this->areaseleccionada)->whereBetween('fecha', [$this->fechaInicioMes, $this->fechaActual])->where('estado', 'Pendiente')->count();
                // $this->confirmados = Operativos::where('area', 'ilike', '%' . $this->areaseleccionada . '%')
                //     ->join('registropagos', DB::raw("CAST(operativos.idempresa AS TEXT)"), '=', DB::raw("CAST(registropagos.idcliente AS TEXT)"))
                //     ->where('registropagos.sucursal', 'ilike', '%' . $this->areaseleccionada . '%')
                //     ->whereBetween('operativos.fecha', [$this->fechaInicioMes, $this->fechaActual])
                //     ->whereBetween('registropagos.fecha', [$this->fechaInicioMes, $this->fechaActual])
                //     ->distinct('registropagos.idcliente')
                //     ->count();
                // $this->nosasistido = Operativos::where(function ($query) {
                //     $query->orWhere('empresa', 'ilike', '%' . $this->busqueda . '%');
                //     $query->orWhere('telefono', 'ilike', '%' . $this->busqueda . '%');
                // })
                //     ->where('area', 'ilike', '%' . $this->areaseleccionada . '%')
                //     ->where('estado', $this->actividad)
                //     ->whereBetween('fecha', [$this->fechaInicioMes, $this->fechaActual])
                //     ->whereNotExists(function ($subquery) {
                //         $subquery->select(DB::raw(1))
                //             ->from('registropagos')
                //             ->whereRaw(DB::raw("CAST(operativos.idempresa AS TEXT) = CAST(registropagos.idcliente AS TEXT)"))
                //             ->whereBetween('fecha', [$this->fechaInicioMes, $this->fechaActual]);
                //     })
                //     ->orderBy('hora', 'asc')
                //     ->count();
                break;
            case "Semanal":
                $this->fechaInicioMes = Carbon::now()->subDays(5)->toDateString();
                $this->fechaActual = Carbon::now()->subDays(5)->toDateString();
                // $this->agendados = Operativos::where('area', 'ilike', '%' . $this->areaseleccionada)->whereBetween('fecha', [$this->fechaInicioMes, $this->fechaActual])->where('estado', 'Pendiente')->count();
                // $this->confirmados = Operativos::where('area', 'ilike', '%' . $this->areaseleccionada . '%')
                //     ->join('registropagos', DB::raw("CAST(operativos.idempresa AS TEXT)"), '=', DB::raw("CAST(registropagos.idcliente AS TEXT)"))
                //     ->where('registropagos.sucursal', 'ilike', '%' . $this->areaseleccionada . '%')
                //     ->whereBetween('operativos.fecha', [$this->fechaInicioMes, $this->fechaActual])
                //     ->whereBetween('registropagos.fecha', [$this->fechaInicioMes, $this->fechaActual])
                //     ->distinct('registropagos.idcliente')
                //     ->count();
                // $this->nosasistido = Operativos::where(function ($query) {
                //     $query->orWhere('empresa', 'ilike', '%' . $this->busqueda . '%');
                //     $query->orWhere('telefono', 'ilike', '%' . $this->busqueda . '%');
                // })
                //     ->where('area', 'ilike', '%' . $this->areaseleccionada . '%')
                //     ->where('estado', $this->actividad)
                //     ->whereBetween('fecha', [$this->fechaInicioMes, $this->fechaActual])
                //     ->whereNotExists(function ($subquery) {
                //         $subquery->select(DB::raw(1))
                //             ->from('registropagos')
                //             ->whereRaw(DB::raw("CAST(operativos.idempresa AS TEXT) = CAST(registropagos.idcliente AS TEXT)"))
                //             ->whereBetween('fecha', [$this->fechaInicioMes, $this->fechaActual]);
                //     })
                //     ->orderBy('hora', 'asc')
                //     ->count();
                // $this->nosasistido = Operativos::where(function ($query) {
                //     $query->orWhere('empresa', 'ilike', '%' . $this->busqueda . '%');
                //     $query->orWhere('telefono', 'ilike', '%' . $this->busqueda . '%');
                // })
                //     ->where('area', 'ilike', '%' . $this->areaseleccionada . '%')
                //     ->where('estado', $this->actividad)
                //     ->whereBetween('fecha', [$this->fechaInicioMes, $this->fechaActual])
                //     ->whereNotExists(function ($subquery) {
                //         $subquery->select(DB::raw(1))
                //             ->from('registropagos')
                //             ->whereRaw(DB::raw("CAST(operativos.idempresa AS TEXT) = CAST(registropagos.idcliente AS TEXT)"))
                //             ->whereBetween('fecha', [$this->fechaInicioMes, $this->fechaActual]);
                //     })
                //     ->orderBy('hora', 'asc')
                //     ->count();
                break;
            case "Mensual":
                $this->fechaInicioMes = Carbon::now()->subDays(30)->toDateString();
                $this->fechaActual = Carbon::now()->subDays(30)->toDateString();
                // $this->agendados = Operativos::where('area', 'ilike', '%' . $this->areaseleccionada)->whereBetween('fecha', [$this->fechaInicioMes, $this->fechaActual])->where('estado', 'Pendiente')->count();
                // $this->confirmados = Operativos::where('area', 'ilike', '%' . $this->areaseleccionada . '%')
                //     ->join('registropagos', DB::raw("CAST(operativos.idempresa AS TEXT)"), '=', DB::raw("CAST(registropagos.idcliente AS TEXT)"))
                //     ->where('registropagos.sucursal', 'ilike', '%' . $this->areaseleccionada . '%')
                //     ->whereBetween('operativos.fecha', [$this->fechaInicioMes, $this->fechaActual])
                //     ->whereBetween('registropagos.fecha', [$this->fechaInicioMes, $this->fechaActual])
                //     ->distinct('registropagos.idcliente')
                //     ->count();
                // $this->nosasistido = Operativos::where(function ($query) {
                //     $query->orWhere('empresa', 'ilike', '%' . $this->busqueda . '%');
                //     $query->orWhere('telefono', 'ilike', '%' . $this->busqueda . '%');
                // })
                //     ->where('area', 'ilike', '%' . $this->areaseleccionada . '%')
                //     ->where('estado', $this->actividad)
                //     ->whereBetween('fecha', [$this->fechaInicioMes, $this->fechaActual])
                //     ->whereNotExists(function ($subquery) {
                //         $subquery->select(DB::raw(1))
                //             ->from('registropagos')
                //             ->whereRaw(DB::raw("CAST(operativos.idempresa AS TEXT) = CAST(registropagos.idcliente AS TEXT)"))
                //             ->whereBetween('fecha', [$this->fechaInicioMes, $this->fechaActual]);
                //     })
                //     ->orderBy('hora', 'asc')
                //     ->count();
                break;
            case "Ayer":
                $this->fechaInicioMes = Carbon::now()->subDays(1)->toDateString();
                $this->fechaActual = Carbon::now()->subDays(1)->toDateString();
                // $this->agendados = Operativos::where('area', 'ilike', '%' . $this->areaseleccionada)->whereBetween('fecha', [$this->fechaInicioMes, $this->fechaActual])->where('estado', 'Pendiente')->count();
                // $this->confirmados = Operativos::where('area', 'ilike', '%' . $this->areaseleccionada . '%')
                //     ->join('registropagos', DB::raw("CAST(operativos.idempresa AS TEXT)"), '=', DB::raw("CAST(registropagos.idcliente AS TEXT)"))
                //     ->where('registropagos.sucursal', 'ilike', '%' . $this->areaseleccionada . '%')
                //     ->whereBetween('operativos.fecha', [$this->fechaInicioMes, $this->fechaActual])
                //     ->whereBetween('registropagos.fecha', [$this->fechaInicioMes, $this->fechaActual])
                //     ->distinct('registropagos.idcliente')
                //     ->count();
                // $this->nosasistido = Operativos::where(function ($query) {
                //     $query->orWhere('empresa', 'ilike', '%' . $this->busqueda . '%');
                //     $query->orWhere('telefono', 'ilike', '%' . $this->busqueda . '%');
                // })
                //     ->where('area', 'ilike', '%' . $this->areaseleccionada . '%')
                //     ->where('estado', $this->actividad)
                //     ->whereBetween('fecha', [$this->fechaInicioMes, $this->fechaActual])
                //     ->whereNotExists(function ($subquery) {
                //         $subquery->select(DB::raw(1))
                //             ->from('registropagos')
                //             ->whereRaw(DB::raw("CAST(operativos.idempresa AS TEXT) = CAST(registropagos.idcliente AS TEXT)"))
                //             ->whereBetween('fecha', [$this->fechaInicioMes, $this->fechaActual]);
                //     })
                //     ->orderBy('hora', 'asc')
                //     ->count();
                break;
            case "Personalizado":
                // $this->agendados = Operativos::where('area', 'ilike', '%' . $this->areaseleccionada)->whereBetween('fecha', [$this->fechaInicioMes, $this->fechaActual])->where('estado', 'Pendiente')->count();
                // $this->confirmados = Operativos::where('area', 'ilike', '%' . $this->areaseleccionada . '%')
                //     ->join('registropagos', DB::raw("CAST(operativos.idempresa AS TEXT)"), '=', DB::raw("CAST(registropagos.idcliente AS TEXT)"))
                //     ->where('registropagos.sucursal', 'ilike', '%' . $this->areaseleccionada . '%')
                //     ->whereBetween('operativos.fecha', [$this->fechaInicioMes, $this->fechaActual])
                //     ->whereBetween('registropagos.fecha', [$this->fechaInicioMes, $this->fechaActual])
                //     ->distinct('registropagos.idcliente')
                //     ->count();
                // $this->nosasistido = Operativos::where(function ($query) {
                //     $query->orWhere('empresa', 'ilike', '%' . $this->busqueda . '%');
                //     $query->orWhere('telefono', 'ilike', '%' . $this->busqueda . '%');
                // })
                //     ->where('area', 'ilike', '%' . $this->areaseleccionada . '%')
                //     ->where('estado', $this->actividad)
                //     ->whereBetween('fecha', [$this->fechaInicioMes, $this->fechaActual])
                //     ->whereNotExists(function ($subquery) {
                //         $subquery->select(DB::raw(1))
                //             ->from('registropagos')
                //             ->whereRaw(DB::raw("CAST(operativos.idempresa AS TEXT) = CAST(registropagos.idcliente AS TEXT)"))
                //             ->whereBetween('fecha', [$this->fechaInicioMes, $this->fechaActual]);
                //     })
                //     ->orderBy('hora', 'asc')
                //     ->count();
                break;
            case "Todos":
                // $this->agendados = Operativos::where('area', 'ilike', '%' . $this->areaseleccionada)->where('estado', 'Pendiente')->count();
                // $this->confirmados = Operativos::where('area', 'ilike', '%' . $this->areaseleccionada . '%')
                //     ->join('registropagos', DB::raw("CAST(operativos.idempresa AS TEXT)"), '=', DB::raw("CAST(registropagos.idcliente AS TEXT)"))
                //     ->where('registropagos.sucursal', 'ilike', '%' . $this->areaseleccionada . '%')
                //     ->distinct('registropagos.idcliente')
                //     ->count();
                // $this->nosasistido = Operativos::where(function ($query) {
                //     $query->orWhere('empresa', 'ilike', '%' . $this->busqueda . '%');
                //     $query->orWhere('telefono', 'ilike', '%' . $this->busqueda . '%');
                // })
                //     ->where('area', 'ilike', '%' . $this->areaseleccionada . '%')
                //     ->where('estado', $this->actividad)
                //     ->whereNotExists(function ($subquery) {
                //         $subquery->select(DB::raw(1))
                //             ->from('registropagos')
                //             ->whereRaw(DB::raw("CAST(operativos.idempresa AS TEXT) = CAST(registropagos.idcliente AS TEXT)"));
                //     })
                //     ->orderBy('hora', 'asc')
                //     ->count();
                break;
            default:
                echo "Opción no válida";
        };
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
    }
}