<div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <div class="card" style="margin:3%;">
        <div class="card-body">

            @livewire('estadistica.llamadas-diario')
            <br>
            @livewire('estadistica.llamadas-semanal')
            <br>
            @livewire('estadistica.llamadas-semanal-agendados')
            <br>
            @livewire('estadistica.llamadas-semanal-asistidos')

        </div>
    </div>
</div>
