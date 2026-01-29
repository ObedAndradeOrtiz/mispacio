<div>
    <div class="px-6 py-4">
        <div class="mb-3 text-lg text-gray-900 fw-bold">
            Traspaso de productos desde Almacén
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="" class="fw-semibold">Buscar productos:</label>
                            <input class="form-control" type="text" wire:model="search"
                                placeholder="Buscar por nombre o código...">
                        </div>
                        <div class="table-responsive" style="font-size: 1vw;">
                            <table class="table w-full text-sm table-bordered">
                                <thead class="text-gray-700 bg-gray-100">
                                    <tr>

                                        <th>Producto</th>
                                        <th>Lote</th>
                                        <th>Stock</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($productos_almacen as $item)
                                   
                                        <tr>

                                            <td>{{ $item->nombreproducto }}</td>
                                            <td>{{ $item->nombrelote }}</td>
                                            <td>{{ $item->cantidad_actual }}</td>
                                            <td><button class="btn btn-success"
                                                    wire:click='AgregarProducto("{{ $item->id }}")'>Agregar
                                                </button></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mb-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="p-4 mb-4 rounded shadow-sm bg-light">
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="fw-semibold">Tipo de lote:</label>
                                    <select class="form-select form-select-lg" wire:model="tipo_lote">
                                        <option value="">Todos</option>
                                        <option value="local">Local</option>
                                        <option value="produccion">Produccion</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="fw-semibold">Lote de origen:</label>
                                    <select class="form-select form-select-lg" wire:model="lote_seleccionado">
                                        <option value="">Todos</option>
                                        @foreach ($lotes as $item)
                                            <option value="{{ $item->id }}">{{ $item->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>


                            <label class="fw-semibold">Sucursal de destino:</label>
                            <select class="form-select form-select-lg" wire:model="areaseleccionada">
                                <option value="">Seleccione sucursal</option>
                                @foreach ($areas as $item)
                                    <option value="{{ $item->area }}">{{ $item->area }}</option>
                                @endforeach
                            </select>

                            <label class="fw-semibold">Fecha de traspaso:</label>
                            <input type="date" class="form-control" wire:model="fecha">

                            <label class="fw-semibold">Responsable del traspaso:</label>
                            <select class="form-select" wire:model="personalseleccionado" disabled>
                                <option value="">Seleccione personal</option>
                                @foreach ($personal as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3 table-responsive" style="font-size: 1vw;">
                            <table class="table w-full text-sm table-bordered">
                                <thead class="text-gray-700 bg-gray-100">
                                    <tr>

                                        <th>Producto</th>
                                        <th>Lote</th>
                                        <th>Cantidad</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($productos_seleccionados as $id => $cantidad)
                                        @php
                                            $producto = DB::table('produccions')->where('id', $id)->first();
                                        @endphp
                                        <tr>
                                            <td>{{ $producto->nombreproducto }}</td>
                                            <td>{{ $producto->nombrelote }}</td>
                                            <td>
                                                <div class="d-flex align-items-center justify-content-center">
                                                    <button class="btn btn-sm btn-secondary"
                                                        wire:click="disminuirCantidad({{ $id }})">-</button>
                                                    <span class="mx-2 text-center"
                                                        style="min-width: 30px;">{{ $cantidad }}</span>
                                                    <button class="btn btn-sm btn-secondary"
                                                        wire:click="aumentarCantidad({{ $id }})">+</button>
                                                </div>

                                            </td>
                                            <td>
                                                <!-- Opcional: Botón para eliminar producto -->
                                                <button class="btn btn-sm btn-danger"
                                                    wire:click="eliminarProducto({{ $id }})"><span
                                                        class="text-white"><i class="fas fa-trash"></i> </span></button>
                                            </td>
                                        </tr>
                                    @endforeach


                                </tbody>
                            </table>
                            <div class="mt-3 col-lg-12 text-end">
                                <button wire:click="realizartraspaso" class="px-5 btn btn-primary">
                                    Realizar Traspaso
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
