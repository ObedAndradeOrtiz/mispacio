<?php

namespace App\Http\Livewire\Tesoreria;

use App\Models\Calls;
use App\Models\Operativos;
use App\Models\Empresas;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Areas;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class EgresoExterno extends Component
{
    public $busqueda;
    public $fechaInicioMes;
    public $fechaActual;
    public function mount()
    {
        $this->fechaInicioMes = Carbon::now()->startOfMonth()->toDateString();
        $this->fechaActual = Carbon::now()->toDateString();
    }

    public function render()
    {
        $areas = Areas::where('area', 'like', '%' . $this->busqueda . '%')->where('estado', 'Activo')->orderBy('id', 'desc')->latest()->limit(10)->get();
        return view('livewire.tesoreria.egreso-externo', compact('areas'));
    }
}
