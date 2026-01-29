<?php

namespace App\Http\Livewire\Mbq;

use App\Models\Areas;
use App\Models\registroinventario;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class ListaMbq extends Component
{
    public $users;
    public $busqueda;
    public $fechaInicioMes;
    public $areaseleccionada;
    public $fechaActual;
    public $fechainicio;
    public $areas;
    public $name;
    public $telefono;
    public $ci;
    public $edad;
    public $sucursal_seleccionada;
    public $crear = false;
    public $cobrar = false;
    public $cantidad_pago = 0;
    public $modo = "efectivo";
    public $id_seleccionado;
    public $pagos_miss;
    public $ver_detalle = false;
    public $fecha_pagada;
    public $descripcion = "";
    protected $rules = [
        'areaseleccionada' => 'nullable',
    ];
    public function mount()
    {
        $this->areaseleccionada = "";
        $this->fechaInicioMes = date("Y-m-01");
        $this->fechaActual = now()->format('Y-m-d');
        $this->fechainicio = now()->format('Y-m-d');
        $this->fecha_pagada = now()->format('Y-m-d');
        $this->areas = Areas::where('estado', 'Activo')->where('area', '!=', 'ALMACEN CENTRAL')->get();
    }
    public function editarmiss($id)
    {
        $this->id_seleccionado = $id;
        $this->cobrar = true;
    }
    public function verdetalle($id)
    {
        $this->pagos_miss = registroinventario::where('iduser', $id)->get();
        $this->ver_detalle = true;
    }
    public function guardarpago()
    {
        $usuario = User::find($this->id_seleccionado);
        $registro = new registroinventario;
        $registro->sucursal = $usuario->sucursal;
        $registro->nombreproducto = "PAGO MBQ";
        $registro->cantidad = $this->cantidad_pago;
        $registro->iduser = $usuario->id;
        $registro->fecha = $this->fecha_pagada;
        $registro->motivo = "mbq";
        $registro->modo = $this->modo;
        $registro->estado = 'Activo';
        $registro->nombreproducto = $this->descripcion;
        $registro->save();
        $this->cobrar = false;

        $this->emit('alert', '¡Pago creado satisfactoriamente!');
    }
    public function render()
    {
        $this->users = User::where('rol', 'mbq')->where('name', 'ilike', '%' . $this->busqueda . '%')
            ->whereBetween('fechainicio', [$this->fechaInicioMes, $this->fechaActual])
            ->where('sucursal', 'ilike', '%' . $this->areaseleccionada . '%')->where('estado', 'Activo')->get();
        $pagoqr = DB::table('registroinventarios')
            ->where('sucursal',  'ilike', '%' . $this->areaseleccionada . '%')
            ->whereBetween('fecha', [$this->fechaInicioMes, $this->fechaActual])
            ->where('modo', 'qr')
            ->where('motivo', 'mbq')
            ->sum('cantidad');
        $pagoefec = DB::table('registroinventarios')
            ->where('sucursal',  'ilike', '%' . $this->areaseleccionada . '%')
            ->whereBetween('fecha', [$this->fechaInicioMes, $this->fechaActual])
            ->where('modo', 'efectivo')
            ->where('motivo', 'mbq')
            ->sum('cantidad');

        return view('livewire.mbq.lista-mbq', compact('pagoqr', 'pagoefec'));
    }
    public function guardartodo()
    {
        $nuevo = new User;
        $nuevo->name = $this->name;
        $nuevo->rol = "mbq";
        $nuevo->tesoreria = "Inactivo";
        $nuevo->telefono = $this->telefono;
        $nuevo->password = Hash::make("MBQ");
        $nuevo->estado = "Activo";
        $nuevo->ci = $this->ci;
        $nuevo->edad = $this->edad;
        $nuevo->sucursal = $this->sucursal_seleccionada;
        $nuevo->fechainicio = $this->fechainicio;
        $nuevo->save();
        $this->reset(['crear']);
        $this->emitTo('users.lista-user', 'render');
        $this->emit('alert', '¡Aspirante creada satisfactoriamente!');
    }
}
