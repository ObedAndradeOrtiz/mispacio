<div class="">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <style>
        @media (max-width: 767.98px) {
            .nav-responsive li.nav-item {
                flex: 0 0 calc(50% - 10px) !important;
                text-align: center;
            }
        }

        @media (min-width: 768px) {
            .nav-responsive {
                flex-wrap: nowrap !important;
                justify-content: flex-start !important;
            }

            .nav-responsive li.nav-item {
                flex: 0 0 auto !important;
                text-align: left;
            }
        }
    </style>
    <style>
        /* En pantallas pequeñas (móviles), los elementos se distribuyen en columnas */
        @media (max-width: 767.98px) {
            .card-header .d-flex {
                flex-direction: column;
                align-items: stretch;
            }

            .card-header .gap-2 {
                margin-bottom: 1rem;
            }

            .card-header label {
                text-align: left;
            }

            .card-header input {
                max-width: 100%;
            }
        }
    </style>

    <div
        style="display: flex; justify-content: center; background-color: rgba(0, 0, 0, 0.5); z-index: 9999; align-items: center;">
        <div wire:loading class="my-3 text-center">
            <div class="spinner-border text-primary" role="status">
            </div>
            <div>
                <label for=""><span style="color:white;">Cargando...</span></label>
            </div>

        </div>
    </div>
    <div wire:loading.remove class="section-body">
        <div class="mt-3 container-fluid">
            <div class="d-flex justify-content-between align-items-center">
                <ul class="flex-wrap nav nav-tabs nav-responsive d-flex justify-content-center" style="gap: 10px;">
                    <li class="nav-item">
                        <a class="nav-link @if ($opcion == 0) active @endif" data-toggle="tab"
                            href="#admin-Dashboard" wire:click="setOpcion(0)">
                            <i class="fas fa-home me-1"></i> Inicio del Sistema
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if ($opcion == 1) active @endif" data-toggle="tab"
                            href="#admin-Activity" wire:click="setOpcion(1)">
                            <i class="fas fa-box-open me-1"></i> Ingresos por Productos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if ($opcion == 2) active @endif" data-toggle="tab"
                            href="#admin-agendado" wire:click="setOpcion(2)">
                            <i class="fas fa-spa me-1"></i> Ingresos por Servicios
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if ($opcion == 3) active @endif" data-toggle="tab"
                            href="#admin-gastos" wire:click="setOpcion(3)">
                            <i class="fas fa-wallet me-1"></i> Control de Gastos
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if ($opcion == 4) active @endif" data-toggle="tab"
                            href="#admin-caja" wire:click="setOpcion(4)">
                            <i class="fas fa-cash-register me-1"></i> Control de Caja Diaria
                        </a>
                    </li>
                </ul>

            </div>
        </div>
    </div>
    @php
        $gastoarealista = DB::table('gastos')
            ->where('area', Auth::user()->sucursal)
            ->where('fechainicio', '<=', $fechaActual)
            ->where('fechainicio', '>=', $fechaInicioMes)
            ->paginate(10);
        $total_monto_citas = DB::table('registropagos')
            ->where('sucursal', Auth::user()->sucursal)
            ->where('fecha', '<=', $fechaActual)
            ->where('fecha', '>=', $fechaInicioMes)
            ->where('modo', 'ilike', '%Efectivo%')
            ->where('monto', '>', '0')
            ->paginate(10);
        $total_monto_qr_lista = DB::table('registropagos')
            ->where('sucursal', Auth::user()->sucursal)
            ->where('estado', 'Activo')
            ->where('modo', 'ilike', '%qr%')
            ->where('fecha', '<=', $fechaActual)
            ->where('fecha', '>=', $fechaInicioMes)
            ->paginate(10);
        $total_inventario_pago = DB::table('registroinventarios')
            ->where('sucursal', Auth::user()->sucursal)
            ->where('estado', 'Activo')
            ->where('modo', 'ilike', '%Efectivo%')
            ->where('fecha', '<=', $fechaActual)
            ->where('fecha', '>=', $fechaInicioMes)
            ->where('motivo', 'compra')
            ->paginate(10);
        $total_inventario_pago_farmacia = DB::table('registroinventarios')
            ->where('sucursal', Auth::user()->sucursal)
            ->where('estado', 'Activo')
            ->where('fecha', '<=', $fechaActual)
            ->where('fecha', '>=', $fechaInicioMes)
            ->whereIn('motivo', ['farmacia', 'compra'])
            ->paginate(10);
        $total_inventario_pago_gabinete = DB::table('registroinventarios')
            ->where('sucursal', Auth::user()->sucursal)
            ->where('estado', 'Activo')
            ->where('fecha', '<=', $fechaActual)
            ->where('fecha', '>=', $fechaInicioMes)
            ->where('motivo', 'personal')
            ->paginate(10);
        $total_inventario_pago_qr = DB::table('registroinventarios')
            ->where('sucursal', Auth::user()->sucursal)
            ->where('estado', 'Activo')
            ->where('modo', 'ilike', '%QR%')
            ->where('fecha', '<=', $fechaActual)
            ->where('fecha', '>=', $fechaInicioMes)
            ->where('motivo', 'compra')
            ->paginate(10);
        $historial_caja = DB::table('registrocajas')
            ->where('estado', 'Activo')
            ->where('iduser', 'ilike', '%' . $usuarioseleccionado . '%')
            ->where('fecha', '<=', $fechaActual)
            ->where('fecha', '>=', $fechaInicioMes)
            ->paginate(10);
        $operativos_count = DB::table('operativos')->where('estado', 'Pendiente')->count();
        $hoy = date('Y-m-d');
        $horaActual = date('H:i');
        $operativos = DB::table('operativos')
            ->where('estado', 'Pendiente')
            ->where('fecha', $hoy)
            ->where('hora', '>=', $horaActual)
            ->where('area', Auth::user()->sucursal)
            ->limit(5)
            ->OrderBy('hora')
            ->paginate(5);
    @endphp
    @php
        use Carbon\Carbon;
        $horaActual = Carbon::now()->toTimeString();
        $personales = DB::table('users')
            ->where('sucursal', $this->areaseleccionada)
            ->where('estado', 'Activo')
            ->whereIn('rol', ['Recepcion', 'Cosmetologia'])
            ->where('horainicio', '<=', $horaActual)
            ->where('horafin', '>', $horaActual)
            ->groupBy('id')
            ->get();
        $personalesmiora = DB::table('users')
            ->leftJoin('operativos as o_actual', function ($join) {
                $join
                    ->on('users.name', '=', 'o_actual.responsable')
                    ->whereBetween('o_actual.fecha', [now()->startOfMonth(), now()]);
            })
            ->leftJoin('operativos as o_anterior', function ($join) {
                $join
                    ->on('users.name', '=', 'o_anterior.responsable')
                    ->whereBetween('o_anterior.fecha', [
                        now()->subMonthNoOverflow()->startOfMonth(),
                        now()->subMonthNoOverflow()->endOfMonth(),
                    ]);
            })
            ->select(
                'users.name',
                'users.rol',
                'users.sucursal',
                DB::raw('COUNT(DISTINCT o_actual.id) as cantidad_actual'),
                DB::raw('COUNT(DISTINCT o_anterior.id) as meta_mes_anterior'),
            )
            ->where('users.estado', 'Activo')
            ->whereIn('users.rol', ['Recepcion', 'CallCenter'])
            ->groupBy('users.name', 'users.rol', 'users.sucursal')
            ->orderByDesc('cantidad_actual')
            ->get();
        $personalesmioraenganche = DB::table('users')
            ->leftJoin('operativos', function ($join) {
                $join
                    ->on('users.name', '=', 'operativos.responsable')
                    ->whereBetween('operativos.fecha', [now()->startOfMonth(), now()]);
            })
            ->select('users.name', 'users.rol', DB::raw('COUNT(operativos.id) as cantidad_agendados'))
            ->where('users.estado', 'Activo')
            ->whereIn('users.rol', ['Cosmetologia'])
            ->groupBy('users.name', 'users.rol')
            ->orderByDesc('cantidad_agendados')
            ->get();
        $usuarios = DB::table('users')
            ->where('estado', 'Activo')
            ->whereIn('rol', ['Recepcion', 'Cosmetologia'])
            ->get();
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
        $fechaInicio = now()->startOfMonth();
        $fechaFin = now();
        $rankingEnganches = DB::table('users as u')
            ->leftJoin('registropagos as rp', function ($join) use ($fechaInicio, $fechaFin) {
                $join
                    ->on('u.id', '=', DB::raw('CAST(rp.idcosmetologa AS BIGINT)'))
                    ->whereBetween('rp.fecha', [$fechaInicio, $fechaFin]);
            })
            ->leftJoin('historial_clientes as hc', function ($join) use ($fechaInicio, $fechaFin) {
                $join
                    ->on('u.id', '=', DB::raw('CAST(hc.idcosmetologa AS BIGINT)'))
                    ->whereBetween('hc.fecha', [$fechaInicio, $fechaFin]);
            })
            ->whereIn('u.rol', ['Cosmetologia'])
            ->where('u.estado', 'Activo')
            ->groupBy('u.id', 'u.name', 'u.rol', 'u.sucursal')
            ->select(
                'u.id',
                'u.name',
                'u.rol',
                'u.sucursal',
                DB::raw('COUNT(DISTINCT rp.id) as total_atendidos'),
                DB::raw('COUNT(DISTINCT hc.id) as total_enganche'),
            )
            ->orderByDesc('total_enganche')
            ->get();
    @endphp
    <style>
        .pie-chart {
            position: relative;
            width: 220px;
            height: 220px;
            border-radius: 50%;
            background: conic-gradient(#1ead6b 0% var(--asistido),
                    #e44b5a var(--asistido) 100%);
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            margin: 20px auto;
            transition: background 0.5s ease-in-out;
        }

        .pie-border {
            position: absolute;
            top: 10px;
            left: 10px;
            right: 10px;
            bottom: 10px;
            background: white;
            border-radius: 50%;
            z-index: 1;
        }

        .center-label {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            z-index: 2;
        }

        .center-label span {
            display: block;
            font-size: 14px;
            color: #333;
            font-weight: 600;
        }

        .legend-pie {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 10px;
            font-size: 13px;
        }

        .legend-pie div {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .legend-color {
            width: 12px;
            height: 12px;
            border-radius: 2px;
        }
    </style>
    <div wire:loading.remove class="mt-4 section-body">
        <div class="container-fluid">
            <div class="tab-content">
                <div class="tab-pane fade @if ($opcion == 0) show active @endif" id="admin-Dashboard"
                    role="tabpanel">
                    <div class="row">
                        <!-- Estadística -->
                        <div class="mb-4 col-xl-4 col-lg-4 col-md-12">
                            <div class="mt-2 border-0 shadow-sm card h-100">
                                <div class="card-header">
                                    <h3 class="card-title">Estadísticas</h3>
                                </div>
                                <div class="text-center card-body">
                                    <div class="flex-wrap gap-3 mb-4 d-flex justify-content-center">
                                        <div style="min-width: 130px;">
                                            <label class="fw-semibold">Desde:</label>
                                            <input type="date" id="fechaInicioMesInput"
                                                class="form-control form-control-sm" wire:model="fechaInicioMes"
                                                onkeydown="return false">

                                        </div>
                                        <div style="min-width: 130px;">
                                            <label class="fw-semibold">Hasta:</label>
                                            <input type="date" class="form-control form-control-sm"
                                                wire:model="fechaActual" onkeydown="return false">
                                        </div>
                                    </div>

                                    @if (Auth::user()->rol == 'Administrador')
                                        <h5 class="mb-3">
                                            <strong>{{ $confirmados + $restantes }}</strong> Agendados
                                        </h5>
                                    @endif

                                    <div class="mb-2">
                                        <span class="badge bg-success me-2">Asistidos: {{ $confirmados }}</span>
                                        <span class="badge bg-danger">No Asistidos: {{ $restantes }}</span>
                                    </div>


                                    <div id="chartx" class="pie-chart" style="--asistido: 50%;">
                                        <div class="pie-border"></div>
                                        <div class="center-label">
                                            <span>Asistidos</span>
                                            <span id="asistidos-value"
                                                data-value="{{ $confirmados }}">{{ $confirmados }}</span>
                                            <span style="margin-top: 6px;">No Asistidos</span>
                                            <span id="noasistidos-value"
                                                data-value="{{ $agendados - $confirmados }}">{{ $agendados - $confirmados }}</span>
                                        </div>
                                    </div>

                                    <div class="legend-pie">
                                        <div>
                                            <div class="legend-color" style="background: #198754;"></div>
                                            Asistidos
                                        </div>
                                        <div>
                                            <div class="legend-color" style="background: #dc3545;"></div> No
                                            Asistidos
                                        </div>
                                    </div>
                                    <script>
                                        function getChartValuesFromDOM() {
                                            const asistidosEl = document.getElementById('asistidos-value');
                                            const noAsistidosEl = document.getElementById('noasistidos-value');

                                            const confirmados = parseInt(asistidosEl?.dataset.value || asistidosEl?.innerText || 0);
                                            const noAsistidos = parseInt(noAsistidosEl?.dataset.value || noAsistidosEl?.innerText || 0);

                                            return {
                                                confirmados,
                                                noAsistidos
                                            };
                                        }

                                        function renderPieChart(confirmados, noAsistidos) {
                                            const total = confirmados + noAsistidos;
                                            let porcentaje = 0;

                                            if (total > 0) {
                                                porcentaje = (confirmados / total) * 100;
                                            }

                                            porcentaje = Math.round(porcentaje * 100) / 100;

                                            const chart = document.getElementById('chartx');
                                            if (chart) {
                                                chart.style.setProperty('--asistido', porcentaje + '%');
                                            }

                                            document.getElementById('asistidos-value').innerText = confirmados;
                                            document.getElementById('asistidos-value').setAttribute('data-value', confirmados);
                                            document.getElementById('noasistidos-value').innerText = noAsistidos;
                                            document.getElementById('noasistidos-value').setAttribute('data-value', noAsistidos);
                                        }

                                        // Cargar al inicio
                                        document.addEventListener('DOMContentLoaded', () => {
                                            const {
                                                confirmados,
                                                noAsistidos
                                            } = getChartValuesFromDOM();
                                            renderPieChart(confirmados, noAsistidos);
                                        });

                                        // ✅ Escuchar cuando Livewire ya actualizó la vista
                                        document.addEventListener('livewire:load', () => {
                                            Livewire.hook('message.processed', () => {
                                                const {
                                                    confirmados,
                                                    noAsistidos
                                                } = getChartValuesFromDOM();
                                                renderPieChart(confirmados, noAsistidos);
                                            });
                                        });
                                    </script>

                                </div>
                            </div>
                        </div>
                        <!-- Tendencias del mes -->
                        <div class="mb-4 col-xl-4 col-lg-4 col-md-12">
                            <div class="mt-2 border-0 shadow-sm card h-100">
                                <div class="card-header">
                                    <h3 class="card-title"><label for="" class="mr-3">Índice de
                                            rendimiento</label>
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <ul class="mb-3 nav nav-pills nav-pills-custom">
                                        <li class="mb-3 nav-item me-3 me-lg-6">
                                            <a class="py-4 overflow-hidden nav-link d-flex justify-content-between flex-column flex-center active w-80px h-85px"
                                                data-bs-toggle="pill" href="#kt_stats_widget_2_tab_1">
                                                <div class="nav-icon">
                                                    <img alt="" src="assets/media/iconos/venta.png"
                                                        class="" />
                                                </div>
                                                <span class="text-gray-700 nav-text fw-bold fs-6 lh-1">Ventas</span>
                                                <span
                                                    class="bottom-0 bullet-custom position-absolute w-100 h-4px bg-primary"></span>
                                            </a>
                                        </li>
                                        <li class="mb-3 nav-item me-3 me-lg-6">
                                            <a class="py-4 overflow-hidden nav-link d-flex justify-content-between flex-column flex-center w-80px h-85px"
                                                data-bs-toggle="pill" href="#kt_stats_widget_2_tab_2">
                                                <div class="nav-icon">
                                                    <img alt="" src="assets/media/iconos/cosmetologa.png"
                                                        class="" />
                                                </div>
                                                <span class="text-gray-700 nav-text fw-bold fs-6 lh-1">Enganches</span>
                                                <span
                                                    class="bottom-0 bullet-custom position-absolute w-100 h-4px bg-primary"></span>
                                            </a>
                                        </li>
                                        <li class="mb-3 nav-item me-3 me-lg-6">
                                            <a class="py-4 overflow-hidden nav-link d-flex justify-content-between flex-column flex-center w-80px h-85px"
                                                data-bs-toggle="pill" href="#kt_stats_widget_2_tab_3">
                                                <div class="nav-icon">
                                                    <img alt="" src="assets/media/iconos/call.png"
                                                        class="" />
                                                </div>
                                                <span class="text-gray-600 nav-text fw-bold fs-6 lh-1">Agendados</span>
                                                <span
                                                    class="bottom-0 bullet-custom position-absolute w-100 h-4px bg-primary"></span>
                                            </a>
                                        </li>
                                    </ul>
                                    <div class="tab-content">
                                        <div class="tab-pane fade show active" id="kt_stats_widget_2_tab_1">
                                            <div class="table-responsive" style="height: 310px;">
                                                @php
                                                    $metaGlobal =
                                                        $ventas->max('total_mes_anterior') > 0
                                                            ? $ventas->max('total_mes_anterior')
                                                            : 1; // evita división por 0
                                                @endphp
                                                <style>
                                                    .bg-purple {
                                                        background-color: #a259ff !important;
                                                    }

                                                    .bg-row-gold {
                                                        background-color: #fff8dc;
                                                        /* light goldenrod */
                                                    }

                                                    .bg-row-purple {
                                                        background-color: #f3e8ff;
                                                        /* pale purple */
                                                    }

                                                    .bg-row-blue {
                                                        background-color: #8dc6fc;
                                                        /* pale blue */
                                                    }
                                                </style>
                                                <table class="table my-0 align-middle table-row-dashed gs-0 gy-4">
                                                    <thead>
                                                        <tr class="text-gray-500 fs-7 fw-bold border-bottom-0">
                                                            <th class="ps-0 w-50px">Nombre</th>
                                                            <th class="min-w-125px">Meta ({{ $metaGlobal }} Bs.)
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($ventas as $index => $v)
                                                            @php
                                                                $partes = explode(' ', $v->name);
                                                                $nombre = $partes[0] ?? '';
                                                                $apellido = $partes[2] ?? ($partes[1] ?? '');

                                                                $actual = round($v->total_mes_actual, 2);
                                                                $anterior = round($v->total_mes_anterior, 2);
                                                                $porcentaje = round(($actual / $metaGlobal) * 100);

                                                                $icono = '';
                                                                $claseBarra = 'bg-secondary'; // barra
                                                                $claseFila = ''; // fila

                                                                if ($index === 0) {
                                                                    $icono = '👑';
                                                                    $claseBarra = 'bg-warning';
                                                                    $claseFila = 'bg-row-gold';
                                                                } elseif ($index === 1) {
                                                                    $claseBarra = 'bg-purple';
                                                                    $claseFila = 'bg-row-purple';
                                                                } elseif ($index === 2) {
                                                                    $claseBarra = 'bg-secundary';
                                                                    $claseFila = 'bg-row-blue';
                                                                }
                                                            @endphp

                                                            <tr class="{{ $claseFila }}">
                                                                <td>
                                                                    <div> {!! $icono !!} {{ $nombre }}
                                                                        {{ $apellido }}</div>
                                                                    <div>
                                                                        <small class="text-muted">{{ $v->rol }}
                                                                            -
                                                                            {{ $v->sucursal }}</small>
                                                                    </div>

                                                                </td>
                                                                <td style="min-width: 150px; margin-right:5px;">
                                                                    <div class="progress" style="height: 20px;">
                                                                        <div class="progress-bar {{ $claseBarra }}"
                                                                            role="progressbar"
                                                                            style="width: {{ $porcentaje }}%; color:black;"
                                                                            aria-valuenow="{{ $porcentaje }}"
                                                                            aria-valuemin="0" aria-valuemax="100">
                                                                            {{ $porcentaje }}%
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="kt_stats_widget_2_tab_2">
                                            <div class="table-responsive" style="height: 310px;">
                                                <table class="table my-0 align-middle table-row-dashed gs-0 gy-4">
                                                    <thead>
                                                        <tr class="text-gray-500 fs-7 fw-bold border-bottom-0">
                                                            <th class="">Nombre</th>
                                                            <th class="">Nro. Enganches</th>
                                                            <th class="">Meta</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php
                                                            $metaEnganche =
                                                                $rankingEnganches->max('total_enganche') > 0
                                                                    ? $rankingEnganches->max('total_enganche')
                                                                    : 1;
                                                        @endphp

                                                        @foreach ($rankingEnganches as $index => $lista)
                                                            @php
                                                                $nombre = explode(' ', $lista->name)[0] ?? '';
                                                                $apellido =
                                                                    explode(' ', $lista->name)[2] ??
                                                                    (explode(' ', $lista->name)[1] ?? '');

                                                                $atendidos = $lista->total_atendidos;
                                                                $enganche = $lista->total_enganche;
                                                                $porcentaje = round(($enganche / $metaEnganche) * 100);

                                                                $icono = '';
                                                                $claseBarra = 'bg-secondary';
                                                                $claseFila = '';

                                                                if ($index === 0) {
                                                                    $icono = '👑';
                                                                    $claseBarra = 'bg-warning';
                                                                    $claseFila = 'bg-row-gold';
                                                                } elseif ($index === 1) {
                                                                    $claseBarra = 'bg-purple';
                                                                    $claseFila = 'bg-row-purple';
                                                                } elseif ($index === 2) {
                                                                    $claseBarra = 'bg-primary';
                                                                    $claseFila = 'bg-row-blue';
                                                                }
                                                            @endphp

                                                            <tr class="{{ $claseFila }}">
                                                                <td>
                                                                    {!! $icono !!} {{ $nombre }}
                                                                    {{ $apellido }}<br>
                                                                    <small class="text-muted">{{ $lista->rol }} -
                                                                        {{ $lista->sucursal }}</small>
                                                                </td>
                                                                <td>{{ $enganche }}</td>
                                                                <td style="min-width: 200px;">
                                                                    <div class="progress" style="height: 20px;">
                                                                        <div class="progress-bar {{ $claseBarra }}"
                                                                            role="progressbar"
                                                                            style="width: {{ $porcentaje }}%; color:black;"
                                                                            aria-valuenow="{{ $porcentaje }}"
                                                                            aria-valuemin="0" aria-valuemax="100">
                                                                            {{ $porcentaje }}%
                                                                        </div>
                                                                    </div>
                                                                </td>

                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="kt_stats_widget_2_tab_3">
                                            <div class="table-responsive" style="height: 310px;">
                                                <table class="table my-0 align-middle table-row-dashed gs-0 gy-4">
                                                    @php
                                                        $metaMaxima =
                                                            $personalesmiora->max('meta_mes_anterior') > 0
                                                                ? $personalesmiora->max('meta_mes_anterior')
                                                                : 1;
                                                    @endphp
                                                    <thead>
                                                        <tr class="text-gray-500 fs-7 fw-bold border-bottom-0">
                                                            <th class="">Nombre</th>
                                                            <th class="">Agendados</th>
                                                            <th class="">Meta</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($personalesmiora as $index => $item)
                                                            @php
                                                                $partes = explode(' ', $item->name);
                                                                $nombre = $partes[0] ?? '';
                                                                $apellido = $partes[2] ?? ($partes[1] ?? '');

                                                                $actual = $item->cantidad_actual ?? 0;
                                                                $meta = $item->meta_mes_anterior ?? 0;

                                                                $metaSegura = $metaMaxima > 0 ? $metaMaxima : 1;
                                                                $porcentaje = round(($actual / $metaSegura) * 100);

                                                                // Ranking visual
                                                                $icono = '';
                                                                $claseBarra = 'bg-secondary'; // por defecto gris
                                                                $claseFila = '';

                                                                if ($index === 0) {
                                                                    $icono = '👑';
                                                                    $claseBarra = 'bg-warning'; // dorado
                                                                    $claseFila = 'bg-row-gold';
                                                                } elseif ($index === 1) {
                                                                    $claseBarra = 'bg-purple'; // lila (requiere clase CSS o Tailwind)
                                                                    $claseFila = 'bg-row-purple';
                                                                } elseif ($index === 2) {
                                                                    $claseBarra = 'bg-primary'; // azul
                                                                    $claseFila = 'bg-row-blue';
                                                                }
                                                            @endphp

                                                            <tr class="{{ $claseFila }}">
                                                                <td>
                                                                    <div> {!! $icono !!} {{ $nombre }}
                                                                        {{ $apellido }}</div>
                                                                    <div>
                                                                        <small class="text-muted">{{ $item->rol }}
                                                                            -
                                                                            {{ $item->sucursal }}</small>
                                                                    </div>
                                                                </td>
                                                                <td>{{ $actual }}</td>
                                                                <td style="min-width: 200px; ">
                                                                    <div class="progress" style="height: 20px;">
                                                                        <div class="progress-bar {{ $claseBarra }}"
                                                                            role="progressbar"
                                                                            style="width: {{ $porcentaje }}%; color:black;"
                                                                            aria-valuenow="{{ $porcentaje }}"
                                                                            aria-valuemin="0" aria-valuemax="100">
                                                                            {{ $porcentaje }}%
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Personal de turno -->
                        <div class="mb-4 col-xl-4 col-lg-4 col-md-12">
                            <div class="mt-2 border-0 shadow-sm card h-100">
                                <div class="card-header">
                                    <h3 class="card-title"><label for="" class="mr-3">Personal de
                                            turno:</label>
                                        <select id="areaSelect" class="form-select" wire:model="areaseleccionada">
                                            @foreach ($areas as $item)
                                                <option value="{{ $item->area }}">{{ $item->area }}</option>
                                            @endforeach
                                        </select>

                                    </h3>
                                    <div class="card-options">
                                        <a href="#" class="card-options-collapse"
                                            data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
                                        <a href="#" class="card-options-fullscreen"
                                            data-toggle="card-fullscreen"><i class="fe fe-maximize"></i></a>
                                        <a href="#" class="card-options-remove" data-toggle="card-remove"><i
                                                class="fe fe-x"></i></a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive" style="height: 310px;">
                                        <table class="table mb-0 table-striped table-vcenter text-nowrap">
                                            <thead>
                                                <tr>
                                                    <th>Nombre</th>
                                                    <th>Rol</th>
                                                    <th>Entrada</th>
                                                    <th>Salida</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($personales as $item)
                                                    <tr>
                                                        @php
                                                            $partes = explode(' ', $item->name);
                                                            $nombre = $partes[0] ?? '';
                                                            $apellido = $partes[2] ?? ($partes[1] ?? ''); // asume 2 nombres + 2 apellidos, o 1 nombre + 1 apellido
                                                        @endphp

                                                        <td>{{ $nombre }} {{ $apellido }}</td>
                                                        <td>{{ $item->rol }}</td>
                                                        <td>{{ $item->horainicio }}</td>
                                                        <td>{{ $item->horafin }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade @if ($opcion == 1) show active @endif" id="admin-Activity"
                    role="tabpanel">
                    <div class="mt-2 border-0 shadow-sm card h-100">
                        <div class="text-center card-header">
                            <h3 class="mb-3 card-title fw-bold">
                                Registro de Ventas de Productos
                            </h3>
                            <div class="flex-wrap gap-3 d-flex justify-content-center align-items-center">
                                <div class="gap-2 d-flex align-items-center">
                                    <label for="fecha-inicio" class="mb-0 fw-semibold">Desde:</label>
                                    <input type="date" id="fecha-inicio" class="form-control form-control-sm"
                                        style="max-width: 150px;" wire:model="fechaInicioMes"
                                        onkeydown="return false">
                                </div>

                                <div class="gap-2 d-flex align-items-center ms-4">
                                    <label for="fecha-actual" class="mb-0 fw-semibold">Hasta:</label>
                                    <input type="date" id="fecha-actual" class="form-control form-control-sm"
                                        style="max-width: 150px;" wire:model="fechaActual" onkeydown="return false">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12" style="margin-top:1%;">
                            <div class="mt-2 border-0 shadow-sm card h-100">
                                <div class="card-header">
                                    <h3 class="card-title">Venta de productos (Efectivo)</h3>
                                    <div class="card-options">
                                        <a href="#" class="card-options-collapse"
                                            data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
                                        <a href="#" class="card-options-fullscreen"
                                            data-toggle="card-fullscreen"><i class="fe fe-maximize"></i></a>
                                        <a href="#" class="card-options-remove" data-toggle="card-remove"><i
                                                class="fe fe-x"></i></a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table mb-0 table-striped text-nowrap">
                                            <thead>
                                                <tr class="ligth">
                                                    <th># Registro</th>
                                                    <th>Ingreso</th>
                                                    <th>Producto</th>
                                                    <th>Cantidad</th>
                                                    <th>Registrado por</th>
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
                                                    $pagado = 0;
                                                @endphp
                                                @foreach ($total_inventario_pago as $lista)
                                                    @php
                                                        $pagado = $pagado + $lista->precio;
                                                    @endphp
                                                    <tr>
                                                        <td>{{ $lista->id }}</td>
                                                        <td>{{ $lista->precio }}</td>
                                                        <td>{{ $lista->nombreproducto }}</td>
                                                        <td>{{ $lista->cantidad }}</td>
                                                        @php
                                                            $responsable = DB::table('users')
                                                                ->where('id', $lista->iduser)
                                                                ->pluck('name')
                                                                ->first();
                                                        @endphp
                                                        <td>{{ $responsable }}</td>
                                                    </tr>
                                                @endforeach
                                                <tr class="bg-gray">
                                                    <td>Total</td>
                                                    <td>{{ $pagado }}</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <div class="py-2 ml-2">
                                            {{ $total_inventario_pago->links() }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12" style="margin-top:1%;">
                            <div class="mt-2 border-0 shadow-sm card h-100">
                                <div class="card-header">
                                    <h3 class="card-title">Venta de productos (QR)</h3>
                                    <div class="card-options">
                                        <a href="#" class="card-options-collapse"
                                            data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
                                        <a href="#" class="card-options-fullscreen"
                                            data-toggle="card-fullscreen"><i class="fe fe-maximize"></i></a>
                                        <a href="#" class="card-options-remove" data-toggle="card-remove"><i
                                                class="fe fe-x"></i></a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table mb-0 table-striped text-nowrap">
                                            <thead>
                                                <tr class="ligth">
                                                    <th># Registro</th>
                                                    <th>Ingreso</th>
                                                    <th>Producto</th>
                                                    <th>Cantidad</th>
                                                    <th>Registrado por</th>
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
                                                    $pagado = 0;
                                                @endphp
                                                @foreach ($total_inventario_pago_qr as $lista)
                                                    @php
                                                        $pagado = $pagado + $lista->precio;
                                                    @endphp
                                                    <tr>
                                                        <td>{{ $lista->id }}</td>
                                                        <td>{{ $lista->precio }}</td>
                                                        <td>{{ $lista->nombreproducto }}</td>
                                                        <td>{{ $lista->cantidad }}</td>
                                                        @php
                                                            $responsable = DB::table('users')
                                                                ->where('id', $lista->iduser)
                                                                ->pluck('name')
                                                                ->first();
                                                        @endphp
                                                        <td>{{ $responsable }}</td>
                                                    </tr>
                                                @endforeach
                                                <tr class="bg-gray">
                                                    <td>Total</td>
                                                    <td>{{ $pagado }}</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <div class="py-2 ml-2">
                                            {{ $total_inventario_pago_qr->links() }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12" style="margin-top:1%;">
                            <div class="mt-2 border-0 shadow-sm card h-100">
                                <div class="card-header">
                                    <h3 class="card-title">Venta de productos Farmacia</h3>
                                    <div class="card-options">
                                        <a href="#" class="card-options-collapse"
                                            data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
                                        <a href="#" class="card-options-fullscreen"
                                            data-toggle="card-fullscreen"><i class="fe fe-maximize"></i></a>
                                        <a href="#" class="card-options-remove" data-toggle="card-remove"><i
                                                class="fe fe-x"></i></a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table mb-0 table-striped text-nowrap">
                                            <thead>
                                                <tr class="ligth">
                                                    <th># Registro</th>
                                                    <th>Ingreso</th>
                                                    <th>Producto</th>
                                                    <th>Cantidad vendida</th>
                                                    <th>Método</th>
                                                    <th>Registrado por</th>
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
                                                    $pagado = 0;
                                                @endphp
                                                @foreach ($total_inventario_pago_farmacia as $lista)
                                                    @php
                                                        $pagado = $pagado + $lista->precio;
                                                    @endphp
                                                    <tr>
                                                        <td>{{ $lista->id }}</td>

                                                        <td>{{ $lista->precio }}</td>
                                                        <td>{{ $lista->nombreproducto }}</td>
                                                        <td>{{ $lista->cantidad }}</td>
                                                        <td>{{ $lista->modo }}</td>
                                                        @php
                                                            $responsable = DB::table('users')
                                                                ->where('id', $lista->iduser)
                                                                ->pluck('name')
                                                                ->first();
                                                        @endphp
                                                        <td>{{ $responsable }}</td>
                                                    </tr>
                                                @endforeach
                                                <tr class="bg-gray">
                                                    <td>Total</td>
                                                    <td>{{ $pagado }}</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <div class="py-2 ml-2">
                                            {{ $total_inventario_pago_farmacia->links() }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12" style="margin-top:1%;">
                            <div class="mt-2 border-0 shadow-sm card h-100">
                                <div class="card-header">
                                    <h3 class="card-title">Productos retirados a gabinete</h3>
                                    <div class="card-options">
                                        <a href="#" class="card-options-collapse"
                                            data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
                                        <a href="#" class="card-options-fullscreen"
                                            data-toggle="card-fullscreen"><i class="fe fe-maximize"></i></a>
                                        <a href="#" class="card-options-remove" data-toggle="card-remove"><i
                                                class="fe fe-x"></i></a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table mb-0 table-striped text-nowrap">
                                            <thead>
                                                <tr class="ligth">
                                                    <th># Registro</th>
                                                    <th>Producto</th>
                                                    <th>Cantidad</th>
                                                    <th>Retirado por</th>
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

                                                @foreach ($total_inventario_pago_gabinete as $lista)
                                                    <tr>
                                                        <td>{{ $lista->id }}</td>

                                                        <td>{{ $lista->nombreproducto }}</td>
                                                        <td>{{ $lista->cantidad }}</td>
                                                        @php
                                                            $responsable = DB::table('users')
                                                                ->where('id', $lista->iduser)
                                                                ->pluck('name')
                                                                ->first();
                                                        @endphp
                                                        <td>{{ $responsable }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        <div class="py-2 ml-2">
                                            {{ $total_inventario_pago_farmacia->links() }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade @if ($opcion == 2) show active @endif" id="admin-agendado"
                    role="tabpanel">
                    <div class="mt-2 border-0 shadow-sm card h-100">
                        <div class="text-center card-header">
                            <h3 class="mb-3 card-title fw-bold">
                                Registro de ingresos por Servicios
                            </h3>
                            <div class="flex-wrap gap-3 d-flex justify-content-center align-items-center">
                                <div class="gap-2 d-flex align-items-center">
                                    <label for="fecha-inicio" class="mb-0 fw-semibold">Desde:</label>
                                    <input type="date" id="fecha-inicio" class="form-control form-control-sm"
                                        style="max-width: 150px;" wire:model="fechaInicioMes"
                                        onkeydown="return false">
                                </div>
                                <div class="gap-2 d-flex align-items-center ms-4">
                                    <label for="fecha-actual" class="mb-0 fw-semibold">Hasta:</label>
                                    <input type="date" id="fecha-actual" class="form-control form-control-sm"
                                        style="max-width: 150px;" wire:model="fechaActual" onkeydown="return false">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12" style="margin-top:1%;">
                            <div class="mt-2 border-0 shadow-sm card h-100">
                                <div class="card-header">
                                    <h3 class="card-title">Ingreso agendado (Efectivo)</h3>
                                    <div class="card-options">
                                        <a href="#" class="card-options-collapse"
                                            data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
                                        <a href="#" class="card-options-fullscreen"
                                            data-toggle="card-fullscreen"><i class="fe fe-maximize"></i></a>
                                        <a href="#" class="card-options-remove" data-toggle="card-remove"><i
                                                class="fe fe-x"></i></a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table mb-0 table-striped text-nowrap">
                                            <thead>
                                                <tr class="ligth">
                                                    <th># Registro</th>
                                                    <th>Ingreso</th>
                                                    <th>Registro por</th>
                                                    <th>Cliente</th>
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
                                                    $pagado = 0;
                                                @endphp
                                                @foreach ($total_monto_citas as $lista)
                                                    @php
                                                        $pagado = $pagado + $lista->monto;
                                                    @endphp
                                                    <tr>
                                                        <td>{{ $lista->id }}</td>
                                                        <td>{{ $lista->monto }}</td>
                                                        <td>{{ $lista->responsable }}</td>
                                                        <td>{{ $lista->nombrecliente }}</td>
                                                    </tr>
                                                @endforeach
                                                <tr class="bg-gray">
                                                    <td>Total</td>
                                                    <td>{{ $pagado }}</td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <div class="py-2 ml-2">
                                            {{ $total_monto_citas->links() }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12" style="margin-top:1%;">
                            <div class="mt-2 border-0 shadow-sm card h-100">
                                <div class="card-header">
                                    <h3 class="card-title">Ingreso agendado (QR)</h3>
                                    <div class="card-options">
                                        <a href="#" class="card-options-collapse"
                                            data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
                                        <a href="#" class="card-options-fullscreen"
                                            data-toggle="card-fullscreen"><i class="fe fe-maximize"></i></a>
                                        <a href="#" class="card-options-remove" data-toggle="card-remove"><i
                                                class="fe fe-x"></i></a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table mb-0 table-striped text-nowrap">
                                            <thead>
                                                <tr class="ligth">
                                                    <th># Registro</th>
                                                    <th>Ingreso</th>
                                                    <th>Registro por</th>
                                                    <th>Cliente</th>
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
                                                    $pagado = 0;
                                                @endphp
                                                @foreach ($total_monto_qr_lista as $lista)
                                                    @php
                                                        $pagado = $pagado + $lista->monto;
                                                    @endphp
                                                    <tr>
                                                        <td>{{ $lista->id }}</td>
                                                        <td>{{ $lista->monto }}</td>
                                                        <td>{{ $lista->responsable }}</td>
                                                        <td>{{ $lista->nombrecliente }}</td>
                                                    </tr>
                                                @endforeach
                                                <tr class="bg-gray">
                                                    <td>Total</td>
                                                    <td>{{ $pagado }}</td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <div class="py-2 ml-2">
                                            {{ $total_monto_qr_lista->links() }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade @if ($opcion == 3) show active @endif" id="admin-gastos"
                    role="tabpanel">
                    <div class="mt-2 border-0 shadow-sm card h-100">
                        <div class="text-center card-header">
                            <h3 class="mb-3 card-title fw-bold">
                                Registro de gastos realizados
                            </h3>
                            <div class="flex-wrap gap-3 d-flex justify-content-center align-items-center">
                                <div class="gap-2 d-flex align-items-center">
                                    <label for="fecha-inicio" class="mb-0 fw-semibold">Desde:</label>
                                    <input type="date" id="fecha-inicio" class="form-control form-control-sm"
                                        style="max-width: 150px;" wire:model="fechaInicioMes"
                                        onkeydown="return false">
                                </div>
                                <div class="gap-2 d-flex align-items-center ms-4">
                                    <label for="fecha-actual" class="mb-0 fw-semibold">Hasta:</label>
                                    <input type="date" id="fecha-actual" class="form-control form-control-sm"
                                        style="max-width: 150px;" wire:model="fechaActual" onkeydown="return false">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12" style="margin-top:1%;">
                            <div class="mt-2 border-0 shadow-sm card h-100">
                                <div class="card-header">
                                    <h3 class="card-title">Gastos realizados (QR + EFECTIVO)</h3>
                                    <div class="card-options">
                                        <a href="#" class="card-options-collapse"
                                            data-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
                                        <a href="#" class="card-options-fullscreen"
                                            data-toggle="card-fullscreen"><i class="fe fe-maximize"></i></a>
                                        <a href="#" class="card-options-remove" data-toggle="card-remove"><i
                                                class="fe fe-x"></i></a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table mb-0 table-striped text-nowrap">
                                            <thead>
                                                <tr class="ligth">
                                                    <th>Motivo</th>
                                                    <th>Monto</th>
                                                    <th>Tipo</th>
                                                    <th>Método</th>
                                                    <th>Responsable</th>
                                                    <th>Sucursal</th>
                                                    <th>Acción</th>
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
                                                    $pagado = 0;
                                                @endphp

                                                @foreach ($gastoarealista as $lista)
                                                    @php
                                                        $pagado = $pagado + $lista->cantidad;
                                                    @endphp
                                                    <tr>
                                                        <td>{{ $lista->empresa }}</td>
                                                        <td>{{ $lista->cantidad }}</td>
                                                        <td>{{ $lista->tipo }}</td>
                                                        <td>{{ $lista->modo }}</td>
                                                        <td>{{ $lista->nameuser }}</td>
                                                        <td>{{ $lista->area }}</td>
                                                        <td><a class="mt-1 btn btn-sm btn-icon btn-danger d-flex align-items-center"
                                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                                title="ELIMINAR" data-original-title="Edit"
                                                                wire:click="$emit('eliminarGastoMicaja',{{ $lista->id }})">
                                                                <svg class="icon-20" width="20"
                                                                    viewBox="0 0 24 24" fill="none"
                                                                    xmlns="http://www.w3.org/2000/svg">
                                                                    <path opacity="0.4"
                                                                        d="M16.34 1.99976H7.67C4.28 1.99976 2 4.37976 2 7.91976V16.0898C2 19.6198 4.28 21.9998 7.67 21.9998H16.34C19.73 21.9998 22 19.6198 22 16.0898V7.91976C22 4.37976 19.73 1.99976 16.34 1.99976Z"
                                                                        fill="currentColor"></path>
                                                                    <path
                                                                        d="M15.0158 13.7703L13.2368 11.9923L15.0148 10.2143C15.3568 9.87326 15.3568 9.31826 15.0148 8.97726C14.6728 8.63326 14.1198 8.63426 13.7778 8.97626L11.9988 10.7543L10.2198 8.97426C9.87782 8.63226 9.32382 8.63426 8.98182 8.97426C8.64082 9.31626 8.64082 9.87126 8.98182 10.2123L10.7618 11.9923L8.98582 13.7673C8.64382 14.1093 8.64382 14.6643 8.98582 15.0043C9.15682 15.1763 9.37982 15.2613 9.60382 15.2613C9.82882 15.2613 10.0518 15.1763 10.2228 15.0053L11.9988 13.2293L13.7788 15.0083C13.9498 15.1793 14.1728 15.2643 14.3968 15.2643C14.6208 15.2643 14.8448 15.1783 15.0158 15.0083C15.3578 14.6663 15.3578 14.1123 15.0158 13.7703Z"
                                                                        fill="currentColor"></path>
                                                                </svg>
                                                            </a></td>
                                                    </tr>
                                                @endforeach
                                                <tr class="bg-gray">
                                                    <td>Total</td>
                                                    <td>{{ $pagado }}</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <div class="py-2 ml-2">
                                            {{ $gastoarealista->links() }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade @if ($opcion == 4) show active @endif" id="admin-caja"
                    role="tabpanel">
                    <div class="mt-2 border-0 shadow-sm card h-100">
                        <div class="text-center card-header">
                            <h3 class="mb-3 card-title fw-bold">
                                Registro de cierre de caja diario
                            </h3>
                            <div class="flex-wrap gap-3 d-flex justify-content-center align-items-center">
                                <div class="gap-2 d-flex align-items-center">
                                    <label for="fecha-inicio" class="mb-0 fw-semibold">Desde:</label>
                                    <input type="date" id="fecha-inicio" class="form-control form-control-sm"
                                        style="max-width: 150px;" wire:model="fechaInicioMes"
                                        onkeydown="return false">
                                </div>
                                <div class="gap-2 d-flex align-items-center ms-4">
                                    <label for="fecha-actual" class="mb-0 fw-semibold">Hasta:</label>
                                    <input type="date" id="fecha-actual" class="form-control form-control-sm"
                                        style="max-width: 150px;" wire:model="fechaActual" onkeydown="return false">
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="mt-3 d-flex justify-content-end">
                                <div style="font-size: 10px; max-width: 200px;">
                                    <label for="fecha-actual" class="mb-1 fw-semibold">Responsable:</label>
                                    <select class="form-control form-control-sm" wire:model="usuarioseleccionado"
                                        style="font-size: 10px;">
                                        <option value="">Todos</option>
                                        @foreach ($usuarios as $lista)
                                            <option value="{{ $lista->id }}">{{ $lista->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table mb-0 table-striped text-nowrap">
                                    <thead>
                                        <tr class="ligth">
                                            <th>QR Recibido</th>
                                            <th>Efectivo en caja</th>
                                            <th>Hora de cierre</th>
                                            <th>Fecha de cierre</th>
                                            <th>Sucursal</th>
                                            <th>Cerrado por </th>
                                            @if (Auth::user()->rol == 'Administrador')
                                                <th>Acción</th>
                                            @endif

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
                                            $pagado = 0;
                                        @endphp
                                        @foreach ($historial_caja as $lista)
                                            <tr>
                                                <td>{{ $lista->montoqr }}</td>
                                                <td>{{ $lista->montoefectivo }}</td>
                                                <td>{{ $lista->hora }}</td>
                                                <td>{{ $lista->fecha }}</td>
                                                <td>{{ $lista->sucursal }}</td>
                                                <td>{{ $lista->responsable }}</td>
                                                @if (Auth::user()->rol == 'Administrador')
                                                    <td> <a class="mt-1 btn btn-sm btn-icon btn-danger d-flex align-items-center"
                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                            title="ELIMINAR" data-original-title="Edit"
                                                            wire:click="$emit('eliminarCierreCajaFunc',{{ $lista->id }})">
                                                            <svg class="icon-20" width="20" viewBox="0 0 24 24"
                                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path opacity="0.4"
                                                                    d="M16.34 1.99976H7.67C4.28 1.99976 2 4.37976 2 7.91976V16.0898C2 19.6198 4.28 21.9998 7.67 21.9998H16.34C19.73 21.9998 22 19.6198 22 16.0898V7.91976C22 4.37976 19.73 1.99976 16.34 1.99976Z"
                                                                    fill="currentColor"></path>
                                                                <path
                                                                    d="M15.0158 13.7703L13.2368 11.9923L15.0148 10.2143C15.3568 9.87326 15.3568 9.31826 15.0148 8.97726C14.6728 8.63326 14.1198 8.63426 13.7778 8.97626L11.9988 10.7543L10.2198 8.97426C9.87782 8.63226 9.32382 8.63426 8.98182 8.97426C8.64082 9.31626 8.64082 9.87126 8.98182 10.2123L10.7618 11.9923L8.98582 13.7673C8.64382 14.1093 8.64382 14.6643 8.98582 15.0043C9.15682 15.1763 9.37982 15.2613 9.60382 15.2613C9.82882 15.2613 10.0518 15.1763 10.2228 15.0053L11.9988 13.2293L13.7788 15.0083C13.9498 15.1793 14.1728 15.2643 14.3968 15.2643C14.6208 15.2643 14.8448 15.1783 15.0158 15.0083C15.3578 14.6663 15.3578 14.1123 15.0158 13.7703Z"
                                                                    fill="currentColor"></path>
                                                            </svg>

                                                        </a></td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="py-2 ml-2">
                                {{ $historial_caja->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
