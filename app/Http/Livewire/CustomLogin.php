<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class CustomLogin extends Component
{
    public $email;
    public $password;
    public $idphone;

    public function mount($idphone)
    {
        if (Auth::check()) {
            // El usuario ya ha iniciado sesión, redirigirlo a /dashboard
            return redirect()->to('/dashboard');
        }
    }
    public function render()
    {
        return view('livewire.custom-login');
    }

    public function login()
    {
        $credentials = [
            'email' => $this->email,
            'password' => $this->password,
        ];

        if (Auth::attempt($credentials)) {
            $user = User::where('email', $this->email)->first();
            $user->key = $this->idphone;
            $user->save();
            return redirect()->to('/dashboard');
        }


        session()->flash('message', 'Las credenciales proporcionadas no son válidas.');
    }
}
