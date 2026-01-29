<?php

namespace App\Http\Livewire\Tesoreria;

use App\Models\Calls;
use App\Models\Operativos;
use App\Models\Empresas;
use App\Models\Pagos;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Areas;
use App\Models\registropago;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ListaTesoreria extends Component
{
    use WithPagination;
    public  $total_si_pagado = 0;
    public $total_ingresado = 0;
    public $open = false;
    public $area;
    public $modo = "";
    public $telefono;
    public $busqueda = "";
    public $actividad = "Areas";
    public $tipos = "Sueldo";
    public $idempresa;
    public $verclienteslogic = false;
    public $seguridad = true;
    public $contra = "";
    public $activar = false;
    public $openAreaGasto = false;
    public $tipogasto;
    public $modopago;
    public $fechaInicioMes;
    public $fechaActual;
    public $control = 'general';
    public $seleccionmodo = 'Por fecha';
    public $empresaseleccionada = '';
    public $tipoingreso = 'ingresoexterno';
    protected $listeners = ['render' => 'render'];
    public $openAreaImage = false;
    public $rutaImagen;
    public $sucursal;
    public $consultaarea;
    public $areaslist;
    public  $sumasucursales = [];
    public $sumagastos = [];
    public function mount()
    {
    }

    public function render()
    {
        $user = User::find(Auth::user()->id);
        if ($user->tesoreria == "Activo" || Auth::user()->rol == "Administrador") {
            return view('livewire.tesoreria.lista-tesoreria');
        } else {
            return view('livewire.tesoreria.seguridad');
        }
    }
    public function descargarArchivo($rutaArchivo)
    {
        $this->rutaImagen = 'storage/' . $rutaArchivo;
        $this->openAreaImage = true;
    }
    public function volveratras()
    {
        $this->openAreaImage = false;
    }
    public function confirmar()
    {
        if ($this->contra == "errorerror") {
            $this->seguridad = false;
            if ($this->activar == true) {
                $user = User::find(Auth::user()->id);
                $user->tesoreria = "Activo";
                $user->save();
            }
        } else {
            $this->emit('error', '¡Contraseña incorrecta!');
        }
    }
    public function recargar()
    {
        $this->render();
    }
}
