<div class="container-fluid">
    <div class="row">
        <!-- Filtros y Buscador -->
        <div class="mb-3 col-md-3">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Filtros</h3>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="" class="mb-0 fw-semibold">Buscador:</label>
                        <input type="text" class="form-control" wire:model="busqueda"
                            placeholder="Buscar llamadas...">
                    </div>
                    <div class="mb-3">
                        <label class="mb-0 fw-semibold">Sucursal: </label>
                        <select class="form-control" wire:model="areaseleccionada">
                            <option value="">Todas</option>
                            @foreach ($areas as $lista)
                                <option value="{{ $lista->area }}">{{ $lista->area }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="mb-0 fw-semibold">Rango: </label>
                        <select class="form-control" wire:model="rangoseleccionado">
                            <option value="Todos">Todos</option>
                            <option value="Personalizado">Personalizado</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="mb-0 fw-semibold">Responsable:</label>
                        <select class="form-control" wire:model="responsableseleccionado">
                            <option value="">Todos</option>
                            @foreach ($responsables as $lista)
                                <option value="{{ $lista->name }}">{{ $lista->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="mb-0 fw-semibold">Estado:</label>
                        <select class="form-control" wire:model="actividad">
                            <option value="">Todos</option>
                            <option value="Pendiente">Agendado</option>
                            <option value="llamadas">Sin agendar</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="fecha-inicio" class="mb-0 fw-semibold">Desde:</label>
                        <input type="date" class="form-control" wire:model="fechaInicioMes">
                    </div>
                    <div class="mb-3">
                        <label for="fecha-actual" class="mb-0 fw-semibold">Hasta:</label>
                        <input type="date" class="form-control" wire:model="fechaActual">
                    </div>
                    <div class="mb-3">
                        <h5 class="mb-4">Seleccionar columnas a mostrar:</h5>
                        <div class="row">
                            <!-- Columna 1 -->
                            <div class="col-md-6">
                                <div class="mb-3 form-check">
                                    <input type="checkbox" id="showNombre" class="filter-column form-check-input"
                                        checked>
                                    <label for="showNombre" class="form-check-label">Nombre</label>
                                </div>
                                <div class="mb-3 form-check">
                                    <input type="checkbox" id="showSucursal" class="filter-column form-check-input"
                                        checked>
                                    <label for="showSucursal" class="form-check-label">Sucursal</label>
                                </div>
                                <div class="mb-3 form-check">
                                    <input type="checkbox" id="showTelefono" class="filter-column form-check-input"
                                        checked>
                                    <label for="showTelefono" class="form-check-label">Teléfono</label>
                                </div>
                            </div>

                            <!-- Columna 2 -->
                            <div class="col-md-6">
                                <div class="mb-3 form-check">
                                    <input type="checkbox" id="showFecha" class="filter-column form-check-input"
                                        checked>
                                    <label for="showFecha" class="form-check-label">Fecha</label>
                                </div>
                                <div class="mb-3 form-check">
                                    <input type="checkbox" id="showEstado" class="filter-column form-check-input"
                                        checked>
                                    <label for="showEstado" class="form-check-label">Estado</label>
                                </div>
                                <div class="mb-3 form-check">
                                    <input type="checkbox" id="showAccion" class="filter-column form-check-input"
                                        checked>
                                    <label for="showAccion" class="form-check-label">Acción</label>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="gap-3 d-flex justify-content-center">
                        @livewire('calls-center.crear-call')
                        <div>
                            <button class="mt-2 btn btn-warning btn-sm d-flex align-items-center"
                                wire:click='copiarConsultaAlPortapapeles'>
                                <svg class="icon-20" width="20" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M14.7379 2.76175H8.08493C6.00493 2.75375 4.29993 4.41175 4.25093 6.49075V17.2037C4.20493 19.3167 5.87993 21.0677 7.99293 21.1147C8.02393 21.1147 8.05393 21.1157 8.08493 21.1147H16.0739C18.1679 21.0297 19.8179 19.2997 19.8029 17.2037V8.03775L14.7379 2.76175Z"
                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round">
                                    </path>
                                    <path d="M14.4751 2.75V5.659C14.4751 7.079 15.6231 8.23 17.0431 8.234H19.7981"
                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round">
                                    </path>
                                    <path d="M14.2882 15.3584H8.88818" stroke="currentColor" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path d="M12.2432 11.606H8.88721" stroke="currentColor" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                                <span>Copiar lista</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista de Llamadas -->
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Lista de Llamadas</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table mb-0 table-striped text-nowrap">
                            <thead>
                                <tr>
                                    <th># LLAMADAS</th>
                                    <th>SUCURSAL</th>
                                    <th>NOMBRE</th>
                                    <th>TELEFONO</th>
                                    <th>FECHA</th>
                                    <th>ESTADO</th>
                                    <th>ACCION</th>
                                </tr>
                            </thead>
                            <tbody style="font-size: 1vw;">
                                @foreach ($llamadas as $lista)
                                    <tr>
                                        <td>{{ $lista->cantidad }}</td>
                                        <td>{{ $lista->area }}</td>
                                        <td>{{ $lista->empresa }}</td>
                                        <td>{{ $lista->telefono }}</td>
                                        @php
                                            \Carbon\Carbon::setLocale('es');
                                            $fecha = $lista->fecha;
                                            $fechaCarbon = \Carbon\Carbon::parse($fecha);
                                            $fecha_formateada = $fechaCarbon->isoFormat('dddd D [de] MMMM [del] YYYY');
                                        @endphp
                                        <td>{{ $fecha_formateada }}</td>
                                        @if ($lista->estado == 'Pendiente')
                                            <td style="background: green;">Agendado</td>
                                        @else
                                            <td style="background: orange;">Por agendar</td>
                                        @endif
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @livewire('calls-center.lazy-load-editar-call', ['idllamada' => $lista->id], key('lazy-' . $lista->id * 5))
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $llamadas->links() }}
                    </div>
                </div>

            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Recuperar las preferencias de columnas desde localStorage
            const savedColumns = JSON.parse(localStorage.getItem('columnPreferences')) || {
                showNombre: true,
                showSucursal: true,
                showTelefono: true,
                showFecha: true,
                showEstado: true,
                showAccion: true,
            };

            // Inicializar los checkboxes según las preferencias guardadas
            document.getElementById('showNombre').checked = savedColumns.showNombre;
            document.getElementById('showSucursal').checked = savedColumns.showSucursal;
            document.getElementById('showTelefono').checked = savedColumns.showTelefono;
            document.getElementById('showFecha').checked = savedColumns.showFecha;
            document.getElementById('showEstado').checked = savedColumns.showEstado;
            document.getElementById('showAccion').checked = savedColumns.showAccion;

            // Actualizar la tabla según las preferencias
            updateTableVisibility(savedColumns);

            // Agregar eventos para actualizar las preferencias cuando el usuario cambie las opciones
            document.querySelectorAll('.filter-column').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const updatedColumns = {
                        showNombre: document.getElementById('showNombre').checked,
                        showSucursal: document.getElementById('showSucursal').checked,
                        showTelefono: document.getElementById('showTelefono').checked,
                        showFecha: document.getElementById('showFecha').checked,
                        showEstado: document.getElementById('showEstado').checked,
                        showAccion: document.getElementById('showAccion').checked,
                    };

                    // Guardar las preferencias en localStorage
                    // localStorage.setItem('columnPreferences', JSON.stringify(updatedColumns));

                    // Actualizar la visibilidad de las columnas
                    updateTableVisibility(updatedColumns);
                });
            });
        });

        // Función para actualizar la visibilidad de las columnas en la tabla
        function updateTableVisibility(columns) {
            const headers = document.querySelectorAll('th');
            const rows = document.querySelectorAll('tbody tr');

            // Mostrar u ocultar columnas basadas en las preferencias
            headers.forEach((header, index) => {
                const columnName = header.textContent.trim().toLowerCase();
                const columnClass = `show${columnName.charAt(0).toUpperCase() + columnName.slice(1)}`;

                if (!columns[`show${columnName.charAt(0).toUpperCase() + columnName.slice(1)}`]) {
                    header.style.display = 'none';
                    rows.forEach(row => {
                        row.cells[index].style.display = 'none';
                    });
                } else {
                    header.style.display = '';
                    rows.forEach(row => {
                        row.cells[index].style.display = '';
                    });
                }
            });
        }
    </script>
</div>
