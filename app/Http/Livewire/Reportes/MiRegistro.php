<?php

namespace App\Http\Livewire\Reportes;

use App\Models\Calls;
use App\Models\Operativos;
use App\Models\Empresas;
use App\Models\Pagos;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Areas;
use App\Models\registroinventario;
use App\Models\Productos;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MiRegistro extends Component
{
    public $fechaInicioMes;
    public $fechaActual;
    public $responsableseleccionado;
    public $responsables;
    public $usuario;
    public $vermisventas = false;
    public function mount()
    {
        $this->fechaInicioMes = Carbon::now()->toDateString();
        $this->fechaActual = Carbon::now()->toDateString();
        $this->responsableseleccionado = Auth::user()->name;
        $this->responsables = User::where('estado', 'Activo')->whereNotIn('rol', ['Recursos Humanos', 'Editor', 'Sistema', 'Administrador', 'TARJETAS', 'Cliente', 'Contador', 'INVENTARIO', 'Asist. Administrativo', 'Jefe Marketing y Publicidad'])->orderBy('name')->get();
    }
    public function render()
    {
        $this->usuario = User::where('name', $this->responsableseleccionado)->first();

        return view('livewire.reportes.mi-registro');
    }
}
