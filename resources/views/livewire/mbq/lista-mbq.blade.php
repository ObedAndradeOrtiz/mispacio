<div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3">
                <div class="border-0 shadow-sm card">
                    <div class="text-center card-header fw-bold">
                        <h3 class="card-title">Gestión de MBQ</h3>
                    </div>
                    <div class="card-body">
                        <!-- Filtro de búsqueda -->
                        <div class="mb-4">
                            <div class="d-flex align-items-center">
                                <input type="text" class="form-control form-control-lg"
                                    placeholder="Buscar por nombre..." wire:model="busqueda">

                            </div>
                        </div>
                        <!-- Filtro de sucursal -->
                        <div class="mb-3">
                            <label for="areaseleccionada" class="form-label font-weight-bold">Sucursal:</label>
                            <select wire:model="areaseleccionada" class="form-control">
                                <option value="">Todos</option>
                                @foreach ($areas as $item)
                                    <option value="{{ $item->area }}">{{ $item->area }}</option>
                                @endforeach
                            </select>
                        </div>
                        <!-- Filtros de fecha y estado -->
                        <div class="flex-wrap mb-3 d-flex justify-content-between" style="gap: 15px;">
                            <!-- Fecha desde -->
                            <div class="flex-column w-100 w-md-45">
                                <label for="fecha-inicio" class="form-label font-weight-bold">Desde:</label>
                                <input type="date" id="fecha-inicio" class="form-control"
                                    wire:model="fechaInicioMes">
                            </div>

                            <!-- Fecha hasta -->
                            <div class="flex-column w-100 w-md-45">
                                <label for="fecha-actual" class="form-label font-weight-bold">Hasta:</label>
                                <input type="date" id="fecha-actual" class="form-control" wire:model="fechaActual">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-9">
                <div class="card">
                    <div class="text-center card-header fw-bold">
                        <h3 class="card-title">Lista de aspirantes</h3>
                        <div class="card-options">
                            <button class="btn btn-success" wire:click="$set('crear',true)"> <i
                                    class="ki-outline ki-user fs-2"></i> Agregar
                                Aspirante</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="mt-4 alert alert-warning w-50 me-2">
                                <strong>Total de inscripción vendida en QR: {{ $pagoqr }} Bs</strong>
                            </div>
                            <div class="mt-4 alert alert-success w-50">
                                <strong>Total de inscripción vendida en Efectivo: {{ $pagoefec }} Bs</strong>
                            </div>
                        </div>



                        <div class="py-3 table-responsive ">
                            <table class="table mb-0 table-striped table-hover table-bordered text-nowrap">
                                <thead class="thead-dark">
                                    <tr class="ligth">
                                        <th>Nombre</th>
                                        <th>Sucursal</th>
                                        <th>Registro</th>
                                        <th>Edad</th>
                                        <th>Ci</th>
                                        <th>Telf.</th>
                                        <th>
                                            <div class="d-flex w-100">
                                                <div class="text-center w-50 border-end">QR</div>
                                                <div class="text-center w-50">Efectivo</div>
                                            </div>
                                        </th>
                                        <th>Acción</th>
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
                                    @foreach ($users as $lista)
                                        <tr>
                                            <td>
                                                <div>
                                                    {{ $lista->name }}
                                                </div>

                                            </td>
                                            <td>{{ $lista->sucursal }}</td>
                                            <td>{{ $lista->fechainicio }}</td>
                                            <td>{{ $lista->edad }}</td>
                                            <td>{{ $lista->ci }}</td>
                                            <td>{{ $lista->telefono }}</td>
                                            @php
                                                $pagoqr = DB::table('registroinventarios')
                                                    ->where('iduser', $lista->id)
                                                    ->where('modo', 'qr')
                                                    ->sum('cantidad');
                                                $pagoefec = DB::table('registroinventarios')
                                                    ->where('iduser', $lista->id)
                                                    ->where('modo', 'efectivo')
                                                    ->sum('cantidad');
                                            @endphp
                                            <td>
                                                <div class="d-flex w-100">
                                                    <div class="text-center w-50 border-end">{{ $pagoqr }}</div>
                                                    <div class="text-center w-50">{{ $pagoefec }}</div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="flex align-items-center list-user-action">

                                                    <button class="mr-2 btn btn-warning"
                                                        wire:click="editarmiss({{ $lista->id }})"><i
                                                            class="ki-outline ki-wallet fs-2"></i> </button>
                                                    <button class="btn btn-success"
                                                        wire:click="verdetalle({{ $lista->id }})"><i
                                                            class="ki-outline ki-eye fs-2"></i>
                                                    </button>

                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-modal wire:model.defer="ver_detalle">
        <div class="px-6 py-4">
            <div class="text-lg font-medium text-gray-900">
                Lista de pagos
            </div>
            <div class="mt-4 text-sm text-gray-600">
                <div class="py-3 table-responsive ">
                    <table class="table mb-0 table-striped table-hover table-bordered text-nowrap">
                        <thead class="thead-dark">
                            <tr class="ligth">
                                <th>Pago</th>
                                <th>Modo</th>
                                <th>Descripcion</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($pagos_miss)
                                @foreach ($pagos_miss as $item)
                                    <tr>
                                        <td>{{ $item->cantidad }}</td>
                                        <td>{{ $item->modo }}</td>
                                        <td>{{ $item->nombreproducto }}</td>
                                    </tr>
                                @endforeach
                            @endif

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </x-modal>
    <x-modal wire:model.defer="cobrar">
        <div class="px-6 py-4">
            <div class="text-lg font-medium text-gray-900">
                Nuevo pago
            </div>
            <div class="mt-4 text-sm text-gray-600">
                <form>
                    <div class="form-group">
                        <label class="form-label" for="">Cantidad a Cancelar:</label>
                        <input type="text" class="form-control" id="texto" oninput="convertirAMayusculas()"
                            wire:model.defer="cantidad_pago">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="">Fecha de pago:</label>
                        <input type="date" class="form-control" id="texto" oninput="convertirAMayusculas()"
                            wire:model.defer="fecha_pagada">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="">Descripción de pago:</label>
                        <input type="text" class="form-control" id="texto" oninput="convertirAMayusculas()"
                            wire:model.defer="descripcion">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="">Modo de pago: </label>
                        <select name="type" class="selectpicker form-control" data-style="py-0"
                            wire:model.defer="modo">
                            <option value="efectivo">Efectivo</option>
                            <option value="qr">QR</option>
                        </select>
                    </div>
                </form>
            </div>
        </div>

        <div class="flex flex-row justify-end px-6 py-4 text-right bg-gray-100">
            <label type="submit" class="btn btn-success" wire:click="guardarpago" wire:loading.remove
                wire:target="guardartodo">Guardar</label>
            <span class="" wire:loading wire:target="guardarpago">Guardando...</span>
        </div>
    </x-modal>
    <x-modal wire:model.defer="crear">
        <div class="px-6 py-4">
            <div class="text-lg font-medium text-gray-900">
                Registro de aspirante
            </div>
            <div class="mt-4 text-sm text-gray-600">
                <form>
                    <div class="form-group">
                        <label class="form-label" for="">Nombre y apellidos:</label>
                        <input type="text" class="form-control" id="texto" oninput="convertirAMayusculas()"
                            wire:model.defer="name">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="">Sucursal de registro: </label>
                        <select name="type" class="selectpicker form-control" data-style="py-0"
                            wire:model.defer="sucursal_seleccionada">
                            <option>Seleccionar sucursal</option>
                            @foreach ($areas as $area)
                                <option>{{ $area->area }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="exampleInputdate">Fecha de registro en sistema:</label>
                        <input type="date" class="form-control" id="exampleInputdate" value="2000-01-01"
                            wire:model="fechainicio">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="">Teléfono principal:</label>
                        <input type="text" class="form-control" id="exampleInputDisabled1"
                            wire:model.defer="telefono">
                    </div>
                    <div class="form-group">

                        <label class="form-label" for="">Edad:</label>
                        <input type="number" class="form-control" id="exampleInputDisabled1"
                            wire:model.defer="edad">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="">Carné de identidad:</label>
                        <input type="text" class="form-control" id="exampleInputDisabled1" wire:model.defer="ci">
                    </div>
                </form>
            </div>
        </div>
        <div class="flex flex-row justify-end px-6 py-4 text-right bg-gray-100">
            <label type="submit" class="btn btn-success" wire:click="guardartodo" wire:loading.remove
                wire:target="guardartodo">Guardar</label>
            <span class="" wire:loading wire:target="guardartodo">Guardando...</span>
        </div>
    </x-modal>
</div>
