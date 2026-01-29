<div>

    <!-- Contenido de tu página -->

    {{-- <div id="chartsucursales">
    </div> --}}
    <script>
        var options = {
            series: @json($areasmes),
            chart: {
                type: 'bar',
                height: 450
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
                categories: @json($diasDelMes),
            },
            yaxis: {
                title: {
                    text: 'Ingreso en Bs.'
                }
            },
            fill: {
                opacity: 1
            },
            tooltip: {
                y: {
                    formatter: function(val) {
                        return "Ingreso: " + val + "Bs."
                    }
                }
            }
        };

        var chart = new ApexCharts(document.querySelector("#chartsucursales"), options);
        chart.render();
    </script>
    

    {{-- <h3 class="mt-4">AGENDADOS/ASISTIDOS</h3>
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
                categories: @json($diasDelMes),
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
    </script> --}}




</div>
