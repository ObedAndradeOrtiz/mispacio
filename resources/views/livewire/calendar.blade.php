<div class="fadeIn third">
    <link el="stylesheet" href="../../assets/css/core/libs.min.css" />
    <link rel="stylesheet" href="assets/css/hope-ui.min.css?v=2.0.0" />
    <link rel="stylesheet" href="assets/css/custom.min.css?v=2.0.0" />
    <link rel='stylesheet' href='assets/vendor/fullcalendar/core/main.css' />
    <link rel='stylesheet' href='assets/vendor/fullcalendar/daygrid/main.css' />
    <link rel='stylesheet' href='assets/vendor/fullcalendar/timegrid/main.css' />
    <link rel='stylesheet' href='assets/vendor/fullcalendar/list/main.css' />
    <div id="calendar1" class="calendar-s"></div>
    <script src='assets/vendor/fullcalendar/core/main.js'></script>
    <script src='assets/vendor/fullcalendar/daygrid/main.js'></script>
    <script src='assets/vendor/fullcalendar/timegrid/main.js'></script>
    <script src='assets/vendor/fullcalendar/list/main.js'></script>
    <script src='assets/vendor/fullcalendar/interaction/main.js'></script>
    <script src='assets/vendor/moment.min.js'></script>
    <script>
        "use strict"
        if (document.querySelectorAll('#calendar1').length) {
            document.addEventListener('DOMContentLoaded', function() {
                let calendarEl = document.getElementById('calendar1');
                let calendar1 = new FullCalendar.Calendar(calendarEl, {
                    locale: 'es', // Asegúrate de que está configurado para español
                    selectable: true,
                    plugins: ["timeGrid", "dayGrid", "list", "interaction"],
                    timeZone: "UTC",
                    defaultView: "dayGridMonth",
                    contentHeight: "auto",
                    eventLimit: true,
                    dayMaxEvents: 4,
                    header: {
                        left: "prev,next today", // Cambié a las palabras en inglés para asegurarnos de que funcionan con la configuración predeterminada
                        center: "title",
                        right: "dayGridMonth,timeGridWeek,timeGridDay,listWeek"
                    },
                    dateClick: function(info) {
                        $('#schedule-start-date').val(info.dateStr)
                        $('#schedule-end-date').val(info.dateStr)
                        $('#date-event').modal('show')
                    },
                    events: @json($eventos)
                });
                calendar1.render();
            });
        }
    </script>
</div>
