<?php

namespace App\Http\Livewire\Inventario;

use App\Models\Areas;
use App\Models\Gastos;
use App\Models\Productos;
use App\Models\registroinventario;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class CompraProductos extends Component
{
    public $productos;
    public $comprar = false;
    public $search;
    public $motivo = "personal";
    public $cantidades = [];
    public $precios = [];
    public $sucursalseleccionada;
    public $areas;
    public $searchcliente;
    public $clientes;
    public $clienteseleccionado = '';
    public $modo = "efectivo";
    public $pagar = 0;
    public $areaseleccionada;
    public $nombre = '';
    public $fecha;
    public $general = '';
    public $cartera = 'Caja';

    public function mount()
    {
        $sucursal = Areas::where('area', 'CENTRAL URBARI')->first();
        $this->general = Productos::where('sucursal',  $sucursal->area)->get();;
        $this->productos = Productos::where('sucursal',  $sucursal->area)->get();
        $productos =  $this->productos;
        foreach ($productos as $producto) {
            $this->precios[$producto->id] = $producto->precio;
        }

        $this->sucursalseleccionada = $sucursal->id;
        $this->fecha = Carbon::now()->toDateString();
    }

    public function render()
    {

        $this->areas = Areas::where('estado', 'Activo')->get();
        $this->pagar = 0;
        $this->cantidades = array_filter($this->cantidades, function ($cantidad) {
            return $cantidad !== null && $cantidad !== '';
        });



        return view('livewire.inventario.compra-productos');
    }
    public function buscar()
    {
        $sucursal = Areas::find($this->sucursalseleccionada);
        if ($this->search) {
            $this->productos = DB::table('productos')->where('nombre', 'ilike', '%' . $this->search . '%')->where('sucursal', $sucursal->area)->where('estado', 'Activo')->get();
        } else {
            $this->productos = Productos::where('sucursal', $sucursal->area)->get();
        }
        $query = Productos::where('sucursal', $sucursal->area);

        if ($this->search) {
            $query->where('nombre', 'ilike', '%' . $this->search . '%');
        }

        $this->productos = $query->get();
    }
    public function realizarfarmacia()
    {
        $this->cantidades = array_filter($this->cantidades, function ($cantidad) {
            return $cantidad !== null && $cantidad !== '';
        });
        if (empty($this->cantidades)) {
            $this->comprar = false;
            $this->emit('error', 'No se ha seleccionado ninguna cantidad.');
            return;
        } else {
            $descriptionWidth = 44;
            $recepcion = explode(' ', Auth::user()->name);
            $text = "COMPRA DE PRODUCTOS-----------------------------------------------------------------------\n" . "Fecha: " . date('Y-m-d H:i:s') . "\nCaja: " . Auth::user()->sucursal . "\nResponsable: " . implode(' ', array_slice($recepcion, 0, 2)) . "\n-----------------------------------------------------------------------------" . str_pad("\nDESCRIPCIÓN", $descriptionWidth) . str_pad("CANT", 8) . "\n-----------------------------------------------------------------------------\n";
            $lineWidth = 35;
            $pricePosition = intval($lineWidth * 0.7);
            foreach ($this->cantidades as $id => $cantidad) {
                $producto = Productos::find($id);
                $registro = new registroinventario;
                $sucursal = Areas::find($this->sucursalseleccionada);
                $registro->idsucursal = $sucursal->id;
                $registro->sucursal = $sucursal->area;
                $registro->idproducto = $producto->id;
                $registro->nombreproducto = $producto->nombre;
                $registro->cantidad = $cantidad;
                $producto->cantidad = $producto->cantidad + $cantidad;
                $producto->save();
                $registro->precio = 0;
                $registro->iduser = Auth::user()->id;
                $registro->fecha = $this->fecha;
                $registro->motivo = "nuevacompra";
                $registro->modo = $this->modo;
                $registro->estado = 'Activo';
                $registro->save();
                $descripcion = substr($producto->nombre, 0, 20);
                $descripcion = str_pad($descripcion, $pricePosition - 1, ' ', STR_PAD_RIGHT);
                $cantidad = str_pad($cantidad, 8, ' ', STR_PAD_RIGHT);
                $text .= $descripcion . $cantidad  . "\n";
            }
            $text .= "


                -----------------------------------------------------------------------------\n";
            $totalpago = "Modo: COMPRA DE PRODUCTOS";
            $totalpago = substr($totalpago, 0, 20);
            $totalpago = str_pad($totalpago, 8 + $pricePosition - 1, ' ', STR_PAD_RIGHT);
            $precio = 'Bs.' . $this->nombre;
            $text .=  $totalpago . $precio . "\nModo de pago:" . $this->modo;
            $text .= "



                -----------------------------------------------------------------------------\n";
            $data = [
                'id' => Auth::user()->sesionsucursal,
                'texto' => $text
            ];
            DB::table('traspasostexts')->insert([
                'user_id' => Auth::user()->id,
                'sucursal_origen' => $sucursal->area,
                'sucursal_destino' => $sucursal->area,
                'texto' => $text, // Texto formateado que quieres guardar
                'fecha' => Carbon::now(), // Inserta la fecha actual
                'created_at' => now(),
                'updated_at' => now(),
                'motivo' => 'compra'
            ]);
            $url = "http://127.0.0.1:5001/imprimirticket";
            $response = Http::post($url, $data);
            sleep(1);
            $response = Http::post($url, $data);
            $nuevo = new Gastos;
            $nuevo->idarea =  $sucursal->id;
            $nuevo->area = $sucursal->area;
            $nuevo->modo = $this->modo;
            $nuevo->fechainicio =  $this->fecha;
            $nuevo->cantidad = doubleval($this->nombre);
            $nuevo->empresa = "";
            $nuevo->pertence = $this->cartera;
            $nuevo->iduser =  Auth::user()->id;
            $nuevo->nameuser = Auth::user()->name;
            $nuevo->tipo = "MATERIAL DE PROCEDIMIENTOS";
            $nuevo->save();
            $this->comprar = false;
            $this->cantidades = [];
            $productos = Productos::where('estado', 'Activo')->get();
            foreach ($productos as $producto) {
                $this->precios[$producto->id] = $producto->precio;
            }
            $this->emit('alert', "COMPRA REALIZADA");
        }
    }
}