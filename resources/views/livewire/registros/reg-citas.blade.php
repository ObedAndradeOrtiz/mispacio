<div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3">
                <div class="border-0 shadow-sm card">
                    <div class="card-body">
                        <div class="flex-wrap d-flex align-items-end" style="gap: 1rem;">

                            <div class="mb-0 form-group">
                                <label>Lista de citas:</label>
                                <select wire:model="tiposeleccionado" class="form-control">
                                    <option value="Agendado">Agendados</option>
                                    <option value="error">Con error de pago</option>
                                    <option value="otro">Por eliminar</option>
                                </select>
                            </div>

                            <!-- Filtro: Sucursal -->
                            <div class="mb-0 form-group">
                                <label>Sucursal:</label>
                                <select wire:model="areaseleccionada" class="form-control">
                                    <option value="">Todas</option>
                                    @foreach ($areas as $lista)
                                        <option value="{{ $lista->area }}">{{ $lista->area }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Filtro: Fecha desde -->
                            <div class="mb-0 form-group">
                                <label for="fecha-inicio">Desde:</label>
                                <input type="date" id="fecha-inicio" wire:model="fechaInicioMes"
                                    class="form-control">
                            </div>

                            <!-- Filtro: Fecha hasta -->
                            <div class="mb-0 form-group">
                                <label for="fecha-actual">Hasta:</label>
                                <input type="date" id="fecha-actual" wire:model="fechaActual" class="form-control">
                            </div>

                            <!-- Filtro: Responsable -->
                            <div class="mb-0 form-group">
                                <label>Responsable:</label>
                                <select wire:model="usuarioseleccionado" class="form-control">
                                    <option value="">Todos</option>
                                    @foreach ($users as $lista)
                                        <option value="{{ $lista->name }}">{{ $lista->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="border-0 shadow-sm card">
                    <div class="card-body">
                        @if ($tiposeleccionado == 'Agendado')
                            <div class="mt-2" style=" border-radius: 5px;">
                                <input type="text" class="form-control" id="exampleInputDisabled1"
                                    wire:model="busquedacli" placeholder="Buscar cliente...">
                            </div>
                            <div class="mb-2 ml-4">
                                <label for="">Se estan mostrando: {{ $miscitaslistacount }} citas.</label>
                            </div>
                            <div class="table-responsive">
                                <table id="mitablaregistros" class="table table-striped" role="grid"
                                    data-bs-toggle="data-table">
                                    <thead style="font-size: 1vw;">
                                        <tr>
                                            <th>ASISTENCIA</th>
                                            <th>NUMERO</th>
                                            <th>CLIENTE</th>
                                            <th>PAGO TOTAL</th>
                                            <th>PAGO REALIZADO</th>

                                            <th>RESPONSABLE</th>
                                            <th>ACCION</th>
                                        </tr>
                                    </thead>
                                    <tbody style="font-size: 0.8vw;">

                                        @foreach ($miscitaslista as $item)
                                            @php
                                                $verificar = false;
                                                $haypago = false;
                                                $paguito = DB::table('registropagos')
                                                    ->where('idoperativo', $item->id)
                                                    ->whereBetween('fecha', [$fechaInicioMes, $fechaActual])
                                                    ->get();
                                                foreach ($paguito as $key => $value) {
                                                    $haypago = true;
                                                }
                                                $mipagos = DB::table('pagos')->where('idoperativo', $item->id)->get();
                                                foreach ($mipagos as $pago) {
                                                    if ($pago->cantidad < $pago->pagado) {
                                                        $verificar = true;
                                                    }
                                                }
                                            @endphp
                                            @if ($verificar)
                                                <tr style="background-color: red; color:aliceblue;">
                                                    @if ($haypago)
                                                        <td>✅</td>
                                                    @else
                                                        <td>❌</td>
                                                    @endif
                                                    <td>{{ $item->telefono }}</td>
                                                    <td style="color:aliceblue;">{{ $item->empresa }}</td>
                                                    @if (count($mipagos) == 0)
                                                        <td style="color:aliceblue;">0</td>
                                                        <td style="color:aliceblue;">0</td>
                                                    @else
                                                        @foreach ($mipagos as $pago)
                                                            <td style="color:aliceblue;">{{ $pago->cantidad }}</td>
                                                            <td style="color:aliceblue;">{{ $pago->pagado }}</td>
                                                        @endforeach
                                                    @endif
                                                    @php
                                                        $responsables = DB::table('registropagos')
                                                            ->where('idoperativo', $item->id)
                                                            ->get();
                                                    @endphp
                                                    {{-- <td>{{ $item->fecha . ' - ' . $item->hora }}</td> --}}
                                                    @if ($responsables->isNotEmpty())
                                                        @php
                                                            $ultimoResponsable = $responsables->last();
                                                        @endphp
                                                        <td>{{ $ultimoResponsable->responsable }}</td>
                                                    @else
                                                        <td>Sin responsable</td>
                                                    @endif
                                                    <td>
                                                        <a class="mt-1 btn btn-sm btn-icon btn-warning d-flex align-items-center"
                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                            title="ELIMINAR" data-original-title="Edit"
                                                            wire:click="editarcita({{ $item->id }})">
                                                            <svg class="icon-20" width="20" viewBox="0 0 24 24"
                                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path
                                                                    d="M11.4925 2.78906H7.75349C4.67849 2.78906 2.75049 4.96606 2.75049 8.04806V16.3621C2.75049 19.4441 4.66949 21.6211 7.75349 21.6211H16.5775C19.6625 21.6211 21.5815 19.4441 21.5815 16.3621V12.3341"
                                                                    stroke="currentColor" stroke-width="1.5"
                                                                    stroke-linecap="round" stroke-linejoin="round">
                                                                </path>
                                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                                    d="M8.82812 10.921L16.3011 3.44799C17.2321 2.51799 18.7411 2.51799 19.6721 3.44799L20.8891 4.66499C21.8201 5.59599 21.8201 7.10599 20.8891 8.03599L13.3801 15.545C12.9731 15.952 12.4211 16.181 11.8451 16.181H8.09912L8.19312 12.401C8.20712 11.845 8.43412 11.315 8.82812 10.921Z"
                                                                    stroke="currentColor" stroke-width="1.5"
                                                                    stroke-linecap="round" stroke-linejoin="round">
                                                                </path>
                                                                <path d="M15.1655 4.60254L19.7315 9.16854"
                                                                    stroke="currentColor" stroke-width="1.5"
                                                                    stroke-linecap="round" stroke-linejoin="round">
                                                                </path>
                                                            </svg>
                                                            <span class="ms-1" style="font-size: 8px;">EDITAR</span>
                                                        </a>
                                                        <a class="mt-1 btn btn-sm btn-icon btn-danger d-flex align-items-center"
                                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                                            title="ELIMINAR" data-original-title="Edit"
                                                            wire:click="$emit('inactivarCita',{{ $item->id }})">
                                                            <svg class="icon-20" width="20" viewBox="0 0 24 24"
                                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path opacity="0.4"
                                                                    d="M16.34 1.99976H7.67C4.28 1.99976 2 4.37976 2 7.91976V16.0898C2 19.6198 4.28 21.9998 7.67 21.9998H16.34C19.73 21.9998 22 19.6198 22 16.0898V7.91976C22 4.37976 19.73 1.99976 16.34 1.99976Z"
                                                                    fill="currentColor"></path>
                                                                <path
                                                                    d="M15.0158 13.7703L13.2368 11.9923L15.0148 10.2143C15.3568 9.87326 15.3568 9.31826 15.0148 8.97726C14.6728 8.63326 14.1198 8.63426 13.7778 8.97626L11.9988 10.7543L10.2198 8.97426C9.87782 8.63226 9.32382 8.63426 8.98182 8.97426C8.64082 9.31626 8.64082 9.87126 8.98182 10.2123L10.7618 11.9923L8.98582 13.7673C8.64382 14.1093 8.64382 14.6643 8.98582 15.0043C9.15682 15.1763 9.37982 15.2613 9.60382 15.2613C9.82882 15.2613 10.0518 15.1763 10.2228 15.0053L11.9988 13.2293L13.7788 15.0083C13.9498 15.1793 14.1728 15.2643 14.3968 15.2643C14.6208 15.2643 14.8448 15.1783 15.0158 15.0083C15.3578 14.6663 15.3578 14.1123 15.0158 13.7703Z"
                                                                    fill="currentColor"></path>
                                                            </svg>
                                                            <span class="ms-1"
                                                                style="font-size: 8px;">ELIMINAR</span>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @else
                                                <tr>
                                                    @if ($haypago)
                                                        <td>✅</td>
                                                    @else
                                                        <td>❌</td>
                                                    @endif
                                                    <td>{{ $item->telefono }}</td>
                                                    <td>{{ $item->empresa }}</td>
                                                    @if (count($mipagos) == 0)
                                                        <td>0</td>
                                                        <td>0</td>
                                                    @else
                                                        @foreach ($mipagos as $pago)
                                                            <td>{{ $pago->cantidad }}</td>
                                                            <td>{{ $pago->pagado }}</td>
                                                        @endforeach
                                                    @endif
                                                    {{-- <td>{{ $item->fecha . ' - ' . $item->hora }}</td> --}}
                                                    @php
                                                        $responsables = DB::table('registropagos')
                                                            ->where('idoperativo', $item->id)
                                                            ->get();
                                                    @endphp

                                                    @if ($responsables->isNotEmpty())
                                                        @php
                                                            $ultimoResponsable = $responsables->last();
                                                        @endphp
                                                        <td>{{ $ultimoResponsable->responsable }}</td>
                                                    @else
                                                        <td>Sin responsable</td>
                                                    @endif


                                                    <td>
                                                        @if (count($mipagos) == 0)
                                                            <a class="mt-1 btn btn-sm btn-icon btn-danger d-flex align-items-center"
                                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                                title="ELIMINAR" data-original-title="Edit"
                                                                wire:click="$emit('inactivarCita',{{ $item->id }})">
                                                                <svg class="icon-20" width="20"
                                                                    viewBox="0 0 24 24" fill="none"
                                                                    xmlns="http://www.w3.org/2000/svg">
                                                                    <path opacity="0.4"
                                                                        d="M16.34 1.99976H7.67C4.28 1.99976 2 4.37976 2 7.91976V16.0898C2 19.6198 4.28 21.9998 7.67 21.9998H16.34C19.73 21.9998 22 19.6198 22 16.0898V7.91976C22 4.37976 19.73 1.99976 16.34 1.99976Z"
                                                                        fill="currentColor"></path>
                                                                    <path
                                                                        d="M15.0158 13.7703L13.2368 11.9923L15.0148 10.2143C15.3568 9.87326 15.3568 9.31826 15.0148 8.97726C14.6728 8.63326 14.1198 8.63426 13.7778 8.97626L11.9988 10.7543L10.2198 8.97426C9.87782 8.63226 9.32382 8.63426 8.98182 8.97426C8.64082 9.31626 8.64082 9.87126 8.98182 10.2123L10.7618 11.9923L8.98582 13.7673C8.64382 14.1093 8.64382 14.6643 8.98582 15.0043C9.15682 15.1763 9.37982 15.2613 9.60382 15.2613C9.82882 15.2613 10.0518 15.1763 10.2228 15.0053L11.9988 13.2293L13.7788 15.0083C13.9498 15.1793 14.1728 15.2643 14.3968 15.2643C14.6208 15.2643 14.8448 15.1783 15.0158 15.0083C15.3578 14.6663 15.3578 14.1123 15.0158 13.7703Z"
                                                                        fill="currentColor"></path>
                                                                </svg>
                                                                <span class="ms-1"
                                                                    style="font-size: 8px;">ELIMINAR</span>
                                                            </a>
                                                        @else
                                                            <a class="mt-1 btn btn-sm btn-icon btn-warning d-flex align-items-center"
                                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                                title="ELIMINAR" data-original-title="Edit"
                                                                wire:click="editarcita({{ $item->id }})">
                                                                <svg class="icon-20" width="20"
                                                                    viewBox="0 0 24 24" fill="none"
                                                                    xmlns="http://www.w3.org/2000/svg">
                                                                    <path
                                                                        d="M11.4925 2.78906H7.75349C4.67849 2.78906 2.75049 4.96606 2.75049 8.04806V16.3621C2.75049 19.4441 4.66949 21.6211 7.75349 21.6211H16.5775C19.6625 21.6211 21.5815 19.4441 21.5815 16.3621V12.3341"
                                                                        stroke="currentColor" stroke-width="1.5"
                                                                        stroke-linecap="round"
                                                                        stroke-linejoin="round"></path>
                                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                                        d="M8.82812 10.921L16.3011 3.44799C17.2321 2.51799 18.7411 2.51799 19.6721 3.44799L20.8891 4.66499C21.8201 5.59599 21.8201 7.10599 20.8891 8.03599L13.3801 15.545C12.9731 15.952 12.4211 16.181 11.8451 16.181H8.09912L8.19312 12.401C8.20712 11.845 8.43412 11.315 8.82812 10.921Z"
                                                                        stroke="currentColor" stroke-width="1.5"
                                                                        stroke-linecap="round"
                                                                        stroke-linejoin="round"></path>
                                                                    <path d="M15.1655 4.60254L19.7315 9.16854"
                                                                        stroke="currentColor" stroke-width="1.5"
                                                                        stroke-linecap="round"
                                                                        stroke-linejoin="round">
                                                                    </path>
                                                                </svg>
                                                                <span class="ms-1"
                                                                    style="font-size: 8px;">EDITAR</span>
                                                            </a>

                                                            <a class="mt-1 btn btn-sm btn-icon btn-danger d-flex align-items-center"
                                                                data-bs-toggle="tooltip" data-bs-placement="top"
                                                                title="ELIMINAR" data-original-title="Edit"
                                                                wire:click="$emit('inactivarCita',{{ $item->id }})">
                                                                <svg class="icon-20" width="20"
                                                                    viewBox="0 0 24 24" fill="none"
                                                                    xmlns="http://www.w3.org/2000/svg">
                                                                    <path opacity="0.4"
                                                                        d="M16.34 1.99976H7.67C4.28 1.99976 2 4.37976 2 7.91976V16.0898C2 19.6198 4.28 21.9998 7.67 21.9998H16.34C19.73 21.9998 22 19.6198 22 16.0898V7.91976C22 4.37976 19.73 1.99976 16.34 1.99976Z"
                                                                        fill="currentColor"></path>
                                                                    <path
                                                                        d="M15.0158 13.7703L13.2368 11.9923L15.0148 10.2143C15.3568 9.87326 15.3568 9.31826 15.0148 8.97726C14.6728 8.63326 14.1198 8.63426 13.7778 8.97626L11.9988 10.7543L10.2198 8.97426C9.87782 8.63226 9.32382 8.63426 8.98182 8.97426C8.64082 9.31626 8.64082 9.87126 8.98182 10.2123L10.7618 11.9923L8.98582 13.7673C8.64382 14.1093 8.64382 14.6643 8.98582 15.0043C9.15682 15.1763 9.37982 15.2613 9.60382 15.2613C9.82882 15.2613 10.0518 15.1763 10.2228 15.0053L11.9988 13.2293L13.7788 15.0083C13.9498 15.1793 14.1728 15.2643 14.3968 15.2643C14.6208 15.2643 14.8448 15.1783 15.0158 15.0083C15.3578 14.6663 15.3578 14.1123 15.0158 13.7703Z"
                                                                        fill="currentColor"></path>
                                                                </svg>
                                                                <span class="ms-1"
                                                                    style="font-size: 8px;">ELIMINAR</span>
                                                            </a>
                                                            @if ($item->cantidadtotal == 1)
                                                                <a class="mt-1 btn btn-sm btn-icon btn-success d-flex align-items-center"
                                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                                    title="ELIMINAR" data-original-title="Edit"
                                                                    wire:click="$emit('activarCita',{{ $item->id }})"><svg
                                                                        class="icon-20" width="20"
                                                                        viewBox="0 0 24 24" fill="none"
                                                                        xmlns="http://www.w3.org/2000/svg">
                                                                        <path opacity="0.4"
                                                                            d="M16.3405 1.99976H7.67049C4.28049 1.99976 2.00049 4.37976 2.00049 7.91976V16.0898C2.00049 19.6198 4.28049 21.9998 7.67049 21.9998H16.3405C19.7305 21.9998 22.0005 19.6198 22.0005 16.0898V7.91976C22.0005 4.37976 19.7305 1.99976 16.3405 1.99976Z"
                                                                            fill="currentColor"></path>
                                                                        <path
                                                                            d="M10.8134 15.248C10.5894 15.248 10.3654 15.163 10.1944 14.992L7.82144 12.619C7.47944 12.277 7.47944 11.723 7.82144 11.382C8.16344 11.04 8.71644 11.039 9.05844 11.381L10.8134 13.136L14.9414 9.00796C15.2834 8.66596 15.8364 8.66596 16.1784 9.00796C16.5204 9.34996 16.5204 9.90396 16.1784 10.246L11.4324 14.992C11.2614 15.163 11.0374 15.248 10.8134 15.248Z"
                                                                            fill="currentColor"></path>
                                                                    </svg>

                                                                    <span class="ms-1"
                                                                        style="font-size: 8px;">ACTIVAR</span>
                                                                </a>
                                                            @endif
                                                        @endif

                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                                <div>
                                    {{ $miscitaslista->links() }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-modal wire:model="editar">
        <div class="px-6 py-4">
            <div class="text-lg font-medium text-gray-900">
                EDITAR PAGO DE CITA
            </div>
            <div class="mt-4 text-sm text-gray-600">
                <form>
                    <div class="form-group">
                        <label class="form-label" for="">PAGO TOTAL:</label>
                        <input type="number" class="form-control" id="exampleInputDisabled1"
                            wire:model="operativo.cantidad">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="">PAGO YA REALIZADO:</label>
                        <input type="number" class="form-control" id="exampleInputDisabled1"
                            wire:model="operativo.pagado">
                    </div>
                </form>
            </div>
        </div>

        <div class="flex flex-row justify-end px-6 py-4 text-right bg-gray-100">
            <label type="submit" class="btn btn-success" wire:click="guardartodo">Guardar</label>
        </div>
    </x-modal>
</div>
