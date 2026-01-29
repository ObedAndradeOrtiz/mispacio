<div class="Messenger_messenger">
    <div class="Messenger_header" style="background-color: #486de6; color: rgb(255, 255, 255);">
        @if ($seleccionado == 0)
            Chat interno
        @else
        <span  wire:click="seleccionar(0)"><-Atras</span>
        @endif
        <h4 class="Messenger_prompt" style="color: whitesmoke"></h4>

        <span class="chat_close_icon" style="color: #fff">-</span>

    </div>
    <div class="Messenger_content">
        <div class="Messages">

            <div class="Messages_list">

                <div>
                    <div class="list-group rounded-0">

                        @if ($seleccionado == 0)
                            <input type="text" class="form-control" placeholder="🔎 Busqueda"
                                id="exampleInputDisabled1" wire:model.defer="busqueda">
                            @foreach ($usuarios as $item)
                                @if ($seleccionado == $item->id)
                                    <a class="text-white list-group-item list-group-item-action active rounded-0"
                                        wire:click="seleccionar({{ $item->id }})">
                                        <div class="media">
                                            @php
                                                $primeraLetra = $item->name[0];
                                                $id = $item->id;
                                                $cantidad = DB::table('mensajes')
                                                    ->where('receptor', Auth::user()->id)
                                                    ->where('emisor', $id)
                                                    ->where('estado', 'A')
                                                    ->count();
                                                $ultimo = DB::table('mensajes')
                                                    ->where('receptor', Auth::user()->id)
                                                    ->where('emisor', $id)
                                                    ->latest()
                                                    ->limit(1)
                                                    ->get();
                                            @endphp
                                            <div class="circle">{{ $primeraLetra }}</div>
                                            <div class="ml-4 media-body">
                                                <div class="mb-1 d-flex align-items-center justify-content-between">
                                                    <h6 class="mb-0">{{ $item->name }}</h6><small
                                                        class="small font-weight-bold">
                                                    </small>
                                                </div>
                                                @foreach ($ultimo as $ult)
                                                    <p class="mb-0 font-italic text-small " style="color: black">
                                                        {{ $ult->mensaje }}</p>
                                                @endforeach
                                            </div>
                                        </div>
                                    </a>
                                @else
                                    <a class="text-white list-group-item list-group-item-action rounded-0"
                                        wire:click="seleccionar({{ $item->id }})">
                                        <div class="media">
                                            @php
                                                $primeraLetra = $item->name[0];
                                                $id = $item->id;
                                                $cantidad = DB::table('mensajes')
                                                    ->where('receptor', Auth::user()->id)
                                                    ->where('emisor', $id)
                                                    ->where('estado', 'A')
                                                    ->count();
                                                $ultimo = DB::table('mensajes')
                                                    ->where('receptor', Auth::user()->id)
                                                    ->where('emisor', $id)
                                                    ->latest()
                                                    ->limit(1)
                                                    ->get();
                                            @endphp
                                            <div class="circle">{{ $primeraLetra }}</div>
                                            <div class="ml-4 media-body">
                                                <div class="mb-1 d-flex align-items-center justify-content-between">
                                                    <h6 class="mb-0">{{ $item->name }}</h6>
                                                    <small class="small font-weight-bold" style="color:black">
                                                        @if ($cantidad > 0)
                                                            <div class="circle-pendiente">
                                                                {{ $cantidad }}</div>
                                                        @endif
                                                    </small>
                                                </div>
                                                @foreach ($ultimo as $ult)
                                                    <p class="mb-0 font-italic text-small " style="color: black">
                                                        {{ $ult->mensaje }}</p>
                                                @endforeach
                                            </div>
                                        </div>
                                    </a>
                                @endif
                            @endforeach
                        @else
                            <div class="px-4 py-5 bg-white chat-box" id="myDiv">
                                @if ($seleccionado == 0)
                                    @livewire('mensajeria.mis-chat-interno', ['iduser' => 0], key(0))
                                @else
                                    @livewire('mensajeria.mis-chat-interno', ['iduser' => $seleccionado], key($seleccionado))
                                @endif

                            </div>
                        @endif

                    </div>
                </div>


            </div>
        </div>
        @if ($seleccionado == 0)
        @else
            <div class="Input Input-blank">
                <input wire:model.defer="mensaje" class="Input_field" placeholder="Escribir mensaje..." style="height: 20px;">
                <button class="Input_button Input_button-send"  wire:click="enviar">
                    <div class="Icon" style="width: 18px; height: 18px;">
                        <svg width="57px" height="54px" viewBox="1496 193 57 54" version="1.1"
                            xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                            style="width: 18px; height: 18px;">
                            <g id="Group-9-Copy-3" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"
                                transform="translate(1523.000000, 220.000000) rotate(-270.000000) translate(-1523.000000, -220.000000) translate(1499.000000, 193.000000)">
                                <path
                                    d="M5.42994667,44.5306122 L16.5955554,44.5306122 L21.049938,20.423658 C21.6518463,17.1661523 26.3121212,17.1441362 26.9447801,20.3958097 L31.6405465,44.5306122 L42.5313185,44.5306122 L23.9806326,7.0871633 L5.42994667,44.5306122 Z M22.0420732,48.0757124 C21.779222,49.4982538 20.5386331,50.5306122 19.0920112,50.5306122 L1.59009899,50.5306122 C-1.20169244,50.5306122 -2.87079654,47.7697069 -1.64625638,45.2980459 L20.8461928,-0.101616237 C22.1967178,-2.8275701 25.7710778,-2.81438868 27.1150723,-0.101616237 L49.6075215,45.2980459 C50.8414042,47.7885641 49.1422456,50.5306122 46.3613062,50.5306122 L29.1679835,50.5306122 C27.7320366,50.5306122 26.4974445,49.5130766 26.2232033,48.1035608 L24.0760553,37.0678766 L22.0420732,48.0757124 Z"
                                    id="sendicon" fill="#96AAB4" fill-rule="nonzero"></path>
                            </g>
                        </svg>
                    </div>
                </button>
            </div>
        @endif
    </div>
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>
    {{-- <script>
        function handleKeyPress(event) {
            if (event.key === "Enter") {
                // Lógica para ejecutar el evento cuando se presiona Enter
                console.log("Se presionó Enter");
                Livewire.emitTo('mensajeria.ver-chats', 'enviar');
            }
        }
    </script>
    <script>
        // Enable pusher logging - don't include this in production
        Pusher.logToConsole = true;

        var pusher = new Pusher('6d4f547e6d802887f1dc', {
            cluster: 'sa1'
        });

        var channel = pusher.subscribe('my-channel');
        channel.bind('my-event', function(data) {
            // Livewire.emitTo('recepcionista.lista-recepcion', 'render');
            // Livewire.emitTo('calls-center.lista-call', 'render');
            Livewire.emitTo('mensajeria.chat-interno-cantidad', 'render');
            Livewire.emitTo('mensajeria.mis-chat-interno', 'render');
        });
    </script> --}}
</div>



