<?php

namespace App\Http\Livewire\Crm;

use App\Http\Livewire\Area;
use Livewire\Component;
use App\Models\Areas;
use App\Models\cuentacomercial;
use App\Models\JsonGuardado;
use App\Models\Tratamiento;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use App\Models\registroinventario;
use App\Models\Productos;
use App\Models\publicidades;
use App\Models\tarjetas;
use App\Models\transacciones;
use App\Models\TelefonoWss;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Http;
use App\Models\MensajesWss;
use App\Models\Msgpre;
use Livewire\WithFileUploads;

class PanelMensajes extends Component
{
    use WithPagination;
    use WithFileUploads;
    public $botonRecepcion = 'transacciones';
    public $areaseleccionada;
    public $usuarioseleccionado;
    public $cuentaseleccionado;
    public $fechaInicioMes;
    public $fechaActual;
    public $areas;
    public $cuentas;
    public $tarjetas;
    public $creartransaccion = false;
    public $tarjetaseleccionada = "";
    public $tipode = "";
    public $sumasaldo;
    public $sumasaldomi = 0;
    public $saldodistribuido = 0;
    public $saldodingresado = 0;
    public $saldotarjetas = 0;
    public $publicidadActivas = 0;
    public $opcion = 0;
    public $crearnumero = false;
    public $sessionName;
    public $qrCode;
    public $telefono = "591";
    public $sucursal;
    public $telefonos;
    public $cargando = true;
    public $abrirqr = false;
    public $idtelefono;
    public $tratamientos;
    public $tratamientoselect = "todas";
    public $sucursalselect = "";
    public $nombretel = "";
    public $jsonchat;
    public $cargarjson = true;
    public $verchat = false;
    public $chatabierto;
    public $mensajewss;
    public $idmensaje = 0;
    public $crearmensaje = false;
    public $mensajepredeterminado = "";
    public $palabras;
    public $mensajescargados;
    public $mensajeescrito;
    public $telefonoseleccionado;
    public $busqueda = '';
    public $mensajeprueba = "";
    public $telefonoprueba = "591";
    public $probar = false;
    public $telefonoseleccionadoprueba;
    public $probartodo = false;
    public $image;
    public $tot;
    public function asignartelefono($idtelefono)
    {
        $this->telefonoseleccionadoprueba = TelefonoWss::find($idtelefono);
        $this->probar = true;
    }
    protected $listeners = [
        'waitForQRCode' => 'waitForQRCode', // Escucha para obtener el QR
        'waitForConnection' => 'waitForConnection',
        'render' => 'render',
        'actualizarTratamiento' => 'actualizarTratamiento',
        'abrirChat' => 'abrirChat',
        'SenalChat' => 'SenalChat'
    ];
    public $suggestions = []; // Sugerencias de mensajes predeterminados

