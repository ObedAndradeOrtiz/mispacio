<?php

namespace App\Http\Livewire\Almacen;

use App\Models\Areas;
use App\Models\Inventario;
use App\Models\Lotes;
use App\Models\Produccion;
use App\Models\Productos;
use App\Models\registroinventario;
use App\Models\trasapasostext;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;

class TraspasoProductos extends Component
{
    use WithPagination;

    public $comprar = false;
    public $search;
    public $motivo = "traspaso";
    public $cantidades = [];
    public $precios = [];
    public $sucursalseleccionada;
    public $areas;
    public $searchcliente = "";
    public $clientes;
    public $clienteseleccionado = '';
    public $modo = "efectivo";
    public $pagar = 0;
    public $areaseleccionada;
    public $nombre = '';
    public $fecha;
    public $general;
    public $productos;
    public $cantidad;
    public $personal;
    public $personalseleccionado;
    public $montoQr = 0;
    public $montoEfectivo = 0;
    public $lotes;
    public $lote_seleccionado;
    public $lista_almacen;
    public $productos_almacen;
    public $productos_seleccionados = [];
    public $tipo_lote = "";
    protected $rules = [
        'producto.nombre' => 'required',
        'producto.descripcion' => 'required',
        'producto.path' => 'required',
        'producto.precio' => 'required',
        'producto.cantidad' => 'required',
        'producto.estado' => 'required',
        'producto.sucursal' => 'required',
    ];

    public function mount()
    {
        $this->lotes = Lotes::where('estado', 'Activo')->get();
        $sucursal = Areas::find(Auth::user()->sesionsucursal);
        $this->sucursalseleccionada = $sucursal->id;
        $this->fecha = Carbon::now()->toDateString();
        $this->areas = Areas::where('estado', 'Activo')->get();
        $this->personal = User::where('estado', 'Activo')->where('rol', '!=', 'Cliente')->get();
        $this->personalseleccionado = Auth::user()->id;
    }
    public function render()
    {
        $this->productos_almacen = Produccion::join('lotes', 'produccions.idlote', '=', 'lotes.id')
            ->when($this->tipo_lote, function ($query) {
                $query->where('lotes.tipo', 'ilike', '%' . $this->tipo_lote . '%');
            })
            ->when($this->lote_seleccionado, function ($query) {
                $query->where('produccions.idlote', $this->lote_seleccionado);
            })
            ->where('produccions.estado', 'Activo')
            ->where('produccions.nombreproducto', 'ilike', '%' . $this->search . '%')
            ->select('produccions.*', 'lotes.nombre as nombrelote')
            ->get();

        $this->lotes = Lotes::where('estado', 'Activo')
            ->where('tipo', 'ilike', '%' . $this->tipo_lote . '%')->get();
        return view('livewire.almacen.traspaso-productos');
    }
    public function eliminarProducto($idproducto)
    {
        unset($this->productos_seleccionados[$idproducto]);
    }

    public function aumentarCantidad($idproducto)
    {
        if (isset($this->productos_seleccionados[$idproducto])) {
            $this->productos_seleccionados[$idproducto]++;
        }
    }

    public function disminuirCantidad($idproducto)
    {
        if (isset($this->productos_seleccionados[$idproducto]) && $this->productos_seleccionados[$idproducto] > 1) {
            $this->productos_seleccionados[$idproducto]--;
        }
    }
    public function AgregarProducto($idproducto)
    {

        if (!in_array($idproducto, $this->productos_seleccionados)) {
            $this->productos_seleccionados[$idproducto] = 1;
        }
    }

