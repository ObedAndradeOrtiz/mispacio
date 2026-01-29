<div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                document.getElementById('myButton').click();
            }, 1);
        });
    </script>
    <style>
        #preloader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.8);
            display: none;
            justify-content: center;
            align-items: center;
        }

        #preloader .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #52b1e5;

            border-radius: 50%;

            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
    <div class="section-body">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center">
                <div class="header-action">
                    <h1 class="page-title">Inicio</h1>
                    <ol class="breadcrumb page-breadcrumb">
                        <li class="breadcrumb-item"><a href="#">{{ Auth::user()->sucursal }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Call Center</li>
                    </ol>
                </div>
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link @if ($opcion == 0) active @endif" data-toggle="tab"
                            href="#admin-Dashboard" wire:click="setOpcion(0)">Por Agendar({{ $realizadas }})</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if ($opcion == 1) active @endif" data-toggle="tab"
                            href="#admin-Activity" wire:click="setOpcion(1)">Agendados({{ $agendadas }})</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if ($opcion == 4) active @endif" data-toggle="tab"
                            href="#admin-Activity" wire:click="setOpcion(4)">Asisitidos({{ $asistidos }})</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="mr-2 card-title">
                    PANEL DE LLAMADAS:
                </h3>
                <div class="row">
                    <div class="col-md-12">
                        <div style="display: flex;">

                            <div class="mt-2" style=" border-radius: 5px;">
                                <input type="text" class="form-control" id="exampleInputDisabled1"
                                    wire:model="busqueda" placeholder="Buscar llamadas...">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-options">
                    @livewire('calls-center.crear-call')
                </div>
            </div>
            <div class="card-body">
                @if ($opcion == 2)
                    <div style="margin-right: 1%; display:flex;">
                        <label>Sucursal: </label>
                        <select class="form-control" wire:model.defer="areaseleccionadacalendario">
                            <option value="0">Sin seleccionar</option>
                            @foreach ($areas as $lista)
                                <option value="{{ $lista->id }}">{{ $lista->area }}</option>
                            @endforeach
                        </select>
                        <button class="btn btn-warning" wire:click="actulizargrafico">Actualizar</button>
                    </div>
                    <iframe id="calendarioIframe" src="https://spamiora.ddns.net/calendario" frameborder="0"
                        style="height: 100vh; width:100%;"></iframe>
                @else
                    @if ($opcion != 4)
                        <div class="flex-wrap d-flex">
                            <div style="margin-right: 1%; font-size: 10px;">
                                <label>Sucursal: </label>
                                <select class="form-control" wire:model="areaseleccionada">
                                    <option value="">Todas</option>
                                    @foreach ($areas as $lista)
                                        <option value="{{ $lista->area }}">{{ $lista->area }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div style="margin-right: 1%; font-size: 10px;">
                                <label for="">Rango: </label>
                                <select class="form-control" wire:model="rangoseleccionado" style="font-size: 10px;">
                                    <option value="Diario">Diario</option>
                                    <option value="Ayer">Ayer</option>
                                    <option value="Semanal">Hace 5 días</option>
                                    <option value="Mensual">Hace 30 días</option>
                                    <option value="Todos">Todos</option>
                                    <option value="Personalizado">Personalizado</option>
                                </select>
                            </div>
                            <div style="margin-right: 1%; font-size: 10px;">
                                <label for="fecha-inicio">Desde:</label>
                                <input style="font-size:10px;" type="date" id="fecha-inicio" class="form-control"
                                    wire:model="fechaInicioMes" @if (in_array($rangoseleccionado, ['Ayer', 'Diario', 'Semanal', 'Mensual', 'Todos'])) readonly @endif>
                            </div>
                            <div style="margin-right: 1%; font-size: 10px;">
                                <label for="fecha-actual">Hasta:</label>
                                <input style="font-size: 10px;" type="date" id="fecha-actual" class="form-control"
                                    wire:model="fechaActual" @if (in_array($rangoseleccionado, ['Ayer', 'Diario', 'Semanal', 'Mensual', 'Todos'])) readonly @endif>
                            </div>
                            <div style="margin-right: 1%; font-size: 10px;">
                                <label for="fecha-actual">Responsable:</label>
                                <select class="form-control" wire:model="responsableseleccionado"
                                    style="font-size: 10px;">
                                    <option value="">Todos</option>
                                    @foreach ($responsables as $lista)
                                        <option value="{{ $lista->name }}">{{ $lista->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                        </div>
                        <div class="mt-4">
                            <div class="table-responsive">
                                <table class="table mb-0 table-striped text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th># LLAMADAS</th>
                                            <th>SUCURSAL</th>
                                            <th>NOMBRE</th>
                                            <th>TELEFONO</th>
                                            @if ($actividad != 'Pendiente')
                                                <th>COMENTARIO</th>
                                            @endif
                                            <th>FECHA</th>
                                            @if ($actividad == 'Pendiente')
                                                <th>Responsable</th>
                                            @endif
                                            <th style="min-width: 100px">ACCIÓN</th>
                                        </tr>
                                    </thead>
                                    <tbody style="font-size: 1vw;">
                                        <style>
                                            td {
                                                max-width: 200px;
                                                overflow: hidden;
                                                text-overflow: ellipsis;
                                                white-space: nowrap;
                                            }
                                        </style>
                                        @foreach ($llamadas as $lista)
                                            <tr>
                                                <td>{{ $lista->id }}</td>
                                                <td>{{ $lista->cantidad }}</td>
                                                <td>{{ $lista->area }}</td>
                                                <td>{{ $lista->empresa }}</td>
                                                <td>{{ $lista->telefono }}</td>
                                                @if ($actividad != 'Pendiente')
                                                    <td>{{ $lista->comentario }}</td>
                                                @endif
                                                @php
                                                    \Carbon\Carbon::setLocale('es');

                                                    $fecha = $lista->fecha;
                                                    $fechaCarbon = \Carbon\Carbon::parse($fecha);
                                                    $fecha_formateada = $fechaCarbon->isoFormat(
                                                        'dddd D [de] MMMM [del] YYYY',
                                                    );
                                                @endphp
                                                <td>{{ $fecha_formateada }}</td>

                                                @if ($actividad == 'Pendiente')
                                                    <td>{{ $lista->responsable }}</td>
                                                @endif
                                                <td>
                                                    <div class="flex align-items-center list-user-action">
                                                        @livewire('calls-center.lazy-load-editar-call', ['idllamada' => $lista->id], key('lazy-' . $lista->id * 5))
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                {{ $llamadas->links() }}
                            </div>
                        </div>
                    @else
                        <div class="flex-wrap d-flex">
                            <div style="margin-right: 1%; font-size: 10px;">
                                <label>Sucursal: </label>
                                <select class="form-control" wire:model="areaseleccionada">
                                    <option value="">Todas</option>
                                    @foreach ($areas as $lista)
                                        <option value="{{ $lista->area }}">{{ $lista->area }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div style="margin-right: 1%; font-size: 10px;">
                                <label for="">Rango: </label>
                                <select class="form-control" wire:model="rangoseleccionado" style="font-size: 10px;">
                                    <option value="Diario">Diario</option>
                                    <option value="Ayer">Ayer</option>
                                    <option value="Semanal">Hace 5 días</option>
                                    <option value="Mensual">Hace 30 días</option>
                                    <option value="Todos">Todos</option>
                                    <option value="Personalizado">Personalizado</option>
                                </select>
                            </div>
                            <div style="margin-right: 1%; font-size: 10px;">
                                <label for="fecha-inicio">Desde:</label>
                                <input style="font-size:10px;" type="date" id="fecha-inicio" class="form-control"
                                    wire:model="fechaInicioMes" @if (in_array($rangoseleccionado, ['Ayer', 'Diario', 'Semanal', 'Mensual', 'Todos'])) readonly @endif>
                            </div>
                            <div style="margin-right: 1%; font-size: 10px;">
                                <label for="fecha-actual">Hasta:</label>
                                <input style="font-size: 10px;" type="date" id="fecha-actual"
                                    class="form-control" wire:model="fechaActual"
                                    @if (in_array($rangoseleccionado, ['Ayer', 'Diario', 'Semanal', 'Mensual', 'Todos'])) readonly @endif>
                            </div>
                            <div style="margin-right: 1%; font-size: 10px;">
                                <label for="fecha-actual">Responsable:</label>
                                <select class="form-control" wire:model="responsableseleccionado"
                                    style="font-size: 10px;">
                                    <option value="">Todos</option>
                                    @foreach ($responsables as $lista)
                                        <option value="{{ $lista->name }}">{{ $lista->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mt-4 d-flex">
                                <div>
                                    <button class="btn btn-warning d-flex" wire:click="$set('crear',true)">
                                        <span class="mr-2" style="color: white; font-size: 15px;">EXPORTAR A
                                            TXT</span>
                                        <svg class="icon-20" width="20" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M16.4109 2.76862L16.4119 3.51824C19.1665 3.73413 20.9862 5.61119 20.9891 8.48975L21 16.9155C21.0039 20.054 19.0322 21.985 15.8718 21.99L8.15188 22C5.01119 22.004 3.01482 20.027 3.01087 16.8795L3.00001 8.55272C2.99606 5.65517 4.75153 3.78311 7.50617 3.53024L7.50518 2.78061C7.5042 2.34083 7.83001 2.01 8.26444 2.01C8.69886 2.009 9.02468 2.33883 9.02567 2.77861L9.02666 3.47826L14.8914 3.47027L14.8904 2.77062C14.8894 2.33084 15.2152 2.001 15.6497 2C16.0742 1.999 16.4099 2.32884 16.4109 2.76862ZM4.52148 8.86157L19.4696 8.84158V8.49175C19.4272 6.34283 18.349 5.21539 16.4138 5.04748L16.4148 5.81709C16.4148 6.24688 16.0801 6.5877 15.6556 6.5877C15.2212 6.5887 14.8943 6.24887 14.8943 5.81909L14.8934 5.0095L9.02863 5.01749L9.02962 5.82609C9.02962 6.25687 8.70479 6.5967 8.27036 6.5967C7.83594 6.5977 7.50913 6.25887 7.50913 5.82809L7.50815 5.05847C5.58286 5.25137 4.51753 6.38281 4.52049 8.55072L4.52148 8.86157ZM15.2399 13.4043V13.4153C15.2498 13.8751 15.625 14.2239 16.0801 14.2139C16.5244 14.2029 16.8789 13.8221 16.869 13.3623C16.8483 12.9225 16.4918 12.5637 16.0485 12.5647C15.5944 12.5747 15.2389 12.9445 15.2399 13.4043ZM16.0554 17.892C15.6013 17.882 15.235 17.5032 15.234 17.0435C15.2241 16.5837 15.5884 16.2029 16.0426 16.1919H16.0525C16.5165 16.1919 16.8927 16.5707 16.8927 17.0405C16.8937 17.5102 16.5185 17.891 16.0554 17.892ZM11.1721 13.4203C11.1919 13.8801 11.568 14.2389 12.0222 14.2189C12.4665 14.1979 12.821 13.8181 12.8012 13.3583C12.7903 12.9085 12.425 12.5587 11.9807 12.5597C11.5266 12.5797 11.1711 12.9605 11.1721 13.4203ZM12.0262 17.8471C11.572 17.8671 11.1968 17.5082 11.1761 17.0485C11.1761 16.5887 11.5305 16.2089 11.9847 16.1879C12.429 16.1869 12.7953 16.5367 12.8052 16.9855C12.8259 17.4463 12.4705 17.8261 12.0262 17.8471ZM7.10433 13.4553C7.12408 13.915 7.50025 14.2749 7.95442 14.2539C8.39872 14.2339 8.75317 13.8531 8.73243 13.3933C8.72256 12.9435 8.35725 12.5937 7.91196 12.5947C7.45779 12.6147 7.10334 12.9955 7.10433 13.4553ZM7.95837 17.8521C7.5042 17.8731 7.12901 17.5132 7.10828 17.0535C7.10729 16.5937 7.46273 16.2129 7.9169 16.1929C8.3612 16.1919 8.7275 16.5417 8.73737 16.9915C8.7581 17.4513 8.40365 17.8321 7.95837 17.8521Z"
                                                fill="currentColor"></path>
                                        </svg></button>
                                </div>
                                @livewire('calls-center.crear-call')
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="table-responsive">
                                <table class="table mb-0 table-striped text-nowrap">
                                    <thead>
                                        <tr>
                                            <th># LLAMADAS</th>
                                            <th>SUCURSAL</th>
                                            <th>NOMBRE</th>
                                            <th>TELEFONO</th>
                                            <th>FECHA</th>
                                            <th>Responsable</th>

                                        </tr>
                                    </thead>
                                    <tbody style="font-size: 1vw;">
                                        <style>
                                            td {
                                                max-width: 200px;
                                                overflow: hidden;
                                                text-overflow: ellipsis;
                                                white-space: nowrap;
                                            }
                                        </style>
                                        @foreach ($listaasistidos as $lista)
                                            <tr>
                                                <td>{{ $lista->cantidad }}</td>
                                                <td>{{ $lista->area }}</td>
                                                <td>{{ $lista->empresa }}</td>
                                                <td>{{ $lista->telefono }}</td>
                                                @php
                                                    \Carbon\Carbon::setLocale('es');

                                                    $fecha = $lista->fecha;
                                                    $fechaCarbon = \Carbon\Carbon::parse($fecha);
                                                    $fecha_formateada = $fechaCarbon->isoFormat(
                                                        'dddd D [de] MMMM [del] YYYY',
                                                    );
                                                @endphp
                                                <td>{{ $fecha_formateada }}</td>


                                                <td>{{ $lista->responsable }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                {{ $listaasistidos->links() }}
                            </div>
                        </div>

                    @endif

                @endif

            </div>
        </div>
    </div>
</div>
