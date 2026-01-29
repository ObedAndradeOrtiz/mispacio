<div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
    <h4 class="mt-4 ml-2">LLAMADAS ASISTIDOS (SEMANAL)</h4>
    <div id="densityCanvasscaa">
    </div>
    <script>
        var options = {
            series: [{
                    name: 'LUNES',
                    data: @json($sumalunes)
                }, {
                    name: 'MARTES',
                    data: @json($sumamartes)
                },
                {
                    name: 'MIERCOLES',
                    data: @json($sumamiercoles)
                },
                {
                    name: 'JUEVES',
                    data: @json($sumajueves)
                },
                {
                    name: 'VIERNES',
                    data: @json($sumaviernes)
                },
                {
                    name: 'SABADO',
                    data: @json($sumasabado)
                },
                {
                    name: 'DOMINGO',
                    data: @json($sumadomingo)
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

        var chart = new ApexCharts(document.querySelector("#densityCanvasscaa"), options);
        chart.render();
    </script>


</div>
