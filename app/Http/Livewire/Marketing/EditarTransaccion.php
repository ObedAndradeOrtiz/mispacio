<?php

namespace App\Http\Livewire\Marketing;

use App\Models\transacciones;
use Livewire\Component;
use Twilio\Rest\Api\V2010\Account\Usage\Record\ThisMonthList;

class EditarTransaccion extends Component
{
    public $transaccion;
    public function mount($idtrasaccion)
    {
        $this->transaccion = transacciones::find($idtrasaccion);
    }
    public function render()
    {
        return view('livewire.marketing.editar-transaccion');
    }
    public function deleteTranaccion($idtrasaccion){
        
    }
}