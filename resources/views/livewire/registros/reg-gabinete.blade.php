<div>
    @php
        $registroinv = DB::table('registroinventarios')
            ->where('motivo', 'personal')
            ->where('nombreproducto', 'ilike', '%' . $this->busquedagabi . '%')
            ->where('sucursal', 'ilike', '%' . $this->areaseleccionada . '%')
            ->where('fecha', '<=', $this->fechaActual)
            ->where('fecha', '>=', $this->fechaInicioMes)
            ->paginate(10);
    @endphp
    <div class="container-fluid">
        <div class="row">
            <!-- Filtro de búsqueda y selección -->
            <div class="col-md-3">
                <div class="border-0 shadow-sm card">
                    <div class="card-body">
                        <h5 class="text-center card-title">Filtros de Búsqueda</h5>

                        <div class="gap-3 d-flex flex-column">
                            <!-- Filtro de Sucursal -->
                            <div class="form-group">
                                <label for="sucursal" class="form-label">Sucursal:</label>
                                <select wire:model="areaseleccionada" class="form-select form-select-sm">
                                    <option value="">Todas</option>
                                    @foreach ($areas as $item)
                                        <option value="{{ $item->area }}">{{ $item->area }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Filtro de Fecha Desde -->
                            <div class="form-group">
                                <label for="fecha-inicio" class="form-label">Desde:</label>
                                <input type="date" id="fecha-inicio" wire:model="fechaInicioMes"
                                    class="form-control form-control-sm">
                            </div>

                            <!-- Filtro de Fecha Hasta -->
                            <div class="form-group">
                                <label for="fecha-actual" class="form-label">Hasta:</label>
                                <input type="date" id="fecha-actual" wire:model="fechaActual"
                                    class="form-control form-control-sm">
                            </div>

                            <!-- Filtro de Modo de Pago -->
                            <div class="form-group">
                                <label for="modopago" class="form-label">Modo de pago:</label>
                                <select wire:model="modopago" class="form-select form-select-sm">
                                    <option value="">Todas</option>
                                    <option value="Efectivo">Efectivo</option>
                                    <option value="QR">QR</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Registros de Productos Usados en Gabinete -->
            <div class="col-md-9">
                <div class="border-0 shadow-sm card">
                    <div class="card-body">
                        <h3 class="mb-3">Registro de Productos Usados en Gabinete</h3>

                        <!-- Filtro de búsqueda -->
                        <div class="mb-3 input-group" style="border-radius: 5px;">
                            <input type="text" class="form-control form-control-lg" wire:model="busquedagabi"
                                placeholder="Buscar Producto...">
                        </div>

                        <!-- Mostrar cantidad de resultados -->
                        <div class="mb-2">
                            <label>Se están mostrando: {{ $registroinvc }} registros.</label>
                        </div>

                        <!-- Tabla con los registros -->
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Fecha</th>
                                        <th>Producto</th>
                                        <th>Cantidad</th>
                                        <th>Monto</th>
                                        <th>Sucursal</th>
                                        <th>Responsable</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($registroinv as $item)
                                        <tr>
                                            <td>{{ $item->id }}</td>
                                            <td>{{ $item->created_at }}</td>
                                            <td>{{ $item->nombreproducto }}</td>
                                            <td>{{ $item->cantidad }}</td>
                                            <td>{{ $item->precio * $item->cantidad }}</td>
                                            <td>{{ $item->sucursal }}</td>
                                            @php
                                                $name = DB::table('users')->where('id', $item->iduser)->value('name');

                                            @endphp
                                            <td>{{ $name }}</td>
                                            <td>
                                                {{-- <a class="btn btn-sm btn-warning"
                                                    wire:click="$emit('editarProducto', {{ $item->id }})">
                                                    <i class="fas fa-edit"></i> Editar
                                                </a> --}}
                                                <a class="btn btn-sm btn-danger"
                                                    wire:click="$emit('eliminarGabinete', {{ $item->id }})">
                                                    <i class="fas fa-trash"></i> Eliminar
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach


                                </tbody>
                            </table>
                        </div>

                        <!-- Paginación -->
                        <div class="mt-3">

                            {{ $registroinv->links() }}

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
