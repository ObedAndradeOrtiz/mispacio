<?php

namespace App\Http\Livewire\Registros;

use Livewire\Component;
use App\Models\Areas;
use App\Models\Tratamiento;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use App\Models\registroinventario;
use App\Models\Productos;

class RegEdicion extends Component
{
    public $fechaInicioMes;
    public $fechaActual;
    public $areas;
    public $busqueda;
    public $areaseleccionada;
    public $usuarioseleccionado;
    public function mount()
    {
        $this->fechaInicioMes = Carbon::now()->toDateString();
        $this->fechaActual = Carbon::now()->toDateString();
    }
    public function render()
    {
        $this->areas = Areas::where('estado', 'Activo')->get();
        $users = User::where('rol', '!=', 'Cliente')->orderBy('id', 'desc')->get();
        return view('livewire.registros.reg-edicion' ,compact('users'));
    }
}