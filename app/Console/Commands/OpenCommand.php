<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\activacion;

class OpenCommand extends Command
{
    protected $signature = 'users:update-status';
    protected $description = 'Update user status based on time';
    public $activado;
    public function handle()
    {
        $hour = date('H'); // Obtener la hora actual

        if ($hour == 7) {
            // Actualizar el estado de los usuarios a 'Activo'
            User::where('rol', '!=', 'Cliente')
                ->where('rol', '!=', 'Administrador')
                ->update(['estado' => 'Activo']);
            $this->activado = activacion::find(1);
            $this->activado->estado = 1;
            $this->activado->save();
        } elseif ($hour == 19) {
            // Actualizar el estado de los usuarios a 'Inactivo'
            User::where('rol', '!=', 'Cliente')
                ->where('rol', '!=', 'Administrador')
                ->update(['estado' => 'Inactivo']);
            $this->activado = activacion::find(1);
            $this->activado->estado = 0;
            $this->activado->save();
        }

        $this->info('User status updated successfully.');
    }
}