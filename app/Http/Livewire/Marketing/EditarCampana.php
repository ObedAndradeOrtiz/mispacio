<?php

namespace App\Http\Livewire\Marketing;

use App\Models\campana;
use Livewire\Component;
use Livewire\WithFileUploads;


class EditarCampana extends Component
{
    use WithFileUploads;
    public $name;
    public $comentario;
    public $crearcuenta = false;
    public $image;
    public $campana;
    public $editar = false;
    protected $rules = [
        'campana.tipo' => 'required',
        'campana.comentario' => 'required',
        'campana.path' => 'required',
    ];
    public function mount($idcampana)
    {
        $this->campana = campana::find($idcampana);
    }
    public function render()
    {
        return view('livewire.marketing.editar-campana');
    }
    public function guardartodo()
    {
        if ($this->image) {
            $image = $this->image->store('public/camp');
            $image = 'camp/' . basename($image);
            $this->campana->path = $image;
        }
        $this->campana->save();
        $this->emitTo('marketing.mark-campanas', 'render');
        $this->emit('alert', '¡Campaña editada satisfactoriamente!');
    }
}
