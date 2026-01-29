<div>

    <div style="display: flex;">
        <a class="mt-1 mr-1 btn btn-sm btn-icon btn-warning d-flex align-items-center" data-bs-toggle="tooltip"
            data-bs-placement="top" title="REAGENDAR" data-original-title="Editar"wire:click="$set('openArea',true)">
            <i class="fas fa-calendar-check"></i>
        </a>
        <a class="mt-1 mr-1 btn btn-sm btn-icon btn-success d-flex align-items-center" data-bs-toggle="tooltip"
            data-bs-placement="top" title="VER WHATSAPP" data-original-title="WahtsApp"
            wire:click="abrirChat('{{ $this->operativo->telefono }}')"><i class="fab fa-whatsapp"></i>
        </a>
        @if ($operativo->problema == 'si')
            <a class="mt-1 mr-1 btn btn-sm btn-icon btn-success d-flex align-items-center" data-bs-toggle="tooltip"
                data-bs-placement="top" title="LIBERAR" data-original-title="Liberar persona"
                wire:click="liberarProblema">
                <i class="fas fa-unlock"></i>
            </a>
        @else
            <a class="mt-1 mr-1 btn btn-sm btn-icon btn-danger d-flex align-items-center" data-bs-toggle="tooltip"
                data-bs-placement="top" title="PROBLEMÁTICO" data-original-title="Persona conflictiva"
                wire:click="$set('openProblema',true)">
                <i class="fas fa-user-slash"></i>
            </a>
        @endif


    </div>
    <x-modal wire:model="openProblema">
        <div class="p-4">
            <h2 class="mb-3 text-lg font-semibold text-red-600">
                Motivo del problema
            </h2>

            <!-- Campo de texto -->
            <div class="mb-4">
                <label for="motivo" class="block mb-1 text-sm font-medium text-gray-700">
                    Describa el motivo:
                </label>
                <textarea id="motivo" wire:model="motivoProblema" rows="4"
                    class="w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500"
                    placeholder="Ejemplo: Cliente agresivo, falta de pago, comportamiento conflictivo..."></textarea>

            </div>

            <!-- Botones -->
            <div class="flex justify-end space-x-2">
                <button type="button" wire:click="$set('openProblema', false)"
                    class="px-3 py-1.5 rounded-md bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm">
                    Cancelar
                </button>

                <button type="button" wire:click="guardarProblema"
                    class="px-3 py-1.5 rounded-md bg-red-600 hover:bg-red-700 text-white text-sm shadow">
                    Guardar
                </button>
            </div>
        </div>
    </x-modal>

    <x-modal wire:model.defer="openArea">

        <div class="user_div">
            <ul class="nav nav-tabs">
                <li class="nav-item"><a class="nav-link @if ($opcionoperativo == 0) active @endif"
                        data-toggle="tab" wire:click="setOpcion(0)">Detalles</a></li>
                <li class="nav-item"><a class="nav-link @if ($opcionoperativo == 1) active @endif"
                        data-toggle="tab" wire:click="setOpcion(1)">Reagendar</a>
                </li>
                <li class="nav-item"><a class="nav-link @if ($opcionoperativo == 2) active @endif"
                        data-toggle="tab" wire:click="setOpcion(2)">Vender</a>
                </li>
            </ul>
            <div class="mt-3 tab-content">
                @if ($opcionoperativo == 0)
                    @livewire('operativos.pagos-cliente', ['idoperativo' => $operativo->id], key($operativo->id * 23))
                @endif
                @if ($opcionoperativo == 1)
                    <div class="px-6 py-4">
                        <div class="text-lg font-medium text-gray-900">
                            REAGENDAR SUCURSAL: <select wire:model="operativo.area">
                                @foreach ($areas as $lista)
                                    <option value="{{ $lista->area }}">{{ $lista->area }}</option>
                                @endforeach
                            </select> CITA DE: {{ $operativo->empresa }}
                        </div>
                        <div class="mt-4 text-sm text-gray-600">
                            <form>
                                <div class="">
                                    <label class="form-label" for="exampleInputdate">Nombre de cliente:</label>
                                    <input type="text" class="form-control" id="exampleInputdate"
                                        wire:model.defer="operativo.empresa">
                                </div>
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label class="form-label" for="exampleInputdate">Fecha de cita:</label>
                                        <input type="date" class="form-control" id="exampleInputdate"
                                            value="2000-01-01" wire:model.defer="operativo.fecha">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="form-label">Hora de cita:</label>
                                        <div class="d-flex">
                                            <select name="type" class="mr-1 selectpicker form-control"
                                                data-style="py-0" wire:model.defer="hora">
                                                <option>Seleccionar hora</option>
                                                <option>00</option>
                                                <option>1</option>
                                                <option>2</option>
                                                <option>3</option>
                                                <option>4</option>
                                                <option>5</option>
                                                <option>6</option>
                                                <option>7</option>
                                                <option>8</option>
                                                <option>9</option>
                                                <option>10</option>
                                                <option>11</option>
                                                <option>12</option>
                                                <option>13</option>
                                                <option>14</option>
                                                <option>15</option>
                                                <option>16</option>
                                                <option>17</option>
                                                <option>18</option>
                                                <option>19</option>
                                                <option>20</option>
                                                <option>21</option>
                                                <option>22</option>
                                                <option>23</option>

                                            </select>
                                            <select name="type" class="selectpicker form-control" data-style="py-0"
                                                wire:model.defer="minuto">
                                                <option>Seleccionar minuto</option>
                                                <option>00</option>
                                                <option>15</option>
                                                <option>30</option>
                                                <option>45</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="">Agrear comentario(opcional):</label>
                                    <input type="text" class="form-control" id="exampleInputDisabled1"
                                        wire:model.defer="operativo.comentario">
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="exampleInputDisabled1">Regitrado por:</label>
                                    <input type="text" class="form-control" id="exampleInputDisabled1"
                                        disabled="" value="{{ Auth::user()->name }}">
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="flex flex-row justify-end px-6 py-4 text-right bg-gray-100">
                        <label class="btn btn-success" wire:click="guardaroperativo">Reagendar</label>
                    </div>
                @endif
                @if ($opcionoperativo == 2)
                    @livewire('inventario.vender-cliente', ['idcliente' => $operativo->idempresa], key($operativo->id * 21))
                @endif
            </div>
        </div>
    </x-modal>
    <?php
    function formatText($text)
    {
        $formatted = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
        // Reemplazo de patrones para negrita, cursiva y tachado
        $formatted = preg_replace('/\*([^\*]+)\*/', '<b>$1</b>', $formatted); // *negrita*
        $formatted = preg_replace('/_([^_]+)_/', '<i>$1</i>', $formatted); // _cursiva_
        $formatted = preg_replace('/~([^~]+)~/', '<s>$1</s>', $formatted); // ~tachado~
        return nl2br($formatted); // Conserva saltos de línea
    }
    function isBase64Image($string)
    {
        // Validar longitud del string
        if (strlen($string) % 4 !== 0) {
            return false;
        }

        // Validar caracteres base64
        if (preg_match('/^[a-zA-Z0-9\/\r\n+]*={0,2}$/', $string)) {
            // Verificar si el string representa una imagen (opcional)
            $image = @imagecreatefromstring(base64_decode($string));
            if ($image !== false) {
                return true;
            }
        }
        return false;
    }
    ?>
    <style>
        .sidebar-config {
            width: 5%;
            background-color: #2b3a4a;
            color: white;
            display: flex;
            flex-direction: column;

        }


        .sidebar {
            width: 20%;
            background-color: #2b3a4a;
            color: white;
            display: flex;
            flex-direction: column;
        }

        .sidebar-header {
            padding: 15px 20px;
            background-color: #2a3942;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #394c53;
        }

        .sidebar-header h2 {
            font-size: 1.5rem;
            font-weight: bold;
            color: #d1d1d1;
        }

        .search-bar {
            padding: 10px 20px;
            background-color: #2a3942;
            border-bottom: 1px solid #394c53;
        }

        .search-bar input {
            width: 100%;
            padding: 10px 15px;
            border-radius: 20px;
            border: none;
            background-color: #394c53;
            color: white;
            font-size: 0.9rem;
            outline: none;
        }

        .chat-list {
            flex-grow: 1;
            overflow-y: auto;
            padding: 10px 0;
            scrollbar-width: thin;
            scrollbar-color: #394c53 transparent;
        }

        .chat-item {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }

        .chat-item:hover {
            background-color: #394c53;
        }

        .chat-item img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 15px;
            border: 2px solid #25d366;
        }

        .chat-details {
            flex-grow: 1;
            border-bottom: 1px solid #394c53;
            padding-bottom: 10px;
        }

        .chat-details h3 {
            font-size: 1rem;
            font-weight: bold;
            color: white;
        }

        .chat-details p {
            font-size: 0.85rem;
            color: #a8a8a8;
            margin-top: 5px;
        }


        .chat-area {
            flex-grow: 1;
            background-color: #efeae2;
            display: flex;
            flex-direction: column;
            position: relative;
        }

        .chat-header {
            background-color: #2a3942;
            padding: 15px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid #ddd;
        }

        .chat-header h3 {
            font-size: 1.2rem;
            font-weight: bold;
            color: #ffffff;
        }

        .chat-messages {
            flex-grow: 1;
            padding: 20px;
            max-height: 500px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            overflow: scroll;
        }

        .message {
            max-width: 500px;
            padding: 10px 15px;
            border-radius: 10px;
            font-size: 0.9rem;
            line-height: 1.4;
            display: inline-block;
            position: relative;
        }

        .message.received {
            background-color: white;
            align-self: flex-start;
            color: #333;
        }

        .message.sent {
            background-color: #d9fdd3;
            align-self: flex-end;
            color: #333;
        }

        .chat-input {
            display: flex;
            padding: 10px;
            background: #f1f1f1;
        }

        textarea {
            flex: 1;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            margin-left: 10px;
            padding: 5px 10px;
            background: #25d366;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .tabs {
            display: flex;
            border-bottom: 2px solid #ddd;
            margin-bottom: 10px;
        }

        .tab-item {
            flex: 1;
            text-align: center;
            padding: 10px 15px;
            cursor: pointer;
            font-weight: bold;
            border-bottom: 2px solid transparent;
            transition: all 0.3s;
        }

        .tab-item.active {
            border-bottom: 2px solid #28a745;
            color: #28a745;
        }

        .tab-item:hover {
            background-color: #f9f9f9;
        }
    </style>
    <style>
        textarea {
            padding: 10px;
            font-size: 14px;
        }

        .suggestions {
            max-height: 100px;
            overflow-y: auto;
            background: #fff;
            border: 1px solid #ccc;
            position: absolute;
            width: 100%;
            z-index: 10;
            margin-top: -6px;
            /* Ajusta esto según sea necesario */
        }

        .suggestions div {
            padding: 10px;
            cursor: pointer;
        }

        .suggestions div:hover {
            background: #f0f0f0;
        }
    </style>
    <x-modal wire:model.defer="openWss">
        <div class="chat-messages" style="max-height: 50hv;" id="chatMessages">
            @if (isset($jsonchat['data']) && count($jsonchat['data']) > 0)
                @foreach ($jsonchat['data'] as $index => $mensaje)
                    @if (isset($mensaje['body']))
                        <div class="message {{ $mensaje['fromMe'] ? 'sent' : 'received' }}"
                            id="{{ $loop->last ? 'last-message' : '' }}">
                            <div class="bubble">
                                @if (isBase64Image($mensaje['body']))
                                    {{-- Mostrar la imagen --}}
                                    <img src="data:image/jpeg;base64,{{ $mensaje['body'] }}" alt="Imagen"
                                        width="500" height="500">
                                @else
                                    {{-- Mostrar el texto formateado --}}
                                    {!! formatText($mensaje['body']) !!}
                                @endif
                            </div>
                            <div class="message-info">
                                <small>{{ date('d/m/Y H:i:s', $mensaje['timestamp']) }}</small>
                            </div>
                        </div>
                    @endif
                @endforeach
            @else
                <p>No hay datos disponibles.</p>
            @endif

        </div>
        @if (isset($jsonchat['data']) && count($jsonchat['data']) > 0)
            <div class="chat-input">
                <textarea wire:model="mensajewss" placeholder="Escribe tu mensaje..." rows="2"></textarea>
                <button wire:click="enviarMss({{ $this->operativo->telefono }})">Enviar</button>
            </div>
        @endif

    </x-modal>
    <script>
        function scrollToBottom() {
            var chatMessages = document.getElementById("chatMessages");
            if (chatMessages) {
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }
        }
    </script>

</div>
