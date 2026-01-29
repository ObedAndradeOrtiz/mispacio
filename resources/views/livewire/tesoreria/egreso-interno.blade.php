<div>
    <style>
        .container {
            display: flex;
            flex-direction: column;
        }

        .flex-row {
            display: flex;
            flex-direction: row;

        }

        .justify-between {
            justify-content: space-between;
        }

        .divider {
            height: 2px;
            background-color: #898282;
            margin-top: 10px;
            margin-bottom: 10px;
        }

        .boton-container {
            display: flex;

        }

        .boton {
            padding: 10px 20px;
            font-size: 16px;
            width: 30%;
        }

        @media screen and (max-width: 600px) {
            .boton {
                font-size: 9px;
                width: 30%;
            }
        }
    </style>
    <div>

    </div>
    <div class="col-sm-12">
        <div>
            <select name="" id="" wire:model="lista">
                <option value="historial">HISTORIAL</option>
                <option value="resumen">RESUMEN POR SUCURSAL</option>
                <option value="planilla">PLANILLA DE PAGOS</option>
                <option value="csv">IMPORTAR DATOS CSV</option>
            </select>
        </div>
        @if ($lista == 'historial')
            <div class="flex flex-row justify-end">
                <div>
                    <label for="fecha-inicio">Desde:</label>
                    <input class="shadow-none form-select form-select-smmt-5" type="date" id="fecha-inicio"
                        wire:model="fechaInicioMes">
                </div>
                <div>
                    <label for="fecha-actual">Hasta:</label>
                    <input class="shadow-none form-select form-select-smmt-5" type="date" id="fecha-actual"
                        wire:model="fechaActual">
                </div>
                <div class="">
                    <label>Sucursal: </label>
                    <select class="shadow-none form-select form-select-smmt-5" wire:model="sucursal">
                        <option value="">Todas</option>
                        @foreach ($areas as $item)
                            <option value="{{ $item->area }}">{{ $item->area }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mr-3">
                    <label>Modo: </label>
                    <select class="ml-2 shadow-none form-select form-select-smmt-5" wire:model="modogeneral">
                        <option>Todos</option>
                        <option>QR</option>
                        <option>Efectivo</option>
                    </select>
                </div>
                <div class="form-group" style=" display: flex; flex-direction: column;">
                    <label>Tipo de Gasto: </label>
                    <select id="tipogasto" class="mb-3 shadow-none form-select form-select-smmt-5"
                        wire:model="tipogasto">
                        <option>Seleccionar:</option>
                        <option value="AGUA POTABLE">AGUA POTABLE</option>
                        <option value="ALQUILER">ALQUILER</option>
                        <option value="GAS">GAS</option>
                        <option value="IMPUESTOS">IMPUESTOS</option>
                        <option value="LUZ ELECTRICA">LUZ ELECTRICA</option>
                        <option value="INTERNET/TEL">INTERNET/TEL</option>
                        <option value="Dra. PAOLA">Dra. PAOLA</option>
                        <option value="Sr. ALEXANDRE">Sr. ALEXANDRE</option>
                        <option value="ADELANTO AL PERSONAL">ADELANTO AL PERSONAL</option>
                        <option value="MATERIAL CAFETERIA">MATERIAL CAFETERIA</option>
                        <option value="MATERIAL ESCRITORIO">MATERIAL ESCRITORIO</option>
                        <option value="MATERIAL LIMPIEZA">MATERIAL LIMPIEZA</option>
                        <option value="MATERIAL DE PROCEDIMIENTOS">MATERIAL DE PROCEDIMIENTOS</option>
                        <option value="MATERIAL PARA EVENTOS">MATERIAL PARA EVENTOS</option>
                        <option value="MATERIAL PARA MANTENIMIENTO">MATERIAL PARA MANTENIMIENTO</option>
                        <option value="MANTENIMIENTO DE EQUIPOS">MANTENIMIENTO DE EQUIPOS</option>
                        <option value="MANTENIMIENTO DE SUCURSAL">MANTENIMIENTO DE SUCURSAL</option>
                        <option value="COMPRA DE EQUIPO">COMPRA DE EQUIPO</option>
                        <option value="COMPRA DE MUEBLE">COMPRA DE MUEBLE</option>
                        <option value="MERIENDAS">MERIENDAS</option>
                        <option value="PUBLICIDAD">PUBLICIDAD</option>
                        <option value="SERVICIOS PROFESIONALES">SERVICIOS PROFESIONALES</option>
                        <option value="TRAMITES">TRAMITES</option>
                        <option value="TRANSPORTE">TRANSPORTE</option>
                        <option value="SUELDO">PAGO SUELDO</option>
                        <option value="OTRO">OTRO</option>
                    </select>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <div>
                        <h3 class="mb-2 ">Historial de egresos</h3>
                    </div>
                    <table id="user-list-table" class="table table-striped" role="grid" data-bs-toggle="data-table">
                        <thead>
                            <tr class="ligth">
                                <th>Tipo</th>
                                <th>Detalle de gasto</th>

                                <th>Monto</th>
                                <th>FECHA</th>
                                <th>Modo</th>
                                <th>Responsable</th>
                                <th>SUCURSAL</th>
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
                                $sumador = 0;
                                if ($modogeneral == 'Todos') {
                                    $gastos = DB::table('gastos')
                                        ->where('fechainicio', '>=', $fechaInicioMes)
                                        ->where('area', 'ilike', '%' . $sucursal . '%')
                                        ->where('tipo', 'ilike', '%' . $this->tipogasto . '%')
                                        ->where('fechainicio', '<=', $fechaActual)
                                        ->get();
                                } else {
                                    $gastos = DB::table('gastos')
                                        ->where('modo', $modogeneral)
                                        ->where('area', 'ilike', '%' . $sucursal . '%')
                                        ->where('tipo', 'ilike', '%' . $this->tipogasto . '%')
                                        ->where('fechainicio', '>=', $fechaInicioMes)
                                        ->where('fechainicio', '<=', $fechaActual)
                                        ->get();
                                }

                            @endphp
                            @foreach ($gastos as $lista)
                                <tr>

                                    <td>{{ $lista->tipo }}</td>
                                    <td style="font-size: 10px;">{{ $lista->empresa }}</td>
                                    <td>{{ $lista->cantidad }}</td>
                                    <td>{{ $lista->fechainicio }}</td>
                                    <td>{{ $modogeneral }}</td>
                                    <td>{{ $lista->nameuser }}</td>
                                    <td>{{ $lista->area }}</td>
                                </tr>
                            @endforeach
                            <tr class="bg-gray">
                                <td style="color: white">TOTALES</td>
                                <td></td>
                                @php
                                    if ($modogeneral == 'Todos') {
                                        $sumador = DB::table('gastos')
                                            ->where('fechainicio', '>=', $fechaInicioMes)
                                            ->where('area', 'ilike', '%' . $sucursal . '%')
                                            ->where('fechainicio', '<=', $fechaActual)
                                            ->sum('cantidad');
                                    } else {
                                        $sumador = DB::table('gastos')
                                            ->where('fechainicio', '>=', $fechaInicioMes)
                                            ->where('area', 'ilike', '%' . $sucursal . '%')
                                            ->where('fechainicio', '<=', $fechaActual)
                                            ->where('modo', $modogeneral)
                                            ->sum('cantidad');
                                    }
                                @endphp
                                <td style="color: white">{{ $sumador }}</td>
                                <td></td>
                                <td style="color: white">{{ $modogeneral }}</td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
        @if ($lista == 'planilla')
            @livewire('planilla.lista-planilla')
        @endif
        @if ($lista == 'csv')
            @livewire('tesoreria.subir-csv')
        @endif
        @if ($lista == 'resumen')
            @livewire('tesoreria.lista-egresos')
        @endif

    </div>
</div>


</div>
