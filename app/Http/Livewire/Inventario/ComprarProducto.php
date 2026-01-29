<?php

namespace App\Http\Livewire\Inventario;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Productos;
use App\Models\Pagos;
use Carbon\Carbon;

class ComprarProducto extends Component
{
    public $idusuario;
    public $productos;
    public $tratamientosSeleccionados = [];
    public $comprar=false;
    public $dinero=0;
    public $seleccionados = [];
    public $totalPagar = 0;

    public function mount($idusuario, $sucursal){
        $this->idusuario=$idusuario;
        $this->productos = Productos::where('estado', 'Activo')->where('sucursal',$sucursal)->get();
    }

    public function restarStock()
    {
        foreach ($this->seleccionados as $productoId => $cantidad) {
            $producto = Productos::find($productoId);
            $producto->cantidad -= $cantidad;
            $producto->save();
            $pago=new Pagos;
            $pago->estado='Comprado';
            $pago->area=$producto->sucursal;
            $pago->iduser=$this->idusuario;
            $pago->fecha=Carbon::now()->toDateString();
            $pago->cantidad=$cantidad;
            $pago->pagado= $producto->precio*$cantidad;
            $pago->idproducto=$productoId;
            $pago->save();
        }

        $this->seleccionados = [];
        $this->comprar=false;
        $this->totalPagar=0;
    }
    public function render()
    {
        return view('livewire.inventario.comprar-producto');
    }
    public function calcularTotal()
    {
        $total = 0;
        foreach ($this->seleccionados as $productoId => $cantidad) {
            $producto = Productos::find($productoId);
            $total += $producto->precio * $cantidad;
        }
        $this->totalPagar = $total;
    }

    public function updatedSeleccionados()
    {
        $this->calcularTotal();
    }

}
