<div>
    <div class="px-6 py-4">
        <div class="mb-3 text-lg text-gray-900 fw-bold">
            Traspaso de productos entre sucursales
        </div>

        <div class="p-4 mb-4 rounded shadow-sm bg-light">
            <div class="row">
                <div class="mb-3 col-lg-6">
                    <label class="fw-semibold">Sucursal de origen:</label>
                    <select class="form-select form-select-lg" wire:model="sucursalseleccionada">
                        <option value="">Seleccione sucursal</option>
                        @foreach ($areas as $item)
                            <option value="{{ $item->id }}">{{ $item->area }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3 col-lg-6">
                    <label class="fw-semibold">Sucursal de destino:</label>
                    <select class="form-select form-select-lg" wire:model="areaseleccionada">
                        <option value="">Seleccione sucursal</option>
                        @foreach ($areas as $item)
                            <option value="{{ $item->area }}">{{ $item->area }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3 col-lg-6">
                    <label class="fw-semibold">Fecha de traspaso:</label>
                    <input type="date" class="form-control" wire:model="fecha">
                </div>

                <div class="mb-3 col-lg-6">
                    <label class="fw-semibold">Responsable del traspaso:</label>
                    <select class="form-select" wire:model="personalseleccionado" disabled>
                        <option value="">Seleccione personal</option>
                        @foreach ($personal as $item)
                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mt-3 col-lg-12 text-end">
                    <button wire:click="realizartraspaso" class="px-5 btn btn-primary">
                        Realizar Traspaso
                    </button>
                </div>
            </div>
        </div>

        <div class="mb-3 table-responsive" style="font-size: 1vw;">
            <table class="table w-full text-sm table-bordered">
                <thead class="text-gray-700 bg-gray-100">
                    <tr>
                        <th>Cod.</th>
                        <th>Producto</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @php $total = 0; @endphp
                    @foreach ($cantidades as $id => $cantidad)
                        @php
                            $producto = DB::table('productos')
                                ->select('nombre', 'idinventario')
                                ->where('id', $id)
                                ->first();
                            $precio = floatval($this->precios[$id]);
                            $subtotal = $precio * $cantidad;
                            $total += $subtotal;
                        @endphp
                        @if ($producto)
                            <tr>
                                <td>{{ $producto->idinventario }}</td>
                                <td>{{ $producto->nombre }}</td>
                                <td>{{ $precio }}</td>
                                <td>{{ $cantidad }}</td>
                                <td>{{ $subtotal }}</td>
                            </tr>
                        @endif
                    @endforeach
                    <tr>
                        <td colspan="4" class="text-end fw-bold">TOTAL</td>
                        <td class="fw-bold">{{ $total }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="mb-3">
            <label for="" class="fw-semibold">Buscar productos:</label>
            <input class="form-control" type="text" wire:model="search" placeholder="Buscar por nombre o código...">
        </div>

        <div class="table-responsive" style="font-size: 1vw;">
            <table class="table w-full text-sm table-bordered">
                <thead class="text-gray-700 bg-gray-100">
                    <tr>
                        <th>Cod.</th>
                        <th>Producto</th>
                        <th>Expiración</th>
                        <th>Rest. Días</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                        <th>Stock</th>
                    </tr>
                </thead>
                <tbody>
                    @if (isset($productos))
                        @foreach ($productos as $producto)
                            @php
                                $fechaActual = new DateTime();
                                $fechaExp = new DateTime($producto->expiracion);
                                $restaDias = $fechaActual->diff($fechaExp)->days;
                            @endphp
                            <tr>
                                <td>{{ $producto->idinventario }}</td>
                                <td>{{ $producto->nombre }}</td>
                                <td>{{ $producto->expiracion }}</td>
                                <td>{{ $restaDias }}</td>
                                <td>
                                    <input type="number" class="form-control form-control-sm"
                                        wire:model="cantidades.{{ $producto->id }}" min="0">
                                </td>
                                <td>
                                    <input type="number" class="form-control form-control-sm"
                                        wire:model="precios.{{ $producto->id }}" value="{{ $producto->precio }}">
                                </td>
                                <td>{{ $producto->cantidad }}</td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
