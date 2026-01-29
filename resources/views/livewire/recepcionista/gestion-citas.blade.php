<div>

    <style>
        .board {
            display: flex;
            gap: 20px;
            width: 99%;
            margin: auto;
        }

        .column {
            width: 25%;
            background: white;
            padding: 10px;
            border-radius: 12px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.329);
        }

        .column h2 {

            color: #333;
        }

        .task-list {
            min-height: 100px;
            background: #b4b4b473;
            padding: 10px;
            border-radius: 5px;
            box-shadow: inset 0 0 5px rgba(0, 0, 0, 0.1);
            font-size: 15px;
        }

        .task {
            background: rgba(100, 149, 237, 0.2);
            /* Azul suave con 20% de opacidad */
            color: black;
            padding: 10px;
            margin-bottom: 8px;
            border-radius: 5px;
            cursor: grab;
            font-size: 12px;


        }

        .task-disponible {
            background: #189b1f;
            color: black;
            padding: 10px;
            margin-bottom: 8px;
            border-radius: 5px;
            cursor: grab;
        }

        .select-container {
            text-align: center;
            margin-bottom: 20px;
        }

        select {
            padding: 15px;
            border-radius: 5px;
            border: 1px solid #ccc;
            width: 50%;
        }
    </style>
    <div class="py-2 card" style="margin: 12px;">
        <div class="col-12">
            <div class="flex-wrap d-flex justify-content-between align-items-center">
                <!-- Select de sucursal -->
                <div class="mb-3" style="flex: 1 0 20%; margin-right:10px; margin-left:5px;">
                    <select id="sucursal" wire:model="sucursal_seleccionada" class="form-control">
                        <option value="">Todas</option>
                        @foreach ($sucursales as $sucursal)
                            <option value="{{ $sucursal->area }}" @if ($sucursal->area == $sucursal_seleccionada) selected @endif>
                                {{ $sucursal->area }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3" style="flex: 1 0 20%;">
                    <!-- Botón Flujo de Pacientes -->
                    @if ($opcion == 0)
                        <button class="btn w-100 btn-primary" wire:click="setOpcion(0)">
                            <i class="fas fa-user-md me-2"></i> Flujo de pacientes
                        </button>
                    @else
                        <button class="btn btn-outline-primary w-100" wire:click="setOpcion(0)">
                            <i class="fas fa-user-md me-2"></i> Flujo de pacientes
                        </button>
                    @endif
                </div>
                <!-- Botón Gabinetes -->
                <div class="mb-3" style="flex: 1 0 20%; margin-right: 10px;">
                    <button class="btn w-100" wire:click="setOpcion(2)"
                        :class="{
                            'btn-outline-primary': {{ $opcion }} != 2,
                            'btn-primary': {{ $opcion }} == 2
                        }">
                        <i class="fas fa-briefcase me-2"></i> Gabinetes


                    </button>
                </div>
                <!-- Botón Camillas -->
                <div class="mb-3" style="flex: 1 0 20%; margin-right: 10px;">
                    <button class="btn w-100" wire:click="setOpcion(1)"
                        :class="{
                            'btn-outline-primary': {{ $opcion }} != 1,
                            'btn-primary': {{ $opcion }} == 1
                        }">
                        <i class="fas fa-bed me-2"></i> Camillas
                    </button>
                </div>


            </div>
        </div>
    </div>

    <body>
        @if ($opcion == 0)
            <div class="board" style="margin-bottom: 1%;">
                <div class="column" style="height: 70vh; overflow-y:scroll;">
                    <div style="display: flex; justify-content:center;">
                        <h5 class="mb-2">Agendado ({{ $agendados->where('estado', 'Pendiente')->count() }})
                        </h5>
                    </div>

                    <div class="mb-2 d-flex justify-content-center align-items-center">
                        <!-- Input de búsqueda con mismo tamaño que el botón de Livewire -->
                        <input type="text" class="mb-2 form-control" wire:model='buscador'
                            placeholder="Buscar paciente...">

                        <!-- Componente Livewire, ajustando el tamaño para que coincida -->
                        <div class="ms-2">
                            @livewire('clientes.crear-cliente')
                        </div>
                    </div>


                    <div class="mb-3 task-list" id="Pendiente" style="height: 55vh; overflow-y:scroll;">
                        @if ($agendados->isNotEmpty())
                            @foreach ($agendados->where('estado', 'Pendiente')->filter(function ($item) use ($buscador) {
        // Filtro de "empresa" y "numero" usando stripos (insensible a mayúsculas)
        return stripos(Str::before($item->empresa, ' '), $buscador) !== false || stripos(Str::before($item->telefono, ' '), $buscador) !== false;
    }) as $item)
                                <div class="p-2 task d-flex align-items-center justify-content-between"
                                    style="font-size: 12px; " data-id="{{ $item->id }}">

                                    <!-- Nombre y Teléfono (50% del espacio) -->
                                    <div class="d-flex flex-column text-truncate" style="width: 50%;">
                                        <span class="fw-bold">{{ Str::before($item->empresa, ' ') }}</span>
                                        <span class="telefono" style="font-size: 12px;">{{ $item->telefono }}</span>
                                    </div>

                                    <!-- Botones (50% del espacio) -->
                                    <div class="d-flex align-items-center justify-content-end"
                                        style="width: 50%; gap: 8px;">
                                        @livewire('operativos.load-editar-ficha', ['operativo' => $item->id], key('lazy-' . $item->id * 3))
                                    </div>

                                    <!-- Hora (Alineada a la derecha) -->
                                    <div class="text-end" style="font-size: 10px; width: 10%;">
                                        {{ $item->hora }}
                                    </div>

                                </div>
                            @endforeach
                        @else
                            <p class="text-white">No hay clientes agendados.</p>
                        @endif
                    </div>
                </div>
                <div class="column" style="height: 70vh; overflow-y:scroll;">
                    <div style="display: flex; justify-content:center;">
                        <h5 class="mb-2">Confirmados
                            ({{ $agendados->filter(fn($item) => stripos($item->estado, 'confirmado') !== false)->count() }})
                        </h5>
                    </div>
                    <div style="height: 60vh; overflow-y:scroll;">
                        @foreach (range(8, 20) as $hora)
                            <div class="mb-3 task-list" id="confirmado{{ $hora }}" style="min-height: 30px;">
                                <h5 class="mb-2">{{ $hora }} {{ $hora < 12 ? 'AM' : 'PM' }}</h5>
                                @foreach ($agendados->where('estado', 'confirmado' . $hora) as $item)
                                    <div class="task"
                                        style="display: flex; align-items: center; width: 100%; font-size: 12px; color:black;"
                                        data-id="{{ $item->id }}">

                                        <span
                                            style="flex: 1; text-align: left;">{{ Str::before($item->empresa, ' ') }}</span>

                                        <div style="flex: 1;">
                                            @livewire('operativos.load-editar-ficha', ['operativo' => $item->id], key('lazy-' . $item->id * 5))
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>

                </div>
                <div class="column" style="height: 70vh; overflow-y:scroll;">
                    <div style="display: flex; justify-content:center;">
                        <h5 class="mb-2">En Espera ({{ $agendados->where('estado', 'espera')->count() }})</h5>
                    </div>
                    <div class="task-list" id="espera" style="height: 60vh; overflow-y:scroll;">
                        @foreach ($agendados->where('estado', 'espera') as $item)
                            <div class="task"
                                style="display: flex; align-items: center; width: 100%; font-size: 12px; color:black;"
                                data-id="{{ $item->id }}">
                                <span
                                    style="flex-grow: 1; text-align: left;">{{ Str::before($item->empresa, ' ') }}</span>
                                <div style="flex-grow: 1;">
                                    @livewire('operativos.load-editar-ficha', ['operativo' => $item->id], key('lazy-' . $item->id * 5))
                                </div>

                            </div>
                        @endforeach
                    </div>
                </div>
                <style>
                    .cuarto-container {
                        display: flex;
                        flex-wrap: wrap;
                        gap: 20px;
                        justify-content: center;
                    }

                    .cuarto {
                        width: 280px;
                        min-height: 150px;
                        border: 3px solid #ccc;
                        border-radius: 10px;
                        padding: 10px;
                        text-align: center;
                        background-color: #f8f9fa;
                    }

                    .titulo-cuarto {

                        font-weight: bold;
                        margin-bottom: 10px;
                    }

                    /* Contenedor de camillas con diseño de 2 columnas */
                    .camillas-container {
                        display: grid;
                        grid-template-columns: repeat(2, 1fr);
                        gap: 10px;
                        justify-items: center;
                    }

                    .camilla {
                        width: 120px;
                        height: 200px;
                        border: 2px solid black;
                        border-radius: 5px;
                        display: flex;
                        flex-direction: column;
                        align-items: center;
                        justify-content: center;

                        color: black;
                        text-align: center;
                    }

                    /* Colores según estado */
                    .disponible {
                        background-color: green;
                    }

                    .espera {
                        background-color: rgba(255, 0, 0, 0.486);
                    }

                    .reservado {
                        background-color: orange;
                    }

                    /* Ajuste de nombre dentro de la camilla */
                    .nombre-camilla {

                        margin-bottom: 5px;
                    }

                    .camilla.disponible {
                        height: 100px !important;
                        /* Limita la altura */
                        background-color: rgba(245, 245, 220, 0.7);
                        /* Beige con 70% de opacidad */

                        /* Verde menta con 20% de opacidad */

                    }
                </style>
                <div class="column" style="height: 70vh; overflow-y:scroll;">
                    <div style="display: flex; justify-content:center;">
                        <h5 class="mb-2">En Atención ({{ $sucursal_seleccionada }})</h5>
                    </div>
                    <div class="cuarto-container" style="height: 60vh; overflow-y:scroll;">
                        @foreach ($gabinetes as $gabinete)
                            <div class="cuarto">
                                <h3 class="titulo-cuarto">{{ $gabinete->nombre }}</h3>
                                <div class="camillas-container">
                                    @foreach ($camillas->where('idgabinete', $gabinete->id) as $camilla)
                                        <div class="mb-2 task-list camilla {{ $camilla->estado }}"
                                            id="atencion{{ $camilla->id }}">
                                            @if ($camilla->estado == 'disponible')
                                                <h5 class="mb-2" style="color:rgb(0, 0, 0);">{{ $camilla->nombre }}
                                                </h5>
                                            @endif

                                            @php
                                                $estado = 'atencion' . $camilla->id;
                                            @endphp
                                            @foreach ($agendados->where('estado', $estado) as $item)
                                                <div class="task" data-id="{{ $item->id }}">
                                                    {{ Str::before(Str::before($item->empresa, ' '), ' ') }}
                                                </div>
                                                @php
                                                    $horaInicio = \Carbon\Carbon::createFromFormat(
                                                        'H:i',
                                                        $camilla->horainicio,
                                                    );
                                                    $horaFin = $horaInicio->copy()->addMinutes($camilla->horaestimada);
                                                    $faltanMinutos = now()->diffInMinutes($horaFin, false);
                                                    $cosmetologa = DB::table('registropagos')
                                                        ->where('idcliente', $item->idempresa)
                                                        ->orderBy('created_at', 'desc')
                                                        ->limit(1)
                                                        ->first();
                                                @endphp
                                                @if ($cosmetologa)
                                                    <label for="" style="font-size: 11px;">
                                                        Aten: {{ Str::before($cosmetologa->cosmetologa, ' ') }}
                                                        <br>
                                                        {{ $faltanMinutos > 0 ? $faltanMinutos . ' minutos faltantes.' : 'Tiempo cumplido' }}
                                                    </label>
                                                @else
                                                    Sin cosmetóloga seleccionada
                                                @endif
                                            @endforeach
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="column" style="height: 70vh; overflow-y:scroll;">
                    <div style="display: flex; justify-content:center;">
                        <h5 class="mb-2">Atendidos ({{ $confirmados }}) - Sin
                            reagendar({{ $agendados->where('estado', 'atendido')->count() }})</h5>
                    </div>
                    <div class="task-list" id="atendido" style="height: 60vh; overflow-y:scroll;">
                        @foreach ($agendados->where('estado', 'atendido') as $item)
                            <div class="task"
                                style="display: flex; align-items: center; width: 100%; font-size: 12px; color:black;"
                                data-id="{{ $item->id }}">
                                <span
                                    style="flex-grow: 1;
                        text-align: left;">{{ Str::before($item->empresa, ' ') }}</span>
                                <div style="flex-grow: 1;">

                                    @livewire('operativos.load-editar-ficha', ['operativo' => $item->id], key('lazy-' . $item->id * 5))

                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    let lists = document.querySelectorAll(".task-list");
                    lists.forEach(list => {
                        new Sortable(list, {
                            group: "shared",
                            animation: 150,
                            onEnd: function(evt) {
                                let taskId = evt.item.getAttribute("data-id");
                                let newStatus = evt.to.id;

                                Livewire.emit('cambiarEstadoPaciente', taskId, newStatus);
                                location.reload();
                            }
                        });
                    });
                    // Escuchar el evento Livewire para recargar la página automáticamente
                    Livewire.on('actualizarVista', () => {
                        location.reload(); // Recargar la página para actualizar la vista
                    });
                });
            </script>
        @endif
        @if ($opcion == 1)
            <div class="card" style="margin: 15px;">
                <div class="d-flex justify-content-end">
                    <!-- Botón con ícono -->
                    <button style="margin-left: 15px;" class="btn btn-success d-flex align-items-center"
                        wire:click="$set('crearcamilla',true)">
                        <i class="fas fa-bed me-2"></i> Crear Camilla
                    </button>
                </div>
                <div class="card-body">
                    <div class="board">
                        <div class="column">
                            <h2 class="mb-2">Disponibles</h2>
                            <div class="" id="disponible"
                                style="height: 50vh; overflow-y:scroll; overflow-x:hidden;">
                                <!-- Usamos grid para organizar las camillas en filas de 2 -->
                                <div class="row">
                                    @foreach ($camillas->where('estado', 'disponible') as $item)
                                        <!-- Cada camilla ocupa 6 columnas (2 camillas por fila) -->
                                        <div class="mb-3 col-md-6">
                                            <div class="border shadow-sm card border-primary">
                                                <div class="card-body d-flex align-items-center">
                                                    <!-- Ícono de la camilla -->
                                                    <i class="fas fa-bed me-3" style="font-size: 24px;"></i>
                                                    <!-- Nombre de la camilla con label -->
                                                    <div>
                                                        <label class="mb-1 card-title">{{ $item->nombre }}</label>
                                                        <!-- Si quieres agregar más detalles, puedes hacerlo aquí -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="column">
                            <h2 class="mb-2">En uso</h2>
                            <div id="espera" style="height: 50vh; overflow-y:scroll; overflow-x:hidden;">
                                <!-- Usamos un contenedor para las camillas en espera -->
                                <div class="row">
                                    @foreach ($camillas->where('estado', 'espera') as $item)
                                        <div class="mb-3 col-md-6">
                                            <!-- Card para cada camilla con borde -->
                                            <div class="border shadow-sm card border-danger">
                                                <div
                                                    class="card-body d-flex justify-content-between align-items-center">
                                                    <!-- Nombre de la camilla -->
                                                    <i class="fas fa-bed me-3" style="font-size: 24px;"></i>
                                                    <label class="card-title text-start">{{ $item->nombre }}</label>
                                                    <!-- Botón para activar la camilla -->

                                                </div>
                                                <button class="btn btn-success btn-sm"
                                                    wire:click='activarCamilla({{ $item->id }})'>
                                                    Activar
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>

        @endif
        @if ($opcion == 2)
            <div class="card" style="margin: 15px;">
                <div class="d-flex justify-content-end">

                    <button style="margin-left: 15px;" class="btn btn-success d-flex align-items-center"
                        wire:click="$set('creargabinete',true)"> <i class="fas fa-briefcase me-2"></i>Crear
                        Gabinete</label>
                </div>
                <div class="card-body">

                    <div class="board">
                        <div class="column">
                            <h2 class="mb-2">Gabinetes Disponibles</h2>
                            <div id="disponible" class="task-list">
                                <div class="row">
                                    @foreach ($gabinetes->where('estado', 'disponible') as $item)
                                        <div class="mb-3 col-md-6">
                                            <!-- Card para cada gabinete con borde -->
                                            <div class="border shadow-sm card border-primary">
                                                <div class="card-body d-flex align-items-center">
                                                    <!-- Ícono condicionado dependiendo del tipo de gabinete -->
                                                    @if (str_contains(strtolower($item->nombre), 'facial'))
                                                        <i class="fas fa-user me-3" style="font-size: 24px;"></i>
                                                        <!-- Ícono de cara para FACIAL -->
                                                    @elseif (str_contains(strtolower($item->nombre), 'corporal'))
                                                        <i class="fas fa-bed me-3" style="font-size: 24px;"></i>
                                                        <!-- Ícono de cama para CORPORAL -->
                                                    @else
                                                        <i class="fas fa-cogs me-3" style="font-size: 24px;"></i>
                                                        <!-- Ícono genérico -->
                                                    @endif
                                                    <!-- Nombre del gabinete -->
                                                    <label class="mb-1 card-title">{{ $item->nombre }}</label>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>

        @endif
    </body>
    <x-sm-modal wire:model.defer="crearcamilla" wire:click.prevent.stop>
        <div class="px-6 py-4">
            <div class="text-lg font-medium text-gray-900">
                Nueva camilla
            </div>
            <div class="mt-4 text-sm text-gray-600">
                <form>
                    <div class="form-group">
                        <label class="form-label" for="">Nombre de camilla:</label>
                        <input type="text" class="form-control" id="texto" oninput="convertirAMayusculas()"
                            wire:model.defer="nombrecamilla">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="">Lista de gabinetes:</label>
                        <select name="" id="" wire:model='idgabinete'>
                            <option value="">Seleccionar</option>
                            @foreach ($gabinetes as $item)
                                <option value="{{ $item->id }}">{{ $item->nombre . '(' . $item->sucursal . ')' }}
                                </option>
                            @endforeach
                        </select>
                    </div>


                </form>
            </div>
        </div>
        <div class="flex flex-row justify-end px-6 py-4 text-right bg-gray-100">
            <label type="submit" class="btn btn-success" wire:click="guardarcamilla" wire:loading.remove
                wire:target="guardarcamilla">Crear camilla</label>
            <span class="" wire:loading wire:target="guardarcamilla">Guardando...</span>
        </div>

    </x-sm-modal>
    <x-sm-modal wire:model.defer="creargabinete" wire:click.prevent.stop>
        <div class="px-6 py-4">
            <div class="text-lg font-medium text-gray-900">
                Nuevo gabinete
            </div>
            <div class="mt-4 text-sm text-gray-600">
                <form>
                    <div class="form-group">
                        <label class="form-label" for="">Nombre de gabinete:</label>
                        <input type="text" class="form-control" id="texto" oninput="convertirAMayusculas()"
                            wire:model.defer="nombregabinete">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="">Sucursal de gabinete:</label>
                        <select name="" id="" wire:model='sucursal_camilla'>
                            <option value="">Seleccionar</option>
                            @foreach ($sucursales as $item)
                                <option value="{{ $item->id }}">{{ $item->area }}</option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
        </div>
        <div class="flex flex-row justify-end px-6 py-4 text-right bg-gray-100">
            <label type="submit" class="btn btn-success" wire:click="guardargabinete" wire:loading.remove
                wire:target="guardargabinete">Crear gabinete</label>
            <span class="" wire:loading wire:target="guardargabinete">Guardando...</span>
        </div>

    </x-sm-modal>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.2/Sortable.min.js"></script>
</div>
