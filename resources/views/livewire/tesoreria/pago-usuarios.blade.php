<div>
    <div class="card-body">
        <div>
            <div class="flex-wrap mb-3 d-flex">
                <div class="" style="display: flex;">
                    <label for="fecha-inicio">Desde:</label>
                    <input class="mr-2" type="date" id="fecha-inicio" wire:model="fechaInicioMes">

                    <label for="fecha-actual">Hasta:</label>
                    <input class="mr2" type="date" id="fecha-actual" wire:model="fechaActual">
                </div>
                <label>Modo: </label>
                <select class="" wire:model="modo">
                    <option value="">Todos</option>
                    <option value="Qr">QR</option>

                    <option value="Efectivo">Efectivo</option>
                </select>
                <label>Sucursal: </label>
                <select class="" wire:model="empresaseleccionada">
                    @foreach ($areas as $item)
                        <option value="{{ $item->area }}">{{ $item->area }}</option>
                    @endforeach
                </select>
                <button class="ml-2 btn btn-success" onclick="exportToExcel()"
                    style="display: flex; align-item:center;"><svg class="icon-32" width="32" viewBox="0 0 24 24"
                        fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path opacity="0.4"
                            d="M16.191 2H7.81C4.77 2 3 3.78 3 6.83V17.16C3 20.26 4.77 22 7.81 22H16.191C19.28 22 21 20.26 21 17.16V6.83C21 3.78 19.28 2 16.191 2Z"
                            fill="currentColor"></path>
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M8.07996 6.6499V6.6599C7.64896 6.6599 7.29996 7.0099 7.29996 7.4399C7.29996 7.8699 7.64896 8.2199 8.07996 8.2199H11.069C11.5 8.2199 11.85 7.8699 11.85 7.4289C11.85 6.9999 11.5 6.6499 11.069 6.6499H8.07996ZM15.92 12.7399H8.07996C7.64896 12.7399 7.29996 12.3899 7.29996 11.9599C7.29996 11.5299 7.64896 11.1789 8.07996 11.1789H15.92C16.35 11.1789 16.7 11.5299 16.7 11.9599C16.7 12.3899 16.35 12.7399 15.92 12.7399ZM15.92 17.3099H8.07996C7.77996 17.3499 7.48996 17.1999 7.32996 16.9499C7.16996 16.6899 7.16996 16.3599 7.32996 16.1099C7.48996 15.8499 7.77996 15.7099 8.07996 15.7399H15.92C16.319 15.7799 16.62 16.1199 16.62 16.5299C16.62 16.9289 16.319 17.2699 15.92 17.3099Z"
                            fill="currentColor"></path>
                    </svg> <span>Exportar a Excel</span></button>
            </div>
        </div>
        <div class="table-responsive">
            <table id="miTabla-users" class="table table-striped" role="grid" data-bs-toggle="data-table">
                <thead>
                    <tr class="ligth">
                        <th>ID</th>
                        <th>SUCURSAL</th>
                        <th>USUARIOS</th>
                        <th>OBTENIEDO</th>
                        <th>GASTOS</th>
                        <th>CAJA</th>
                        <th>MODO</th>
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
                        $totalsumamonto = 0;
                        $sumagasto = 0;
                    @endphp
                    @foreach ($internos as $lista)
                        @php
                            $totalsumainventario = 0;
                            $totalgastos = 0;
                            $gastoarea = 0;
                            if ($modo != 'Todos') {
                                $total_monto = DB::table('registropagos')
                                    ->where('iduser', $lista->id)
                                    ->where('sucursal', $empresaseleccionada)
                                    ->where('modo', 'ilike', '%' . $modo . '%')
                                    ->where('fecha', '<=', $fechaActual)
                                    ->where('fecha', '>=', $fechaInicioMes)
                                    ->sum(DB::raw('CAST(monto AS DECIMAL(10, 2))'));
                                $total_inventario = DB::table('registroinventarios')
                                    ->where('sucursal', $empresaseleccionada)
                                    ->where('iduser', $lista->id)
                                    ->where('modo', 'ilike', '%' . $modo . '%')
                                    ->where('fecha', '<=', $fechaActual)
                                    ->where('fecha', '>=', $fechaInicioMes)
                                    ->where('motivo', 'compra')
                                    ->sum(DB::raw('CAST(precio AS DECIMAL(10, 2))'));
                                $gastoarea = DB::table('gastos')
                                    ->where('area', $empresaseleccionada)
                                    ->where('modo', 'ilike', '%' . $modo . '%')
                                    ->where('nameuser', $lista->name)
                                    ->where('fechainicio', '<=', $fechaActual)
                                    ->where('fechainicio', '>=', $fechaInicioMes)
                                    ->sum('cantidad');
                                $sumagasto = $sumagasto + $gastoarea;
                                $totalgastos = $totalgastos + $gastoarea;
                                $totalsumamonto = $totalsumamonto + $total_monto + $total_inventario;
                            } else {
                                $total_monto = DB::table('registropagos')
                                    ->where('sucursal', $empresaseleccionada)
                                    ->where('iduser', $lista->id)
                                    ->where('fecha', '<=', $fechaActual)
                                    ->where('fecha', '>=', $fechaInicioMes)
                                    ->sum(DB::raw('CAST(monto AS DECIMAL(10, 2))'));
                                $total_inventario = DB::table('registroinventarios')
                                    ->where('sucursal', $empresaseleccionada)
                                    ->where('iduser', $lista->id)
                                    ->where('fecha', '<=', $fechaActual)
                                    ->where('fecha', '>=', $fechaInicioMes)
                                    ->where('motivo', 'compra')
                                    ->sum(DB::raw('CAST(precio AS DECIMAL(10, 2))'));
                                $gastoarea = DB::table('gastos')
                                    ->where('area', $empresaseleccionada)
                                    ->where('nameuser', $lista->name)
                                    ->where('fechainicio', '<=', $fechaActual)
                                    ->where('fechainicio', '>=', $fechaInicioMes)
                                    ->sum('cantidad');
                                $sumagasto = $sumagasto + $gastoarea;
                                $totalgastos = $totalgastos + $gastoarea;
                                $totalsumamonto = $totalsumamonto + $total_monto + $total_inventario;
                            }
                        @endphp
                        <tr>
                            <td>{{ $lista->id }}</td>
                            <td>{{ $lista->sucursal }}</td>
                            <td>{{ $lista->name }}</td>
                            <td>{{ $total_monto + $total_inventario }}</td>
                            <td>{{ $gastoarea }}</td>
                            <td>{{ $total_monto + $total_inventario - $totalgastos }}</td>
                            @if ($modo == '')
                                <td>Todo</td>
                            @else
                                <td>{{ $modo }}</td>
                            @endif
                        </tr>
                    @endforeach
                    <tr class="bg-gray">
                        <td style="color: white">TOTALES</td>
                        <td></td>
                        <td></td>

                        <td style="color: white">{{ $totalsumamonto }}</td>
                        <td style="color: white">{{ $sumagasto }}</td>
                        <td style="color: white">{{ $totalsumamonto - $sumagasto }}</td>
                        @if ($modo == '')
                            <td style="color: white">Todo</td>
                        @else
                            <td style="color: white">{{ $modo }}</td>
                        @endif
                    </tr>
                </tbody>
            </table>

        </div>

    </div>
</div>
