<div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
    <h4 class="mt-4 ml-2">LLAMADAS REALIZADAS/ AGENDADAS / ASITENCIA(DIARIO)</h4>
    <div id="chartsucursalesagendadosuser">
    </div>
    <script>
        var options = {
            series: [{
                    name: 'AGENDADOS',
                    data: @json($agendados)
                }, {
                    name: 'ASISTIDOS',
                    data: @json($asistidos)
                },
                {
                    name: 'LLAMADAS',
                    data: @json($llamadas)
                },
                {
                    name: 'REMARKETING',
                    data: @json($remarketing)
                },
            ],
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
    <h4 class="mt-4 ml-2" style="margin-top: 5%;">LLAMADAS REALIZADAS/ AGENDADAS / ASITENCIA(MES ACTUAL)</h4>
    <div id="densityCanvasllamames">
    </div>
    <script>
        var options = {
            series: [{
                    name: 'AGENDADOS',
                    data: @json($mesagendados)
                }, {
                    name: 'ASISTIDOS',
                    data: @json($mesasistidos)
                },
                {
                    name: 'LLAMADAS',
                    data: @json($mesllamadas)
                },
                {
                    name: 'REMARKETING',
                    data: @json($mesremarketing)
                },
            ],
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

        var chart = new ApexCharts(document.querySelector("#densityCanvasllamames"), options);
        chart.render();
    </script>


    <div class="table-responsive">

        <table id="user-list-table" class="table table-striped" role="grid" data-bs-toggle="data-table">
            <thead>
                {{-- <tr class="ligth">
                    <th>AGENDADOS <br> (POR <br> LLAMADAS)</th>
                    <th>AGENDADOS <br> (ACTIVOS)</th>
                    <th>AGENDADOS <br> (ELIMINADOS)</th>
                    <th>AGENDADOS <br> (ASISTIDOS)</th>
                    <th>INGRESO <br> (ASISTIDOS)</th>
                </tr> --}}
            </thead>
            <tbody>
                {{-- <td>
                    @php
                        $promedio_1 = DB::table('calls')
                            ->whereBetween('fecha', [$this->fechaInicioMes, $this->fechaActual])
                            ->where('estado', 'Pendiente')
                            ->count();
                    @endphp
                    {{ $promedio_1 }}
                </td>
                <td>
                    @php
                        $promedio_2 = DB::table('operativos')
                            ->join('calls', 'operativos.idllamada', '=', 'calls.id')
                            ->whereBetween('operativos.fecha', [$this->fechaInicioMes, $this->fechaFinMes])
                            ->whereBetween('calls.fecha', [$this->fechaInicioMes, $this->fechaFinMes])
                            ->count();
                    @endphp
                    {{ $promedio_2 }}
                </td>
                <td>
                    {{ $promedio_1 - $promedio_2 }}
                </td> --}}
                {{-- <td>
                    @php
                        $promedio_3 = DB::table('operativos')
                            ->join('calls', 'operativos.idllamada', '=', 'calls.id')
                            ->whereBetween('operativos.fecha', [$this->fechaInicioMes, $this->fechaFinMes])
                            ->whereBetween('calls.fecha', [$this->fechaInicioMes, $this->fechaFinMes])
                            ->join('registropagos', 'operativos.id', '=', 'registropagos.idoperativo')
                            ->count();
                    @endphp
                    {{ $promedio_3 . ' (' . ($promedio_3 * 100) / $promedio_2 . ')%' }}
                </td>
                <td>
                    @php
                        $promedio_4 = DB::table('operativos')
                            ->join('calls', 'operativos.idllamada', '=', 'calls.id')
                            ->whereBetween('operativos.fecha', [$this->fechaInicioMes, $this->fechaFinMes])
                            ->whereBetween('calls.fecha', [$this->fechaInicioMes, $this->fechaFinMes])
                            ->join('registropagos', 'operativos.id', '=', 'registropagos.idoperativo')
                            ->selectRaw('SUM(CAST(registropagos.monto AS DECIMAL(10, 2))) as total_monto')
                            ->value('total_monto');

                    @endphp
                    {{ $promedio_4 }}
                </td> --}}
            </tbody>
        </table>
    </div>
</div>
