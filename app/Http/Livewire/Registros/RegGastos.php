<?php

namespace App\Http\Livewire\Registros;

use App\Models\Areas;
use App\Models\Gastos;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class RegGastos extends Component
{
    public $gastoarealista;
    public $fechaInicioMes;
    public $fechaActual;
    public $areas;
    public $usuarioseleccionado = '';
    public $areaseleccionada = '';
    public $users;
    public $tipogasto = '';
    protected $listeners = ['render' => 'render', 'eliminar' => 'eliminar'];

    public function mount()
    {
        $this->fechaInicioMes = Carbon::now()->toDateString();
        $this->fechaActual = Carbon::now()->toDateString();
    }
    public function render()
    {
        $this->users = User::where('rol', '!=', 'Cliente')->orderBy('id', 'desc')->get();
        $this->areas = Areas::where('estado', 'Activo')->get();
        $this->gastoarealista = DB::table('gastos')
            ->where('tipo', 'ilike', '%' . $this->tipogasto . '%')
            ->where('area', 'ilike', '%' . $this->areaseleccionada . '%')
            ->where('nameuser', 'ilike', '%' . $this->usuarioseleccionado . '%')
            ->where('fechainicio', '<=', $this->fechaActual)
            ->where('fechainicio', '>=', $this->fechaInicioMes)
            ->get();
        return view('livewire.registros.reg-gastos');
    }
    public function exportPdf()
    {
        $gastoarealista =
            DB::table('gastos')
            ->select('motivo', 'fecha', 'cantidad', 'tipo', 'modo', 'responsable', 'area')
            ->where('tipo', 'ilike', '%' . $this->tipogasto . '%')
            ->where('area', 'ilike', '%' . $this->areaseleccionada . '%')
            ->where('nameuser', 'ilike', '%' . $this->usuarioseleccionado . '%')
            ->where('fechainicio', '<=', $this->fechaActual)
            ->where('fechainicio', '>=', $this->fechaInicioMes)
            ->get();
        $pagado = $gastoarealista->sum('cantidad');

        $data = [
            'gastoarealista' => $gastoarealista,
            'pagado' => $pagado,
        ];

        $pdf = PDF::loadView('pdf.gastos', $data);
        return $pdf->download('gastos_realizados.pdf');
    }
    public function eliminar($idgasto)
    {
        $misgastos = Gastos::find($idgasto);
        $misgastos->delete();
        $this->render();
    }
}