<?php

namespace App\Http\Livewire;

use App\Models\Areas;
use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Operativos;
use App\Models\registropago;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;

class Calendar extends Component
{
    public $eventos;
    public $areas;
    public $areaseleccionada;
    protected $listeners = ['render' => 'render', 'operativos' => 'operativos'];
    public function render()
    {
        $sucursal = Areas::find(Auth::user()->sucseleccionada);
        if (!$sucursal) {
            $sucursal = Areas::find(1);
            $user = User::find(Auth::user()->id);
            $user->sucseleccionada = 1;
            $user->save();
        }
        $all_events = DB::table('operativos')->where('area',  $sucursal->area)->get();
        foreach ($all_events as $event) {
            $this->eventos[] = [
                'title' => 'CITA: ' . $event->empresa . '-' . $sucursal->area,
                'start' => $event->fecha . ' ' . $event->hora,
                'end' => $event->fecha . ' ' . $event->hora,
                'color' => '#167D27',
            ];
        }
        return view('livewire.calendar');
    }
}
