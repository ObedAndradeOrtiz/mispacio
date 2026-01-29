<?php

namespace App\Http\Livewire\Marketing;

use Livewire\Component;
use App\Models\Areas;
use App\Models\cuentacomercial;
use App\Models\Tratamiento;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use App\Models\registroinventario;
use App\Models\Productos;
use App\Models\publicidades;
use App\Models\tarjetas;
use App\Models\transacciones;
use Maatwebsite\Excel\Facades\Excel;

class Marketing extends Component
{
    use WithPagination;
    public $botonRecepcion = 'transacciones';
    public $areaseleccionada;
    public $usuarioseleccionado;
    public $cuentaseleccionado;
    public $fechaInicioMes;
    public $fechaActual;
    public $areas;
    public $cuentas;
    public $tarjetas;
    public $creartransaccion = false;
    public $tarjetaseleccionada = "";
    public $tipode = "";
    public $sumasaldo;
    public $sumasaldomi = 0;
    public $saldodistribuido = 0;
    public $saldodingresado = 0;
    public $saldotarjetas = 0;
    public $publicidadActivas = 0;
    public $opcion = 0;

    protected $listeners = ['render' => 'render', 'deleteTransaccion'  => 'deleteTransaccion', 'eliminarPublicidad' => 'eliminarPublicidad'];
    public function mount()
    {
        $this->fechaInicioMes = Carbon::now()->startOfMonth()->toDateString();
        $this->fechaActual = Carbon::now()->toDateString();
    }
    public function setOpcion($num)
    {
        $this->opcion = $num;
    }
    public function render()
    {
        $this->publicidadActivas = publicidades::where('estado', 'Activo')->count();
        if ($this->tipode == '') {
            $registrotransacciones = DB::table('transacciones')
                ->where('fecha', '<=', $this->fechaActual)
                ->where('fecha', '>=', $this->fechaInicioMes)
                ->orderBy('id', 'asc')
                ->paginate(20);
            $registrotransaccionestotal = DB::table('transacciones')
                ->where('fecha', '<=', $this->fechaActual)
                ->where('fecha', '>=', $this->fechaInicioMes)
                ->count();
        } else {
            if ($this->tipode == 'envio') {
                $registrotransacciones = DB::table('transacciones')
                    ->where('fecha', '<=', $this->fechaActual)
                    ->where('fecha', '>=', $this->fechaInicioMes)
                    ->where('idtarjetaprincipal', 'ilike', '%' . $this->tarjetaseleccionada)
                    ->orderBy('id', 'asc')
                    ->paginate(10);
                $registrotransaccionestotal = DB::table('transacciones')
                    ->where('idtarjetaprincipal', 'ilike', '%' . $this->tarjetaseleccionada)
                    ->where('fecha', '<=', $this->fechaActual)
                    ->where('fecha', '>=', $this->fechaInicioMes)
                    ->count();
            } else {
                if ($this->tipode == 'seguro') {
                    $registrotransacciones = DB::table('transacciones')
                        ->where('fecha', '<=', $this->fechaActual)
                        ->where('fecha', '>=', $this->fechaInicioMes)
                        ->where('motivo', 'SEGURO DE TARJETA')
                        ->orderBy('id', 'asc')
                        ->paginate(10);
                    $registrotransaccionestotal = DB::table('transacciones')
                        ->where('idtarjetaprincipal', 'ilike', '%' . $this->tarjetaseleccionada)
                        ->where('fecha', '<=', $this->fechaActual)
                        ->where('fecha', '>=', $this->fechaInicioMes)
                        ->where('motivo', 'SEGURO DE TARJETA')
                        ->count();
                }
                if ($this->tipode == 'enviosaldo') {
                    $registrotransacciones = DB::table('transacciones')
                        ->where('fecha', '<=', $this->fechaActual)
                        ->where('fecha', '>=', $this->fechaInicioMes)
                        ->where('motivo', 'AUMENTO DE SALDO')
                        ->orderBy('id', 'asc')
                        ->paginate(10);
                    $registrotransaccionestotal = DB::table('transacciones')
                        ->where('idtarjetaprincipal', 'ilike', '%' . $this->tarjetaseleccionada)
                        ->where('fecha', '<=', $this->fechaActual)
                        ->where('fecha', '>=', $this->fechaInicioMes)
                        ->where('motivo', 'AUMENTO DE SALDO')
                        ->count();
                } else {
                    $registrotransacciones = DB::table('transacciones')
                        ->where('fecha', '<=', $this->fechaActual)
                        ->where('fecha', '>=', $this->fechaInicioMes)
                        ->where('idtarjeta', 'ilike', '%' . $this->tarjetaseleccionada)
                        ->orderBy('id', 'asc')
                        ->paginate(10);
                    $registrotransaccionestotal = DB::table('transacciones')
                        ->where('fecha', '<=', $this->fechaActual)
                        ->where('fecha', '>=', $this->fechaInicioMes)
                        ->where('idtarjeta', 'ilike', '%' . $this->tarjetaseleccionada)
                        ->count();
                }
            }
        }

        $trasacciones = transacciones::where('fecha', '<=', $this->fechaActual)
            ->where('fecha', '>=', $this->fechaInicioMes)->get();
        $primer_dia_del_mes = $this->fechaInicioMes;
        $fecha_actual = $this->fechaActual;
        $saldodis = transacciones::where('fecha', '>=', $primer_dia_del_mes)
            ->where('fecha', '<=', $fecha_actual)
            ->whereIn('idmotivo', [3, 4, 7])
            ->get();
        $saldoin = transacciones::where('fecha', '>=', $primer_dia_del_mes)
            ->where('fecha', '<=', $fecha_actual)
            ->where('motivo', 'AUMENTO DE SALDO')
            ->get();
        $this->saldodingresado = 0;
        foreach ($saldoin as $saldo) {
            $this->saldodingresado += round((float) $saldo->monto, 2);
        }

        $this->saldodistribuido = 0;
        foreach ($saldodis as $item) {

            $this->saldodistribuido += (float) $item->montouso;
        }
        $this->tarjetas = tarjetas::where('estado', 'Activo')->orderBy('nombretarjeta')->get();
        $this->sumasaldo = 0;
        $this->sumasaldomi = 0;
        $this->saldotarjetas = 0;
        foreach ($this->tarjetas as $tarjeta) {
            $this->sumasaldo += (float)  $tarjeta->saldo;

            if ($tarjeta->motivo == '1') {
                $this->sumasaldomi += (float)  $tarjeta->saldo;
            } else {
                $this->saldotarjetas += (float)  $tarjeta->saldo;
            }
        }
        $tot = DB::table('publicidades')->get();
        $this->cuentas = cuentacomercial::where('estado', 'Activo')->get();
        $this->areas = Areas::where('estado', 'Activo')->get();
        $users = User::where('rol', '!=', 'Cliente')->orderBy('id', 'desc')->get();
        return view('livewire.marketing.marketing', compact('users', 'registrotransacciones', 'registrotransaccionestotal', 'tot'));
    }


