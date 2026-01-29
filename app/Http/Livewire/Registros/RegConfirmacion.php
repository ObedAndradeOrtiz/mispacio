<?php

namespace App\Http\Livewire\Registros;

use App\Models\Operativos;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class RegConfirmacion extends Component
{
    public $registros;
    protected $listeners = ['render' => 'render', 'inactivar' => 'inactivar', 'activar' => 'activar'];
    public function mount()
    {
        $this->registros = Operativos::where('ingreso', '1')->get();
    }
    public function render()
    {
        return view('livewire.registros.reg-confirmacion');
    }

    public function inactivar($idCall)
    {
        $operativo = Operativos::find($idCall);
        DB::table('pagos')->where('idoperativo', $operativo->id)->delete();
        DB::table('registropagos')->where('idoperativo', $operativo->id)->delete();
        DB::table('historial_clientes')->where('idoperativo', $operativo->id)->delete();
        $operativo->delete();
        $this->emitTo('registros.reg-confirmacion', 'render');
        $this->emit('alert', '¡Cita eliminada!');
    }
}
