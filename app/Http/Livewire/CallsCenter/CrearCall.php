<?php

namespace App\Http\Livewire\CallsCenter;

use App\Models\Calls;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Areas;
use Carbon\Carbon;
use  App\Models\registrollamadas;

class CrearCall extends Component
{
    public $llamada;
    public $fecha;
    public $telefono = "591";
    public $responsable;
    public $estado = "Activo";
    public $crear = false;
    public $areaseleccionada;
    public $empresa;
    public $comentario = "";
    public $ci;
    public $modo = "";
    protected $rules = [
        'areaseleccionada' => 'required',
        'telefono' => 'required',
        'fecha' => 'required',
        'modo' => 'required',
    ];
    public $telefonoExistente = null;

    public function updatedTelefono()
    {
        $this->telefonoExistente = Calls::where('telefono', $this->telefono)->exists();
    }
    public function mount()
    {
        $this->areaseleccionada = Auth::user()->sucursal;
    }
    public function render()
    {
        $this->fecha = Carbon::now()->toDateString();
        $areas = Areas::where('estado', 'Activo')->where(function ($query) {
            $query->whereNull('almacen')
                ->orWhere('almacen', '');
        })->orderBy('id', 'desc')->get();
        return view('livewire.calls-center.crear-call', compact('areas'));
    }
    public function guardartodo()
    {

        $this->validate();
        $telefonoExistente = Calls::where('telefono', $this->telefono)->exists();
        if ($telefonoExistente) {
            $this->emitTo('calls-center.lista-call', 'render');
            $this->emit('error', '¡Telefono ya registrado!');
        } else {
            $nuevo = new Calls;
            if ($this->empresa == '') {
                $nuevo->empresa = "Sin nombre";
            } else {
                $nuevo->empresa = $this->empresa;
            }

            $nuevo->area = $this->areaseleccionada;
            $nuevo->fecha = $this->fecha;
            $nuevo->telefono = $this->telefono;
            $nuevo->comentario = $this->comentario;
            if ($this->comentario == "") {
                $nuevo->comentario = "";
            } else {
                $nuevo->comentario = $this->comentario;
            }
            $nuevo->responsable = Auth::user()->name;
            $nuevo->ci = $this->ci;
            $nuevo->estado = "llamadas";
            $nuevo->cantidad = 1;
            $nuevo->modo = $this->modo;
            $nuevo->save();
            $registro = new registrollamadas();
            $registro->idllamada = $nuevo->id;
            $registro->telefono = $this->telefono;
            $registro->iduser = Auth::user()->id;
            $registro->responsable = Auth::user()->name;
            $registro->fecha = $this->fecha;
            $registro->estado = 'Activo';
            $registro->sucursal = '0';
            $registro->save();
            $this->reset(['crear', 'telefono', 'empresa', 'areaseleccionada', 'comentario']);
            $this->emitTo('calls-center.lista-call', 'render');
            $this->emit('alert', '¡Llamada creada satisfactoriamente!');
        }
    }
}
