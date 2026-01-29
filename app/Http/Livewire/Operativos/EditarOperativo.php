<?php

namespace App\Http\Livewire\Operativos;

use App\Events\EnviarMensaje;
use App\Models\Areas;
use App\Models\Tratamiento;
use App\Models\HistorialCliente;
use App\Models\Operativos;
use App\Models\Pagos;
use App\Models\registropago;
use App\Models\TratamientoCliente;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use  App\Models\registrollamadas;
use App\Models\Calls;
use App\Models\TelefonoWss;
use Illuminate\Validation\Rules\Exists;
use Illuminate\Support\Facades\Http;
use Livewire\WithPagination;
use Twilio\Rest\Api\V2010\Account\Usage\Record\ThisMonthList;

class EditarOperativo extends Component
{
    use WithPagination;
    public $operativo;
    public $mododepago = "Efectivo";
    public $elegido;
    public $openArea = false;
    public $openArea2 = false;
    public $openArea3 = false;
    public $openArea4 = false;
    public $nombre;
    public $telefono;
    public $cantidad;
    public $fecha = "";
    public $hora = "";
    public $minuto = "";
    public $openArea5 = false;
    public $user;
    public $tratamientos;
    public $editar = false;
    public $tratamientosSeleccionados = [];
    public $tratamientosSeleccionadosvacios = [];
    public $cantidadpago = 0;
    public $cantidadpagototal;
    public $cuotasmanual = 1;
    public $cuota1 = 0;
    public $cuota2;
    public $cuota3;
    public $cuota4;
    public $haycuota = false;
    public $deuda;
    public $todoslospagos;
    public $pagototal = 0;
    public $mistratamientos;
    public $agreagar = false;
    public $mistratamientospara;
    public $tratamientosSeleccionadosnuevos = [];
    public $busquedatratamiento = "";
    public $pagonuevo = 0;
    public $total = 0;
    public $valores = [];
    public $primerafecha;
    public $pagos = 'uno';
    public $ffff = false;
    public $mostrarbotones = false;
    public $comentariollamada;
    public $pagoefectivo = 0;
    public $pagoqr = 0;
    public $pagotratamientostotal = 0;
    public $descuentotratamientos = 0;
    public $info = false;
    public $idtratamientoseleccionado;
    public $totalpagado = 0;
    public $eliminar = false;
    public $opcionoperativo = 0;
    public $openWss = false;
    public $chatabierto;
    public $jsonchat;
    public $cargarjson = false;
    public $numerousar;
    public $mensajewss = "";
    public $openProblema=false;
    public $motivoProblema="";
    protected $rules = [
        'operativo.fecha'  => 'sometimes|required',
        'operativo.area' => 'sometimes|required',
        'operativo.telefono' => 'sometimes|required',
        'operativo.empresa' => 'sometimes|required',
        'operativo.comentario' => 'sometimes|required',
        'operativo.responsable' => 'sometimes|required',
        'operativo.estado' => 'sometimes|required',
        'operativo.encargado' => 'sometimes|required',
        'pagototal' => 'sometimes|required',
        'cuota1' => 'sometimes|required',
        'cantidadpago' => 'sometimes|required',
        'hora' => 'sometimes|required',
        'minuto' => 'sometimes|required',
    ];

    protected $listeners = ['guardarProblema'=>'guardarProblema','eliminarTratamiento' => 'eliminarTratamiento', 'render' => 'render', 'inactivar' => 'inactivar', 'activar' => 'activar', 'confirmar' => 'confirmar', 'inputValueChanged' => 'updatedValoresFromJS', 'rellamarnum' => 'rellamarnum'];

