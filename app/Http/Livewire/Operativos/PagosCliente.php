<?php

namespace App\Http\Livewire\Operativos;

use App\Models\Areas;
use App\Models\Tratamiento;
use App\Models\HistorialCliente;
use App\Models\Operativos;
use App\Models\Pagos;
use App\Models\registropago;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Http;

use function Termwind\render;

class PagosCliente extends Component
{
    use WithPagination;
    public $operativo;
    public $confirmarimprimir = false;
    public $mododepago = "Efectivo";
    public $elegido;
    public $editaraux = false;
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
    public $deuda = 0;
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
    public $eliminart = false;
    public $crear = false;
    public $costo = 0;
    public $tratamientoaEditar;
    public $editarTratamientoBool;
    public $tratamientoCobrar;
    public $comentarioobser = "";
    public $personalenganche;
    public $tipodetratamiento = "Inactivo";
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
    protected $listeners = ['render' => 'render'];
    public function mount($idoperativo)
    {
        $this->loadOperativoData($idoperativo);
    }

    public function loadOperativoData($idoperativo)
    {
        $this->operativo = Operativos::find($idoperativo);
        $this->total = DB::table('pagos')
            ->where('idoperativo', $this->operativo->id)
            ->sum('cantidad');
        $this->primerafecha = $this->operativo->fecha;
        $pago = Pagos::where('idoperativo', $this->operativo->id)->first();
        if ($pago) {
            $this->pagotratamientostotal =  $pago->cantidad;
        }
    }

