<div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <div class="col-md-12">
        <div class="card">
            <div class="mt-2 ml-4 mr-4 d-flex">
                <a wire:click="$set('botonRecepcion','pagos')"
                    class="mr-4 {{ $botonRecepcion === 'pagos' ? 'btn btn-warning' : 'btn btn-outline-primary' }}">
                    {{ __('INGRESOS / GASTOS') }}</a>
                <a wire:click="$set('botonRecepcion','citas')" type="button"
                    class="mr-4 {{ $botonRecepcion === 'citas' ? 'btn btn-warning' : 'btn btn-outline-primary' }}"
                    style="flex:1;">
                    <div style="display: flex;">
                        CITAS
                    </div>
                </a>
                <a wire:click="$set('botonRecepcion','llamadas')" type="button"
                    class="mr-4 {{ $botonRecepcion === 'llamadas' ? 'btn btn-warning' : 'btn btn-outline-primary' }}"
                    style="flex:1;">
                    <div style="display: flex;">
                        LLAMADAS
                    </div>
                </a>
                <a wire:click="$set('botonRecepcion','tratamientos')" type="button"
                    class="mr-4 {{ $botonRecepcion === 'tratamientos' ? 'btn btn-warning' : 'btn btn-outline-primary' }}"
                    style="flex:1;">
                    <div style="display: flex;">
                        TRAT./VENTAS
                    </div>
                </a>
                <a wire:click="$set('botonRecepcion','sucx')" type="button"
                    class="mr-4 {{ $botonRecepcion === 'tratamientossuc' ? 'btn btn-warning' : 'btn btn-outline-primary' }}"
                    style="flex:1;">
                    <div style="display: flex;">
                        TRAT. POR SUC.
                    </div>
                </a>
            </div>
            @if ($botonRecepcion == 'pagos')
                @php
                    $totalconfirmados = 0;
                    $totalasistidos = 0;
                @endphp
               
                <div class="" style="display: flex;">
                    @foreach ($areas as $index => $item)
                        @php

                            $confirmados = DB::table('registropagos')
                                ->where('sucursal', $item->area)
                                ->whereBetween('fecha', [$fechaInicioMes, $fechaActual])
                                ->distinct('idoperativo')
                                ->count();
                            $agendados = DB::table('operativos')
                                ->where('area', 'ilike', '%' . $item->area)
                                ->whereBetween('fecha', [$fechaInicioMes, $fechaActual])

                                ->count();

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
                                colors: ['#33FF74', '#FF5233'], // Colores para las dos opciones respectivamente
                                responsive: [{
                                    breakpoint: 510,
                                    options: {
                                        chart: {
                                            width: '100%', // Ancho del contenedor del gráfico ajustado al 100%
                                            height: 'auto' // Altura del gráfico ajustada automáticamente
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
                        document.addEventListener('DOMContentLoaded', function() {
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
                        });
                    </script>
                    @php
                        $index++;
                    @endphp
                @endforeach
                @foreach ($areas as $item)
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
                        document.addEventListener('DOMContentLoaded', function() {
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
                        });
                    </script>
                    @php
                        $index++;
                    @endphp
                @endforeach






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

                <div style="display:flex;">
                    <div style="width: 50%;">
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                setTimeout(function() {
                                    document.getElementById('myButton').click();
                                }, 1);
                            });
                        </script>
                        @livewire('estadistica.sucursal-diario')
                    </div>
                    <div style="width: 50%;">
                        @livewire('estadistica.sucursal-gasto-semanal')
                    </div>
                </div>
                <div style="display:flex;">
                    <div style="width: 50%;">
                        @livewire('estadistica.sucursal-semanal')
                    </div>
                    <div style="width: 50%;">
                        @livewire('estadistica.sucursal-mensual')
                    </div>
                </div>
                @livewire('estadistica.ingresodinamico')
                @livewire('estadistica.mes-general')
            @endif
            @if ($botonRecepcion == 'citas')
                <h3 class="mt-4">AGENDADOS/ASISTIDOS</h3>
                <div id="chartsucursalesagendados">
                </div>
                <script>
                    var agendadosData = @json($agendadoslist) || [0];
                    var confirmadosData = @json($confirmadoslist) || [0];
                    var diasDelMes = @json($diasDelMes) || ["Día 1"];


                    var options = {
                        series: [{
                            name: 'AGENDADOS',
                            data: agendadosData
                        }, {
                            name: 'ASISTIDOS',
                            data: confirmadosData
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
                            width: 2,
                            colors: ['transparent']
                        },
                        xaxis: {
                            categories: diasDelMes,
                        },
                        yaxis: {
                            title: {
                                text: '$ (thousands)'
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

                    var chart = new ApexCharts(document.querySelector("#chartsucursalesagendados"), options);
                    chart.render();
                </script>
                <div class="table-responsive">

                    <table id="user-list-table" class="table table-striped" role="grid" data-bs-toggle="data-table">
                        <thead>
                            <tr class="ligth">
                                <th>PROMEDIO AGENDADOS</th>
                                <th>PROMEDIO ASISTENCIA</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- <td>

                                @php
                                    $fechaInicial = date('Y-m-1');
                                    $fechaActual = date('Y-m-d');
                                    $datetimeInicial = new DateTime($fechaInicial);
                                    $datetimeActual = new DateTime($fechaActual);
                                    $diferencia = $datetimeInicial->diff($datetimeActual);
                                    $diasPasados = $diferencia->days;

                                    $suma = array_sum($agendadoslist);
                                    $cantidad = count($agendadoslist);
                                    // Calcula el promedio
                                    $promedio = $suma / $diasPasados + 1;
                                @endphp
                                {{ $promedio }}
                            </td>
                            <td>
                                @php
                                    $suma = array_sum($confirmadoslist);

                                    // Obtén la cantidad de elementos en el array
                                    $cantidad = count($confirmadoslist);

                                    // Calcula el promedio
                                    $promedio = $suma / $diasPasados;
                                @endphp
                                {{ $promedio }}
                            </td> --}}
                        </tbody>
                    </table>
                </div>
                <h3 class="mt-4">AGENDADOS/ASISTIDOS POR SUCURSAL</h3>
                <div id="chartsucursalesagendadossucu">
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
                            width: 2,
                            colors: ['transparent']
                        },
                        xaxis: {
                            categories: @json($areaslist),
                        },
                        yaxis: {
                            title: {
                                text: '$ (thousands)'
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
                    var chart = new ApexCharts(document.querySelector("#chartsucursalesagendadossucu"), options);
                    chart.render();
                </script>
                <h3 class="mt-4">AGENDADOS/ASISTIDOS POR USUARIOS</h3>
                <div id="chartsucursalesagendadosuser">
                </div>
                <script>
                    var options = {
                        series: [{
                            name: 'AGENDADOS',
                            data: @json($agendadoslistuser)
                        }, {
                            name: 'ASISTIDOS',
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
            @endif
            @if ($botonRecepcion == 'llamadas')
                @livewire('estadistica.llamadas-diario')
                <br>
                @livewire('estadistica.llamadas-semanal')
                <br>
                @livewire('estadistica.llamadas-semanal-agendados')
                <br>
                @livewire('estadistica.llamadas-semanal-asistidos')
            @endif
            @if ($botonRecepcion == 'tratamientos')

                <h3 class="mt-4">TOP TRATAMIENTOS(MES ACTUAL)</h3>
                <div id="charttratamientos"></div>
                <script>
                    var options = {
                        series: [{
                            data: @json($cantidades)
                        }],
                        chart: {
                            type: 'bar',
                            height: 2000
                        },
                        plotOptions: {
                            bar: {
                                borderRadius: 4,
                                borderRadiusApplication: 'end',
                                horizontal: true,
                            }
                        },
                        dataLabels: {
                            enabled: false
                        },
                        xaxis: {
                            categories: @json($nombretratamientos)
                        }
                    };
                    var chart = new ApexCharts(document.querySelector("#charttratamientos"), options);
                    chart.render();
                </script>
                @if (Auth::user()->rol == 'Administrador' || Auth::user()->rol == 'Contador')
                    <h3 class="mt-4">TOP TRATAMIENTOS INGRESO(MES ACTUAL)</h3>
                    <div id="charttratamientosganado"></div>
                    <script>
                        var options = {
                            series: [{
                                data: @json($cantidadesganado)
                            }],
                            chart: {
                                type: 'bar',
                                height: 2000
                            },
                            plotOptions: {
                                bar: {
                                    borderRadius: 4,
                                    borderRadiusApplication: 'end',
                                    horizontal: true,
                                }
                            },
                            dataLabels: {
                                enabled: false
                            },
                            xaxis: {
                                categories: @json($nombretratamientosganado)
                            }
                        };
                        var chart = new ApexCharts(document.querySelector("#charttratamientosganado"), options);
                        chart.render();
                    </script>
                @endif
                <h3 class="mt-4">TOP 10 PRODUCTOS MAS VENDIDOS(MES ACTUAL)</h3>
                <div id="chartproductos"></div>
                <style>
                    .apexcharts-xaxis-label {
                        font-size: 10px;
                        /* Ajusta el tamaño de la letra aquí */
                        font-family: 'Helvetica, Arial, sans-serif';
                        font-weight: bold;
                    }
                </style>
                <script>
                    var options = {
                        series: [{
                            data: @json($cantidadesproductos)
                        }],
                        chart: {
                            type: 'bar',
                            height: 800
                        },
                        plotOptions: {
                            bar: {
                                borderRadius: 4,
                                borderRadiusApplication: 'end',
                                horizontal: true,
                            }
                        },
                        dataLabels: {
                            enabled: false
                        },
                        xaxis: {
                            categories: @json($nombreproductos),
                        }
                    };
                    var chart = new ApexCharts(document.querySelector("#chartproductos"), options);
                    chart.render();
                </script>
                <h3 class="mt-4">TOP VENDODORAS DEL MES(CANTIDAD DE VENTAS)</h3>
                <div id="chartproductosvendidos"></div>
                <script>
                    var options = {
                        series: [{
                            data: @json($cantidadvendido)
                        }],
                        chart: {
                            type: 'bar',
                            height: 350
                        },
                        plotOptions: {
                            bar: {
                                borderRadius: 4,
                                borderRadiusApplication: 'end',
                                horizontal: true,
                            }
                        },
                        dataLabels: {
                            enabled: false
                        },
                        xaxis: {
                            categories: @json($nombrevendedoras),
                        }
                    };
                    var chart = new ApexCharts(document.querySelector("#chartproductosvendidos"), options);
                    chart.render();
                </script>
                <h3 class="mt-4">TOP VENDEDORAS (MAYOR INGRESO)DEL MES</h3>
                <div id="chartproductosvendidosingreso"></div>
                <script>
                    var options = {
                        series: [{
                            data: @json($cantidadmasvendido)
                        }],
                        chart: {
                            type: 'bar',
                            height: 350
                        },
                        plotOptions: {
                            bar: {
                                borderRadius: 4,
                                borderRadiusApplication: 'end',
                                horizontal: true,
                            }
                        },
                        dataLabels: {
                            enabled: false
                        },
                        xaxis: {
                            categories: @json($nombremasobtenido),
                        }
                    };
                    var chart = new ApexCharts(document.querySelector("#chartproductosvendidosingreso"), options);
                    chart.render();
                </script>
            @endif
            @if ($botonRecepcion == 'sucx')
                <div class="row">
                    @foreach ($areas as $index => $item)
                        @php
                            $resultadostratamientos = DB::table('historial_clientes')
                                ->select(
                                    'historial_clientes.nombretratamiento',
                                    DB::raw('COUNT(DISTINCT historial_clientes.idcliente) as total_ingreso'),
                                )
                                ->join(
                                    'operativos',
                                    DB::raw('CAST(historial_clientes.idoperativo AS INTEGER)'),
                                    '=',
                                    'operativos.id',
                                )
                                ->whereBetween('historial_clientes.fecha', [$this->fechaInicioMes, $this->fechaActual])
                                ->where('historial_clientes.nombretratamiento', '!=', 'CONSULTA')
                                ->where('operativos.area', $item->area)
                                ->groupBy('historial_clientes.nombretratamiento')
                                ->orderBy('total_ingreso', 'desc')
                                ->get();

                        @endphp
                        <div class="cold-md-2">
                            <div class="table-responsive">
                                <table class="table mb-0 table-striped text-nowrap">
                                    <thead>
                                        <tr>
                                            <th style="font-size: 9px">{{ $item->area }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($resultadostratamientos as $resultado)
                                            <tr style="border: 1px solid black;">
                                                <td style="font-size: 9px">
                                                    {{ $resultado->nombretratamiento }}
                                                </td>
                                                <td style="font-size: 9px">
                                                    {{ $resultado->total_ingreso }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
