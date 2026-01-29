<div class="py-5">
    <link rel="stylesheet" href="{{ asset('assets/css/font-awesome.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" />
    <style>
        body {
            background-color: #74EBD5;
            background-image: linear-gradient(90deg, #74EBD5 0%, #9FACE6 100%);

            min-height: 100vh;
        }

        ::-webkit-scrollbar {
            width: 5px;
        }

        ::-webkit-scrollbar-track {
            width: 5px;
            background: #f5f5f5;
        }

        ::-webkit-scrollbar-thumb {
            width: 1em;
            background-color: #ddd;
            outline: 1px solid slategrey;
            border-radius: 1rem;
        }

        .text-small {
            font-size: 0.9rem;
        }

        .messages-box,
        .chat-box {
            height: 750px;
            overflow-y: scroll;
            scroll-behavior: smooth;
        }

        .rounded-lg {
            border-radius: 0.5rem;
        }

        input::placeholder {
            font-size: 0.9rem;
            color: #999;
        }

        .circle {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: #ccc;
            color: #fff;
            font-size: 20px;
            font-weight: bold;
        }

        .circle-pendiente {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 25px;
            height: 25px;
            border-radius: 50%;
            background-color: #486de6;
            color: #fff;
            font-size: 20px;
            font-weight: bold;
        }
    </style>
    <!-- For demo purpose-->
    <header class="text-center">
        <h1 class="mt-5 mb-2 text-white display-4"></h1>
    </header>

    <div class="overflow-hidden rounded-lg shadow row" style="width: 100%">
        <!-- Users box-->
        <div class="px-0 col-5">
            <div class="bg-white">
                <div class="px-4 py-2 bg-gray bg-light">
                    <p class="py-1 mb-0 h5">Usuarios</p>
                </div>
                <div class="messages-box">
                    <div class="list-group rounded-0">
                        <input type="text" class="form-control" placeholder="🔎 Busqueda" id="exampleInputDisabled1"
                            wire:model.defer="busqueda">
                        @foreach ($usuarios as $item)
                            @if ($seleccionado == $item->id)
                                <a class="text-white list-group-item list-group-item-action active rounded-0"
                                    wire:click="$set('seleccionado',{{ $item->id }})">
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
                                    wire:click="$set('seleccionado',{{ $item->id }})">
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
                                                        <div class="circle-pendiente">{{ $cantidad }}</div>
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

                    </div>
                </div>
            </div>
        </div>
        <!-- Chat Box-->
        <div class="px-0 col-7">
            <style>
                .chat-int {
                    background: url('../../assets/images/lgb.png') center center no-repeat;
                    background-color: #021554;
                    background-position: center center;
                    background-size: cover;
                    position: relative;
                }
            </style>
            <div class="px-4 py-5 chat-box chat-int" id="myDiv" style="">
                @if ($seleccionado == 0)
                    @livewire('mensajeria.ver-mi-chat', ['iduser' => 0], key(0))
                @else
                    @livewire('mensajeria.ver-mi-chat', ['iduser' => $seleccionado], key($seleccionado))
                @endif

            </div>
            <script>
                $(document).on('livewire:load', function() {
                    Livewire.on('scrollToBottom', function() {
                        var container = document.getElementById('myDiv');
                        container.scrollTop = container.scrollHeight;
                    });
                });
            </script>
            <!-- Typing area -->
            <div class="bg bg-light">
                <div class="input-group">
                    @if ($seleccionado != 0)
                        <input type="text" placeholder="Escribir mensaje" aria-describedby="button-addon2"
                            class="py-4 border-0 form-control rounded-0 bg-light" wire:model.defer="mensaje"
                            onkeyup="handleKeyPress(event)">
                        <div class="input-group-append">
                            <button id="button-addon2" type="submit" class="btn btn-link" wire:click="enviar">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                    fill="currentColor" class="bi bi-send" viewBox="0 0 16 16">
                                    <path
                                        d="M15.854.146a.5.5 0 0 1 .11.54l-5.819 14.547a.75.75 0 0 1-1.329.124l-3.178-4.995L.643 7.184a.75.75 0 0 1 .124-1.33L15.314.037a.5.5 0 0 1 .54.11ZM6.636 10.07l2.761 4.338L14.13 2.576 6.636 10.07Zm6.787-8.201L1.591 6.602l4.339 2.76 7.494-7.493Z" />
                                </svg>
                            </button>
                        </div>
                    @endif
                </div>
            </div>

        </div>
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
            Livewire.emitTo('mensajeria.ver-chats', 'render');
            Livewire.emitTo('mensajeria.ver-mi-chat', 'render');
            Livewire.emitTo('mensajeria.chat-interno-cantidad', 'render');
            Livewire.emitTo('mensajeria.mis-chat-interno', 'render');
        });
    </script> --}}
</div>
