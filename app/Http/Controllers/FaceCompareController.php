<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class FaceCompareController extends Controller
{
    public function verificar(Request $request)
    {
        $canvasBase64 = $request->input('canvas');
        if (!$canvasBase64) {
            return response()->json(['error' => 'No se recibió la firma'], 400);
        }

        // Rutas temporales
        $tmpDir = storage_path('app/tmp');
        if (!file_exists($tmpDir)) {
            mkdir($tmpDir, 0755, true);
        }

        $canvasPath = $tmpDir . '/canvas.png';
        file_put_contents($canvasPath, base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $canvasBase64)));

        // Firma guardada
        $firmaGuardadaPath = storage_path('app/firma_guardada.png'); // ajusta según donde guardes la firma

        if (!file_exists($firmaGuardadaPath)) {
            return response()->json(['error' => 'No hay firma guardada'], 400);
        }

        // Ejecutar Python y capturar output
        $pythonPath = base_path('scripts/compare_signatures.py'); // ruta al script Python
        $comando = "python3 " . escapeshellarg($pythonPath) . " " . escapeshellarg($canvasPath) . " " . escapeshellarg($firmaGuardadaPath);

        $output = shell_exec($comando);

        if ($output === null) {
            return response()->json(['error' => 'Error al ejecutar Python'], 500);
        }

        $similitud = floatval($output);
        $isSimilar = $similitud > 70; // Umbral ajustable

        return response()->json([
            'similarity' => $similitud,
            'is_similar' => $isSimilar
        ]);
    }
    public function verificar(Request $r)
    {
        $imgActual = $r->input('foto');

        if (!$imgActual) {
            return response()->json([
                'error' => 'No se recibió ninguna imagen.',
                'data' => $r->all()
            ]);
        }

        // Muestra solo los primeros caracteres para verificar
        return response()->json([
            'received' => true,
            'length' => strlen($imgActual),
            'preview' => substr($imgActual, 0, 100) . '...', // muestra inicio
        ]);
    }
}