    public function guardarProblema(){

        $this->operativo->tipoproblema= $this->motivoProblema;
        $this->operativo->problema="si";
        $this->operativo->save();
        $this->openProblema=false;
        $this->emit('alert', '¡Guardado correctamente!');
    }
    public function liberarProblema(){

        $this->operativo->problema="no";
        $this->operativo->save();
        $this->emit('alert', '¡Liberado correctamente!');
    }
    public function eliminarVista($idtratamiento)
    {
        $this->idtratamientoseleccionado = $idtratamiento;
        $this->editar = false;
        $this->eliminar = true;
    }
    public function setOpcion($num)
    {
        $this->opcionoperativo = $num;
    }
    public function abrirChat($idnumero)
    {
        $idnumero = trim($idnumero);

        // Llama al endpoint para iniciar la sesión
        $response = Http::post('http://localhost:3000/get-last-messages', [
            'sessionName' => $this->numerousar->id,
            'toPhoneNumber' => $idnumero . '@c.us'
        ]);

        if ($response->successful()) {

            $this->jsonchat = $response->json(); // Obtienes el JSON como array
            // Convertir el array a JSON antes de guardarlo
            $jsonchatStr = json_encode($this->jsonchat);
            $this->jsonchat = $jsonchatStr;
            $this->jsonchat = json_decode($jsonchatStr, true);;
        } else {
            $this->render();
            session()->flash('error', 'No se pudo obtener el chat');
            $this->cargarjson = false; // Termina la carga
        }
        $this->openWss = true;
    }
    public function enviarMss($telefono)
    {
        $response = Http::post('http://localhost:3000/send-message', [
            'sessionName' =>  $this->numerousar->id,
            'toPhoneNumber' => trim($telefono),
            'messageBody' => $this->mensajewss,
        ]);
        $this->mensajewss = "";
        $this->abrirChat($telefono);
    }
    public function problematico(){

    }
    public function eliminarTratamiento()
    {
        $tratamientohistorial = HistorialCliente::find($this->idtratamientoseleccionado);
        $pago = Pagos::where('idoperativo', $this->operativo->id)->first();
        if ($pago) {
            if ($pago->cantidad > 0 && $pago->cantidad != "") {
                $pago->cantidad = (float) $pago->cantidad - (float) $tratamientohistorial->costo;
            }
            $pago->save();
        }
        $tratamientohistorial->delete();

        $this->editar = true;
        $this->eliminar = false;
        $this->render();
    }
    public function mount(Operativos $operativo)
    {
        $this->mensajewss = "Buenos dias estimad@ " . $operativo->empresa . ", le hablamos del Spa Medic Miora. Quería preguntarle que tal le atendieron en el Spa? Si tuviera alguna observación por favor,  del 1 al 10 cuanto calificaría la atención 😊
        ";
        $this->numerousar =  TelefonoWss::where('conectado', 'CC')
            ->where('sucursal', $operativo->area)
            ->where('modo', 'P')
            ->first();
        $this->operativo = $operativo;
        $this->total = DB::table('pagos')
            ->where('idoperativo', $this->operativo->id)
            ->sum('cantidad');
        $this->primerafecha = $this->operativo->fecha;
    }
    public function render()
    {
        $pago = Pagos::where('idoperativo', $this->operativo->id)->first();
        if ($pago) {
            $this->haycuota = true;
        }
        $this->deuda = Pagos::where('idoperativo', $this->operativo->id)->where('estado', 'Pendiente')->sum('cantidad') - Pagos::where('idoperativo', $this->operativo->id)->where('estado', 'Pendiente')->sum('pagado');
        $this->tratamientos = HistorialCliente::where('idoperativo', $this->operativo->id)->get();
        $this->totalpagado = Pagos::where('idoperativo', $this->operativo->id)->where('estado', 'Pendiente')->sum('pagado');
        $this->mistratamientospara = Tratamiento::where('estado', 'Activo')->where('nombre', 'ilike', '%' . $this->busquedatratamiento . '%')->orderBy('nombre', 'asc')->get();
        $this->todoslospagos = Pagos::where('idoperativo', $this->operativo->id)->where('estado', 'Pendiente')->count();
        $this->mistratamientos = HistorialCliente::where('estado', 'Activo')->where('idoperativo', $this->operativo->id)->get();
        $tratamientoscliente = HistorialCliente::where('idoperativo', $this->operativo->id)->get();
        $this->pagotratamientostotal = 0;
        if ($this->haycuota) {
            $pago = Pagos::where('idoperativo', $this->operativo->id)->first();
            $this->pagotratamientostotal = $pago->cantidad;
        } else {
            foreach ($tratamientoscliente as $mistrata) {
                $this->pagotratamientostotal  = $this->pagotratamientostotal + $mistrata->costo;
            }
        }
        $areas = Areas::where('estado', 'Activo')->orderBy('id', 'desc')->get();
        $users = User::where('estado', 'Activo')->where('rol', '!=', 'Cliente')->orderBy('name', 'asc')->get();
        return view('livewire.operativos.editar-operativo', compact('areas', 'users'));
    }
    public function restablecer()
    {
        $pago = Pagos::where('idoperativo', $this->operativo->id)->first();
        if ($pago) {
            $pago->delete();
        }

        $tratamientos = HistorialCliente::where('idoperativo', $this->operativo->id)->get();
        $pagos = registropago::where('idoperativo', $this->operativo->id)->get();
        foreach ($pagos as $pago) {
            $pago->delete();
        }
        foreach ($tratamientos as $tratamiento) {
            $tratamiento->delete();
        }
        $this->haycuota = false;
        $this->emitTo('recepcionista.lista-recepcion', 'render');
        $this->emit('alert', '¡Cita restablecida!');
    }
    public function eliminarhistorial($idtratamiento)
    {
        $historial = HistorialCliente::find($idtratamiento);
        $historial->delete();
    }
    public function toggleTratamiento($id)
    {
        if (in_array($id, $this->tratamientosSeleccionadosvacios)) {
            $this->tratamientosSeleccionadosvacios = array_diff($this->tratamientosSeleccionadosvacios, [$id]);
        } else {
            $this->tratamientosSeleccionadosvacios[] = $id;
        }
        if (in_array($id, $this->tratamientosSeleccionados)) {
            $this->tratamientosSeleccionados = array_diff($this->tratamientosSeleccionados, [$id]);
        } else {
            $this->tratamientosSeleccionados[] = $id;
        }
    }
    public function updatedValores($value, $key)
    {
        // Separar la clave en id y nombre del tratamiento
        [$id, $nombre] = explode('.', $key);

        // Guardar el valor en el array $valores
        $this->valores[$id][$nombre] = $value;
    }
    public function agregartratamientos()
    {
        $this->operativo->cantidadtotal = 0;
        $this->operativo->save();
        $deudapago = Pagos::where('estado', 'Pendiente')->where('idoperativo', $this->operativo->id)->first();
        if ($deudapago) {
            $deudapago->cantidad = $deudapago->cantidad + $this->pagonuevo;
            $deudapago->save();
        } else {
            $pago = new Pagos;
            $pago->estado = 'Pendiente';
            $pago->area = $this->operativo->area;
            $pago->iduser = $this->operativo->idempresa;
            $pago->fecha = Carbon::now()->toDateString();
            $pago->cantidad = $this->pagonuevo;
            $pago->pagado = 0;
            $pago->idoperativo = $this->operativo->id;
            $pago->save();
        }

        foreach ($this->tratamientosSeleccionadosnuevos as $elemento) {
            $tratamiento = Tratamiento::find($elemento);
            if ($tratamiento) {
                $nuevo = new HistorialCliente;
                $nuevo->idtratamiento = $tratamiento->id;
                $nuevo->idllamada = "1";
                $nuevo->nombretratamiento = $tratamiento->nombre;
                $nuevo->idcliente = $this->operativo->idempresa;
                $nuevo->costo = $tratamiento->costo;
                $nuevo->fecha =  Carbon::now()->toDateString();
                $nuevo->idtratamientocliente = 1;
                $nuevo->idoperativo = $this->operativo->id;
                $nuevo->estado = 'Inactivo';
                $nuevo->save();
            }
        }
        $this->tratamientosSeleccionadosnuevos = [];
        $this->emit('alert', '¡Tratamiento agregado satisfactoriamente!');

        $this->reset(['openArea3', 'tratamientosSeleccionadosnuevos']);
        $this->render();
        $this->emitTo('operativos.lista-operativo', 'render');
        $this->emitTo('recepcionista.lista-recepcion', 'render');
    }
    public function inactivar($idCall)
    {
        $operativo = Operativos::find($idCall);
        $operativo->ingreso = 1;
        $operativo->cantidadaregistrar = Auth::user()->name;
        $operativo->save();
        $this->reset(['openArea2']);
        $this->emitTo('registros.reg-citas', 'render');
        $this->emitTo('recepcionista.lista-recepcion', 'render');
        $this->emit('alert', '!Cita enviada a verificacion para eliminar!');
    }
    public function pagar()
    {
        $operativo = $this->operativo;
        $registro = new registropago;
        $sucursal = Areas::where('area', $operativo->area)->first();
        $registro->idsucursal = $sucursal->id;
        $registro->sucursal = $sucursal->area;
        $registro->idoperativo = $this->operativo->id;
        $registro->nombrecliente = $this->operativo->empresa;
        $registro->monto = $this->cantidadpago;
        $registro->iduser = Auth::user()->id;
        $registro->responsable = Auth::user()->name;
        $registro->idcliente = $this->operativo->idempresa;
        $registro->fecha = date('Y-m-d');
        $registro->modo = $this->mododepago;
        $registro->motivo = "operativo";
        $registro->estado = 'Activo';
        $cosmetologa = User::find($this->elegido);
        $registro->idcosmetologa = $cosmetologa->id;
        $registro->cosmetologa = $cosmetologa->name;
        $registro->save();
    }
    public function confirmarasistencia()
    {
        $operativo = $this->operativo;
        $registro = new registropago;
        $sucursal = Areas::where('area', $operativo->area)->first();
        $registro->idsucursal = $sucursal->id;
        $registro->sucursal = $sucursal->area;
        $registro->idoperativo = $this->operativo->id;
        $registro->nombrecliente = $this->operativo->empresa;
        $registro->monto = "0";
        $registro->iduser = Auth::user()->id;
        $registro->responsable = Auth::user()->name;
        $registro->idcliente = $this->operativo->idempresa;
        $registro->fecha = $this->primerafecha;
        $registro->modo = "QR";
        $registro->motivo = "operativo";
        $registro->estado = 'Activo';
        $cosmetologa = User::find($this->elegido);
        $registro->idcosmetologa = $cosmetologa->id;
        $registro->cosmetologa = $cosmetologa->name;
        $registro->save();
        $this->emitTo('recepcionista.lista-recepcion', 'render');
        $this->emit('alert', '¡Asitencia confirmada satisfactoriamente!');
    }
    public function imprimircita()
    {
        $descriptionWidth = 30;
        $cosmetologa = User::find($this->elegido);
        $recepcion = explode(' ', Auth::user()->name);
        $cosmetologa = explode(' ',  $cosmetologa->name);
        $area = Areas::find(Auth::user()->sesionsucursal);

        $text = "Ticket #: " . $area->ticket . "\nFecha: " . date('Y-m-d H:i:s') . "\nCaja: " . $this->operativo->area . "\nCliente: " . $this->operativo->empresa . "\nRecepcionista: " . implode(' ', array_slice($recepcion, 0, 2)) .  "\nCosmetóloga:" . implode(' ', array_slice($cosmetologa, 0, 2)) . "\n-----------------------------------------------------------------------------" . str_pad("\nDESCRIPCIÓN", $descriptionWidth) . "" . "PRECIO\n-----------------------------------------------------------------------------\n";

        $historiales = DB::table('historial_clientes')
            ->where('idoperativo', $this->operativo->id)
            ->get();
        $this->tratamientosSeleccionadosvacios = array_filter($this->tratamientosSeleccionadosvacios, function ($value) {
            return !empty($value);
        });
        $costo = 0;
        $lineWidth = 44;
        $pricePosition = intval($lineWidth * 0.7);

        foreach ($historiales as $tratamiento) {
            $costo += $tratamiento->costo;
            $descripcion = substr($tratamiento->nombretratamiento, 0, 20);
            $descripcion = str_pad($descripcion, $pricePosition - 1, ' ', STR_PAD_RIGHT);
            $precio = 'Bs.' . $tratamiento->costo;
            $text .= $descripcion . $precio . "\n";
        }

        $deudapago = Pagos::where('idoperativo', $this->operativo->id)->first();
        $text .= " -----------------------------------------------------------------------------\n";

        $subtotal = "Subtotal";
        $subtotal = substr($subtotal, 0, 20);
        $subtotal = str_pad($subtotal, $pricePosition - 1, ' ', STR_PAD_RIGHT);
        $precio = 'Bs.' . $costo;
        $text .= $subtotal . $precio . "\n";

        $descuento = "Descuento";
        $descuento = substr($descuento, 0, 20);
        $descuento = str_pad($descuento, $pricePosition - 1, ' ', STR_PAD_RIGHT);
        $precio = 'Bs.' . (float)$costo - (float)$deudapago->cantidad;
        $text .= $descuento . $precio . "\n";

        $totalpago = "Total";
        $totalpago = substr($totalpago, 0, 20);
        $totalpago = str_pad($totalpago, $pricePosition - 1, ' ', STR_PAD_RIGHT);
        $precio = 'Bs.' . (float)$deudapago->cantidad;
        $text .=  $totalpago . $precio . "\n";

        $pagoenqr = "Pago Qr-Efectivo";
        $pagoenqr = substr($pagoenqr, 0, 20);
        $pagoenqr = str_pad($pagoenqr, $pricePosition - 1, ' ', STR_PAD_RIGHT);
        $precio = 'Bs.' . $this->pagoqr + $this->pagoefectivo;
        $text .= $pagoenqr . $precio . "\n";

        $pendiente = "Cobro pendiente";
        $pendiente = substr($pendiente, 0, 20);
        $pendiente = str_pad($pendiente, $pricePosition - 1, ' ', STR_PAD_RIGHT);
        $precio = 'Bs.' . (float)$deudapago->cantidad - (float)$deudapago->pagado;
        $text .=  $pendiente . $precio . "\n";
        $text .= " -----------------------------------------------------------------------------\n";
        $data = [
            'id' => Auth::user()->sesionsucursal,
            'texto' => $text
        ];
        $url = "http://127.0.0.1:5001/imprimirticket";
        $response = Http::post($url, $data);
    }
    public function imprimircitaRecepcion()
    {
        $descriptionWidth = 30;
        $cosmetologa = User::find($this->elegido);
        $recepcion = explode(' ', Auth::user()->name);
        $cosmetologa = explode(' ',  $cosmetologa->name);
        $area = Areas::find(Auth::user()->sesionsucursal);
        $text = "RECEPCION--------------------------------------------------------------------\n" .
            "Ticket #: " . $area->ticket . "\nFecha: " . date('Y-m-d H:i:s') . "\nCaja: " . $this->operativo->area . "\nCliente: " . $this->operativo->empresa .  "\nTelefono: " . $this->operativo->telefono . "\nRecepcionista: " . implode(' ', array_slice($recepcion, 0, 2)) .  "\nCosmetóloga:" . implode(' ', array_slice($cosmetologa, 0, 2)) . "\n-----------------------------------------------------------------------------" . str_pad("\nDESCRIPCIÓN", $descriptionWidth) . "" . "PRECIO\n-----------------------------------------------------------------------------\n";

        $historiales = DB::table('historial_clientes')
            ->where('idoperativo', $this->operativo->id)
            ->get();
        $this->tratamientosSeleccionadosvacios = array_filter($this->tratamientosSeleccionadosvacios, function ($value) {
            return !empty($value);
        });
        $costo = 0;
        $lineWidth = 44;
        $pricePosition = intval($lineWidth * 0.7);

        foreach ($historiales as $tratamiento) {
            $costo += $tratamiento->costo;
            $descripcion = substr($tratamiento->nombretratamiento, 0, 20);
            $descripcion = str_pad($descripcion, $pricePosition - 1, ' ', STR_PAD_RIGHT);
            $precio = 'Bs.' . $tratamiento->costo;
            $text .= $descripcion . $precio . "\n";
        }

        $deudapago = Pagos::where('idoperativo', $this->operativo->id)->first();
        $text .= " -----------------------------------------------------------------------------\n";

        $subtotal = "Subtotal";
        $subtotal = substr($subtotal, 0, 20);
        $subtotal = str_pad($subtotal, $pricePosition - 1, ' ', STR_PAD_RIGHT);
        $precio = 'Bs.' . $costo;
        $text .= $subtotal . $precio . "\n";
        $descuento = "Descuento";
        $descuento = substr($descuento, 0, 20);
        $descuento = str_pad($descuento, $pricePosition - 1, ' ', STR_PAD_RIGHT);
        $precio = 'Bs.' . (float)$costo - (float)$deudapago->cantidad;
        $text .= $descuento . $precio . "\n";
        $totalpago = "Total";
        $totalpago = substr($totalpago, 0, 20);
        $totalpago = str_pad($totalpago, $pricePosition - 1, ' ', STR_PAD_RIGHT);
        $precio = 'Bs.' . (float)$deudapago->cantidad;
        $text .=  $totalpago . $precio . "\n";

        $pagoenqr = "Pago Qr";
        $pagoenqr = substr($pagoenqr, 0, 20);
        $pagoenqr = str_pad($pagoenqr, $pricePosition - 1, ' ', STR_PAD_RIGHT);
        $precio = 'Bs.' . $this->pagoqr;
        $text .= $pagoenqr . $precio . "\n";
        $pagoenqr = "Pago Efectivo";
        $pagoenqr = substr($pagoenqr, 0, 20);
        $pagoenqr = str_pad($pagoenqr, $pricePosition - 1, ' ', STR_PAD_RIGHT);
        $precio = 'Bs.' . $this->pagoefectivo;
        $text .= $pagoenqr . $precio . "\n";
        $pagoenqr = "Total Efectivo + Qr";
        $pagoenqr = substr($pagoenqr, 0, 20);
        $pagoenqr = str_pad($pagoenqr, $pricePosition - 1, ' ', STR_PAD_RIGHT);
        $precio = 'Bs.' . $this->pagoqr + $this->pagoefectivo;
        $text .= $pagoenqr . $precio . "\n";
        $text .= " -----------------------------------------------------------------------------\n";
        $data = [
            'id' => Auth::user()->sesionsucursal,
            'texto' => $text
        ];
        $url = "http://127.0.0.1:5001/imprimirticket";
        $response = Http::post($url, $data);
    }
    public function imprimircitaCosmetologa()
    {
        $descriptionWidth = 30;
        $cosmetologa = User::find($this->elegido);
        $recepcion = explode(' ', Auth::user()->name);
        $cosmetologa = explode(' ',  $cosmetologa->name);
        $area = Areas::find(Auth::user()->sesionsucursal);
        $text = "Ticket #: " . $area->ticket . "\nFecha: " . date('Y-m-d H:i:s') . "\nCaja: " . $this->operativo->area . "\nCliente: " . $this->operativo->empresa . "\nRecepcionista: " . implode(' ', array_slice($recepcion, 0, 2)) .  "\nCosmetóloga:" . implode(' ', array_slice($cosmetologa, 0, 2)) . "\n-----------------------------------------------------------------------------" . str_pad("\nDESCRIPCIÓN", $descriptionWidth) . "" . "PRECIO\n-----------------------------------------------------------------------------\n";
        $historiales = DB::table('historial_clientes')
            ->where('idoperativo', $this->operativo->id)
            ->get();
        $this->tratamientosSeleccionadosvacios = array_filter($this->tratamientosSeleccionadosvacios, function ($value) {
            return !empty($value);
        });
        $costo = 0;
        $lineWidth = 44;
        $pricePosition = intval($lineWidth * 0.7);

        foreach ($historiales as $tratamiento) {
            $costo += $tratamiento->costo;
            $descripcion = substr($tratamiento->nombretratamiento, 0, 20);
            $descripcion = str_pad($descripcion, $pricePosition - 1, ' ', STR_PAD_RIGHT);
            $precio = 'Bs.' . $tratamiento->costo;
            $text .= $descripcion . $precio . "\n";
        }


        $text .= "
        COSMETOLOGA:


        FIRMA:------------------------------------------------------------------------

        -----------------------------------------------------------------------------\n";
        $data = [
            'id' => Auth::user()->sesionsucursal,
            'texto' => $text
        ];
        $url = "http://127.0.0.1:5001/imprimirticket";
        $response = Http::post($url, $data);
    }

