<?php

namespace App\Http\Livewire\Marketing;

use App\Models\Planilla;
use App\Models\planillaplanifiaciondato;
use App\Models\PlanillaSueldo;
use App\Models\User;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Livewire\WithPagination;

class MarkPlanificacion extends Component
{
    use WithPagination;
    public $fechaInicioMes;
    public $fechaActual;
    public $respuesta = [];
    public $textocsv;
    public $busqueda = "";
    public $buscar = false;
    public $nuevo = false;
    public $nombre = "";
    public $tratamiento = "";
    public $eliminar = false;
    public $seleccion = 'planificacion';
    public $tratamientoseleccionado = "";
    public $videoselect = 'videos';

    public function mount()
    {
        $this->fechaInicioMes = Carbon::now()->toDateString();
        $this->fechaActual = Carbon::now()->toDateString();
    }
    public function render()
    {
        $planillas = planillaplanifiaciondato::where(function ($query) {
            $query->OrWhere('nombre', 'ilike', '%' . $this->busqueda . '%');
        })->orderBy('id')->get();
        if ($this->tratamientoseleccionado != "") {
            $planillas = planillaplanifiaciondato::where(function ($query) {
                $query->OrWhere('tratamiento', $this->tratamientoseleccionado);
            })->orderBy('id')->get();
        }
        $tratamientos = planillaplanifiaciondato::select('tratamiento')->where('tratamiento', 'ilike', '%' . $this->busqueda . '%')
            ->selectRaw('count(*) as cantidad')
            ->groupBy('tratamiento')
            ->orderBy('tratamiento')
            ->get();
        $personas = planillaplanifiaciondato::select('nombre')
            ->where('nombre', 'ilike', '%' . $this->busqueda . '%')
            ->selectRaw('count(*) as cantidad')
            ->groupBy('nombre')
            ->orderBy('nombre')
            ->get();
        return view('livewire.marketing.mark-planificacion', compact('planillas', 'tratamientos', 'personas'));
    }
    public function eliminarpanificacion($id)
    {
        $planilladato =  planillaplanifiaciondato::findOrFail($id);
        $planilladato->delete();
        $this->emit('alert', '¡Datos eliminados!');
        $this->render();
    }
    public function guardarnuevo()
    {
        $planilladato =  new  planillaplanifiaciondato();
        $planilladato->nombre = $this->nombre;
        $planilladato->tratamiento = $this->tratamiento;
        $planilladato->save();
        $this->emit('alert', '¡Datos guardados!');
        $this->render();
    }
    public function guardartodo($item, $id)
    {
        $planilladato =  planillaplanifiaciondato::findOrFail($id);
        if ($item == 1) {
            $planilladato->segundos = ($planilladato->segundos == 'SI') ? 'NO' : 'SI';
        }
        if ($item == 2) {
            $planilladato->minutos = ($planilladato->minutos == 'SI') ? 'NO' : 'SI';
        }
        if ($item == 3) {
            $planilladato->segundosv = ($planilladato->segundosv == 'SI') ? 'NO' : 'SI';
        }
        if ($item == 4) {
            $planilladato->minutosv = ($planilladato->minutosv == 'SI') ? 'NO' : 'SI';
        }
        if ($item == 5) {
            $planilladato->reel = ($planilladato->reel == 'SI') ? 'NO' : 'SI';
        }
        if ($item == 6) {
            $planilladato->trend = ($planilladato->trend == 'SI') ? 'NO' : 'SI';
        }
        if ($item == 7) {
            $planilladato->tiktok = ($planilladato->tiktok == 'SI') ? 'NO' : 'SI';
        }
        if ($item == 8) {
            $planilladato->curso = ($planilladato->curso == 'SI') ? 'NO' : 'SI';
        }
        if ($item == 9) {
            $planilladato->franquicia = ($planilladato->franquicia == 'SI') ? 'NO' : 'SI';
        }
        if ($item == 10) {
            $planilladato->sucursal = ($planilladato->sucursal == 'SI') ? 'NO' : 'SI';
        }
        if ($item == 11) {
            $planilladato->gifcard = ($planilladato->gifcard == 'SI') ? 'NO' : 'SI';
        }
        if ($item == 12) {
            $planilladato->live = ($planilladato->live == 'SI') ? 'NO' : 'SI';
        }
        if ($item == 13) {
            $planilladato->album = ($planilladato->album == 'SI') ? 'NO' : 'SI';
        }
        if ($item == 14) {
            $planilladato->consulta = ($planilladato->consulta == 'SI') ? 'NO' : 'SI';
        }
        if ($item == 15) {
            $planilladato->horario = ($planilladato->horario == 'SI') ? 'NO' : 'SI';
        }
        if ($item == 16) {
            $planilladato->gifcardimg = ($planilladato->gifcardimg == 'SI') ? 'NO' : 'SI';
        }
        if ($item == 17) {
            $planilladato->cursoimg = ($planilladato->cursoimg == 'SI') ? 'NO' : 'SI';
        }
        if ($item == 18) {
            $planilladato->festejo = ($planilladato->festejo == 'SI') ? 'NO' : 'SI';
        }
        if ($item == 19) {
            $planilladato->carrusel = ($planilladato->carrusel == 'SI') ? 'NO' : 'SI';
        }
        if ($item == 20) {
            $planilladato->nuevomodelo = ($planilladato->nuevomodelo == 'SI') ? 'NO' : 'SI';
        }
        if ($item == 21) {
            $planilladato->procedimiento = ($planilladato->procedimiento == 'SI') ? 'NO' : 'SI';
        }
        if ($item == 22) {
            $planilladato->editarcontenido = ($planilladato->editarcontenido == 'SI') ? 'NO' : 'SI';
        }
        $planilladato->save();
        $this->render();
    }

