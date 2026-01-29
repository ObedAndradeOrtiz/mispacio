<?php

namespace App\Http\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class PerfilUsuario extends Component
{
    use WithFileUploads;

    public $foto;

    public function render()
    {
        return view('livewire.perfil-usuario');
    }
    public function guardarFoto()
    {
        $this->validate([
            'foto' => 'image|max:2048', // máximo 2MB
        ]);

        $path = $this->foto->store('usuarios', 'public');

        $user = User::find(Auth::user()->id);
        $user->path = $path;
        $user->save();
        session()->flash('mensaje', 'Foto actualizada correctamente.');
    }
}