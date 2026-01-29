<div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                document.getElementById('myButton').click();
            }, 1);
        });
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
    <h4 class="mt-4 ml-2">INGRESOS Y GASTOS DE CAJA DIARIO (SUCURSALES)</h4>
    <div>
        <canvas id="densityCanvas"></canvas>
    </div>


    <script>
        var densityData = {
            label: 'EFECTIVO',
            data: @json($sumasucursales),
            backgroundColor: 'rgba(0, 99, 132, 0.6)',
            borderColor: 'rgba(0, 99, 132, 1)',
        };
        var gravityData = {
            label: 'GASTOS',
            data: @json($sumagastos),
            backgroundColor: 'rgba(99, 132, 0, 0.6)',
            borderColor: 'rgba(99, 132, 0, 1)',
        };

        var planetData = {
            labels: @json($areaslist),
            datasets: [densityData, gravityData]
        };
        var chartOptions = {
            scales: {
                xAxes: [{
                    barPercentage: 0.8,
                    categoryPercentage: 0.5
                }],

            }
        };
        var densityCanvas = document.getElementById("densityCanvas");
        var barChart = new Chart(densityCanvas, {
            type: 'bar',
            data: planetData,
            options: chartOptions
        });
    </script>
</div>
