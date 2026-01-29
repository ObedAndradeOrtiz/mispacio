<div>
    <div class="section-body">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center">
                <div class="header-action">
                    <h1 class="page-title">Inicio</h1>
                    <ol class="breadcrumb page-breadcrumb">
                        <li class="breadcrumb-item"><a href="#">{{ Auth::user()->sucursal }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">PANEL DE RECEPCIÓN: </h3>
                <div class="ml-4" style=" border-radius: 5px;">
                    <input type="text" class="form-control" id="exampleInputDisabled1" wire:model="busqueda"
                        placeholder="Buscar Clientes...">
                </div>
                <div class="card-options">
                    @livewire('clientes.crear-cliente')
                    <button class="ml-2 btn btn-warning d-flex" wire:click='copiarConsultaAlPortapapeles'
                        style="font-size: 1vw;">
                        <svg class="icon-20" width="20" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M14.7379 2.76175H8.08493C6.00493 2.75375 4.29993 4.41175 4.25093 6.49075V17.2037C4.20493 19.3167 5.87993 21.0677 7.99293 21.1147C8.02393 21.1147 8.05393 21.1157 8.08493 21.1147H16.0739C18.1679 21.0297 19.8179 19.2997 19.8029 17.2037V8.03775L14.7379 2.76175Z"
                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            </path>
                            <path d="M14.4751 2.75V5.659C14.4751 7.079 15.6231 8.23 17.0431 8.234H19.7981"
                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            </path>
                            <path d="M14.2882 15.3584H8.88818" stroke="currentColor" stroke-width="1.5"
                                stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M12.2432 11.606H8.88721" stroke="currentColor" stroke-width="1.5"
                                stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="flex-wrap d-flex">
                    <div style="margin-right: 1%; font-size: 1vw;">
                        <label>Sucursal: </label>
                        <select class="form-control" wire:model="areaseleccionada" style="font-size: 1vw;">
                            <option value="">Todas</option>
                            @foreach ($areas as $lista)
                                <option value="{{ $lista->area }}">{{ $lista->area }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div style="margin-right: 1%; font-size:1vw;">
                        <label for="">Selecciona el tratamiento:</label>
                        <select class="form-control" wire:model="tratamientobuscado" style="font-size: 1vw;">
                            <option value="">Todos</option>
                            @foreach ($tratamientos as $item)
                                <option value="{{ $item->nombre }}">{{ $item->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div style="margin-right: 1%;  font-size: 1vw; width:180px;">
                        <label for="">Rango: </label>
                        <select class="form-control" wire:model="rangoseleccionado" style="font-size: 1vw;">
                            <option value="Todos">Todos</option>
                            {{-- <option value="Diario">Hoy</option>
                            <option value="Ayer">Ayer</option>
                            <option value="5 dias">5 días</option>
                            <option value="7 dias">7 días</option>
                            <option value="1 mes">1 Mes</option> --}}
                            <option value="Personalizado">Personalizado</option>
                        </select>
                    </div>
                    <div style="margin-right: 1%; font-size: 1vw;">
                        <label for="fecha-inicio">Desde:</label>
                        <input style="font-size: 1vw;" type="date" id="fecha-inicio" class="form-control"
                            wire:model="fechaInicioMes" @if (in_array($rangoseleccionado, ['Ayer', 'Diario', 'Semanal', 'Mensual', 'Todos'])) readonly @endif>
                    </div>
                    <div style="margin-right: 1%; font-size: 1vw;">
                        <label for="fecha-actual">Hasta:</label>
                        <input style="font-size: 1vw;" type="date" id="fecha-actual" class="form-control"
                            wire:model="fechaActual" @if (in_array($rangoseleccionado, ['Ayer', 'Diario', 'Semanal', 'Mensual', 'Todos'])) readonly @endif>
                    </div>

                </div>

                <div class="table-responsive">
                    <table class="table mb-0 table-striped text-nowrap">
                        <thead>
                            <tr>
                                <th>ASISTIDO</th>
                                <th>NOMBRE</th>
                                <th>TELEFONO</th>
                                <th>FECHA/HORA/LUGAR/AGENDADO POR</th>
                                <th>ACCIÓN</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($llamadas as $lista)
                                @php
                                    $verificar = false;
                                    $haypago = false;
                                    $paguito = DB::table('registropagos')
                                        ->where('idcliente', $lista->idempresa)
                                        ->whereBetween('fecha', [$fechaInicioMes, $fechaActual])
                                        ->get();
                                    foreach ($paguito as $key => $value) {
                                        $haypago = true;
                                    }
                                    $mipagos = DB::table('pagos')->where('idoperativo', $lista->id)->get();
                                    foreach ($mipagos as $pago) {
                                        if ($pago->cantidad < $pago->pagado) {
                                            $verificar = true;
                                        }
                                    }
                                @endphp
                                <tr>
                                    @if ($haypago)
                                        <td>✅</td>
                                    @else
                                        <td>❌</td>
                                    @endif


                                    <td>{{ $lista->empresa }}
                                        <div class="text-muted">{{ $lista->area }}</div>
                                        <div class="text-muted">{{ $lista->idempresa }}</div>
                                    </td>
                                    <td>{{ $lista->telefono }}</td>
                                    @php
                                        \Carbon\Carbon::setLocale('es');

                                        $fecha = $lista->fecha;
                                        $fechaCarbon = \Carbon\Carbon::parse($fecha);
                                        $fecha_formateada = $fechaCarbon->isoFormat('dddd D [de] MMMM [del] YYYY');
                                    @endphp

                                    <td>{{ $fecha_formateada }}<div class="text-muted">{{ $lista->hora }}</div>
                                        <div class="text-muted">
                                            {{ $lista->area }}</div>
                                        <div class="text-muted">{{ $lista->responsable }}</div>
                                    </td>
                                    <td class="d-flex">
                                        <div class="flex align-items-center list-user-action">
                                            @livewire('operativos.load-editar-ficha', ['operativo' => $lista->id], key('lazy-' . $lista->id * 5))
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    {{ $llamadas->links() }}
                </div>

            </div>
        </div>
    </div>

</div>
