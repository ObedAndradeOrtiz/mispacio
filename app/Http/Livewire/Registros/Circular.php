<?php

namespace App\Http\Livewire\Registros;

use Livewire\Component;
use App\Models\Areas;
use Illuminate\Support\Facades\DB;

class Circular extends Component
{
    public $item;
    public $fechaInicioMes;
    public $fechaActual;
    public $totalcitas;
    public $asistido;
    public $noasistido;

    public function mount($item, $fechaInicioMes, $fechaActual)
    {
        $this->item = $item;
        $this->fechaInicioMes = $fechaInicioMes;
        $this->fechaActual = $fechaActual;
    }

    public function render()
    {
        $this->totalcitas = DB::table('operativos')
            ->where('area', $this->item->area)
            ->whereDate('fecha', '>=', $this->fechaInicioMes)
            ->whereDate('fecha', '<=', $this->fechaActual)
            ->count();

        $this->asistido = DB::table('registropagos')
            ->select('idoperativo')
            ->where('sucursal', $this->item->area)
            ->whereDate('fecha', '>=', $this->fechaInicioMes)
            ->whereDate('fecha', '<=', $this->fechaActual)
            ->distinct('idoperativo')
            ->count();

        $this->noasistido = $this->totalcitas - $this->asistido;
        $this->emit('graficoActualizado', $this->asistido, $this->noasistido);

        return view('livewire.registros.circular');
    }
}
