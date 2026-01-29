<?php

namespace App\Console\Commands;

use App\Models\Areas;
use App\Models\Calls;
use App\Models\campana;
use App\Models\Operativos;
use App\Models\Productos;
use App\Models\publicidades;
use App\Models\TelefonoWss;
use Carbon\Carbon;
use DateTime;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use PhpOffice\PhpSpreadsheet\Calculation\Engine\Operands\Operand;

class TareaCada1Minuto extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:tarea-cada1-minuto';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public $fechaInicioMes;
    public $fechaActual;
    public function handle()
    {
        $this->fechaInicioMes = Carbon::now()->toDateString();
        $this->fechaActual = Carbon::now()->toDateString();
        $horaActual = Carbon::now()->format('H:i');
        $horaInicio = '09:00';
        $horaFin = '23:30';
        $fechaActual = date('Y-m-d');
        $horaObjetivo = Carbon::now()->addHour()->format('H:i');

        if ($horaActual >= $horaInicio && $horaActual < $horaFin) {
            $telefonos = TelefonoWss::where('modo', 'P')->get();

            foreach ($telefonos as $telefonoactual) {
                $destino = '59177035251';

                $llamadasnoasistidas = Operativos::where('area', $telefonoactual->sucursal)
                    ->where('fecha', date('Y-m-d'))
                    ->where('hora', $horaObjetivo) // Coincide con la hora exacta
                    ->whereNotExists(function ($subquery) {
                        $subquery->select(DB::raw(1))
                            ->from('registropagos')
                            ->whereRaw("CAST(operativos.idempresa AS TEXT) = CAST(registropagos.idcliente AS TEXT)")
                            ->where('fecha', date('Y-m-d'));
                    })
                    ->get();

                foreach ($llamadasnoasistidas as $sinllamar) {
                    try {
                        $telefono = $sinllamar->telefono;
                        // Validar y formatear número de teléfono
                        if (substr($telefono, 0, 3) != '591' && strlen($telefono) == 8 && in_array(substr($telefono, 0, 1), ['6', '7'])) {
                            $telefono = '591' . $telefono;
                        } elseif (strlen($telefono) < 8 || (substr($telefono, 0, 3) == '591' && strlen($telefono) != 11)) {
                            continue;
                        }

                        // Determinar si es AM o PM para ajustar el saludo
                        $horaFormato24 = Carbon::createFromFormat('H:i',  Carbon::now()->format('H:i'));
                        $saludo = $horaFormato24->format('A') === 'AM' ? 'Buenos días' : 'Buenas tardes';

                        $mensaje = "$saludo " . $sinllamar->empresa . "\n\n" .
                            "Esperamos se encuentre bien, 😷👍🏼\n\n" .
                            "Quisiéramos confirmar su asistencia hoy a las " . $sinllamar->hora . "\n\n" .
                            "😊👍🏼 Sí\n" .
                            "😓👎🏼 No\n" .
                            "📝 Otra Fecha\n\n" .
                            "Cualquier duda estoy para responderle !!.";


                        $response = Http::post('http://localhost:3000/send-message', [
                            'sessionName' => $telefonoactual->id,
                            'toPhoneNumber' =>   $telefono,
                            'messageBody' => $mensaje,
                        ]);
                    } catch (\Exception $e) {
                        continue; // Si hay error, sigue con la siguiente iteración
                    }
                }
            }
        }
        if ($horaActual == '19:00' && Carbon::now()->isSaturday()) {
            $fechaSabado = Carbon::now()->startOfWeek()->addDays(5)->format('Y-m-d'); // Sábado de la semana actual
            $fechaInicioMes = Carbon::now()->startOfWeek()->format('Y-m-d'); // Lunes de la semana actual

            $noasistidos = DB::table('operativos as o')
                ->selectRaw('DISTINCT ON (o.idempresa) o.*')
                ->leftJoin('registropagos as rp', function ($join) {
                    $join->on('o.idempresa', '=', DB::raw('CAST(rp.idcliente AS TEXT)'));
                })
                ->whereNull('rp.id')
                ->whereRaw("TO_DATE(o.fecha, 'YYYY-MM-DD') BETWEEN ? AND ?", [$fechaInicioMes, $fechaSabado])
                ->get();

            foreach ($noasistidos as $noasistido) {
                try {
                    $cuentawss = TelefonoWss::where('conectado', 'CC')
                        ->where('sucursal', $noasistido->area)
                        ->where('modo', 'P')
                        ->first();
                    $mensaje1 = "🌙 Buenas noches , espero que esté bien @nombre, notamos que no pudo asistir a su cita esta semana. Para su comodidad, ahora atendemos también los *Domingos de 08:00 AM a 5:00 PM*. https://vm.tiktok.com/ZMSfeutAq/
                    🗓 ¿Le gustaría reprogramar su cita para este Domingo?";
                    $mensaje = str_replace('@nombre', $noasistido->empresa, $mensaje1);
                    $response = Http::post('http://localhost:3000/send-message', [
                        'sessionName' => $cuentawss->id, // Evita error si $cuentawss es null
                        'toPhoneNumber' => $noasistido->telefono,
                        'messageBody' => $mensaje
                    ]);
                } catch (\Exception $e) {
                    continue; // Si hay error, sigue con la siguiente iteración
                }
            }
        }

        if ($horaActual == '08:00') {
            $areas = Areas::orderBy('id', 'asc')->get();
            foreach ($areas as $area) {
                $llamadasnoasistidas = Operativos::where('area', $area->area)
                    ->where('estado', 'Pendiente')
                    ->where('fecha', $fechaActual)
                    ->whereNotExists(function ($subquery) {
                        $subquery->select(DB::raw(1))
                            ->from('registropagos')
                            ->whereRaw(DB::raw("CAST(operativos.idempresa AS TEXT) = CAST(registropagos.idcliente AS TEXT)"))
                            ->where('fecha', $this->fechaInicioMes);
                    })
                    ->orderBy('hora', 'asc')
                    ->get();
                foreach ($llamadasnoasistidas as $sinllamar) {
                    try {
                        $telefono = $sinllamar->telefono;

                        // Verificar si el número ya tiene el prefijo 591
                        if (substr($telefono, 0, 3) != '591' && strlen($telefono) == 8 && (substr($telefono, 0, 1) == '6' || substr($telefono, 0, 1) == '7')) {

                            $telefono = '591' . $telefono;
                        } elseif (strlen($telefono) < 8) {

                            continue;
                        }

                        // Si el número ya tiene 591, lo dejamos tal cual
                        if (substr($telefono, 0, 3) == '591' && strlen($telefono) == 11) {
                            // Número válido con 591
                        } else {
                            // Si el número tiene más de 8 dígitos y no comienza con 591, es extranjero, no lo enviamos
                            continue;
                        }
                        $verificar = TelefonoWss::where('conectado', 'CC')->where('modo', 'P')->where('sucursal', $area->area)->first();
                        if ($verificar->sucursal == 'CENTRAL URBARI') {
                            if ($sinllamar->hora == "00:00") {
                                $response = Http::post('http://localhost:3000/send-message', [
                                    'sessionName' =>  $verificar->id,
                                    'toPhoneNumber' =>  $telefono,
                                    'messageBody' => "Buenos días " . $sinllamar->empresa . " Le hablo de Cosmetología y Estética MIORA, *RECORDARLE* que el día de hoy tiene una cita pendiente, _Cualquier inconveniente o duda que tenga *Estoy para Responderle !!*_ Recordarle traer su *Carnet de Identidad* para poder ingresar al edificio porfavor🙌🏻"
                                ]);
                            } else {
                                $response = Http::post('http://localhost:3000/send-message', [
                                    'sessionName' => $verificar->id,
                                    'toPhoneNumber' =>  $telefono,
                                    'messageBody' => "Buenos días " . $sinllamar->empresa . " Le hablo de Cosmetología y Estética MIORA, *RECORDARLE* que el día de hoy tiene una cita a las " . $sinllamar->hora . " - _Cualquier inconveniente o duda que tenga *Estoy para Responderle !!*_ Recordarle traer su *Carnet de Identidad* para poder ingresar al edificio porfavor🙌🏻"
                                ]);
                            }
                        } else {
                            if ($sinllamar->hora == "00:00") {
                                $response = Http::post('http://localhost:3000/send-message', [
                                    'sessionName' => $verificar->id,
                                    'toPhoneNumber' => $telefono,
                                    'messageBody' => "Buenos días " . $sinllamar->empresa . " Le hablo de Cosmetología y Estética MIORA, *RECORDARLE* que el día de hoy tiene una cita pendiente, _Cualquier inconveniente o duda que tenga *Estoy para Responderle !!*_ Recordarle traer su *Carnet de Identidad* para poder ingresar al edificio porfavor🙌🏻"
                                ]);
                            } else {
                                $response = Http::post('http://localhost:3000/send-message', [
                                    'sessionName' => $verificar->id,
                                    'toPhoneNumber' => $telefono,
                                    'messageBody' => "Buenos días " . $sinllamar->empresa . " Le hablo de Cosmetología y Estética MIORA, *RECORDARLE* que el día de hoy tiene una cita a las " . $sinllamar->hora . " - _Cualquier inconveniente o duda que tenga *Estoy para Responderle !!*_ Recordarle traer su *Carnet de Identidad* para poder ingresar al edificio porfavor🙌🏻"
                                ]);
                            }
                        }
                        if ($response->successful()) {
                            // Manejo de éxito
                        } else {
                            // Manejo de error
                        }
                    } catch (\Exception $e) {
                        continue; // Si hay error, sigue con la siguiente iteración
                    }
                }
            }
        }

        $publicidades = publicidades::where('estado', 'Activo')->orderBy('id')->get();

        foreach ($publicidades as $publicidad) {
            try {
                $campana = campana::find($publicidad->idcampana);
                if ($publicidad->fecharango == $fechaActual) {
                    if ($publicidad->hora == $horaActual) {
                        if ($publicidad->asistencia == "snasis") {
                            if ($publicidad->enviosucursal == 'suc0') {
                                $agendados = Operativos::whereBetween('fecha', [$publicidad->fechainicio, $publicidad->fechafin])->get();
                                foreach ($agendados as $agendado) {
                                    try {
                                        $cuentawss = TelefonoWss::where('conectado', 'CC')
                                            ->where('sucursal', $agendado->area)
                                            ->where('modo', 'P')
                                            ->first();
                                        $mensaje = str_replace('@nombre', $agendado->empresa, $campana->comentario);
                                        if ($campana->path) {
                                            $response = Http::post('http://localhost:3000/send-message', [
                                                'sessionName' => $cuentawss->id, // Evita error si $cuentawss es null
                                                'toPhoneNumber' =>  $agendado->telefono,
                                                'messageBody' => $mensaje, // Evita error si teléfono es null
                                                'imagePath' => 'C:/xampp/htdocs/miora-project/public/storage/' . $campana->path
                                            ]);
                                        } else {
                                            $response = Http::post('http://localhost:3000/send-message', [
                                                'sessionName' => $cuentawss->id,
                                                'toPhoneNumber' =>  $agendado->telefono,
                                                'messageBody' => $mensaje,
                                            ]);
                                        }
                                    } catch (\Exception $e) {

                                        continue; // Salta esta iteración y sigue con la siguiente
                                    }
                                }
                            }
                        }
                    }
                    if ($publicidad->hora == $horaActual) {
                        if ($publicidad->asistencia == "noagen") {
                            if ($publicidad->enviosucursal == 'suc0') {
                                $agendados  = Calls::where('estado', 'llamadas')->whereBetween('fecha', [$publicidad->fechainicio, $publicidad->fechafin])->get();
                                foreach ($agendados as $agendado) {
                                    try {
                                        $cuentawss = TelefonoWss::where('conectado', 'CC')
                                            ->where('sucursal', $agendado->area)
                                            ->where('modo', 'P')
                                            ->first();
                                        $mensaje = $campana->comentario;
                                        if ($campana->path) {
                                            $response = Http::post('http://localhost:3000/send-message', [
                                                'sessionName' => $cuentawss->id, // Evita error si $cuentawss es null
                                                'toPhoneNumber' =>  '59177035251',
                                                'messageBody' => $mensaje, // Evita error si teléfono es null
                                                'imagePath' => 'C:/xampp/htdocs/miora-project/public/storage/' . $campana->path
                                            ]);
                                        } else {
                                            $response = Http::post('http://localhost:3000/send-message', [
                                                'sessionName' => $cuentawss->id,
                                                'toPhoneNumber' =>  $agendado->telefono,
                                                'messageBody' => $mensaje,
                                            ]);
                                        }
                                    } catch (\Exception $e) {

                                        continue; // Salta esta iteración y sigue con la siguiente
                                    }
                                }
                            }
                        }
                    }
                }

                if ($publicidad->enviosucursal == 'suc0') {
                    if ($publicidad->frecuencia == "frec0") {

                        if ($publicidad->asistencia == "snasis") {

                            if ($publicidad->tipocliente == 'tip7') {

                                if ($publicidad->hora == $horaActual  && $publicidad->fechainicio == $fechaActual) {
                                    $llamadas = Calls::where('fecha', Carbon::now()->subDays(1)->toDateString())
                                        ->orderBy('area')
                                        ->get();
                                    foreach ($llamadas as $llamada) {
                                        try {
                                            $cuentawss = TelefonoWss::where('conectado', 'CC')
                                                ->where('sucursal', $llamada->area)
                                                ->where('modo', 'P')
                                                ->first();

                                            if ($campana->path) {
                                                $response = Http::post('http://localhost:3000/send-message', [
                                                    'sessionName' => $cuentawss->id, // Evita error si $cuentawss es null
                                                    'toPhoneNumber' =>  $llamada->telefono,
                                                    'messageBody' => $campana->comentario, // Evita error si teléfono es null
                                                    'imagePath' => 'C:/xampp/htdocs/miora-project/public/storage/' . $campana->path
                                                ]);
                                            } else {
                                                $response = Http::post('http://localhost:3000/send-message', [
                                                    'sessionName' => $cuentawss->id,
                                                    'toPhoneNumber' =>   $llamada->telefono,
                                                    'messageBody' => $campana->comentario,
                                                ]);
                                            }
                                        } catch (\Exception $e) {

                                            continue; // Salta esta iteración y sigue con la siguiente
                                        }
                                    }
                                }
                            }
                        }
                    }
                    if ($publicidad->frecuencia == "frec1") {
                        if ($publicidad->hora == $horaActual) {
                            if ($publicidad->asistencia == "noagen") {
                                if ($publicidad->tipocliente == 'tip10') {
                                    $llamadas = Calls::where('fecha',  Carbon::now()->subDay()->toDateString())->where('estado', 'llamadas')->orderBy('area')->get();
                                    foreach ($llamadas as $llamada) {
                                        try {
                                            $cuentawss = TelefonoWss::where('conectado', 'CC')->where('sucursal', $llamada->area)->where('modo', 'P')->first();
                                            if ($campana->path) {
                                                $response = Http::post('http://localhost:3000/send-message', [
                                                    'sessionName' =>  $cuentawss->id,
                                                    'toPhoneNumber' =>  $llamada->telefono,
                                                    'messageBody' => $campana->comentario,
                                                    'imagePath' => 'C:/xampp/htdocs/miora-project/public/storage/' . $campana->path
                                                ]);
                                            } else {
                                                $response = Http::post('http://localhost:3000/send-message', [
                                                    'sessionName' =>  $cuentawss->id,
                                                    'toPhoneNumber' =>  $llamada->telefono,
                                                    'messageBody' => $campana->comentario,
                                                ]);
                                            }
                                        } catch (\Exception $e) {
                                            continue; // Si hay error, sigue con la siguiente iteración
                                        }
                                    }
                                }
                            }
                            if ($publicidad->asistencia == "noasis") {
                                if ($publicidad->tipocliente == 'tip10') {

                                    $fechaEspecifica = Carbon::yesterday()->toDateString(); // Obtiene la fecha de ayer
                                    $no_asistidos = DB::table('operativos')
                                        ->leftJoin('registropagos', function ($join) use ($fechaEspecifica) {
                                            $join->on(DB::raw('CAST(operativos.idempresa AS INTEGER)'), '=', 'registropagos.idcliente')
                                                ->whereRaw("NULLIF(registropagos.fecha, '')::DATE = ?", [$fechaEspecifica]); // Evitar errores con fechas vacías
                                        })
                                        ->whereNull('registropagos.idcliente') // Solo los que no pagaron
                                        ->whereRaw("NULLIF(operativos.fecha, '')::DATE = ?", [$fechaEspecifica]) // Filtrar operativos del día anterior
                                        ->orderBy('operativos.area', 'asc')
                                        ->select('operativos.*')
                                        ->distinct()
                                        ->get();


                                    foreach ($no_asistidos as $llamada) {
                                        try {
                                            $cuentawss = TelefonoWss::where('conectado', 'CC')
                                                ->where('sucursal', $llamada->area)
                                                ->where('modo', 'P')
                                                ->first();

                                            // Reemplazar "@nombre" con el nombre real de la empresa en el comentario
                                            $mensaje = str_replace('@nombre', $llamada->empresa, $campana->comentario);

                                            if ($campana->path) {
                                                $response = Http::post('http://localhost:3000/send-message', [
                                                    'sessionName' => $cuentawss->id, // Evita error si $cuentawss es null
                                                    'toPhoneNumber' => $llamada->telefono,
                                                    'messageBody' => $mensaje, // Usa el comentario con el nombre reemplazado
                                                    'imagePath' => 'C:/xampp/htdocs/miora-project/public/storage/' . $campana->path
                                                ]);
                                            } else {
                                                $response = Http::post('http://localhost:3000/send-message', [
                                                    'sessionName' => $cuentawss->id,
                                                    'toPhoneNumber' => $llamada->telefono,
                                                    'messageBody' => $mensaje,
                                                ]);
                                            }
                                        } catch (\Exception $e) {
                                            continue; // Si hay error, sigue con la siguiente iteración
                                        }
                                    }
                                }
                            }
                        }
                    }
                    if ($publicidad->frecuencia == "frec2") {
                    }
                    if ($publicidad->frecuencia == "frec3") {
                    }
                }
                if ($publicidad->enviosucursal == 'suc2') {
                    if ($publicidad->frecuencia == "frec0") {
                        if ($publicidad->fechainicio == $fechaActual && $publicidad->hora == $horaActual) {
                            if ($campana->path) {
                                $response = Http::post('http://localhost:3000/send-message', [
                                    'sessionName' => $publicidad->idcuenta,
                                    'toPhoneNumber' =>  $publicidad->numero,
                                    'messageBody' => $campana->comentario,
                                    'imagePath' => 'C:/xampp/htdocs/miora-project/public/storage/' . $campana->path
                                ]);
                            } else {
                                $response = Http::post('http://localhost:3000/send-message', [
                                    'sessionName' => $publicidad->idcuenta,
                                    'toPhoneNumber' =>  $publicidad->numero,
                                    'messageBody' => $campana->comentario,
                                ]);
                            }
                        }
                    }
                    if ($publicidad->frecuencia == "frec1") {
                        $campana = campana::find($publicidad->idcampana);
                        $fechainicio = new DateTime($publicidad->fechainicio); // Convierte la fecha de inicio en un objeto DateTime
                        $fechafin = new DateTime($publicidad->fechafin);       // Convierte la fecha de fin en un objeto DateTime
                        $fechaActual = new DateTime();                         // Obtiene la fecha actual

                        if ($fechainicio <= $fechaActual && $fechafin >= $fechaActual) {
                            if ($campana->path) {
                                $response = Http::post('http://localhost:3000/send-message', [
                                    'sessionName' => $publicidad->idcuenta,
                                    'toPhoneNumber' =>  $publicidad->numero,
                                    'messageBody' => $campana->comentario,
                                    'imagePath' => 'C:/xampp/htdocs/miora-project/public/storage/' . $campana->path
                                ]);
                            } else {
                                $response = Http::post('http://localhost:3000/send-message', [
                                    'sessionName' => $publicidad->idcuenta,
                                    'toPhoneNumber' =>  $publicidad->numero,
                                    'messageBody' => $campana->comentario,
                                ]);
                            }
                        }
                    }
                    if ($publicidad->frecuencia == "frec2") {
                        $campana = campana::find($publicidad->idcampana);
                        $fechainicio = new DateTime($publicidad->fechainicio);
                        $fechaActual = new DateTime(); // Obtiene la fecha actual
                        // Agregar un mes a la fecha de inicio
                        $fechainicio->modify('+1 week');
                        // Comparar si la fecha modificada es igual a la fecha actual
                        if ($fechainicio->format('Y-m-d') == $fechaActual->format('Y-m-d')) {
                            if ($campana->path) {
                                $response = Http::post('http://localhost:3000/send-message', [
                                    'sessionName' => $publicidad->idcuenta,
                                    'toPhoneNumber' =>  $publicidad->numero,
                                    'messageBody' => $campana->comentario,
                                    'imagePath' => 'C:/xampp/htdocs/miora-project/public/storage/' . $campana->path
                                ]);
                            } else {
                                $response = Http::post('http://localhost:3000/send-message', [
                                    'sessionName' => $publicidad->idcuenta,
                                    'toPhoneNumber' =>  $publicidad->numero,
                                    'messageBody' => $campana->comentario,
                                ]);
                            }
                        }
                    }
                    if ($publicidad->frecuencia == "frec3") {
                        $campana = campana::find($publicidad->idcampana);
                        $fechainicio = new DateTime($publicidad->fechainicio);
                        $fechaActual = new DateTime(); // Obtiene la fecha actual
                        $fechainicio->modify('+1 month');
                        // Comparar si la fecha modificada es igual a la fecha actual
                        if ($fechainicio->format('Y-m-d') == $fechaActual->format('Y-m-d')) {
                            if ($campana->path) {
                                $response = Http::post('http://localhost:3000/send-message', [
                                    'sessionName' => $publicidad->idcuenta,
                                    'toPhoneNumber' =>  $publicidad->numero,
                                    'messageBody' => $campana->comentario,
                                    'imagePath' => 'C:/xampp/htdocs/miora-project/public/storage/' . $campana->path
                                ]);
                            } else {
                                $response = Http::post('http://localhost:3000/send-message', [
                                    'sessionName' => $publicidad->idcuenta,
                                    'toPhoneNumber' =>  $publicidad->numero,
                                    'messageBody' => $campana->comentario,
                                ]);
                            }
                        }
                    }
                }
            } catch (\Exception $e) {
                continue; // Si hay error, sigue con la siguiente iteración
            }
        }
    }
}
