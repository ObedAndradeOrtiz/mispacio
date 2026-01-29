<?php

namespace App\Http\Livewire\Marketing;

use App\Models\campana;
use Livewire\Component;
use Livewire\WithPagination;

class MarkCampanas extends Component
{

    use WithPagination;
    protected $listeners = ['render' => 'render', 'deleteCampana'  => 'deleteCampana'];
    public $campañas;
    public function mount()
    {
        $this->campañas = campana::where('estado', 'Activo')->get();
    }
    public function render()
    {
        $this->campañas = campana::where('estado', 'Activo')->get();
        return view('livewire.marketing.mark-campanas');
    }
    public function deleteCampana($idcampaña)
    {
        $campaña = campana::find($idcampaña);
        if ($campaña) {
            $campaña->delete();
        }
    }
}
