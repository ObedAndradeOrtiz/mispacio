<div>
    <div class="card-body">
        <div>
            <div class="flex flex-row justify-end py-2 mr-2">
                <div class="" style="display: flex;">
                    <label for="fecha-inicio mr-2">Desde:</label>
                    <input class="mr-2" type="date" id="fecha-inicio" wire:model="fechaInicioMes">

                    <label for="fecha-actual mr-2">Hasta:</label>
                    <input class="mr2" type="date" id="fecha-actual" wire:model="fechaActual">
                </div>
                <label class="">Modo: </label>
                <select class="" wire:model="modo">
                    <option value="">Todos</option>
                    <option value="efectivo">Efectivo</option>
                    <option value="qr">Qr</option>

                </select>
                <label class="">Sucursal: </label>
                <select class="" wire:model="empresaseleccionada">
                    <option value="">Todas</option>
                    @foreach ($areas as $lista)
                        <option value="{{ $lista->area }}">{{ $lista->area }}</option>
                    @endforeach
                </select>

            </div>
        </div>


        <div>
            <h3>Historial de pagos por consultas</h3>
        </div>

        <div class="table-responsive">
            <table id="user-list-table" class="table table-striped" role="grid" data-bs-toggle="data-table">
                <thead>
                    <tr class="ligth">
                        <th>SUCURSAL</th>
                        <th>Monto</th>
                        <th>Modo</th>
                        <th>Responsable</th>
                        <th>Cliente</th>

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
                    @foreach ($total_monto as $lista)
                        @php
                            $pagado = $pagado + $lista->monto;
                        @endphp
                        <tr>
                            <td>{{ $lista->sucursal }}</td>
                            <td>{{ $lista->monto }}</td>
                            <td>{{ $lista->modo }}</td>
                            <td>{{ $lista->responsable }}</td>
                            <td>{{ $lista->nombrecliente }}</td>


                        </tr>
                    @endforeach
                    <tr class="bg-gray">
                        <td style="color: white">Total</td>
                        <td style="color: white">{{ $pagado }}</td>
                        <td style="color: white"></td>
                        <td style="color: white"></td>
                        <td> </td>
                        <td></td>

                    </tr>
                </tbody>
            </table>

        </div>
        <div>
            <h3>Historial de pagos por productos</h3>
        </div>

        <div class="table-responsive">
            <table id="user-list-table" class="table table-striped" role="grid" data-bs-toggle="data-table">
                <thead>
                    <tr class="ligth">
                        <th>SUCURSAL</th>
                        <th>Monto</th>
                        <th>Modo</th>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Responsable</th>

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
                    @foreach ($total_inventario as $lista)
                        @php
                            $pagado = $pagado + $lista->precio;
                        @endphp
                        <tr>
                            <td>{{ $lista->sucursal }}</td>
                            <td>{{ $lista->precio }}</td>
                            <td>{{ $lista->modo }}</td>
                            <td>{{ $lista->nombreproducto }}</td>
                            <td>{{ $lista->cantidad }}</td>
                            @php
                                $responsable = DB::table('users')
                                    ->where('id', $lista->iduser)
                                    ->pluck('name')
                                    ->first();
                            @endphp
                            <td>{{ $responsable }}</td>



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
        <div>
            <h3>Historial de productos utilizados</h3>
        </div>

        <div class="table-responsive">
            <table id="user-list-table" class="table table-striped" role="grid" data-bs-toggle="data-table">
                <thead>
                    <tr class="ligth">
                        <th>SUCURSAL</th>
                        <th>Monto</th>
                        <th>Motivo</th>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Responsable</th>
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
                    @foreach ($total_inventario_uso as $lista)
                        @php
                            $pagado = $pagado + $lista->precio;
                        @endphp
                        <tr>
                            <td>{{ $lista->sucursal }}</td>
                            <td>{{ $lista->precio }}</td>
                            <td>{{ $lista->motivo }}</td>
                            <td>{{ $lista->nombreproducto }}</td>
                            <td>{{ $lista->cantidad }}</td>
                            @php
                                $responsable = DB::table('users')
                                    ->where('id', $lista->iduser)
                                    ->pluck('name')
                                    ->first();
                            @endphp
                            <td>{{ $responsable }}</td>


                        </tr>
                    @endforeach
                    <tr class="bg-gray">
                        <td style="color: white">Total</td>
                        <td style="color: white">{{ $pagado }}</td>
                        <td style="color: white"></td>
                        <td style="color: white"></td>
                        <td></td>
                        <td></td>

                    </tr>
                </tbody>
            </table>

        </div>
        <div>
            <h3>Historial de productos traspasados</h3>
        </div>

        <div class="table-responsive">
            <table id="user-list-table" class="table table-striped" role="grid" data-bs-toggle="data-table">
                <thead>
                    <tr class="ligth">
                        <th>SUCURSAL</th>
                        <th>Monto</th>
                        <th>Motivo</th>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Responsable</th>
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
                    @foreach ($total_inventario_traspaso as $lista)
                        @php
                            $pagado = $pagado + $lista->precio;
                        @endphp
                        <tr>
                            <td>{{ $lista->sucursal }}</td>
                            <td>{{ $lista->precio }}</td>
                            <td>{{ $lista->motivo }}</td>
                            <td>{{ $lista->nombreproducto }}</td>
                            <td>{{ $lista->cantidad }}</td>
                            @php
                                $responsable = DB::table('users')
                                    ->where('id', $lista->iduser)
                                    ->pluck('name')
                                    ->first();
                            @endphp
                            <td>{{ $responsable }}</td>


                        </tr>
                    @endforeach
                    <tr class="bg-gray">
                        <td style="color: white">Total</td>
                        <td style="color: white">{{ $pagado }}</td>
                        <td style="color: white"></td>
                        <td style="color: white"></td>
                        <td></td>
                        <td></td>

                    </tr>
                </tbody>
            </table>

        </div>
    </div>
</div>
