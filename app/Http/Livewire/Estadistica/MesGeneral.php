<?php

namespace App\Http\Livewire\Estadistica;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Areas;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MesGeneral extends Component
{
    public $areaslist;
    public $sumasucursales = [];
    public $sumagastos = [];
    public $fechaInicioMes;
    public $fechaActual;
    public $totalingreso = 0;
    public $totalegreso = 0;
    public $mesSeleccionado;
    public $meses = [];
    public $diasDelMes = [];
    public $diasennumero = [];
    public function mount()
    {
        $this->fechaInicioMes = date("Y-m-01");
        $this->fechaActual = now()->format('Y-m-d');
        $this->meses = [
            1 => 'Enero',
            2 => 'Febrero',
            3 => 'Marzo',
            4 => 'Abril',
            5 => 'Mayo',
            6 => 'Junio',
            7 => 'Julio',
            8 => 'Agosto',
            9 => 'Septiembre',
            10 => 'Octubre',
            11 => 'Noviembre',
            12 => 'Diciembre',
        ];
    }
    public function obtenerDiasDelMes()
    {
        if ($this->mesSeleccionado) {
            setlocale(LC_TIME, 'es_ES.utf8');
            $this->diasDelMes = null;
            $this->diasennumero = [];
            switch ($this->mesSeleccionado) {
                case 1:
                    $mes = 'Enero';
                    break;
                case 2:
                    $mes = 'Febrero';
                    break;
                case 3:
                    $mes = 'Marzo';
                    break;
                case 4:
                    $mes = 'Abril';
                    break;
                case 5:
                    $mes = 'Mayo';
                    break;
                case 6:
                    $mes = 'Junio';
                    break;
                case 7:
                    $mes = 'Julio';
                    break;
                case 8:
                    $mes = 'Agosto';
                    break;
                case 9:
                    $mes = 'Septiembre';
                    break;
                case 10:
                    $mes = 'Octubre';
                    break;
                case 11:
                    $mes = 'Noviembre';
                    break;
                case 12:
                    $mes = 'Diciembre';
                    break;
                default:
                    $mes = '';
                    break;
            }
            $numeroDias = cal_days_in_month(CAL_GREGORIAN, $this->mesSeleccionado, date('Y'));
            $this->diasDelMes = [];
            for ($dia = 1; $dia <= $numeroDias; $dia++) {
                $carbonDate = Carbon::create(null, $this->mesSeleccionado, $dia);
                $nombreDia = Carbon::create(null, $this->mesSeleccionado, $dia)->locale('es')->dayName;
                $nombreMes = Carbon::create(null, $this->mesSeleccionado, $dia)->locale('es')->monthName;
                $this->diasDelMes[] = "{$nombreDia} {$dia} de {$nombreMes}";
                $this->diasennumero[] = $carbonDate->format('Y-m-d');
            }
        }
        $this->actualizarGrafico();
    }
    public function actualizarGrafico()
    {
        $areas = Areas::all();
        $this->sumasucursales = [];
        $this->sumagastos = [];
        $this->totalingreso = 0;

        foreach ($this->diasennumero as $dia) {
            $registros = DB::table('registropagos')
                ->where('fecha', $dia)
                ->get();
            $suma = 0;
            foreach ($registros as $registro) {
                $suma += (float) $registro->monto;
            }
            $registros = DB::table('registroinventarios')
                ->where('fecha', $dia)
                ->where(function ($query) {
                    $query->where('motivo', 'compra')
                        ->orWhere('motivo', 'farmacia');
                })
                ->get();
            foreach ($registros as $registro) {
                $suma += (float) $registro->precio;
            }
            $this->sumasucursales[] = $suma;
        }

        $this->areaslist = Areas::pluck('area')->toArray();
        $this->emit('alert', 'Grafico Actulizado');
        $this->emit('actualizarGrafico2', [
            'labels' =>  $this->diasDelMes,
            'datasets' => [
                [
                    'label' => 'INGRESOS',
                    'data' => $this->sumasucursales,
                    'backgroundColor' => 'rgba(0, 99, 132, 0.6)',
                    'borderColor' => 'rgba(0, 99, 132, 1)'
                ]
                // [
                //     'label' => 'GASTOS',
                //     'data' => $this->sumagastos,

                //     'backgroundColor' => 'rgba(99, 132, 0, 0.6)',
                //     'borderColor' => 'rgba(99, 132, 0, 1)'
                // ]
            ]
        ]);
    }
    public function render()
    {

        return view('livewire.estadistica.mes-general');
    }
}
