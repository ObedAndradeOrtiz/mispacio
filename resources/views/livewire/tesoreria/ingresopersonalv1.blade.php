<div>
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <div class="header-title">
                <h4 class="card-title">Ingreso porcentual</h4>
            </div>
        </div>
        @php
            $restante = 0;
            $obtenido_area;
            $gastos_suma;
            $total_gastos = DB::table('gastos')
                ->where('fechainicio', '<', $fechaActual)
                ->where('fechainicio', '>', $fechaInicioMes)
                ->where('area', 'BBC')
                ->sum('cantidad');
        @endphp
        @foreach ($areas as $item)
            @php
                if ($item->area != 'BBC') {
                    $obtenido_area = DB::table('pagos')
                        ->where('fechainicio', '<', $fechaActual)
                        ->where('fechainicio', '>', $fechaInicioMes)
                        ->where('area', $item->area)
                        ->sum('cantidad');
                    $gastos_suma = DB::table('gastos')
                        ->where('fechainicio', '<', $fechaActual)
                        ->where('fechainicio', '>', $fechaInicioMes)
                        ->where('area', $item->area)
                        ->sum('cantidad');
                    $gastos_total = $gastos_total + $gastos_suma;
                    $restante = ($restante + ($obtenido_area - $gastos_suma)) * (1 - $item->porcentaje / 100);
                }
            @endphp
        @endforeach
        <div class="card-body">
            <div>
                <label for="fecha-inicio">Desde:</label>
                <input type="date" id="fecha-inicio" wire:model.defer="fechaInicioMes">

                <label for="fecha-actual">Hasta:</label>
                <input type="date" id="fecha-actual" wire:model.defer="fechaActual">
            </div>

            @php
                $total_pagados = DB::table('pagos')
                    ->where('fechapagado', '<', $fechaActual)
                    ->where('fechapagado', '>', $fechaInicioMes)
                    ->sum('cantidad');

            @endphp
            <span>

                @php
                    $total_no_pagado = DB::table('pagos')
                        ->where('estado', 'Activo')
                        ->where('fechainicio', '<', $fechaActual)
                        ->where('fechainicio', '>', $fechaInicioMes)
                        ->sum('cantidad');
                    $total_si_pagado = DB::table('pagos')
                        ->where('estado', 'Inactivo')
                        ->where('fechapagado', '<', $fechaActual)
                        ->where('fechapagado', '>', $fechaInicioMes)
                        ->sum('cantidad');
                @endphp
                <h2 class="counter mb-3 mt-5">Total ingreso Externo: {{ $total_si_pagado }} Bs.</h2>
                <h2 class="counter mb-3 mt-5">Total ingreso interno: {{ $restante }} Bs.</h2>
                <h2 class="counter mb-3 mt-5">Total gastos: {{ $total_gastos }} Bs.</h2>
            </span>
            <span>
                @php

                @endphp

            </span>
            <div class="card-header d-flex">


            </div>

            <div class="card-body">

                <div class="table-responsive">

                    <table id="user-list-table" class="table table-striped" role="grid" data-bs-toggle="data-table">
                        <thead>
                            <tr class="ligth">
                                <th>NOMBRE</th>
                                <th>%</th>
                                <th>Gastos en %</th>
                                <th>Ingreso</th>
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

                            @foreach ($usersl as $lista)
                                @php

                                    $mis_gastos = $total_gastos * ($lista->porcentaje / 100);
                                    $mi_ganancia = $restante * ($lista->porcentaje / 100) - $total_gastos;
                                @endphp
                                <tr>
                                    <td>{{ $lista->name }}</td>
                                    <td>{{ $lista->porcentaje }}%</td>
                                    <td>{{ $mis_gastos }}</td>
                                    <td>{{ $mi_ganancia }}</td>
                                </tr>

                                {{-- <tr>
                                    <td>{{ $lista->area }}</td>
                                    @php
                                        if ($modo != 'Todos') {
                                            $total_monto = DB::table('pagos')
                                                ->where('pertence', $lista->area)
                                                ->where('estado', 'Inactivo')
                                                ->where('modo', $modo)
                                                ->where('fechapagado', '<', $fechaActual)
                                                ->where('fechapagado', '>', $fechaInicioMes)
                                                ->sum('cantidad');
                                        } else {
                                            $total_monto = DB::table('pagos')
                                                ->where('pertence', $lista->area)
                                                ->where('estado', 'Inactivo')
                                                ->where('fechapagado', '<', $fechaActual)
                                                ->where('fechapagado', '>', $fechaInicioMes)
                                                ->sum('cantidad');
                                        }
                                    @endphp
                                    <td>{{ $total_monto }}</td>
                                    <td>{{ $modo }}</td>
                                </tr> --}}
                            @endforeach
                            {{-- <tr class="bg-gray">
                                <td style="color: white">TOTALES</td>
                                @php
                                    if ($modo != 'Todos') {
                                        $total_monto = DB::table('pagos')
                                            ->where('estado', 'Inactivo')
                                            ->where('modo', $modo)
                                            ->where('fechapagado', '<', $fechaActual)
                                            ->where('fechapagado', '>', $fechaInicioMes)
                                            ->sum('cantidad');
                                    } else {
                                        $total_monto = DB::table('pagos')
                                            ->where('estado', 'Inactivo')
                                            ->where('fechapagado', '<', $fechaActual)
                                            ->where('fechapagado', '>', $fechaInicioMes)
                                            ->sum('cantidad');
                                    }

                                @endphp
                                <td style="color: white">{{ $total_monto }}</td>
                                <td style="color: white">{{ $modo }}</td>
                            </tr> --}}
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>
