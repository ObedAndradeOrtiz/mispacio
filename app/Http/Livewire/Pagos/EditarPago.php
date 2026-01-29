<?php

namespace App\Http\Livewire\Pagos;

use App\Models\registropago;
use Livewire\Component;

class EditarPago extends Component
{
    public $pago;
    public $editar = false;
    protected $rules = [
        'pago.monto' => 'required',
        'pago.modo' => 'required',
        'pago.fecha' => 'required',
    ];
    public $monto;
    public $modo;
    public $fecha;
    protected $listeners = ['render' => 'render', 'editar' => 'editar'];
    public function mount($pago)
    {
        $this->pago = $pago;

        $this->monto = $this->pago->monto;
        $this->modo = $this->pago->modo;
        $this->fecha = $this->pago->fecha;
    }
    public function guardartodo()
    {
        $this->pago->monto = $this->monto;
        $this->pago->modo = $this->modo;
        $this->pago->fecha = $this->fecha;
    
        $this->pago->save();
        $this->emitTo('pagos.lista-pagos', 'render');
        $this->emit('alert', '¡Pago editado!');
    }
    public function render()
    {
        return view('livewire.pagos.editar-pago');
    }
}