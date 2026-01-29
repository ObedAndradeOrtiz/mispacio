<div>

    <div class="px-6 py-4">
        <div class="text-lg font-medium text-gray-900">
            Inventario de productos:
        </div>
        <div class="mt-4 ">
            <!-- Contenedor principal con centrado y espaciado adecuado -->
            <div class="justify-between mb-4 d-flex align-items-center" style="font-size: 1rem;">
                <div class="d-flex align-items-center flex-grow-1">
                    <!-- Aquí puede ir algún contenido adicional si es necesario -->
                </div>
                <div>
                    <!-- Aquí también puede ir contenido adicional -->
                </div>
                <!-- Botón para registrar con estilo más profesional -->
                <div class="justify-end d-flex">
                    <div class="justify-center mt-3">
                        <label style=" margin-right: 10px;">Modo de pago: </label>
                        <select class="form-select form-select-lg" wire:model="modo" style="width: auto;">
                            <option value="efectivo">Efectivo</option>
                            <option value="qr">QR</option>
                        </select>
                    </div>
                    <button wire:click="realizarCompra" class="text-white btn btn-warning btn-lg">
                        Registrar venta
                    </button>
                </div>
            </div>

            <!-- Información de la sucursal y cliente con márgenes y tamaños adecuados -->
            <div class="mb-4">
                <label style="">Sucursal: {{ Auth::user()->sucursal }}</label>
                <br><br>
                <label for="" style="">Cliente:
                    {{ $cliente->name }}</label>
            </div>

            <!-- Información de fecha y personal con mejor organización -->
            <div class="mb-4">
                <div class="justify-between mb-3 d-flex align-items-center">
                    <div class="w-50">
                        <label for="fecha" style="font-size: 1rem;">Fecha realizado:</label>
                        <input type="date" wire:model="fecha" class="form-control">
                    </div>
                    <div class="w-50">
                        <label for="personalseleccionado" style="font-size: 1rem;">Personal que vendió:</label>
                        <select name="personalseleccionado" id="personalseleccionado" wire:model="personalseleccionado"
                            class="form-control">
                            @foreach ($personal as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive" style="font-size: 1vw;">
            <table class="table table-bordered">
                <thead style="font-size: 0.7vw;">
                    <tr>
                        <th>COD.</th>
                        <th>PRODUCTO SELECCIONADO</th>
                        <th>PRECIO</th>
                        <th>CANTIDAD</th>
                        <th>TOTAL</th>
                    </tr>
                </thead>
                <tbody style="font-size: 0.6vw;">
                    @php
                        $total = 0;
                    @endphp
                    @foreach ($cantidades as $id => $cantidad)
                        @php
                            $producto = DB::table('productos')
                                ->select('nombre', 'idinventario')
                                ->where('id', $id)
                                ->first();
                        @endphp
                        <tr>
                            @if ($producto)
                                <td>{{ $producto->idinventario }}</td>
                                <td>{{ $producto->nombre }}</td>

                                <td>{{ floatval($this->precios[$id]) }}</td>
                                <td>{{ $cantidad }}</td>
                                <td>{{ floatval($this->precios[$id]) * $cantidad }}</td>
                                @php
                                    $total = $total + floatval($this->precios[$id]) * $cantidad;
                                @endphp
                            @endif
                        </tr>
                    @endforeach
                    <tr>
                        <td>TOTAL</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>{{ $total }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div>
            <p>
                Se estan mostrando {{ $cantidad }} resultados...
            </p>
        </div>
        <div class="d-flex">
            <input class="mt-1" type="text" wire:model="search" placeholder="Buscar productos..."
                style="width: 100%;" />
        </div>
        <div class="table-responsive" style="height: 35vh; overflow-y:scroll; font-size: 0.6vw;">
            <table class="table table-bordered">
                <thead style="font-size: 0.7vw;">
                    <tr>
                        <th>Cod.</th>
                        <th>Producto</th>
                        <td>Exp.</td>
                        <td>Rest. Dias</td>
                        <th>Cantidad</th>
                        <th>Precio</th>
                        <th>Stock</th>
                    </tr>
                </thead>
                <tbody>
                    @if (isset($productos))
                        @foreach ($productos as $producto)
                            <tr>
                                <td>{{ $producto->idinventario }}</td>
                                <td>{{ $producto->nombre }}</td>
                                <td>{{ $producto->expiracion }}</td>
                                @php
                                    $fechaActual = new DateTime();
                                    $fechaFutura = new DateTime($producto->expiracion);

                                    // Calcular la diferencia entre ambas fechas
                                    $diferencia = $fechaActual->diff($fechaFutura);

                                @endphp
                                <td>{{ $diferencia->days }}</td>
                                <td>
                                    <input style="font-size: 0.7vw;" type="number"
                                        wire:model="cantidades.{{ $producto->id }}" min="0">
                                </td>
                                <td>
                                    <input style="font-size: 0.7vw;" type="number"
                                        wire:model="precios.{{ $producto->id }}" value="{{ $producto->precio }}">

                                </td>
                                <td>
                                    <label for="">{{ $producto->cantidad }}</label>
                                </td>
                            </tr>
                        @endforeach
                        <div>

                        </div>
                    @endif

                </tbody>
            </table>
        </div>
    </div>

</div>
