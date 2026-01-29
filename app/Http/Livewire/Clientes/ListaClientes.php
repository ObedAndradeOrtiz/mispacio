<?php

namespace App\Http\Livewire\Clientes;


use App\Models\User;
use App\Models\Empresas;
use App\Models\Areas;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class ListaClientes extends Component
{
    use WithPagination;
    public $open = false;
    public $user;
    public $telefono;
    public $busqueda = "";
    public $actividad = "Activo";
    public $empresaseleccionada = "";
    public $fechaInicioMes;
    public $fechaActual;
    public $rango = "";
    protected $listeners = ['render' => 'render'];
    public function mount()
    {
        $this->emit('sacarboton', []);
        $this->fechaInicioMes = date("Y-m-01");
        $this->fechaActual = now()->format('Y-m-d');
    }
    public function render()
    {
        $empresas = Areas::where('estado', 'Activo')->get();
        if ($this->rango == "") {
            $users = User::where(function ($query) {
                $query->OrWhere('name', 'ilike', '%' . $this->busqueda . '%');
                $query->OrWhere('telefono', 'ilike', '%' . $this->busqueda . '%');
            })

                ->where('estado', $this->actividad)->where('rol', 'Cliente')
                ->orderBy('created_at')
                ->paginate(10);
        } else {
            $users = User::where(function ($query) {
                $query->OrWhere('name', 'ilike', '%' . $this->busqueda . '%');
                $query->OrWhere('telefono', 'ilike', '%' . $this->busqueda . '%');
            })
                ->whereBetween('created_at', [$this->fechaInicioMes,  $this->fechaActual])
                ->where('estado', $this->actividad)->where('rol', 'Cliente')
                ->orderBy('created_at')
                ->paginate(10);
        }

        return view('livewire.clientes.lista-clientes', compact('users', 'empresas'));
    }
}
