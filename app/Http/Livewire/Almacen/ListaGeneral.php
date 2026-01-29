<?php

namespace App\Http\Livewire\Almacen;

use App\Models\Areas;
use App\Models\categoria_inventario;
use App\Models\Inventario;
use App\Models\Lotes;
use App\Models\Produccion;
use App\Models\Productos;
use App\Models\registroinventario;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class ListaGeneral extends Component
{
    use WithPagination;
    public $crear_transaccion_almacen = false;
    public $crear_distribucion_almacen = false;
    public $crear_lote = false;
    public $opcion = 1;
    public $p_descripcion;
    public $cantidad_creada = 0;
    public $f_creacion;
    public $f_vencimiento;
    public $nombre_lote;
    public $nombre_lotes_seleccionado;
    public $nombre_categoria_seleccionado;
    public $nombre_producto_seleccionado;
    public $descripcion_lote;
    public $f_creacion_lote;
    public $lotes;
    public $all_lotes;
    public $all_produccion;
    public $productos_almacen;
    public $categorias;
    public $categoria_distribucion;
    public $sucursales;
    public $producto_distribuido;
    public $tipo_lote;
    public $lote_seleccionado;
    public $areas;
    public $cantidad_distribuir;
    public $sucursal_seleccionada;
    public $productosdistribucion;
    public $distribucion_realizada;
    public $busqueda = '';
    public $fecha_desde;
    public $fecha_hasta;
    public $productos_lote;
    public $busqueda_lote = '';

    protected $listeners = ['eliminarProduccion' => 'eliminarProduccion', 'eliminarLote' => 'eliminarLote'];
    public function render()
    {
        $this->productos_lote = DB::table('produccions')
            ->select('nombreproducto', DB::raw('SUM(cantidad_actual) as total_cantidad'))
            ->where('nombreproducto', 'ilike', '%' . $this->busqueda_lote . '%')
            ->groupBy('nombreproducto')
            ->orderByDesc('total_cantidad')
            ->get();
        $this->areas = Areas::where('estado', 'Activo')->get();
        $this->categorias = categoria_inventario::where('estado', 'Activo')->get();
        $this->lotes = Lotes::where('tipo', 'ilike', '%' . $this->tipo_lote . '%')->where('estado', 'Activo')->orderBy('fecha', 'desc')->get();
        $this->all_lotes = Lotes::orderBy('fecha', 'desc')->get();


        $this->all_produccion = Produccion::orderBy('fechacreacion', 'desc')->get();
        $this->distribucion_realizada = DB::table('registroinventarios')
            ->select('nombreproducto', 'precio', 'modo', 'fecha', DB::raw('SUM(cantidad) as total_cantidad'))
            ->where('sucursal', 'ALMACEN PRODUCCION')
            ->when($this->busqueda, function ($query) {
                $query->where('nombreproducto', 'ilike', '%' . $this->busqueda . '%');
            })
            ->when($this->fecha_desde, function ($query) {
                $query->whereDate('fecha', '>=', $this->fecha_desde);
            })
            ->when($this->fecha_hasta, function ($query) {
                $query->whereDate('fecha', '<=', $this->fecha_hasta);
            })
            ->groupBy('nombreproducto', 'precio', 'modo', 'fecha')
            ->orderBy('fecha', 'desc')
            ->get();


        $this->productos_almacen = Produccion::where('estado', 'Activo')
            ->where('idlote', 'ilike', '%' . $this->lote_seleccionado)->get();
        $this->productosdistribucion = Inventario::where('estado', 'Activo')
            ->where('categoria', 'ilike', '%' . $this->nombre_categoria_seleccionado)->get();
        return view('livewire.almacen.lista-general');
    }
    public function setOpcion($number)
    {
        $this->opcion = $number;
        $this->render();
    }
    public function GuardarProduccion()
    {
        $nuevo = new Produccion;
        $producto = Inventario::find($this->nombre_producto_seleccionado);
        $nuevo->idproducto = $producto->id;
        $nuevo->nombreproducto = $producto->nombre;
        $lote = Lotes::find($this->nombre_lotes_seleccionado);
        $nuevo->idlote = $lote->id;
        $nuevo->nombrelote = $lote->nombre;
        $nuevo->descripcion = $this->p_descripcion;
        $nuevo->cantidad = $this->cantidad_creada;
        $nuevo->cantidad_actual = $this->cantidad_creada;
        $nuevo->exportado = 0;
        $nuevo->fechacreacion = $this->f_creacion;
        $nuevo->fechavencimiento = $this->f_vencimiento;
        $nuevo->estado = 'Activo';
        $nuevo->save();
        $this->crear_transaccion_almacen = false;
        $this->reset();
        $this->emit('alert', '¡Producción creada!');
    }
    public function GuardarLote()
    {
        $this->crear_lote = false;
        $nuevo = new Lotes;
        $nuevo->nombre = $this->nombre_lote;
        $nuevo->descripcion = $this->descripcion_lote;
        $nuevo->fecha = $this->f_creacion_lote;
        $nuevo->estado = 'Activo';
        $nuevo->tipo = $this->tipo_lote;
        $nuevo->save();
        $this->reset();
        $this->emit('alert', '¡Lote creado!');
    }
    public function eliminarLote($idlote)
    {
        $lote = Lotes::find($idlote);
        $nroproduccion = Produccion::where('idlote', $lote->id)->count();
        if ($nroproduccion > 0) {
            $this->emit('error', "Producción en lista, por favor hablar con el Encargado de Sistema");
        } else {
            $lote->delete();
            $this->emit('alert', '¡Lote eliminado!');
        }
    }
    public function GuardarDistribucion()
    {

        if ($this->sucursal_seleccionada) {
            $nuevaroduccion = Produccion::find($this->nombre_producto_seleccionado);
            $producto = Inventario::find($nuevaroduccion->idproducto);
            $lote_select = Lotes::find($this->lote_seleccionado);
            if ($this->cantidad_distribuir > 0) {
                $registro = new registroinventario;
                $sucursal = Areas::find($this->sucursal_seleccionada);
                $registro->modo =  $sucursal->area;
                $registro->sucursal = "ALMACEN DE PRODUCCION";
                $registro->idproducto = $producto->id;
                $registro->precio = $lote_select->nombre;
                $registro->idcliente = $nuevaroduccion->id;
                $registro->nombreproducto = $producto->nombre;
                $registro->cantidad = $this->cantidad_distribuir;
                $registro->iduser = Auth::user()->id;
                $registro->fecha = Carbon::now()->toDateString();
                $registro->motivo =  "traspaso";
                $nuevoproducto = Productos::where('sucursal', $sucursal->area)->where('nombre', $producto->nombre)->first();
                $nuevoproducto->cantidad = $nuevoproducto->cantidad + $this->cantidad_distribuir;
                $nuevaroduccion->cantidad_actual = $nuevaroduccion->cantidad_actual - $this->cantidad_distribuir;
                $nuevaroduccion->exportado = $nuevaroduccion->exportado + $this->cantidad_distribuir;
                $nuevoproducto->save();
                $nuevaroduccion->save();
                $registro->estado = 'Activo';
                $registro->save();
                $this->reset();
                $this->emit('alert', '¡Distribución creada!');
            } else {
                $this->emit('error', "No se colocado una cantidad válida");
            }
        } else {
            $this->emit('error', "No se ha seleccionado sucursal");
        }
    }
    public function eliminarProduccion($idproduccion)
    {
        $lista = Produccion::find($idproduccion);
        if ($lista->exportado > 0) {
            $this->emit('error', "Productos exportados, por favor hablar con el Encargado de Sistema");
        } else {
            $lista->delete();

            $this->reset();
            $this->emit('alert', '¡Produccíon eliminada!');
        }
    }
}
