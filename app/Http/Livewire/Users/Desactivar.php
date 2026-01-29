<?php

namespace App\Http\Livewire\Users;

use Livewire\Component;
use App\Models\Calls;
use App\Models\User;
use App\Models\Areas;
use Illuminate\Support\Facades\Auth;
use App\Models\activacion;
use Livewire\WithPagination;

class Desactivar extends Component
{
    public $hour;
    public function mount()
    {
        // $hour = date('H');
        // $minutes = date('i');
        // $this->hour = date('H');
        // if ($hour >= 7 && $hour <= 20) {
        //     User::where('rol', '!=', 'Cliente')
        //         ->where('rol', '!=', 'Administrador')
        //         ->update(['estado' => 'Activo']);

        //     $activado = Activacion::find(1);
        //     $activado->estado = 1;
        //     $activado->save();
        // } else {
        //     if ($hour >= 21  && $minutes >= 45 && $hour <= 21 && $minutes <= 46) {
        //         User::where('rol', '!=', 'Cliente')
        //             ->where('rol', '!=', 'Administrador')
        //             ->update(['estado' => 'Inactivo']);

        //         $activado = Activacion::find(1);
        //         $activado->estado = 0;
        //         $activado->save();
        //     }
        // }
    }
    public function render()
    {

        return view('livewire.users.desactivar');
    }
}
