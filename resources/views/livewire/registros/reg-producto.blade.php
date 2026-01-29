<div>
    <div class="container-fluid">
        <div class="row">
            <!-- Filtro de búsqueda y selección -->
            <div class="col-md-3">
                <div class="border-0 shadow-sm card">
                    <div class="card-body">
                        <h5 class="card-title">Filtros</h5>

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

            <!-- Registros de productos vendidos -->
            <div class="col-md-9">
                <div class="border-0 shadow-sm card">
                    <div class="card-body">
                        <h3 class="mb-3">Registro de Productos Vendidos</h3>

                        <!-- Filtro de búsqueda -->
                        <div class="mb-3 input-group" style=" border-radius: 5px;">
                            <input type="text" class="form-control form-control-lg" wire:model="busqueda"
                                placeholder="Buscar Producto...">
                        </div>

                        <!-- Mostrar cantidad de resultados -->
                        <div class="mb-2">
                            <label>Se están mostrando: {{ $registroinvcomprac }} pagos.</label>
                        </div>

                        <!-- Tabla con los registros -->
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Fecha</th>
                                        <th>Producto</th>
                                        <th>Tipo</th>
                                        <th>Modo</th>
                                        <th>Cantidad</th>
                                        <th>Monto</th>
                                        @if ($this->areaseleccionada == '')
                                            <th>Sucursal</th>
                                        @endif

                                        <th>Responsable</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($registroinvcompra as $item)
                                        <tr>
                                            <td>{{ $item->id }}</td>
                                            <td>{{ $item->created_at }}</td>
                                            <td>{{ $item->nombreproducto }}</td>
                                            @if ($item->motivo == 'compra')
                                                <td>VENTA</td>
                                            @endif
                                            @if ($item->motivo == 'farmacia')
                                                <td>FARMACIA</td>
                                            @endif
                                            <td>{{ $item->modo }}</td>
                                            <td>{{ $item->cantidad }}</td>
                                            <td>{{ $item->precio * $item->cantidad }}</td>
                                            @if ($this->areaseleccionada == '')
                                                <td>{{ $item->sucursal }}</td>
                                            @endif
                                            @php
                                                $name = DB::table('users')->where('id', $item->iduser)->value('name');

                                            @endphp
                                            <td>{{ $name }}</td>
                                            <td>
                                                <a class="btn btn-sm btn-warning"
                                                    wire:click="imprimirTrapaso({{ $item->id }})">
                                                    <i class="fas fa-print"></i>
                                                </a>
                                                <a class="btn btn-sm btn-primary"
                                                    wire:click="$emit('editarProducto', {{ $item->id }})">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a class="btn btn-sm btn-danger"
                                                    wire:click="$emit('eliminarProducto', {{ $item->id }})">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div>
                                {{ $registroinvcompra->links() }}
                            </div>
                        </div>


                    </div>
                </div>
                <div class="mt-4 border-0 shadow-sm card">
                    <div class="card-body">
                        <h3>IMPRESIONES DE VENTAS</h3>
                        <div class="table-responsive">
                            <table id="mitablaregistros1" class="table table-striped" role="grid"
                                data-bs-toggle="data-table">
                                <thead>
                                    <tr>
                                        <th>ORIGEN</th>
                                        <th>DESTINO</th>
                                        <th>FECHA</th>
                                        <th>ACCION</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php

                                    @endphp
                                    @foreach ($registroimpresiones as $item)
                                        <tr>
                                            <td>{{ $item->sucursal_origen }}</td>
                                            <td>{{ $item->sucursal_destino }}</td>
                                            <td>{{ $item->fecha }}</td>


                                            <td> <a class="mt-1 btn btn-sm btn-icon btn-warning d-flex align-items-center"
                                                    data-bs-toggle="tooltip" data-bs-placement="top" title="IMPRIMIR"
                                                    data-original-title="Edit"
                                                    wire:click="imprimirTrapaso({{ $item->id }})">

                                                    <span class="ms-1" style="font-size: 8px;">Imprimir</span>
                                                </a></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div>
                                {{ $registroimpresiones->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if ($registro)
        <x-modal wire:model="editar">
            <div class="px-6 py-4">
                <div class="text-lg font-medium text-gray-900">
                    Editar venta de: {{ $registro->nombreproducto }}
                </div>
                <div class="mt-4 text-sm text-gray-600">
                    <form>
                        <div class="form-group">
                            <label class="form-label" for="">Monto ingresado:</label>
                            <input type="number" class="form-control" id="exampleInputDisabled1"
                                wire:model.defer="registro.precio">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="">Fecha de venta:</label>
                            <input type="date" class="form-control" id="exampleInputDisabled1"
                                wire:model.defer="registro.fecha">

                        </div>
                        <div class="form-group">
                            <label class="form-label" for="">Modo de pago:</label>
                            <select class="mb-3 shadow-none form-select form-select-smmt-5" wire:model="registro.modo">
                                <option value="qr">Qr</option>
                                <option value="efectivo">Efectivo</option>
                            </select>
                        </div>
                    </form>
                </div>
            </div>

            <div class="flex flex-row justify-end px-6 py-4 text-right bg-gray-100">
                <label type="submit" class="btn btn-success" wire:click="guardartodo">Guardar</label>
            </div>
        </x-modal>
    @endif

</div>
