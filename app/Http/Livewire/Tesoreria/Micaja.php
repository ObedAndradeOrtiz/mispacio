<?php

namespace App\Http\Livewire\Tesoreria;

use App\Models\Calls;
use App\Models\Operativos;
use App\Models\Empresas;
use App\Models\Gastos;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Areas;
use App\Models\registrocaja;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Http;

class Micaja extends Component
{
    public $total_monto_g;
    public $total_inventario_g;
    public $gastoarea_g;
    public $gastoarealista;
    public $total_monto_citas_g;
    public $total_monto_qr_g;
    public $total_monto_qr_lista_g;
    public $total_inventario_qr_g;
    public $total_monto;
    public $total_inventario;
    public $gastoarea;
    public $total_monto_citas;
    public $total_monto_qr;
    public $total_monto_qr_lista;
    public $total_inventario_qr;
    public $total_inventario_pago;
    public $total_inventario_pago_qr;
    public $openAreaGasto = false;
    public  $fechaInicioMes;
    public $fechaActual;
    public $total_monto_cita_efectivo;
    public  $total_monto_cita_qr;
    public $turnomanana = 0;
    public $turnotarde = 0;
    public $existecaja = false;
    public $opcion = 0;
    protected $listeners = ['render' => 'render', 'eliminar' => 'eliminar'];

