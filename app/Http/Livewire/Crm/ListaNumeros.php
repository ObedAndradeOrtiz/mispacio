<?php

namespace App\Http\Livewire\Crm;

use Livewire\Component;
use App\Models\Areas;
use App\Models\cuentacomercial;
use App\Models\Tratamiento;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use App\Models\registroinventario;
use App\Models\Productos;
use App\Models\publicidades;
use App\Models\tarjetas;
use App\Models\transacciones;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Http;

class ListaNumeros extends Component
{
    use WithPagination;
    public $botonRecepcion = 'transacciones';
    public $areaseleccionada;
    public $usuarioseleccionado;
    public $cuentaseleccionado;
    public $fechaInicioMes;
    public $fechaActual;
    public $areas;
    public $cuentas;
    public $tarjetas;
    public $creartransaccion = false;
    public $tarjetaseleccionada = "";
    public $tipode = "";
    public $sumasaldo;
    public $sumasaldomi = 0;
    public $saldodistribuido = 0;
    public $saldodingresado = 0;
    public $saldotarjetas = 0;
    public $publicidadActivas = 0;
    public $opcion = 0;
    public $crearnumero = false;
    public $sessionName;
    public $qrCode;

    public function startSession()
    {
        $this->sessionName = 'session_' . time(); // Nombre único para la sesión

        // Hacer la solicitud POST para iniciar la sesión
        $response = Http::post('http://localhost:3000/start-session', [
            'sessionName' => $this->sessionName,
        ]);

        // Verificar si la solicitud fue exitosa
        if ($response->successful()) {
            $this->dispatchBrowserEvent('session-started', ['sessionName' => $this->sessionName]);
            $this->waitForQRCode();
        } else {
            // Manejar error si la sesión no pudo ser iniciada
            session()->flash('error', 'No se pudo iniciar la sesión');
        }
    }

    public function waitForQRCode()
    {
        // Hacer la solicitud GET para obtener el QR
        $maxAttempts = 50;
        $attempts = 0;
        $qrUrl = 'http://localhost:3000/get-qr/' . $this->sessionName;

        while ($attempts < $maxAttempts) {
            $attempts++;
            $response = Http::get($qrUrl);

            if ($response->successful()) {
                $this->qrCode = $response->json()['qrCode'];
                $this->dispatchBrowserEvent('qr-received', ['qrCode' => $this->qrCode]);
                break;
            } else {
                // Esperar 3 segundos antes de reintentar
                sleep(3);
            }
        }

        if (!$this->qrCode) {
            session()->flash('error', 'No se pudo obtener el QR. Inténtalo de nuevo.');
        }
    }

    public function render()
    {
        return view('livewire.crm.lista-numeros');
    }
}