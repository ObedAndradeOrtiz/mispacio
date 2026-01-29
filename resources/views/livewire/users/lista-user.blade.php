<div class="mt-4 section-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3">
                <div class="border-0 shadow-sm card">
                    <div class="text-center card-header fw-bold">
                        <h3 class="card-title">Gestión de usuarios de sistema</h3>
                    </div>
                    <div class="card-body">
                        <!-- Filtro de búsqueda -->
                        <div class="mb-4">
                            <div class="d-flex align-items-center">
                                <input type="text" class="form-control form-control-lg"
                                    placeholder="Buscar por nombre..." wire:model="busqueda">
                                @livewire('users.crear-user')
                            </div>
                        </div>



                        <!-- Filtros de estado, cargo y sucursal -->
                        <div class="flex-wrap mb-3 d-flex justify-content-between" style="gap: 15px;">
                            <!-- Estado -->
                            <div class="flex-column w-100 w-md-45">
                                <label for="estadoUser" class="form-label font-weight-bold">Estado:</label>
                                <select wire:model="estadoUser" class="form-control">
                                    <option value="todos">Todos</option>
                                    <option value="Activo">Activos</option>
                                    {{-- <option value="Inactivo">Inactivos</option> --}}
                                </select>
                            </div>

                            <!-- Cargo -->
                            <div class="flex-column w-100 w-md-45">
                                <label for="rolseleccionado" class="form-label font-weight-bold">Cargo:</label>
                                <select wire:model="rolseleccionado" class="form-control">
                                    <option value="">Todos</option>
                                    @foreach ($roles as $item)
                                        <option value="{{ $item->rol }}">{{ $item->rol }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Filtro de sucursal -->
                        <div class="mb-3">
                            <label for="areaseleccionada" class="form-label font-weight-bold">Sucursal:</label>
                            <select wire:model="areaseleccionada" class="form-control">
                                <option value="">Todos</option>
                                @foreach ($areas as $item)
                                    <option value="{{ $item->area }}">{{ $item->area }}</option>
                                @endforeach
                            </select>
                        </div>
                        <!-- Filtros de fecha y estado -->
                        <div class="flex-wrap mb-3 d-flex justify-content-between" style="gap: 15px;">
                            <!-- Fecha desde -->
                            <div class="flex-column w-100 w-md-45">
                                <label for="fecha-inicio" class="form-label font-weight-bold">Desde:</label>
                                <input type="date" id="fecha-inicio" class="form-control"
                                    wire:model="fechaInicioMes">
                            </div>

                            <!-- Fecha hasta -->
                            <div class="flex-column w-100 w-md-45">
                                <label for="fecha-actual" class="form-label font-weight-bold">Hasta:</label>
                                <input type="date" id="fecha-actual" class="form-control" wire:model="fechaActual">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-9">
                <div class="card">
                    <div class="text-center card-header fw-bold">
                        <h3 class="card-title">Lista de usuarios</h3>
                    </div>
                    <div class="card-body">
                        <div class="py-3 table-responsive ">
                            <table class="table mb-0 table-striped table-hover table-bordered text-nowrap">
                                <thead class="thead-dark">
                                    <tr class="ligth">
                                        <th>PERFIL</th>
                                        <th>NOMBRE</th>
                                        <th>SUCURSAL</th>
                                        <th>ESTADO</th>
                                        <th>FECHA INICIO</th>
                                        <th>DIAS TRABAJADOS</th>
                                        <th>MEMORANDUM</th>
                                        <th>TOTAL PAGADO</th>
                                        <th>ACCIÓN</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <style>
                                        td {
                                            max-width: 200px;
                                            overflow: hidden;
                                            text-overflow: ellipsis;
                                            white-space: nowrap;
                                        }
                                    </style>
                                    @foreach ($users as $lista)
                                        <tr>
                                            <td>
                                                {{-- Avatar redondo --}}
                                                @php
                                                    $ruta = $lista->path ? asset('storage/' . $lista->path) : null;
                                                @endphp

                                                <div
                                                    style="width:45px; height:45px; border-radius:50%; overflow:hidden; background:#ccc;">
                                                    @if ($ruta)
                                                        <img src="{{ $ruta }}" alt="Foto"
                                                            style="width:100%; height:100%; object-fit:cover;">
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    {{ $lista->name }}
                                                </div>
                                                <div class="text-muted">{{ $lista->rol }}</div>
                                            </td>
                                            <td>{{ $lista->sucursal }}</td>

                                            @if ($lista->estado == 'Activo')
                                                <td><span class="badge bg-primary">Activo</span></td>
                                            @else
                                                <td><span class="badge bg-danger">Inactivo</span></td>
                                            @endif
                                            <td>{{ $lista->fechainicio }}</td>
                                            @php
                                                $mesesPasados = 0;
                                                $fechaInicio = new DateTime($lista->fechainicio);
                                                $hoy = new DateTime();
                                                $diferencia = $hoy->diff($fechaInicio);
                                                $diasRestantes = $diferencia->days;
                                                $mesesPasados = intval($diferencia->days / 30.4);

                                                $gastoarea = DB::table('gastos')
                                                    ->whereBetween('fechainicio', [$fechaInicioMes, $fechaActual])
                                                    ->where(function ($q) {
                                                        $q->where('tipo', 'SUELDO')->orWhere(
                                                            'tipo',
                                                            'ADELANTO AL PERSONAL',
                                                        );
                                                    })
                                                    ->where('numero', $lista->id)
                                                    ->sum('cantidad');

                                            @endphp

                                            <td>{{ $diasRestantes }}</td>
                                            @if ($lista->memorandum > 3)
                                                <td style="background-color: red">
                                                    <label for="" style="color: white">Expulsión</label>
                                                    @livewire('users.editar-numero', ['iduser' => $lista->id], key($lista->name))
                                                </td>
                                            @else
                                                <td>@livewire('users.editar-numero', ['iduser' => $lista->id], key($lista->name))</td>
                                            @endif

                                            <td>
                                                {{ $gastoarea }}
                                            </td>
                                            <td>

                                                @livewire('users.editar-user', ['iduser' => $lista->id], key($lista->id))


                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $users->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