    public function exportarPagos()
    {
        $fechaActual = $this->fechaActual;  // Asegúrate de que $fechaActual y $fechaInicioMes están definidas
        $fechaInicioMes = $this->fechaInicioMes;

        if (!$fechaActual || !$fechaInicioMes) {
            $this->emit('error', 'Por favor, proporciona ambas fechas.');
            return;
        }

        // Obtener transacciones en el rango de fechas
        $transaccioneslist = transacciones::whereBetween('fecha', [$fechaInicioMes, $fechaActual])
            ->whereNotIn('motivo', ['ENVIO DE SALDO', 'AUMENTO DE SALDO'])
            ->orderBy('fecha', 'asc')
            ->get()
            ->map(function ($transaccion) {
                $transaccion->monto = str_replace(',', '.', $transaccion->montouso);
                return [
                    'MONTO' => $transaccion->montouso,
                    'CODIGO' => $transaccion->codigo,
                    'MOTIVO' => $transaccion->motivo,
                    'FECHA' => $transaccion->fecha,
                    'TARJETA' => $transaccion->tarjeta
                ];
            })->toArray();

        // Emitir el evento con los datos JSON
        $this->emit('exportarExcel', $transaccioneslist);
    }
    public function exportarIngresos()
    {
        $fechaActual = $this->fechaActual;  // Asegúrate de que $fechaActual y $fechaInicioMes están definidas
        $fechaInicioMes = $this->fechaInicioMes;

        if (!$fechaActual || !$fechaInicioMes) {
            $this->emit('error', 'Por favor, proporciona ambas fechas.');
            return;
        }

        // Obtener transacciones en el rango de fechas
        $transaccioneslist = transacciones::whereBetween('fecha', [$fechaInicioMes, $fechaActual])
            ->where('motivo', 'AUMENTO DE SALDO')
            ->orderBy('fecha', 'asc')
            ->get()
            ->map(function ($transaccion) {
                $transaccion->monto = str_replace(',', '.', $transaccion->montouso);
                return [
                    'MONTO' => $transaccion->montouso,
                    'MOTIVO' => $transaccion->motivo,
                    'FECHA' => $transaccion->fecha,
                    'TARJETA' => $transaccion->tarjeta
                ];
            })->toArray();

        // Emitir el evento con los datos JSON
        $this->emit('exportarExcelIngresos', $transaccioneslist);
    }