    // Mensajes predeterminados que pueden ser filtrados
    protected $predeterminedMessages = [
        '/limpieza' => 'Limpieza general de la casa',
        '/mantenimiento' => 'Mantenimiento preventivo',
        '/atencion' => 'Atención al cliente disponible',
        '/reparacion' => 'Reparación de electrodomésticos',
        // Añade más aquí
    ];
    public function guardarmensaje()
    {
        $nuevo = new Msgpre;
        $nuevo->mensaje = $this->mensajepredeterminado;
        $nuevo->sucursal = $this->sucursal;
        $nuevo->palabras = $this->palabras;
        $nuevo->estado = "Activo";
        $nuevo->save();
        $this->render();
        $this->emit('alert', '¡Mensaje creado satisfactoriamente!');
    }
    // Función que se llama cuando el texto cambia en el textarea
    public function updatedMensajewss()
    {
        // Si el mensaje empieza con '/'
        if (substr($this->mensajewss, 0, 1) === '/') {
            $keyword = substr($this->mensajewss, 1); // Extraer la palabra después de '/'

            // Filtrar los mensajes predeterminados que contienen la palabra
            $this->suggestions = array_filter($this->predeterminedMessages, function ($message) use ($keyword) {
                return strpos(strtolower($message), strtolower($keyword)) !== false;
            });

            // Limitar el número de sugerencias a 2
            $this->suggestions = array_slice($this->suggestions, 0, 2);
        } else {
            $this->suggestions = []; // Si no empieza con '/', no hay sugerencias
        }
    }
    public function colocarMensaje($idmensaje)
    {
        $mensaje = Msgpre::find($idmensaje);
        $this->mensajewss = $mensaje->mensaje;
        $this->render();
    }
    // Función para seleccionar una sugerencia
    public function selectSuggestion($suggestion)
    {
        // Llenar el textarea con el mensaje seleccionado y limpiar el input actual
        $this->mensajewss = $this->predeterminedMessages[$suggestion];
        $this->suggestions = []; // Limpiar las sugerencias después de seleccionar una
    }
    public function render()
    {
        $this->tot = DB::table('publicidades')->where('estado', 'Activo')->get();
        $this->mensajescargados = Msgpre::where('palabras', 'ilike', '%' . $this->mensajeescrito . '%')->get();
        $this->tratamientos = Tratamiento::where('estado', 'Activo')->orderBy('nombre')->get();
        if (Auth::user()->rol == 'Recepcion') {
            $this->telefonos = TelefonoWss::where('estado', 'Activo')->where('sucursal',  'ilike', '%' . Auth::user()->sucursal)->get();
            if ($this->telefonoseleccionado == '') {
                $mensajes = MensajesWss::join('telefono_wsses', 'mensajes_wsses.receptor', '=', 'telefono_wsses.id')
                    ->where('telefono_wsses.sucursal',  'ilike', '%' . Auth::user()->sucursal);
                if ($this->busqueda != '') {
                    $mensajes->where('mensajes_wsses.emisor', 'ilike', '%' . $this->busqueda . '%');
                }
                $mensajes = $mensajes->orderBy('mensajes_wsses.updated_at', 'desc')
                    ->select('mensajes_wsses.*', 'telefono_wsses.sucursal')
                    ->limit(50)
                    ->get();
            } else {
                $mensajes = MensajesWss::join('telefono_wsses', 'mensajes_wsses.receptor', '=', 'telefono_wsses.id')
                    ->where('telefono_wsses.sucursal',  'ilike', '%' . Auth::user()->sucursal);
                if ($this->busqueda != '') {
                    $mensajes->where('mensajes_wsses.emisor', 'ilike', '%' . $this->busqueda . '%');
                }
                $mensajes = $mensajes->where('mensajes_wsses.receptor', $this->telefonoseleccionado)
                    ->orderBy('mensajes_wsses.updated_at', 'desc')
                    ->select('mensajes_wsses.*', 'telefono_wsses.sucursal')
                    ->limit(50)
                    ->get();
            }
        } else {
            $this->telefonos = TelefonoWss::where('estado', 'Activo')->get();
            if ($this->telefonoseleccionado == '') {
                $mensajes = MensajesWss::join('telefono_wsses', 'mensajes_wsses.receptor', '=', 'telefono_wsses.id');
                if ($this->busqueda != '') {
                    $mensajes->where('mensajes_wsses.emisor', 'ilike', '%' . $this->busqueda . '%');
                }
                $mensajes = $mensajes->orderBy('mensajes_wsses.updated_at', 'desc')
                    ->select('mensajes_wsses.*', 'telefono_wsses.sucursal')
                    ->limit(50)
                    ->get();
            } else {
                $mensajes = MensajesWss::join('telefono_wsses', 'mensajes_wsses.receptor', '=', 'telefono_wsses.id')
                    ->where('mensajes_wsses.receptor', $this->telefonoseleccionado);

                if ($this->busqueda != '') {
                    $mensajes->where('mensajes_wsses.emisor', 'ilike', '%' . $this->busqueda . '%');
                }

                $mensajes = $mensajes->orderBy('mensajes_wsses.updated_at', 'desc')
                    ->select('mensajes_wsses.*', 'telefono_wsses.sucursal')
                    ->limit(50)
                    ->get();
            }
        }



        $this->areas = Areas::where('estado', 'Activo')->where(function ($query) {
            $query->whereNull('almacen')
                ->orWhere('almacen', '');
        })->orderBy('id')->get();
        return view('livewire.crm.panel-mensajes', compact('mensajes'));
    }
    public function eliminarnum($idnumero)
    {
        $numero = TelefonoWss::find($idnumero);
        $numero->delete();
        $this->emit('alert', '¡Número eliminado satisfactoriamente!');
    }
    public function setOpcion($numero)
    {
        $this->opcion = $numero;
        $this->render();
    }
    public function actualizarTratamiento($mensajeId, $tratamientoId)
    {
        // Busca el mensaje correspondiente
        $mensaje = MensajesWss::find($mensajeId);

        if ($mensaje) {
            // Actualiza el tratamiento asociado al mensaje (o realiza cualquier lógica)
            $mensaje->categoria = $tratamientoId;
            $mensaje->save();

            // Opcional: Retorna un mensaje de éxito
            session()->flash('success', 'Tratamiento actualizado correctamente.');
        } else {
            session()->flash('error', 'El mensaje no fue encontrado.');
        }
    }
    public function guardartodo()
    {
        $nuevo = new  TelefonoWss;
        $nuevo->idsucursal = $this->sucursal;
        $area = Areas::find($this->sucursal);
        $nuevo->sucursal = $area->area;
        $nuevo->telefono = $this->telefono;
        $nuevo->conectado = 'SC';
        $nuevo->iduser = Auth::user()->id;
        $nuevo->responsable = Auth::user()->name;
        $nuevo->estado = "Activo";
        $nuevo->nombre = $this->nombretel;
        $nuevo->save();
        $this->crearnumero = false;
        $this->emit('alert', '¡Número creado satisfactoriamente!');
        $this->render();
    }
    public function desconectarnumero($idtelefono)
    {

        $telefono = TelefonoWss::find($idtelefono);
        $telefono->conectado = "SC";
        $telefono->save();
        $this->render();
    }
    public function checkSessionStatus()
    {
        $response = Http::get('http://localhost:3000/session-status/' . $this->sessionName);

        if ($response->successful()) {
            $data = $response->json();

            if (isset($data['status'])) {
                $status = $data['status'];

                if ($status === 'CONNECTED') {
                    $telefono = TelefonoWss::find($this->idtelefono);
                    $telefono->conectado = "CC";
                    $telefono->save();
                    $this->render();

                    $this->abrirqr = false;
                    $this->qrCode = null;
                    $this->cargando = false;
                    session()->flash('success', 'Sesión conectada correctamente');
                    $this->emit('alert', '¡Sesión iniciada correctamente!');
                } else {
                    session()->flash('warning', 'Esperando conexión...');
                }
            } else {
                $this->abrirqr = false;
                $this->qrCode = null;
                $this->cargando = false;
                session()->flash('error', 'Respuesta inválida del servidor');
            }
        } else {
            $this->abrirqr = false;
            $this->qrCode = null;
            $this->cargando = false;
            session()->flash('error', 'Error al verificar el estado de la sesión: ' . $response->body());
            $this->qrCode = null;
        }
    }


