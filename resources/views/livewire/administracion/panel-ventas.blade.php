<div>
    <!-- Contenedor del Tab -->
    <div class="p-0 container-fluid">
        <ul class="nav nav-tabs w-100" id="miTab" role="tablist" style="background-color: #f8f9fa;">
            <li class="nav-item" role="presentation">
                <button class="nav-link active d-flex align-items-center justify-content-center" id="ventas-tab"
                    data-bs-toggle="tab" data-bs-target="#ventas" type="button" role="tab" aria-controls="ventas"
                    aria-selected="true">
                    <i class="bi bi-currency-dollar me-2"></i> Ventas
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link d-flex align-items-center justify-content-center" id="agendados-tab"
                    data-bs-toggle="tab" data-bs-target="#agendados" type="button" role="tab"
                    aria-controls="agendados" aria-selected="false">
                    <i class="bi bi-calendar-check me-2"></i> Agendados
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link d-flex align-items-center justify-content-center" id="enganches-tab"
                    data-bs-toggle="tab" data-bs-target="#enganches" type="button" role="tab"
                    aria-controls="enganches" aria-selected="false">
                    <i class="bi bi-hand-thumbs-up me-2"></i> Enganches
                </button>
            </li>
        </ul>

        <div class="p-3 border tab-content w-100 border-top-0" id="miTabContent" style="background-color: #ffffff;">
            <div class="tab-pane fade show active" id="ventas" role="tabpanel" aria-labelledby="ventas-tab">
                <div class="border-0 shadow-sm card">

                   
                    <div class="card-body">

                        {{-- ==================== Selector de Mes ==================== --}}
                        <form method="GET" class="mb-4 text-center">
                            @php
                                setlocale(LC_TIME, 'es_ES.UTF-8', 'es_ES', 'Spanish_Spain', 'es');
                                $mesSeleccionado = request('mes') ?? date('m');
                                $anio = date('Y');
                            @endphp
                            <label for="mes" class="fw-bold me-2">Seleccionar mes:</label>
                            <select name="mes" id="mes" class="w-auto form-select d-inline-block"
                                onchange="this.form.submit()">
                                @for ($m = 1; $m <= 12; $m++)
                                    @php
                                        $nombreMes = ucfirst(strftime('%B', mktime(0, 0, 0, $m, 1)));
                                    @endphp
                                    <option value="{{ sprintf('%02d', $m) }}"
                                        {{ $mesSeleccionado == sprintf('%02d', $m) ? 'selected' : '' }}>
                                        {{ $nombreMes }}
                                    </option>
                                @endfor
                            </select>
                        </form>

                        {{-- ==================== Ventas por Sucursal ==================== --}}
                        <h4 class="mb-3 text-center fw-bold">
                            <i class="bi bi-currency-dollar me-2"></i> Ventas por Sucursal
                            ({{ ucfirst(strftime('%B', mktime(0, 0, 0, $mesSeleccionado, 1))) }})
                        </h4>

                        @php
                            // Preparar fechas y acumuladores
                            $diasDelMes = cal_days_in_month(CAL_GREGORIAN, $mesSeleccionado, $anio);
                            $diaActual = $mesSeleccionado == date('m') ? date('d') : $diasDelMes;
                            $fechas = [];
                            for ($dia = 1; $dia <= $diaActual; $dia++) {
                                $fechas[] = sprintf('%04d-%02d-%02d', $anio, $mesSeleccionado, $dia);
                            }
                            $promediosPorDia = array_fill(1, $diaActual, 0);
                            $sumaTotalPorDia = array_fill(1, $diaActual, 0);
                            $conteoSucursales = 0;
                        @endphp

                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-bordered text-nowrap">
                                <thead class="table-dark">
                                    <tr>
                                        <th>SUCURSAL</th>
                                        @foreach ($fechas as $fecha)
                                            <th>{{ date('d', strtotime($fecha)) }}</th>
                                        @endforeach
                                        <th>PROMEDIO</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($areas as $item)
                                        @if ($item->area !== 'ALMACEN FEXPO')
                                            @php
                                                $conteoSucursales++;
                                                $totalSucursal = 0;
                                            @endphp
                                            <tr>
                                                <td>{{ $item->area }}</td>
                                                @foreach ($fechas as $index => $fecha)
                                                    @php
                                                        $totalVentas = DB::table('registroinventarios')
                                                            ->where('sucursal', $item->area)
                                                            ->whereIn('motivo', ['compra', 'farmacia'])
                                                            ->whereDate('fecha', $fecha)
                                                            ->sum(DB::raw('CAST(precio AS DECIMAL(10,2))'));
                                                        $totalSucursal += $totalVentas;
                                                        $promediosPorDia[$index + 1] += $totalVentas;
                                                        $sumaTotalPorDia[$index + 1] += $totalVentas;
                                                    @endphp
                                                    <td>{{ number_format($totalVentas, 2) }}</td>
                                                @endforeach
                                                @php
                                                    $promedioSucursal =
                                                        $diaActual > 0 ? $totalSucursal / $diaActual : 0;
                                                @endphp
                                                <td class="fw-bold bg-light">{{ number_format($promedioSucursal, 2) }}
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach

                                    {{-- Promedio general por día --}}
                                    <tr class="text-white fw-bold bg-secondary">
                                        <td>Promedio General</td>
                                        @foreach ($promediosPorDia as $valor)
                                            @php
                                                $promedioDia = $conteoSucursales > 0 ? $valor / $conteoSucursales : 0;
                                            @endphp
                                            <td>{{ number_format($promedioDia, 2) }}</td>
                                        @endforeach
                                        @php
                                            $promedioGeneral =
                                                $conteoSucursales > 0
                                                    ? array_sum($promediosPorDia) / ($diaActual * $conteoSucursales)
                                                    : 0;
                                        @endphp
                                        <td>{{ number_format($promedioGeneral, 2) }}</td>
                                    </tr>

                                    {{-- Total diario y promedio mensual --}}
                                    <tr class="fw-bold bg-light">
                                        <td>Total Diario</td>
                                        @foreach ($sumaTotalPorDia as $totalDia)
                                            <td>{{ number_format($totalDia, 2) }}</td>
                                        @endforeach
                                        @php
                                            $promedioSumaMensual =
                                                $diaActual > 0 ? array_sum($sumaTotalPorDia) / $diaActual : 0;
                                        @endphp
                                        <td>{{ number_format($promedioSumaMensual, 2) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="table-responsive">

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

                            <table class="table table-striped table-hover table-bordered text-nowrap">
                                <thead class="table-dark">
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
                                        @if ($lista->area !== 'ALMACEN FEXPO')
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
                                                        $esMesActual = $mes == date('n') && $anioActual == date('Y');

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
                                                            ->whereBetween('fecha', [$fechaInicio, $fechaFin])
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
                                                    $diferencia = $ventaHoy - ($promedios[$mesAnterior] ?? 0);
                                                @endphp

                                                @for ($i = 1; $i <= $mesActual; $i++)
                                                    <td>{{ number_format($promedios[$i] ?? 0, 2) }}</td>
                                                @endfor

                                                <td>{{ number_format($ventaHoy, 2) }}</td>
                                                <td>{{ number_format($diferencia, 2) }}</td>
                                            </tr>
                                        @endif
                                    @endforeach

                                    <tr>
                                        <td><strong>TOTALES</strong></td>
                                        @for ($i = 1; $i <= $mesActual; $i++)
                                            @php
                                                $totalMes = 0;

                                                // Fechas de inicio y fin del mes evaluado
                                                $fechaInicio = date('Y-m-d', mktime(0, 0, 0, $i, 1, $anioActual));
                                                $fechaFin = date('Y-m-t', mktime(0, 0, 0, $i, 1, $anioActual));

                                                // Verifica si el mes es el actual
                                                $esMesActual = $i == date('n') && $anioActual == date('Y');

                                                // Días del mes: si es el mes actual, usar solo los días transcurridos
                                                $diasDelMes = $esMesActual
                                                    ? date('j')
                                                    : cal_days_in_month(CAL_GREGORIAN, $i, $anioActual);

                                                foreach ($areas as $lista) {
                                                    $suma = DB::table('registroinventarios')
                                                        ->where('sucursal', $lista->area)
                                                        ->whereIn('modo', ['efectivo', 'qr'])
                                                        ->whereBetween('fecha', [$fechaInicio, $fechaFin])
                                                        ->whereIn('motivo', ['compra', 'farmacia'])
                                                        ->sum(DB::raw('CAST(precio AS DECIMAL(10,2))'));

                                                    $totalMes += $suma;
                                                }

                                                $promedioTotalMes =
                                                    $diasDelMes > 0 ? round($totalMes / $diasDelMes, 2) : 0;
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
                        <div class="table-responsive">


                            <table class="table table-striped table-hover table-bordered text-nowrap">
                                <thead class="table-dark">

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
                                        <th>VENTAS POR LA MAÑANA</th>
                                        <th>VENTAS LAS TARDE</th>
                                        <th>INGR. PRODUCTO</th>
                                    </tr>
                                </thead>
                                <tbody>


                                    @foreach ($areas as $lista)
                                        @if ($lista->area !== 'ALMACEN FEXPO')
                                            @php
                                                $totalAyer = DB::table('registroinventarios')
                                                    ->where('idsucursal', $lista->id)
                                                    ->where('modo', 'ilike', '%' . $modo . '%')
                                                    ->where('fecha', $ayer)
                                                    ->where(function ($q) {
                                                        $q->where('motivo', 'compra')->orWhere('motivo', 'farmacia');
                                                    })
                                                    ->sum(DB::raw('CAST(precio AS DECIMAL(10,2))'));

                                                $fechaFinal = date('Y-m-d', strtotime($fechaActual . ' +1 day'));

                                                $manana = DB::table('registroinventarios')
                                                    ->where('idsucursal', $lista->id)
                                                    ->where('modo', 'ilike', '%' . $modo . '%')
                                                    ->where('created_at', '>=', $fechaInicioMes)
                                                    ->where('created_at', '<', $fechaFinal)
                                                    ->where(function ($q) {
                                                        $q->where('motivo', 'compra')->orWhere('motivo', 'farmacia');
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
                                                        $q->where('motivo', 'compra')->orWhere('motivo', 'farmacia');
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
                                        @endif
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



                        {{-- ==================== Mejores Vendedoras ==================== --}}
                        @php
                            use Illuminate\Support\Facades\DB;

                            // Mes y año actuales
                            $mesActual = $mesSeleccionado;
                            $anioActual = $anio;
                            $fechaInicioActual = sprintf('%04d-%02d-01', $anioActual, $mesActual);
                            $fechaFinActual = sprintf(
                                '%04d-%02d-%02d',
                                $anioActual,
                                $mesActual,
                                cal_days_in_month(CAL_GREGORIAN, $mesActual, $anioActual),
                            );

                            // Ventas actuales por vendedoras
                            $mejoresVendedoras = DB::table('users as u')
                                ->select(
                                    'u.id as id_usuario',
                                    'u.name as nombre_usuario',
                                    DB::raw('SUM(CAST(r.precio AS DECIMAL(10,2))) as total_ingreso'),
                                )
                                ->join('registroinventarios as r', 'u.id', '=', 'r.iduser')
                                ->where('u.rol', '!=', 'Cliente')
                                ->whereIn('r.motivo', ['compra', 'farmacia'])
                                ->whereBetween('r.fecha', [$fechaInicioActual, $fechaFinActual])
                                ->groupBy('u.id', 'u.name')
                                ->orderByDesc('total_ingreso')
                                ->get();

                            // Mes anterior
                            $mesAnterior = $mesActual - 1;
                            $anioAnterior = $anioActual;
                            if ($mesAnterior < 1) {
                                $mesAnterior = 12;
                                $anioAnterior--;
                            }
                            $fechaInicioAnterior = sprintf('%04d-%02d-01', $anioAnterior, $mesAnterior);
                            $fechaFinAnterior = sprintf(
                                '%04d-%02d-%02d',
                                $anioAnterior,
                                $mesAnterior,
                                cal_days_in_month(CAL_GREGORIAN, $mesAnterior, $anioAnterior),
                            );

                            // Mejor vendedora mes anterior (meta)
                            $metaAnterior = DB::table('users as u')
                                ->select(DB::raw('MAX(total_mes) as total'))
                                ->from(
                                    DB::raw("(SELECT u.id, SUM(CAST(r.precio AS DECIMAL(10,2))) as total_mes
                                            FROM users u
                                            JOIN registroinventarios r ON u.id = r.iduser
                                            WHERE u.rol != 'Cliente' AND r.motivo IN ('compra','farmacia')
                                            AND r.fecha BETWEEN '$fechaInicioAnterior' AND '$fechaFinAnterior'
                                            GROUP BY u.id) as sub"),
                                )
                                ->value('total');
                            $metaAnterior = $metaAnterior ?? 0; // Si no hay ventas
                        @endphp

                        <h4 class="mt-5 mb-3 text-center fw-bold">
                            <i class="bi bi-person-check me-2"></i> Mejores Vendedoras -
                            {{ ucfirst(strftime('%B', mktime(0, 0, 0, $mesActual, 1))) }} {{ $anioActual }}
                        </h4>

                        <div class="table-responsive">
                            <table class="table align-middle table-striped table-hover table-bordered text-nowrap">
                                <thead class="table-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Vendedora</th>
                                        <th>Total Ventas (Bs.)</th>

                                        <th>% Cumplimiento de: {{ number_format($metaAnterior, 2) }}</th>
                                        <th>Accion</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($mejoresVendedoras as $index => $vendedora)
                                        @php
                                            $porcentaje =
                                                $metaAnterior > 0
                                                    ? ($vendedora->total_ingreso / $metaAnterior) * 100
                                                    : 0;
                                        @endphp
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $vendedora->nombre_usuario }}</td>
                                            <td>{{ number_format($vendedora->total_ingreso, 2) }}</td>

                                            <td style="min-width:200px;">
                                                <div class="progress" style="height: 20px;">
                                                    <div class="progress-bar bg-success" role="progressbar"
                                                        style="width: {{ min($porcentaje, 100) }}%"
                                                        aria-valuenow="{{ $porcentaje }}" aria-valuemin="0"
                                                        aria-valuemax="100">
                                                        {{ number_format($porcentaje, 2) }}%
                                                    </div>
                                                </div>
                                            </td>

                                            <td>
                                                <button class="btn btn-warning"
                                                    wire:click="verventas('{{ $vendedora->id_usuario }}')">Ver
                                                    ventas</button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">No hay ventas
                                                registradas
                                                este mes</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>


                        {{-- ==================== Top 5 Productos por Sucursal ==================== --}}
                        @php
                            $fechaInicio = sprintf('%04d-%02d-01', $anio, $mesSeleccionado);
                            $fechaFin = sprintf('%04d-%02d-%02d', $anio, $mesSeleccionado, $diasDelMes);
                        @endphp

                        <h4 class="mt-5 mb-3 text-center fw-bold">
                            <i class="bi bi-trophy me-2"></i> Top 10 Productos por Sucursal
                            ({{ ucfirst(strftime('%B', mktime(0, 0, 0, $mesSeleccionado, 1))) }})
                        </h4>

                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-bordered text-nowrap">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Sucursal</th>
                                        @for ($i = 1; $i <= 10; $i++)
                                            <th>Producto {{ $i }}</th>
                                        @endfor
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($areas as $index => $item)
                                        @if ($item->area !== 'ALMACEN FEXPO')
                                            @php
                                                $topProductos = DB::table('registroinventarios')
                                                    ->select('nombreproducto')
                                                    ->where('sucursal', $item->area)
                                                    ->whereIn('motivo', ['compra', 'farmacia'])
                                                    ->whereBetween('fecha', [$fechaInicio, $fechaFin])
                                                    ->groupBy('nombreproducto')
                                                    ->orderByDesc(DB::raw('COUNT(idproducto)'))
                                                    ->limit(10)
                                                    ->pluck('nombreproducto');
                                            @endphp
                                            <tr>
                                                <td>{{ $item->area }}</td>
                                                @foreach ($topProductos as $producto)
                                                    <td>{{ $producto }}</td>
                                                @endforeach
                                                @for ($j = $topProductos->count(); $j < 10; $j++)
                                                    <td></td>
                                                @endfor
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>


                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="agendados" role="tabpanel" aria-labelledby="agendados-tab">
                <div class="py-3 table-responsive">
                    <table class="table table-striped table-hover table-bordered text-nowrap">
                        <thead class="table-dark">
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
                                @if (!str_contains($lista->area, 'ALMACEN'))
                                    @php
                                        $mes_actual_inicio = date('Y-m-01');
                                        $dia_actual = date('Y-m-d');

                                        $agendados_mes = DB::table('operativos')
                                            ->where('area', $lista->area)
                                            ->whereBetween('fecha', [$mes_actual_inicio, $dia_actual])
                                            ->count();

                                        $asistidos_mes = DB::table('registropagos')
                                            ->where('sucursal', $lista->area)
                                            ->whereBetween('fecha', [$mes_actual_inicio, $dia_actual])
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
                                            $dias_transcurridos > 0 ? ceil($agendados_mes / $dias_transcurridos) : 0;
                                        $prom_asistidos =
                                            $dias_transcurridos > 0 ? ceil($asistidos_mes / $dias_transcurridos) : 0;

                                        // Mes anterior
                                        $inicio_mes_anterior = date('Y-m-01', strtotime('first day of last month'));
                                        $fin_mes_anterior = date('Y-m-t', strtotime('last day of last month'));
                                        $dias_mes_anterior = date('t', strtotime('last day of last month'));

                                        $agendados_anterior = DB::table('operativos')
                                            ->where('area', $lista->area)
                                            ->whereBetween('fecha', [$inicio_mes_anterior, $fin_mes_anterior])
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
                                                $fechaInicio = date('Y-m-d', mktime(0, 0, 0, $mes, 1, $anioActual));
                                                $fechaFin = date('Y-m-t', mktime(0, 0, 0, $mes, 1, $anioActual));
                                                $hoy = date('Y-m-d');
                                                $esMesActual = $mes == date('n') && $anioActual == date('Y');

                                                if ($esMesActual) {
                                                    // Usamos el número de días transcurridos hasta hoy
                                                    $diasDelMes = date('j'); // Día del mes actual (1-31)
                                                    $fechaFin = date('Y-m-d');
                                                } else {
                                                    // Usamos el total de días del mes
                                                    $diasDelMes = cal_days_in_month(CAL_GREGORIAN, $mes, $anioActual);
                                                }
                                                $sumaMes = DB::table('operativos')
                                                    ->where('area', $lista->area)
                                                    ->whereBetween('fecha', [$fechaInicio, $fechaFin])
                                                    ->count();

                                                $promedios[$mes] = round($sumaMes / $diasDelMes);
                                                $promedios_agenda[$mes] =
                                                    ($promedios_agenda[$mes] ?? 0) + round($sumaMes / $diasDelMes);
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
                                @endif
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
                @php
                    $areas = DB::table('areas')->get(); // O tu colección de sucursales
                    $anioActual = date('Y');
                    $mesSeleccionado = request('mes') ?? date('m');
                    $diaActual =
                        $mesSeleccionado == date('m')
                            ? date('d')
                            : cal_days_in_month(CAL_GREGORIAN, $mesSeleccionado, $anioActual);

                    $fechas = [];
                    for ($d = 1; $d <= $diaActual; $d++) {
                        $fechas[] = sprintf('%04d-%02d-%02d', $anioActual, $mesSeleccionado, $d);
                    }

                    // Inicializamos acumuladores generales
                    $totalAgendadosPorDia = array_fill(1, $diaActual, 0);
                    $totalAsistidosPorDia = array_fill(1, $diaActual, 0);
                @endphp

                <h4 class="mb-4 text-center fw-bold">
                    📅 Promedios de Agendados y Asistidos - {{ strftime('%B', mktime(0, 0, 0, $mesSeleccionado, 1)) }}
                    {{ $anioActual }}
                </h4>

                <div class="table-responsive">
                    <table class="table table-striped table-hover table-bordered text-nowrap">
                        <thead class="table-dark">
                            <tr>
                                <th>Sucursal</th>
                                @foreach ($fechas as $fecha)
                                    <th>{{ date('d', strtotime($fecha)) }}</th>
                                @endforeach
                                <th>Promedio Agendados</th>
                                <th>Promedio Asistidos</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($areas as $area)
                                @php
                                    $totalAgendadosSucursal = 0;
                                    $totalAsistidosSucursal = 0;
                                    $promediosAgendados = [];
                                    $promediosAsistidos = [];
                                @endphp
                                @if (!str_contains($area->area, 'ALMACEN'))
                                    <tr>
                                        <td>{{ $area->area }}</td>
                                        @foreach ($fechas as $index => $fecha)
                                            @php
                                                $agendados = DB::table('operativos')
                                                    ->where('area', $area->area)
                                                    ->where('fecha', $fecha)
                                                    ->count();

                                                $asistidos = DB::table('registropagos')
                                                    ->where('sucursal', $area->area)
                                                    ->where('fecha', $fecha)
                                                    ->distinct('idoperativo')
                                                    ->count();

                                                $totalAgendadosSucursal += $agendados;
                                                $totalAsistidosSucursal += $asistidos;

                                                $totalAgendadosPorDia[$index + 1] += $agendados;
                                                $totalAsistidosPorDia[$index + 1] += $asistidos;

                                                $promediosAgendados[] = $agendados;
                                                $promediosAsistidos[] = $asistidos;
                                            @endphp
                                            <td>{{ $agendados }}</td>
                                        @endforeach
                                        <td>{{ $diaActual > 0 ? number_format($totalAgendadosSucursal / $diaActual, 2) : 0 }}
                                        </td>
                                        <td>{{ $diaActual > 0 ? number_format($totalAsistidosSucursal / $diaActual, 2) : 0 }}
                                        </td>
                                    </tr>
                                @endif
                            @endforeach

                            {{-- Totales generales por día --}}
                            <tr class="table-primary fw-bold">
                                <td>TOTALES</td>
                                @foreach ($fechas as $index => $fecha)
                                    <td>
                                        {{ $totalAgendadosPorDia[$index + 1] ?? 0 }} /
                                        {{ $totalAsistidosPorDia[$index + 1] ?? 0 }}
                                    </td>
                                @endforeach
                                <td>{{ $diaActual > 0 ? number_format(array_sum($totalAgendadosPorDia) / $diaActual, 2) : 0 }}
                                </td>
                                <td>{{ $diaActual > 0 ? number_format(array_sum($totalAsistidosPorDia) / $diaActual, 2) : 0 }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                @php
                    $areas = DB::table('areas')->get(); // Tu colección de sucursales
                    $anioActual = date('Y');
                    $mesSeleccionado = request('mes') ?? date('m');
                    $diaActual =
                        $mesSeleccionado == date('m')
                            ? date('d')
                            : cal_days_in_month(CAL_GREGORIAN, $mesSeleccionado, $anioActual);

                    // Lista de fechas del mes seleccionado hasta hoy
                    $fechas = [];
                    for ($d = 1; $d <= $diaActual; $d++) {
                        $fechas[] = sprintf('%04d-%02d-%02d', $anioActual, $mesSeleccionado, $d);
                    }

                    // Inicializar acumuladores generales
                    $totalAsistidosPorDia = array_fill(1, $diaActual, 0);
                @endphp

                <h4 class="mb-4 text-center fw-bold">
                    👩‍💼 Promedio Asistidos - {{ strftime('%B', mktime(0, 0, 0, $mesSeleccionado, 1)) }}
                    {{ $anioActual }}
                </h4>


                <div class="py-3 table-responsive">
                    <table class="table table-striped table-hover table-bordered text-nowrap">
                        <thead class="table-dark">
                            <tr>
                                <th>Sucursal</th>
                                @foreach ($fechas as $fecha)
                                    <th>{{ date('d', strtotime($fecha)) }}</th>
                                @endforeach
                                <th>Promedio Asistidos</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($areas as $area)
                                @if (!str_contains($area->area, 'ALMACEN'))
                                    @php
                                        $totalAsistidosSucursal = 0;
                                    @endphp
                                    <tr>
                                        <td>{{ $area->area }}</td>
                                        @foreach ($fechas as $index => $fecha)
                                            @php
                                                $asistidos = DB::table('registropagos')
                                                    ->where('sucursal', $area->area)
                                                    ->whereDate('fecha', $fecha)
                                                    ->distinct('idoperativo')
                                                    ->count();

                                                $totalAsistidosSucursal += $asistidos;
                                                $totalAsistidosPorDia[$index + 1] += $asistidos;
                                            @endphp
                                            <td>{{ $asistidos }}</td>
                                        @endforeach
                                        <td>{{ $diaActual > 0 ? number_format($totalAsistidosSucursal / $diaActual, 2) : 0 }}
                                        </td>
                                    </tr>
                                @endif
                            @endforeach

                            {{-- Totales generales por día --}}
                            <tr class="table-primary fw-bold">
                                <td>TOTALES</td>
                                @foreach ($fechas as $index => $fecha)
                                    <td>{{ $totalAsistidosPorDia[$index + 1] ?? 0 }}</td>
                                @endforeach
                                <td>{{ $diaActual > 0 ? number_format(array_sum($totalAsistidosPorDia) / $diaActual, 2) : 0 }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>


            </div>
            <div class="tab-pane fade" id="enganches" role="tabpanel" aria-labelledby="enganches-tab">
                <div class="px-4">
                    @php
                        $anioActual = date('Y');
                        $mesSeleccionado = request('mes') ?? date('m');
                        $diaActual =
                            $mesSeleccionado == date('m')
                                ? date('d')
                                : cal_days_in_month(CAL_GREGORIAN, $mesSeleccionado, $anioActual);

                        // Lista de fechas del mes seleccionado hasta hoy
                        $fechas = [];
                        for ($d = 1; $d <= $diaActual; $d++) {
                            $fechas[] = sprintf('%04d-%02d-%02d', $anioActual, $mesSeleccionado, $d);
                        }

                        $totalPorDia = array_fill(1, $diaActual, 0);
                    @endphp

                    <div class="py-3 table-responsive">
                        <table class="table table-striped table-hover table-bordered text-nowrap">
                            <thead class="table-dark">
                                <tr>
                                    <th>SUCURSAL</th>
                                    @foreach ($fechas as $fecha)
                                        <th>{{ date('d', strtotime($fecha)) }}</th>
                                    @endforeach
                                    <th>ENGANCHE DEL DÍA</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($areas as $lista)
                                    @if (!str_contains($lista->area, 'ALMACEN'))
                                        {{-- Excluye almacenes --}}
                                        @php
                                            $totalSucursal = 0;
                                        @endphp
                                        <tr>
                                            <td>{{ $lista->area }}</td>
                                            @foreach ($fechas as $index => $fecha)
                                                @php
                                                    $sumaDia = DB::table('historial_clientes')
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
                                                        ->whereDate('historial_clientes.fecha', $fecha)
                                                        ->whereNotNull('historial_clientes.idcosmetologa')
                                                        ->count();

                                                    $totalSucursal += $sumaDia;
                                                    $totalPorDia[$index + 1] += $sumaDia;
                                                @endphp
                                                <td>{{ $sumaDia }}</td>
                                            @endforeach
                                            @php
                                                $totalHoy = DB::table('historial_clientes')
                                                    ->join('operativos', function ($join) {
                                                        $join->on(
                                                            DB::raw('operativos.id'),
                                                            '=',
                                                            DB::raw('CAST(historial_clientes.idoperativo AS BIGINT)'),
                                                        );
                                                    })
                                                    ->where('operativos.area', $lista->area)
                                                    ->whereDate('historial_clientes.fecha', date('Y-m-d'))
                                                    ->whereNotNull('historial_clientes.idcosmetologa')
                                                    ->count();
                                            @endphp
                                            <td>{{ $totalHoy }}</td>
                                        </tr>
                                    @endif
                                @endforeach

                                {{-- Totales generales por día --}}
                                <tr class="table-primary fw-bold">
                                    <td>TOTALES</td>
                                    @foreach ($totalPorDia as $total)
                                        <td>{{ $total }}</td>
                                    @endforeach
                                    @php
                                        $totalHoyGeneral = 0;
                                        foreach ($areas as $lista) {
                                            if (!str_contains($lista->area, 'ALMACEN')) {
                                                $totalHoyGeneral += DB::table('historial_clientes')
                                                    ->join('operativos', function ($join) {
                                                        $join->on(
                                                            DB::raw('operativos.id'),
                                                            '=',
                                                            DB::raw('CAST(historial_clientes.idoperativo AS BIGINT)'),
                                                        );
                                                    })
                                                    ->where('operativos.area', $lista->area)
                                                    ->whereDate('historial_clientes.fecha', date('Y-m-d'))
                                                    ->whereNotNull('historial_clientes.idcosmetologa')
                                                    ->count();
                                            }
                                        }
                                    @endphp
                                    <td>{{ $totalHoyGeneral }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

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

                    <div class="py-3 table-responsive">
                        <table class="table table-striped table-hover table-bordered text-nowrap">
                            <thead class="table-dark">
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
                                                $fechaInicio = date('Y-m-d', mktime(0, 0, 0, $mes, 1, $anioActual));
                                                $fechaFin = date('Y-m-t', mktime(0, 0, 0, $mes, 1, $anioActual));
                                                $hoy = date('Y-m-d');
                                                $esMesActual = $mes == date('n') && $anioActual == date('Y');

                                                if ($esMesActual) {
                                                    // Usamos el número de días transcurridos hasta hoy
                                                    $diasDelMes = date('j'); // Día del mes actual (1-31)
                                                } else {
                                                    // Usamos el total de días del mes
                                                    $diasDelMes = cal_days_in_month(CAL_GREGORIAN, $mes, $anioActual);
                                                }
                                                $sumaMes = DB::table('historial_clientes')
                                                    ->join('operativos', function ($join) {
                                                        $join->on(
                                                            DB::raw('operativos.id'),
                                                            '=',
                                                            DB::raw('CAST(historial_clientes.idoperativo AS BIGINT)'),
                                                        );
                                                    })
                                                    ->where('operativos.area', $lista->area)
                                                    ->whereBetween('historial_clientes.fecha', [
                                                        $fechaInicio,
                                                        $fechaFin,
                                                    ])
                                                    ->whereNotNull('historial_clientes.idcosmetologa')
                                                    ->count();

                                                // Ahora puedes calcular el promedio:

                                                $promedios[$mes] = $sumaMes / $diasDelMes;
                                            }
                                            $totalHoy = DB::table('historial_clientes')
                                                ->join('operativos', function ($join) {
                                                    $join->on(
                                                        DB::raw('operativos.id'),
                                                        '=',
                                                        DB::raw('CAST(historial_clientes.idoperativo AS BIGINT)'),
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
                                            $fechaInicio = date('Y-m-d', mktime(0, 0, 0, $i, 1, $anioActual));
                                            $fechaFin = date('Y-m-t', mktime(0, 0, 0, $i, 1, $anioActual));

                                            // Verifica si el mes es el actual
                                            $esMesActual = $i == date('n') && $anioActual == date('Y');

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
                                                            DB::raw('CAST(historial_clientes.idoperativo AS BIGINT)'),
                                                        );
                                                    })
                                                    ->where('operativos.area', $lista->area)
                                                    ->whereBetween('historial_clientes.fecha', [
                                                        $fechaInicio,
                                                        $fechaFin,
                                                    ])
                                                    ->whereNotNull('historial_clientes.idcosmetologa')
                                                    ->count();

                                                $totalMes += $suma;
                                            }

                                            $promedioTotalMes = $diasDelMes > 0 ? $totalMes / $diasDelMes : 0;
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
                                                        DB::raw('CAST(historial_clientes.idoperativo AS BIGINT)'),
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
            </div>
        </div>
    </div>

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Bootstrap 5 JS (si no lo tienes ya) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

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
