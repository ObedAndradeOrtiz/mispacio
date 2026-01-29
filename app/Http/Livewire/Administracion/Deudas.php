<?php

namespace App\Http\Livewire\Administracion;

use App\Models\User;
use App\Models\Empresas;
use App\Models\Areas;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class Deudas extends Component
{
    use WithPagination;
    public $open = false;
    public $user;
    public $telefono;
    public $busqueda = "";
    public $actividad = "Activo";
    public $empresaseleccionada = "";
    public $sucursalseleccionada = "";
    public $sucursales;
    public $anio;
    public $mes;
    protected $listeners = ['render' => 'render'];
    public function mount()
    {
        $this->emit('sacarboton', []);
        $this->sucursales = Areas::where('estado', 'Activo')->get();
        $this->anio = Carbon::now()->year;
        $this->mes = Carbon::now()->month;
    }
    public function render()
    {
        $empresas = Areas::where('estado', 'Activo')->get();
        $users = DB::table('users as u')
            ->leftJoin('historial_clientes as hc', DB::raw('CAST(hc.idcliente AS INTEGER)'), '=', 'u.id')
            ->leftJoin('registropagos as rp', 'hc.id', '=', 'rp.idoperativo')
            ->select([
                'u.id as id_cliente',
                'rp.sucursal',
                'u.name as nombre_cliente',
                'u.telefono',
                'hc.id as id_historial',
                'hc.idtratamiento',
                'hc.nombretratamiento',
                DB::raw('COUNT(rp.id) as total_pagos'),
                'hc.costo',
                DB::raw('COALESCE(SUM(CAST(rp.monto AS NUMERIC)), 0) as total_monto_pagado'),
                DB::raw('hc.costo - COALESCE(SUM(CAST(rp.monto AS NUMERIC)), 0) as deuda'),
                DB::raw('MAX(rp.fecha) as ultima_fecha_pago') // Última fecha de pago
            ])
            ->where('u.rol', 'Cliente')
            ->where('u.telefono', 'ilike', '%' . $this->busqueda . '%')
            ->where('rp.sucursal', 'ilike', '%' . $this->sucursalseleccionada . '%')
            ->where('hc.nombretratamiento', 'ILIKE', '%PAQ%')
            ->when($this->anio && $this->mes, function ($query) {
                return $query->whereRaw("TO_CHAR(TO_DATE(rp.fecha, 'YYYY-MM-DD'), 'YYYY') = ? AND TO_CHAR(TO_DATE(rp.fecha, 'YYYY-MM-DD'), 'MM') = ?", [$this->anio, str_pad($this->mes, 2, '0', STR_PAD_LEFT)]);
            })
            ->groupBy('u.id', 'rp.sucursal', 'u.name', 'u.telefono', 'hc.id', 'hc.idtratamiento', 'hc.nombretratamiento', 'hc.costo')
            ->havingRaw('hc.costo - COALESCE(SUM(CAST(rp.monto AS NUMERIC)), 0) > 0')
            ->havingRaw('COALESCE(SUM(CAST(rp.monto AS NUMERIC)), 0) > 0')
            ->orderBy('u.id')
            ->orderBy('hc.id')
            ->paginate(10);



        return view('livewire.administracion.deudas', compact('users', 'empresas'));
    }
    public function copiarConsultaAlPortapapeles()
    {
        $resultados = DB::table('users as u')
            ->leftJoin('historial_clientes as hc', DB::raw('CAST(hc.idcliente AS INTEGER)'), '=', 'u.id')
            ->leftJoin('registropagos as rp', 'hc.id', '=', 'rp.idoperativo')
            ->select([
                'u.id as id_cliente',
                'rp.sucursal',
                'u.name as nombre_cliente',
                'u.telefono',
                'hc.id as id_historial',
                'hc.idtratamiento',
                'hc.nombretratamiento',
                DB::raw('COUNT(rp.id) as total_pagos'),
                'hc.costo',
                DB::raw('COALESCE(SUM(CAST(rp.monto AS NUMERIC)), 0) as total_monto_pagado'),
                DB::raw('hc.costo - COALESCE(SUM(CAST(rp.monto AS NUMERIC)), 0) as deuda'),
                DB::raw('MAX(rp.fecha) as ultima_fecha_pago') // Última fecha de pago
            ])
            ->where('u.rol', 'Cliente')
            ->where('u.telefono', 'ilike', '%' . $this->busqueda . '%')
            ->where('rp.sucursal', 'ilike', '%' . $this->sucursalseleccionada . '%')
            ->where('hc.nombretratamiento', 'ILIKE', '%PAQ%')
            ->when($this->anio && $this->mes, function ($query) {
                return $query->whereRaw("TO_CHAR(TO_DATE(rp.fecha, 'YYYY-MM-DD'), 'YYYY') = ? AND TO_CHAR(TO_DATE(rp.fecha, 'YYYY-MM-DD'), 'MM') = ?", [$this->anio, str_pad($this->mes, 2, '0', STR_PAD_LEFT)]);
            })
            ->groupBy('u.id', 'rp.sucursal', 'u.name', 'u.telefono', 'hc.id', 'hc.idtratamiento', 'hc.nombretratamiento', 'hc.costo')
            ->havingRaw('hc.costo - COALESCE(SUM(CAST(rp.monto AS NUMERIC)), 0) > 0')
            ->havingRaw('COALESCE(SUM(CAST(rp.monto AS NUMERIC)), 0) > 0')
            ->orderBy('u.id')
            ->orderBy('hc.id')->get();
        $texto = "";
        foreach ($resultados as $resultado) {
            $texto .= $resultado->telefono  . " -" . $resultado->nombre_cliente . " -" . $resultado->nombretratamiento . "\n";
        }
        $texto = addslashes($texto);
        $this->emit('copiarTabla', $texto);
        $this->emit('alert', '¡Números copiados!');
    }
}