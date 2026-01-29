<?php

namespace App\Console\Commands;

use App\Models\Operativos;
use App\Models\TelefonoWss;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Areas;
use App\Models\Calls;
use App\Models\Productos;
use App\Models\User;
use App\Models\registropago;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class TareaCada15Minutos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:tarea-cada15-minutos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $horaActual = Carbon::now()->format('H:i');
        $horaInicio = '09:00';
        $horaFin = '23:30';

        // Calcular la hora objetivo (hora actual + 1 hora)
        $horaObjetivo = Carbon::now()->addHour()->format('H:i');
        $minutosPermitidos = ['00', '15', '30', '45'];

       
    }
}