    public function realizartraspaso()
    {
        $falta = false;
        $faltante = "";
        if (empty($this->productos_seleccionados)) {

            $this->emit('error', 'No se ha seleccionado ninguna cantidad.');
            return;
        } else {
            $descriptionWidth = 30;
            $vendedor = User::find($this->personalseleccionado);
            $recepcion = explode(' ', Auth::user()->name);
            $area = Areas::find($this->sucursalseleccionada);
            $text = "TRASPASO SPA MEDIC MIORA-----------------------------------------------------------------------\n"
                . "DE: ALMACEN DE PRODUCCION\n"
                . "A: " . $this->areaseleccionada . "\n"
                . "Fecha: " . date('Y-m-d H:i:s') . "\nCaja: " . Auth::user()->sucursal . "\nResponsable: " . implode(' ', array_slice($recepcion, 0, 2)) .
                "\n-----------------------------------------------------------------------------" .
                str_pad("\nDESCRIPCIÓN", $descriptionWidth) . str_pad("CANT", 8) .
                "\n-----------------------------------------------------------------------------\n";
            $costo = 0;
            $lineWidth = 44;
            $pricePosition = intval($lineWidth * 0.7);
            foreach ($this->productos_seleccionados as $id => $cantidad) {
                if ($this->areaseleccionada) {
                    $nuevaroduccion = Produccion::find($id);
                    $producto = Inventario::find($nuevaroduccion->idproducto);
                    if ($cantidad > 0 && $cantidad <= $nuevaroduccion->cantidad_actual) {
                        $registro = new registroinventario;

                        $registro->modo =  $this->areaseleccionada;
                        $registro->sucursal = "ALMACEN DE PRODUCCION";
                        $registro->idproducto = $producto->id;
                        $registro->precio = $nuevaroduccion->nombrelote;
                        $registro->idcliente = $nuevaroduccion->id;
                        $registro->nombreproducto = $producto->nombre;
                        $registro->cantidad = $cantidad;
                        $registro->iduser = Auth::user()->id;
                        $registro->fecha = Carbon::now()->toDateString();
                        $registro->motivo =  "traspaso";
                        $nuevoproducto = Productos::where('sucursal', $this->areaseleccionada)->where('nombre', $producto->nombre)->first();
                        $nuevoproducto->cantidad = $nuevoproducto->cantidad + $cantidad;
                        $nuevaroduccion->cantidad_actual = $nuevaroduccion->cantidad_actual - $cantidad;
                        $nuevaroduccion->exportado = $nuevaroduccion->exportado + $cantidad;
                        $nuevoproducto->save();
                        $nuevaroduccion->save();
                        $registro->estado = 'Activo';
                        $registro->save();
                        $descripcion = substr($nuevoproducto->nombre, 0, 20);
                        $descripcion = str_pad($descripcion, $pricePosition - 1, ' ', STR_PAD_RIGHT);
                        $cantidad = str_pad($cantidad, 15, ' ', STR_PAD_RIGHT);
                        $text .= $descripcion . $cantidad  . "\n";
                    } else {
                        $falta = true;
                    }
                } else {
                    $this->comprar = false;
                    $falta = false;
                    $this->emit('error', "No se ha seleccionado sucursal");
                }
            }
            if ($falta == false) {
                if ($this->motivo == "traspaso" && $this->areaseleccionada) {
                    $text .= "


                    -----------------------------------------------------------------------------\n";
                    $totalpago = "TIPO: TRASPASO";
                    $totalpago = substr($totalpago, 0, 20);
                    $totalpago = str_pad($totalpago,  $pricePosition - 1, ' ', STR_PAD_RIGHT);
                    $precio = $costo;
                    $text .=  $totalpago . $precio . "\n";
                    $text .= "



                    -----------------------------------------------------------------------------\n";
                    $data = [
                        'id' => Auth::user()->sesionsucursal,
                        'texto' => $text
                    ];
                    $url = "http://127.0.0.1:5001/imprimirticket";
                    $response = Http::post($url, $data);
                    sleep(1);
                    $url = "http://127.0.0.1:5001/imprimirticket";
                    $response = Http::post($url, $data);


                    DB::table('traspasostexts')->insert([
                        'user_id' => Auth::user()->id,
                        'sucursal_origen' => "ALMACEN DE PRODUCCION",
                        'sucursal_destino' => $this->areaseleccionada,
                        'texto' => $text, // Texto formateado que quieres guardar
                        'fecha' => Carbon::now(), // Inserta la fecha actual
                        'created_at' => now(),
                        'updated_at' => now(),
                        'motivo' => 'traspaso'
                    ]);
                    $this->productos_seleccionados = [];
                    $this->emit('alert', "Traspaso realizado");
                } else {

                    $this->emit('error', "Seleccione sucursal destino");
                }
            } else {
                $this->productos_seleccionados = [];

                $this->emit('error', "No hay suficientes productos disponibles.");
            }
        }
    }
}
