<?php

namespace App\Http\Livewire\Inventario;

use App\Models\Areas;
use App\Models\Productos;
use App\Models\registroinventario;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;

class CargaMasiva extends Component
{
    use WithFileUploads;
    public $sucursal;
    public $areas;
    public $crear = false;
    public $fechaseleccionada;
    public $cantidadactualizada = 0;
    protected $listeners = ['crearCambiado' => 'cambiarCrearFalse', 'datosRecibidos' => 'datosRecibidos'];
    public function mount()
    {
        $this->fechaseleccionada = Carbon::now()->toDateString();
    }
    public function datosRecibidos($data)
    {
        $this->cantidadactualizada = 0;

        $rows = $data['rows'] ?? [];
        $sucursal = trim($data['sucursal'] ?? '');

        foreach ($rows as $idx => $row) {
            // si hay header, salta la primera fila cuando detectes texto
            $nombre = trim((string)($row[0] ?? ''));
            $cantidad = trim((string)($row[1] ?? ''));
            $precio = trim((string)($row[2] ?? ''));

            if ($nombre === '' || $cantidad === '' || $sucursal === '') continue;

            // normaliza NBSP
            $nombre = str_replace("\xC2\xA0", ' ', $nombre);
            $nombre = trim(preg_replace('/\s+/u', ' ', $nombre));

            // normaliza números (si te llegan con coma o Bs)
            $cantidad = (float) str_replace(',', '.', preg_replace('/[^\d,.\-]/', '', $cantidad));
            $precioVal = $precio === '' ? null : (float) str_replace(',', '.', preg_replace('/[^\d,.\-]/', '', $precio));

            $producto = Productos::where('sucursal', $sucursal)
                ->whereRaw("trim(nombre) ILIKE trim(?)", [$nombre])
                ->first();

            if (!$producto) continue;

            $producto->precio = $precioVal ?? $producto->precio;

            $producto->cantidadanterior = $producto->cantidad;     // viejo
            $producto->fechaanterior    = $producto->fechainicio;  // viejo

            $producto->cantidad    = $cantidad;                   // nuevo
            $producto->inicio      = $cantidad;
            $producto->fechainicio = $this->fechaseleccionada;    // nuevo
            $producto->save();

            $this->cantidadactualizada++;
        }

        if (!empty($data['isLast'])) {
            $this->emitTo('inventario.lista-inventario', 'render');
            $this->emit('alert', "¡Inventario actualizado de {$sucursal}! ({$this->cantidadactualizada})");
        }
    }

