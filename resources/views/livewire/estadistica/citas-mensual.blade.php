<div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
    <h4 class="mt-4 ml-2">CITAS ASISTIDAS MES ACTUAL</h4>


    <div>
        <canvas id="densityCanvacma"></canvas>
    </div>
    <script>
        var semana1 = {
            label: "{{ $inicioSemana1 }} al {{ $finSemana1 }}",
            data: @json($suma1),
            backgroundColor: 'rgba(255, 0, 0, 0.6)', // Rojo oscuro
            borderColor: 'rgba(255, 0, 0, 1)', // Rojo brillante
        };

        var semana2 = {
            label: "{{ $inicioSemana2 }} al {{ $finSemana2 }}",
            data: @json($suma2),
            backgroundColor: 'rgba(0, 128, 0, 0.6)', // Verde oscuro
            borderColor: 'rgba(0, 128, 0, 1)', // Verde brillante
        };

        var semana3 = {
            label: "{{ $inicioSemana3 }} al {{ $finSemana3 }}",
            data: @json($suma3),
            backgroundColor: 'rgba(0, 0, 128, 0.6)', // Azul oscuro
            borderColor: 'rgba(0, 0, 128, 1)', // Azul brillante
        };

        var semana4 = {
            label: "{{ $inicioSemana4 }} al {{ $finSemana4 }}",
            data: @json($suma4),
            backgroundColor: 'rgba(255, 0, 255, 0.6)', // Magenta oscuro
            borderColor: 'rgba(255, 0, 255, 1)', // Magenta brillante
        };
        var semana5 = {
            label: "{{ $inicioSemana5 }} al {{ $finSemana5 }}",
            data: @json($suma5),
            backgroundColor: 'rgba(255, 165, 0, 0.6)', // Naranja oscuro
            borderColor: 'rgba(255, 165, 0, 1)', // Naranja brillante
        };


        var planetData = {
            labels: @json($areaslist),
            datasets: [semana1, semana2, semana3, semana4, semana5]
        };
        var chartOptions = {
            scales: {
                xAxes: [{
                    barPercentage: 0.8,
                    categoryPercentage: 0.5
                }],

            }
        };
        var densityCanvas = document.getElementById("densityCanvacma");
        var barChart = new Chart(densityCanvas, {
            type: 'bar',
            data: planetData,
            options: chartOptions
        });
    </script>

</div>
