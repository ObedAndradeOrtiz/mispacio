<div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3">
                <div class="border-0 shadow-sm card">
                    <div class="text-center card-header fw-bold">
                        <h3 class="card-title">Registro de ingresos de tratamientos</h3>
                    </div>
                    <div class="card-body">
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

                            <!-- Filtro de Responsable -->
                            <div class="form-group">
                                <label for="responsable" class="form-label">Responsable:</label>
                                <select wire:model="usuarioseleccionado" class="form-select form-select-sm">
                                    <option value="">Todos</option>
                                    @foreach ($users as $item)
                                        <option value="{{ $item->name }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-9">
                <div class="border-0 shadow-sm card">
                    <div class="card-body">
                        <div class="mb-4">
                            <h3>INGRESOS POR CONSULTA EN EFECTIVO</h3>
                        </div>

                        <div class="mb-4 input-group" style="border-radius: 5px;">
                            <input type="text" class="form-control form-control-lg" wire:model="busquedaefec"
                                placeholder="Buscar cliente...">
                        </div>

                        <div class="mb-3">
                            <label for="showing-records">Se están mostrando: {{ $total_monto_citasc }} pagos.</label>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>

                                        <th>MONTO</th>
                                        <th>CLIENTE</th>
                                        <th>FECHA/HORA</th>
                                        <th>RESPONSABLE</th>
                                        <th>SUCURSAL</th>
                                        <th>ACCION</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $pagado = 0; @endphp
                                    @foreach ($total_monto_citas as $lista)
                                        @php $pagado += $lista->monto; @endphp
                                        <tr>
                                            <td>{{ $lista->id }}</td>
                                            <td>{{ $lista->monto }}</td>
                                            <td>{{ $lista->nombrecliente }}</td>
                                            <td>{{ $lista->created_at }}</td>
                                            <td>{{ $lista->responsable }}</td>
                                            <td>{{ $lista->sucursal }}</td>
                                            <td class="d-flex">
                                                <a class="mr-1 btn btn-sm btn-warning"
                                                    wire:click="editarpago({{ $lista->id }})">
                                                    <i class="fas fa-edit"></i> Editar
                                                </a>
                                                <a class="btn btn-sm btn-danger"
                                                    wire:click="$emit('eliminarPagoCita',{{ $lista->id }})">
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
                            {{ $total_monto_citas->links() }}
                        </div>
                    </div>
                </div>
                <div class="mt-3 border-0 shadow-sm card">
                    <div class="card-body">
                        <div class="mb-4">
                            <h3>INGRESOS POR CONSULTA EN QR</h3>
                        </div>
                        <div class="mb-4 input-group" style="border-radius: 5px;">
                            <input type="text" class="form-control form-control-lg" wire:model="busquedaqr"
                                placeholder="Buscar cliente...">
                        </div>
                        <div class="mb-3">
                            <label for="showing-records">Se estan mostrando: {{ $total_monto_qr_listac }}
                                pagos.</label>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr class="ligth">
                                        <th>ID</th>
                                        <th>MONTO</th>
                                        <th>RESPONSABLE</th>
                                        <th>CLIENTE</th>
                                        <th>FECHA/HORA</th>
                                        <th>SUCURSAL</th>
                                        <th>ACCION</th>
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
                                    @php
                                        $pagado = 0;
                                    @endphp
                                    @foreach ($total_monto_qr_lista as $lista)
                                        @php
                                            $pagado = $pagado + $lista->monto;
                                        @endphp
                                        <tr>
                                            <td>{{ $lista->id }}</td>
                                            <td>{{ $lista->monto }}</td>
                                            <td>{{ $lista->responsable }}</td>
                                            <td>{{ $lista->nombrecliente }}</td>
                                            <td>{{ $lista->created_at }}</td>
                                            <td>{{ $lista->sucursal }}</td>
                                            <td>
                                                <a class="mr-1 btn btn-sm btn-warning"
                                                    wire:click="editarpago({{ $lista->id }})">
                                                    <i class="fas fa-edit"></i> Editar
                                                </a>
                                                <a class="btn btn-sm btn-danger"
                                                    wire:click="$emit('eliminarPagoCita',{{ $lista->id }})">
                                                    <i class="fas fa-trash"></i> Eliminar
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr class="bg-gray">
                                        <td style="color: white">Total</td>
                                        <td style="color: white">{{ $pagado }}</td>
                                        <td style="color: white"></td>
                                        <td style="color: white"></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        {{ $total_monto_qr_lista->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-modal wire:model="editar">
        <div class="px-6 py-4">
            <div class="text-lg font-medium text-gray-900">
                Editar pago (SE DEBE EDITAR TAMBIEN EL MONTO EN EL CLIENTE)
            </div>
            <div class="mt-4 text-sm text-gray-600">
                <form>
                    <div class="form-group">
                        <label class="form-label" for="">Monto de pago:</label>
                        <input type="number" class="form-control" id="exampleInputDisabled1"
                            wire:model.defer="registro.monto">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="">Fecha de pago:</label>
                        <input type="date" class="form-control" id="exampleInputDisabled1"
                            wire:model.defer="registro.fecha">

                    </div>
                    <div class="form-group">
                        <label class="form-label" for="">Modo de pago:</label>
                        <select class="mb-3 shadow-none form-select form-select-smmt-5" wire:model="registro.modo">

                            <option value="QR">Qr</option>
                            <option value="Efectivo">Efectivo</option>

                        </select>

                    </div>
                </form>
            </div>
        </div>

        <div class="flex flex-row justify-end px-6 py-4 text-right bg-gray-100">
            <label type="submit" class="btn btn-success" wire:click="guardartodo">Guardar</label>
        </div>
    </x-modal>
</div>
