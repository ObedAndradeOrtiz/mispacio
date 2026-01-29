<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Areas;
use Illuminate\Support\Facades\Auth;

class Inventario extends Component
{
    public $presionado = 15;
    public $areas;
    public $sucursalId;
    public $sucursalName;
    public $excelData;

    protected $listeners = ['render' => 'render'];
    public function mount()
    {

        $area = Areas::find(Auth::user()->sesionsucursal);
        $this->sucursalName = $area->area;
    }


    public function handleExcelUpload()
    {
        // Manejar los datos del archivo Excel recibidos del frontend
        $excelData = request()->input('data');

        // Realizar cualquier operación necesaria con los datos del Excel
        $this->excelData = $excelData;

        // Retornar una respuesta si es necesario
        return response()->json(['message' => 'Datos del Excel recibidos correctamente']);
    }
    public function render()
    {
        $this->areas = Areas::all();
        return view('livewire.inventario');
    }
}