    public function actualizarOperativo($idoperativo)
    {
        $this->loadOperativoData($idoperativo);
    }
    public function render()
    {
        $haypago = false;
        $pago = registropago::where('idcliente', $this->operativo->idempresa)->sum(DB::raw('CAST(monto AS DECIMAL(10, 2))'));


        if ($pago > 0) {
            $this->haycuota = true;
        }
        $this->deuda = DB::table('registropagos')
            ->where('idoperativo', $this->operativo->id)
            ->sum(DB::raw('CAST(monto AS DECIMAL(10, 2))'));
        $this->tratamientos = HistorialCliente::where('idcliente', $this->operativo->idempresa)->get();
        $this->totalpagado = registropago::where('idoperativo', $this->operativo->id)->sum(DB::raw('CAST(monto AS DECIMAL(10, 2))'));
        $this->mistratamientospara = Tratamiento::where('estado', 'Activo')->where('nombre', 'ilike', '%' . $this->busquedatratamiento . '%')->orderBy('nombre', 'asc')->get();
        $this->todoslospagos = Pagos::where('idoperativo', $this->operativo->id)->where('estado', 'Pendiente')->count();
        $this->mistratamientos = HistorialCliente::where('estado', 'Activo')->where('idoperativo', $this->operativo->id)->get();
        if ($this->pagotratamientostotal == "") {
            $this->deuda = 0;
        } else {
            $this->deuda = $this->pagotratamientostotal - $this->totalpagado;
        }

        $areas = Areas::where('estado', 'Activo')->orderBy('id', 'desc')->get();
        $users = User::where('estado', 'Activo')->where('rol', '!=', 'Cliente')->where('rol', '!=', 'TARJETAS')->orderBy('name', 'asc')->get();
        return view('livewire.operativos.pagos-cliente', compact('areas', 'users'));
    }
    public function finalizarTratamiento($idhistorial)
    {
        $historial = HistorialCliente::find($idhistorial);
        $historial->estado = 'Finalizado';
        $historial->save();
        $this->render();
        $this->emit('alert', '¡Tratamiento finalizado!');
    }
    public function activarTratamiento($idhistorial)
    {
        $historial = HistorialCliente::find($idhistorial);
        $historial->estado = 'Inactivo';
        $historial->save();
        $this->render();
        $this->emit('alert', '¡Tratamiento recuperado!');
    }
    public function guardaroperativo()
    {
        if (Pagos::where('idoperativo', $this->operativo->id)->first()) {
        } else {

            $pago = new Pagos;
            $pago->estado = 'Pendiente';
            $pago->area = $this->operativo->area;
            $pago->iduser = $this->operativo->idempresa;
            $pago->fecha = Carbon::now()->toDateString();
            $pago->cantidad = $this->cantidadpago;
            $pago->pagado = 0;
            $pago->idoperativo = $this->operativo->id;
            $pago->save();
        }
        $pago = Pagos::where('idoperativo', $this->operativo->id)->first();
        $pago->cantidad = $this->pagotratamientostotal;
        $pago->save();
        $this->emit('alert', '¡Pago editado!');
    }
    public function guardartodo()
    {
        if ($this->tratamientoCobrar != null && $this->tratamientoCobrar != "") {
            if (Pagos::where('idoperativo', $this->operativo->id)->first()) {
            } else {
                $pago = new Pagos;
                $pago->estado = 'Pendiente';
                $pago->area = $this->operativo->area;
                $pago->iduser = $this->operativo->idempresa;
                $pago->fecha = Carbon::now()->toDateString();
                $pago->cantidad = $this->cantidadpago;
                $pago->pagado = 0;
                $pago->idoperativo = $this->operativo->id;
                $pago->save();
            }
            $operativo = $this->operativo;
            $registro = new registropago;
            $sucursal = Areas::where('area', $operativo->area)->first();
            $sucursal->ticket = $sucursal->ticket + 1;
            $sucursal->save();
            $registro->idsucursal = $sucursal->id;
            $registro->sucursal = $sucursal->area;
            $registro->idoperativo = $this->tratamientoCobrar;
            $registro->nombrecliente = $this->operativo->empresa;
            $registro->monto = $this->cantidadpago;
            $registro->iduser = Auth::user()->id;
            $registro->comentario = $this->comentarioobser;
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
            $this->crear = false;
            $this->emit('alert', '¡Pago creado!');
            $this->emitTo('pagos.lista-pagos', 'render');
            $this->render();
        } else {
            $this->emit('error', 'Seleccione Tratamiento!');
        }
    }
    public function boolTratamiento($idtratamiento)
    {
        $this->tratamientoaEditar = $idtratamiento;
        $this->editarTratamientoBool = true;
    }
    public function editarTaratamiento()
    {
        $historial = HistorialCliente::find($this->tratamientoaEditar);
        $historial->costo = $this->costo;
        $historial->save();
        $this->costo = 0;
        $this->editarTratamientoBool = false;
        $this->render();
        $this->emit('alert', '¡Precio editado!');
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
    public function eliminarVista($idtratamiento)
    {
        $this->idtratamientoseleccionado = $idtratamiento;
        $this->eliminart = true;
    }
    public function eliminarTratamiento()
    {
        $tratamientohistorial = HistorialCliente::find($this->idtratamientoseleccionado);
        $tratamientohistorial->delete();
        $this->eliminart = false;
        $this->render();
        $this->emit('alert', '¡Acción realizada!');
        $this->emitTo('pagos.lista-pagos', 'render');
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
                $nuevo->idcosmetologa = $this->personalenganche;
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
        $this->agreagar = false;
        $this->reset(['openArea3', 'tratamientosSeleccionadosnuevos']);
        $this->render();
        $this->emitTo('operativos.lista-operativo', 'render');
        $this->emitTo('recepcionista.lista-recepcion', 'render');
    }
    public function imprimir()
    {
        $this->imprimircita();
        sleep(1);
        $this->imprimircitaCosmetologa();
        $this->confirmarimprimir = false;
        $this->emit('alert', '¡Accion realizada!');
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
        $pagosrealizados = DB::table('registropagos')
            ->where('idoperativo', $this->operativo->id)
            ->sum(DB::raw('CAST(monto AS DECIMAL(10, 2))'));
        $pagoenqr = "Pago(s) realizado(s)";
        $pagoenqr = substr($pagoenqr, 0, 20);
        $pagoenqr = str_pad($pagoenqr, $pricePosition - 1, ' ', STR_PAD_RIGHT);
        $precio = 'Bs.' . $pagosrealizados;
        $text .= $pagoenqr . $precio . "\n";

        $pendiente = "Cobro pendiente";
        $pendiente = substr($pendiente, 0, 20);
        $pendiente = str_pad($pendiente, $pricePosition - 1, ' ', STR_PAD_RIGHT);
        $precio = 'Bs.' . $this->deuda;
        $text .=  $pendiente . $precio . "\n";
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
}