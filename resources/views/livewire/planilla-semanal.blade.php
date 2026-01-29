<div>
    <style>
        /* Panel izquierdo */
        .task-list .task {
            padding: 8px 10px;
            margin-bottom: 6px;
            background: #ffffff;
            border: 1px solid #dcdcdc;
            border-radius: 6px;
            cursor: grab;
            transition: background 0.2s, transform 0.1s;
        }

        .task:hover {
            background: #f5f9ff;
            transform: scale(1.02);
        }

        /* Tarjetas */
        .card {
            border-radius: 8px !important;
            overflow: hidden;
        }

        .card-header {
            font-size: 1rem;
            font-weight: 600;
            padding: 10px;
            background: #f5f7fa !important;
            border-bottom: 1px solid #d8d8d8;
        }

        /* Tabla estilo Excel */
        table.table {
            border-collapse: collapse !important;
        }

        table.table thead th {
            background: #eef2f7;
            font-weight: 600;
            text-transform: capitalize;
            border: 1px solid #d0d0d0 !important;
            padding: 12px;
        }

        table.table tbody td {
            border: 1px solid #d0d0d0 !important;
            background: #fbfbfb;
            vertical-align: top;
            height: 110px;
            padding: 6px;
            transition: background 0.2s;
        }

        table.table tbody td:hover {
            background: #f0f7ff;
        }

        /* Items dentro de las celdas */
        .task-item {
            padding: 6px 8px;
            margin-bottom: 4px;
            background: #e8f0ff;
            border-radius: 4px;
            font-size: 0.85rem;
            border: 1px solid #c6dafc;
            cursor: grab;
        }

        .task-item:hover {
            background: #ddecff;
        }

        /* Título principal */
        .titulo-planilla {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .subtitulo-planilla {
            font-size: 0.95rem;
            color: #6c757d;
        }

        /* Sombreado general */
        #planilla-captura {
            background: #ffffff;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0px 2px 8px rgba(0, 0, 0, 0.05);
        }
    </style>


    <div class="py-3 container-fluid">

        {{-- ENCABEZADO DE SEMANA --}}
        <div class="mb-4 d-flex justify-content-between align-items-center">
            <div>
                <div class="titulo-planilla">Planilla Semanal</div>
                <div class="subtitulo-planilla">
                    Semana del
                    <strong>{{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }}</strong>
                    al
                    <strong>{{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</strong>
                </div>
            </div>

            <div class="gap-2 d-flex">
                <button wire:click="goToPreviousWeek" class="btn btn-outline-secondary btn-sm">
                    &laquo; Semana anterior
                </button>

                <button wire:click="goToNextWeek" class="btn btn-outline-secondary btn-sm">
                    Semana siguiente &raquo;
                </button>

                <input type="date" wire:model="selectedDate" wire:change="loadWeekFromDate"
                    class="form-control form-control-sm" style="width:150px;">

                <button class="btn btn-success btn-sm" id="btn-descargar-img" data-start="{{ $startDate }}"
                    data-end="{{ $endDate }}">
                    Descargar imagen
                </button>
            </div>
        </div>

        <div class="row">

            {{-- PANEL IZQUIERDO DE PERSONAL --}}
            <div class="col-md-3">
                <div class="mb-4 shadow-sm card">
                    <div class="text-black card-header bg-primary">
                        Personal disponible
                    </div>

                    <div class="card-body" style="max-height: 650px; overflow-y:auto;">

                        <input type="text" class="mb-3 form-control form-control-sm" placeholder="Buscar personal..."
                            wire:model.debounce.400ms="busqueda">

                        <div class="task-list" id="users-list">
                            @foreach ($users as $user)
                                <div class="task" data-user="{{ $user->id }}">
                                    {{ $user->name }}
                                </div>
                            @endforeach
                        </div>

                    </div>
                </div>
            </div>

            {{-- PANEL DERECHO DE PLANILLA --}}
            <div class="col-md-9">
                <div id="planilla-captura">

                    @foreach ($areas as $area)
                        <div class="mb-4 shadow-sm card">

                            <div class="text-center card-header">
                                {{ $area->area }}
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered table-sm">

                                    {{-- CABECERA --}}
                                    <thead>
                                        <tr>
                                            @foreach ($days as $day)
                                                <th class="text-center">
                                                    {{ ucfirst($day['label']) }}
                                                </th>
                                            @endforeach
                                        </tr>
                                    </thead>

                                    {{-- CUERPO --}}
                                    <tbody>
                                        <tr>

                                            @foreach ($days as $day)
                                                @php
                                                    $fecha = $day['date'];
                                                    $asignaciones = $assignmentsMatrix[$area->id][$fecha] ?? [];
                                                @endphp

                                                <td class="day-cell" data-area="{{ $area->id }}"
                                                    data-date="{{ $fecha }}">

                                                    {{-- TURNO MAÑANA --}}
                                                    <div class="mb-2 small text-muted">Mañana</div>
                                                    <div class="task-list shift-am"
                                                        id="list-{{ $area->id }}-{{ $fecha }}-am"
                                                        data-shift="am">

                                                        @foreach ($asignaciones['am'] ?? [] as $asig)
                                                            <div class="task-item" data-user="{{ $asig->user->id }}">
                                                                {{ $asig->user->name }}
                                                                <button class="px-2 py-0 btn btn-danger btn-sm ms-2"
                                                                    wire:click="deleteAssignment({{ $asig->id }})">
                                                                    ×
                                                                </button>
                                                            </div>
                                                        @endforeach
                                                    </div>

                                                    {{-- TURNO TARDE --}}
                                                    <div class="mt-3 mb-2 small text-muted">Tarde</div>
                                                    <div class="task-list shift-pm"
                                                        id="list-{{ $area->id }}-{{ $fecha }}-pm"
                                                        data-shift="pm">

                                                        @foreach ($asignaciones['pm'] ?? [] as $asig)
                                                            <div class="task-item" data-user="{{ $asig->user->id }}">
                                                                {{ $asig->user->name }}
                                                                <button class="px-2 py-0 btn btn-danger btn-sm ms-2"
                                                                    wire:click="deleteAssignment({{ $asig->id }})">
                                                                    ×
                                                                </button>
                                                            </div>
                                                        @endforeach
                                                    </div>

                                                </td>
                                            @endforeach

                                        </tr>
                                    </tbody>

                                </table>
                            </div>

                        </div>
                    @endforeach

                </div>
            </div>

        </div>

    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            document.getElementById("btn-descargar-img").addEventListener("click", function() {

                let start = this.dataset.start;
                let end = this.dataset.end;

                const div = document.getElementById("planilla-captura");

                html2canvas(div, {
                    scale: 2
                }).then(canvas => {

                    const imgData = canvas.toDataURL("image/png");

                    const link = document.createElement("a");
                    link.href = imgData;

                    // NOMBRE DINÁMICO
                    link.download = `planilla_${start}_a_${end}.png`;

                    link.click();
                });

            });

        });
    </script>

    <script>
        function initPlanillaSortables() {
            // Evitar duplicar Sortable en las mismas listas
            document.querySelectorAll(".task-list").forEach(list => {
                if (list.dataset.sortableInitialized === "1") return;

                new Sortable(list, {
                    group: "staff",
                    animation: 150,
                    onEnd: function(evt) {
                        let item = evt.item;
                        let userId = item.getAttribute("data-user");

                        let toList = evt.to;
                        let fromList = evt.from;

                        // Si lo sueltan en la lista de usuarios disponibles
                        if (toList.id === "users-list") {
                            if (!userId) return;
                            Livewire.emit("unassignUserFromWeek", userId);
                            return;
                        }

                        // Si lo sueltan en algún día (celda)
                        let toWrapper = toList.closest("td");
                        if (!toWrapper) return;

                        let areaId = toWrapper.getAttribute("data-area");
                        let date = toWrapper.getAttribute("data-date");

                        if (!userId || !areaId || !date) return;

                        // Desde qué día / área venía (puede ser null si venía desde users-list)
                        let fromWrapper = fromList.closest("td");
                        let fromDate = fromWrapper ? fromWrapper.getAttribute("data-date") : null;
                        let shift = toList.dataset.shift;

                        Livewire.emit("assignUserToDay", {
                            userId: userId,
                            areaId: areaId,
                            date: date,
                            algo: shift,
                            fromDate: fromDate,
                        });
                    }
                });

                list.dataset.sortableInitialized = "1";
            });
        }

        document.addEventListener("livewire:load", function() {
            // Inicial primera carga
            initPlanillaSortables();

            // Cada vez que Livewire actualiza el DOM, re-inicializamos
            Livewire.hook("message.processed", (message, component) => {
                initPlanillaSortables();
            });
        });
    </script>


</div>
