<div>

    <div class="section-body">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center">
                <div class="header-action">
                    <h1 class="page-title">Inicio</h1>
                    <ol class="breadcrumb page-breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Miora</a></li>
                        <li class="breadcrumb-item active" aria-current="page">CRM</li>
                    </ol>
                </div>
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link @if ($opcion == 0) active @endif" data-toggle="tab"
                            href="#admin-Dashboard" wire:click="setOpcion(0)">CRM WhatsApp</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if ($opcion == 1) active @endif" data-toggle="tab"
                            href="#admin-Activity" wire:click="setOpcion(1)">Números</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if ($opcion == 2) active @endif" data-toggle="tab"
                            href="#publicidades" wire:click="setOpcion(2)">Publicidades</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if ($opcion == 3) active @endif" data-toggle="tab"
                            href="#campañas" wire:click="setOpcion(3)">Campañas</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="mt-4 section-body">
        <div class="container-fluid">
            @if ($opcion == 0)
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
                        overflow-y: auto;
                        display: flex;
                        flex-direction: column;
                        gap: 10px;
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
                        background-color: #2a3942;
                        padding: 15px 20px;
                        display: flex;
                        align-items: center;
                        border-top: 1px solid #ddd;
                    }

                    .chat-input textarea {
                        flex-grow: 1;
                        padding: 10px 15px;
                        border: 1px solid #ccc;
                        border-radius: 20px;
                        resize: none;
                        font-size: 1rem;
                        outline: none;
                        margin-right: 10px;
                    }


                    .chat-input button {
                        padding: 10px 20px;
                        background-color: #28a745;
                        color: #fff;
                        border: none;
                        border-radius: 5px;
                        cursor: pointer;
                    }

                    .chat-input button:hover {
                        background-color: #309748;
                    }

                    .chat-input button i {
                        font-size: 1.5rem;
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
                <div style="display: flex; height:85vh;  ">
                    <div class="sidebar-config">
                        <div class="sidebar-header">
                            <h2>#</h2>
                        </div>

                        <div
                            style="margin:1%;   flex-grow: 1;
                        overflow-y: auto;
                        padding: 10px 0;
                        scrollbar-width: thin;
                        scrollbar-color: #394c53 transparent;">
                            @if (Auth::user()->rol != 'Recepcion')
                                <div style="
                                width: 55px;
                                height: 55px;
                                display: flex;
                                margin:5%;
                                align-items: center;
                                justify-content: center;
                                border-radius: 50%;
                                background-color: #25d366;
                                text-align: center;
                                font-size: 7px;
                                font-weight: bold;
                                overflow: hidden;
                                color:white;
                                word-wrap: break-word;"
                                    wire:click="$set('telefonoseleccionado','')">
                                    Todos
                                </div>
                            @endif

                            @foreach ($telefonos as $item)
                                @if ($item->conectado == 'CC')
                                    <div style="
                                width: 55px;
                                height: 55px;
                                display: flex;
                                margin:5%;
                                align-items: center;
                                justify-content: center;
                                border-radius: 50%;
                                background-color: #25d366;
                                text-align: center;
                                font-size: 7px;
                                font-weight: bold;
                                overflow: hidden;
                                color:white;
                                word-wrap: break-word;"
                                        wire:click="$set('telefonoseleccionado',{{ $item->id }})">
                                        {{ \Illuminate\Support\Str::limit($item->nombre, 8, '') }}
                                    </div>
                                @endif
                            @endforeach
                        </div>

                    </div>
                    <div class="sidebar">
                        <div class="sidebar-header">
                            <h2>WhatsApp</h2>
                        </div>
                        <div class="tabs">
                            <div class="tab-item active">
                                POR AGENDAR
                            </div>
                            <div class="tab-item">
                                AGENDADOS
                            </div>

                        </div>
                        <div class="search-bar">
                            <input type="number" placeholder="Buscar número..." wire:model="busqueda">
                        </div>
                        <div class="chat-list">
                            @foreach ($mensajes as $mensaje)
                                @php
                                    $existell = DB::table('calls')->where('telefono', $mensaje->emisor)->exists();
                                    $existeag = DB::table('users')->where('telefono', $mensaje->emisor)->exists();
                                @endphp
                                @if (Str::startsWith($mensaje->emisor, '120'))
                                @else
                                    @php
                                        $receptor = DB::table('telefono_wsses')
                                            ->where('id', $mensaje->receptor)
                                            ->first();
                                    @endphp
                                    <div class="chat-item"
                                        wire:click="abrirChat('{{ $mensaje->receptor }}','{{ $mensaje->emisor }}','{{ $mensaje->id }}')">
                                        <div class="chat-details">

                                            <h3>{{ $mensaje->emisor }}
                                                <div class="text-muted">
                                                    {{ $receptor->nombre }}
                                                </div>
                                            </h3>
                                            <p>{{ \Illuminate\Support\Str::limit($mensaje->mensaje, 25) }}</p>
                                            <div class="text-muted">
                                                {{ $mensaje->fecha . ' - ' . $mensaje->hora }}
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    <div class="chat-area verchat" id="mapa">
                        @if (isset($chatabierto))
                            <div class="chat-header">
                                @php
                                    $existell = DB::table('calls')->where('telefono', $chatabierto->emisor)->exists();
                                    $existeag = DB::table('users')->where('telefono', $chatabierto->emisor)->exists();
                                    $receptor = DB::table('telefono_wsses')
                                        ->where('id', $chatabierto->receptor)
                                        ->first();
                                    $idllamada = DB::table('calls')->where('telefono', $chatabierto->emisor)->first();
                                @endphp
                                <h3>{{ $chatabierto->emisor . ' ' . $receptor->sucursal }}</h3>


                                @if ($existell)
                                    {{-- <div style="display: flex;">
                                        <label for="" class="btn btn-danger"> Ya
                                            registrado</label>
                                    </div> --}}
                                    @livewire('calls-center.editar-call', ['idllamada' => $idllamada->id])
                                @else
                                    @livewire('calls-center.crear-call')
                                    @livewire('crm.crear-cliente', ['sucursal' => $receptor->sucursal, 'telefonoTratamiento' => $chatabierto->emisor . '-' . $chatabierto->categoria], key($chatabierto->id * 105))
                                @endif
                            </div>

                        @endif


                        <div class="chat-messages" id="chatMessages">
                            @if (isset($jsonchat['data']) && count($jsonchat['data']) > 0)
                                @foreach ($jsonchat['data'] as $index => $mensaje)
                                    @if (isset($mensaje['body']))
                                        <div class="message {{ $mensaje['fromMe'] ? 'sent' : 'received' }}"
                                            id="{{ $loop->last ? 'last-message' : '' }}">
                                            <div class="bubble">
                                                @if (isBase64Image($mensaje['body']))
                                                    {{-- Mostrar la imagen --}}
                                                    <img src="data:image/jpeg;base64,{{ $mensaje['body'] }}"
                                                        alt="Imagen" width="500" height="500">
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

                        <!-- Mostrar las sugerencias encima del textarea -->
                        @if (count($suggestions) > 0)
                            <div class="w-full bg-white border shadow-lg">
                                @foreach ($suggestions as $suggestion => $message)
                                    <div wire:click="selectSuggestion('{{ $suggestion }}')"
                                        class="p-2 cursor-pointer hover:bg-gray-200">
                                        {{ strlen($message) > 100 ? substr($message, 0, 100) . '...' : $message }}
                                    </div>
                                @endforeach
                            </div>
                        @endif
                        @if (isset($jsonchat['data']) && count($jsonchat['data']) > 0)
                            <div class="chat-input">
                                <textarea placeholder="Mensaje..." rows="1" wire:model="mensajewss" class="border"></textarea>
                                <button
                                    wire:click="enviarMss({{ $chatabierto->emisor }},{{ $chatabierto->receptor }})">Enviar</button>
                            </div>
                        @endif
                        <script>
                            document.addEventListener('livewire:update', function() {
                                const lastMessage = document.getElementById('last-message');
                                if (lastMessage) {
                                    lastMessage.scrollIntoView({
                                        behavior: 'smooth',
                                        block: 'end'
                                    });
                                }
                            });
                        </script>
                    </div>
                    <div class="sidebar">
                        <div class="sidebar-header">
                            <h2>Predeterminados</h2>
                            <label for="" class="btn btn-success"
                                wire:click="$set('crearmensaje',true)">Crear</label>
                        </div>
                        <div class="search-bar">
                            <input type="text" placeholder="Buscar mensaje..." wire:model="mensajeescrito">
                        </div>

                        <div class="chat-list">
                            @foreach ($mensajescargados as $mensaje)
                                <div class="chat-item" wire:click="colocarMensaje('{{ $mensaje->id }}')">
                                    <div class="chat-details">
                                        <p> {{ \Illuminate\Support\Str::limit($mensaje->mensaje, 125) }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
            @if ($opcion == 1)
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">CRM Miora</h3>
                        <div class="card-options">
                            <label for="" class="btn btn-success" wire:click="$set('crearnumero',true)">Agregar
                                Nro.</label>
                            <label for="" class="btn btn-info" wire:click="$set('probartodo',true)">Probar
                                todos</label>
                        </div>
                    </div>
                    <div class="card-body">
                        @foreach ($areas as $area)
                            <div class="mb-3 card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h3 class="m-0 card-title">{{ $area->area }}</h3>
                                    <div class="card-options">
                                        <a href="#" class="card-options-collapse" data-toggle="card-collapse">
                                            <i class="fe fe-chevron-up"></i>
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    @foreach ($telefonos as $telefono)
                                        @if ($telefono->idsucursal == $area->id)
                                            <div
                                                class="py-2 d-flex justify-content-between align-items-center border-bottom">
                                                <span>{{ $telefono->telefono }}</span>
                                                <span>{{ $telefono->nombre }}</span>
                                                <div class="ms-3">
                                                    @if ($telefono->conectado == 'SC')
                                                        <label class="btn btn-success btn-sm"
                                                            wire:click="startSession('{{ $telefono->id }}')">Conectar
                                                            a
                                                            WhatsApp</label>
                                                    @else
                                                        <label class="btn btn-info btn-sm"
                                                            wire:click="desconectarnumero('{{ $telefono->id }}')">Desconectar</label>
                                                        <label for="" class="btn btn-primary"
                                                            wire:click="asignartelefono({{ $telefono->id }})">Probar</label>
                                                    @endif
                                                    @if ($telefono->modo != 'P')
                                                        <label for="" class="btn btn-warning btn-sm"
                                                            wire:click="elegirprincipal('{{ $telefono->id }}')">Principal
                                                            Recordatorios</label>
                                                    @endif


                                                    <label class="btn btn-danger btn-sm"
                                                        wire:click='eliminarnum({{ $telefono->id }})'>Eliminar</label>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if ($opcion == 2)
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Publicidades WhatsApp Programadas</h3>
                        <div class="card-options">
                            {{-- <label for="" class="btn btn-success" wire:click="$set('crearnumero',true)">Nueva
                                Campaña</label> --}}
                        </div>
                    </div>
                    <div class="card-body">
                        <div>
                            <div class="flex-wrap mt-2 ml-4 mr-4" style="display: flex;">
                            </div>
                            <div class="flex flex-row justify-between">
                                <h3 class="ml-4" style="font-size: 18px;"><strong>LISTA DE
                                        PUBLICIDADES</strong>
                                </h3>
                                @livewire('marketing.crear-publicidad')
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped" role="grid" data-bs-toggle="data-table">
                                    <thead>
                                        <tr>
                                            <th>CAMPAÑA</th>
                                            <th>WHATSAPP</th>
                                            <th>HORA</th>
                                            <th>FECHA</th>
                                            <th>COMENTARIO</th>
                                            <th>ACCIÓN</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($tot as $item)
                                            <tr data-id="{{ $item->id }}"
                                                data-nombrecampana="{{ $item->nombrecampana }}"
                                                data-sucursal="{{ $item->sucursal }}"
                                                data-nombrecuenta="{{ $item->nombrecuenta }}"
                                                data-fechainicio="{{ $item->fechainicio }}"
                                                data-fechafin="{{ $item->fechafin }}"
                                                data-estado="{{ $item->estado }}" data-motivo="{{ $item->motivo }}">
                                                {{-- <td>
                                                    @if ($item->estado == 'Activo')
                                                        <input type="checkbox"
                                                            wire:click="guardartodo({{ $item->id }})" checked>
                                                    @else
                                                        <input type="checkbox"
                                                            wire:click="guardartodo({{ $item->id }})">
                                                    @endif
                                                </td> --}}
                                                <td class="clickable">{{ $item->nombrecampana }}</td>

                                                <td class="clickable">{{ $item->nombrecuenta }}</td>
                                                <td class="clickable">{{ $item->hora }}</td>
                                                <td class="clickable">{{ $item->fechainicio }}</td>
                                                <td class="clickable">{{ $item->motivo }}</td>
                                                <td>
                                                    <div class="d-flex">

                                                        @livewire('marketing.editar-publicidad', ['idpublicidad' => $item->id], key($item->id))
                                                    </div>

                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="px-6 py-3">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @if ($opcion == 3)
                @livewire('marketing.mark-campanas')
            @endif
        </div>
    </div>
    <x-sm-modal wire:model="probartodo">
        <div class="px-6 py-4">
            <div class="py-2 text-lg font-medium text-gray-900">
                Probar mensajes
            </div>
            <div class="mt-2 text-sm text-gray-600">
                <form>
                    <div class="form-group">
                        <label class="form-label" for="">Telefono:</label>
                        <input type="number" class="form-control" id="exampleInputDisabled1"
                            wire:model.defer="telefonoprueba" placeholder="">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="">Mensaje:</label>
                        <textarea name="" id="" cols="55" rows="10" wire:model="mensajeprueba"></textarea>
                    </div>

                </form>
            </div>
        </div>
        <div class="flex flex-row justify-end px-6 py-4 text-right bg-gray-100">
            <label type="submit" class="btn btn-success" style="background-color: green;"
                wire:click="pruebamensajetodo" wire:loading.remove wire:target="pruebamensajetodo">Enviar
                Mensaje</label>
            <span class="" wire:loading wire:target="pruebamensajetodo">Guardando...</span>
        </div>
    </x-sm-modal>
    <x-sm-modal wire:model="probar">
        <div class="px-6 py-4">
            <div class="py-2 text-lg font-medium text-gray-900">
                Probar mensaje
            </div>
            <div class="mt-2 text-sm text-gray-600">
                <form>
                    <div class="form-group">
                        <label class="form-label" for="">Telefono:</label>
                        <input type="number" class="form-control" id="exampleInputDisabled1"
                            wire:model.defer="telefonoprueba" placeholder="">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="">Mensaje:</label>
                        <textarea name="" id="" cols="55" rows="10" wire:model="mensajeprueba"></textarea>
                    </div>
                    <input class="form-control" type="file" wire:model="image">
                    @if ($image)
                        @if ($image->getClientOriginalExtension() === 'jpg' || $image->getClientOriginalExtension() === 'png')
                            <img class="mt-4" src="{{ $image->temporaryUrl() }}" alt="">
                        @endif
                    @endif
                </form>
            </div>
        </div>
        <div class="flex flex-row justify-end px-6 py-4 text-right bg-gray-100">
            <label type="submit" class="btn btn-success" style="background-color: green;"
                wire:click="pruebamensaje" wire:loading.remove wire:target="pruebamensaje">Enviar Mensaje</label>
            <span class="" wire:loading wire:target="pruebamensaje">Guardando...</span>
        </div>
    </x-sm-modal>
    <x-sm-modal wire:model.defer="crearnumero">
        <div class="px-6 py-4">
            <div class="py-2 text-lg font-medium text-gray-900">
                Registrar nuevo número
            </div>
            <div class="mt-2 text-sm text-gray-600">
                <form>
                    <div class="form-group">
                        <label class="form-label" for="">Seleccione Sucursal: </label>
                        <select name="type" class="selectpicker form-control" data-style="py-0"
                            wire:model.defer="sucursal">
                            <option>Seleccionar sucursal</option>
                            @foreach ($areas as $area)
                                <option value="{{ $area->id }}">{{ $area->area }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="">Teléfono de WhatsApp:</label>
                        <input type="number" class="form-control" id="exampleInputDisabled1"
                            wire:model.defer="telefono">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="">Nombre de Teléfono:</label>
                        <input type="text" class="form-control" id="exampleInputDisabled1"
                            wire:model.defer="nombretel">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="exampleInputDisabled1">Regitrado por:</label>
                        <input type="text" class="form-control" id="exampleInputDisabled1" disabled=""
                            value="{{ Auth::user()->name }}">
                    </div>
                </form>
            </div>
        </div>
        <div class="flex flex-row justify-end px-6 py-4 text-right bg-gray-100">
            <label type="submit" class="btn btn-success" style="background-color: green;" wire:click="guardartodo"
                wire:loading.remove wire:target="guardartodo">Registrar</label>
            <span class="" wire:loading wire:target="guardartodo">Guardando...</span>

        </div>
    </x-sm-modal>
    <x-sm-modal wire:model="crearmensaje">
        <div class="px-6 py-4">
            <div class="py-2 text-lg font-medium text-gray-900">
                Registrar nuevo mensaje predeterminado
            </div>
            <div class="mt-2 text-sm text-gray-600">
                <form>
                    <div class="form-group">
                        <label class="form-label" for="">Seleccione Sucursal: </label>
                        <select name="type" class="selectpicker form-control" data-style="py-0"
                            wire:model.defer="sucursal">
                            <option>Seleccionar sucursal</option>
                            @foreach ($areas as $area)
                                <option value="{{ $area->id }}">{{ $area->area }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="">Palabras clave:</label>
                        <input type="text" class="form-control" id="exampleInputDisabled1"
                            wire:model.defer="palabras" placeholder="Separa con , por favor">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="">Escribe el mensaje predeterminado:</label>
                        <textarea name="" id="" cols="55" rows="10" wire:model="mensajepredeterminado"></textarea>
                    </div>
                </form>
            </div>
        </div>
        <div class="flex flex-row justify-end px-6 py-4 text-right bg-gray-100">
            <label type="submit" class="btn btn-success" style="background-color: green;"
                wire:click="guardarmensaje" wire:loading.remove wire:target="guardarmensaje">Guardar Mensaje</label>
            <span class="" wire:loading wire:target="guardarmensaje">Guardando...</span>

        </div>
    </x-sm-modal>
    <x-sm-modal wire:model="abrirqr">
        @if ($cargando)
            <div class="d-flex justify-content-center align-items-center" style="height: 300px;">
                @if (session()->has('error'))
                    <div style="color: red;">{{ session('error') }}</div>
                @endif
                <div class="spinner-border text-primary" role="status">

                </div>
                <p class="text-center text-muted">
                    Esperando código QR... esto puede tardar unos segundos.
                </p>
            </div>
        @else
            <div class="text-center">
                @if (session()->has('error'))
                    <div style="color: red;">{{ session('error') }}</div>
                @endif
                @if (session()->has('success'))
                    <div style="color:green;">{{ session('success') }}</div>
                @endif
                @if (session()->has('warning'))
                    <div style="color:orange;">{{ session('warning') }}</div>
                @endif
                <div style="color:orange;">Esperando vinculación...</div>
                @if ($qrCode != null)
                    <img src="{{ $qrCode }}" alt="Escanea este código QR para iniciar sesión"
                        style="width:300px; height:300px;">
                @else
                    <div class="spinner-border text-primary" role="status">

                    </div>
                    <p class="text-center text-muted">
                        Esperando código QR... esto puede tardar unos segundos.
                    </p>
                @endif

            </div>
        @endif
    </x-sm-modal>
    <x-sm-modal wire:model.defer="verchat">
        @if ($cargarjson)
            <div class="d-flex justify-content-center align-items-center" style="height: 300px;">
                @if (session()->has('error'))
                    <div style="color: red;">{{ session('error') }}</div>
                @endif
                <div class="spinner-border text-primary" role="status">

                </div>
                <p class="text-center text-muted">
                    Esperando aprovación... esto puede tardar unos segundos.
                </p>
            </div>
        @else
            <div class="text-center">
                @if (session()->has('error'))
                    <div style="color: red;">{{ session('error') }}</div>
                @endif
                @if (session()->has('success'))
                    <div style="color:green;">{{ session('success') }}</div>
                @endif
                @if (session()->has('warning'))
                    <div style="color:orange;">{{ session('warning') }}</div>
                @endif
                <div style="width: 100%; height: 50vh; position: relative;">
                    <iframe src="https://spamiora.ddns.net/mensajes/{{ Auth::user()->id }}" frameborder="0"
                        style="width: 100%; height: 100%;"></iframe>
                </div>

            </div>
        @endif
    </x-sm-modal>
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    <script>
        Pusher.logToConsole = true;

        var pusher = new Pusher('6d4f547e6d802887f1dc', {
            cluster: 'sa1'
        });

        var channel = pusher.subscribe('my-channel');
        channel.bind('my-event', function(data) {
            Livewire.emitTo('crm.panel-mensajes', 'render');
            Livewire.emitTo('crm.panel-mensajes', 'SenalChat');
            Livewire.emitTo('crm.abrir-json', 'render');
        });
    </script>
</div>
