<div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
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

            <div style="display: flex; flex-wrap: wrap; gap: 20px;">
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
            </div>
            <div class="ml-4">
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
            </div>
            <div class="table-responsive">

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
            </div>
            <h3 class="mt-4">AGENDADOS/ASISTIDOS POR SUCURSALES</h3>
            @php
                $index = 0;
            @endphp
            @foreach ($areas as $area)
                <div class="mt-5">
                    <h5 class="text-primary">{{ strtoupper($area->area) }}</h5>
                    <div id="chart_area_{{ $index }}"></div>
                    <script>
                        var options = {
                            series: [{
                                name: 'AGENDADOS',
                                data: @json($agendadosPorArea[$area->area] ?? [])
                            }, {
                                name: 'ASISTIDOS',
                                data: @json($confirmadosPorArea[$area->area] ?? [])
                            }],
                            chart: {
                                type: 'bar',
                                height: 350,
                                width: '100%'
                            },
                            plotOptions: {
                                bar: {
                                    horizontal: false,
                                    columnWidth: '55%',
                                    endingShape: 'rounded'
                                },
                            },
                            dataLabels: {
                                enabled: false
                            },
                            stroke: {
                                show: true,
                                width: 1,
                                colors: ['#fff']
                            },
                            xaxis: {
                                categories: @json($diasDelMes),
                            },
                            fill: {
                                opacity: 1
                            },
                            tooltip: {
                                y: {
                                    formatter: function(val) {
                                        return val;
                                    }
                                }
                            }
                        };

                        var chart = new ApexCharts(
                            document.querySelector("#chart_area_{{ $index }}"), options);
                        chart.render();
                    </script>
                    @php
                        $index++;
                    @endphp
                </div>
            @endforeach
            <h3 class="mt-4">AGENDADOS Y ASISTIDOS POR SUCURSAL(ESTADISTICA MES ACTUAL)</h3>
            <div id="agendadosporsucursal">
            </div>
            <script>
                var options = {
                    series: [{
                        name: 'AGENDADOS',
                        data: @json($citasagendas)
                    }, {
                        name: 'ASISTIDOS',
                        data: @json($confirmadossucu)
                    }],

                    chart: {
                        type: 'bar',
                        height: 350
                    },
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            columnWidth: '55%',
                            endingShape: 'rounded'
                        },
                    },
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        show: true,
                        width: 1,
                        colors: ['#fff']
                    },
                    xaxis: {
                        categories: @json($areaslist),
                    },
                    fill: {
                        opacity: 1
                    },
                    tooltip: {
                        y: {
                            formatter: function(val) {
                                return val
                            }
                        }
                    }
                };
                var chart = new ApexCharts(document.querySelector("#agendadosporsucursal"), options);
                chart.render();
            </script>
            <h3 class="mt-4">AGENDADOS/ASISTIDOS</h3>
            <div id="chartsucursalesagendados">
            </div>
            <script>
                var options = {
                    series: [{
                        name: 'AGENDADOS',
                        data: @json($agendadoslist)
                    }, {
                        name: 'ASISTIDOS',
                        data: @json($confirmadoslist)
                    }],
                    chart: {
                        type: 'bar',
                        height: 350,
                        width: '100%'
                    },
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            columnWidth: '55%',
                            endingShape: 'rounded'
                        },
                    },
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        show: true,
                        width: 1,
                        colors: ['#fff']
                    },
                    xaxis: {
                        categories: @json($diasDelMes),
                    },

                    fill: {
                        opacity: 1
                    },
                    tooltip: {
                        y: {
                            formatter: function(val) {
                                return val
                            }
                        }
                    }
                };

                var chart = new ApexCharts(document.querySelector("#chartsucursalesagendados"), options);
                chart.render();
            </script>

            <h3 class="mt-4">AGENDADOS Y ATENDIDOS POR USUARIOS</h3>
            <div id="chartsucursalesagendadosuser">
            </div>
            <script>
                var options = {
                    series: [{
                        name: 'AGENDADOS',
                        data: @json($agendadoslistuser)
                    }, {
                        name: 'ATENDIDOS',
                        data: @json($confirmadoslistuser)
                    }],
                    chart: {
                        type: 'bar',
                        height: 350
                    },
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            columnWidth: '55%',
                            endingShape: 'rounded'
                        },
                    },
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        show: true,
                        width: 2,
                        colors: ['transparent']
                    },
                    xaxis: {
                        categories: @json($usuarioslist),
                    },
                    yaxis: {
                        title: {
                            text: ''
                        }
                    },
                    fill: {
                        opacity: 1
                    },
                    tooltip: {
                        y: {
                            formatter: function(val) {
                                return val
                            }
                        }
                    }
                };

                var chart = new ApexCharts(document.querySelector("#chartsucursalesagendadosuser"), options);
                chart.render();
            </script>
            @livewire('estadistica.citas-semanal')
            @livewire('estadistica.citas-semanal-agendados')
        </div>
    </div>
</div>
