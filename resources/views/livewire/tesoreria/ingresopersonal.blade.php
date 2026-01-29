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
        $cadena = 0;
        $suma_gasto_externo = 0;
        $ingreso_externo = DB::table('pagos')
            ->where('fechapagado', '<', $fechaActual)
            ->where('fechapagado', '>', $fechaInicioMes)
            ->sum('cantidad');

        $total_gastos = DB::table('gastos')
            ->where('fechainicio', '<', $fechaActual)
            ->where('fechainicio', '>', $fechaInicioMes)
            ->where('area', 'BBC')
            ->sum('cantidad');

        $total_pagados = DB::table('pagos')
            ->where('fechapagado', '<', $fechaActual)
            ->where('fechapagado', '>', $fechaInicioMes)
            ->sum('cantidad');

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
    @foreach ($areas as $item)
        @php
            if ($item->area != 'BBC') {
                $obtenido_area = DB::table('pagos')
                    ->where('fechapagado', '>', $fechaInicioMes)
                    ->where('fechapagado', '<', $fechaActual)
                    ->where('area', $item->area)
                    ->sum('cantidad');
                $gastos_suma = DB::table('gastos')
                    ->where('fechainicio', '>', $fechaInicioMes)
                    ->where('fechainicio', '<', $fechaActual)
                    ->where('area', $item->area)
                    ->sum('cantidad');
                $suma_gasto_externo = $suma_gasto_externo + $gastos_suma;
                $cadena = 1 - $item->porcentaje / 100;
                $restante = $restante + ($obtenido_area - $gastos_suma) * $cadena;
            }
        @endphp
        @php
            $monto_repartir = $restante - $total_gastos;
        @endphp
    @endforeach
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <div class="header-title">
                {{-- <h4 class="card-title">Ingreso porcentual de admnistrativos:</h4> --}}
            </div>
        </div>
        <div class="card-body">
            <div>
                <label for="fecha-inicio">Desde:</label>
                <input type="date" id="fecha-inicio" wire:model.defer="fechaInicioMes">

                <label for="fecha-actual">Hasta:</label>
                <input type="date" id="fecha-actual" wire:model.defer="fechaActual">
            </div>
            <span>
                <div class="row flex flex-row justify-center">
                    <div class="col-lg-3 col-md-6">
                        <div class="card border-bottom border-4 border-0 border-primary">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h4 class="counter">{{ $ingreso_externo }}Bs.</h4>
                                    </div>
                                    <div>
                                        <span>Ingreso externo</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="card border-bottom border-4 border-0 border-warning">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h4 class="counter">{{ $suma_gasto_externo }} Bs.</h4>
                                    </div>
                                    <div>
                                        <span>Gastos operativos</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="card border-bottom border-4 border-0 border-info">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h4>{{ $restante }} Bs.</h4>
                                    </div>
                                    <div>
                                        <span>Ingreso interno</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <div class="card border-bottom border-4 border-0 border-danger">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h4>{{ $total_gastos }} Bs.</h4>
                                    </div>
                                    <div>
                                        <span>Gastos internos</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </span>
            <div class="card-header d-flex">
            </div>
            <div class="card-body">
                <h4 class="card-title">Ingreso porcentual de admnistrativos:</h4>
                <div class="table-responsive">
                    <table id="user-list-table" class="table table-striped" role="grid" data-bs-toggle="data-table">
                        <thead>
                            <tr class="ligth">
                                <th>NOMBRE</th>
                                <th>%</th>
                                <th>Gastos en %</th>
                                <th>Ganancia</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $suma_misgastos = 0;
                                $suma_misganancias = 0;
                            @endphp
                            @foreach ($usersl as $lista)
                                @php
                                    $mis_gastos = $total_gastos * ($lista->porcentaje / 100);
                                    $mi_ganancia = $monto_repartir * ($lista->porcentaje / 100);
                                    $suma_misgastos = $suma_misgastos + $mis_gastos;
                                    $suma_misganancias = $suma_misganancias + $mi_ganancia;

                                @endphp
                                <tr>
                                    <td>{{ $lista->name }}</td>
                                    <td>{{ $lista->porcentaje }}%</td>
                                    <td>{{ $mis_gastos }}</td>
                                    <td>{{ $mi_ganancia }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tr class="bg-gray">
                            <td style="color:white;">Total</td>
                            <td style="color:white;">100%</td>
                            <td style="color:white;">{{ $suma_misgastos }}</td>
                            <td style="color:white;">{{ $suma_misganancias }}</td>
                        </tr>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>
