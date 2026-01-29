<div>
    <div>
        <!-- FileSaver.js -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.3/xlsx.full.min.js"></script>
        <!-- Importar la biblioteca FileSaver.js -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>

        <style>
            .fixed-button-z {
               
                right: 0px;
                bottom: 33%;
                padding: 15px;
                background-color: rgb(17, 156, 236);
                color: white;
                border: none;
                border-radius: 5%;
                cursor: pointer;
                z-index: 9999;
                width: 150px;
                /* Ancho predeterminado */
                margin: 1%;
                height: 25px;
            }

            /* Cambia el ancho del botón cuando el ancho de la pantalla sea menor a 900px */
            @media (max-width: 900px) {
                .fixed-button-z {
                    width: 50px;
                }

                .fixed-button-z span {
                    display: none;
                    /* Oculta la palabra "REGISTRO" */
                }
            }
        </style>

        <label class="fixed-button-z" style="display: flex; align-items: center;" wire:click="$set('openAreaGasto',true)">
            <i class="icon">
                <svg class="icon-20" width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path opacity="0.4"
                        d="M16.191 2H7.81C4.77 2 3 3.78 3 6.83V17.16C3 20.26 4.77 22 7.81 22H16.191C19.28 22 21 20.26 21 17.16V6.83C21 3.78 19.28 2 16.191 2Z"
                        fill="currentColor"></path>
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M8.07996 6.6499V6.6599C7.64896 6.6599 7.29996 7.0099 7.29996 7.4399C7.29996 7.8699 7.64896 8.2199 8.07996 8.2199H11.069C11.5 8.2199 11.85 7.8699 11.85 7.4289C11.85 6.9999 11.5 6.6499 11.069 6.6499H8.07996ZM15.92 12.7399H8.07996C7.64896 12.7399 7.29996 12.3899 7.29996 11.9599C7.29996 11.5299 7.64896 11.1789 8.07996 11.1789H15.92C16.35 11.1789 16.7 11.5299 16.7 11.9599C16.7 12.3899 16.35 12.7399 15.92 12.7399ZM15.92 17.3099H8.07996C7.77996 17.3499 7.48996 17.1999 7.32996 16.9499C7.16996 16.6899 7.16996 16.3599 7.32996 16.1099C7.48996 15.8499 7.77996 15.7099 8.07996 15.7399H15.92C16.319 15.7799 16.62 16.1199 16.62 16.5299C16.62 16.9289 16.319 17.2699 15.92 17.3099Z"
                        fill="currentColor"></path>
                </svg>
            </i>
            <span style="margin-left: 5px;">REGISTRO</span>
        </label>

        <style>
            .boton {
                display: inline-block;
                padding: 10px 20px;
                font-size: 16px;
                font-weight: bold;
                color: #ffffff;
                background-color: #007bff;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                text-decoration: none;
                text-align: center;
                transition: background-color 0.3s ease;
            }

            .boton:hover {
                background-color: #0056b3;
            }
        </style>
        <x-modal wire:model="openAreaGasto">
            <div class="px-6 py-4">
                <div class="text-lg font-medium text-gray-900">
                    MiS REGISTROS: {{ Auth::user()->name }}
                </div>
                <div>
                    <div class="" style="display: flex; font-size: 1vw;">
                        <label for="fecha-inicio mr-2">Desde:</label>
                        <input style="font-size: 1vw;" class="mr-2" type="date" id="fecha-inicio"
                            wire:model="fechaInicioMes">
                        <label for="fecha-actual mr-2">Hasta:</label>
                        <input style="font-size: 1vw;" class="mr2" type="date" id="fecha-actual"
                            wire:model="fechaActual">
                        {{-- <button class="ml-2 boton" onclick="exportToExcel()">Exportar a Excel</button> --}}
                    </div>
                </div>
                <div class="mt-4 text-sm text-gray-600">
                    <form>
                        @php

                            $total_monto = DB::table('registropagos')
                                ->where('iduser', Auth::user()->id)
                                ->where('modo', 'ilike', '%Efectivo%')
                                ->where('fecha', '<=', $fechaActual)
                                ->where('fecha', '>=', $fechaInicioMes)
                                ->sum(DB::raw('CAST(monto AS DECIMAL(10, 2))'));
                            $total_inventario = DB::table('registroinventarios')
                                ->where('iduser', Auth::user()->id)
                                ->where('modo', 'ilike', '%Efectivo%')
                                ->where('fecha', '<=', $fechaActual)
                                ->where('fecha', '>=', $fechaInicioMes)
                                ->where('motivo', 'compra')
                                ->sum(DB::raw('CAST(precio AS DECIMAL(10, 2))'));
                            $gastoarea = DB::table('gastos')
                                ->where('modo', 'ilike', '%Efectivo%')
                                ->where('iduser', Auth::user()->id)
                                ->where('fechainicio', '<=', $fechaActual)
                                ->where('fechainicio', '>=', $fechaInicioMes)
                                ->sum('cantidad');
                            $total_monto_citas = DB::table('registropagos')
                                ->where('iduser', Auth::user()->id)
                                ->where('fecha', '<=', $fechaActual)
                                ->where('fecha', '>=', $fechaInicioMes)
                                ->where('modo', 'ilike', '%Efectivo%')
                                ->get();
                            $total_monto_qr = DB::table('registropagos')
                                ->where('iduser', Auth::user()->id)
                                ->where('modo', 'ilike', '%qr%')
                                ->where('fecha', '<=', $fechaActual)
                                ->where('fecha', '>=', $fechaInicioMes)
                                ->sum(DB::raw('CAST(monto AS DECIMAL(10, 2))'));
                            $total_inventario_qr = DB::table('registroinventarios')
                                ->where('iduser', Auth::user()->id)
                                ->where('modo', 'ilike', '%Qr%')
                                ->where('fecha', '<=', $fechaActual)
                                ->where('fecha', '>=', $fechaInicioMes)
                                ->where('motivo', 'compra')
                                ->sum(DB::raw('CAST(precio AS DECIMAL(10, 2))'));
                            $total_inventario_farmacia = DB::table('registroinventarios')
                                ->where('iduser', Auth::user()->id)
                                ->where('fecha', '<=', $fechaActual)
                                ->where('fecha', '>=', $fechaInicioMes)
                                ->where('motivo', 'farmacia')
                                ->get();
                            $total_inventario_cliente = DB::table('registroinventarios')
                                ->where('iduser', Auth::user()->id)
                                ->where('fecha', '<=', $fechaActual)
                                ->where('fecha', '>=', $fechaInicioMes)
                                ->where('motivo', 'compra')
                                ->get();
                            $total_inventario_pago = DB::table('registroinventarios')
                                ->where('iduser', Auth::user()->id)
                                ->where('modo', 'ilike', '%Efectivo%')
                                ->where('fecha', '<=', $fechaActual)
                                ->where('fecha', '>=', $fechaInicioMes)
                                ->where('motivo', 'compra')
                                ->get();

                        @endphp
                        <div class="mb-1" style="font-size: 1vw;">
                            <h6>REGISTRO DE LA FECHA SELECCIONADA</h6>
                        </div>
                        <div class="table-responsive">
                            <table id="mitablaregistros" class="table table-striped" role="grid"
                                data-bs-toggle="data-table">
                                <thead>
                                    <tr>
                                        <th>Llamadas creadas</th>
                                        <th>Llamdas agendadas</th>
                                        <th>Citas creadas</th>
                                        <th>Remarketing</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        @php
                                            $misllamada = DB::table('registrollamadas')
                                                ->where('responsable', Auth::user()->name)
                                                ->where('sucursal', '0')
                                                ->whereBetween('fecha', [$fechaInicioMes, $fechaActual])
                                                ->count();
                                            $remarketing = DB::table('registrollamadas')
                                                ->where('responsable', Auth::user()->name)
                                                ->where('sucursal', '1')
                                                ->whereBetween('fecha', [$fechaInicioMes, $fechaActual])
                                                ->count();
                                            $misagendados = DB::table('calls')
                                                ->where('responsable', Auth::user()->name)
                                                ->where('estado', '!=', 'llamadas')
                                                ->whereBetween('fecha', [$fechaInicioMes, $fechaActual])
                                                ->count();
                                            $fechaInicioMes = date('Y-m-d', strtotime($fechaInicioMes));
                                            $fechaActual = date('Y-m-d', strtotime($fechaActual));
                                            $miscitas = DB::table('operativos')
                                                ->where('responsable', Auth::user()->name)
                                                ->whereDate('created_at', '>=', $fechaInicioMes)
                                                ->whereDate('created_at', '<=', $fechaActual)
                                                ->count();

                                        @endphp
                                        <td>{{ $misllamada }}</td>
                                        <td>{{ $misagendados }}</td>
                                        <td>{{ $miscitas }}</td>
                                        <td>{{ $remarketing }}</td>
                                    </tr>


                                </tbody>
                            </table>
                        </div>
                        @php
                            $misllamada = DB::table('calls')
                                ->where('responsable', Auth::user()->name)
                                ->whereBetween('fecha', [$fechaInicioMes, $fechaActual])
                                ->get();
                            $cantidad = DB::table('calls')
                                ->where('responsable', Auth::user()->name)
                                ->where('estado', '!=', 'llamadas')
                                ->whereBetween('fecha', [$fechaInicioMes, $fechaActual])
                                ->get();
                            $misagendados = DB::table('calls')
                                ->where('responsable', Auth::user()->name)
                                ->where('estado', '!=', 'llamadas')
                                ->whereBetween('fecha', [$fechaInicioMes, $fechaActual])
                                ->get();
                            $fechaInicioMes = date('Y-m-d', strtotime($fechaInicioMes));
                            $fechaActual = date('Y-m-d', strtotime($fechaActual));
                            $miscitas = DB::table('operativos')
                                ->where('responsable', Auth::user()->name)
                                ->whereDate('created_at', '>=', $fechaInicioMes)
                                ->whereDate('created_at', '<=', $fechaActual)
                                ->get();

                        @endphp
                        <div class="mb-1">
                            <h6>REGISTRO DE MIS LLAMADAS AGENDADAS</h6>
                        </div>
                        <div class="table-responsive">
                            <table id="mitablaregistros1" class="table table-striped" role="grid"
                                data-bs-toggle="data-table">
                                <thead>
                                    <tr>
                                        <th>CLIENTE</th>
                                        <th>NUMERO</th>
                                        <th>FECHA Y HORA</th>

                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($misagendados as $item)
                                        <tr>
                                            <td>{{ $item->empresa }}</td>
                                            <td>{{ $item->telefono }}</td>
                                            <td>{{ $item->created_at }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mb-1">
                            <h6>REGISTRO DE MIS CITAS CREADAS</h6>
                        </div>
                        <div class="table-responsive">
                            <table id="mitablaregistros" class="table table-striped" role="grid"
                                data-bs-toggle="data-table">
                                <thead>
                                    <tr>
                                        <th>CLIENTE</th>
                                        <th>NUMERO</th>
                                        <th>FECHA Y HORA</th>
                                        <th>ACCIÓN</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($miscitas as $item)
                                        <tr>
                                            <td>{{ $item->empresa }}</td>
                                            <td>{{ $item->telefono }}</td>
                                            <td>{{ $item->created_at }}</td>
                                            <td>
                                                <a class="mt-1 btn btn-sm btn-icon btn-danger d-flex align-items-center"
                                                    data-bs-toggle="tooltip" data-bs-placement="top" title="ELIMINAR"
                                                    data-original-title="Edit"
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
                                                    <span class="ms-1" style="font-size: 8px;">ELIMINAR</span>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mb-1">
                            <h6>REGISTRO DE PRODUCTOS USADOS EN GABINETE</h6>
                        </div>
                        <div class="table-responsive">
                            <table id="mitablaregistros1" class="table table-striped" role="grid"
                                data-bs-toggle="data-table">
                                <thead style="font-size: 1vw;">
                                    <tr>
                                        <th>PRODUCTO</th>
                                        <th>CANTIDAD</th>
                                        <th>SUCURSAL</th>
                                        <th>ACCIÓN</th>
                                    </tr>
                                </thead>
                                <tbody style="font-size: 1vw;">
                                    <tr>
                                        @foreach ($registroinv as $item)
                                            <td>{{ $item->nombreproducto }}</td>
                                            <td>{{ $item->cantidad }}</td>
                                            <td>{{ $item->sucursal }}</td>
                                            <td> <a class="mt-1 btn btn-sm btn-icon btn-danger d-flex align-items-center"
                                                    data-bs-toggle="tooltip" data-bs-placement="top" title="ELIMINAR"
                                                    data-original-title="Edit"
                                                    wire:click="$emit('eliminarProductoreg',{{ $item->id }})">
                                                    <svg class="icon-20" width="20" viewBox="0 0 24 24"
                                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path opacity="0.4"
                                                            d="M16.34 1.99976H7.67C4.28 1.99976 2 4.37976 2 7.91976V16.0898C2 19.6198 4.28 21.9998 7.67 21.9998H16.34C19.73 21.9998 22 19.6198 22 16.0898V7.91976C22 4.37976 19.73 1.99976 16.34 1.99976Z"
                                                            fill="currentColor"></path>
                                                        <path
                                                            d="M15.0158 13.7703L13.2368 11.9923L15.0148 10.2143C15.3568 9.87326 15.3568 9.31826 15.0148 8.97726C14.6728 8.63326 14.1198 8.63426 13.7778 8.97626L11.9988 10.7543L10.2198 8.97426C9.87782 8.63226 9.32382 8.63426 8.98182 8.97426C8.64082 9.31626 8.64082 9.87126 8.98182 10.2123L10.7618 11.9923L8.98582 13.7673C8.64382 14.1093 8.64382 14.6643 8.98582 15.0043C9.15682 15.1763 9.37982 15.2613 9.60382 15.2613C9.82882 15.2613 10.0518 15.1763 10.2228 15.0053L11.9988 13.2293L13.7788 15.0083C13.9498 15.1793 14.1728 15.2643 14.3968 15.2643C14.6208 15.2643 14.8448 15.1783 15.0158 15.0083C15.3578 14.6663 15.3578 14.1123 15.0158 13.7703Z"
                                                            fill="currentColor"></path>
                                                    </svg>
                                                    <span class="ms-1" style="font-size: 8px;">ELIMINAR</span>
                                                </a></td>
                                        @endforeach

                                    </tr>


                                </tbody>
                            </table>
                        </div>
                        <div class="mb-1">
                            <h6>REGISTRO DE PRODUCTOS VENDIDOS A CLIENTES</h6>
                        </div>
                        <div class="table-responsive">
                            <table id="mitablaregistros1" class="table table-striped" role="grid"
                                data-bs-toggle="data-table">
                                <thead>
                                    <tr>
                                        <th>PRODUCTO</th>
                                        <th>CANTIDAD</th>
                                        <th>MONTO</th>
                                        <th>MODO</th>
                                        <th>SUCURSAL</th>
                                        <th>CLIENTE</th>
                                        <th>ACCIÓN</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $pagado = 0;
                                    @endphp
                                    @if ($total_inventario_cliente)
                                        @foreach ($total_inventario_cliente as $item)
                                            <tr>
                                                @php
                                                    $pagado = $pagado + $item->precio;
                                                @endphp
                                                <td>{{ $item->nombreproducto }}</td>
                                                <td>{{ $item->cantidad }}</td>
                                                <td>{{ $item->precio }}</td>
                                                <td>{{ $item->modo }}</td>
                                                <td>{{ $item->sucursal }}</td>
                                                @php
                                                    $cliente = DB::table('users')
                                                        ->select('name')
                                                        ->where('id', $item->idcliente)
                                                        ->get();
                                                @endphp
                                                @foreach ($cliente as $item1)
                                                    <td>{{ $item1->name }}</td>
                                                @endforeach
                                                <td> <a class="mt-1 btn btn-sm btn-icon btn-danger d-flex align-items-center"
                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                        title="ELIMINAR" data-original-title="Edit"
                                                        wire:click="$emit('eliminarProductoreg',{{ $item->id }})">
                                                        <svg class="icon-20" width="20" viewBox="0 0 24 24"
                                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path opacity="0.4"
                                                                d="M16.34 1.99976H7.67C4.28 1.99976 2 4.37976 2 7.91976V16.0898C2 19.6198 4.28 21.9998 7.67 21.9998H16.34C19.73 21.9998 22 19.6198 22 16.0898V7.91976C22 4.37976 19.73 1.99976 16.34 1.99976Z"
                                                                fill="currentColor"></path>
                                                            <path
                                                                d="M15.0158 13.7703L13.2368 11.9923L15.0148 10.2143C15.3568 9.87326 15.3568 9.31826 15.0148 8.97726C14.6728 8.63326 14.1198 8.63426 13.7778 8.97626L11.9988 10.7543L10.2198 8.97426C9.87782 8.63226 9.32382 8.63426 8.98182 8.97426C8.64082 9.31626 8.64082 9.87126 8.98182 10.2123L10.7618 11.9923L8.98582 13.7673C8.64382 14.1093 8.64382 14.6643 8.98582 15.0043C9.15682 15.1763 9.37982 15.2613 9.60382 15.2613C9.82882 15.2613 10.0518 15.1763 10.2228 15.0053L11.9988 13.2293L13.7788 15.0083C13.9498 15.1793 14.1728 15.2643 14.3968 15.2643C14.6208 15.2643 14.8448 15.1783 15.0158 15.0083C15.3578 14.6663 15.3578 14.1123 15.0158 13.7703Z"
                                                                fill="currentColor"></path>
                                                        </svg>
                                                        <span class="ms-1" style="font-size: 8px;">ELIMINAR</span>
                                                    </a></td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    <tr class="bg-gray">
                                        <td style="color: white">Total</td>
                                        <td style="color: white"></td>
                                        <td style="color: white">{{ $pagado }}</td>
                                        <td style="color: white"></td>
                                        <td style="color: white"></td>
                                        <td style="color: white"></td>
                                        <td style="color: white"></td>

                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="mb-1">
                            <h6>REGISTRO DE PRODUCTOS VENDIDOS EN FARMACIA</h6>
                        </div>
                        <div class="table-responsive">
                            <table id="mitablaregistros1" class="table table-striped" role="grid"
                                data-bs-toggle="data-table">
                                <thead>
                                    <tr>
                                        <th>PRODUCTO</th>
                                        <th>CANTIDAD</th>
                                        <th>MONTO</th>
                                        <th>MODO</th>
                                        <th>SUCURSAL</th>
                                        <th>ACCIÓN</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $pagado = 0;
                                    @endphp
                                    @if ($total_inventario_farmacia)
                                        @foreach ($total_inventario_farmacia as $item)
                                            <tr>
                                                @php
                                                    $pagado = $pagado + $item->precio;
                                                @endphp
                                                <td>{{ $item->nombreproducto }}</td>
                                                <td>{{ $item->cantidad }}</td>
                                                <td>{{ $item->precio }}</td>
                                                <td>{{ $item->modo }}</td>

                                                <td>{{ $item->sucursal }}</td>
                                                <td> <a class="mt-1 btn btn-sm btn-icon btn-danger d-flex align-items-center"
                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                        title="ELIMINAR" data-original-title="Edit"
                                                        wire:click="$emit('eliminarProductoreg',{{ $item->id }})">
                                                        <svg class="icon-20" width="20" viewBox="0 0 24 24"
                                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path opacity="0.4"
                                                                d="M16.34 1.99976H7.67C4.28 1.99976 2 4.37976 2 7.91976V16.0898C2 19.6198 4.28 21.9998 7.67 21.9998H16.34C19.73 21.9998 22 19.6198 22 16.0898V7.91976C22 4.37976 19.73 1.99976 16.34 1.99976Z"
                                                                fill="currentColor"></path>
                                                            <path
                                                                d="M15.0158 13.7703L13.2368 11.9923L15.0148 10.2143C15.3568 9.87326 15.3568 9.31826 15.0148 8.97726C14.6728 8.63326 14.1198 8.63426 13.7778 8.97626L11.9988 10.7543L10.2198 8.97426C9.87782 8.63226 9.32382 8.63426 8.98182 8.97426C8.64082 9.31626 8.64082 9.87126 8.98182 10.2123L10.7618 11.9923L8.98582 13.7673C8.64382 14.1093 8.64382 14.6643 8.98582 15.0043C9.15682 15.1763 9.37982 15.2613 9.60382 15.2613C9.82882 15.2613 10.0518 15.1763 10.2228 15.0053L11.9988 13.2293L13.7788 15.0083C13.9498 15.1793 14.1728 15.2643 14.3968 15.2643C14.6208 15.2643 14.8448 15.1783 15.0158 15.0083C15.3578 14.6663 15.3578 14.1123 15.0158 13.7703Z"
                                                                fill="currentColor"></path>
                                                        </svg>
                                                        <span class="ms-1" style="font-size: 8px;">ELIMINAR</span>
                                                    </a></td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    <tr class="bg-gray">
                                        <td style="color: white">Total</td>
                                        <td style="color: white"></td>
                                        <td style="color: white">{{ $pagado }}</td>
                                        <td style="color: white"></td>
                                        <td style="color: white"></td>
                                        <td style="color: white"></td>


                                    </tr>





                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
            </div>


        </x-modal>
        <script>
            function exportToExcel() {
                const table = document.getElementById('mitablaregistros1');
                const wb = XLSX.utils.table_to_book(table, {
                    sheet: "Sheet1"
                });
                // Generar un archivo binario
                const wbout = XLSX.write(wb, {
                    bookType: 'xlsx',
                    type: 'binary'
                });
                // Convertir a un arreglo de bytes
                const buf = new ArrayBuffer(wbout.length);
                const view = new Uint8Array(buf);
                for (let i = 0; i < wbout.length; i++) view[i] = wbout.charCodeAt(i) & 0xFF;
                // Crear una nueva instancia de Blob para almacenar los datos del XLSX
                const blob = new Blob([buf], {
                    type: 'application/octet-stream'
                });
                // Utilizar FileSaver.js para guardar el archivo
                saveAs(blob, 'tabla_excel.xlsx');
            }
        </script>
    </div>

</div>
