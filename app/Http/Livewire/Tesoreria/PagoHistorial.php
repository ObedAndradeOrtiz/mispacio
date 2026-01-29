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
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PagoHistorial extends Component
{
    public $open = false;
    public  $total_si_pagado = 0;
    public $total_ingresado = 0;
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
    public $totalsumamonto = 0;
    public $totalsumainventario = 0;
    public $totalgastos = 0;
    public $areasData = [];
    public $valoresData = [];
    public $areas;
    public $total_inventario_traspaso;
    public $total_inventario_uso;
    public $total_monto;
    public $total_inventario;
    public function mount()
    {
        $this->fechaInicioMes = Carbon::now()->toDateString();
        $this->fechaActual = Carbon::now()->toDateString();
        $this->areas = Areas::where('estado', 'Activo')->orderBy('id', 'desc')->get();
        foreach ($this->areas as $area) {
            $this->areasData[] = $area->area;
        }
    }
    public function render()
    {
        $internos = User::where('rol', '!=', 'Cliente')->where('name', 'ilike', '%' . $this->busqueda . '%')->where('estado', 'Activo')->get();
        $this->areas = Areas::where('estado', 'Activo')->orderBy('id', 'desc')->get();
        $this->total_inventario_uso = DB::table('registroinventarios')
            ->where('modo', 'ilike', '%' . $this->modo . '%')
            ->where('fecha', '<=', $this->fechaActual)
            ->where('fecha', '>=', $this->fechaInicioMes)
            ->where('sucursal', 'ILIKE', '%' . $this->empresaseleccionada . '%')
            ->where('motivo', 'personal')
            ->get();
        $this->total_inventario_traspaso = DB::table('registroinventarios')
            ->where('fecha', '<=', $this->fechaActual)
            ->where('fecha', '>=', $this->fechaInicioMes)
            ->where('sucursal', 'ILIKE', '%' . $this->empresaseleccionada . '%')
            ->where('motivo', 'traspaso')
            ->get();

        $this->total_monto = DB::table('registropagos')

            ->where('modo', 'ilike', '%' . $this->modo . '%')
            ->where('sucursal', 'ILIKE', $this->empresaseleccionada . '%')
            ->where('fecha', '<=', $this->fechaActual)
            ->where('fecha', '>=', $this->fechaInicioMes)
            ->get();
        $this->total_inventario = DB::table('registroinventarios')

            ->where('modo', 'ilike', '%' . $this->modo . '%')
            ->where('sucursal', 'ILIKE', '%' . $this->empresaseleccionada . '%')
            ->where('fecha', '<=', $this->fechaActual)
            ->where('fecha', '>=', $this->fechaInicioMes)
            ->where('motivo', 'compra')
            ->get();

        return view('livewire.tesoreria.pago-historial', compact('internos'));
    }
}
