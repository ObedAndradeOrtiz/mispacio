<div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <style>
        /* Estilo general para la tabla */
        .table-responsive {
            max-width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
            padding: 0;
        }

        th,
        td {
            padding: 12px 15px;
            text-align: left;
            vertical-align: middle;
            border-bottom: 1px solid #ddd;
        }

        /* Estilo para las cabeceras de las columnas */
        th {
            background-color: #4CAF50;
            /* Color de fondo de la cabecera */
            color: white;
            /* Color del texto */
            font-weight: bold;
            text-transform: uppercase;
        }

        /* Estilo para las filas de la tabla */
        tr:nth-child(even) {
            background-color: #f9f9f9;
            /* Color alternado para las filas */
        }

        tr:hover {
            background-color: #f1f1f1;
            /* Color de fondo al pasar el mouse */
        }

        /* Estilo de las celdas */
        td {
            color: #302f2f;
        }

        /* Agregar un borde redondeado a la tabla */
        table {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.342);
            /* Sombra para darle profundidad */
        }

        /* Media queries para dispositivos móviles */
        @media (max-width: 767px) {

            /* Hacemos que las celdas se apilen en lugar de estar en una fila */
            table,
            th,
            td {
                display: block;
                width: 100%;
            }

            th {
                text-align: right;
            }

            td {
                text-align: right;
                padding-left: 50%;
                position: relative;
            }

            td::before {
                content: attr(data-label);
                position: absolute;
                left: 10px;
                top: 50%;
                transform: translateY(-50%);
                font-weight: bold;
                color: #555;
            }
        }
    </style>
    @php
        $totalconfirmados = 0;
        $totalasistidos = 0;
    @endphp
    <div class="card" style="margin:3%;">
        <div class="card-body">

            <div class="px-4 row align-items-center" style="gap: 15px;">


                <!-- Fecha Inicio -->
                <div class="col-md-auto">
                    <label for="fecha-inicio" class="form-label">Desde:</label>
                    <input type="date" id="fecha-inicio" class="form-control" wire:model="fechaDesde"
                        style="width: 120px; font-size: 12px;">
                </div>

                <!-- Fecha Fin -->
                <div class="col-md-auto">
                    <label for="fecha-actual" class="form-label">Hasta:</label>
                    <input type="date" id="fecha-actual" class="form-control" wire:model="fechaHasta"
                        style="width: 120px; font-size: 12px;">
                </div>
                <div class="col-md-auto">
                    <label class="form-label">Año:</label>
                    <select class="form-control" wire:model="año" style="width:90px; font-size:12px;">
                        @for ($i = date('Y'); $i >= 2023; $i--)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                </div>

                <!-- Sucursal -->
                <div class="col-md-auto">

                    <label for="" class="btn btn-primary" wire:click='actualizarFecha'>Cambiar fecha</label>
                </div>

            </div>

        </div>
    </div>
    <div class="card" style="margin:3%;">
        <div class="card-body">


            @php
                $totalconfirmados = 0;
                $totalasistidos = 0;
            @endphp

            {{-- <div style="display: flex; flex-wrap: wrap; gap: 20px;">

                @foreach ($areas as $index => $item)
                    @php
                        $confirmados = DB::table('registropagos')
                            ->where('sucursal', 'ilike', '%' . $item->area)
                            ->whereBetween('fecha', [$fechaInicioMes, $fechaActual])
                            ->distinct('idoperativo')
                            ->count();
                        $agendados =
                            DB::table('operativos')
                                ->where('area', 'ilike', '%' . $item->area)
                                ->whereBetween('fecha', [$fechaInicioMes, $fechaActual])
                                ->count() - $confirmados;
                        $totalconfirmados = $totalconfirmados + $agendados;
                        $totalasistidos = $totalasistidos + $confirmados;
                    @endphp
                    <div class="mt-4">
                        <h3 style="color: black;">{{ $item->area }}</h3>
                        <div id="chart{{ $index }}" class="mt-4">

                        </div>
                    </div>

                    <script>
                        var options{{ $index }} = {
                            series: [@json($confirmados), @json($agendados)],
                            chart: {
                                type: 'pie',
                            },
                            labels: ['ASISTIDOS', 'NO ASISTIDOS'],
                            colors: ['#33FF74', '#FF5233'],
                            responsive: [{
                                breakpoint: 510,
                                options: {
                                    chart: {
                                        width: '100%',
                                        height: 'auto'
                                    },
                                    legend: {
                                        position: 'bottom' // Cambiar la posición de la leyenda en pantallas pequeñas
                                    }
                                }
                            }]
                        };
                        var chart{{ $index }} = new ApexCharts(document.getElementById("chart{{ $index }}"),
                            options{{ $index }});
                        chart{{ $index }}.render();
                    </script>
                @endforeach
            </div> --}}
            {{-- <div class="ml-4">
                <h3 style="color:black;">TOTAL PACIENTES</h3>
                <div class="flex-wrap mt-3" style="display: flex;">
                    <div id="chartx">

                    </div>
                    <script>
                        var options = {
                            series: [@json($totalasistidos), @json($totalconfirmados)],
                            chart: {
                                width: 380,
                                type: 'pie',
                            },
                            labels: ['ASISTIDOS', 'NO ASISTIDOS'],
                            colors: ['#33FF74', '#FF5233'], // Colores para las dos opciones respectivamente
                            responsive: [{
                                breakpoint: 480,
                                options: {
                                    chart: {
                                        width: 200
                                    },
                                    legend: {
                                        position: 'left'
                                    }
                                }
                            }]
                        };
                        var chartx = new ApexCharts(document.getElementById("chartx"),
                            options);
                        chartx.render();
                    </script>
                </div>
            </div> --}}
            {{-- <div class="table-responsive">

                <table id="user-list-table" class="table table-striped" role="grid" data-bs-toggle="data-table">
                    <thead>
                        <tr class="ligth">
                            <th>CITAS ASISTIDAS</th>
                            <th>CITAS NO ASISTIDAS</th>
                            <th>TOTAL</th>
                            <th>PROMEDIO DE ASISTENCIA DIARIA</th>
                        </tr>
                    </thead>
                    <tbody>
                        <td>
                            {{ $totalasistidos }}
                        </td>
                        <td>
                            {{ $totalconfirmados }}
                        </td>
                        <td>
                            {{ $totalasistidos + $totalconfirmados }}
                        </td>
                        @php
                            $primer_dia_mes_actual = date('Y-m-01');

                            // Obtener la fecha actual
                            $fecha_actual = date('Y-m-d');

                            // Calcular la diferencia en segundos entre las dos fechas
                            $diferencia_segundos = strtotime($fecha_actual) - strtotime($primer_dia_mes_actual);

                            // Convertir la diferencia de segundos a días
                            $dias_pasados = floor($diferencia_segundos / (60 * 60 * 24));
                            if ($dias_pasados == 0) {
                                $dias_pasados = 1;
                            }
                        @endphp
                        <td>

                            {{ $totalasistidos / $dias_pasados }}



                        </td>
                    </tbody>
                </table>
            </div> --}}
            <h3 class="mt-4">INGRESOS CITAS/PRODUCTOS</h3>
            <div id="chartline">
            </div>
            <h3 class="mt-4">INGRESOS CITAS+PRODUCTOS</h3>
            <div id="chartsucursalesdia"></div>
            <script>
                var options = {
                    series: [{
                        name: 'CITAS/PRODUCTOS',
                        group: 'ingresos',
                        data: @json($sumaingresosdia)
                    }],
                    chart: {
                        type: 'bar',
                        height: 450,
                        stacked: true,
                    },
                    plotOptions: {
                        bar: {
                            horizontal: false
                        }
                    },
                    stroke: {
                        width: 1,
                        colors: ['#fff']
                    },

                    xaxis: {
                        categories: @json($diasDelMes)

                    },
                    fill: {
                        opacity: 1
                    },
                    colors: ['#80c7fd', '#008FFB', '#80f1cb', '#00E396'],

                    legend: {
                        position: 'top',
                        horizontalAlign: 'left'
                    }
                };

                var chart = new ApexCharts(document.querySelector("#chartsucursalesdia"), options);
                chart.render();
            </script>
            @php
                $index = 0;
            @endphp
            @foreach ($areas as $item)
                @php
                    $sumaingresosdia = []; // Reseteamos la variable en cada iteración
                    foreach ($this->diasennumero as $dia) {
                        $registros = DB::table('registropagos')
                            ->where('sucursal', $item->area)
                            ->where('fecha', $dia)
                            ->get();
                        $suma = 0;
                        foreach ($registros as $registro) {
                            $suma += (float) $registro->monto;
                        }

                        $registros = DB::table('registroinventarios')
                            ->where('sucursal', $item->area)
                            ->where('fecha', $dia)
                            ->where(function ($query) {
                                $query->where('motivo', 'compra')->orWhere('motivo', 'farmacia');
                            })
                            ->get();
                        foreach ($registros as $registro) {
                            $suma += (float) $registro->precio;
                        }
                        $sumaingresosdia[] = $suma;
                    }
                @endphp
                <h3 class="mt-4">INGRESOS CITAS+PRODUCTOS ({{ $item->area }})</h3>
                <div id="chartsucursalesdia{{ $index }}"></div>
                <script>
                    var options = {
                        series: [{
                            name: 'CITAS/PRODUCTOS',
                            group: 'ingresos',
                            data: @json($sumaingresosdia)
                        }],
                        chart: {
                            type: 'bar',
                            height: 450,
                            stacked: true,
                        },
                        plotOptions: {
                            bar: {
                                horizontal: false
                            }
                        },
                        stroke: {
                            width: 1,
                            colors: ['#fff']
                        },
                        xaxis: {
                            categories: @json($diasDelMes)
                        },
                        fill: {
                            opacity: 1
                        },
                        colors: ['#80c7fd', '#008FFB', '#80f1cb', '#00E396'],
                        legend: {
                            position: 'top',
                            horizontalAlign: 'left'
                        }
                    };

                    var chart = new ApexCharts(document.querySelector("#chartsucursalesdia{{ $index }}"), options);
                    chart.render();
                </script>
                @php
                    $index++;
                @endphp
            @endforeach
            {{-- @foreach ($areas as $item)
                @php
                    $sumaingresosnoche = [];
                    $dias = 0;
                    $sumadia = 0;
                    $sumanoche = 0;
                    $sumaingresosdia = []; // Reseteamos la variable en cada iteración
                    foreach ($this->diasennumero as $dia) {
                        $suma = 0;
                        $registros = DB::table('registroinventarios')
                            ->whereRaw('EXTRACT(HOUR FROM created_at) = 0')
                            ->whereRaw('EXTRACT(MINUTE FROM created_at) = 0')
                            ->whereRaw('EXTRACT(SECOND FROM created_at) = 0')
                            ->orWhere(function ($query) {
                                $query->whereRaw('EXTRACT(HOUR FROM created_at) < 14')->orWhere(function ($query) {
                                    $query
                                        ->whereRaw('EXTRACT(HOUR FROM created_at) = 14')
                                        ->whereRaw('EXTRACT(MINUTE FROM created_at) <= 14');
                                });
                            })
                            ->where('sucursal', $item->area)
                            ->where('fecha', $dia)
                            ->where(function ($query) {
                                $query->where('motivo', 'compra')->orWhere('motivo', 'farmacia');
                            })
                            ->get();
                        foreach ($registros as $registro) {
                            $suma += (float) $registro->precio;
                        }
                        $dias++;
                        $sumaingresosdia[] = $suma;
                        $sumadia = $sumadia + $suma;
                    }
                    foreach ($this->diasennumero as $dia) {
                        $suma = 0;
                        $registros = DB::table('registroinventarios')
                            ->whereRaw('EXTRACT(HOUR FROM created_at) = 14')
                            ->whereRaw('EXTRACT(MINUTE FROM created_at) = 14')
                            ->whereRaw('EXTRACT(SECOND FROM created_at) = 59')
                            ->orWhere(function ($query) {
                                $query->whereRaw('EXTRACT(HOUR FROM created_at) > 14')->orWhere(function ($query) {
                                    $query
                                        ->whereRaw('EXTRACT(HOUR FROM created_at) = 14')
                                        ->whereRaw('EXTRACT(MINUTE FROM created_at) >= 14');
                                });
                            })
                            ->where('sucursal', $item->area)
                            ->where('fecha', $dia)
                            ->where(function ($query) {
                                $query->where('motivo', 'compra')->orWhere('motivo', 'farmacia');
                            })
                            ->get();
                        foreach ($registros as $registro) {
                            $suma += (float) $registro->precio;
                        }
                        $sumaingresosnoche[] = $suma;
                        $sumanoche = $sumanoche + $suma;
                    }

                    $promediodia = $sumadia / $dias;
                    $promediodia = round($promediodia, 2);
                    $promedionoche = $sumanoche / $dias;
                    $promedionoche = round($promedionoche, 2);
                    $suma = 0;
                    $sumadiaanual = 0;
                    $sumanocheanual = 0;
                    $registros = DB::table('registroinventarios')
                        ->whereRaw('EXTRACT(HOUR FROM created_at) = 0')
                        ->whereRaw('EXTRACT(MINUTE FROM created_at) = 0')
                        ->whereRaw('EXTRACT(SECOND FROM created_at) = 0')
                        ->orWhere(function ($query) {
                            $query->whereRaw('EXTRACT(HOUR FROM created_at) < 14')->orWhere(function ($query) {
                                $query
                                    ->whereRaw('EXTRACT(HOUR FROM created_at) = 14')
                                    ->whereRaw('EXTRACT(MINUTE FROM created_at) <= 14');
                            });
                        })
                        ->where('sucursal', $item->area)
                        ->where(function ($query) {
                            $query->where('motivo', 'compra')->orWhere('motivo', 'farmacia');
                        })
                        ->get();
                    foreach ($registros as $registro) {
                        $suma += (float) $registro->precio;
                    }
                    $startDate = new DateTime('2024-03-19');
                    $currentDate = new DateTime();
                    $interval = $startDate->diff($currentDate);
                    $promedioanualdia = $suma / $interval->days;
                    $promedioanualdia = round($promedioanualdia, 2);

                    $suma = 0;
                    $registros = DB::table('registroinventarios')
                        ->whereRaw('EXTRACT(HOUR FROM created_at) = 14')
                        ->whereRaw('EXTRACT(MINUTE FROM created_at) = 14')
                        ->whereRaw('EXTRACT(SECOND FROM created_at) = 59')
                        ->orWhere(function ($query) {
                            $query->whereRaw('EXTRACT(HOUR FROM created_at) > 14')->orWhere(function ($query) {
                                $query
                                    ->whereRaw('EXTRACT(HOUR FROM created_at) = 14')
                                    ->whereRaw('EXTRACT(MINUTE FROM created_at) >= 14');
                            });
                        })
                        ->where('sucursal', $item->area)
                        ->where(function ($query) {
                            $query->where('motivo', 'compra')->orWhere('motivo', 'farmacia');
                        })
                        ->get();
                    foreach ($registros as $registro) {
                        $suma += (float) $registro->precio;
                    }
                    $promedioanualnoche = $suma / $interval->days;
                    $promedioanualnoche = round($promedioanualnoche, 2);

                @endphp
                <h3 class="mt-4">INGRESOS PRODUCTOS ({{ $item->area }})</h3>
                <h1>PROMEDIO DEL VENTAS EN LA MAÑANA (MES ACTUAL): {{ $promediodia }}</h1>
                <h1>PROMEDIO DEL VENTAS EN LA TARDE (MES ACTUAL): {{ $promedionoche }}</h1>
                <h1>PROMEDIO DEL VENTAS EN LA MAÑANA (ANUAL): {{ $promedioanualdia }}</h1>
                <h1>PROMEDIO DEL VENTAS EN LA TARDE (ANUAL): {{ $promedioanualnoche }}</h1>
                <div id="chartsucursalesdia{{ $index }}"></div>
                <script>
                    var options = {
                        series: [{
                            name: 'DIA',
                            group: 'ingresos',
                            data: @json($sumaingresosdia)
                        }, {
                            name: 'TARDE',
                            group: 'ingresos',
                            data: @json($sumaingresosnoche)
                        }, ],

                        chart: {
                            type: 'bar',
                            height: 450,
                            stacked: true,
                        },
                        plotOptions: {
                            bar: {
                                horizontal: false
                            }
                        },
                        stroke: {
                            width: 1,
                            colors: ['#fff']
                        },
                        xaxis: {
                            categories: @json($diasDelMes)
                        },
                        fill: {
                            opacity: 1
                        },
                        colors: ['#80c7fd', '#008FFB', '#80f1cb', '#00E396'],
                        legend: {
                            position: 'top',
                            horizontalAlign: 'left'
                        }
                    };

                    var chart = new ApexCharts(document.querySelector("#chartsucursalesdia{{ $index }}"), options);
                    chart.render();
                </script>
                @php
                    $index++;
                @endphp
            @endforeach --}}
            <h3 class="mt-4">INGRESOS ANUAL</h3>
            <div id="chartingresoanual"></div>
            <script>
                var options = {
                    series: [{
                        name: 'CITAS/PRODUCTOS',
                        group: 'ingresos',
                        data: @json($totalesPorMes)
                    }],
                    chart: {
                        type: 'bar',
                        height: 450,
                        stacked: true,
                    },
                    plotOptions: {
                        bar: {
                            horizontal: false
                        }
                    },
                    stroke: {
                        width: 1,
                        colors: ['#fff']
                    },

                    xaxis: {
                        categories: @json($meses)

                    },
                    fill: {
                        opacity: 1
                    },
                    colors: ['#80c7fd', '#008FFB', '#80f1cb', '#00E396'],

                    legend: {
                        position: 'top',
                        horizontalAlign: 'left'
                    }
                };

                var chart = new ApexCharts(document.querySelector("#chartingresoanual"), options);
                chart.render();
            </script>
            <div class="table-responsive">

                <table id="user-list-table" class="table table-striped" role="grid" data-bs-toggle="data-table">
                    <thead>
                        <tr class="ligth">
                            <th>INGRESO PRODUCTOS</th>
                            <th>INGRESO POR CITAS</th>
                            <th>TOTAL</th>
                            <th>PROMEDIO INGRESO PRODUCTOS</th>
                            <th>PROMEDIO INGRESO CITAS</th>
                            <th>PROMEDIO INGRESO</th>
                        </tr>
                    </thead>
                    <tbody>
                        <td>
                            {{ array_sum($sumafarmacia) }}
                        </td>
                        <td>
                            {{ array_sum($sumaingresos) }}
                        </td>
                        <td>
                            {{ array_sum($sumafarmacia) + array_sum($sumaingresos) }}
                        </td>
                        @php
                            $primer_dia_mes_actual = date('Y-m-01');

                            // Obtener la fecha actual
                            $fecha_actual = date('Y-m-d');

                            // Calcular la diferencia en segundos entre las dos fechas
                            $diferencia_segundos = strtotime($fecha_actual) - strtotime($primer_dia_mes_actual);

                            // Convertir la diferencia de segundos a días
                            $dias_pasados = floor($diferencia_segundos / (60 * 60 * 24));
                            if ($dias_pasados == 0) {
                                $dias_pasados = 1;
                            }
                        @endphp
                        <td>
                            {{ array_sum($sumafarmacia) / $dias_pasados }}
                        </td>
                        <td>
                            {{ array_sum($sumaingresos) / $dias_pasados }}
                        </td>
                        <td>
                            {{ (array_sum($sumafarmacia) + array_sum($sumaingresos)) / $dias_pasados }}
                        </td>
                    </tbody>
                </table>
            </div>
            <script>
                var options = {
                    series: [{
                        name: 'CITAS',
                        group: 'ingresos',
                        data: @json($sumaingresos)
                    }, {
                        name: 'PRODUCTOS',
                        group: 'ingresos',
                        data: @json($sumafarmacia)
                    }, ],
                    chart: {
                        type: 'bar',
                        height: 450,
                        stacked: true,
                    },
                    stroke: {
                        width: 1,
                        colors: ['#fff']
                    },
                    plotOptions: {
                        bar: {
                            horizontal: false
                        }
                    },
                    xaxis: {
                        categories: @json($areaslist)

                    },
                    fill: {
                        opacity: 1
                    },
                    colors: ['#80c7fd', '#008FFB', '#80f1cb', '#00E396'],

                    legend: {
                        position: 'top',
                        horizontalAlign: 'left'
                    }
                };

                var chart = new ApexCharts(document.querySelector("#chartline"), options);
                chart.render();
            </script>
        </div>
    </div>
</div>
