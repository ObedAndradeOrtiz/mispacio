<?php

namespace App\Http\Livewire\Marketing;

use App\Models\cuentacomercial;
use App\Models\publicidades;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class MarkPublicidades extends Component
{
    use WithPagination;
    public $cuentas;
    public $tot;


    protected $listeners = ['render' => 'render', 'eliminarPublicidad' => 'eliminarPublicidad'];
    public function mount() {}
    public function render()
    {
        $this->tot = DB::table('publicidades')->where('estado', 'Activo')->get();
        $this->cuentas = cuentacomercial::where('estado', 'Activo')->get();
        return view('livewire.marketing.mark-publicidades');
    }
    public function eliminarPublicidad($idpu)
    {
        $publicidad = publicidades::find($idpu);
        $publicidad->delete();
        $this->render();
    }
}
