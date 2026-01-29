<?php

namespace App\Http\Livewire;

use App\Models\inventario_producciones;
use App\Models\InventarioProduccion;
use App\Models\Producto;
use App\Models\Productos;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class InventarioMensualArtesanal extends Component
{
    public string $month;              // "YYYY-MM"
    public int $daysInMonth = 30;

    /** @var array<int, array<int,int>> inputs[producto_id][day] = cantidad */
    public array $inputs = [];

    /** @var array<int, array<int,int>> traspasos[producto_id][day] = total */
    public array $traspasos = [];

    /** Cache de productos artesanales (para render rápido) */
    public $productos;

    public function mount(?string $month = null): void
    {
        $this->month = $month ?: now()->format('Y-m');
        $this->loadMonth();
    }

    public function prevMonth(): void
    {
        $this->month = Carbon::createFromFormat('Y-m', $this->month)->subMonth()->format('Y-m');
        $this->loadMonth();
    }

    public function nextMonth(): void
    {
        $this->month = Carbon::createFromFormat('Y-m', $this->month)->addMonth()->format('Y-m');
        $this->loadMonth();
    }

    public function loadMonth(): void
    {
        $m = Carbon::createFromFormat('Y-m', $this->month)->startOfMonth();
        $this->daysInMonth = $m->daysInMonth;

        $start = $m->copy()->startOfMonth()->toDateString();
        $end   = $m->copy()->endOfMonth()->toDateString();

        // SOLO artesanales (ajusta campo si no es "tipo")
        $this->productos = Productos::query()
            ->where('nombre', 'ilike', '%artesanal%')
            ->orderBy('nombre')
            ->get(['id', 'nombre']);

        $ids = $this->productos->pluck('id')->all();

        // Producciones guardadas => inputs
        $rows = inventario_producciones::query()
            ->whereIn('producto_id', $ids)
            ->whereBetween('fecha', [$start, $end])
            ->get(['producto_id', 'fecha', 'cantidad']);

        $this->inputs = [];
        foreach ($rows as $r) {
            $day = (int) Carbon::parse($r->fecha)->format('j'); // 1..31
            $this->inputs[(int)$r->producto_id][$day] = (int)$r->cantidad;
        }

        // Traspasos automáticos desde registroinventarios
        $trows = DB::table('registroinventarios')
            ->selectRaw('id, DAY(fecha) as dia, SUM(cantidad) as total')
            ->whereIn('id', $ids)
            ->whereBetween('fecha', [$start, $end])
            ->where('motivo', 'traspaso')
            ->where('sucursal', 'ALMACEN CENTRAL')
            ->groupBy('id', DB::raw('DAY(fecha)'))
            ->get();

        $this->traspasos = [];
        foreach ($trows as $tr) {
            $this->traspasos[(int)$tr->producto_id][(int)$tr->dia] = (int)$tr->total;
        }

        // Idea poco común: prellenar ceros SOLO al render, sin ensuciar DB
        // (por eso no llenamos inputs con 0 aquí)
    }

    public function save(): void
    {
        $m = Carbon::createFromFormat('Y-m', $this->month)->startOfMonth();

        $upserts = [];
        foreach ($this->inputs as $productoId => $byDay) {
            foreach ($byDay as $day => $qty) {
                $day = (int)$day;
                if ($day < 1 || $day > $this->daysInMonth) continue;

                $qty = max(0, (int)$qty);
                $fecha = $m->copy()->day($day)->toDateString();

                $upserts[] = [
                    'producto_id' => (int)$productoId,
                    'fecha' => $fecha,
                    'cantidad' => $qty,
                    'updated_at' => now(),
                    'created_at' => now(),
                ];
            }
        }

        if ($upserts) {
            inventario_producciones::upsert(
                $upserts,
                ['producto_id', 'fecha'],
                ['cantidad', 'updated_at']
            );
        }

        $this->dispatchBrowserEvent('notify', ['msg' => 'Producción guardada ✅']);
    }

    public function render()
    {
        return view('livewire.inventario-mensual-artesanal');
    }
}
