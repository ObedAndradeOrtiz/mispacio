<?php

namespace App\Http\Livewire\Planilla;

use App\Models\PlanillaSueldo;
use Livewire\Component;

class EditarPlanilla extends Component
{
    public $planillasueldousuario;
    public $nombre;
    public $idcargo;
    public $sucursal;
    public $haber;
    public $sueldohora;
    public $horasdias;
    public $diastrabajados;
    public $horasextras;
    public $bonos;
    public $anticipo;
    public $pagado;
    public $iddelaplanilla;
    protected $rules = [
        'planillasueldousuario.nombre' => 'required',
        'planillasueldousuario.idcargo' => 'required',
        'planillasueldousuario.sucursal' => 'required',
        'planillasueldousuario.haberbasico' => 'numeric',
        'planillasueldousuario.sueldohora' => 'required',
        'planillasueldousuario.horasdias' => 'required',
        'planillasueldousuario.diastrabajados' => 'required',
        'planillasueldousuario.horasextras' => 'required',
        'planillasueldousuario.bonos' => 'required',
        'planillasueldousuario.anticipo' => 'required',
        'planillasueldousuario.pagado' => 'required',
    ];

    public function mount($planillasueldousuario)
    {
        $this->planillasueldousuario = $planillasueldousuario;
    }
    public function render()
    {
        return view('livewire.planilla.editar-planilla');
    }
    public function guardar()
    {
        $this->planilla->save();
    }
}
