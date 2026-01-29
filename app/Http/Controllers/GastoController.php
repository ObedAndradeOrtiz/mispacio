<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class GastoController extends Controller
{
    public function exportPdf(Request $request)
    {
        $areaseleccionada = $request->input('areaseleccionada');
        $fechaInicioMes = $request->input('fechaInicioMes');
        $fechaActual = $request->input('fechaActual');
        $usuarioseleccionado = $request->input('usuarioseleccionado');
        $tipogasto = $request->input('tipogasto');

        $query = DB::table('gastos')
            ->select('empresa', 'fecha', 'cantidad', 'tipo', 'modo', 'nameuser', 'area');

        if ($areaseleccionada) {
            $query->where('area', $areaseleccionada);
        }

        if ($fechaInicioMes) {
            $query->where('fechainicio', '>=', $fechaInicioMes);
        }

        if ($fechaActual) {
            $query->where('fechainicio', '<=', $fechaActual);
        }

        if ($usuarioseleccionado) {
            $query->where('nameuser', $usuarioseleccionado);
        }

        if ($tipogasto) {
            $query->where('tipo', $tipogasto);
        }

        $gastoarealista = $query->get();

        $pagado = $gastoarealista->sum('cantidad');

        $data = [
            'gastoarealista' => $gastoarealista,
            'pagado' => $pagado,
        ];

        $pdf = Pdf::loadView('rport.gastos', $data);
        return $pdf->download('gastos_realizados.pdf');
    }
}
