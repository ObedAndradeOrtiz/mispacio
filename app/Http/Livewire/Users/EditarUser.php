<?php

namespace App\Http\Livewire\Users;

use App\Models\Calls;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Areas;
use App\Models\User;
use App\Models\Roles;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Hash;

class EditarUser extends Component
{
    use WithFileUploads;
    public $iduser;
    public $usuario;
    public $openArea = false;
    public $openArea2 = false;
    public $openArea3 = false;
    public $openArea4 = false;
    public $nombre;
    public $telefono;
    public $cantidad;
    public $areas;
    public $roles;
    public $openuser = false;
    public $contraseñaDeshasheada;
    public $image;
    public $newPassword;

    protected $rules = [
        'usuario.name' => 'required',
        'usuario.estado' => 'required',
        'usuario.sueldo' => 'required',
        'usuario.sucursal' => 'required',
        'usuario.telefono' => 'required',
        'usuario.rol' => 'required',
        'usuario.email' => 'required',
        'usuario.fechainicio' => 'required',
        'usuario.sueldo' => 'required',
        'usuario.horainicio' => 'required',
        'usuario.horafin' => 'required',
    ];
    protected $listeners = ['render' => 'render', 'inactivar' => 'inactivar', 'activar' => 'activar'];

    public function mount($iduser)
    {
        $user = User::find($iduser);
        $this->usuario = $user;
        $this->contraseñaDeshasheada = password_hash($this->usuario->password, PASSWORD_DEFAULT);
    }
    public function render()
    {
        $this->roles = Roles::where('estado', 'Activo')->get();
        $this->areas = Areas::where('estado', 'Activo')->get();
        return view('livewire.users.editar-user');
    }
    public function guardartodo()
    {
        $area = Areas::where('area', $this->usuario->sucursal)->first();
        $this->usuario->sucseleccionada = $area->id;
        $this->usuario->sesionsucursal = $area->id;
        if ($this->image) {
            $image = $this->image->store('public/usuarios');
            $image = 'usuarios/' . basename($image);
            $this->usuario->path = $image;
        }
        // Cambiar la contraseña si se envió un nuevo valor
        if (!empty($this->newPassword)) {
            $this->usuario->password = Hash::make($this->newPassword);
        }
        $this->usuario->save();
        $this->reset(['openArea']);
        $this->emitTo('users.lista-user', 'render');
        $this->emit('alert', '¡Cliente editado satisfactoriamente!');
    }

    public function inactivar($idCall)
    {
        $llamada = User::find($idCall);
        $llamada->estado = "Inactivo";
        $llamada->save();
        $this->reset(['openArea2']);
        $this->emitTo('users.lista-user', 'render');
    }
    public function activar($idCall)
    {
        $llamada = User::find($idCall);
        $llamada->estado = "Activo";
        $llamada->save();
        $this->reset(['openArea3']);
        $this->emitTo('users.lista-user', 'render');
    }
    public function cancelar()
    {
        $this->reset(['openArea2', 'openArea', 'openArea3', 'openArea4']);
    }
}
