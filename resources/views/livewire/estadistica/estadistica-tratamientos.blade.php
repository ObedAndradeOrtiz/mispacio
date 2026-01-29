<div>
    <style>
        /* Contenedor principal para evitar scroll en la página */
        .row {
            display: flex;
            flex-wrap: nowrap;
            overflow-x: auto;
            max-width: 100%;
        }

        /* Columnas con tabla */
        .col-md-2 {
            flex: 1;
            min-width: 200px;
            padding: 10px;
        }

        /* Contenedor de la tabla con scroll interno */
        .table-container {
            max-height: calc(70vh - 20px);
            overflow-y: auto;
            border-radius: 10px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            background: #fff;
        }

        /* Estilos generales de la tabla */
        .custom-table {
            width: 100%;
            border-collapse: collapse;
        }

        /* Cabecera fija */
        .custom-table th {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            text-align: left;
            font-size: 12px;
            position: sticky;
            top: 0;
        }

        /* Filas organizadas en grid */
        .custom-table tbody tr {
            display: grid;
            grid-template-columns: 80% 20%;
            /* Tratamiento 70% - Número 30% */
            align-items: center;
        }

        /* Estilo de celdas */
        .custom-table td {
            padding: 8px;
            font-size: 12px;
            border-bottom: 1px solid #ddd;
        }

        /* Alternar colores en las filas */
        .custom-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        /* Scroll con estilo */
        .table-container::-webkit-scrollbar {
            width: 5px;
        }

        .table-container::-webkit-scrollbar-thumb {
            background: #4CAF50;
            border-radius: 10px;
        }
    </style>

    <div class="card" style="margin:3%;">
        <div class="card-body">
            <div class="row">
                @foreach ($areas as $index => $item)
                    @php
                        $resultadostratamientos = DB::table('historial_clientes')
                            ->select(
                                'historial_clientes.nombretratamiento',
                                DB::raw('COUNT(DISTINCT historial_clientes.idcliente) as total_ingreso'),
                            )
                            ->join(
                                'operativos',
                                DB::raw('CAST(historial_clientes.idoperativo AS INTEGER)'),
                                '=',
                                'operativos.id',
                            )
                            ->whereBetween('historial_clientes.fecha', [date('Y-0m-1'), date('Y-m-d')])
                            ->where('historial_clientes.nombretratamiento', '!=', 'CONSULTA')
                            ->where('operativos.area', $item->area)
                            ->groupBy('historial_clientes.nombretratamiento')
                            ->orderBy('total_ingreso', 'desc')
                            ->get();
                    @endphp

                    <div class="col-md-2">
                        <div class="table-container">
                            <table class="custom-table">
                                <thead>
                                    <tr>
                                        <th>{{ $item->area }}</th>
                                        <th>Cantidad</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($resultadostratamientos as $resultado)
                                        <tr>
                                            <td>{{ $resultado->nombretratamiento }}</td>
                                            <td>{{ $resultado->total_ingreso }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
