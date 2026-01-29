<div class="mt-4 section-body">
    <!-- Handsontable JS -->
    <script src="https://cdn.jsdelivr.net/npm/handsontable@12.1.0/dist/handsontable.full.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    @php
        $fechaInicioMesActual = now()->startOfMonth();
        $fechaFinActual = now();
        $fechaInicioMesAnterior = now()->subMonthNoOverflow()->startOfMonth();
        $fechaFinMesAnterior = now()->subMonthNoOverflow()->endOfMonth();
        $ventas = DB::table('users as u')
            ->select(
                'u.id',
                'u.name',
                'u.rol',
                'u.sucursal',
                DB::raw("
            COALESCE((
                SELECT SUM(CAST(r1.precio AS DECIMAL(10, 2)))
                FROM registroinventarios r1
                WHERE r1.iduser = u.id
                AND r1.motivo IN ('compra', 'farmacia')
                AND r1.fecha BETWEEN '{$fechaInicioMesActual}' AND '{$fechaFinActual}'
            ), 0) as total_mes_actual
        "),
                DB::raw("
            COALESCE((
                SELECT SUM(CAST(r2.precio AS DECIMAL(10, 2)))
                FROM registroinventarios r2
                WHERE r2.iduser = u.id
                AND r2.motivo IN ('compra', 'farmacia')
                AND r2.fecha BETWEEN '{$fechaInicioMesAnterior}' AND '{$fechaFinMesAnterior}'
            ), 0) as total_mes_anterior
        "),
            )
            ->where('u.estado', 'Activo')
            ->whereIn('u.rol', ['Recepcion'])
            ->orderByDesc('total_mes_actual')
            ->get();

        $metaGlobal = $ventas->max('total_mes_anterior') > 0 ? $ventas->max('total_mes_anterior') : 1; // evita división por 0

    @endphp
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2 ">
                <div class="border-0 shadow-sm card">
                    <div class="text-center card-header fw-bold">
                        <h3 class="card-title">Gestión de ventas</h3>
                    </div>
                    <div class="card-body">
                        <!-- Título y selección de opciones -->
                        <div class="mb-4">
                            <label for="opcionSelect" class="form-label font-weight-bold">Selecciona una opción:</label>
                            <select id="opcionSelect" class="form-control form-control-lg" wire:model="opcion"
                                wire:change="setOpcion">
                                <option value="0" @if ($opcion == 0) selected @endif>Recepción
                                </option>
                                <option value="3" @if ($opcion == 3) selected @endif>Promedios
                                    Ventas Diarias</option>
                                <option value="7" @if ($opcion == 7) selected @endif>Agendados por
                                    sucursal</option>
                                <option value="1" @if ($opcion == 1) selected @endif>Call Center
                                </option>
                                <option value="4" @if ($opcion == 4) selected @endif>Promedios
                                    Agendados</option>
                                <option value="2" @if ($opcion == 2) selected @endif>Cosmetología
                                </option>
                                <option value="10" @if ($opcion == 10) selected @endif>Promedios
                                    de enganches</option>

                                <option value="8" @if ($opcion == 8) selected @endif>Ganancias por
                                    personal</option>
                                <option value="6" @if ($opcion == 6) selected @endif>Ingresos por
                                    sucursal</option>



                                <option value="5" @if ($opcion == 5) selected @endif>Clientes no
                                    asistidos</option>


                            </select>

                        </div>

                        <!-- Buscador -->
                        <div class="mb-4">
                            <label for="busqueda" class="form-label font-weight-bold">Búsqueda:</label>
                            <input type="text" id="busqueda" class="form-control form-control-lg"
                                placeholder="Nombre del personal..." wire:model="busqueda">
                        </div>

                        <!-- Fechas -->
                        <div class="mb-4 d-flex justify-content-between">
                            <div class="col-md-5">
                                <label for="fecha-inicio" class="form-label font-weight-bold">Desde:</label>
                                <input type="date" id="fecha-inicio" class="form-control" wire:model="fechaInicioMes"
                                    style="font-size: 14px;">
                            </div>
                            <div class="col-md-5">
                                <label for="fecha-actual" class="form-label font-weight-bold">Hasta:</label>
                                <input type="date" id="fecha-actual" class="form-control" wire:model="fechaActual"
                                    style="font-size: 14px;">
                            </div>
                        </div>

                        <!-- Sucursal -->
                        <div class="mb-4">
                            <label for="sucursal" class="form-label font-weight-bold">Sucursal:</label>
                            <select id="sucursal" wire:model="areaseleccionada" class="form-select"
                                style="font-size: 14px;">
                                <option value="">Todos</option>
                                @foreach ($areas as $item)
                                    <option value="{{ $item->area }}">{{ $item->area }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-10">
                <div class="card">
                    <div class="text-center card-header fw-bold">
                        <h3 class="card-title">
                            @switch($opcion)
                                @case(0)
                                    Recepción
                                @break

                                @case(1)
                                    Call Center
                                @break

                                @case(2)
                                    Cosmetología
                                @break

                                @case(3)
                                    Promedios Ventas Diarias
                                @break

                                @case(4)
                                    Promedios Agendados
                                @break

                                @case(5)
                                    Clientes no asistidos
                                @break

                                @case(6)
                                    Ingresos por sucursal
                                @break

                                @case(7)
                                    Agendados por sucursal
                                @break

                                @case(8)
                                    Ganancias por personal
                                @break

                                @case(10)
                                    Promedios de Enganches
                                @break

                                @default
                                    Título no definido
                            @endswitch
                        </h3>
                    </div>

                    @if ($opcion == 0)
                        <div class="tab-pane active" id="Student-all">
                            <div class="card">
                                <div class="card-body">


                                    <button class="btn btn-warning"
                                        onclick="copiarSoloTbodySinAcciones('miTablaVendedoras')">📋 Copiar cuerpo de
                                        tabla</button>

                                    <div class="px-4">
                                        <div class="py-3 table-responsive " style="height: 50vh; overflow-y:scroll;">
                                            @php
                                                $total_vendido = 0;
                                                $total_aprobado = 0;
                                                $total_ganado = 0;
                                            @endphp


                                            <table id="miTablaVendedoras"
                                                class="table mb-0 table-striped table-hover table-bordered text-nowrap">
                                                <thead class="thead-dark">

                                                    <tr class="ligth">
                                                        <th>NOMBRE</th>
                                                        <th>F. INGRESO</th>
                                                        <th>ATENDIDOS</th>
                                                        <th>LLAMADAS</th>
                                                        <th>AGENDADOS</th>
                                                        <th>TOTAL VENDIDO</th>
                                                        <th>R. {{ $metaGlobal . ' Bs.' }}</th>
                                                        <th>MONTO GANADO (4%)</th>
                                                        <th>ACCIÓN</th>

                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <div>
                                                        <style>
                                                            td {
                                                                max-width: 200px;
                                                                overflow: hidden;
                                                                text-overflow: ellipsis;
                                                                white-space: nowrap;
                                                            }
                                                        </style>
                                                        @php
                                                            $totalatendidos = 0;
                                                            $totalvendido = 0;
                                                            $totalcall = 0;
                                                            $totalagendado = 0;

                                                        @endphp
                                                        @foreach ($users as $lista)
                                                            @if ($lista->estado == 'Inactivo')
                                                                <tr style="background-color: rgba(255, 0, 0, 0.117);">
                                                                    <td>
                                                                        <div>
                                                                            {{ $lista->name }}
                                                                        </div>
                                                                        <div class="text-muted">{{ $lista->rol }}
                                                                        </div>
                                                                        <div class="text-muted">{{ $lista->sucursal }}
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        @if ($lista->fechainicio)
                                                                            {{ $lista->fechainicio }}
                                                                        @else
                                                                            <label for="">No tiene fecha de
                                                                                ingreso</label>
                                                                        @endif

                                                                    </td>
                                                                    @php

                                                                        $atendidos = DB::table('registropagos')
                                                                            ->whereBetween('fecha', [
                                                                                $fechaInicioMes,
                                                                                $fechaActual,
                                                                            ])
                                                                            ->where('iduser', $lista->id)
                                                                            ->count();

                                                                        $vendidos = DB::table('registroinventarios')
                                                                            ->whereBetween('fecha', [
                                                                                $fechaInicioMes,
                                                                                $fechaActual,
                                                                            ])
                                                                            ->where('iduser', $lista->id)
                                                                            ->whereIn('motivo', ['farmacia', 'compra'])
                                                                            ->count();

                                                                        $cantidadvendidos = DB::table(
                                                                            'registroinventarios',
                                                                        )
                                                                            ->whereBetween('fecha', [
                                                                                $fechaInicioMes,
                                                                                $fechaActual,
                                                                            ])
                                                                            ->where('iduser', $lista->id)
                                                                            ->whereIn('motivo', ['farmacia', 'compra'])
                                                                            ->sum(DB::raw('CAST(precio AS NUMERIC)'));

                                                                        $llamadas = DB::table('calls')
                                                                            ->whereBetween('fecha', [
                                                                                $fechaInicioMes,
                                                                                $fechaActual,
                                                                            ])
                                                                            ->where('responsable', $lista->name)

                                                                            ->count();

                                                                        $agendados = DB::table('operativos')
                                                                            ->whereBetween('fecha', [
                                                                                $this->fechaInicioMes,
                                                                                $this->fechaActual,
                                                                            ])
                                                                            ->where('responsable', $lista->name)

                                                                            ->count();

                                                                    @endphp
                                                                    @if ($lista->rol == 'Recepcion')
                                                                        <td>{{ $atendidos }}</td>
                                                                    @else
                                                                        <td>{{ $atendidos }}</td>
                                                                    @endif

                                                                    {{-- <td>{{ $cantidadvendidos }}Bs.</td> --}}
                                                                    <td>{{ $llamadas }}</td>
                                                                    <td>
                                                                        {{ $agendados }}
                                                                    </td>
                                                                    @php
                                                                        $fechaActualMasUno = date(
                                                                            'Y-m-d',
                                                                            strtotime($fechaActual . ' +1 day'),
                                                                        );

                                                                        $totalVentas = DB::table('registroinventarios')
                                                                            ->where('iduser', $lista->id)
                                                                            ->whereIn('motivo', ['compra', 'farmacia'])
                                                                            ->whereBetween('fecha', [
                                                                                $fechaInicioMes,
                                                                                $fechaActualMasUno,
                                                                            ])
                                                                            ->sum(
                                                                                DB::raw(
                                                                                    'CAST(precio AS DECIMAL(10,2)) ',
                                                                                ),
                                                                            );
                                                                        $total_vendido = $totalVentas + $total_vendido;
                                                                        $total_aprobado =
                                                                            $total_aprobado + $lista->monto_aprobado;
                                                                        $total_ganado =
                                                                            $total_ganado + $lista->monto_ganado;
                                                                    @endphp

                                                                    <td>{{ $totalVentas }}</td>
                                                                    <td></td>
                                                                    <td>{{ number_format($lista->monto_ganado, 2) }}
                                                                    </td>
                                                                    <td>
                                                                        <button class="btn btn-success"
                                                                            wire:click="verventas('{{ $lista->id }}')">Ver
                                                                            ventas</button>
                                                                    </td>
                                                                </tr>
                                                            @else
                                                                <tr>
                                                                    <td>
                                                                        <div>
                                                                            {{ $lista->name }}
                                                                        </div>
                                                                        <div class="text-muted">{{ $lista->rol }}
                                                                        </div>
                                                                        <div class="text-muted">{{ $lista->sucursal }}
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        @if ($lista->fechainicio)
                                                                            {{ $lista->fechainicio }}
                                                                        @else
                                                                            <label for="">No tiene fecha de
                                                                                ingreso</label>
                                                                        @endif

                                                                    </td>
                                                                    @php

                                                                        $atendidos = DB::table('registropagos')
                                                                            ->whereBetween('fecha', [
                                                                                $fechaInicioMes,
                                                                                $fechaActual,
                                                                            ])
                                                                            ->where('iduser', $lista->id)
                                                                            ->count();
                                                                        $totalatendidos = $totalatendidos + $atendidos;
                                                                        $vendidos = DB::table('registroinventarios')
                                                                            ->whereBetween('fecha', [
                                                                                $fechaInicioMes,
                                                                                $fechaActual,
                                                                            ])
                                                                            ->where('iduser', $lista->id)
                                                                            ->whereIn('motivo', ['farmacia', 'compra'])
                                                                            ->count();

                                                                        $cantidadvendidos = DB::table(
                                                                            'registroinventarios',
                                                                        )
                                                                            ->whereBetween('fecha', [
                                                                                $fechaInicioMes,
                                                                                $fechaActual,
                                                                            ])
                                                                            ->where('iduser', $lista->id)
                                                                            ->whereIn('motivo', ['farmacia', 'compra'])
                                                                            ->sum(DB::raw('CAST(precio AS NUMERIC)'));
                                                                        $totalvendido =
                                                                            $totalvendido + $cantidadvendidos;
                                                                        $llamadas = DB::table('calls')
                                                                            ->whereBetween('fecha', [
                                                                                $fechaInicioMes,
                                                                                $fechaActual,
                                                                            ])
                                                                            ->where('responsable', $lista->name)

                                                                            ->count();
                                                                        $totalcall = $totalcall + $llamadas;
                                                                        $agendados = DB::table('operativos')
                                                                            ->whereBetween('fecha', [
                                                                                $this->fechaInicioMes,
                                                                                $this->fechaActual,
                                                                            ])
                                                                            ->where('responsable', $lista->name)

                                                                            ->count();
                                                                        $totalagendado = $totalagendado + $agendados;
                                                                    @endphp
                                                                    @if ($lista->rol == 'Recepcion')
                                                                        <td>{{ $atendidos }}</td>
                                                                    @else
                                                                        <td>{{ $atendidos }}</td>
                                                                    @endif

                                                                    {{-- <td>{{ $cantidadvendidos }}Bs.</td> --}}
                                                                    <td>{{ $llamadas }}</td>
                                                                    <td>
                                                                        {{ $agendados }}
                                                                    </td>
                                                                    @php
                                                                        $fechaActualMasUno = date(
                                                                            'Y-m-d',
                                                                            strtotime($fechaActual . ' +1 day'),
                                                                        );

                                                                        $totalVentas = DB::table('registroinventarios')
                                                                            ->where('iduser', $lista->id)
                                                                            ->whereIn('motivo', ['compra', 'farmacia'])
                                                                            ->whereBetween('fecha', [
                                                                                $fechaInicioMes,
                                                                                $fechaActualMasUno,
                                                                            ])
                                                                            ->sum(
                                                                                DB::raw(
                                                                                    'CAST(precio AS DECIMAL(10,2)) ',
                                                                                ),
                                                                            );
                                                                        $total_vendido = $totalVentas + $total_vendido;
                                                                        $total_aprobado =
                                                                            $total_aprobado + $lista->monto_aprobado;
                                                                        $total_ganado =
                                                                            $total_ganado + $lista->monto_ganado;

                                                                        $personal = DB::table('users as u')
                                                                            ->joinSub(
                                                                                function ($query) use (
                                                                                    $fechaInicioMesActual,
                                                                                    $fechaActualMasUno,
                                                                                ) {
                                                                                    $query
                                                                                        ->from('registroinventarios')
                                                                                        ->select(
                                                                                            'iduser',
                                                                                            DB::raw(
                                                                                                "TO_CHAR(created_at, 'YYYY-MM-DD HH24:MI') as hora_sin_segundos",
                                                                                            ),
                                                                                            DB::raw(
                                                                                                'SUM(CAST(precio AS DECIMAL)) as total_venta',
                                                                                            ),
                                                                                        )
                                                                                        ->whereIn('motivo', [
                                                                                            'compra',
                                                                                            'farmacia',
                                                                                        ])
                                                                                        ->whereRaw(
                                                                                            'CAST(precio AS DECIMAL) >= 30',
                                                                                        )
                                                                                        ->whereBetween('created_at', [
                                                                                            $fechaInicioMesActual,
                                                                                            $fechaActualMasUno,
                                                                                        ])
                                                                                        ->groupBy(
                                                                                            'iduser',
                                                                                            DB::raw(
                                                                                                "TO_CHAR(created_at, 'YYYY-MM-DD HH24:MI')",
                                                                                            ),
                                                                                        )
                                                                                        ->havingRaw(
                                                                                            'SUM(CAST(precio AS DECIMAL)) >= 100',
                                                                                        );
                                                                                },
                                                                                'sub',
                                                                                'sub.iduser',
                                                                                '=',
                                                                                'u.id',
                                                                            )
                                                                            ->select(
                                                                                'u.id',
                                                                                'u.name as nombre_usuario',
                                                                                DB::raw(
                                                                                    'SUM(sub.total_venta) as monto_aprobado',
                                                                                ),
                                                                                DB::raw(
                                                                                    'ROUND(SUM(sub.total_venta) * 0.04, 2) as monto_ganado',
                                                                                ),
                                                                            )
                                                                            ->where('u.id', $lista->id)
                                                                            ->groupBy('u.id', 'u.name')
                                                                            ->first(); // <- esto te devuelve un solo objeto

                                                                    @endphp
                                                                    <td>{{ $totalVentas }}</td>
                                                                    @php
                                                                        $actual = round($totalVentas, 2);

                                                                        $porcentaje = round(
                                                                            ($actual / $metaGlobal) * 100,
                                                                        );
                                                                    @endphp
                                                                    <td>
                                                                        <div class="progress" style="height: 20px;">
                                                                            <div class="progress-bar bg-secundary"
                                                                                role="progressbar"
                                                                                style="width: {{ $porcentaje }}%; color:black;"
                                                                                aria-valuenow="{{ $porcentaje }}"
                                                                                aria-valuemin="0" aria-valuemax="100">
                                                                                {{ $porcentaje }}%
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                    @if ($personal)
                                                                        <td>{{ number_format($personal->monto_ganado, 2) }}
                                                                        @else
                                                                        <td></td>
                                                                    @endif

                                                                    </td>
                                                                    <td>
                                                                        <button class="btn btn-success"
                                                                            wire:click="verventas('{{ $lista->id }}')">Ver
                                                                            ventas</button>
                                                                    </td>
                                                                </tr>
                                                            @endif
                                                        @endforeach
                                                        <tr>
                                                            <td>SUMA TOTAL</td>
                                                            <td></td>
                                                            <td>{{ $totalatendidos }}</td>
                                                            <td>{{ $totalcall }}</td>
                                                            <td>{{ $totalvendido }}</td>
                                                            <td>{{ $totalagendado }}</td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                        </tr>
                                                    </div>

                                                </tbody>
                                            </table>
                                            {{ $users->links() }}
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    @endif
                    @if ($opcion == 1)
                        <div class="tab-pane active" id="Student-all">
                            <div class="card">
                                <div class="card-body">

                                    <div class="px-4">
                                        <div class="py-3 table-responsive ">
                                            <table
                                                class="table mb-0 table-striped table-hover table-bordered text-nowrap">
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
                                                                        ->whereBetween('fecha', [
                                                                            $fechaInicioMes,
                                                                            $fechaActual,
                                                                        ])
                                                                        ->where('responsable', $lista->name)
                                                                        ->where('area', $item->area)
                                                                        ->count();

                                                                    $agendados = DB::table('calls')
                                                                        ->whereBetween('fecha', [
                                                                            $fechaInicioMes,
                                                                            $fechaActual,
                                                                        ])
                                                                        ->where('responsable', $lista->name)
                                                                        ->where('estado', 'Pendiente')
                                                                        ->where('area', $item->area)
                                                                        ->count();
                                                                    $asistidos = DB::table('operativos')
                                                                        ->join(
                                                                            'calls',
                                                                            'operativos.idllamada',
                                                                            '=',
                                                                            'calls.id',
                                                                        )
                                                                        ->whereBetween('calls.fecha', [
                                                                            $fechaInicioMes,
                                                                            $fechaActual,
                                                                        ])
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
                                                </tbody>


                                                <tfoot>
                                                    <tr>
                                                        <th>Total General</th>
                                                        @foreach ($areas as $index => $item)
                                                            <td>
                                                                <strong>{{ $totalLlamadasPorArea[$index] }} -
                                                                    Llamadas</strong><br>
                                                                <strong
                                                                    class="text-muted">{{ $totalAgendadosPorArea[$index] }}
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
                    @endif
                    @if ($opcion == 2)
                        <div class="tab-pane active" id="Student-all">
                            <div class="card">
                                <div class="card-body">

                                    <div class="px-4">
                                        <div class="py-3 table-responsive ">
                                            @php
                                                $total_vendido = 0;
                                                $total_aprobado = 0;
                                                $total_ganado = 0;
                                            @endphp
                                            <button class="btn btn-warning"
                                                onclick="copiarSoloTbodySinAcciones('miTablacosme')">📋 Copiar cuerpo
                                                de
                                                tabla</button>
                                            <table id="miTablacosme"
                                                class="table mb-0 table-striped table-hover table-bordered text-nowrap">
                                                <thead class="thead-dark">
                                                    <tr class="ligth">
                                                        <th>NOMBRE</th>
                                                        <th>ESTADO</th>
                                                        <th>F. INGRESO</th>
                                                        <th>ATENDIDOS</th>
                                                        <th>ENGANCHES</th>
                                                        <th>RENDIMIENTO</th>
                                                        <th>TOTAL VENDIDO</th>
                                                        <th>MONTO GANADO (4%)</th>
                                                        <th>ACCION</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <style>
                                                        td {
                                                            max-width: 200px;
                                                            overflow: hidden;
                                                            text-overflow: ellipsis;
                                                            white-space: nowrap;
                                                        }
                                                    </style>
                                                    @php
                                                        $totalatendido = 0;
                                                        $totalenganche = 0;
                                                    @endphp
                                                    @foreach ($users as $lista)
                                                        @if ($lista->estado == 'Inactivo')
                                                            <tr style="background-color: rgba(255, 0, 0, 0.117);">
                                                                <td>
                                                                    <div>
                                                                        {{ $lista->name }}
                                                                    </div>
                                                                    <div class="text-muted">{{ $lista->rol }}</div>
                                                                    <div class="text-muted">{{ $lista->sucursal }}
                                                                    </div>
                                                                </td>
                                                                <td>{{ $lista->estado }}</td>
                                                                <td>
                                                                    {{ $lista->fechainicio }}
                                                                </td>
                                                                @php
                                                                    $atendidos = DB::table('registropagos')
                                                                        ->whereBetween('fecha', [
                                                                            $fechaInicioMes,
                                                                            $fechaActual,
                                                                        ])
                                                                        ->where('idcosmetologa', $lista->id)
                                                                        ->count();
                                                                    $enganche = DB::table('historial_clientes')
                                                                        ->whereBetween('fecha', [
                                                                            $fechaInicioMes,
                                                                            $fechaActual,
                                                                        ])
                                                                        ->where('idcosmetologa', $lista->id)
                                                                        ->count();
                                                                    $totalatendido = $totalatendido + $atendidos;
                                                                    $totalenganche = $totalenganche + $enganche;
                                                                @endphp
                                                                <td>{{ $atendidos }}</td>
                                                                <td>{{ $enganche }}</td>

                                                                <td></td>


                                                                @php
                                                                    $fechaActualMasUno = date(
                                                                        'Y-m-d',
                                                                        strtotime($fechaActual . ' +1 day'),
                                                                    );

                                                                    $totalVentas = DB::table('registroinventarios')
                                                                        ->where('iduser', $lista->id)
                                                                        ->whereIn('motivo', ['compra', 'farmacia'])
                                                                        ->whereBetween('fecha', [
                                                                            $fechaInicioMes,
                                                                            $fechaActualMasUno,
                                                                        ])
                                                                        ->sum(
                                                                            DB::raw('CAST(precio AS DECIMAL(10,2)) '),
                                                                        );
                                                                    $total_vendido = $totalVentas + $total_vendido;
                                                                    $total_aprobado =
                                                                        $total_aprobado + $lista->monto_aprobado;
                                                                    $total_ganado =
                                                                        $total_ganado + $lista->monto_ganado;
                                                                @endphp

                                                                <td>{{ $totalVentas }}</td>
                                                                <td>{{ number_format($lista->monto_aprobado, 2) }}</td>
                                                                <td>{{ number_format($lista->monto_ganado, 2) }}</td>
                                                                <td>
                                                                    <button class="btn btn-success"
                                                                        wire:click="verenganches('{{ $lista->id }}')">Ver
                                                                        enganches</button>
                                                                    <br>
                                                                    <button class="mt-3 btn btn-success"
                                                                        wire:click="verventas('{{ $lista->id }}')">Ver
                                                                        ventas</button>
                                                                </td>

                                                            </tr>
                                                        @else
                                                            <tr>
                                                                <td>
                                                                    <div>
                                                                        {{ $lista->name }}
                                                                    </div>
                                                                    <div class="text-muted">{{ $lista->rol }}</div>
                                                                    <div class="text-muted">{{ $lista->sucursal }}
                                                                    </div>
                                                                </td>
                                                                <td>{{ $lista->estado }}</td>
                                                                <td>
                                                                    {{ $lista->fechainicio }}
                                                                </td>
                                                                @php
                                                                    $atendidos = DB::table('registropagos')
                                                                        ->whereBetween('fecha', [
                                                                            $fechaInicioMes,
                                                                            $fechaActual,
                                                                        ])
                                                                        ->where('idcosmetologa', $lista->id)
                                                                        ->count();
                                                                    $enganche = DB::table('historial_clientes')
                                                                        ->whereBetween('fecha', [
                                                                            $fechaInicioMes,
                                                                            $fechaActual,
                                                                        ])
                                                                        ->where('idcosmetologa', $lista->id)
                                                                        ->count();
                                                                    $totalatendido = $totalatendido + $atendidos;
                                                                    $totalenganche = $totalenganche + $enganche;
                                                                @endphp
                                                                <td>{{ $atendidos }}</td>
                                                                <td>{{ $enganche }}</td>

                                                                <td></td>


                                                                @php
                                                                    $fechaActualMasUno = date(
                                                                        'Y-m-d',
                                                                        strtotime($fechaActual . ' +1 day'),
                                                                    );

                                                                    $totalVentas = DB::table('registroinventarios')
                                                                        ->where('iduser', $lista->id)
                                                                        ->whereIn('motivo', ['compra', 'farmacia'])
                                                                        ->whereBetween('fecha', [
                                                                            $fechaInicioMes,
                                                                            $fechaActualMasUno,
                                                                        ])
                                                                        ->sum(
                                                                            DB::raw('CAST(precio AS DECIMAL(10,2)) '),
                                                                        );
                                                                    $total_vendido = $totalVentas + $total_vendido;
                                                                    $total_aprobado =
                                                                        $total_aprobado + $lista->monto_aprobado;
                                                                    $total_ganado =
                                                                        $total_ganado + $lista->monto_ganado;
                                                                @endphp

                                                                <td>{{ $totalVentas }}</td>
                                                                <td>{{ number_format($lista->monto_aprobado, 2) }}</td>
                                                                <td>{{ number_format($lista->monto_ganado, 2) }}</td>
                                                                <td>
                                                                    <button class="btn btn-success"
                                                                        wire:click="verenganches('{{ $lista->id }}')">Ver
                                                                        enganches</button>
                                                                    <br>
                                                                    <button class="mt-3 btn btn-success"
                                                                        wire:click="verventas('{{ $lista->id }}')">Ver
                                                                        ventas</button>
                                                                </td>

                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                    <tr>
                                                        <td>SUMA TOTAL</td>
                                                        <td>{{ $totalatendido }}</td>
                                                        <td>{{ $totalenganche }}</td>

                                                    </tr>
                                                </tbody>
                                            </table>
                                            {{ $users->links() }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if ($opcion == 3)
                        <div class="tab-pane active" id="Student-all">
                            <div class="card">
                                <div class="card-body">MONTO GANADO (4%)
                                    <div class="px-4">
                                        @php
                                            $mesActual = date('n');
                                            $anioActual = date('Y');
                                            $meses_es = [
                                                1 => 'Enero',
                                                2 => 'Febrero',
                                                3 => 'Marzo',
                                                4 => 'Abril',
                                                5 => 'Mayo',
                                                6 => 'Junio',
                                                7 => 'Julio',
                                                8 => 'Agosto',
                                                9 => 'Septiembre',
                                                10 => 'Octubre',
                                                11 => 'Noviembre',
                                                12 => 'Diciembre',
                                            ];
                                        @endphp
                                        <div class="py-3 table-responsive ">
                                            <table
                                                class="table mb-0 table-striped table-hover table-bordered text-nowrap">
                                                <thead class="thead-dark">
                                                    <tr class="ligth">
                                                        <th>SUCURSAL</th>
                                                        @for ($i = 1; $i <= $mesActual; $i++)
                                                            <th>{{ strtoupper($meses_es[$i]) }}</th>
                                                        @endfor
                                                        <th>VENTA DEL DÍA</th>
                                                        <th>DIF. MES ANTERIOR</th>
                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    @foreach ($areas as $lista)
                                                        <tr>
                                                            <td>{{ $lista->area }}</td>
                                                            @php
                                                                $promedios = [];
                                                                for ($mes = 1; $mes <= $mesActual; $mes++) {
                                                                    $fechaInicio = date(
                                                                        'Y-m-d',
                                                                        mktime(0, 0, 0, $mes, 1, $anioActual),
                                                                    );
                                                                    $fechaFin = date(
                                                                        'Y-m-t',
                                                                        mktime(0, 0, 0, $mes, 1, $anioActual),
                                                                    );
                                                                    $hoy = date('Y-m-d');
                                                                    $esMesActual =
                                                                        $mes == date('n') && $anioActual == date('Y');

                                                                    if ($esMesActual) {
                                                                        // Usamos el número de días transcurridos hasta hoy
                                                                        $diasDelMes = date('j'); // Día del mes actual (1-31)
                                                                    } else {
                                                                        // Usamos el total de días del mes
                                                                        $diasDelMes = cal_days_in_month(
                                                                            CAL_GREGORIAN,
                                                                            $mes,
                                                                            $anioActual,
                                                                        );
                                                                    }

                                                                    $sumaMes = DB::table('registroinventarios')
                                                                        ->where('sucursal', $lista->area)
                                                                        ->whereIn('modo', ['efectivo', 'qr'])
                                                                        ->whereBetween('fecha', [
                                                                            $fechaInicio,
                                                                            $fechaFin,
                                                                        ])
                                                                        ->whereIn('motivo', ['compra', 'farmacia'])
                                                                        ->sum(DB::raw('CAST(precio AS DECIMAL(10,2))'));

                                                                    // Ahora puedes calcular el promedio:

                                                                    $promedios[$mes] = round($sumaMes / $diasDelMes, 2);
                                                                }

                                                                $hoy = date('Y-m-d');
                                                                $ventaHoy = DB::table('registroinventarios')
                                                                    ->where('sucursal', $lista->area)
                                                                    ->whereIn('modo', ['efectivo', 'qr'])
                                                                    ->where('fecha', $hoy)
                                                                    ->whereIn('motivo', ['compra', 'farmacia'])
                                                                    ->sum(DB::raw('CAST(precio AS DECIMAL(10,2))'));

                                                                $mesAnterior = $mesActual > 1 ? $mesActual - 1 : 1;
                                                                $diferencia =
                                                                    $ventaHoy - ($promedios[$mesAnterior] ?? 0);
                                                            @endphp

                                                            @for ($i = 1; $i <= $mesActual; $i++)
                                                                <td>{{ number_format($promedios[$i] ?? 0, 2) }}</td>
                                                            @endfor

                                                            <td>{{ number_format($ventaHoy, 2) }}</td>
                                                            <td>{{ number_format($diferencia, 2) }}</td>
                                                        </tr>
                                                    @endforeach

                                                    <tr>
                                                        <td><strong>TOTALES</strong></td>
                                                        @for ($i = 1; $i <= $mesActual; $i++)
                                                            @php
                                                                $totalMes = 0;

                                                                // Fechas de inicio y fin del mes evaluado
                                                                $fechaInicio = date(
                                                                    'Y-m-d',
                                                                    mktime(0, 0, 0, $i, 1, $anioActual),
                                                                );
                                                                $fechaFin = date(
                                                                    'Y-m-t',
                                                                    mktime(0, 0, 0, $i, 1, $anioActual),
                                                                );

                                                                // Verifica si el mes es el actual
                                                                $esMesActual =
                                                                    $i == date('n') && $anioActual == date('Y');

                                                                // Días del mes: si es el mes actual, usar solo los días transcurridos
                                                                $diasDelMes = $esMesActual
                                                                    ? date('j')
                                                                    : cal_days_in_month(CAL_GREGORIAN, $i, $anioActual);

                                                                foreach ($areas as $lista) {
                                                                    $suma = DB::table('registroinventarios')
                                                                        ->where('sucursal', $lista->area)
                                                                        ->whereIn('modo', ['efectivo', 'qr'])
                                                                        ->whereBetween('fecha', [
                                                                            $fechaInicio,
                                                                            $fechaFin,
                                                                        ])
                                                                        ->whereIn('motivo', ['compra', 'farmacia'])
                                                                        ->sum(DB::raw('CAST(precio AS DECIMAL(10,2))'));

                                                                    $totalMes += $suma;
                                                                }

                                                                $promedioTotalMes =
                                                                    $diasDelMes > 0
                                                                        ? round($totalMes / $diasDelMes, 2)
                                                                        : 0;
                                                            @endphp

                                                            <td><strong>{{ number_format($promedioTotalMes, 2) }}</strong>
                                                            </td>
                                                        @endfor

                                                        @php
                                                            $totalHoy = 0;
                                                            $hoy = date('Y-m-d');
                                                            foreach ($areas as $lista) {
                                                                $totalHoy += DB::table('registroinventarios')
                                                                    ->where('sucursal', $lista->area)
                                                                    ->whereIn('modo', ['efectivo', 'qr'])
                                                                    ->where('fecha', $hoy)
                                                                    ->whereIn('motivo', ['compra', 'farmacia'])
                                                                    ->sum(DB::raw('CAST(precio AS DECIMAL(10,2))'));
                                                            }
                                                        @endphp
                                                        <td><strong>{{ number_format($totalHoy, 2) }}</strong></td>
                                                        <td></td>
                                                    </tr>
                                                </tbody>
                                            </table>


                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div>
                            @php
                                $labels = [];
                                $series = [];

                                // Armar etiquetas de meses
                                for ($i = 1; $i <= $mesActual; $i++) {
                                    $labels[] = $meses_es[$i];
                                }

                                // Armar series por sucursal
                                foreach ($areas as $lista) {
                                    $data = [];
                                    for ($mes = 1; $mes <= $mesActual; $mes++) {
                                        $fechaInicio = date('Y-m-d', mktime(0, 0, 0, $mes, 1, $anioActual));
                                        $fechaFin = date('Y-m-t', mktime(0, 0, 0, $mes, 1, $anioActual));

                                        $diasDelMes =
                                            $mes == date('n')
                                                ? date('j')
                                                : cal_days_in_month(CAL_GREGORIAN, $mes, $anioActual);

                                        $sumaMes = DB::table('registroinventarios')
                                            ->where('sucursal', $lista->area)
                                            ->whereIn('modo', ['efectivo', 'qr'])
                                            ->whereBetween('fecha', [$fechaInicio, $fechaFin])
                                            ->whereIn('motivo', ['compra', 'farmacia'])
                                            ->sum(DB::raw('CAST(precio AS DECIMAL(10,2))'));

                                        $data[] = $diasDelMes > 0 ? round($sumaMes / $diasDelMes, 2) : 0;
                                    }

                                    $series[] = [
                                        'name' => $lista->area,
                                        'data' => $data,
                                    ];
                                }
                            @endphp
                            <div id="grafico-promedios" style="height: 400px;"></div>
                            <script>
                                const labels = {!! json_encode($labels) !!};
                                const series = {!! json_encode($series) !!};
                            </script>

                            <script>
                                const options = {
                                    chart: {
                                        type: 'line',
                                        height: 400
                                    },
                                    series: series,
                                    xaxis: {
                                        categories: labels
                                    },
                                    title: {
                                        text: 'Promedios diarios de ventas dividido en meses por sucursal'
                                    },
                                    stroke: {
                                        curve: 'smooth'
                                    },
                                    markers: {
                                        size: 4
                                    }
                                };

                                const chart = new ApexCharts(document.querySelector("#grafico-promedios"), options);
                                chart.render();
                            </script>

                        </div>
                    @endif
                    @if ($opcion == 4)
                        <div class="tab-pane active" id="Student-all">
                            <div class="card">
                                <div class="card-body">
                                    <div class="px-4">
                                        <div class="py-3 table-responsive ">
                                            <table
                                                class="table mb-0 table-striped table-hover table-bordered text-nowrap">
                                                <thead class="thead-dark">
                                                    <tr class="ligth">
                                                        @php
                                                            $promedios_agenda = [];
                                                            $mesActual = date('n');
                                                            $anioActual = date('Y');
                                                            $meses_es = [
                                                                1 => 'Enero',
                                                                2 => 'Febrero',
                                                                3 => 'Marzo',
                                                                4 => 'Abril',
                                                                5 => 'Mayo',
                                                                6 => 'Junio',
                                                                7 => 'Julio',
                                                                8 => 'Agosto',
                                                                9 => 'Septiembre',
                                                                10 => 'Octubre',
                                                                11 => 'Noviembre',
                                                                12 => 'Diciembre',
                                                            ];

                                                            $nombre_mes_actual = $meses_es[$mesActual];

                                                            // Inicializamos acumuladores
                                                            $total_mes_anterior = 0;
                                                            $total_agendados = 0;
                                                            $total_asistidos = 0;
                                                            $total_agendados_hoy = 0;
                                                            $total_asistidos_hoy = 0;
                                                        @endphp

                                                        <th>Sucursal</th>
                                                        @for ($i = 1; $i <= $mesActual; $i++)
                                                            <th>{{ strtoupper($meses_es[$i]) }}</th>
                                                        @endfor
                                                        {{-- <th>Pr Agen {{ $nombre_mes_actual }}</th> --}}
                                                        <th>Pr Asis {{ $nombre_mes_actual }}</th>
                                                        <th>Agen hoy</th>
                                                        <th>Asis hoy</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($areas as $lista)
                                                        @php
                                                            $mes_actual_inicio = date('Y-m-01');
                                                            $dia_actual = date('Y-m-d');

                                                            $agendados_mes = DB::table('operativos')
                                                                ->where('area', $lista->area)
                                                                ->whereBetween('fecha', [
                                                                    $mes_actual_inicio,
                                                                    $dia_actual,
                                                                ])
                                                                ->count();

                                                            $asistidos_mes = DB::table('registropagos')
                                                                ->where('sucursal', $lista->area)
                                                                ->whereBetween('fecha', [
                                                                    $mes_actual_inicio,
                                                                    $dia_actual,
                                                                ])
                                                                ->distinct('idoperativo')
                                                                ->count();

                                                            $agendados_hoy = DB::table('operativos')
                                                                ->where('area', $lista->area)
                                                                ->where('fecha', $dia_actual)
                                                                ->count();

                                                            $asistidos_hoy = DB::table('registropagos')
                                                                ->where('sucursal', $lista->area)
                                                                ->where('fecha', $dia_actual)
                                                                ->distinct('idoperativo')
                                                                ->count();

                                                            $dias_transcurridos = date('j');

                                                            $prom_agendados =
                                                                $dias_transcurridos > 0
                                                                    ? ceil($agendados_mes / $dias_transcurridos)
                                                                    : 0;
                                                            $prom_asistidos =
                                                                $dias_transcurridos > 0
                                                                    ? ceil($asistidos_mes / $dias_transcurridos)
                                                                    : 0;

                                                            // Mes anterior
                                                            $inicio_mes_anterior = date(
                                                                'Y-m-01',
                                                                strtotime('first day of last month'),
                                                            );
                                                            $fin_mes_anterior = date(
                                                                'Y-m-t',
                                                                strtotime('last day of last month'),
                                                            );
                                                            $dias_mes_anterior = date(
                                                                't',
                                                                strtotime('last day of last month'),
                                                            );

                                                            $agendados_anterior = DB::table('operativos')
                                                                ->where('area', $lista->area)
                                                                ->whereBetween('fecha', [
                                                                    $inicio_mes_anterior,
                                                                    $fin_mes_anterior,
                                                                ])
                                                                ->count();

                                                            $prom_mes_anterior =
                                                                $dias_mes_anterior > 0
                                                                    ? round($agendados_anterior / $dias_mes_anterior, 2)
                                                                    : 0;

                                                            // Acumuladores
                                                            $total_mes_anterior += $prom_mes_anterior;
                                                            $total_agendados += $prom_agendados;
                                                            $total_asistidos += $prom_asistidos;
                                                            $total_agendados_hoy += $agendados_hoy;
                                                            $total_asistidos_hoy += $asistidos_hoy;
                                                        @endphp
                                                        <tr>
                                                            <td>{{ $lista->area }}</td>
                                                            @php
                                                                $promedios = [];
                                                                for ($mes = 1; $mes <= $mesActual; $mes++) {
                                                                    $fechaInicio = date(
                                                                        'Y-m-d',
                                                                        mktime(0, 0, 0, $mes, 1, $anioActual),
                                                                    );
                                                                    $fechaFin = date(
                                                                        'Y-m-t',
                                                                        mktime(0, 0, 0, $mes, 1, $anioActual),
                                                                    );
                                                                    $hoy = date('Y-m-d');
                                                                    $esMesActual =
                                                                        $mes == date('n') && $anioActual == date('Y');

                                                                    if ($esMesActual) {
                                                                        // Usamos el número de días transcurridos hasta hoy
                                                                        $diasDelMes = date('j'); // Día del mes actual (1-31)
                                                                        $fechaFin = date('Y-m-d');
                                                                    } else {
                                                                        // Usamos el total de días del mes
                                                                        $diasDelMes = cal_days_in_month(
                                                                            CAL_GREGORIAN,
                                                                            $mes,
                                                                            $anioActual,
                                                                        );
                                                                    }
                                                                    $sumaMes = DB::table('operativos')
                                                                        ->where('area', $lista->area)
                                                                        ->whereBetween('fecha', [
                                                                            $fechaInicio,
                                                                            $fechaFin,
                                                                        ])
                                                                        ->count();

                                                                    $promedios[$mes] = round($sumaMes / $diasDelMes);
                                                                    $promedios_agenda[$mes] =
                                                                        ($promedios_agenda[$mes] ?? 0) +
                                                                        round($sumaMes / $diasDelMes);
                                                                }
                                                            @endphp

                                                            @for ($i = 1; $i <= $mesActual; $i++)
                                                                <td>{{ number_format($promedios[$i] ?? 0, 2) }}</td>
                                                            @endfor

                                                            {{-- <td>{{ $prom_agendados }}</td> --}}
                                                            <td>{{ $prom_asistidos }}</td>
                                                            <td>{{ $agendados_hoy }}</td>
                                                            <td>{{ $asistidos_hoy }}</td>
                                                        </tr>
                                                    @endforeach

                                                    {{-- Fila de totales --}}
                                                    <tr class="table-primary font-weight-bold">
                                                        <td>TOTALES</td>
                                                        @for ($i = 1; $i <= $mesActual; $i++)
                                                            <td>{{ number_format($promedios_agenda[$i] ?? 0, 2) }}</td>
                                                        @endfor
                                                        {{-- <td>{{ number_format($total_agendados, 0) }}</td> --}}
                                                        <td>{{ number_format($total_asistidos, 0) }}</td>
                                                        <td>{{ number_format($total_agendados_hoy, 0) }}</td>
                                                        <td>{{ number_format($total_asistidos_hoy, 0) }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if ($opcion == 5)
                        <div class="tab-pane active" id="Student-all">
                            <div class="card">
                                <div class="card-body">
                                    <div class="px-4">
                                        <div class="py-3 table-responsive ">
                                            <table
                                                class="table mb-0 table-striped table-hover table-bordered text-nowrap">
                                                <thead class="thead-dark">
                                                    <tr class="ligth">
                                                        <th>Sucursal</th>
                                                        <th># Reagendado</th>
                                                        <th>Nombre</th>
                                                        <th>Número</th>
                                                        <th>Fecha creado</th>
                                                        <th>Fecha no asistida</th>
                                                        <th>Acción</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($noasistidos as $item)
                                                        <tr>
                                                            <td>{{ $item->area }}</td>
                                                            <td></td>
                                                            <td>{{ $item->empresa }}</td>
                                                            <td>{{ $item->telefono }}</td>
                                                            <td>{{ $item->created_at }}</td>
                                                            <td>{{ $item->fecha }}</td>
                                                            <td>
                                                                <div class="d-flex">
                                                                    @livewire('clientes.editar-cliente', ['iduser' => $item->idempresa], key($item->idempresa))
                                                                    @livewire('operativos.load-editar-ficha', ['operativo' => $item->id], key('lazy-' . $item->id * 5))
                                                                </div>

                                                            </td>

                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            <div>
                                                {{ $noasistidos->links() }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if ($opcion == 6)
                        <div class="card">
                            <div class="mt-3 mr-2 d-flex justify-content-end">
                                <button class="btn btn-primary" onclick="copiarTotalesTabla()">
                                    <i class="fas fa-copy me-1"></i> Copiar venta de ayer
                                </button>
                            </div>

                            <div class="card-body">
                                <div class="py-3 table-responsive ">
                                    <table class="table mb-0 table-striped table-hover table-bordered text-nowrap">
                                        <thead class="thead-dark">
                                            <tr class="ligth">
                                                @php
                                                    $total_ayer_global = 0;
                                                    $total_manana_global = 0;
                                                    $total_tarde_global = 0;
                                                    $total_inventario_global = 0;
                                                    $ayer = date('Y-m-d', strtotime('yesterday'));
                                                @endphp

                                                <th>SUCURSAL</th>
                                                <th>VENTA AYER ({{ $ayer }})</th>
                                                <th>VENTA MAÑANA</th>
                                                <th>VENTA TARDE</th>
                                                <th>INGR. PRODUCTO</th>
                                            </tr>
                                        </thead>
                                        <tbody>


                                            @foreach ($areas as $lista)
                                                @php
                                                    $totalAyer = DB::table('registroinventarios')
                                                        ->where('idsucursal', $lista->id)
                                                        ->where('modo', 'ilike', '%' . $modo . '%')
                                                        ->where('fecha', $ayer)
                                                        ->where(function ($q) {
                                                            $q->where('motivo', 'compra')->orWhere(
                                                                'motivo',
                                                                'farmacia',
                                                            );
                                                        })
                                                        ->sum(DB::raw('CAST(precio AS DECIMAL(10,2))'));

                                                    $fechaFinal = date('Y-m-d', strtotime($fechaActual . ' +1 day'));

                                                    $manana = DB::table('registroinventarios')
                                                        ->where('idsucursal', $lista->id)
                                                        ->where('modo', 'ilike', '%' . $modo . '%')
                                                        ->where('created_at', '>=', $fechaInicioMes)
                                                        ->where('created_at', '<', $fechaFinal)
                                                        ->where(function ($q) {
                                                            $q->where('motivo', 'compra')->orWhere(
                                                                'motivo',
                                                                'farmacia',
                                                            );
                                                        })
                                                        ->whereTime('created_at', '>=', '06:00:00')
                                                        ->whereTime('created_at', '<=', '13:59:59')
                                                        ->sum(DB::raw('CAST(precio AS DECIMAL(10,2))'));

                                                    $tarde = DB::table('registroinventarios')
                                                        ->where('idsucursal', $lista->id)
                                                        ->where('modo', 'ilike', '%' . $modo . '%')
                                                        ->where('created_at', '>=', $fechaInicioMes)
                                                        ->where('created_at', '<', $fechaFinal)
                                                        ->where(function ($q) {
                                                            $q->where('motivo', 'compra')->orWhere(
                                                                'motivo',
                                                                'farmacia',
                                                            );
                                                        })
                                                        ->whereTime('created_at', '>=', '14:00:00')
                                                        ->whereTime('created_at', '<=', '23:59:59')
                                                        ->sum(DB::raw('CAST(precio AS DECIMAL(10,2))'));

                                                    $total_inventario = $manana + $tarde;

                                                    $total_ayer_global += $totalAyer;
                                                    $total_manana_global += $manana;
                                                    $total_tarde_global += $tarde;
                                                    $total_inventario_global += $total_inventario;
                                                @endphp
                                                <tr>
                                                    <td>{{ $lista->area }}</td>
                                                    <td>{{ number_format($totalAyer, 2, ',', '.') }}</td>
                                                    <td>{{ number_format($manana, 2, ',', '.') }}</td>
                                                    <td>{{ number_format($tarde, 2, ',', '.') }}</td>
                                                    <td>{{ number_format($total_inventario, 2, ',', '.') }}</td>
                                                </tr>
                                            @endforeach

                                            <tr class="bg-gray text-bold">
                                                <td>TOTALES</td>
                                                <td>{{ number_format($total_ayer_global, 2, ',', '.') }}</td>
                                                <td>{{ number_format($total_manana_global, 2, ',', '.') }}</td>
                                                <td>{{ number_format($total_tarde_global, 2, ',', '.') }}</td>
                                                <td>{{ number_format($total_inventario_global, 2, ',', '.') }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>


                            </div>
                        </div>

                    @endif
                    @if ($opcion == 7)
                        <div class="card">
                            <div class="card-body">
                                <div class="py-3 table-responsive ">
                                    <table class="table mb-0 table-striped table-hover table-bordered text-nowrap">
                                        <thead class="thead-dark">
                                            <tr class="ligth">
                                                @php
                                                    $total_ayer_global = 0;
                                                    $total_manana_global = 0;
                                                    $total_tarde_global = 0;
                                                    $total_agendado_global = 0;
                                                @endphp

                                                <th>SUCURSAL</th>
                                                <th>AGENDADO MAÑANA</th>
                                                <th>AGENDADO TARDE</th>
                                                <th>AGENDADO TOTAL</th>
                                            </tr>
                                        </thead>
                                        <tbody>


                                            @foreach ($areas as $lista)
                                                @php
                                                    $fechaFinal = date('Y-m-d', strtotime($fechaActual . ' +1 day'));
                                                    $manana = DB::table('operativos')
                                                        ->where('area', $lista->area)
                                                        ->where('created_at', '>=', $fechaInicioMes)
                                                        ->where('created_at', '<', $fechaFinal)
                                                        ->whereTime('created_at', '>=', '06:00:00')
                                                        ->whereTime('created_at', '<=', '13:59:59')
                                                        ->distinct('idempresa')
                                                        ->count();

                                                    $tarde = DB::table('operativos')
                                                        ->where('area', $lista->area)
                                                        ->where('created_at', '>=', $fechaInicioMes)
                                                        ->where('created_at', '<', $fechaFinal)
                                                        ->whereTime('created_at', '>=', '14:00:00')
                                                        ->whereTime('created_at', '<=', '23:59:59')
                                                        ->distinct('idempresa')
                                                        ->count();

                                                    $total_agendado = $manana + $tarde;

                                                    $total_manana_global += $manana;
                                                    $total_tarde_global += $tarde;
                                                    $total_agendado_global += $total_agendado;
                                                @endphp
                                                <tr>
                                                    <td>{{ $lista->area }}</td>
                                                    <td>{{ number_format($manana, 0, ',', '.') }}</td>
                                                    <td>{{ number_format($tarde, 0, ',', '.') }}</td>
                                                    <td>{{ number_format($total_agendado, 0, ',', '.') }}</td>
                                                </tr>
                                            @endforeach

                                            <tr class="bg-gray text-bold">
                                                <td>TOTALES</td>
                                                <td>{{ number_format($total_manana_global, 0, ',', '.') }}</td>
                                                <td>{{ number_format($total_tarde_global, 0, ',', '.') }}</td>
                                                <td>{{ number_format($total_agendado_global, 0, ',', '.') }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div>

                    @endif
                    @if ($opcion == 8)
                        <div class="card">
                            <div class="card-body">
                                <div class="py-3 table-responsive ">
                                    <table class="table mb-0 table-striped table-hover table-bordered text-nowrap">
                                        <thead class="thead-dark">
                                            <tr class="ligth">
                                                @php
                                                    $total_vendido = 0;
                                                    $total_aprobado = 0;
                                                    $total_ganado = 0;
                                                @endphp

                                                <th>PERSONAL</th>
                                                <th>TOTAL VENDIDO</th>
                                                <th>MONTO APROBADO</th>
                                                <th>MONTO GANADO (4%)</th>
                                                <th>ACCIÓN</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($personalesConVentas as $lista)
                                                <tr>
                                                    @php
                                                        $fechaActualMasUno = date(
                                                            'Y-m-d',
                                                            strtotime($fechaActual . ' +1 day'),
                                                        );

                                                        $totalVentas = DB::table('registroinventarios')
                                                            ->where('iduser', $lista->id)
                                                            ->whereIn('motivo', ['compra', 'farmacia'])
                                                            ->whereBetween('fecha', [
                                                                $fechaInicioMes,
                                                                $fechaActualMasUno,
                                                            ])
                                                            ->sum(DB::raw('CAST(precio AS DECIMAL(10,2)) '));
                                                        $total_vendido = $totalVentas + $total_vendido;
                                                        $total_aprobado = $total_aprobado + $lista->monto_aprobado;
                                                        $total_ganado = $total_ganado + $lista->monto_ganado;
                                                    @endphp
                                                    <td>{{ $lista->nombre_usuario }}</td>
                                                    <td>{{ $totalVentas }}</td>
                                                    <td>{{ number_format($lista->monto_aprobado, 2) }}</td>
                                                    <td>{{ number_format($lista->monto_ganado, 2) }}</td>
                                                    <td>
                                                        <button class="btn btn-success"
                                                            wire:click="verventas('{{ $lista->id }}')">Ver
                                                            ventas</button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            <tr>
                                                <td>TOTALES</td>
                                                <td>{{ $total_vendido }}</td>
                                                <td>{{ $total_aprobado }}</td>
                                                <td>{{ $total_ganado }}</td>
                                                <td></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if ($opcion == 10)
                        <div class="tab-pane active" id="Student-all">
                            <div class="card">
                                <div class="card-body">
                                    <div class="px-4">
                                        @php
                                            $mesActual = date('n');
                                            $anioActual = date('Y');
                                            $meses_es = [
                                                1 => 'Enero',
                                                2 => 'Febrero',
                                                3 => 'Marzo',
                                                4 => 'Abril',
                                                5 => 'Mayo',
                                                6 => 'Junio',
                                                7 => 'Julio',
                                                8 => 'Agosto',
                                                9 => 'Septiembre',
                                                10 => 'Octubre',
                                                11 => 'Noviembre',
                                                12 => 'Diciembre',
                                            ];
                                        @endphp

                                        <div class="py-3 table-responsive ">
                                            <table
                                                class="table mb-0 table-striped table-hover table-bordered text-nowrap">
                                                <thead class="thead-dark">
                                                    <tr class="ligth">
                                                        <th>SUCURSAL</th>
                                                        @for ($i = 1; $i <= $mesActual; $i++)
                                                            <th>{{ strtoupper($meses_es[$i]) }}</th>
                                                        @endfor
                                                        <th>ENGANCHE DEL DÍA</th>

                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    @foreach ($areas as $lista)
                                                        <tr>
                                                            <td>{{ $lista->area }}</td>
                                                            @php
                                                                $promedios = [];
                                                                for ($mes = 1; $mes <= $mesActual; $mes++) {
                                                                    $fechaInicio = date(
                                                                        'Y-m-d',
                                                                        mktime(0, 0, 0, $mes, 1, $anioActual),
                                                                    );
                                                                    $fechaFin = date(
                                                                        'Y-m-t',
                                                                        mktime(0, 0, 0, $mes, 1, $anioActual),
                                                                    );
                                                                    $hoy = date('Y-m-d');
                                                                    $esMesActual =
                                                                        $mes == date('n') && $anioActual == date('Y');

                                                                    if ($esMesActual) {
                                                                        // Usamos el número de días transcurridos hasta hoy
                                                                        $diasDelMes = date('j'); // Día del mes actual (1-31)
                                                                    } else {
                                                                        // Usamos el total de días del mes
                                                                        $diasDelMes = cal_days_in_month(
                                                                            CAL_GREGORIAN,
                                                                            $mes,
                                                                            $anioActual,
                                                                        );
                                                                    }
                                                                    $sumaMes = DB::table('historial_clientes')
                                                                        ->join('operativos', function ($join) {
                                                                            $join->on(
                                                                                DB::raw('operativos.id'),
                                                                                '=',
                                                                                DB::raw(
                                                                                    'CAST(historial_clientes.idoperativo AS BIGINT)',
                                                                                ),
                                                                            );
                                                                        })
                                                                        ->where('operativos.area', $lista->area)
                                                                        ->whereBetween('historial_clientes.fecha', [
                                                                            $fechaInicio,
                                                                            $fechaFin,
                                                                        ])
                                                                        ->whereNotNull(
                                                                            'historial_clientes.idcosmetologa',
                                                                        )
                                                                        ->count();

                                                                    // Ahora puedes calcular el promedio:

                                                                    $promedios[$mes] = $sumaMes / $diasDelMes;
                                                                }
                                                                $totalHoy = DB::table('historial_clientes')
                                                                    ->join('operativos', function ($join) {
                                                                        $join->on(
                                                                            DB::raw('operativos.id'),
                                                                            '=',
                                                                            DB::raw(
                                                                                'CAST(historial_clientes.idoperativo AS BIGINT)',
                                                                            ),
                                                                        );
                                                                    })
                                                                    ->where('operativos.area', $lista->area)
                                                                    ->where('historial_clientes.fecha', $hoy)
                                                                    ->whereNotNull('historial_clientes.idcosmetologa')
                                                                    ->count();
                                                            @endphp

                                                            @for ($i = 1; $i <= $mesActual; $i++)
                                                                <td>{{ number_format($promedios[$i] ?? 0, 2) }}</td>
                                                            @endfor

                                                            <td>{{ $totalHoy }}</td>

                                                        </tr>
                                                    @endforeach

                                                    <tr>
                                                        <td><strong>TOTALES</strong></td>
                                                        @for ($i = 1; $i <= $mesActual; $i++)
                                                            @php
                                                                $totalMes = 0;

                                                                // Fechas de inicio y fin del mes evaluado
                                                                $fechaInicio = date(
                                                                    'Y-m-d',
                                                                    mktime(0, 0, 0, $i, 1, $anioActual),
                                                                );
                                                                $fechaFin = date(
                                                                    'Y-m-t',
                                                                    mktime(0, 0, 0, $i, 1, $anioActual),
                                                                );

                                                                // Verifica si el mes es el actual
                                                                $esMesActual =
                                                                    $i == date('n') && $anioActual == date('Y');

                                                                // Días del mes: si es el mes actual, usar solo los días transcurridos
                                                                $diasDelMes = $esMesActual
                                                                    ? date('j')
                                                                    : cal_days_in_month(CAL_GREGORIAN, $i, $anioActual);

                                                                foreach ($areas as $lista) {
                                                                    $suma = DB::table('historial_clientes')
                                                                        ->join('operativos', function ($join) {
                                                                            $join->on(
                                                                                DB::raw('operativos.id'),
                                                                                '=',
                                                                                DB::raw(
                                                                                    'CAST(historial_clientes.idoperativo AS BIGINT)',
                                                                                ),
                                                                            );
                                                                        })
                                                                        ->where('operativos.area', $lista->area)
                                                                        ->whereBetween('historial_clientes.fecha', [
                                                                            $fechaInicio,
                                                                            $fechaFin,
                                                                        ])
                                                                        ->whereNotNull(
                                                                            'historial_clientes.idcosmetologa',
                                                                        )
                                                                        ->count();

                                                                    $totalMes += $suma;
                                                                }

                                                                $promedioTotalMes =
                                                                    $diasDelMes > 0 ? $totalMes / $diasDelMes : 0;
                                                            @endphp

                                                            <td><strong>{{ number_format($promedioTotalMes, 2) }}</strong>
                                                            </td>
                                                        @endfor

                                                        @php
                                                            $totalHoy = 0;
                                                            $hoy = date('Y-m-d');
                                                            foreach ($areas as $lista) {
                                                                $totalHoy += DB::table('historial_clientes')
                                                                    ->join('operativos', function ($join) {
                                                                        $join->on(
                                                                            DB::raw('operativos.id'),
                                                                            '=',
                                                                            DB::raw(
                                                                                'CAST(historial_clientes.idoperativo AS BIGINT)',
                                                                            ),
                                                                        );
                                                                    })
                                                                    ->where('operativos.area', $lista->area)
                                                                    ->where('historial_clientes.fecha', $hoy)
                                                                    ->whereNotNull('historial_clientes.idcosmetologa')
                                                                    ->count();
                                                            }
                                                        @endphp
                                                        <td><strong>{{ number_format($totalHoy) }}</strong></td>

                                                    </tr>
                                                </tbody>
                                            </table>


                                        </div>
                                    </div>
                                    <div class="p-6 bg-white rounded-lg shadow-md">
                                        <h3 class="mb-4 text-lg font-bold text-gray-700">Reporte de Enganches</h3>

                                        <div class="flex flex-col md:flex-row md:items-end md:space-x-4">
                                            <div class="flex flex-col mb-3 md:mb-0">
                                                <label for="fecha_desde"
                                                    class="mb-1 text-sm font-semibold text-gray-600">Desde</label>
                                                <input type="date" wire:model="fecha_desde" id="fecha_desde"
                                                    class="px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
                                            </div>

                                            <div class="flex flex-col mb-3 md:mb-0">
                                                <label for="fecha_hasta"
                                                    class="mb-1 text-sm font-semibold text-gray-600">Hasta</label>
                                                <input type="date" wire:model="fecha_hasta" id="fecha_hasta"
                                                    class="px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
                                            </div>

                                            <button wire:click="buscar" class="btn btn-success">
                                                Buscar
                                            </button>
                                            <div>

                                            </div>
                                        </div>

                                        @if ($totalFechas !== null)
                                            <div class="mt-6 text-center">
                                                <p class="text-lg font-semibold text-gray-700">
                                                    Total de enganches entre <span
                                                        class="font-bold text-blue-600">{{ $fecha_desde }}</span> y
                                                    <span class="font-bold text-blue-600">{{ $fecha_hasta }}</span>:
                                                </p>
                                                <p class="mt-2 text-3xl font-extrabold text-green-600">
                                                    {{ $totalFechas }}</p>
                                            </div>
                                        @endif
                                    </div>

                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <script>
        function copiarSoloTbodySinAcciones(idTabla) {
            const tabla = document.getElementById(idTabla);
            if (!tabla) {
                alert("No se encontró la tabla con ID: " + idTabla);
                return;
            }

            const tbody = tabla.querySelector("tbody");
            if (!tbody) {
                alert("La tabla no tiene un <tbody>.");
                return;
            }

            // Clonamos el tbody para no modificar el original
            const tbodyClonado = tbody.cloneNode(true);

            // Eliminamos la última columna (acción) de cada fila
            tbodyClonado.querySelectorAll("tr").forEach(tr => {
                const celdas = tr.querySelectorAll("td");
                if (celdas.length > 1) {
                    celdas[celdas.length - 1].remove(); // Elimina la última celda
                }
            });

            // Creamos una tabla temporal para copiar
            const tablaTemporal = document.createElement("table");
            tablaTemporal.style.border = "1px solid black";
            tablaTemporal.style.borderCollapse = "collapse";
            tablaTemporal.appendChild(tbodyClonado);

            // Estilo básico a todas las celdas para que sea visible al pegar
            tablaTemporal.querySelectorAll("td").forEach(td => {
                td.style.border = "1px solid black";
                td.style.padding = "4px";
            });

            // Convertimos la tabla en HTML
            const html = tablaTemporal.outerHTML;

            // Usamos un rango para copiar como HTML
            const blob = new Blob([html], {
                type: "text/html"
            });
            const data = [new ClipboardItem({
                "text/html": blob
            })];

            navigator.clipboard.write(data).then(() => {
                alert("Tabla copiada (sin la columna de acción).");
            }).catch(err => {
                alert("Error al copiar: " + err);
            });
        }
    </script>


    <script>
        function copiarTotalesTabla() {
            const tabla = document.getElementById("mitabla-ps");
            if (!tabla) return;

            const padRight = (text, length) => {
                return (text + " ".repeat(length)).substring(0, length);
            };

            // Obtener la fecha de ayer en formato YYYY-MM-DD
            const fechaAyer = new Date();
            fechaAyer.setDate(fechaAyer.getDate() - 1);
            const yyyy = fechaAyer.getFullYear();
            const mm = String(fechaAyer.getMonth() + 1).padStart(2, '0');
            const dd = String(fechaAyer.getDate()).padStart(2, '0');
            const fechaFormateada = `${yyyy}-${mm}-${dd}`;

            let texto = `VENTAS DIARIAS - ${fechaFormateada}\n\n`;
            texto += padRight("SUCURSAL", 25) + padRight("TOTAL", 15) + "\n";
            texto += "-".repeat(40) + "\n";

            const filas = tabla.querySelectorAll("tbody tr");

            filas.forEach((tr, index) => {
                const celdas = tr.querySelectorAll("td");
                if (celdas.length < 4) return;

                const nombreSucursal = celdas[0].innerText.trim();
                const totalFila = celdas[1].innerText.trim();

                if (index === filas.length - 1) {
                    texto += padRight("TOTALES", 25) + padRight(totalFila, 15) + "\n";
                } else {
                    texto += padRight(nombreSucursal, 25) + padRight(totalFila, 15) + "\n";
                }
            });

            navigator.clipboard.writeText(texto)
                .then(() => alert("Totales copiados al portapapeles"))
                .catch(err => console.error("Error al copiar:", err));
        }
    </script>




    <x-sm-modal wire:model.defer="ver">
        <div class="mt-4">
            <div class="mt-4 card">
                <div class="card-header">Lista de enganches

                </div>
                <div class="card-body">
                    <table id="tablaHerramientas" class="table table-striped">
                        <thead>
                            <tr>
                                <th>Cliente</th>
                                <th>Tratamiento</th>
                                <th>Costo</th>
                                <th>Fecha</th>

                            </tr>
                        </thead>
                        <tbody>
                            @if ($listaenganches)
                                @foreach ($listaenganches as $item)
                                    <tr>
                                        <td>{{ $item->nombre_usuario }}</td>
                                        <td>{{ $item->nombretratamiento }}</td>
                                        <td>{{ $item->costo }}</td>
                                        <td>{{ $item->fecha }}</td>
                                    </tr>
                                @endforeach
                            @endif

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </x-sm-modal>
    <x-modal wire:model.defer="vermisventas">
        @php
            $fechaActualMasUno = date('Y-m-d', strtotime($fechaActual . ' +2 day'));
            $fechaActualCompleta = date('Y-m-d') . ' 23:59:59';

            $ventasAgrupadas = DB::select(
                "
    SELECT
        TO_CHAR(created_at, 'YYYY-MM-DD HH24:MI') AS hora_sin_segundos,
        motivo,
        modo,
        nombreproducto,
        precio,
        created_at
    FROM registroinventarios
    WHERE motivo IN ('compra','farmacia')
      AND modo IN ('efectivo', 'qr')
      AND CAST(precio AS DECIMAL) >= 30
      AND iduser = ?
      AND created_at BETWEEN ? AND ?
    ORDER BY hora_sin_segundos desc
",
                [$responsableseleccionado, $fechaInicioMes, $fechaActualCompleta],
            );

            // Agrupamos por hora y filtramos por suma > 100
            $gruposFiltrados = collect($ventasAgrupadas)
                ->groupBy('hora_sin_segundos')
                ->filter(function ($grupo) {
                    return $grupo->sum('precio') >= 100;
                });

        @endphp
        @php
            $sumaTotalDeVentas = $gruposFiltrados
                ->flatMap(function ($grupo) {
                    return $grupo;
                })
                ->reduce(function ($carry, $item) {
                    return $carry + floatval($item->precio);
                }, 0);
        @endphp

        <div class="mt-4 alert alert-success">
            <strong>Total de todas las ventas mayores a 100 Bs: {{ number_format($sumaTotalDeVentas, 2) }} Bs</strong>
        </div>

        <div class="mt-2 alert alert-primary">
            <strong>Monto a cobrar (4%): {{ number_format($sumaTotalDeVentas * 0.04, 2) }} Bs</strong>
        </div>


        @foreach ($gruposFiltrados as $hora => $ventas)
            <div class="mb-4 card">
                <div class="card-header">
                    <strong>Venta agrupada a las: {{ $hora }} — Total:
                        {{ number_format($ventas->sum('precio'), 2) }} Bs</strong>
                </div>
                <div class="p-0 card-body">
                    <table class="table m-0 table-bordered table-sm">
                        <thead class="table-light">
                            <tr>
                                <th>Producto</th>
                                <th>Modo</th>
                                <th>Motivo</th>
                                <th>Precio</th>
                                <th>Hora exacta</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($ventas as $venta)
                                <tr>
                                    <td>{{ $venta->nombreproducto }}</td>
                                    <td>{{ ucfirst($venta->modo) }}</td>
                                    <td>{{ ucfirst($venta->motivo) }}</td>
                                    <td>{{ number_format($venta->precio, 2) }} Bs</td>
                                    <td>{{ $venta->created_at }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach

    </x-modal>
</div>