    public function datosRecibidos1($data)
    {
        $this->cantidadactualizada = 0;
        $this->areas = Areas::where('estado', 'Activo')->get();
        $htmlTable = $data['tablaHTML'];
        $sucursalData = $data['sucursal'];
        $dom = new \DOMDocument();
        $dom->loadHTML($htmlTable);
        $filas = $dom->getElementsByTagName('tr');

        for ($i = 1; $i < $filas->length; $i++) {
            $datosFila = $filas->item($i)->getElementsByTagName('td');
            $nombre = $datosFila->item(0)->nodeValue;
            $cantidad = $datosFila->item(1)->nodeValue;
            $precio = $datosFila->item(2)->nodeValue;
            $sucursal =  $sucursalData;
            if ($nombre !== null && $nombre !== '' && $cantidad !== null && $cantidad !== '') {
                $producto = Productos::where('nombre', $nombre)
                    ->where('sucursal', $sucursal)
                    ->first();
                if ($producto) {
                    $producto->precio = $precio;
                    $producto->cantidadanterior = $producto->cantidad;
                    $producto->cantidad = $cantidad;
                    $producto->inicio = $cantidad;
                    $producto->fechaanterior = $producto->fechainicio;
                    $producto->fechainicio = $this->fechaseleccionada;
                    $producto->cantidadanterior = $cantidad;
                    $producto->fechaanterior = $this->fechaseleccionada;
                    $producto->save();
                }
            }
            $this->cantidadactualizada = $this->cantidadactualizada + 1;
        }
        $this->emitTo('inventario.lista-inventario', 'render');
        $this->emit('alert', '¡Inventario actuliazado de ' . $sucursalData . '!');
    }
    public function datosRecibidos2($data)
    {
        $this->cantidadactualizada = 0;
        $this->areas = Areas::where('estado', 'Activo')->get();
        $htmlTable = $data['tablaHTML'];
        $sucursalData = $data['sucursal'];
        $dom = new \DOMDocument();
        $dom->loadHTML($htmlTable);
        $filas = $dom->getElementsByTagName('tr');

        for ($i = 1; $i < $filas->length; $i++) {
            $datosFila = $filas->item($i)->getElementsByTagName('td');
            $nombre = $datosFila->item(0)->nodeValue;
            $cantidad = $datosFila->item(1)->nodeValue;
            $precio = $datosFila->item(2)->nodeValue;
            $sucursal =  $sucursalData;
            if ($nombre !== null && $nombre !== '' && $cantidad !== null && $cantidad !== '') {
                $producto = Productos::where('nombre', $nombre)
                    ->where('sucursal', $sucursal)
                    ->first();
                if ($producto) {
                    // $producto->precio = $precio;
                    $producto->cantidadanterior = $producto->cantidad;
                    $producto->cantidad = $cantidad;
                    $producto->inicio = $cantidad;
                    $producto->fechaanterior = $producto->fechainicio;
                    $producto->fechainicio = $this->fechaseleccionada;
                    // $producto->cantidadanterior = $cantidad;
                    // $producto->fechaanterior = $this->fechaseleccionada;
                    $producto->save();
                    // $registro = new registroinventario;
                    // $registro->idsucursal = $area->id;
                    // $registro->sucursal = $area->area;
                    // $registro->idproducto = $producto->id;
                    // $registro->nombreproducto = $producto->nombre;
                    // $registro->cantidad = $cantidad;
                    // $registro->iduser = Auth::user()->id;
                    // $registro->fecha = Carbon::now()->toDateString();;
                    // $registro->motivo = "Modificaciones";
                    // $registro->modo = "EXCEL";
                    // $registro->estado = 'Activo';
                    // $registro->save();
                    //     }
                    // } else {
                    // $producto = new Productos;
                    // $producto->nombre = $nombre;
                    // $producto->estado = 'Activo';
                    // $producto->sucursal = $area->area;
                    // $producto->cantidad = 0;
                    // $producto->fechainicio = $this->fechaseleccionada;
                    // $producto->save();
                    //         }
                    //     }
                    // } else {
                    // foreach ($this->areas as $area) {
                    //     $producto = Productos::where('nombre', $nombre)
                    //         ->where('sucursal',  $area->area)
                    //         ->first();
                    //     if ($producto) {
                    //     } else {
                    //         $producto = new Productos;
                    //         $producto->nombre = $nombre;
                    //         $producto->precio = $precio;
                    //         $producto->estado = 'Activo';
                    //         $producto->fechainicio = $this->fechaseleccionada;
                    //         $producto->sucursal =  $area->area;
                    //         $producto->fechainicio = $this->fechaseleccionada;
                    //         if ($area->area == $sucursal) {
                    //             $producto->cantidad = $cantidad;
                    //             // $registro = new registroinventario;
                    //             // $registro->idsucursal = $area->id;
                    //             // $registro->sucursal = $area->area;
                    //             // $registro->idproducto = $producto->id;
                    //             // $registro->nombreproducto = $producto->nombre;
                    //             // $registro->cantidad = $cantidad;
                    //             // $registro->iduser = Auth::user()->id;
                    //             // $registro->fecha = Carbon::now()->toDateString();;
                    //             // $registro->motivo = "Modificaciones";
                    //             // $registro->modo = "EXCEL";
                    //             // $registro->estado = 'Activo';
                    //             // $registro->save();
                    //         } else {
                    //             $producto->cantidad = 0;
                    //         }
                    //         $producto->save();
                    //     }
                    // }
                } else {
                    $nuevo = new Productos;
                    $nuevo->nombre =  $nombre;
                    $nuevo->sucursal = $sucursal;
                    $nuevo->cantidad = 0;
                    $nuevo->save();
                }
            }
            $this->cantidadactualizada = $this->cantidadactualizada + 1;
        }
        // $productoslist = Productos::where('sucursal',  $sucursalData)->get();
        // foreach ($productoslist as $producto) {
        //     $producto->inicio = $producto->cantidad;
        //     $producto->fechainicio = $this->fechaseleccionada;
        //     $producto->save();
        // }
        // Productos::where('sucursal',   $sucursalData)->chunk(100, function ($productoslist) {
        //     foreach ($productoslist as $lista) {
        //         // Hacer todas las sumas necesarias en una sola consulta para cada producto
        //         $resultados = DB::table('registroinventarios')
        //             ->select(
        //                 DB::raw("SUM(CASE WHEN motivo = 'traspaso' AND sucursal = '$lista->sucursal' THEN cantidad ELSE 0 END) AS traspaso"),
        //                 DB::raw("SUM(CASE WHEN motivo = 'traspaso' AND modo = '$lista->sucursal' THEN cantidad ELSE 0 END) AS traspasorecibido"),
        //                 DB::raw("SUM(CASE WHEN motivo = 'nuevacompra' AND sucursal = '$lista->sucursal' THEN cantidad ELSE 0 END) AS compra"),
        //                 DB::raw("SUM(CASE WHEN motivo IN ('farmacia', 'compra') AND sucursal = '$lista->sucursal' THEN cantidad ELSE 0 END) AS venta"),
        //                 DB::raw("SUM(CASE WHEN motivo = 'personal' AND sucursal = '$lista->sucursal' THEN cantidad ELSE 0 END) AS gabinete")
        //             )
        //             ->where('nombreproducto', $lista->nombre)
        //             ->whereBetween('fecha', [$lista->fechainicio, Carbon::now()->toDateString()])
        //             ->first();

        //         // Cálculo de la nueva cantidad
        //         $traspaso = $resultados->traspaso;
        //         $traspasorecibido = $resultados->traspasorecibido;
        //         $compra = $resultados->compra;
        //         $venta = $resultados->venta;
        //         $gabinete = $resultados->gabinete;

        //         $lista->cantidad = ($lista->inicio + $traspasorecibido + $compra) - ($traspaso + $venta + $gabinete);
        //         // $lista->cantidad = $venta;
        //         // $lista->cantidad = 0;
        //         $lista->save();
        //     }
        // });
        $this->emitTo('inventario.lista-inventario', 'render');
        $this->emit('alert', '¡Inventario actuliazado de ' . $sucursalData . '!');
    }

    public function render()
    {
        $this->areas = Areas::where('estado', 'Activo')->get();
        return view('livewire.inventario.carga-masiva');
    }
}