    public function cargardata()
    {
        $lineas = explode("\n", $this->textocsv);
        foreach ($lineas as $linea) {
            $datos = explode(';', $linea);
            $NOMBRE = "";
            $TRATAMIENTO = "";
            $SEGUNDOS = "";
            $MINUTOS = "";
            $SEGUNDOSV = "";
            $MINUTOSV = "";
            $REEL = "";
            $TREND = "";
            $TIKTOK = "";
            $CURSO = "";
            $FRANQUICIA = "";
            $SUCURSAL = "";
            $GIFCARD = "";
            $LIVE = "";
            $ALBUM = "";
            $CONSULTA = "";
            $HORARIO = "";
            $GIFCARDIMG = "";
            $CURSOIMG = "";
            $FESTEJO = "";
            list(
                $NOMBRE,
                $TRATAMIENTO,
                $SEGUNDOS,
                $MINUTOS,
                $SEGUNDOSV,
                $MINUTOSV,
                $REEL,
                $TREND,
                $TIKTOK,
                $CURSO,
                $FRANQUICIA,
                $SUCURSAL,
                $GIFCARD,
                $LIVE,
                $ALBUM,
                $CONSULTA,
                $HORARIO,
                $GIFCARDIMG,
                $CURSOIMG,
                $FESTEJO,
            ) = $datos;
            $planilla = new planillaplanifiaciondato;

            if (!empty($NOMBRE)) {
                $planilla->nombre = strtoupper($NOMBRE);
            }
            if (!empty($TRATAMIENTO)) {
                $planilla->tratamiento = strtoupper($TRATAMIENTO);
            }
            if (!empty($SEGUNDOS)) {
                $planilla->segundos = strtoupper($SEGUNDOS);
            }
            if (!empty($MINUTOS)) {
                $planilla->minutos = strtoupper($MINUTOS);
            }
            if (!empty($SEGUNDOSV)) {
                $planilla->segundosv = strtoupper($SEGUNDOSV);
            }
            if (!empty($MINUTOSV)) {
                $planilla->minutosv = strtoupper($MINUTOSV);
            }
            if (!empty($REEL)) {
                $planilla->reel = strtoupper($REEL);
            }
            if (!empty($TREND)) {
                $planilla->trend = strtoupper($TREND);
            }
            if (!empty($TIKTOK)) {
                $planilla->tiktok = strtoupper($TIKTOK);
            }
            if (!empty($CURSO)) {
                $planilla->curso = strtoupper($CURSO);
            }
            if (!empty($FRANQUICIA)) {
                $planilla->franquicia = strtoupper($FRANQUICIA);
            }
            if (!empty($SUCURSAL)) {
                $planilla->sucursal = strtoupper($SUCURSAL);
            }
            if (!empty($GIFCARD)) {
                $planilla->gifcard = strtoupper($GIFCARD);
            }
            if (!empty($LIVE)) {
                $planilla->live = strtoupper($LIVE);
            }
            if (!empty($ALBUM)) {
                $planilla->album = strtoupper($ALBUM);
            }
            if (!empty($CONSULTA)) {
                $planilla->consulta = strtoupper($CONSULTA);
            }
            if (!empty($HORARIO)) {
                $planilla->horario = strtoupper($HORARIO);
            }
            if (!empty($GIFCARDIMG)) {
                $planilla->gifcardimg = strtoupper($GIFCARDIMG);
            }
            if (!empty($CURSOIMG)) {
                $planilla->cursoimg = strtoupper($CURSOIMG);
            }
            if (!empty($FESTEJO)) {
                $planilla->festejo = strtoupper($FESTEJO);
            }
            $planilla->save();
        }
        $this->emit('alert', '¡Datos procesados!');
    }
}
