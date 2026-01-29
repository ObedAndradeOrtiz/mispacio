<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SignatureController extends Controller
{
    public function guardarFirma(Request $request)
    {
        $request->validate([
            'firma' => 'required',
        ]);

        $user = Auth::user();

        // Convertir Base64 a imagen
        $data = $request->firma;
        $data = str_replace('data:image/png;base64,', '', $data);
        $data = str_replace(' ', '+', $data);
        $imageName = 'firma_' . $user->id . '.png';

        Storage::disk('public')->put('firmas/' . $imageName, base64_decode($data));

        // Guardar la ruta en DB
        $user->firma = 'firmas/' . $imageName;
        $user->save();

        return redirect()->back()->with('success', 'Firma guardada correctamente.');
    }
}
