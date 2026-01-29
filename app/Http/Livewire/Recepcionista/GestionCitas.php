<?php

namespace App\Http\Livewire\Recepcionista;

use App\Http\Livewire\Area;
use App\Models\Areas;
use App\Models\Asistidos;
use App\Models\Camillas;
use App\Models\Gabinetes;
use App\Models\HistorialCliente;
use App\Models\JsonGuardado;
use App\Models\MensajesWss;
use App\Models\Operativos;
use App\Models\registropago;
use App\Models\TelefonoWss;
use App\Models\Tratamiento;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use PHPUnit\Event\Runtime\OperatingSystem;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class GestionCitas extends Component
{
    public $agendados;
    public $sucursales;
    public $sucursal_seleccionada;
    public $opcion = 0;
    public $camillas;
    public $crearcamilla = false;
    public $nombrecamilla = 'Camilla';
    public $sucursal_camilla;
    public $cosmetologas;
    public $buscador = '';
    public $gabinetes;
    public $idgabinete;
    public $nombregabinete = '';
    public $creargabinete = false;
    public $chatabierto;
    public $jsonchat;
    public $cargarjson = false;
    public $numerousar;
    public $asistidos;
    public $confirmados;

    protected $listeners = ['cambiarEstadoPaciente', 'render' => 'render'];

    public function mount()
    {
        $this->sucursales = Areas::where('estado', 'Activo')->get();
        $this->sucursal_seleccionada = Auth::user()->sucursal;
        $this->camillas = Camillas::all();
        $this->gabinetes = Gabinetes::all();
    }
    public function setOpcion($num)
    {
        $this->opcion = $num;
        if ($num == 0) {
            $this->emit('actualizarVista');
        }
    }

    public function render()
    {
        $this->confirmados = registropago::where('sucursal', $this->sucursal_seleccionada)
            ->where('fecha', Carbon::now()->toDateString())
            ->distinct('idoperativo')
            ->count('idoperativos');

        $this->agendados = Operativos::where('fecha', Carbon::now()->toDateString())
            ->where('area', $this->sucursal_seleccionada)
            ->orderBy('hora', 'asc')
            ->get();
        $this->cosmetologas = User::where('rol', 'Cosmetologia')->where('estado', 'Activo')->where('sucursal', $this->sucursal_seleccionada)->get();
        $this->camillas = Camillas::where('sucursal', $this->sucursal_seleccionada)->get();
        $this->gabinetes = Gabinetes::where('sucursal', $this->sucursal_seleccionada)->get();
        $this->asistidos = Asistidos::where('fecha', Carbon::now()->toDateString())->where('extra', $this->sucursal_seleccionada)->get();

        return view('livewire.recepcionista.gestion-citas');
    }

    public function guardargabinete()
    {
        $nuevo = new Gabinetes;
        $sucursal = Areas::find($this->sucursal_camilla);
        $nuevo->sucursal = $sucursal->area;
        $nuevo->idsucursal = $sucursal->id;
        $nuevo->nombre = $this->nombregabinete;
        $nuevo->estado = 'disponible';
        $nuevo->save();
        $this->emit('alert', '¡Gabinete creado satisfactoriamente!');
        $this->render();
    }
    public function guardarcamilla()
    {
        $nuevo = new Camillas();
        $gabinete = Gabinetes::find($this->idgabinete);
        $nuevo->sucursal = $gabinete->sucursal;
        $nuevo->idgabinete = $this->idgabinete;
        $nuevo->idsucursal = $gabinete->idsucursal;
        $nuevo->nombre = $this->nombrecamilla;
        $nuevo->estado = 'disponible';
        $nuevo->save();
        $this->emit('alert', '¡Camilla creada satisfactoriamente!');
        $this->render();
    }
    public function activarCamilla($idcamilla)
    {
        $nuevo = Camillas::find($idcamilla);
        $nuevo->estado = 'disponible';
        $nuevo->save();
        $this->emit('alert', '¡Camilla ' . $nuevo->nombre . ' disponile!');
        $this->render();
    }
    public function cambiarEstadoPaciente($pacienteId, $nuevoEstado)
    {
        $paciente = Operativos::find($pacienteId);

        if (!$paciente) {
            return; // Si el paciente no existe, salir de la función
        }

        $estadoActual = $paciente->estado;

        // Identificar los tipos de estado
        $esConfirmadoActual = strpos($estadoActual, 'confirmado') !== false;
        $esConfirmadoNuevo = strpos($nuevoEstado, 'confirmado') !== false;
        $esAtencionActual = strpos($estadoActual, 'atencion') !== false;
        $esAtencionNuevo = strpos($nuevoEstado, 'atencion') !== false;

        // Verificar si el cambio es válido según el flujo permitido
        $cambioValido = false;

        if ($estadoActual === 'Pendiente' && $esConfirmadoNuevo) {
            $cambioValido = true;
        } elseif ($esConfirmadoActual && ($esConfirmadoNuevo || $nuevoEstado === 'espera')) {
            $cambioValido = true;
        } elseif ($estadoActual === 'espera' && $esAtencionNuevo) {
            $cambioValido = true;
        } elseif ($esAtencionActual && ($esAtencionNuevo || $nuevoEstado === 'atendido')) {
            $cambioValido = true;
        }

        if (!$cambioValido) {
            return; // Si el cambio no es válido, salir de la función
        }

        // **Liberar la camilla anterior si estaba en "atenciónX" y cambia de camilla**
        if ($esAtencionActual && $esAtencionNuevo) {
            $numeroCamillaAnterior = filter_var($estadoActual, FILTER_SANITIZE_NUMBER_INT);
            $camillaAnterior = Camillas::find($numeroCamillaAnterior);

            if ($camillaAnterior) {
                $camillaAnterior->estado = 'disponible';
                $camillaAnterior->save();
            }
        }

        // **Asignar la nueva camilla si cambia a "atenciónX"**
        if ($esAtencionNuevo) {
            $numeroCamillaNueva = filter_var($nuevoEstado, FILTER_SANITIZE_NUMBER_INT);
            $camillaNueva = Camillas::find($numeroCamillaNueva);

            if ($camillaNueva) {
                $mistratamientos = HistorialCliente::where('idcliente', $paciente->idempresa)
                    ->where('estado', 'Inactivo')
                    ->get();

                $totalMinutos = 0;

                foreach ($mistratamientos as $historial) {
                    $tratamiento = Tratamiento::find($historial->idtratamiento);
                    $totalMinutos += $tratamiento->hora;
                }

                $camillaNueva->horainicio = Carbon::now()->format('H:i');
                $camillaNueva->horaestimada = $totalMinutos;
                $camillaNueva->estado = 'espera';
                $camillaNueva->save();
            }
        }

        // **Liberar la camilla si el paciente pasa a "atendido"**
        if ($nuevoEstado === 'atendido') {
            $numeroCamillaAnterior = filter_var($estadoActual, FILTER_SANITIZE_NUMBER_INT);
            $camillaAnterior = Camillas::find($numeroCamillaAnterior);

            if ($camillaAnterior) {
                $camillaAnterior->estado = 'disponible';
                $camillaAnterior->save();
            }
            $asistio = new  Asistidos();
            $asistio->idcliente = $paciente->idempresa;
            $asistio->idoperativo = $paciente->id;
            $asistio->estado = 'asistio';
            $asistio->fecha = date('Y-m-d');
            $asistio->extra = $paciente->area;
            $asistio->nombre = $paciente->empresa;
            $asistio->save();
        }

        // Actualizar el estado del paciente
        $paciente->estado = $nuevoEstado;
        $paciente->save();

        // Recargar la lista de agendados
        $this->agendados = Operativos::where('fecha', Carbon::now()->toDateString())->get();

        // Emitir un evento para actualizar la vista
        $this->emit('actualizarVista');
    }
}