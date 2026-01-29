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
use App\Models\registroinventario;

class EditarPagoProducto extends Component
{
    protected $listeners = ['render' => 'render', 'eliminar' => 'eliminar'];
    protected $rules = [
        'registro.precio' => 'required',
        'registro.modo' => 'required',
        'registro.fecha' => 'required',
    ];
    public $registro;
    public $editar = false;

    public function mount($idregistro)
    {
        $this->registro = registroinventario::find($idregistro);
    }
    public function guardartodo()
    {
        $this->validate();
        $this->registro->save();
        $this->emit('alert', '¡Pago editado!');
    }
    public function render()
    {
        return view('livewire.tesoreria.editar-pago-producto');
    }
    public function eliminar($idregistro)
    {
        $registro = registroinventario::find($idregistro);
        $registro->delete();
    }
}