    public function confirmar()
    {
        if ($this->elegido) {
            if ($this->haycuota) {
                $pago = Pagos::where('idoperativo', $this->operativo->id)->first();
                if ($this->descuentotratamientos != "") {
                    $pago->cantidad =  $this->pagotratamientostotal - $this->descuentotratamientos;
                }

                $pago->pagado =  $pago->pagado + $this->pagoefectivo + $this->pagoqr;
                $pago->save();
                if ($this->pagoqr == 0 && $this->pagoefectivo == 0) {
                    $this->confirmarasistencia();
                } else {
                    if ($this->pagoqr > 0) {
                        $this->cantidadpago = $this->pagoqr;
                        $this->mododepago = "QR";
                        $this->pagar();
                    }
                    if ($this->pagoefectivo > 0) {
                        $this->cantidadpago = $this->pagoefectivo;
                        $this->mododepago = "Efectivo";
                        $this->pagar();
                    }
                }
            } else {
                if ($this->pagoqr == 0 && $this->pagoefectivo == 0) {
                    $this->confirmarasistencia();
                } else {
                    $this->mododepago = "Efectivo";
                    $pago = new Pagos;
                    $pago->estado = 'Pendiente';
                    $pago->area = $this->operativo->area;
                    $pago->iduser = $this->operativo->idempresa;
                    $pago->fecha = Carbon::now()->toDateString();
                    $pago->cantidad =  $this->pagotratamientostotal - $this->descuentotratamientos;
                    $pago->pagado = $this->pagoefectivo + $this->pagoqr;
                    $pago->idoperativo = $this->operativo->id;
                    $pago->modo =  $this->mododepago;
                    $pago->save();
                    if ($this->pagoqr > 0) {
                        $this->cantidadpago = $this->pagoqr;
                        $this->mododepago = "QR";
                        $this->pagar();
                    }
                    if ($this->pagoefectivo > 0) {
                        $this->cantidadpago = $this->pagoefectivo;
                        $this->mododepago = "Efectivo";
                        $this->pagar();
                    }
                }
            }


            if (($this->hora && $this->minuto) != "") {
                $this->hora = sprintf('%02d', $this->hora);
                $this->operativo->hora = $this->hora . ':' . $this->minuto;
            }
            $this->operativo->save();
            $historiales = HistorialCliente::where('idoperativo', $this->operativo->id)->get();
            $tratamientos = TratamientoCliente::where('idoperativo', $this->operativo->id)->get();
            foreach ($tratamientos as $tratamiento) {
                $tratamiento = TratamientoCliente::find($tratamiento->id);
                $tratamiento->estado = 'Activo';
                $tratamiento->save();
            }
            foreach ($historiales as $historial) {
                if (in_array($historial->idtratamiento, $this->tratamientosSeleccionados)) {
                    if ($historial->estado != 'Activo') {
                        $historial->estado = 'Activo';
                        $historial->fecha = Carbon::now()->toDateString();
                        $historial->save();
                    }
                }
            }

            sleep(1);
            $this->imprimircita();
            sleep(1);
            $this->imprimircitaRecepcion();
            sleep(1);
            $this->imprimircitaCosmetologa();
            sleep(1);
            $area = Areas::find(Auth::user()->sesionsucursal);
            $area->ticket = $area->ticket + 1;
            $area->save();
            $this->emit('alert', '¡Cita confirmada satisfactoriamente!');
        } else {
            $this->emit('error', '¡Seleccione cosmetologa!');
            $this->editar = true;
        }
        $this->resetearVariables();
        $this->emitTo('operativos.lista-operativo', 'render');
        $this->emitTo('recepcionista.lista-recepcion', 'render');
    }

