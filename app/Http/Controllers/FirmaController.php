<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Calls;
use App\Models\Operativos;
use App\Models\User;
use App\Models\Pagos;
use Carbon\Carbon;

class FirmaController extends Controller
{
    public function verificar(Request $request)
    {
        \Log::info('Canvas recibido: ' . substr($request->input('canvas'), 0, 100));
        $canvasBase64 = $request->input('canvas');
        if (!$canvasBase64) {
            \Log::info('No se recibió la firma');
            return response()->json(['error' => 'No se recibió la firma'], 400);
        }

        // Rutas temporales
        $tmpDir = storage_path('app/tmp');
        if (!file_exists($tmpDir)) {
            mkdir($tmpDir, 0755, true);
        }

        $canvasPath = $tmpDir . '/perfil.png';
        file_put_contents($canvasPath, base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $canvasBase64)));

        // Firma guardada
        $firmaGuardadaPath = storage_path('app/public/' . $request->input('firma_guardada')); // ajusta según donde guardes la firma

        if (!file_exists($firmaGuardadaPath)) {
            \Log::info('No hay firma');
            return response()->json(['error' => 'No hay firma guardada'], 400);
        }

        $pythonPath = base_path('scripts/compare_signatures.py');

        $cmd = "python " . escapeshellarg($pythonPath)
            . " " . escapeshellarg($canvasPath)
            . " " . escapeshellarg($firmaGuardadaPath);

        \Log::info($cmd);

        $output = shell_exec($cmd);
        $lines = preg_split("/\r\n|\n|\r/", trim($output));
        $lastLine = trim(end($lines)); // ← La última línea debería ser "84.13133"
        if ($output === null) {
            \Log::info('Error al ejecutar Python');
            return response()->json(['error' => 'Error al ejecutar Python'], 500);
        }
        \Log::info($output);
        $similitud = floatval($lastLine);
        $isSimilar = $similitud > 60;
        $usuario = User::find($request->input('userID'));
        \Log::info($usuario);
        $usuario->facial = "Activo";
        $usuario->horaverificada = now()->format('Y-m-d H:i:s');
        $usuario->save();
        return response()->json([
            'similarity' => $similitud,
            'is_similar' => $isSimilar
        ]);
    }


    public function downloadFile($filePath)
    {
        return response()->download('C:/xampp/htdocs/bbc-live/storage/app/public/pagos/M71xttCpNYaEjeAO7UTlHsPP1zNRhbV3R8HOCWdk.pdf');
    }
    public function guardarFirma(Request $request)
    {
        if ($request->has('imagen')) {
            $imagenBase64 = $request->input('imagen');
            $imagenDecodificada = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $imagenBase64));
            $nombreArchivo = 'firma_' . $request->input('miid') . '.jpg';
            $ruta = public_path('firmas') . '\\' . $nombreArchivo;
            file_put_contents($ruta, $imagenDecodificada);
            $user = User::find($request->input('miid'));
            $user->firma = $ruta;
            $user->save();
            return response()->json(['message' => 'Imagen guardada correctamente ' . $ruta]);
        } else {
            return response()->json(['message' => 'No se encontró ninguna imagen'], 400);
        }
    }
    public function crearfirma($id)
    {
        $usuario = User::find($id);
        $pagos = Pagos::where('iduser', $usuario->id);
        $miid = $id;
        return view('firma', compact('usuario', 'pagos', 'miid'));
    }
    public function crearfirmauser($id)
    {
        $usuario = User::find($id);
        $pagos = Pagos::where('iduser', $usuario->id);
        $miid = $id;
        return view('firma', compact('usuario', 'pagos', 'miid'));
    }
    public function contrato($id)
    {
        $usuario = User::find($id);
        $pagos = Pagos::where('iduser', $usuario->id);
        $imagenPath = 'C:\inetpub\wwwroot\bbc-live\public\firmas\imagen.png';
        // Obtener el tipo de archivo de la imagen
        $tipoArchivo = pathinfo($imagenPath, PATHINFO_EXTENSION);
        // Leer el contenido binario de la imagen
        $imagenBinario = file_get_contents($imagenPath);
        // Codificar la imagen en base64
        $imagenBase64 = 'data:image/' . $tipoArchivo . ';base64,' . base64_encode($imagenBinario);
        $strings = array();
        $varid = $usuario->id;
        $pagos = Pagos::where('iduser', $usuario->id)->get();
        $area = "";
        $primerafecha = "";
        $num = 0;
        foreach ($pagos as $pago) {
            Carbon::setLocale('es'); // Establecer el idioma en español

            $fecha = Carbon::createFromFormat('Y-m-d', $pago->fechainicio)->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY'); // Crear una instancia de Carbon con la fecha
            if ($num == 0) {
                $primerafecha = $fecha;
                $num = 1;
            }
            $fechaEscrita = $fecha;
            $nuevoString = $fechaEscrita; // Obtener el nuevo string para agregar al array
            array_push($strings, $nuevoString);
            $area = $pago->area;
        }
        return view('pdfview', compact('imagenBase64', 'varid', 'pagos', 'area', 'strings', 'usuario', 'primerafecha'));
    }
}