    public function iniciarQr()
    {
        $this->abrirqr = true;
        $this->render();
    }
    public function waitForConnection()
    {
        $maxAttempts = 30;
        $attempts = 0;

        while ($attempts < $maxAttempts) {
            $this->checkSessionStatus();
            if (session()->has('success')) {
                break; // Salir si ya está conectado
            }

            sleep(3); // Espera 3 segundos entre intentos
            $attempts++;
        }

        if ($attempts >= $maxAttempts) {
            $this->abrirqr = false;
            $this->qrCode = null;
            $this->cargando = false;
            $this->render();
            session()->flash('error', 'No se pudo conectar la sesión en el tiempo esperado.');
            $this->qrCode = null;
        }
    }

    public function abrirChat($idsesion, $idnumero, $idmensaje)
    {
        // Llama al endpoint para iniciar la sesión
        $response = Http::post('http://localhost:3000/get-last-messages', [
            'sessionName' => $idsesion,
            'toPhoneNumber' => $idnumero . '@c.us'
        ]);

        if ($response->successful()) {
            $this->chatabierto = MensajesWss::find($idmensaje);
            $this->jsonchat = $response->json(); // Obtienes el JSON como array
            // dd($response->json());

            // Convertir el array a JSON antes de guardarlo
            $jsonchatStr = json_encode($this->jsonchat);
            $this->jsonchat = $jsonchatStr;
            $this->jsonchat = json_decode($jsonchatStr, true);;
            $jsonant = JsonGuardado::where('iduser', Auth::user()->id)->first();
            if ($jsonant) {
                $jsonant->json = $jsonchatStr; // Guardar la cadena JSON
                $jsonant->save();
                $this->render();
                // dd($response->json());
            } else {
                $jsonnuevo = new JsonGuardado;
                $jsonnuevo->iduser = Auth::user()->id;
                $jsonnuevo->json = $jsonchatStr; // Guardar la cadena JSON
                $jsonnuevo->save();
                $this->render();
            }
        } else {
            $this->render();
            session()->flash('error', 'No se pudo obtener el chat');
            $this->cargarjson = false; // Termina la carga
        }
    }
    public function enviarMss($telefono, $sesion)
    {
        if ($this->mensajewss != "") {
            $numeroWhatsapp = $telefono . '@c.us';
            $response = Http::post('http://localhost:3000/send-message', [
                'sessionName' => $sesion,
                'toPhoneNumber' => $numeroWhatsapp,
                'messageBody' => $this->mensajewss,
            ]);
        }
        $this->mensajewss = "";
        $this->abrirChat($sesion, $telefono, $this->chatabierto->id);
    }
    public function SenalChat()
    {
        if (isset($this->chatabierto)) {
            $this->abrirChat($this->chatabierto->receptor, $this->chatabierto->emisor, $this->chatabierto->id);
        }
    }
    public function elegirprincipal($idtelefono)
    {
        $telefono = TelefonoWss::find($idtelefono);
        $telefonos = TelefonoWss::where('sucursal', $telefono->sucursal)->get();
        foreach ($telefonos as $telefono) {
            $telefono->modo = "";
            $telefono->save();
        }
        $telefono = TelefonoWss::find($idtelefono);
        $telefono->modo = "P";
        $telefono->save();
        $this->render();
        $this->emit('alert', '¡Número creado Actualizado!');
    }
    public function startSession($idtelefono)
    {
        $this->abrirqr = true;
        $this->sessionName = $idtelefono;
        $this->idtelefono = $idtelefono;

        // Llama al endpoint para iniciar la sesión
        $response = Http::post('http://localhost:3000/start-session', [
            'sessionName' => $idtelefono,
        ]);

        if ($response->successful()) {
            // Espera el QR de manera asíncrona
            $this->emit('waitForQRCode');
        } else {
            session()->flash('error', 'No se pudo iniciar la sesión');
            $this->cargando = false; // Termina la carga
        }
    }


