<div>
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
    </div>
    <div class="card-body">
        <?php
        
        // Obtener la lista de tipos de gastos y ordenarlos alfabéticamente
        $tiposGastos = ['AGUA POTABLE', 'ALQUILER', 'GAS', 'IMPUESTOS', 'LUZ ELECTRICA', 'INTERNET/TEL', 'Dra. PAOLA', 'Sr. ALEXANDRE', 'ADELANTO AL PERSONAL', 'MATERIAL CAFETERIA', 'MATERIAL ESCRITORIO', 'MATERIAL LIMPIEZA', 'MATERIAL DE PROCEDIMIENTOS', 'MATERIAL PARA EVENTOS', 'MATERIAL PARA MANTENIMIENTO', 'MANTENIMIENTO DE EQUIPOS', 'MANTENIMIENTO DE SUCURSAL', 'COMPRA DE EQUIPO', 'COMPRA DE MUEBLE', 'MERIENDAS', 'PUBLICIDAD', 'SERVICIOS PROFESIONALES', 'TRAMITES', 'TRANSPORTE', 'PAGO SUELDO'];
        
        sort($tiposGastos);
        
        ?>

        <div class="table-responsive">
            <div>
                <h3 class="mb-2">Historial de egresos por sucursales:</h3>
            </div>
            <table id="user-list-table" class="table table-striped" role="grid" data-bs-toggle="data-table">
                <thead>
                    <tr class="ligth">
                        <th>TIPO</th>
                        @foreach ($areas as $item)
                            <th>{{ $item->area }}</th>
                        @endforeach
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

                    @foreach ($tiposGastos as $tipo)
                        <tr>
                            <td>{{ $tipo }}</td>
                            @foreach ($areas as $item)
                                <td>
                                    @php
                                        $gasto = DB::table('gastos')
                                            ->where('area', $item->area)
                                            ->where('tipo', 'ILIKE', '%' . $tipo . '%')
                                            ->where('fechainicio', '<=', $this->fechaActual)
                                            ->where('fechainicio', '>=', $this->fechaInicioMes)
                                            ->sum('cantidad');
                                    @endphp
                                    {{ $gasto }}
                                </td>
                            @endforeach
                        </tr>
                    @endforeach

                    <tr>
                        <td><strong>Total</strong></td>
                        @foreach ($areas as $item)
                            <td>
                                @php
                                    $totalGasto = DB::table('gastos')
                                        ->where('area', $item->area)
                                        ->where('fechainicio', '<=', $this->fechaActual)
                                        ->where('fechainicio', '>=', $this->fechaInicioMes)
                                        ->sum('cantidad');
                                @endphp
                                <strong>{{ $totalGasto }}</strong>
                            </td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
</div>