    public function setOpcion($num)
    {
        $this->opcion = $num;
    }
    public function mount()
    {
        $this->fechaInicioMes = Carbon::now()->toDateString();
        $this->fechaActual = Carbon::now()->toDateString();
    }
    public function registrarcaja()
    {

        $now = new DateTime();
        $fecha = $now->format('Y-m-d'); // Formato de fecha: 2024-05-21
        $hora = $now->format('H:i:s'); // Formato de hora: 14:35:22

        //SUMA DE CITAS EN EFECTIVO
        $total_monto_cita_efectivo = DB::table('registropagos')
            ->where('modo', 'ilike', '%Efectivo%')
            ->where('sucursal', Auth::user()->sucursal)
            ->where('fecha', '<=',  $fecha)
            ->where('fecha', '>=',   $fecha)
            ->sum(DB::raw('CAST(monto AS DECIMAL(10, 2))'));
        //SUMA DE PRODUCTOS VENDIDOS EN EFECTIVO
        $total_inventario_efectivo =
            DB::table('registroinventarios')
            ->where('sucursal', Auth::user()->sucursal)
            ->where('modo', 'ilike', '%Efectivo%')
            ->where('fecha', '<=', $fecha)
            ->where('fecha', '>=',  $fecha)
            ->where('motivo', 'compra')
            ->sum(DB::raw('CAST(precio AS DECIMAL(10, 2))')) +
            DB::table('registroinventarios')
            ->where('sucursal', Auth::user()->sucursal)
            ->where('modo', 'ilike', '%Efectivo%')
            ->where('fecha', '<=', $fecha)
            ->where('fecha', '>=',  $fecha)
            ->where('motivo', 'farmacia')
            ->sum(DB::raw('CAST(precio AS DECIMAL(10, 2))'));
        //GASTOS EN EFECTIVO DE CAJA
        $gastoarea_efectivo = DB::table('gastos')
            ->where('area', Auth::user()->sucursal)
            ->where('fechainicio', '<=', $this->fechaActual)
            ->where('fechainicio', '>=',  $this->fechaInicioMes)
            ->where('pertence', 'Caja')
            ->sum('cantidad');
        //SUMA DE CITAS EN QR
        $total_monto_cita_qr = DB::table('registropagos')
            ->where('modo', 'ilike', '%Qr%')
            ->where('sucursal', Auth::user()->sucursal)
            ->where('fecha', '<=',  $fecha)
            ->where('fecha', '>=',   $fecha)
            ->sum(DB::raw('CAST(monto AS DECIMAL(10, 2))'));
        //SUMA DE PRODUCTOS VENDIDOS EN QR
        $total_inventario_qr =
            DB::table('registroinventarios')
            ->where('sucursal', Auth::user()->sucursal)
            ->where('modo', 'ilike', 'QR%')
            ->where('fecha', '<=', $fecha)
            ->where('fecha', '>=',  $fecha)
            ->where('motivo', 'compra')
            ->sum(DB::raw('CAST(precio AS DECIMAL(10, 2))')) +
            DB::table('registroinventarios')
            ->where('sucursal', Auth::user()->sucursal)
            ->where('modo', 'ilike', '%QR%')
            ->where('fecha', '<=', $fecha)
            ->where('fecha', '>=',  $fecha)
            ->where('motivo', 'farmacia')
            ->sum(DB::raw('CAST(precio AS DECIMAL(10, 2))'));
        $nuevo = new registrocaja();
        $nuevo->sucursal = Auth::user()->sucursal;
        $nuevo->idsucursal = Auth::user()->sesionsucursal;
        $nuevo->montoefectivo =  $total_monto_cita_efectivo + $total_inventario_efectivo - $gastoarea_efectivo;
        $nuevo->montoqr = $total_monto_cita_qr + $total_inventario_qr;
        $nuevo->productosvendidos = 0;
        $nuevo->iduser = Auth::user()->id;
        $nuevo->responsable = Auth::user()->name;
        $nuevo->fecha = $fecha;
        $nuevo->hora = $hora;
        $nuevo->estado = 'Activo';
        $nuevo->save();

        $this->emit('alert', '¡Caja guardada satisfactoriamente!');
    }
    public function render()
    {
        $this->existecaja = registrocaja::where('iduser', Auth::user()->id)->where('fecha', $this->fechaActual)->exists();
        $this->turnotarde = DB::table('registropagos')
            ->whereRaw("EXTRACT(HOUR FROM created_at) = 14")
            ->whereRaw("EXTRACT(MINUTE FROM created_at) = 14")
            ->whereRaw("EXTRACT(SECOND FROM created_at) = 59")
            ->orWhere(function ($query) {
                $query->whereRaw("EXTRACT(HOUR FROM created_at) > 14")
                    ->orWhere(function ($query) {
                        $query->whereRaw("EXTRACT(HOUR FROM created_at) = 14")
                            ->whereRaw("EXTRACT(MINUTE FROM created_at) >= 14");
                    });
            })
            ->where('sucursal', Auth::user()->sucursal)
            ->where('fecha', '<=', $this->fechaActual)
            ->where('fecha', '>=',  $this->fechaInicioMes)
            ->sum(DB::raw('CAST(monto AS DECIMAL(10, 2))')) + DB::table('registroinventarios')
            ->whereRaw("EXTRACT(HOUR FROM created_at) = 14")
            ->whereRaw("EXTRACT(MINUTE FROM created_at) = 14")
            ->whereRaw("EXTRACT(SECOND FROM created_at) = 59")
            ->orWhere(function ($query) {
                $query->whereRaw("EXTRACT(HOUR FROM created_at) > 14")
                    ->orWhere(function ($query) {
                        $query->whereRaw("EXTRACT(HOUR FROM created_at) = 14")
                            ->whereRaw("EXTRACT(MINUTE FROM created_at) >= 14");
                    });
            })
            ->where('sucursal', Auth::user()->sucursal)
            ->where('modo', 'ilike', '%Efectivo%')
            ->where('fecha', '<=', $this->fechaActual)
            ->where('fecha', '>=',  $this->fechaInicioMes)
            ->where('motivo', 'compra')
            ->sum(DB::raw('CAST(precio AS DECIMAL(10, 2))')) +
            DB::table('registroinventarios')
            ->whereRaw("EXTRACT(HOUR FROM created_at) = 14")
            ->whereRaw("EXTRACT(MINUTE FROM created_at) = 14")
            ->whereRaw("EXTRACT(SECOND FROM created_at) = 59")
            ->orWhere(function ($query) {
                $query->whereRaw("EXTRACT(HOUR FROM created_at) > 14")
                    ->orWhere(function ($query) {
                        $query->whereRaw("EXTRACT(HOUR FROM created_at) = 14")
                            ->whereRaw("EXTRACT(MINUTE FROM created_at) >= 14");
                    });
            })
            ->where('sucursal', Auth::user()->sucursal)
            ->where('modo', 'ilike', '%Efectivo%')
            ->where('fecha', '<=', $this->fechaActual)
            ->where('fecha', '>=',  $this->fechaInicioMes)
            ->where('motivo', 'farmacia')
            ->sum(DB::raw('CAST(precio AS DECIMAL(10, 2))'));

        $this->turnomanana = DB::table('registropagos')
            ->whereRaw("EXTRACT(HOUR FROM created_at) = 0")
            ->whereRaw("EXTRACT(MINUTE FROM created_at) = 0")
            ->whereRaw("EXTRACT(SECOND FROM created_at) = 0")
            ->orWhere(function ($query) {
                $query->whereRaw("EXTRACT(HOUR FROM created_at) < 14")
                    ->orWhere(function ($query) {
                        $query->whereRaw("EXTRACT(HOUR FROM created_at) = 14")
                            ->whereRaw("EXTRACT(MINUTE FROM created_at) <= 14");
                    });
            })
            ->where('sucursal', Auth::user()->sucursal)
            ->where('fecha', '<=', $this->fechaActual)
            ->where('fecha', '>=',  $this->fechaInicioMes)
            ->sum(DB::raw('CAST(monto AS DECIMAL(10, 2))')) + DB::table('registroinventarios')
            ->whereRaw("EXTRACT(HOUR FROM created_at) = 0")
            ->whereRaw("EXTRACT(MINUTE FROM created_at) = 0")
            ->whereRaw("EXTRACT(SECOND FROM created_at) = 0")
            ->orWhere(function ($query) {
                $query->whereRaw("EXTRACT(HOUR FROM created_at) < 14")
                    ->orWhere(function ($query) {
                        $query->whereRaw("EXTRACT(HOUR FROM created_at) = 14")
                            ->whereRaw("EXTRACT(MINUTE FROM created_at) <= 14");
                    });
            })
            ->where('sucursal', Auth::user()->sucursal)

            ->where('fecha', '<=', $this->fechaActual)
            ->where('fecha', '>=',  $this->fechaInicioMes)
            ->where('motivo', 'compra')
            ->sum(DB::raw('CAST(precio AS DECIMAL(10, 2))')) +
            DB::table('registroinventarios')
            ->whereRaw("EXTRACT(HOUR FROM created_at) = 0")
            ->whereRaw("EXTRACT(MINUTE FROM created_at) = 0")
            ->whereRaw("EXTRACT(SECOND FROM created_at) = 0")
            ->orWhere(function ($query) {
                $query->whereRaw("EXTRACT(HOUR FROM created_at) < 14")
                    ->orWhere(function ($query) {
                        $query->whereRaw("EXTRACT(HOUR FROM created_at) = 14")
                            ->whereRaw("EXTRACT(MINUTE FROM created_at) <= 14");
                    });
            })
            ->where('sucursal', Auth::user()->sucursal)

            ->where('fecha', '<=', $this->fechaActual)
            ->where('fecha', '>=',  $this->fechaInicioMes)
            ->where('motivo', 'farmacia')
            ->sum(DB::raw('CAST(precio AS DECIMAL(10, 2))'));

        $this->total_monto_g = DB::table('registropagos')
            ->where('sucursal', Auth::user()->sucursal)
            ->where('fecha', '<=', $this->fechaActual)
            ->where('fecha', '>=',  $this->fechaInicioMes)
            ->sum(DB::raw('CAST(monto AS DECIMAL(10, 2))'));
        //OBTIENE TODO EN EFECTIVO DE  CITAS
        $this->total_monto_cita_efectivo = DB::table('registropagos')
            ->where('modo', 'ilike', '%Efectivo%')
            ->where('sucursal', Auth::user()->sucursal)
            ->where('fecha', '<=', $this->fechaActual)
            ->where('fecha', '>=',  $this->fechaInicioMes)
            ->sum(DB::raw('CAST(monto AS DECIMAL(10, 2))'));
        //OBTIENE TODO EN EFECTIVO DE PRODUCTOS EN COMPRA Y FARMACIA
        $this->total_inventario_g =
            DB::table('registroinventarios')
            ->where('sucursal', Auth::user()->sucursal)
            ->where('modo', 'ilike', '%Efectivo%')
            ->where('fecha', '<=', $this->fechaActual)
            ->where('fecha', '>=',  $this->fechaInicioMes)
            ->where('motivo', 'compra')
            ->sum(DB::raw('CAST(precio AS DECIMAL(10, 2))')) +
            DB::table('registroinventarios')
            ->where('sucursal', Auth::user()->sucursal)
            ->where('modo', 'ilike', '%Efectivo%')
            ->where('fecha', '<=', $this->fechaActual)
            ->where('fecha', '>=',  $this->fechaInicioMes)
            ->where('motivo', 'farmacia')
            ->sum(DB::raw('CAST(precio AS DECIMAL(10, 2))'));




        //OBTIENE TODO EN QR DE  CITAS
        $this->total_monto_cita_qr = DB::table('registropagos')
            ->where('modo', 'ilike', '%Qr%')
            ->where('sucursal', Auth::user()->sucursal)
            ->where('fecha', '<=', $this->fechaActual)
            ->where('fecha', '>=',  $this->fechaInicioMes)
            ->sum(DB::raw('CAST(monto AS DECIMAL(10, 2))'));

        $this->gastoarea_g = DB::table('gastos')
            ->where('area', Auth::user()->sucursal)
            ->where('fechainicio', '<=', $this->fechaActual)
            ->where('fechainicio', '>=',  $this->fechaInicioMes)
            ->where('pertence', 'Caja')
            ->sum('cantidad');

        $this->gastoarealista = DB::table('gastos')
            ->where('area', Auth::user()->sucursal)
            ->where('fechainicio', '<=', $this->fechaActual)
            ->where('fechainicio', '>=',  $this->fechaInicioMes)
            ->where('pertence', 'Caja')
            ->get();
        $this->total_monto_citas_g = DB::table('registropagos')
            ->where('sucursal', Auth::user()->sucursal)
            ->where('fecha', '<=', $this->fechaActual)
            ->where('fecha', '>=',  $this->fechaInicioMes)
            ->where('modo', 'ilike', '%Efectivo%')
            ->get();

        $this->total_monto_qr_g = DB::table('registropagos')
            ->where('sucursal', Auth::user()->sucursal)
            ->where('estado', 'Activo')
            ->where('modo', 'ilike', '%qr%')
            ->where('fecha', '<=', $this->fechaActual)
            ->where('fecha', '>=',  $this->fechaInicioMes)
            ->sum(DB::raw('CAST(monto AS DECIMAL(10, 2))'));
        $this->total_monto_qr_lista_g = DB::table('registropagos')
            ->where('sucursal', Auth::user()->sucursal)
            ->where('estado', 'Activo')
            ->where('modo', 'ilike', '%qr%')
            ->where('fecha', '<=', $this->fechaActual)
            ->where('fecha', '>=',  $this->fechaInicioMes)
            ->get();
        $this->total_inventario_qr_g =
            DB::table('registroinventarios')
            ->where('sucursal', Auth::user()->sucursal)
            ->where('estado', 'Activo')
            ->where('modo', 'ilike', '%Qr%')
            ->where('fecha', '<=', $this->fechaActual)
            ->where('fecha', '>=',  $this->fechaInicioMes)
            ->where('motivo', 'compra')
            ->sum(DB::raw('CAST(precio AS DECIMAL(10, 2))')) +
            DB::table('registroinventarios')
            ->where('sucursal', Auth::user()->sucursal)
            ->where('modo', 'ilike', '%Qr%')
            ->where('fecha', '<=', $this->fechaActual)
            ->where('fecha', '>=',  $this->fechaInicioMes)
            ->where('motivo', 'farmacia')
            ->sum(DB::raw('CAST(precio AS DECIMAL(10, 2))'));
        $this->total_monto = DB::table('registropagos')
            ->where('iduser', Auth::user()->id)
            ->where('modo', 'ilike', '%Efectivo%')
            ->where('fecha', '<=', $this->fechaActual)
            ->where('fecha', '>=',  $this->fechaInicioMes)
            ->sum(DB::raw('CAST(monto AS DECIMAL(10, 2))'));
        $this->total_inventario =
            DB::table('registroinventarios')
            ->where('sucursal', Auth::user()->sucursal)
            ->where('modo', 'ilike', '%Efectivo%')
            ->where('fecha', '<=', $this->fechaActual)
            ->where('fecha', '>=',  $this->fechaInicioMes)
            ->where('motivo', 'compra')
            ->sum(DB::raw('CAST(precio AS DECIMAL(10, 2))')) +
            DB::table('registroinventarios')
            ->where('iduser', Auth::user()->id)
            ->where('sucursal', Auth::user()->sucursal)
            ->where('modo', 'ilike', '%Efectivo%')
            ->where('fecha', '<=', $this->fechaActual)
            ->where('fecha', '>=',  $this->fechaInicioMes)
            ->where('motivo', 'farmacia')
            ->sum(DB::raw('CAST(precio AS DECIMAL(10, 2))'));
        $this->gastoarea = DB::table('gastos')
            ->where('modo', 'ilike', '%Efectivo%')
            ->where('pertence', 'Caja')
            ->where('area', Auth::user()->sucursal)
            ->where('fechainicio', '<=', $this->fechaActual)
            ->where('fechainicio', '>=',  $this->fechaInicioMes)
            ->sum('cantidad');
        $this->total_monto_citas = DB::table('registropagos')
            ->where('sucursal', Auth::user()->sucursal)
            ->where('fecha', '<=', $this->fechaActual)
            ->where('fecha', '>=',  $this->fechaInicioMes)
            ->where('modo', 'ilike', '%Efectivo%')
            ->get();
        $this->total_monto_qr = DB::table('registropagos')
            ->where('iduser', Auth::user()->id)
            ->where('estado', 'Activo')
            ->where('modo', 'ilike', '%qr%')
            ->where('fecha', '<=', $this->fechaActual)
            ->where('fecha', '>=',  $this->fechaInicioMes)
            ->sum(DB::raw('CAST(monto AS DECIMAL(10, 2))'));
        $this->total_monto_qr_lista = DB::table('registropagos')
            ->where('sucursal', Auth::user()->sucursal)
            ->where('estado', 'Activo')
            ->where('modo', 'ilike', '%qr%')
            ->where('fecha', '<=', $this->fechaActual)
            ->where('fecha', '>=',  $this->fechaInicioMes)
            ->get();
        $this->total_inventario_qr =
            DB::table('registroinventarios')
            ->where('sucursal', Auth::user()->sucursal)
            ->where('estado', 'Activo')
            ->where('modo', 'ilike', '%Qr%')
            ->where('fecha', '<=', $this->fechaActual)
            ->where('fecha', '>=',  $this->fechaInicioMes)
            ->where('motivo', 'compra')
            ->sum(DB::raw('CAST(precio AS DECIMAL(10, 2))')) +
            DB::table('registroinventarios')
            ->where('sucursal', Auth::user()->sucursal)
            ->where('modo', 'ilike', '%Qr%')
            ->where('fecha', '<=', $this->fechaActual)
            ->where('fecha', '>=',  $this->fechaInicioMes)
            ->where('motivo', 'farmacia')
            ->sum(DB::raw('CAST(precio AS DECIMAL(10, 2))'));
        $this->total_inventario_pago = DB::table('registroinventarios')
            ->select('nombreproducto', DB::raw('SUM(cantidad) as antidad'))
            ->where('sucursal', Auth::user()->sucursal)
            ->where('estado', 'Activo')
            ->where('fecha', '<=', $this->fechaActual)
            ->where('fecha', '>=',  $this->fechaInicioMes)
            ->where('motivo', 'compra')
            ->groupBy('nombreproducto')
            ->get();
        $this->total_inventario_pago_qr = DB::table('registroinventarios')
            ->where('sucursal', Auth::user()->sucursal)
            ->where('estado', 'Activo')
            ->where('modo', 'ilike', '%QR%')
            ->where('fecha', '<=', $this->fechaActual)
            ->where('fecha', '>=',  $this->fechaInicioMes)
            ->where('motivo', 'compra')
            ->get();
        return view('livewire.tesoreria.micaja');
    }
    public function actualizar()
    {
        $this->render();
    }
    public function eliminar($idgasto)
    {
        $misgastos = Gastos::find($idgasto);
        $misgastos->delete();

        $this->render();
    }
    public function imprimirResultado()
    {

        $descriptionWidth = 30;
        $recepcion = explode(' ', Auth::user()->name);

        $text = "REGISTRO DE PAGOS--------------------------------------------------------------------\n" . "Fecha: " . date('Y-m-d H:i:s') . "\nCaja: " . Auth::user()->sucursal . "\nRecepcionista: " . implode(' ', array_slice($recepcion, 0, 2)) . "\n-----------------------------------------------------------------------------" . str_pad("\nDESCRIPCIÓN", $descriptionWidth) . "" . "MONTO\n" .
            "PAGOS EN EFECTIVO AGENDADOS-------------------------------------------------\n";

        $costo = 0;
        $ingresoefectivo = 0;
        $ingresoqr = 0;
        $ingresototal = 0;
        $gastototal = 0;
        $lineWidth = 44;
        $pricePosition = intval($lineWidth * 0.7);

        $total_monto_citas = DB::table('registropagos')
            ->where('sucursal', Auth::user()->sucursal)
            ->where('fecha', '<=', $this->fechaActual)
            ->where('fecha', '>=', $this->fechaInicioMes)
            ->where('modo', 'ilike', '%Efectivo%')
            ->get();
        foreach ($total_monto_citas as $citas) {
            $costo += (float)$citas->monto;
            $ingresoefectivo += (float)$citas->monto;
            $cliente = User::find($citas->idcliente);
            if ($cliente) {
                $descripcion = substr($cliente->telefono . '-' . $cliente->name, 0, 27);
                $descripcion = str_pad($descripcion, $pricePosition - 1, ' ', STR_PAD_RIGHT);
                $precio = 'Bs.' . $citas->monto;
                $text .= $descripcion . $precio . "\n";
            }
        }
        $text .= " -----------------------------------------------------------------------------\n";
        $totalpago = "Total";
        $totalpago = substr($totalpago, 0, 20);
        $totalpago = str_pad($totalpago, $pricePosition - 1, ' ', STR_PAD_RIGHT);
        $precio = 'Bs.' . (float) $costo;
        $text .=  $totalpago . $precio . "\n";
        $text .= " -----------------------------------------------------------------------------\n";
        $text .= "PAGOS EN EFECTIVO PRODUCTOS-------------------------------------------------\n";
        $costo = 0;
        $lineWidth = 44;
        $pricePosition = intval($lineWidth * 0.7);

        $total_monto_citas = DB::table('registroinventarios')
            ->where('sucursal', Auth::user()->sucursal)
            ->where('estado', 'Activo')
            ->whereIn('modo', ['efectivo', 'qr/efectivo'])
            ->where('fecha', '<=', $this->fechaActual)
            ->where('fecha', '>=', $this->fechaInicioMes)
            ->whereIn('motivo', ['compra', 'farmacia'])->get();
        foreach ($total_monto_citas as $citas) {
            $costo += (float)$citas->precio;
            $ingresoefectivo += (float)$citas->precio;
            $descripcion = substr($citas->cantidad . '-' . $citas->nombreproducto, 0, 27);
            $descripcion = str_pad($descripcion, $pricePosition - 1, ' ', STR_PAD_RIGHT);
            $precio = 'Bs.' . $citas->precio;
            $text .= $descripcion . $precio . "\n";
        }
        $text .= " -----------------------------------------------------------------------------\n";
        $totalpago = "Total";
        $totalpago = substr($totalpago, 0, 20);
        $totalpago = str_pad($totalpago, $pricePosition - 1, ' ', STR_PAD_RIGHT);
        $precio = 'Bs.' . (float) $costo;
        $text .=  $totalpago . $precio . "\n";
        $text .= " -----------------------------------------------------------------------------\n";
        $text .= "PAGOS EN QR AGENDADOS-------------------------------------------------\n";
        $costo = 0;
        $lineWidth = 44;
        $pricePosition = intval($lineWidth * 0.7);

        $total_monto_citas = DB::table('registropagos')
            ->where('sucursal', Auth::user()->sucursal)
            ->where('fecha', '<=', $this->fechaActual)
            ->where('fecha', '>=', $this->fechaInicioMes)
            ->where('modo', 'ilike', '%Qr%')
            ->get();
        foreach ($total_monto_citas as $citas) {
            $costo += (float)$citas->monto;
            $ingresoqr += (float)$citas->monto;
            $cliente = User::find($citas->idcliente);
            if ($cliente) {
                $descripcion = substr($cliente->telefono . '-' . $cliente->name, 0, 27);
                $descripcion = str_pad($descripcion, $pricePosition - 1, ' ', STR_PAD_RIGHT);
                $precio = 'Bs.' . $citas->monto;
                $text .= $descripcion . $precio . "\n";
            } else {
                $descripcion = substr("NO REGISTRADO" . '-' . $citas->nombrecliente, 0, 27);
                $descripcion = str_pad($descripcion, $pricePosition - 1, ' ', STR_PAD_RIGHT);
                $precio = 'Bs.' . $citas->monto;
                $text .= $descripcion . $precio . "\n";
            }
        }
        $text .= " -----------------------------------------------------------------------------\n";
        $totalpago = "Total";
        $totalpago = substr($totalpago, 0, 20);
        $totalpago = str_pad($totalpago, $pricePosition - 1, ' ', STR_PAD_RIGHT);
        $precio = 'Bs.' . (float) $costo;
        $text .=  $totalpago . $precio . "\n";
        $text .= " -----------------------------------------------------------------------------\n";


        $text .= "PAGOS EN QR PRODUCTOS-------------------------------------------------\n";
        $costo = 0;
        $lineWidth = 44;
        $pricePosition = intval($lineWidth * 0.7);

        $total_monto_citas = DB::table('registroinventarios')
            ->where('sucursal', Auth::user()->sucursal)
            ->where('estado', 'Activo')
            ->whereIn('modo', ['qr', 'qr/efectivo'])
            ->where('fecha', '<=', $this->fechaActual)
            ->where('fecha', '>=', $this->fechaInicioMes)
            ->whereIn('motivo', ['compra', 'farmacia'])->get();
        foreach ($total_monto_citas as $citas) {

            if ((float)$citas->precio_qr > 0) {
                $costo += (float)$citas->precio_qr;
                $ingresoqr += (float)$citas->precio_qr;
            } else {
                $costo += (float)$citas->precio;
                $ingresoqr += (float)$citas->precio;
            }

            $descripcion = substr($citas->cantidad . '-' . $citas->nombreproducto, 0, 27);
            $descripcion = str_pad($descripcion, $pricePosition - 1, ' ', STR_PAD_RIGHT);
            $precio = 'Bs.' . $citas->precio;
            $text .= $descripcion . $precio . "\n";
        }
        $text .= " -----------------------------------------------------------------------------\n";
        $totalpago = "Total";
        $totalpago = substr($totalpago, 0, 20);
        $totalpago = str_pad($totalpago, $pricePosition - 1, ' ', STR_PAD_RIGHT);
        $precio = 'Bs.' . (float) $costo;
        $text .=  $totalpago . $precio . "\n";
        $text .= " -----------------------------------------------------------------------------\n";
        $text .= "INGRESO TOTAL EFECTIVO-------------------------------------------------\n";
        $totalpago = "Total";
        $totalpago = substr($totalpago, 0, 20);
        $totalpago = str_pad($totalpago, $pricePosition - 1, ' ', STR_PAD_RIGHT);
        $precio = 'Bs.' . (float) $ingresoefectivo;
        $text .=  $totalpago . $precio . "\n";
        $text .= " -----------------------------------------------------------------------------\n";
        $text .= "INGRESO TOTAL QR-------------------------------------------------\n";
        $totalpago = "Total";
        $totalpago = substr($totalpago, 0, 20);
        $totalpago = str_pad($totalpago, $pricePosition - 1, ' ', STR_PAD_RIGHT);
        $precio = 'Bs.' . (float) $ingresoqr;
        $text .=  $totalpago . $precio . "\n";
        $text .= " -----------------------------------------------------------------------------\n";
        $ingresototal = $ingresoefectivo + $ingresoqr;
        $text .= "INGRESO TOTAL SUC.-------------------------------------------------\n";
        $totalpago = "Total";
        $totalpago = substr($totalpago, 0, 20);
        $totalpago = str_pad($totalpago, $pricePosition - 1, ' ', STR_PAD_RIGHT);
        $precio = 'Bs.' . (float) $ingresototal;
        $text .=  $totalpago . $precio . "\n";
        $text .= " -----------------------------------------------------------------------------\n";
        $text .= "GASTOS DE CAJA-------------------------------------------------\n";
        $costo = 0;
        $lineWidth = 44;
        $pricePosition = intval($lineWidth * 0.7);
        $total_monto_citas = DB::table('gastos')
            ->where('modo', 'ilike', '%Efectivo%')
            ->where('area', Auth::user()->sucursal)
            ->where('fechainicio', '<=', $this->fechaActual)
            ->where('fechainicio', '>=', $this->fechaInicioMes)
            ->where('pertence', 'Caja')
            ->get();
        foreach ($total_monto_citas as $citas) {
            $costo += (float)$citas->cantidad;
            $gastototal += (float)$citas->cantidad;
            $descripcion = substr($citas->empresa, 0, 27);
            $descripcion = str_pad($descripcion, $pricePosition - 1, ' ', STR_PAD_RIGHT);
            $precio = 'Bs.' . $citas->cantidad;
            $text .= $descripcion . $precio . "\n";
        }
        $text .= " -----------------------------------------------------------------------------\n";
        $text .= "CUADRE DE CAJA-------------------------------------------------\n";
        $totalpago = "TOTAL SUCURSAL";
        $totalpago = substr($totalpago, 0, 20);
        $totalpago = str_pad($totalpago, $pricePosition - 1, ' ', STR_PAD_RIGHT);
        $precio = 'Bs.' . (float) $ingresototal - (float) $gastototal;
        $text .=  $totalpago . $precio . "\n";
        $totalpago = "TOTAL EN CAJA";
        $totalpago = substr($totalpago, 0, 20);
        $totalpago = str_pad($totalpago, $pricePosition - 1, ' ', STR_PAD_RIGHT);
        $precio = 'Bs.' . (float) $ingresoefectivo - (float) $gastototal;
        $text .=  $totalpago . $precio . "\n";
        $text .= "




        FIRMA : -----------------------------------------------------------------------------


        -----------------------------------------------------------------------------\n";
        $text = mb_convert_encoding($text, 'UTF-8', 'auto');
        $data = [
            'id' => Auth::user()->sesionsucursal,
            'texto' => $text
        ];
        $url = "http://127.0.0.1:5001/imprimirticket";
        $response = Http::post($url, $data);
        sleep(1);
    }
}
