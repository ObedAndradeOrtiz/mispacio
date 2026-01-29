<?php

namespace App\Http\Livewire\Mensajeria;

use Livewire\Component;
use App\Models\Calls;
use App\Models\Operativos;
use App\Models\Empresas;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Areas;
use App\Models\Mensajes;
use Livewire\WithPagination;
use App\Models\Gastos;
use Illuminate\Support\Facades\DB;

class MisChatInterno extends Component
{
    public $iduser;
    public function mount($iduser)
    {
        $this->iduser = $iduser;
    }
    protected $listeners = ['render' => 'render'];
    public function render()
    {
        if ($this->iduser != 0) {
            $usuario = User::find($this->iduser);
            $mensajes = DB::table('mensajes')
                ->where(function ($query) {
                    $query->where('receptor', Auth::user()->id)
                        ->where('emisor', $this->iduser);
                })
                ->orWhere(function ($query) {
                    $query->where('receptor', $this->iduser)
                        ->where('emisor', Auth::user()->id);
                })
                ->limit(100)
                ->get();
            foreach ($mensajes as $men) {
                if ($men->receptor == Auth::user()->id) {
                    $nuevo = Mensajes::find($men->id);
                    $nuevo->estado = "I";
                    $nuevo->save();
                }
            }
            return view('livewire.mensajeria.mis-chat-interno', compact('usuario', 'mensajes'));
        } else {
            return view('livewire.mensajeria.mis-chat-interno');
        }
    }
}
