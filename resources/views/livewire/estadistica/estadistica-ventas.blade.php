<div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    {{-- <div class="card" style="margin:3%;">
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
    </div> --}}

    <div class="card" style="margin:3%;">
        <div class="card-body">
            @livewire('estadistica.ingresos-venta-mes')




            @php
                $index = 0;
            @endphp
            @foreach ($areas as $item)
                @php
                    $sumaingresosdia = [];
                    foreach ($this->diasennumero as $dia) {
                        $suma = 0;
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
                <h3 class="mt-4">INGRESOS DE VENTAS : ({{ $item->area }})</h3>
                <div id="chartsucursalesdia{{ $index }}" wire:ignore></div>
                <script>
                    var options = {
                        series: [{
                            name: 'PRODUCTOS',
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
            <h3 class="mt-4">TOP TRATAMIENTOS ({{ $mes }})</h3>
            <div id="charttratamientos" wire:ignore></div>
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
            <h3 class="mt-4">TOP TRATAMIENTOS INGRESO ({{ $mes }})</h3>
            <div id="charttratamientosganado" wire:ignore></div>
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
            <h3 class="mt-4">TOP 10 PRODUCTOS MAS VENDIDOS ({{ $mes }})</h3>
            <div id="chartproductos" wire:ignore></div>
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
            <h3 class="mt-4">TOP VENDODORAS DE {{ $mes }} (CANTIDAD DE VENTAS)</h3>
            <div id="chartproductosvendidos" wire:ignore></div>
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
            <h3 class="mt-4">TOP VENDEDORAS (MAYOR INGRESO)DE {{ $mes }}</h3>
            <div id="chartproductosvendidosingreso" wire:ignore></div>
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

            @php
                $promediosTotales = [];
                $labels = [];

                for ($i = 1; $i <= $mesActual; $i++) {
                    $totalMes = 0;
                    $fechaInicio = date('Y-m-d', mktime(0, 0, 0, $i, 1, $anioActual));
                    $fechaFin = date('Y-m-t', mktime(0, 0, 0, $i, 1, $anioActual));
                    $diasDelMes = cal_days_in_month(CAL_GREGORIAN, $i, $anioActual);

                    foreach ($areas as $lista) {
                        $suma = DB::table('registroinventarios')
                            ->where('sucursal', $lista->area)
                            ->whereIn('modo', ['efectivo', 'qr'])
                            ->whereBetween('fecha', [$fechaInicio, $fechaFin])
                            ->whereIn('motivo', ['compra', 'farmacia'])
                            ->sum(DB::raw('CAST(precio AS DECIMAL(10,2))'));

                        $totalMes += $suma;
                    }

                    $labels[] = strtoupper($meses_es[$i]);
                    $promediosTotales[] = round($totalMes / $diasDelMes, 2);
                }
            @endphp
            {{-- <label for="">GRAFICOS</label>
            <div id="graficopromediosmensuales" class="mt-4" wire:ignore></div>
            <script>
                var labels = @json($labels);
                var dataPromedios = @json($promediosTotales);

                var optionsPromedios = {
                    chart: {
                        type: 'line',
                        height: 350,
                        zoom: {
                            enabled: true
                        },
                        toolbar: {
                            show: true
                        }
                    },
                    series: [{
                        name: 'Promedio Diario por Mes',
                        data: dataPromedios
                    }],
                    xaxis: {
                        categories: labels,
                        title: {
                            text: 'Meses'
                        }
                    },
                    yaxis: {
                        title: {
                            text: 'Promedio (Bs)'
                        }
                    },
                    stroke: {
                        curve: 'smooth',
                        width: 3
                    },
                    markers: {
                        size: 5
                    },
                    colors: ['#00b894'],
                    title: {
                        text: 'Promedios de Ventas Mensuales por Día',
                        align: 'left',
                        style: {
                            fontSize: '18px'
                        }
                    },
                    tooltip: {
                        y: {
                            formatter: val => `Bs. ${val.toFixed(2)}`
                        }
                    }
                };

                const chartPromedios = new ApexCharts(document.querySelector("#graficopromediosmensuales"), optionsPromedios);
                chartPromedios.render();
            </script> --}}


        </div>
    </div>

</div>
