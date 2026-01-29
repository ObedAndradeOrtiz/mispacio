<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Areas;
use Illuminate\Support\Facades\Auth;

class Usuarios extends Component
{
    public $presionado=8;
    protected $listeners = ['render'=>'render'];
    public $areas;
    public $sucursalId;
    public $sucursalName;

    public function mount()
    {
       if(Auth::user()->sesionsucursal==0 || Auth::user()->sesionsucursal==null){
        $this->sucursalName = "SPA MIORA GENERAL";
        Auth::user()->sesionsucursal=0;
        Auth::user()->save();

       }else{
        $area = Areas::find(Auth::user()->sesionsucursal);
        $this->sucursalName =$area->area;
       }

    }
    public function render()
    {
        $this->areas=Areas::all();
        return view('livewire.usuarios');
    }
}
