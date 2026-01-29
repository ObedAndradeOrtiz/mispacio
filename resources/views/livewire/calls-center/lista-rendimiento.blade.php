<div>
    <div class="tab-pane active" id="Student-all">
        <div class="card">
            <div class="card-body">
                <div class="text-center row">
                    <div class="col-6 border-right d-flex flex-column align-items-center">
                        <label class="mb-0">Desde</label>
                        <div class="font-weight-bold">
                            <input style="width: 100%;font-size:10px;" type="date" id="fecha-inicio"
                                wire:model="fechaInicioMes" class="text-start" onkeydown="return false">
                        </div>
                    </div>
                    <div class="col-6 d-flex flex-column align-items-center">
                        <label class="mb-0">Hasta</label>
                        <div class="font-weight-bold">
                            <input style="width: 100%;font-size:10px;" type="date" id="fecha-actual"
                                wire:model="fechaActual" class="text-start" onkeydown="return false">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">

                <div class="px-4">
                    <div class="py-3 table-responsive ">
                        <table class="table mb-0 table-striped table-hover table-bordered text-nowrap">
                            <thead class="thead-dark">
                                <tr class="ligth">
                                    <th>NOMBRE</th>
                                    @foreach ($areas as $item)
                                        <th>{{ $item->area }}</th>
                                    @endforeach
                                    <th>Total Usuarios</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php

                                    $totalLlamadasPorArea = array_fill(0, count($areas), 0);
                                    $totalAgendadosPorArea = array_fill(0, count($areas), 0);
                                    $totalAsistidosPorArea = array_fill(0, count($areas), 0);
                                    $totalLlamadas = 0;
                                    $totalAgendados = 0;
                                    $totalAsistidos = 0;
                                @endphp
                                @if (Auth::user()->rol == 'Administrador')
                                    @foreach ($users->where('estado', 'Activo') as $lista)
                                        <tr>
                                            <td>
                                                <div>{{ $lista->name }}
                                                </div>
                                                <div class="text-muted">{{ $lista->rol }}</div>
                                                <div class="text-muted">{{ $lista->sucursal }}</div>
                                            </td>

                                            @php
                                                $totalLlamadasFila = 0;
                                                $totalAgendadosFila = 0;
                                                $totalAsistidosFila = 0;
                                            @endphp

                                            @foreach ($areas as $index => $item)
                                                @php
                                                    $llamadas = DB::table('calls')
                                                        ->whereBetween('fecha', [$fechaInicioMes, $fechaActual])
                                                        ->where('responsable', $lista->name)
                                                        ->where('area', $item->area)
                                                        ->count();

                                                    $agendados = DB::table('calls')
                                                        ->whereBetween('fecha', [$fechaInicioMes, $fechaActual])
                                                        ->where('responsable', $lista->name)
                                                        ->where('estado', 'Pendiente')
                                                        ->where('area', $item->area)
                                                        ->count();
                                                    $asistidos = DB::table('operativos')
                                                        ->join('calls', 'operativos.idllamada', '=', 'calls.id')
                                                        ->whereBetween('calls.fecha', [$fechaInicioMes, $fechaActual])
                                                        ->where('calls.responsable', $lista->name)
                                                        ->where('calls.estado', 'Pendiente')
                                                        ->where('calls.area', $item->area)
                                                        ->whereExists(function ($query) {
                                                            $query
                                                                ->select(DB::raw(1))
                                                                ->from('registropagos')
                                                                ->whereRaw(
                                                                    'TRIM(CAST(registropagos.idcliente AS TEXT)) = TRIM(CAST(operativos.idempresa AS TEXT))',
                                                                );
                                                        })
                                                        ->count();

                                                    $totalLlamadasPorArea[$index] += $llamadas;
                                                    $totalAgendadosPorArea[$index] += $agendados;
                                                    $totalAsistidosPorArea[$index] += $asistidos;
                                                    $totalLlamadasFila += $llamadas;
                                                    $totalAgendadosFila += $agendados;
                                                    $totalAsistidosFila += $asistidos;
                                                    $totalLlamadas += $llamadas;
                                                    $totalAgendados += $agendados;
                                                    $totalAsistidos += $asistidos;
                                                @endphp

                                                <td>
                                                    <div>{{ $llamadas }}
                                                        -
                                                        Llamadas</div>
                                                    <div class="text-muted">{{ $agendados }} -
                                                        Agendados
                                                    </div>
                                                    <div>
                                                        {{ $asistidos }} -
                                                        Asistidos
                                                    </div>
                                                </td>
                                            @endforeach


                                            <td>
                                                <strong>{{ $totalLlamadasFila }} -
                                                    Llamadas</strong><br>
                                                <strong class="text-muted">{{ $totalAgendadosFila }} -
                                                    Agendados</strong> <br>
                                                <strong>{{ $totalAsistidosFila }} -
                                                    Asistidos</strong>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    @foreach ($users->where('estado', 'Activo') as $lista)
                                        <tr>
                                            <td>
                                                <div>{{ $lista->name }}
                                                </div>
                                                <div class="text-muted">{{ $lista->rol }}</div>
                                                <div class="text-muted">{{ $lista->sucursal }}</div>
                                            </td>

                                            @php
                                                $totalLlamadasFila = 0;
                                                $totalAgendadosFila = 0;
                                                $totalAsistidosFila = 0;
                                            @endphp

                                            @foreach ($areas as $index => $item)
                                                @php
                                                    $llamadas = DB::table('calls')
                                                        ->whereBetween('fecha', [$fechaInicioMes, $fechaActual])
                                                        ->where('responsable', $lista->name)
                                                        ->where('area', $item->area)
                                                        ->count();

                                                    $agendados = DB::table('calls')
                                                        ->whereBetween('fecha', [$fechaInicioMes, $fechaActual])
                                                        ->where('responsable', $lista->name)
                                                        ->where('estado', 'Pendiente')
                                                        ->where('area', $item->area)
                                                        ->count();
                                                    $asistidos = DB::table('operativos')
                                                        ->join('calls', 'operativos.idllamada', '=', 'calls.id')
                                                        ->whereBetween('calls.fecha', [$fechaInicioMes, $fechaActual])
                                                        ->where('calls.responsable', $lista->name)
                                                        ->where('calls.estado', 'Pendiente')
                                                        ->where('calls.area', $item->area)
                                                        ->whereExists(function ($query) {
                                                            $query
                                                                ->select(DB::raw(1))
                                                                ->from('registropagos')
                                                                ->whereRaw(
                                                                    'TRIM(CAST(registropagos.idcliente AS TEXT)) = TRIM(CAST(operativos.idempresa AS TEXT))',
                                                                );
                                                        })
                                                        ->count();

                                                    $totalLlamadasPorArea[$index] += $llamadas;
                                                    $totalAgendadosPorArea[$index] += $agendados;
                                                    $totalAsistidosPorArea[$index] += $asistidos;
                                                    $totalLlamadasFila += $llamadas;
                                                    $totalAgendadosFila += $agendados;
                                                    $totalAsistidosFila += $asistidos;
                                                    $totalLlamadas += $llamadas;
                                                    $totalAgendados += $agendados;
                                                    $totalAsistidos += $asistidos;
                                                @endphp

                                                <td>
                                                    <div>{{ $llamadas }}
                                                        -
                                                        Llamadas</div>
                                                    <div class="text-muted">{{ $agendados }} -
                                                        Agendados
                                                    </div>
                                                    <div>
                                                        {{ $asistidos }} -
                                                        Asistidos
                                                    </div>
                                                </td>
                                            @endforeach


                                            <td>
                                                <strong>{{ $totalLlamadasFila }} -
                                                    Llamadas</strong><br>
                                                <strong class="text-muted">{{ $totalAgendadosFila }} -
                                                    Agendados</strong> <br>
                                                <strong>{{ $totalAsistidosFila }} -
                                                    Asistidos</strong>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif

                            </tbody>


                            <tfoot>
                                <tr>
                                    <th>Total General</th>
                                    @foreach ($areas as $index => $item)
                                        <td>
                                            <strong>{{ $totalLlamadasPorArea[$index] }} -
                                                Llamadas</strong><br>
                                            <strong class="text-muted">{{ $totalAgendadosPorArea[$index] }}
                                                -
                                                Agendados</strong>
                                            <br>
                                            <strong>{{ $totalAsistidosPorArea[$index] }} -
                                                Asistidos</strong><br>
                                        </td>
                                    @endforeach
                                    <td>
                                        <strong>{{ $totalLlamadas }} -
                                            Llamadas</strong><br>
                                        <strong class="text-muted">{{ $totalAgendados }}
                                            -
                                            Agendados</strong>
                                        <br>
                                        <strong>{{ $totalAsistidos }} -
                                            Asistidos</strong><br>

                                    </td>
                                </tr>
                            </tfoot>
                        </table>

                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