    public function cancelar()
    {
        $this->reset(['openArea2', 'openArea', 'openArea3', 'openArea4']);
    }
    public function guardaroperativo()
    {
        if (($this->hora && $this->minuto) != "") {
            $this->hora = sprintf('%02d', $this->hora);
            $this->operativo->hora = $this->hora . ':' . $this->minuto;
            $this->operativo->estado = 'Pendiente';
            $this->operativo->save();

            $this->emit('alert', '¡Cita reagendada satisfactoriamente!');
        } else {
            $this->emit('error', '¡No presionó hora o minuto!');
        }
        $this->emitTo('recepcionista.gestion-citas', 'render');
        $this->emitTo('operativos.lista-operativo', 'render');
        $this->emitTo('recepcionista.lista-recepcion', 'render');
    }

    public function enviarcompra()
    {
        $numeroTelefono = '59177393205';
        $mensaje = "¡Hola!";
        $enlaceWhatsApp = 'https://wa.me/' . $numeroTelefono . '?text=' . urlencode($mensaje);
        return redirect()->to($enlaceWhatsApp);
    }
    public function rellamarnum()
    {
        $operativo = Operativos::find($this->operativo->id);

        $verificar = Calls::find($operativo->idllamada);
        if ($verificar) {
            $verificar->cantidad = $verificar->cantidad + 1;
            $verificar->save();
            $registro = new registrollamadas();
            $registro->idllamada = $verificar->id;
            $registro->telefono = $verificar->telefono;
            $registro->iduser = Auth::user()->id;
            $registro->responsable = Auth::user()->name;
            $registro->fecha = Carbon::now()->toDateString();
            $registro->sucursal = '1';
            $registro->estado = 'Activo';
            $registro->save();
        } else {
            $nuevo = new Calls;
            $nuevo->empresa = $operativo->empresa;
            $nuevo->area = $operativo->area;
            $nuevo->fechacita = $operativo->fecha;
            $nuevo->telefono = $operativo->telefono;
            $nuevo->comentario = "";
            $nuevo->responsable = Auth::user()->name;
            $nuevo->estado = "Pendiente";
            $nuevo->cantidad = 1;
            $nuevo->save();
            $operativo->idllamada = $nuevo->id;
            $operativo->save();
            $registro = new registrollamadas();
            $registro->idllamada = $nuevo->id;
            $registro->telefono = $operativo->telefono;
            $registro->iduser = Auth::user()->id;
            $registro->responsable = Auth::user()->name;
            $registro->fecha = Carbon::now()->toDateString();
            $registro->estado = 'Activo';
            $registro->sucursal = '1';

            $registro->save();
        }
        $this->emit('alert', '¡Remarketing guardado satisfactoriamente!');
    }
    public function resetearVariables()
    {
        $this->reset([
            'elegido',
            'openArea',
            'openArea2',
            'openArea3',
            'openArea4',
            'nombre',
            'telefono',
            'cantidad',
            'fecha',
            'hora',
            'minuto',
            'openArea5',
            'user',
            'tratamientos',
            'editar',
            'tratamientosSeleccionados',
            'tratamientosSeleccionadosvacios',
            'cantidadpago',
            'cantidadpagototal',
            'cuotasmanual',
            'cuota1',
            'cuota2',
            'cuota3',
            'cuota4',
            'haycuota',
            'deuda',
            'todoslospagos',
            'pagototal',
            'mistratamientos',
            'agreagar',
            'mistratamientospara',
            'tratamientosSeleccionadosnuevos',
            'busquedatratamiento',
            'pagonuevo',
            'total',
            'valores',
            'primerafecha',
            'pagos',
            'ffff',
            'mostrarbotones',
            'comentariollamada',
            'pagoefectivo',
            'pagoqr',
            'pagotratamientostotal',
            'descuentotratamientos',
            'info',
            'idtratamientoseleccionado',
            'eliminar'
        ]);
    }
}