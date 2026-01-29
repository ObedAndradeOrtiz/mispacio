<div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
    <h4 class="mt-4 ml-2">ASISTENCIA A CITAS SEMANAL</h4>
    <div id="chartsucursales">
    </div>
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
                enabled: true
            },
            stroke: {
                show: true,
                width: 2,
                colors: ['transparent']
            },
            xaxis: {
                categories: @json($dias),
            },
            yaxis: {
                title: {
                    text: 'Asisitencia pacientes'
                }
            },
            fill: {
                opacity: 1
            },
            tooltip: {
                y: {
                    formatter: function(val) {
                        return "Asisitidos: " + val
                    }
                }
            }
        };

        var chart = new ApexCharts(document.querySelector("#chartsucursales"), options);
        chart.render();
    </script>


</div>