    public function guardartodo($id)
    {
        $dato =  publicidades::findOrFail($id);
        if ($dato->estado == "Activo") {
            $dato->estado = "Inactivo";
        } else {
            $dato->estado = "Activo";
        }
        $dato->save();
    }
    public function deleteTransaccion($idtrasaccion)
    {
        $trasaccion = transacciones::find($idtrasaccion);
        if ($trasaccion->idmotivo == 1) {
            $cuentaemisora = tarjetas::find($trasaccion->idtarjetaprincipal);
            $cuentareceptora = tarjetas::find($trasaccion->idtarjeta);
            $cuentaemisora->saldo = $cuentaemisora->saldo + $trasaccion->monto;
            $cuentaemisora->save();
            $cuentareceptora->saldo = ($cuentareceptora->saldo + $trasaccion->montouso) - $trasaccion->monto;
            $cuentareceptora->save();
            $trasaccion->delete();
        }
        if ($trasaccion->idmotivo == 2) {
            $cuentaemisora = tarjetas::find($trasaccion->idtarjetaprincipal);
            $cuentaemisora->saldo = $cuentaemisora->saldo - $trasaccion->monto;
            $cuentaemisora->save();
            $trasaccion->delete();
        }
        if ($trasaccion->idmotivo == 3) {
            //PAGO DE PUBLICIDAD MISMA TARJETA
            $cuentaemisora = tarjetas::find($trasaccion->idtarjetaprincipal);
            $cuentaemisora->saldo = $cuentaemisora->saldo + $trasaccion->monto;
            $cuentaemisora->save();
            $trasaccion->delete();
        }
        if ($trasaccion->idmotivo == 4) {
            //PAGO PARA OTRA TARJETA
            $cuentaemisora = tarjetas::find($trasaccion->idtarjetaprincipal);
            $cuentareceptora = tarjetas::find($trasaccion->idtarjeta);
            $cuentaemisora->saldo = $cuentaemisora->saldo + $trasaccion->monto;
            $cuentaemisora->save();
            $cuentareceptora->saldo = ($cuentareceptora->saldo + $trasaccion->montouso) - $trasaccion->monto;
            $cuentareceptora->save();
            $trasaccion->delete();
        }
        if ($trasaccion->idmotivo == 5) {
            //ENVIO DE SALDO DE UNA A OTRA
            $cuentaemisora = tarjetas::find($trasaccion->idtarjetaprincipal);
            $cuentareceptora = tarjetas::find($trasaccion->idtarjeta);
            $cuentaemisora->saldo = $cuentaemisora->saldo + $trasaccion->monto;
            $cuentaemisora->save();
            $cuentareceptora->saldo = $cuentareceptora->saldo - $trasaccion->monto;
            $cuentareceptora->save();
            $trasaccion->delete();
        }
        if ($trasaccion->idmotivo == 6) {
            //DISMINUIR POR VERIFICACION
            $cuentaemisora = tarjetas::find($trasaccion->idtarjetaprincipal);
            $cuentaemisora->saldo = $cuentaemisora->saldo + $trasaccion->monto;
            $cuentaemisora->save();
            $trasaccion->delete();
        }
        if ($trasaccion->idmotivo == 7) {
            //DISMINUIR POR SEGURO
            $cuentaemisora = tarjetas::find($trasaccion->idtarjetaprincipal);
            $cuentaemisora->saldo = $cuentaemisora->saldo + $trasaccion->monto;
            $cuentaemisora->save();
            $trasaccion->delete();
        }
        if ($trasaccion->idmotivo == 8) {
            //MOTIVO OTRO
            $cuentaemisora = tarjetas::find($trasaccion->idtarjetaprincipal);
            $cuentaemisora->saldo = $cuentaemisora->saldo + $trasaccion->monto;
            $cuentaemisora->save();
            $trasaccion->delete();
        }
        $this->render();
    }
    public function eliminarPublicidad($idpu)
    {
        $publicidad = publicidades::find($idpu);
        $publicidad->delete();
        $this->render();
    }
}
