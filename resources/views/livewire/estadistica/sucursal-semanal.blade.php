<div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
    <h4 class="mt-4 ml-2">INGRESOS SEMANAL (SUCURSALES)</h4>
    <div>
        <canvas id="densityCanvass"></canvas>
    </div>
    <script>
        var lunes = {
            label: 'LUNES',
            data: @json($sumalunes),
            backgroundColor: 'rgba(255, 0, 0, 0.6)', // Rojo oscuro
            borderColor: 'rgba(255, 0, 0, 1)', // Rojo brillante
        };

        var martes = {
            label: 'MARTES',
            data: @json($sumamartes),
            backgroundColor: 'rgba(0, 128, 0, 0.6)', // Verde oscuro
            borderColor: 'rgba(0, 128, 0, 1)', // Verde brillante
        };

        var miercoles = {
            label: 'MIÉRCOLES',
            data: @json($sumamiercoles),
            backgroundColor: 'rgba(0, 0, 128, 0.6)', // Azul oscuro
            borderColor: 'rgba(0, 0, 128, 1)', // Azul brillante
        };

        var jueves = {
            label: 'JUEVES',
            data: @json($sumajueves),
            backgroundColor: 'rgba(255, 0, 255, 0.6)', // Magenta oscuro
            borderColor: 'rgba(255, 0, 255, 1)', // Magenta brillante
        };


        var viernes = {
            label: 'VIERNES',
            data: @json($sumaviernes),
            backgroundColor: 'rgba(255, 165, 0, 0.6)', // Naranja oscuro
            borderColor: 'rgba(255, 165, 0, 1)', // Naranja brillante
        };

        var sabado = {
            label: 'SÁBADO',
            data: @json($sumasabado),
            backgroundColor: 'rgba(128, 0, 128, 0.6)', // Púrpura oscuro
            borderColor: 'rgba(128, 0, 128, 1)', // Púrpura brillante
        };

        var domingo = {
            label: 'DOMINGO',
            data: @json($sumadomingo),
            backgroundColor: 'rgba(0, 128, 128, 0.6)', // Verdeazulado oscuro
            borderColor: 'rgba(0, 128, 128, 1)', // Verdeazulado brillante
        };


        var planetData = {
            labels: @json($areaslist),
            datasets: [lunes, martes, miercoles, jueves, viernes, sabado, domingo]
        };
        var chartOptions = {
            scales: {
                xAxes: [{
                    barPercentage: 0.8,
                    categoryPercentage: 0.5
                }],

            }
        };
        var densityCanvas = document.getElementById("densityCanvass");
        var barChart = new Chart(densityCanvas, {
            type: 'bar',
            data: planetData,
            options: chartOptions
        });
    </script>
</div>
