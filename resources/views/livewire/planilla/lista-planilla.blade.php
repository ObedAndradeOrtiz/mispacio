<div>
    <div class="flex flex-row justify-end">
        <div class="mr-4">
            <label for="">FECHAS DE PAGOS:</label>
        </div>

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
        <div class="table-responsive">
            <div>
                <h3 class="mb-2 ">PLANILLAS DE GASTOS</h3>
            </div>
            <div class="flex flex-row justify-end">
                <button class="btn btn-success" wire:click="crearPlanilla">NUEVA PLANILLA</button>
            </div>
            <table id="user-list-table" class="table table-striped" role="grid" data-bs-toggle="data-table">
                <thead>
                    <tr class="ligth">
                        <th>SEMANA</th>
                        <th>NRO.TRABAJADORES</th>
                        <th>LIQUIDO PAGABLE</th>
                        <th>FECHA DE PAGO</th>
                        <th>ESTADO</th>
                        <th>ACCIÓN</th>
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
                    @foreach ($planillas as $item)
                        <tr>
                            <td>
                                {{ $item->semana }}
                            </td>
                            <td>
                                {{ $item->trabajadores }}
                            </td>
                            <td>
                                {{ $item->pagado }}
                            </td>
                            <td>
                                {{ $item->fecha }}
                            </td>
                            <td>
                                {{ $item->estado }}
                            </td>
                            <td>
                                <a class="mt-1 btn btn-sm btn-icon btn-warning d-flex align-items-center"
                                    data-bs-toggle="tooltip" data-bs-placement="top" title="EDITAR"
                                    data-original-title="Edit" href="planilla/{{ $item->id }}">

                                    <svg class="icon-15" width="15" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M11.4925 2.78906H7.75349C4.67849 2.78906 2.75049 4.96606 2.75049 8.04806V16.3621C2.75049 19.4441 4.66949 21.6211 7.75349 21.6211H16.5775C19.6625 21.6211 21.5815 19.4441 21.5815 16.3621V12.3341"
                                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round"></path>
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M8.82812 10.921L16.3011 3.44799C17.2321 2.51799 18.7411 2.51799 19.6721 3.44799L20.8891 4.66499C21.8201 5.59599 21.8201 7.10599 20.8891 8.03599L13.3801 15.545C12.9731 15.952 12.4211 16.181 11.8451 16.181H8.09912L8.19312 12.401C8.20712 11.845 8.43412 11.315 8.82812 10.921Z"
                                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round"></path>
                                        <path d="M15.1655 4.60254L19.7315 9.16854" stroke="currentColor"
                                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                    <span class="ms-1" style="font-size: 8px;">EDITAR</span>
                                </a>
                                <a href="">Copiar anterior</a>

                            </td>

                        </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>
</div>
