<div class="p-6 bg-white rounded-lg shadow-md card" style="margin: 1% 2%;">
    <div class="card-body">
        <div class="mb-4 text-2xl font-bold text-gray-800">🧾 Venta de Productos – Farmacia - Traspasos</div>

        {{-- Sección: Selección de datos generales --}}
        <div class="grid grid-cols-1 gap-4 mb-6 md:grid-cols-2 lg:grid-cols-3">
            <div>
                <label class="block mb-1 font-semibold">Motivo de uso:</label>
                <select wire:model="motivo" class="w-full form-select">
                    <option value="personal">Gabinete</option>
                    <option value="farmacia">Farmacia</option>
                </select>
            </div>

            <div>
                <label class="block mb-1 font-semibold">Sucursal:</label>
                <select wire:model="sucursalseleccionada" class="w-full form-select">
                    @foreach ($areas as $item)
                        <option value="{{ $item->id }}">{{ $item->area }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block mb-1 font-semibold">Fecha:</label>
                <input type="date" wire:model="fecha" class="w-full form-input">
            </div>

            <div>
                <label class="block mb-1 font-semibold">Personal responsable:</label>
                <select wire:model="personalseleccionado" class="w-full form-select">
                    @foreach ($personal as $item)
                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                    @endforeach
                </select>
            </div>

            @if ($motivo == 'farmacia')
                <div>
                    <label class="block mb-1 font-semibold">Monto QR:</label>
                    <input type="number" step="0.01" wire:model="montoQr" class="w-full form-input">
                </div>
                <div>
                    <label class="block mb-1 font-semibold">Monto Efectivo:</label>
                    <input type="number" step="0.01" wire:model="montoEfectivo" class="w-full form-input">
                </div>
            @endif
        </div>

        {{-- Sección: Acciones --}}
        <div class="mb-6">
            @if ($motivo == 'traspaso')
                <button wire:click="realizartraspaso" class="btn btn-success">Traspasar</button>
            @elseif ($motivo == 'personal' || $motivo == 'compra')
                <button wire:click="realizarCompra" class="btn btn-warning">Registrar</button>
            @elseif ($motivo == 'farmacia')
                <button wire:click="realizarfarmacia" class="btn btn-warning">Vender</button>
            @endif
        </div>

        {{-- Sección: Detalles Farmacia --}}
        @if ($motivo == 'farmacia')
            <div class="mb-4">
                <label class="block mb-1 font-semibold">Nombre del cliente:</label>
                <input type="text" wire:model="nombre" class="w-full form-input">
            </div>
            <div class="mb-4 text-xl font-semibold text-right text-blue-600">
                Total a pagar: Bs. {{ $pagar }}
            </div>
        @endif

        {{-- Sección: Tabla de Productos Seleccionados --}}
        <div class="mb-6 overflow-x-auto">
            <table class="table w-full text-sm table-bordered">
                <thead class="text-gray-700 bg-gray-100">
                    <tr>
                        <th>Código</th>
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
                        @endphp
                        @if ($producto)
                            <tr>
                                <td>{{ $producto->idinventario }}</td>
                                <td>{{ $producto->nombre }}</td>
                                <td>Bs. {{ floatval($this->precios[$id]) }}</td>
                                <td>{{ $cantidad }}</td>
                                <td>Bs. {{ floatval($this->precios[$id]) * $cantidad }}</td>
                            </tr>
                            @php $total += floatval($this->precios[$id]) * $cantidad; @endphp
                        @endif
                    @endforeach
                    <tr class="font-bold text-blue-700 bg-gray-50">
                        <td colspan="4" class="text-right">TOTAL:</td>
                        <td>Bs. {{ $total }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Sección: Buscador y Productos --}}
        <div class="mb-4">
            <input type="text" wire:model="search" placeholder="Buscar productos..." class="w-full form-input">
        </div>

        <div class="overflow-x-auto">
            <table class="table w-full text-sm table-bordered">
                <thead class="text-gray-700 bg-gray-100">
                    <tr>
                        <th>Código</th>
                        <th>Producto</th>
                        <th>Expiración</th>
                        <th>Días Restantes</th>

                        <th>Precio</th>
                        <th>Cantidad</th>
                        <th>Stock</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($productos as $producto)
                        @php
                            $fechaActual = new DateTime();
                            $fechaFutura = new DateTime($producto->expiracion);
                            $diferencia = $fechaActual->diff($fechaFutura);
                        @endphp
                        <tr>
                            <td>{{ $producto->idinventario }}</td>
                            <td>{{ $producto->nombre }}</td>
                            <td>{{ $producto->expiracion }}</td>
                            <td>{{ $diferencia->days }}</td>
                            <td>
                                <input type="number" step="0.01" wire:model="precios.{{ $producto->id }}"
                                    class="w-24 form-input">
                            </td>
                            <td>
                                <input type="number" min="0" wire:model="cantidades.{{ $producto->id }}"
                                    class="w-20 form-input">
                            </td>

                            <td>{{ $producto->cantidad }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
