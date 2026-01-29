<?php

namespace App\Http\Livewire\Tesoreria;

use Livewire\Component;
use App\Models\Calls;
use App\Models\Operativos;
use App\Models\Empresas;
use App\Models\Pagos;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Areas;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\registropago;

class EditarPagoCita extends Component
{
    protected $rules = [
        'registro.monto' => 'required',
        'registro.modo' => 'required',
        'registro.fecha' => 'required',
    ];
    public $registro;
    public $editar = false;
    protected $listeners = ['render' => 'render', 'eliminar' => 'eliminar'];
    public function mount($idregistro)
    {
        $this->registro = registropago::find($idregistro);
    }
    public function render()
    {
        return view('livewire.tesoreria.editar-pago-cita');
    }
    public function guardartodo()
    {
        $this->validate();
        $this->registro->save();
        $this->emitTo('registros.reg-pagos', 'render');
        $this->emitTo('registros.lista-registros', 'render');
        $this->emit('alert', '¡Pago editado!');
    }
   
}