    public function waitForQRCode()
    {
        $maxAttempts = 50;
        $attempts = 0;
        $qrUrl = 'http://localhost:3000/get-qr/' . $this->sessionName;

        while ($attempts < $maxAttempts) {
            $attempts++;

            // Verifica si el QR ya está disponible
            $response = Http::get($qrUrl);

            if ($response->successful()) {
                $this->qrCode = $response->json()['qrCode'];
                $this->cargando = false; // Termina la carga
                $this->dispatchBrowserEvent('qr-received', ['qrCode' => $this->qrCode]);
                $this->emit('waitForConnection');
                break;
            } else {
                sleep(3); // Espera 3 segundos
            }
        }

        // Manejo de error si el QR no se obtuvo
        if (!$this->qrCode) {
            $this->abrirqr = false;
            $this->qrCode = null;
            $this->cargando = false;
            $this->render();
            session()->flash('error', 'No se pudo obtener el QR. Inténtalo de nuevo.');
            $this->cargando = false;
        }
    }
    public function  pruebamensajetodo()
    {
        $telefonos = TelefonoWss::get();
        foreach ($telefonos as $telefono) {
            $response = Http::post('http://localhost:3000/send-message', [
                'sessionName' => $telefono->id,
                'toPhoneNumber' => $this->telefonoprueba,
                'messageBody' => $this->mensajeprueba,
            ]);

            if ($response->successful()) {
            } elseif ($response->failed()) {
                // Hubo un error en la solicitud
                $this->emit('error', '¡Error de envio!');
            }
        }
    }
    public function pruebamensaje()
    {
        try {
            if ($this->image) {

                //      // Obtener la ruta temporal de la imagen cargada
                // $temporaryPath = $this->image->getRealPath();

                // // Ruta de destino final
                // $destinationPath = public_path('storage/wss');
                // $imageName = uniqid() . '.' . $this->image->getClientOriginalExtension(); // Nombre único

                // // Crear la carpeta si no existe
                // if (!file_exists($destinationPath)) {
                //     mkdir($destinationPath, 0755, true);
                // }

                // // Mover la imagen a la carpeta de destino
                // $finalPath = $destinationPath . DIRECTORY_SEPARATOR . $imageName;

                // if (move_uploaded_file($temporaryPath, $finalPath)) {
                //     session()->flash('success', "Imagen guardada correctamente en: $finalPath");
                // } else {
                //     session()->flash('error', 'No se pudo mover la imagen al destino.');
                // }

                $image = $this->image->store('public/wss');
                $image = 'wss/' . basename($image);
                $response = Http::post('http://localhost:3000/send-message', [
                    'sessionName' => $this->telefonoseleccionadoprueba->id,
                    'toPhoneNumber' => $this->telefonoprueba,
                    'messageBody' => $this->mensajeprueba,
                    'imagePath' => 'C:/xampp/htdocs/miora-project/public/storage/' . $image
                ]);
            } else {
                $response = Http::post('http://localhost:3000/send-message', [
                    'sessionName' => $this->telefonoseleccionadoprueba->id,
                    'toPhoneNumber' => $this->telefonoprueba,
                    'messageBody' => $this->mensajeprueba,
                ]);
            }
            if ($response->successful()) {
                $this->emit('alert', '¡Enviado correctamente!');
            } elseif ($response->failed()) {
                // Hubo un error en la solicitud
                $this->emit('error', '¡Error de envio!');
            }
        } catch (\Exception $e) {
            $this->emit('alert', '¡Enviado de servidor!');
        }
    }
}
