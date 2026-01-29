<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Areas;
use Illuminate\Support\Facades\Auth;
class Comprar extends Component
{public $presionado=26;
    public $areas;
    public $sucursalId;
    public $sucursalName;

    protected $listeners = ['render' => 'render'];
    public function mount()
    {

        $area = Areas::find(Auth::user()->sesionsucursal);
        $this->sucursalName =$area->area;


    }

    public function render()
    {
        $this->areas=Areas::all();
        return view('livewire.comprar');
    }
}
