<?php

namespace App\Http\Livewire\IA;

use Livewire\Component;
use Illuminate\Support\Facades\Http;

class Conversation extends Component
{
    public $mensaje = '';
    public $historial = [];
    public $cargando = false;
    private string $contextoSPA = <<<TEXT
    Eres una inteligencia artificial llamada MioraBot. Formas parte del equipo de apoyo interno del centro de estética y medicina "Spa Medic Miora", ubicado en Bolivia. Tu función es ayudar exclusivamente al personal del Spa a brindar una mejor atención al cliente.
    
    Debes responder preguntas sobre tratamientos, protocolos de atención, productos recomendados para cada tipo de tratamiento, combinaciones posibles entre servicios, sugerencias de venta cruzada, cuidados posteriores, horarios, personal capacitado, beneficios y contraindicaciones. 
    
    Si te preguntan, por ejemplo, qué productos deben usarse en una limpieza facial, debes responder con una lista clara y específica. También puedes sugerir tratamientos complementarios según el caso.
    
    Responde siempre en español, de forma clara, técnica pero amable, y evita dar información que no esté relacionada con el Spa.
    
    Si la pregunta no corresponde a temas del Spa o su funcionamiento interno, responde educadamente que solo puedes asistir con información relevante a los servicios del "Spa Medic Miora".
    TEXT;


    public function enviar()
    {
        if (trim($this->mensaje) === '') return;
        $pregunta = $this->mensaje;
        $this->historial[] = ['usuario' => 'tú', 'texto' => $pregunta];
        $this->mensaje = '';
        $this->cargando = true;
        $this->render();
        $this->dispatchBrowserEvent('scroll-chat');
        $respuesta = $this->consultarIA($pregunta);

        $this->historial[] = ['usuario' => 'IA Miora', 'texto' => $respuesta];
        $this->cargando = false;
        $this->render();
        $this->dispatchBrowserEvent('scroll-chat');
    }

    public function consultarIA($pregunta)
    {
        $prompt = $this->contextoSPA . "\n\nPregunta del usuario: " . $pregunta;
        //Spa.miora2025*#
        try {
            $response = Http::timeout(60)->post('http://localhost:11434/api/generate', [
                'model' => 'gemma:2b',
                'prompt' => $prompt,
                'stream' => false,
            ]);


            if ($response->successful()) {

                return $response->json()['response'] ?? 'Sin respuesta.';
            }
            return 'Error al contactar con la IA.';
        } catch (\Exception $e) {

            return 'Error: ' . $e->getMessage();
        }
    }
    public function render()
    {
        return view('livewire.i-a.conversation');
    }
}