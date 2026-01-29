<div>
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Reportes: </h3>

                <div class="card-options">
                    <a href="#" class="card-options-collapse" data-toggle="card-collapse"><i
                            class="fe fe-chevron-up"></i></a>
                    <a href="#" class="card-options-fullscreen" data-toggle="card-fullscreen"><i
                            class="fe fe-maximize"></i></a>
                    <a href="#" class="card-options-remove" data-toggle="card-remove"><i class="fe fe-x"></i></a>
                </div>
            </div>
            <div class="card-body">
                <div>
                    <div class="" style="display: flex; font-size: 10PX;">
                        <label for="fecha-inicio mr-2">Desde:</label>
                        <input style="font-size: 10px;" class="mr-2" type="date" id="fecha-inicio"
                            wire:model="fechaInicioMes">
                        <label for="fecha-actual mr-2">Hasta:</label>
                        <input style="font-size: 10PX;" class="mr2" type="date" id="fecha-actual"
                            wire:model="fechaActual">
                        <div style="margin-left: 1%; font-size: 10px;">
                            <label for="fecha-actual">Responsable:</label>
                            <select class="form-control" wire:model="responsableseleccionado" style="font-size: 10px;">
                                <option value="{{ $responsableseleccionado }}">{{ $responsableseleccionado }}</option>
                                @if (in_array(Auth::user()->rol, ['Administrador', 'Asist. Administrativo', 'Recursos Humanos']))
                                    @foreach ($responsables as $lista)
                                        <option value="{{ $lista->name }}">{{ $lista->name }}</option>
                                    @endforeach
                                @endif


                            </select>
                        </div>
                    </div>

                </div>
                <div class="table-responsive">
                    <h1 style="font-size: 24px; margin-top:2%;"><strong>Atención al Cliente</strong></h1>
                    @php
                        $atendidos = DB::table('registropagos')
                            ->where('responsable', $responsableseleccionado)
                            ->whereBetween('fecha', [$fechaInicioMes, $fechaActual])
                            ->count('iduser');
                    @endphp
                    <h1 class="mt-2">Total atendidos: {{ $atendidos }}</h1>
                    <h1 style="font-size: 24px; margin-top:2%;"><strong>Metricas de desempeño</strong></h1>
                    <table id="mitablaregistros" class="table table-striped" role="grid" data-bs-toggle="data-table">
                        <thead>
                            <tr>
                                <th>LLAMADAS REALIZADAS</th>
                                <th>REMARKETING</th>
                                <th>TOTAL LLAMADAS</th>
                                <th>LLAMADAS AGENDADAS</th>
                                <th>AGENDADOS CREADOS</th>
                                <th>TOTAL AGENDADOS</th>
                                <th>VENTAS > 100 Bs.</th>
                                <th>ACCION</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                @php
                                    $misllamada = DB::table('registrollamadas')
                                        ->where('responsable', $responsableseleccionado)
                                        ->where('sucursal', '0')
                                        ->whereBetween('fecha', [$fechaInicioMes, $fechaActual])
                                        ->count();
                                    $remarketing = DB::table('registrollamadas')
                                        ->where('responsable', $responsableseleccionado)
                                        ->where('sucursal', '1')
                                        ->whereBetween('fecha', [$fechaInicioMes, $fechaActual])
                                        ->count();
                                    $misagendados = DB::table('calls')
                                        ->where('responsable', $responsableseleccionado)
                                        ->where('estado', '!=', 'llamadas')
                                        ->whereBetween('fecha', [$fechaInicioMes, $fechaActual])
                                        ->count();
                                    $fechaInicioMes = date('Y-m-d', strtotime($fechaInicioMes));
                                    $fechaActual = date('Y-m-d', strtotime($fechaActual));
                                    $miscitas = DB::table('operativos')
                                        ->where('responsable', $responsableseleccionado)
                                        ->whereDate('created_at', '>=', $fechaInicioMes)
                                        ->whereDate('created_at', '<=', $fechaActual)
                                        ->count();
                                    $iduser = DB::table('users')->where('name', $responsableseleccionado)->value('id');
                                    $totalVentas =
                                        DB::select(
                                            "
    SELECT SUM(sub.total_venta) as suma_final
    FROM (
        SELECT TO_CHAR(created_at, 'YYYY-MM-DD HH24:MI') as hora_sin_segundos,
               SUM(CAST(precio AS DECIMAL)) AS total_venta
        FROM registroinventarios
        WHERE motivo IN ('compra','farmacia')
          AND modo IN ('efectivo', 'qr')
          AND CAST(precio AS DECIMAL) >= 30
          AND iduser = ?
          AND created_at BETWEEN ? AND ?
        GROUP BY hora_sin_segundos
    ) sub
    WHERE sub.total_venta >= 100
",
                                            [$iduser, $fechaInicioMes, $fechaActual],
                                        )[0]->suma_final ?? 0;

                                @endphp
                                <td>{{ $misllamada }}</td>
                                <td>{{ $remarketing }}</td>
                                <td>{{ $remarketing + $misllamada }}</td>
                                <td>{{ $misagendados }}</td>
                                <td>{{ $miscitas }}</td>
                                <td>{{ $miscitas + $misagendados }}</td>
                                <td>{{ $totalVentas }}</td>
                                <td><button class="btn btn-success" wire:click="$set('vermisventas','true')">Ver mis
                                        ventas</button></td>
                            </tr>


                        </tbody>
                    </table>

                    <h1 style="font-size: 24px; margin-top:2%;"><strong>Manejo de caja registradora y pagos:</strong>
                    </h1>
                    <table id="mitablaregistros" class="table table-striped" role="grid" data-bs-toggle="data-table">
                        <thead>
                            <tr>
                                <th>AGENDADOS QR</th>
                                <th>AGENDADOS EFECTIVO</th>
                                <th>PRODUCTOS QR</th>
                                <th>PRODUCTOS EFECTIVO</th>
                                <th>TOTAL PRODUCTOS</th>
                                <th>TOTAL AGENDADOS Y PRODUCTOS</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                @php
                                    $suma = 0;
                                    $resultados_efectivo = DB::table('users as u')
                                        ->select(
                                            'u.id as id_usuario',
                                            'u.name as nombre_usuario',
                                            DB::raw('SUM(CAST(r.precio AS DECIMAL(10, 2))) as total_ingreso'),
                                        )
                                        ->join('registroinventarios as r', 'u.id', '=', 'r.iduser')
                                        ->where('u.name', $responsableseleccionado)
                                        ->whereIn('r.motivo', ['compra', 'farmacia'])
                                        ->where('r.modo', 'ilike', '%efectivo%')
                                        ->whereBetween('r.fecha', [$this->fechaInicioMes, $this->fechaActual])
                                        ->groupBy('u.id', 'u.name')
                                        ->orderBy('total_ingreso', 'desc')
                                        ->get();
                                    $resultados_qr = DB::table('users as u')
                                        ->select(
                                            'u.id as id_usuario',
                                            'u.name as nombre_usuario',
                                            DB::raw('SUM(CAST(r.precio AS DECIMAL(10, 2))) as total_ingreso'),
                                        )
                                        ->join('registroinventarios as r', 'u.id', '=', 'r.iduser')
                                        ->where('u.name', $responsableseleccionado)
                                        ->whereIn('r.motivo', ['compra', 'farmacia'])
                                        ->where('r.modo', 'ilike', '%Qr%')
                                        ->whereBetween('r.fecha', [$this->fechaInicioMes, $this->fechaActual])
                                        ->groupBy('u.id', 'u.name')
                                        ->orderBy('total_ingreso', 'desc')
                                        ->get();
                                    $agendados_efectivo = DB::table('users as u')
                                        ->select(
                                            'u.id as id_usuario',
                                            'u.name as nombre_usuario',
                                            DB::raw('SUM(CAST(r.monto AS DECIMAL(10, 2))) as total_ingreso'),
                                        )
                                        ->join('registropagos as r', 'u.id', '=', 'r.iduser')
                                        ->where('u.name', $responsableseleccionado)
                                        ->where('r.modo', 'ilike', '%efectivo%')
                                        ->whereBetween('r.fecha', [$this->fechaInicioMes, $this->fechaActual])
                                        ->groupBy('u.id', 'u.name')
                                        ->orderBy('total_ingreso', 'desc')
                                        ->get();
                                    $agendados_qr = DB::table('users as u')
                                        ->select(
                                            'u.id as id_usuario',
                                            'u.name as nombre_usuario',
                                            DB::raw('SUM(CAST(r.monto AS DECIMAL(10, 2))) as total_ingreso'),
                                        )
                                        ->join('registropagos as r', 'u.id', '=', 'r.iduser')
                                        ->where('u.name', $responsableseleccionado)
                                        ->where('r.modo', 'ilike', '%qr%')
                                        ->whereBetween('r.fecha', [$this->fechaInicioMes, $this->fechaActual])
                                        ->groupBy('u.id', 'u.name')
                                        ->orderBy('total_ingreso', 'desc')
                                        ->get();

                                @endphp
                                @foreach ($agendados_qr as $item)
                                    @php
                                        $suma = $suma + $item->total_ingreso;
                                    @endphp
                                    <td>
                                        {{ $item->total_ingreso }}
                                    </td>
                                @endforeach
                                @foreach ($agendados_efectivo as $item)
                                    @php
                                        $suma = $suma + $item->total_ingreso;
                                    @endphp
                                    <td>
                                        {{ $item->total_ingreso }}
                                    </td>
                                @endforeach
                                @foreach ($resultados_efectivo as $item)
                                    @php
                                        $suma = $suma + $item->total_ingreso;
                                    @endphp
                                    <td>
                                        {{ $item->total_ingreso }}
                                    </td>
                                @endforeach

                                @foreach ($resultados_qr as $item)
                                    @php
                                        $suma = $suma + $item->total_ingreso;
                                    @endphp
                                    <td>
                                        {{ $item->total_ingreso }}
                                    </td>
                                @endforeach
                                <td></td>
                                <td>{{ $suma }}</td>
                            </tr>
                            <tr>
                                <td>EGRESOS</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                @php
                                    $egreso = DB::table('gastos')
                                        ->where('nameuser', $responsableseleccionado)
                                        ->whereBetween('fechainicio', [$fechaInicioMes, $fechaActual])
                                        ->sum('cantidad');
                                @endphp
                                <td>{{ $egreso }}</td>
                            </tr>


                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <x-modal wire:model.defer="vermisventas">
        @php

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
    ORDER BY hora_sin_segundos
",
                [$iduser, $fechaInicioMes, $fechaActual],
            );

            // Agrupamos por hora y filtramos por suma > 100
            $gruposFiltrados = collect($ventasAgrupadas)
                ->groupBy('hora_sin_segundos')
                ->filter(function ($grupo) {
                    return $grupo->sum('precio') > 100;
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
