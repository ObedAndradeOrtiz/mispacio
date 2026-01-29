<?php

namespace App\Http\Livewire\Crm;

use Livewire\Component;
use App\Events\EnviarMensaje;
use App\Models\JsonGuardado;
use Illuminate\Support\Facades\Auth;

class AbrirJson extends Component
{
    public $jsondelchat;
    public $iduser;
    protected $listeners = ['render' => 'render'];
    public function mount($iduser)
    {
        $this->iduser = $iduser;
    }
    public function render()
    {
        $jsondelchat = JsonGuardado::where('iduser', $this->iduser)->first();
        if ($jsondelchat) {
            // Decodificar el JSON almacenado
            $decodedJson = json_decode($jsondelchat->json, true); // Convertir el JSON en array
        }

        return view('livewire.crm.abrir-json', compact('decodedJson'));
    }
    public function actualizar()
    {
        $this->render();
    }
}
