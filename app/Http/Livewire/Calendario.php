<?php

namespace App\Http\Livewire;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\Operativos;
use App\Models\registropago;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;

class Calendario extends Component
{
    public $eventos;
    public function mount()
    {

        $this->eventos = [];
        $all_events = DB::table('operativos')->where('area', Auth::user()->sucursal)->where('estado', 'Pendiente')->get();
        foreach ($all_events as $event) {
            $this->eventos[] = [
                'title' => 'CITA: ' . $event->empresa,
                'start' => $event->fecha . ' ' . $event->hora,
                'end' => $event->fecha . ' ' . $event->hora,
                'color' => '#167D27',
            ];
        }
    }
    public function render()
    {
        return view('livewire.calendario');
    }
}
