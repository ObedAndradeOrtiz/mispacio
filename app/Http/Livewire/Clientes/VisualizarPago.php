<?php

namespace App\Http\Livewire\Clientes;
use Dompdf\Dompdf;
use Illuminate\Support\Facades\View;
use Livewire\Component;
use App\Models\User;

class VisualizarPago extends Component
{
    public $idu;
    public $usuario;
    public $openAreaImage=false;
    public $rutaImagen;
    public function mount($nombre){
        $this->idu=$nombre;
        $this->usuario=User::find($nombre);
    }
    public function descargarArchivo($rutaArchivo)
    {
        $this->rutaImagen='storage/'.$rutaArchivo;
        $this->openAreaImage=true;
    }
    public function render()
    {
        return view('livewire.clientes.visualizar-pago');
    }
    public function downloadPdf()
    {
        $pdf = new Dompdf();
        $pdf->loadHtml(View::make('livewire.clientes.visualizar-pago',$this->idu)->render());
        $pdf->render();
        return response($pdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="documento.pdf"',
        ]);
    }
}
