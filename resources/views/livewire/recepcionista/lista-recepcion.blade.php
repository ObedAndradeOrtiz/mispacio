<div class="container-fluid">
    <div class="row">
        <div class="mb-3 col-md-3">
            <div class="card">
                <div class="text-center card-header fw-bold">
                    <h3 class="card-title">Filtros</h3>
                </div>
                <div class="card-body">
                    <!-- Buscador -->
                    <div class="mb-3 align-items-center">
                        <label for="fecha-actual" class="mb-0 fw-semibold">Buscardor de pacientes: </label>
                        <input type="text" class="form-control form-control-sm" id="exampleInputDisabled1"
                            wire:model="busqueda" placeholder="Buscar Clientes...">
                    </div>

                    <!-- Sucursal -->
                    <div class="mb-3 align-items-center">
                        <label for="fecha-actual" class="mb-0 fw-semibold">Sucursal: </label>
                        <select class="form-control" wire:model="areaseleccionada">
                            <option value="">Todas</option>
                            @foreach ($areas as $lista)
                                <option value="{{ $lista->area }}">{{ $lista->area }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Rango -->
                    <div class="mb-3 align-items-center">
                        <label for="fecha-actual" class="mb-0 fw-semibold">Rango: </label>
                        <select class="form-control" wire:model="rangoseleccionado">
                            <option value="Diario">Diario</option>
                            <option value="Ayer">Ayer</option>
                            <option value="Semanal">Hace 5 días</option>
                            <option value="Mensual">Hace 30 días</option>
                            <option value="Todos">Todos</option>
                            <option value="Personalizado">Personalizado</option>
                        </select>
                    </div>

                    <!-- Fechas -->
                    <div class="mb-3 align-items-center">
                        <label for="fecha-inicio" class="mb-0 fw-semibold">Desde:</label>
                        <input type="date" id="fecha-inicio" class="form-control" wire:model="fechaInicioMes"
                            @if (in_array($rangoseleccionado, ['Ayer', 'Diario', 'Semanal', 'Mensual', 'Todos'])) readonly @endif onkeydown="return false">
                    </div>
                    <div class="mb-3 align-items-center">
                        <label for="fecha-actual" class="mb-0 fw-semibold">Hasta:</label>
                        <input type="date" id="fecha-actual" class="form-control" wire:model="fechaActual"
                            @if (in_array($rangoseleccionado, ['Ayer', 'Diario', 'Semanal', 'Mensual', 'Todos'])) readonly @endif onkeydown="return false">
                    </div>
                    <div class="mb-3">
                        <h5 class="mb-4">Seleccionar columnas a mostrar:</h5>
                        <div class="row">
                            <!-- Columna 1 -->
                            <div class="col-md-6">
                                <div class="mb-3 form-check">
                                    <input type="checkbox" id="showAgendaAsistencia"
                                        class="filter-column form-check-input" checked>
                                    <label for="showAgendaAsistencia" class="form-check-label">Asistencia</label>
                                </div>
                                <div class="mb-3 form-check">
                                    <input type="checkbox" id="showAgendaCliente" class="filter-column form-check-input"
                                        checked>
                                    <label for="showAgendaCliente" class="form-check-label">Cliente</label>
                                </div>
                                <div class="mb-3 form-check">
                                    <input type="checkbox" id="showAgendaTelefono"
                                        class="filter-column form-check-input" checked>
                                    <label for="showAgendaTelefono" class="form-check-label">Telefono</label>
                                </div>

                            </div>

                            <!-- Columna 2 -->
                            <div class="col-md-6">
                                <div class="mb-3 form-check">
                                    <input type="checkbox" id="showAgendaFecha" class="filter-column form-check-input"
                                        checked>
                                    <label for="showAgendaFecha" class="form-check-label">Fecha</label>
                                </div>
                                <div class="mb-3 form-check">
                                    <input type="checkbox" id="showAgendaSucursal"
                                        class="filter-column form-check-input" checked>
                                    <label for="showAgendaSucursal" class="form-check-label">Sucursal</label>
                                </div>
                                <div class="mb-3 form-check">
                                    <input type="checkbox" id="showAgendaResponsable"
                                        class="filter-column form-check-input" checked>
                                    <label for="showAgendaResponsable" class="form-check-label">Responsable</label>
                                </div>
                                <div class="mb-3 form-check">
                                    <input type="checkbox" id="showAgendaAccion" class="filter-column form-check-input"
                                        checked>
                                    <label for="showAgendaAccion" class="form-check-label">Accion</label>
                                </div>
                            </div>


                            <!-- Botón Notificación -->
                        </div>
                    </div>
                    <!-- Botones -->
                    <div class="gap-3 d-flex justify-content-center">
                        @livewire('clientes.crear-cliente')
                        <button class="btn btn-warning btn-sm d-flex align-items-center"
                            wire:click='copiarConsultaAlPortapapeles' title="Copiar consulta al portapapeles">
                            <svg class="icon-20" width="18" viewBox="0 0 24 24" fill="none"
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
                        </button>

                        <button title="Activar recordatorio" class="btn btn-danger btn-sm d-flex align-items-center"
                            wire:click='$set("recordar",true)'>
                            <i class="text-white fas fa-bell"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Lista de pacientes agendados</h3>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table mb-0 table-striped text-nowrap">
                            <thead>
                                <tr>
                                    <th>Asistencia</th>
                                    <th>Cliente</th>
                                    <th>Telefono</th>
                                    <th>Fecha</th>
                                    <th>Sucursal</th>
                                    <th>Responsable</th>
                                    <th>Accion</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($llamadas as $lista)
                                    @php
                                        $verificar = false;
                                        $haypago = false;
                                        $paguito = DB::table('registropagos')
                                            ->where('idcliente', $lista->idempresa)
                                            ->whereBetween('fecha', [$fechaInicioMes, $fechaActual])
                                            ->get();
                                        foreach ($paguito as $key => $value) {
                                            $haypago = true;
                                        }
                                        $mipagos = DB::table('pagos')->where('idoperativo', $lista->id)->get();
                                        foreach ($mipagos as $pago) {
                                            if ($pago->cantidad < $pago->pagado) {
                                                $verificar = true;
                                            }
                                        }
                                    @endphp
                                    @if ($lista->problema == 'si')
                                        <tr style="background-color: red;">
                                            @if ($haypago)
                                                <td><i class="fas fa-check-circle text-success"></i></td>
                                            @else
                                                <td><i class="fas fa-times-circle text-danger"></i></td>
                                            @endif
                                            <td>{{ $lista->empresa }}</td>
                                            <td>{{ $lista->telefono }}</td>

                                            @php
                                                \Carbon\Carbon::setLocale('es');
                                                $fecha = $lista->fecha;
                                                $fechaCarbon = \Carbon\Carbon::parse($fecha);
                                                $fecha_formateada = $fechaCarbon->isoFormat(
                                                    'dddd D [de] MMMM [del] YYYY',
                                                );
                                            @endphp
                                            <td>
                                                {{ $lista->hora . ' - ' . $fecha_formateada }}</td>

                                            <td>{{ $lista->area }}</td>

                                            <td>{{ $lista->responsable }}</td>

                                            <td>
                                                <div class="flex align-items-center list-user-action">
                                                    @livewire('operativos.load-editar-ficha', ['operativo' => $lista->id], key('lazy-' . $lista->id * 5))
                                                </div>
                                            </td>
                                        </tr>
                                    @else
                                        <tr>
                                            @if ($haypago)
                                                <td><i class="fas fa-check-circle text-success"></i></td>
                                            @else
                                                <td><i class="fas fa-times-circle text-danger"></i></td>
                                            @endif
                                            <td>{{ $lista->empresa }}</td>
                                            <td>{{ $lista->telefono }}</td>

                                            @php
                                                \Carbon\Carbon::setLocale('es');
                                                $fecha = $lista->fecha;
                                                $fechaCarbon = \Carbon\Carbon::parse($fecha);
                                                $fecha_formateada = $fechaCarbon->isoFormat(
                                                    'dddd D [de] MMMM [del] YYYY',
                                                );
                                            @endphp
                                            <td>
                                                {{ $lista->hora . ' - ' . $fecha_formateada }}</td>

                                            <td>{{ $lista->area }}</td>

                                            <td>{{ $lista->responsable }}</td>

                                            <td>
                                                <div class="flex align-items-center list-user-action">
                                                    @livewire('operativos.load-editar-ficha', ['operativo' => $lista->id], key('lazy-' . $lista->id * 5))
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
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
                showAgendaAsistencia: true,
                showAgendaCliente: true,
                showAgendaTelefono: true,
                showAgendaSucursal: true,
                showAgendaFecha: true,
                showAgendaResponsable: true,
                showAgendaAccion: true,
            };
            document.getElementById('showAgendaAsistencia').checked = savedColumns.showAgendaAsistencia;
            document.getElementById('showAgendaCliente').checked = savedColumns.showAgendaCliente;
            document.getElementById('showAgendaSucursal').checked = savedColumns.showAgendaSucursal;
            document.getElementById('showAgendaTelefono').checked = savedColumns.showAgendaTelefono;
            document.getElementById('showAgendaFecha').checked = savedColumns.showAgendaFecha;
            document.getElementById('showAgendaResponsable').checked = savedColumns.showAgendaResponsable;
            document.getElementById('showAgendaAccion').checked = savedColumns.showAgendaAccion;

            // Actualizar la tabla según las preferencias
            updateTableVisibility(savedColumns);

            // Agregar eventos para actualizar las preferencias cuando el usuario cambie las opciones
            document.querySelectorAll('.filter-column').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const updatedColumns = {
                        showAgendaAsistencia: document.getElementById('showAgendaAsistencia')
                            .checked,
                        showAgendaCliente: document.getElementById('showAgendaCliente').checked,
                        showAgendaTelefono: document.getElementById('showAgendaTelefono')
                            .checked,
                        showAgendaSucursal: document.getElementById('showAgendaSucursal')
                            .checked,
                        showAgendaFecha: document.getElementById('showAgendaFecha').checked,
                        showAgendaResponsable: document.getElementById('showAgendaResponsable')
                            .checked,
                        showAgendaAccion: document.getElementById('showAgendaAccion').checked,
                    };

                    // Guardar las preferencias en localStorage
                    //localStorage.setItem('columnPreferences', JSON.stringify(updatedColumns));

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
                const columnClass = `showAgenda${columnName.charAt(0).toUpperCase() + columnName.slice(1)}`;

                if (!columns[`showAgenda${columnName.charAt(0).toUpperCase() + columnName.slice(1)}`]) {
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
    <x-sm-modal wire:model.defer="recordar" style="max-width: 250px;">
        <div class="px-6 py-4">
            <div class="py-2 text-lg font-medium text-gray-900">
                Recordatorio diario:
            </div>
            <div class="mt-2 text-sm text-gray-600">
                <form>
                    <div class="form-group">
                        <label class="form-label" for="">Seleccione teléfono de envío: </label>
                        <select name="type" class="selectpicker form-control" data-style="py-0"
                            wire:model.defer="telefonosesion">
                            <option>Seleccionar teléfono</option>
                            @foreach ($telefonosWss as $telefono)
                                <option value="{{ $telefono->id }}">
                                    {{ $telefono->telefono . ' ' . $telefono->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
        </div>
        <div class="flex flex-row justify-end px-6 py-4 text-right bg-gray-100">
            <label type="submit" class="btn btn-success" style="background-color: green;"
                wire:click="enviarRecordatorio" wire:loading.remove wire:target="enviarRecordatorio">Enviar
                mensajes</label>
            <span class="" wire:loading wire:target="enviarRecordatorio">Enviando...</span>

        </div>
    </x-sm-modal>
</div>
