<?php

namespace App\Console\Commands;

use App\Models\Productos;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class TareaCada5Minutos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:tarea-cada5-minutos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Productos::chunk(100, function ($productoslist) {
            foreach ($productoslist as $lista) {
                // Hacer todas las sumas necesarias en una sola consulta para cada producto
                $resultados = DB::table('registroinventarios')
                    ->select(
                        DB::raw("SUM(CASE WHEN motivo = 'traspaso' AND sucursal = '$lista->sucursal' THEN cantidad ELSE 0 END) AS traspaso"),
                        DB::raw("SUM(CASE WHEN motivo = 'traspaso' AND modo = '$lista->sucursal' THEN cantidad ELSE 0 END) AS traspasorecibido"),
                        DB::raw("SUM(CASE WHEN motivo = 'nuevacompra' AND sucursal = '$lista->sucursal' THEN cantidad ELSE 0 END) AS compra"),
                        DB::raw("SUM(CASE WHEN motivo IN ('farmacia', 'compra') AND sucursal = '$lista->sucursal' THEN cantidad ELSE 0 END) AS venta"),
                        DB::raw("SUM(CASE WHEN motivo = 'personal' AND sucursal = '$lista->sucursal' THEN cantidad ELSE 0 END) AS gabinete")
                    )
                    ->where('nombreproducto', $lista->nombre)
                    ->whereBetween('fecha', [$lista->fechainicio, Carbon::now()->toDateString()])
                    ->first();

                // Cálculo de la nueva cantidad
                $traspaso = $resultados->traspaso;
                $traspasorecibido = $resultados->traspasorecibido;
                $compra = $resultados->compra;
                $venta = $resultados->venta;
                $gabinete = $resultados->gabinete;

                $lista->cantidad = ($lista->inicio + $traspasorecibido + $compra) - ($traspaso + $venta + $gabinete);
                // $lista->cantidad = $venta;
                // $lista->cantidad = 0;
                $lista->save();
            }
        });
    }
}
