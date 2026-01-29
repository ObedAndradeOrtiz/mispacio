<?php

namespace App\Http\Livewire\Tesoreria;

use App\Models\Areas;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class PagoSucursal extends Component
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
    public $totalsumainv = 0;
    public $totalsumainventario = 0;
    public $totalgastos = 0;
    public $areasData = [];
    public $valoresData = [];
    public $areas;
    public $ventadelmes = 0;
    public $gastodelmes = 0;
    public $fechaInicioMesFormateada;
    public function mount()
    {
        $fechaInicioMes = new DateTime('first day of this month');
        $this->fechaInicioMesFormateada = $fechaInicioMes->format('Y-m-d');
        $this->fechaInicioMes = Carbon::now()->toDateString();
        $this->fechaActual = Carbon::now()->toDateString();
        $this->areas = Areas::where('estado', 'Activo')->orderBy('id', 'desc')->get();
        foreach ($this->areas as $area) {
            $this->areasData[] = $area->area;
        }
    }

    public function render()
    {
        $this->areas = Areas::where('estado', 'Activo')->where('area', '!=', 'ALMACEN CENTRAL')->orderBy('id', 'desc')->get();
        foreach ($this->areas as $lista) {
            $total_monto = DB::table('registropagos')
                ->where('idsucursal', $lista->id)
                ->where('modo', 'ilike', '%' . $this->modo . '%')
                ->where('fecha', '<=', $this->fechaActual)
                ->where('fecha', '>=', $this->fechaInicioMes)
                ->sum(DB::raw('CAST(monto AS DECIMAL(10, 2))'));
            $total_inventario = DB::table('registroinventarios')
                ->where('idsucursal', $lista->id)
                ->where('modo', 'ilike', '%' . $this->modo . '%')
                ->whereBetween('fecha', [$this->fechaInicioMes, $this->fechaActual])
                ->where('motivo', 'compra')
                ->sum(DB::raw('CAST(precio AS DECIMAL(10,2)) '))
                + DB::table('registroinventarios')
                ->where('idsucursal', $lista->id)
                ->where('modo', 'ilike', '%' . $this->modo . '%')
                ->whereBetween('fecha', [$this->fechaInicioMes, $this->fechaActual])
                ->where('motivo', 'farmacia')
                ->sum(DB::raw('CAST(precio AS DECIMAL(10,2)) '));

            $gastoarea = DB::table('gastos')
                ->where('idarea', $lista->id)
                ->where('modo', 'ilike', '%' . $this->modo . '%')
                ->where('fechainicio', '<=', $this->fechaActual)
                ->where('fechainicio', '>=', $this->fechaInicioMes)
                ->sum('cantidad');
            $this->valoresData[] = $total_monto + $total_inventario - $gastoarea;
        }
        $this->total_si_pagado = DB::table('registropagos')
            ->where('fecha', '<=',  $this->fechaActual)
            ->where('fecha', '>=',  $this->fechaInicioMes)
            ->sum(DB::raw('CAST(monto AS DECIMAL(10, 2))'));
        $this->total_ingresado = DB::table('registroinventarios')
            ->where('fecha', '<=',  $this->fechaActual)
            ->where('fecha', '>=',  $this->fechaInicioMes)
            ->where('motivo', 'compra')
            ->sum(DB::raw('CAST(precio AS DECIMAL(10, 2))')) + DB::table('registroinventarios')
            ->where('fecha', '<=',  $this->fechaActual)
            ->where('fecha', '>=',  $this->fechaInicioMes)
            ->where('motivo', 'farmacia')
            ->sum(DB::raw('CAST(precio AS DECIMAL(10, 2))'));


        return view('livewire.tesoreria.pago-sucursal');
    }
}