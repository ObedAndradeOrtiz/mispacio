<?php

namespace App\Http\Livewire\Pagos;

use App\Events\EnviarMensaje;
use App\Models\Areas;
use App\Models\Tratamiento;
use App\Models\HistorialCliente;
use App\Models\Operativos;
use App\Models\Pagos;
use App\Models\registropago;
use App\Models\TratamientoCliente;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use PhpOffice\PhpSpreadsheet\Calculation\Engine\Operands\Operand;

class ListaPagos extends Component
{
    public $pagos;
    public $registro;
    public $editar = false;
    public $editaraux = false;
    public $idoperativo;
    public $tratamiento;
    public $tratamientos;
    public $eliminarboton = false;
    protected $listeners = ['render' => 'render', 'eliminar' => 'eliminar'];
    protected $rules = [
        'registro.monto' => 'required',
        'registro.modo' => 'required',
        'registro.fecha' => 'required',
        'registro.comentario' => 'required'
    ];
    public function mount($idoperativo)
    {
        $this->idoperativo = $idoperativo;
        $operativo = Operativos::find($idoperativo);
        $this->tratamientos = HistorialCliente::where('idcliente', $operativo->idempresa)->get();
    }
    public function editarpago($idregistro)
    {
        $this->registro = registropago::find($idregistro);
        $this->editar = true;
    }
    public function preeliminar($idregistro)
    {
        $this->registro = registropago::find($idregistro);
        $this->eliminarboton = true;
    }
    public function guardartodo()
    {
        $this->validate([
            'registro.monto' => 'required|numeric|min:0',  // Verifica que el monto sea un número positivo
            'registro.modo' => 'required|in:Efectivo,Qr',  // Asegura que el modo de pago sea 'efectivo' o 'qr'
            'registro.fecha' => 'required|date',  // Asegura que la fecha sea válida
            'registro.comentario' => 'required|string',  // Asegura que el comentario sea un texto
            'tratamiento' => 'required|filled', // Verifica que 'tratamiento' no sea vacío ni null
        ], [
            'registro.monto.required' => 'El monto del pago es obligatorio.',
            'registro.monto.numeric' => 'El monto debe ser un número válido.',
            'registro.monto.min' => 'El monto debe ser mayor o igual a 0.',
            'registro.modo.required' => 'El modo de pago es obligatorio.',
            'registro.modo.in' => 'El modo de pago debe ser "Efectivo" o "Qr".',
            'registro.fecha.required' => 'La fecha de realización es obligatoria.',
            'registro.fecha.date' => 'La fecha debe ser válida.',
            'registro.comentario.required' => 'El comentario es obligatorio.',
            'registro.comentario.string' => 'El comentario debe ser un texto válido.',
            'tratamiento.required' => 'El tratamiento es obligatorio.',
            'tratamiento.filled' => 'El tratamiento no puede estar vacío.',
        ]);

        // Si la validación pasa, guardamos el registro
        $this->registro->idoperativo = $this->tratamiento;
        $this->registro->save();

        // Emitimos el evento para que se renderice el pago
        $this->emitTo('operativos.pagos-cliente', 'render');

        // Emitimos un evento para mostrar la alerta de éxito
        $this->emit('alert', '¡Pago editado!');
        $this->editar = false;

        // Volvemos a renderizar si es necesario
        $this->render();
    }

    public function eliminarPago()
    {
        $this->registro->delete();
        $this->emitTo('operativos.pagos-cliente', 'render');
        $this->eliminarboton = false;
        $this->emit('alert', '¡Pago eliminado!');
    }

    public function render()
    {
        $operativo = Operativos::find($this->idoperativo);
        $this->pagos = registropago::where('idcliente', $operativo->idempresa)->orderBy('fecha', 'desc')->get();
        return view('livewire.pagos.lista-pagos');
    }
    public function imprimirpago($idpago)
    {

        $operativo = Operativos::find($this->idoperativo);
        $pago = registropago::find($idpago);
        $descriptionWidth = 30;
        $cosmetologa = $pago->cosmetologa;
        $recepcion = explode(' ', Auth::user()->name);
        $area = Areas::find(Auth::user()->sesionsucursal);
        $text = "RECIBO #: " . $area->ticket . "\nFecha: " . date('Y-m-d H:i:s') . "\nCaja: " . Auth::user()->sucursal . "\nRecepcionista: " . implode(' ', array_slice($recepcion, 0, 2)) . "\nCosmetóloga:" . $pago->cosmetologa . "\n" . "Cliente: " . $operativo->empresa . "\n-----------------------------------------------------------------------------" . str_pad("\nDESCRIPCIÓN", $descriptionWidth) . "" . "MONTO\n-----------------------------------------------------------------------------\n";
        $costo =  $pago->monto;
        $lineWidth = 44;
        $pricePosition = intval($lineWidth * 0.7);
        $precio = 'Bs.' . $costo;
        $totalgeneral = 0;
        $totalcosto = 0;
        $total_pagado_lista = 0;
        $tratamientos = HistorialCliente::where('idcliente', $operativo->idempresa)->where('estado', 'Inactivo')->get();
        foreach ($tratamientos as $lista) {
            $total_pagado_lista = DB::table('registropagos')
                ->where('idoperativo', $lista->id)
                ->sum(DB::raw('CAST(monto AS DECIMAL(10, 2))'));
            $descripcion = substr($lista->nombretratamiento, 0, 20);
            $descripcion = str_pad($descripcion, $pricePosition - 1, ' ', STR_PAD_RIGHT);
            $totalgeneral = $totalgeneral + $total_pagado_lista;
            $totalcosto = $totalcosto + $lista->costo;
            $text .= $descripcion . $precio . "\n";
        }
        $precio =  $total_pagado_lista;
        $descripcion = substr("Saldo:", 0, 20);
        $descripcion = str_pad($descripcion, $pricePosition - 1, ' ', STR_PAD_RIGHT);
        $precio = $totalgeneral;
        $text .= $descripcion . ($totalcosto -  $totalgeneral) . "\n";

        $descripcion = substr("Modo de pago:", 0, 20);
        $descripcion = str_pad($descripcion, $pricePosition - 1, ' ', STR_PAD_RIGHT);
        $precio = $pago->modo;
        $text .= $descripcion . $precio . "\n";

        $text .= "



        FIRMA:------------------------------------------------------------------------

        -----------------------------------------------------------------------------\n";
        $data = [
            'id' => Auth::user()->sesionsucursal,
            'texto' => $text
        ];
        $url = "http://127.0.0.1:5001/imprimirticket";
        $response = Http::post($url, $data);
        $this->emit('alert', '¡Impresion enviada!');
    }
}