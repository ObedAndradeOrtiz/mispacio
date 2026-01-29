<div>
    <div class="col-sm-12">
        <div class="">
            <div class="card ">
                <div class="col-sm-12">
                    <div class="">
                        <div class="card ">
                            <div class="card-header d-flex bg-bl">
                                <h2>ADMINISTRACIÓN DE EGRESOS EXTERNOS</h2>
                            </div>
                            <div class="card-body">
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <label for="fecha-inicio">Desde:</label>
                    <input type="date" id="fecha-inicio" wire:model.defer="fechaInicioMes">

                    <label for="fecha-actual">Hasta:</label>
                    <input type="date" id="fecha-actual" wire:model.defer="fechaActual">
                </div>
                <div class="card-header d-flex">
                    <div class="mt-4 mb-2 ml-4 input-group search-input">
                        <span class="input-group-text" id="search-input">
                            <svg class="icon-18" width="18" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <circle cx="11.7669" cy="11.7666" r="8.98856" stroke="currentColor"
                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                </circle>
                                <path d="M18.0186 18.4851L21.5426 22" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round">
                                </path>
                            </svg>

                        </span>
                        <input type="search" class="form-control" placeholder="Busqueda..."
                            wire:model.defer="busqueda">
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="user-list-table" class="table table-striped" role="grid"
                            data-bs-toggle="data-table">
                            <thead>
                                <tr class="ligth">
                                    <th>Area</th>
                                    <th>Obtenido total</th>
                                    <th>Gastos total</th>
                                    <th>Restante</th>
                                    <th>Mi Monto</th>
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

                                @foreach ($areas as $lista)
                                    @php
                                        $restante = 0;
                                        $restante_bbc = 0;
                                        $restante_bbc_total = 0;
                                    @endphp
                                    @if ($lista->area != 'BBC')
                                        <tr>
                                            <td>{{ $lista->area }}</td>
                                            @php
                                                $total_monto = DB::table('pagos')
                                                    ->where('area', $lista->area)
                                                    ->where('fechapagado', '>', $fechaInicioMes)
                                                    ->where('fechapagado', '<', $fechaActual)
                                                    ->where('estado', 'Inactivo')
                                                    ->sum('cantidad');
                                                $total_gasto = DB::table('gastos')
                                                    ->where('fechainicio', '>', $fechaInicioMes)
                                                    ->where('fechainicio', '<', $fechaActual)
                                                    ->where('idarea', $lista->id)
                                                    ->sum('cantidad');
                                                $total_resto = $total_monto - $total_gasto;
                                                $restante = $restante + $total_resto;
                                                $restante_bbc = $restante * (1 - $lista->porcentaje / 100);
                                                $restante_bbc_total = $restante_bbc_total + $restante_bbc;
                                            @endphp
                                            <td>{{ $total_monto }}</td>
                                            <td>{{ $total_gasto }}</td>
                                            <td>{{ $total_resto }}</td>
                                            <td>{{ $restante_bbc }} <button
                                                    class="btn btn-success">{{ 100 - $lista->porcentaje }} %</button>
                                            </td>
                                            <td>@livewire('tesoreria.editar-tesoreria', ['area' => $lista], key($lista->id))</td>
                                        </tr>
                                    @endif
                                @endforeach
                                <tr class="bg-gray">
                                    <td style="color: white">TOTALES</td>
                                    @php

                                        $total_monto = DB::table('pagos')->where('estado', 'Inactivo')->sum('cantidad');
                                        $total_gasto = DB::table('gastos')->sum('cantidad');
                                    @endphp
                                    <td style="color: white">{{ $total_monto }}</td>
                                    <td style="color: white">{{ $total_gasto }}</td>
                                    <td></td>
                                    <td style="color: white">{{ $restante_bbc_total }}</td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- <x-modal wire:model="openAreaGasto">
        <div class="px-6 py-4">
            <div class="text-lg font-medium text-gray-900">
                Añadir gasto interno
            </div>
            <div class="mt-4 text-sm text-gray-600">
                <form>
                    <label class="form-label" for="">Seleccione sucursal: </label>
                    <select class="mb-3 shadow-none form-select form-select-smmt-5" wire:model="sucursal">
                        @foreach ($areas as $item)
                            <option value="{{ $item->id }}">{{ $item->area }}</option>
                        @endforeach
                    </select>
                    <label class="form-label" for="">Tipo de egreso: </label>
                    <select class="mb-3 shadow-none form-select form-select-smmt-5" wire:model.="tipogasto">
                        <option>Seleccionar:</option>
                        <option>Sueldo</option>
                        <option>Bono</option>
                        <option>Retiro</option>
                        <option>Otro</option>
                    </select>
                    <div class="form-group">
                        <label class="form-label" for="exampleInputdate">Fecha de egreso:</label>
                        <input type="date" class="form-control" id="exampleInputdate" value="2000-01-01"
                            wire:model="fechagasto">
                    </div>
                    <label class="form-label" for="">Metodo de pago: </label>
                    <select class="mb-3 shadow-none form-select form-select-smmt-5" wire:model="modopago">
                        <option>Seleccionar modo:</option>
                        <option>QR</option>

                        <option>Efectivo</option>
                    </select>

                    <div class="form-group">
                        <label class="form-label" for="">Monto del egreso:</label>
                        <input type="number" class="form-control" id="exampleInputDisabled1" wire:model="montoegreso">
                    </div>
                    @if ($tipogasto == 'Sueldo')
                        <div class="mb-2 form-group">
                            <label class="form-label" for="">Seleccionar destinario: </label>
                            <select class="mb-3 shadow-none form-select form-select-smmt-5" wire:model="destinario">
                                <option>Seleccionar usuario </option>
                                @foreach ($usersl as $area)
                                    <option>{{ $area->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    @if ($tipogasto == 'Bono')
                        <div class="mb-2 form-group">
                            <label class="form-label" for="">Seleccionar destinario: </label>
                            <select class="mb-3 shadow-none form-select form-select-smmt-5" wire:model="destinario">
                                <option>Seleccionar usuario </option>
                                @foreach ($usersl as $area)
                                    <option>{{ $area->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                    <div class="form-group">
                        <label class="form-label" for="">Especifique el egreso (Opcional):</label>
                        <input type="text" class="form-control" id="exampleInputDisabled1" wire:model="comentario">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="exampleInputDisabled1">Regitrado por:</label>
                        <input type="text" class="form-control" id="exampleInputDisabled1" disabled=""
                            value="{{ Auth::user()->name }}">
                    </div>
                </form>
            </div>
        </div>

        <div class="flex flex-row justify-end px-6 py-4 text-right bg-gray-100">
            <label type="submit" class="btn btn-success" wire:click="confirmar">Guardar</label>
        </div>
    </x-modal> --}}
</div>
