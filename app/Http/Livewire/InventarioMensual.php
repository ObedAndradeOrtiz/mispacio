<?php

namespace App\Http\Livewire;

use App\Models\Productos;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class InventarioMensual extends Component
{
    public string $mes;
    public int $diasMes;

    // inputs[producto_id][dia]
    public array $inputs = [];

    // traspasos[producto_id][dia]
    public array $traspasos = [];

    public $productos;
    public $buscar = '';

    public function mount()
    {
        $this->mes = now()->format('Y-m');
        $this->cargarMes();
    }

    public function mesAnterior()
    {
        $this->mes = Carbon::createFromFormat('Y-m', $this->mes)->subMonth()->format('Y-m');
        $this->cargarMes();
    }

    public function mesSiguiente()
    {
        $this->mes = Carbon::createFromFormat('Y-m', $this->mes)->addMonth()->format('Y-m');
        $this->cargarMes();
    }

    private function cargarMes()
    {
        $inicio = Carbon::createFromFormat('Y-m', $this->mes)->startOfMonth();
        $fin    = $inicio->copy()->endOfMonth();

        $this->diasMes = $inicio->daysInMonth;

        /* ===============================
           IDS DE PRODUCTOS (solo para cruzar datos)
           =============================== */
        $ids = DB::table('productos')
            ->where('sucursal', 'ALMACEN PRODUCCION')
            ->whereRaw("nombre ILIKE '%artesanal%'")
            ->pluck('id')
            ->toArray();

        /* ===============================
           PRODUCCIÓN (editable)
           =============================== */
        $this->inputs = [];

        $producciones = DB::table('inventario_produccion')
            ->whereIn('idproducto', $ids)
            ->whereBetween(DB::raw('fecha::date'), [
                $inicio->toDateString(),
                $fin->toDateString()
            ])
            ->get(['idproducto', 'fecha', 'cantidad']);

        foreach ($producciones as $p) {
            $dia = (int) Carbon::parse($p->fecha)->format('j');
            $this->inputs[(int)$p->idproducto][$dia] = (int)$p->cantidad;
        }

        /* ===============================
           TRASPASOS (automáticos)
           =============================== */
        $this->traspasos = [];

        $movs = DB::table('registroinventarios')
            ->selectRaw("
                idproducto,
                EXTRACT(DAY FROM (fecha::date)) AS dia,
                SUM(cantidad) AS total
            ")
            ->whereIn('idproducto', $ids)
            ->whereBetween(DB::raw('fecha::date'), [
                $inicio->toDateString(),
                $fin->toDateString()
            ])
            ->where('motivo', 'traspaso')
            ->where('sucursal', 'ALMACEN PRODUCCION')
            ->groupBy('idproducto', 'dia')
            ->get();

        foreach ($movs as $m) {
            $this->traspasos[(int)$m->idproducto][(int)$m->dia] = (int)$m->total;
        }
    }
    public function guardar()
    {
        $inicioMes = Carbon::createFromFormat('Y-m', $this->mes)->startOfMonth();

        /*
        |--------------------------------------------------------------------------
        | 1) GUARDAR PRODUCCIÓN DIARIA
        |--------------------------------------------------------------------------
        */
        foreach ($this->inputs as $productoId => $dias) {
            foreach ($dias as $dia => $cantidad) {
                $dia = (int) $dia;
                if ($dia < 1 || $dia > $this->diasMes) {
                    continue;
                }

                $fecha = $inicioMes->copy()->day($dia)->toDateString();

                DB::table('inventario_produccion')->updateOrInsert(
                    [
                        'idproducto' => (int) $productoId,
                        'fecha'      => $fecha,
                    ],
                    [
                        'cantidad'   => max(0, (int) $cantidad),
                        'updated_at' => now(),
                        'created_at' => now(),
                    ]
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | 2) CALCULAR TOTAL PRODUCCIÓN POR PRODUCTO (P)
        |--------------------------------------------------------------------------
        */
        foreach ($this->inputs as $productoId => $dias) {

            // 🔹 SUMA TOTAL DE PRODUCCIÓN DEL MES
            $totalProduccion = array_sum(
                array_map(fn($v) => max(0, (int)$v), $dias)
            );

            // 🔹 UPDATE DIRECTO (NO acumulativo)
            Productos::where('id', (int)$productoId)->update([
                'inicio'     => $totalProduccion,
                'updated_at' => now(),
            ]);
        }

        $this->dispatchBrowserEvent('ok', [
            'msg' => 'Inventario guardado. Inicio actualizado con total de producción ✅'
        ]);

        $this->cargarMes();
    }

    public function render()
    {
        $this->productos = DB::table('productos')
            ->where('sucursal', 'ALMACEN PRODUCCION')
            ->where('nombre', 'ilike', '%' . $this->buscar . '%')
            ->whereRaw("nombre ILIKE '%artesanal%'")
            ->orderBy('nombre')
            ->get(['id', 'nombre']);

        return view('livewire.inventario-mensual');
    }
}
