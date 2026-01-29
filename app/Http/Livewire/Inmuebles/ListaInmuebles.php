<?php

namespace App\Http\Livewire\Inmuebles;

use Livewire\Component;
use App\Models\Inmueble;
use App\Models\Areas;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ListaInmuebles extends Component
{
    use WithPagination;
    public $productos;
    public $actividad = 'Activo';
    public $sucursal = "";
    public $areas;
    protected $listeners = ['render' => 'render', 'handleExcelData' => 'handleExcelData'];
    public $excelFile;
    use WithFileUploads;
    public $excelData;
    public $file;
    public $busqueda;
    public $fecha;

    public function enviar()
    {
        $this->emit('alert', '¡Inventario actulizado satisfactoriamente!');
    }

    public function render()
    {

            $this->fecha = date('Y-m-d');
            $this->areas = Areas::where('estado', 'Activo')->get();
            if ($this->sucursal == "Todas") {
                $productoslist = Inmueble::where('nombre', 'ilike', '%' . $this->busqueda . '%')->paginate(5);
            } else {
                $productoslist = Inmueble::where('sucursal', 'ilike', '%' . $this->sucursal . '%')->where('nombre', 'ilike', '%' . $this->busqueda . '%')->paginate(5);
            }
            return view('livewire.inmuebles.lista-inmuebles', compact('productoslist'));
    }
}