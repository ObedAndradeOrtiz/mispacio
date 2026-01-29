<?php

namespace App\Http\Livewire\Inventario;

use Livewire\Component;
use App\Models\Productos;
use App\Models\Areas;
use Carbon\Carbon;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;


class ListaInventario extends Component
{
    use WithPagination;
    public $productos;
    public $actividad = 'Activo';
    public $sucursal = "";
    public $areas;
    protected $listeners = ['render' => 'render', 'handleExcelData' => 'handleExcelData'];
    public $excelFile;
    use WithFileUploads;
    public $excelData;
    public $file;
    public $busqueda;
    public $fecha;
    public $vistaproductos = 'saldo';
    public $fechainicio;
    public $fechafinal;
    public $fechaseleccionada;
    public $selectfecha = false;
    public function enviar()
    {
        $this->emit('alert', '¡Inventario actulizado satisfactoriamente!');
    }
    public function mount()
    {
        $this->fechainicio = Carbon::now()->startOfMonth()->toDateString();
        $this->fechafinal = Carbon::now()->toDateString();
        $this->sucursal = Auth::user()->sucursal;
    }
    public function vaciar()
    {
        $productoslist = Productos::where('sucursal', 'ilike', '%' . $this->sucursal . '%')->get();
        foreach ($productoslist as $producto) {
            $producto->cantidadanterior = $producto->cantidad;
            $producto->inicio = 0;
            $producto->cantidad = 0;
            $producto->save();
        }
        $this->emit('alert', '¡Inventario vaciado satisfactoriamente!');
        $this->render();
    }
    public function recuperar()
    {
        $productoslist = Productos::where('sucursal', 'ilike', '%' . $this->sucursal . '%')->get();
        foreach ($productoslist as $producto) {
            $producto->cantidadanterior = $producto->cantidad;
            $producto->cantidad = 0;
            $producto->save();
        }
        $this->emit('alert', '¡Inventario vaciado satisfactoriamente!');
        $this->render();
    }
    public function render()
    {

        $this->fecha = date('Y-m-d');

        $this->areas = Areas::where('estado', 'Activo')->get();
        if ($this->sucursal == "Todas") {
            if ($this->vistaproductos == "") {
                $searchTerms = explode(' ', $this->busqueda);
                $productoslist = Productos::where(function ($query) {
                    if (is_numeric($this->busqueda)) {

                        $query->where('idinventario', $this->busqueda);
                    } else {

                        $query->where('nombre', 'ilike', '%' . $this->busqueda . '%');
                    }
                })
                    ->orderBy('nombre')
                    ->paginate(7);
            } elseif ($this->vistaproductos == "negativo") {
                $productoslist = Productos::where('sucursal', 'ilike', '%AUX%')
                    ->where('estado', $this->actividad)
                    ->where('cantidad', '<', 0)
                    ->where(function ($query) {
                        if (is_numeric($this->busqueda)) {
                            $query->where('idinventario', $this->busqueda);
                        } else {
                            $query->where('nombre', 'ilike', '%' . $this->busqueda . '%');
                        }
                    })
                    ->orderBy('nombre')
                    ->paginate(7);
            } else {
                $productoslist = Productos::where('sucursal', 'ilike', '%AUX%')
                    ->where('estado', $this->actividad)
                    ->where('cantidad', '>', 0)
                    ->where(function ($query) {
                        if (is_numeric($this->busqueda)) {
                            $query->where('idinventario', $this->busqueda);
                        } else {
                            $query->where('nombre', 'ilike', '%' . $this->busqueda . '%');
                        }
                    })
                    ->orderBy('nombre')
                    ->paginate(7);
            }
        } else {
            if ($this->vistaproductos == "") {
                $searchTerms = explode(' ', $this->busqueda);
                $productoslist = Productos::where('sucursal', 'ilike', '%' . $this->sucursal . '%')
                    ->where(function ($query) {
                        if (is_numeric($this->busqueda)) {
                            $query->where('idinventario', $this->busqueda);
                        } else {
                            $query->where('nombre', 'ilike', '%' . $this->busqueda . '%');
                        }
                    })
                    ->orderBy('nombre')
                    ->paginate(7);
            } elseif ($this->vistaproductos == "negativo") {
                $productoslist = Productos::where('sucursal', 'ilike', '%' . $this->sucursal . '%')
                    ->where('cantidad', '<', 0)
                    ->where(function ($query) {
                        if (is_numeric($this->busqueda)) {
                            $query->where('idinventario', $this->busqueda);
                        } else {
                            $query->where('nombre', 'ilike', '%' . $this->busqueda . '%');
                        }
                    })
                    ->orderBy('nombre')
                    ->paginate(7);
            } else {
                if ($this->vistaproductos == 'saldo') {
                    $productoslist = Productos::where('sucursal', 'ilike', '%' . $this->sucursal . '%')
                        ->where('cantidad', '>', 0)
                        ->where(function ($query) {
                            if (is_numeric($this->busqueda)) {
                                $query->where('idinventario', $this->busqueda);
                            } else {
                                $query->where('nombre', 'ilike', '%' . $this->busqueda . '%');
                            }
                        })
                        ->orderBy('nombre')
                        ->paginate(7);
                } else {
                    $productoslist = Productos::where('sucursal', 'ilike', '%' . $this->sucursal . '%')
                        ->whereNotNull('fechaanterior') // Verifica que 'fechaanterior' no sea nulo
                        ->where(function ($query) {
                            if (is_numeric($this->busqueda)) {
                                $query->where('idinventario', $this->busqueda);
                            } else {
                                $query->where('nombre', 'ilike', '%' . $this->busqueda . '%');
                            }
                        })
                        ->orderBy('nombre')
                        ->paginate(100);
                }
            }
        }

        return view('livewire.inventario.lista-inventario', compact('productoslist'));
    }
    public function guardarInventario()
    {
        if ($this->sucursal != '') {
            $productoslist = Productos::where('sucursal',  $this->sucursal)->get();
            foreach ($productoslist as $producto) {
                $producto->inicio = $producto->cantidad;
                $producto->fechainicio = $this->fechaseleccionada;
                $producto->save();
            }
            $this->emit('alert', '¡Inventario guardado satisfactoriamente!');
        } else {
            $this->emit('error', 'Por favor seleccione una sucursal');
        }
    }
    public function reindexar()
    {
        Productos::where('sucursal',  $this->sucursal)->chunk(100, function ($productoslist) {
            foreach ($productoslist as $lista) {
                // Hacer todas las sumas necesarias en una sola consulta para cada producto
                $resultados = DB::table('registroinventarios')
                    ->select(
                        DB::raw("SUM(CASE WHEN motivo = 'traspaso' AND sucursal = '$lista->sucursal' THEN cantidad ELSE 0 END) AS traspaso"),
                        DB::raw("SUM(CASE WHEN motivo = 'traspaso' AND modo = '$lista->sucursal' THEN cantidad ELSE 0 END) AS traspasorecibido"),
                        DB::raw("SUM(CASE WHEN motivo = 'nuevacompra' AND sucursal = '$lista->sucursal' THEN cantidad ELSE 0 END) AS compra"),
                        DB::raw("SUM(CASE WHEN motivo IN ('farmacia', 'compra') AND sucursal = '$lista->sucursal' THEN cantidad ELSE 0 END) AS venta"),
                        DB::raw("SUM(CASE WHEN motivo = 'personal' AND sucursal = '$lista->sucursal' THEN cantidad ELSE 0 END) AS gabinete")
                    )

                    ->where('nombreproducto', $lista->nombre)
                    ->whereBetween('fecha', [$lista->fechainicio, Carbon::now()->toDateString()])
                    ->first();

                // Cálculo de la nueva cantidad
                $traspaso = $resultados->traspaso;
                $traspasorecibido = $resultados->traspasorecibido;
                $compra = $resultados->compra;
                $venta = $resultados->venta;
                $gabinete = $resultados->gabinete;

                $lista->cantidad = ($lista->inicio + $traspasorecibido + $compra) - ($traspaso + $venta + $gabinete);
                // $lista->cantidad = $venta;
                // $lista->cantidad = 0;
                $lista->save();
            }
        });

        $this->emit('alert', '¡Inventario reindexado satisfactoriamente!');
    }
}