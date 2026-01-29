<div>
    @php
        $registrotransacciones = DB::table('transacciones')
            ->where('fecha', '<=', $this->fechaActual)
            ->where('fecha', '>=', $this->fechaInicioMes)
            ->get();
        $registrotransaccionestotal = DB::table('transacciones')
            ->where('fecha', '<=', $this->fechaActual)
            ->where('fecha', '>=', $this->fechaInicioMes)
            ->count();
    @endphp
    <div class="flex-wrap mt-2 ml-4 mr-4" style="display: flex;">
        <div style="margin-right: 1%;">
            {{-- <div class="form-group" style="margin-right: 1%; ">
                <div>
                    <label>Sucursal: </label>
                </div>
                <div>
                    <select wire:model="areaseleccionada">
                        <option value="">Todas</option>
                        @foreach ($areas as $lista)
                            <option value="{{ $lista->area }}">{{ $lista->area }}</option>
                        @endforeach
                    </select>
                </div>

            </div> --}}
            <div class="form-group" style="margin-right: 1%; ">
                <label>Responsable: </label>
                <select wire:model="usuarioseleccionado">
                    <option value="">Todos</option>
                    @foreach ($users as $lista)
                        <option value="{{ $lista->id }}">{{ $lista->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group" style="margin-right: 1%;">
            <div class="mb-2 mr-1">
                <div>
                    <label for="fecha-inicio">Desde:</label>
                </div>

                <input type="date" id="fecha-inicio" wire:model="fechaInicioMes">
            </div>
            <div class="ml-1 mr-1">
                <div>
                    <label for="fecha-actual">Hasta:</label>
                </div>
                <input type="date" id="fecha-actual" wire:model="fechaActual">
            </div>

        </div>
        <div class="form-group" style="margin-right: 1%;">
            <div>
                <label>Cuenta comercial: </label>
            </div>
            <div><select wire:model="cuentaseleccionado">
                    <option value="">Todas</option>
                    @foreach ($cuentas as $lista)
                        <option value="{{ $lista->id }}">{{ $lista->nombrecuenta }}</option>
                    @endforeach
                </select></div>

        </div>
        <div class="ml-4 mr-4">

        </div>

    </div>
    <div class="mb-2 ml-4">
        <h3>TRASACCIONES REALIZADOS EN CAJA DE MARKETING</h3>
    </div>
    {{-- <div class="mt-2" style=" border-radius: 5px;">
        <input type="text" class="form-control" id="exampleInputDisabled1" wire:model="busqueda"
            placeholder="Buscar Producto...">
    </div> --}}
    <div class="mb-2 ml-4">
        <label for="">Se estan mostrando: {{ $registrotransaccionestotal }} transacciones.</label>
    </div>
    <div class="table-responsive">
        <table id="mitablaregistros1" class="table table-striped" role="grid" data-bs-toggle="data-table">
            <thead>
                <tr>
                    <th># TRANSACCION</th>
                    <th>MONTO</th>
                    <th>TARJETA EMISORA</th>
                    <th>TARJETA RECEPTORA</th>
                    <th>CUENTA COMERCIAL</th>
                    <th>FECHA</th>
                    <th>RESPONSABLE</th>
                    <th>ACCIÓN</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($registrotransacciones as $item)
                    <tr>
                        <td>{{ $item->id }}</td>
                        <td>{{ $item->monto }}</td>
                        <td>{{ $item->tarjetaprincipal }}</td>
                        <td>{{ $item->tarjeta }}</td>
                        <td>{{ $item->nombrecuenta }}</td>
                        <td>{{ $item->fecha }}</td>
                        <td>{{ $item->responsable }}</td>
                        <td>
                            <a class="btn btn-sm btn-icon btn-danger" data-bs-toggle="tooltip" data-bs-placement="top"
                                title="Edit" data-original-title="Edit"
                                wire:click="$emit('eliminarTransaccion',{{ $item->id }})">
                                <span class="btn-inner">
                                    <svg class="icon-20" width="20" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path opacity="0.4"
                                            d="M16.34 1.99976H7.67C4.28 1.99976 2 4.37976 2 7.91976V16.0898C2 19.6198 4.28 21.9998 7.67 21.9998H16.34C19.73 21.9998 22 19.6198 22 16.0898V7.91976C22 4.37976 19.73 1.99976 16.34 1.99976Z"
                                            fill="currentColor"></path>
                                        <path
                                            d="M15.0158 13.7703L13.2368 11.9923L15.0148 10.2143C15.3568 9.87326 15.3568 9.31826 15.0148 8.97726C14.6728 8.63326 14.1198 8.63426 13.7778 8.97626L11.9988 10.7543L10.2198 8.97426C9.87782 8.63226 9.32382 8.63426 8.98182 8.97426C8.64082 9.31626 8.64082 9.87126 8.98182 10.2123L10.7618 11.9923L8.98582 13.7673C8.64382 14.1093 8.64382 14.6643 8.98582 15.0043C9.15682 15.1763 9.37982 15.2613 9.60382 15.2613C9.82882 15.2613 10.0518 15.1763 10.2228 15.0053L11.9988 13.2293L13.7788 15.0083C13.9498 15.1793 14.1728 15.2643 14.3968 15.2643C14.6208 15.2643 14.8448 15.1783 15.0158 15.0083C15.3578 14.6663 15.3578 14.1123 15.0158 13.7703Z"
                                            fill="currentColor"></path>
                                    </svg>
                                </span>
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
