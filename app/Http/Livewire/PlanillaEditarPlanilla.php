<?php

namespace App\Http\Livewire;

use Livewire\Component;

class PlanillaEditarPlanilla extends Component
{
    public $planillasueldousuario;

    public function mount($planillasueldousuario)
    {
        $this->planillasueldousuario = $planillasueldousuario;
    }
    public function render()
    {
        return view('livewire.planilla-editar-planilla');
    }
}
