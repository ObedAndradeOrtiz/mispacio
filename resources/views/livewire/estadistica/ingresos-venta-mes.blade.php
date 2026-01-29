<div>
    <h3 class="mt-4">INGRESOS PRODUCTOS POR MES</h3>
    <div id="chartlinexxx" wire:ignore></div>
    <script>
        var optionsx = {
            series: [{
                name: 'PRODUCTOS',
                data: @json($sumafarmacia)
            }],
            chart: {
                type: 'bar',
                height: 450
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    borderRadius: 4,
                    columnWidth: '50%' // opcional, ajusta grosor
                }
            },
            dataLabels: {
                enabled: false
            },
            xaxis: {
                categories: @json($areaslist)
            },
            fill: {
                opacity: 1,
                colors: ['#008FFB']
            },
            colors: ['#008FFB'], // solo un color
            legend: {
                show: false
            }
        };

        var chartx = new ApexCharts(document.querySelector("#chartlinexxx"), optionsx);
        chartx.render();
    </script>
    <h3 class="mt-4">INGRESOS PRODUCTOS POR DIA MIORA</h3>
    <div id="chartsucursalesdiaxx" wire:ignore></div>

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

        var chart = new ApexCharts(document.querySelector("#chartsucursalesdiaxx"), options);
        chart.render();
    </script>
</div>
