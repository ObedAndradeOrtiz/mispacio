<div>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script lang="javascript" src="https://cdn.sheetjs.com/xlsx-0.20.1/package/dist/xlsx.mini.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.5/xlsx.full.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                document.getElementById('myButton').click();
            }, 1);
        });
    </script>
    <div class="section-body">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center">
                <div class="header-action">
                    <h1 class="page-title">Inicio</h1>
                    <ol class="breadcrumb page-breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Miora</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Marketing</li>
                    </ol>
                </div>
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a class="nav-link @if ($opcion == 0) active @endif" data-toggle="tab"
                            href="#admin-Dashboard" wire:click="setOpcion(0)">Transacciones/Publicidades</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if ($opcion == 1) active @endif" data-toggle="tab"
                            href="#admin-Activity" wire:click="setOpcion(1)">Planificación</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link @if ($opcion == 2) active @endif" data-toggle="tab"
                            href="#admin-agendado" wire:click="setOpcion(2)">Edición</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="mt-4 section-body">
        <div class="container-fluid">
            @if ($opcion == 0)
                <div class="row">
                    <div class="col-md-3">
                        <div class="card ">
                            <div class="row">
                                <div class="flex-wrap align-items-center">
                                    <div class="mt-2 ml-2 mr-4">
                                        <div class="d-flex">
                                            <h3 style="font-size: 24px;">PANEL DE MARKETING</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-wrap mt-2 ml-2 mr-4">
                                <div class="form-group" style="margin-right: 5%;">
                                    <label style="font-size: 10px;">Tipo de lista: </label>
                                    <select wire:model="botonRecepcion" style="font-size: 8px;">
                                        <option value="transacciones">TRANSACCIONES CAJA</option>
                                        <option value="tarjetas">LISTA DE TARJETAS</option>
                                        <option value="cuentas">LISTA DE CUENTAS COMERCIALES</option>
                                        <option value="publicidades">LISTA DE PUBLICIDADES</option>

                                        <option value="abreviatura">LISTA DE ABREVIATURAS</option>
                                        <option value="mensajes">LISTA DE MENSAJES</option>
                                        <option value="mbq">LISTA VENTAS MBQ</option>
                                    </select>
                                </div>
                            </div>
                            <div class="table-responsive">

                                <table id="user-list-table" class="table table-striped" role="grid"
                                    data-bs-toggle="data-table">
                                    <thead>

                                        <th style="font-size: 10px;">
                                            SALDO <br> DISTRIBUIDO
                                        </th>
                                        <th style="font-size: 10px;">
                                            SALDO <br> TARJETA <br> PRINCIPAL
                                        </th>

                                        <th style="font-size: 10px;">
                                            SALDO <br> TOTAL
                                        </th>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                {{ $saldotarjetas }}
                                            </td>
                                            <td>
                                                {{ $sumasaldomi }}
                                            </td>
                                            <td>
                                                {{ $sumasaldo }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="font-size: 10px;"> <strong>GASTO TOTAL</strong> </td>
                                            <td></td>
                                            <td> {{ $saldodistribuido }}</td>
                                        </tr>
                                        <tr>
                                            <td style="font-size: 10px;"> <strong>INGRESO TOTAL</strong> </td>
                                            <td></td>
                                            <td> {{ $saldodingresado }}</td>
                                        </tr>
                                        <tr>
                                            <td style="font-size: 10px;"> <strong>RESTANTE TOTAL</strong> </td>
                                            <td></td>
                                            <td> {{ round((float) ($saldodingresado - $saldodistribuido), 2) }}</td>
                                        </tr>
                                        <tr>
                                            <td style="font-size: 10px;"> <strong>FALTANTE TOTAL</strong> </td>
                                            <td></td>
                                            <td> {{ round((float) ($saldodingresado - $saldodistribuido - $sumasaldo), 2) }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            {{-- <div class="table-responsive">
                                <table id="user-list-table" class="table table-striped" role="grid"
                                    data-bs-toggle="data-table">
                                    <thead>
                                        <th>MSJ HOY</th>
                                        <th>MSJ TOTAL</th>
                                        <th>PROM</th>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                0
                                            </td>
                                            <td>
                                                0
                                            </td>
                                            <td>
                                                0
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div> --}}
                            <div class="table-responsive">
                                <table id="user-list-table" class="table table-striped" role="grid"
                                    data-bs-toggle="data-table">
                                    <thead style="font-size: 10px;">
                                        <th><strong>PUBLICIDADES ACTIVAS</strong> </th>
                                        <th>{{ $publicidadActivas }}</th>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                            <div class="">
                                <button class="btn btn-success" style="width: 100%;">AGREGAR NRO. MENSAJES</button>
                            </div>
                            <div class="mt-3 mb-4">
                                @livewire('marketing.crear-transaccion')
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="card">
                            <div class="text-sm text-gray-600">
                                <form>
                                    @if ($botonRecepcion == 'transacciones')
                                        <div class="mb-2 ml-4">
                                            <h2 style="font-size: 18px;"><strong>TRASACCIONES REALIZADOS EN CAJA DE
                                                    MARKETING</strong> </h2>
                                        </div>
                                        <div>
                                            <div class="flex-wrap mt-2 ml-4 mr-4" style="display: flex;">

                                                <div class="mb-2 mr-1">
                                                    <div>
                                                        <label>Desde:</label>
                                                    </div>

                                                    <input type="date" id="fecha-inicio" wire:model="fechaInicioMes">
                                                </div>
                                                <div class="ml-1 mr-1">
                                                    <div>
                                                        <label>Hasta:</label>
                                                    </div>
                                                    <input type="date" id="fecha-actual" wire:model="fechaActual">
                                                </div>
                                                <div class="form-group" style="margin-right: 1%;">
                                                    <div>
                                                        <label>TARJETA: </label>
                                                    </div>
                                                    <div><select wire:model="tarjetaseleccionada">
                                                            <option value="">Todas</option>
                                                            @foreach ($tarjetas as $lista)
                                                                <option value="{{ $lista->id }}">
                                                                    {{ $lista->nombretarjeta }}
                                                                </option>
                                                            @endforeach
                                                        </select></div>

                                                </div>
                                                <div class="form-group" style="margin-right: 1%;">
                                                    <div>
                                                        <label>MOVIMIENTO: </label>
                                                    </div>
                                                    <div><select wire:model="tipode">
                                                            <option value="">Todos</option>
                                                            <option value="seguro">Seguro</option>
                                                            <option value="envio">EMISORA</option>
                                                            <option value="recibo">RECEPTORA</option>
                                                            <option value="enviosaldo">AUMENTO DE SALDO</option>
                                                        </select></div>

                                                </div>
                                                <div class="form-group">
                                                    <label class="btn btn-success" wire:click="exportarPagos"
                                                        id="descargarExcel">Exportar a
                                                        excel Anual</label>
                                                </div>
                                                <div class="form-group">
                                                    <label class="ml-2 btn btn-warning" wire:click='exportarIngresos'
                                                        id="descargarExcelAnual">Exportar
                                                        ingresos Anual</label>
                                                </div>
                                            </div>
                                        </div>

                                        <script>
                                            document.addEventListener('livewire:load', function() {
                                                Livewire.on('exportarExcelIngresos', function(transacciones) {
                                                    if (!transacciones.length) {
                                                        alert("No hay transacciones para exportar.");
                                                        return;
                                                    }

                                                    // Convertir los datos a un formato adecuado para Excel
                                                    const workbook = XLSX.utils.book_new();
                                                    const worksheet = XLSX.utils.json_to_sheet(transacciones);
                                                    XLSX.utils.book_append_sheet(workbook, worksheet, 'PAGOS-TRANSACCIONES-TARJETAS');

                                                    // Generar un identificador único basado en la fecha y hora actual (timestamp)
                                                    const uniqueIdentifier = new Date().toISOString().replace(/[-:.TZ]/g, '');
                                                    const fileName = `PAGOS-TRANSACCIONES-TARJETAS-${uniqueIdentifier}.xlsx`;

                                                    // Generar y descargar el archivo Excel en el navegador con un nombre único
                                                    XLSX.writeFile(workbook, fileName);
                                                });

                                                Livewire.on('error', function(message) {
                                                    alert(message);
                                                });
                                            });
                                        </script>

                                        <script>
                                            document.addEventListener('livewire:load', function() {
                                                Livewire.on('exportarExcel', function(transacciones) {
                                                    if (!transacciones.length) {
                                                        alert("No hay transacciones para exportar.");
                                                        return;
                                                    }

                                                    // Convertir los datos a un formato adecuado para Excel
                                                    const workbook = XLSX.utils.book_new();
                                                    const worksheet = XLSX.utils.json_to_sheet(transacciones);
                                                    XLSX.utils.book_append_sheet(workbook, worksheet, 'GASTOS-TRANSACCIONES-TARJETAS');

                                                    // Generar un identificador único basado en la fecha y hora actual (timestamp)
                                                    const uniqueIdentifier = new Date().toISOString().replace(/[-:.TZ]/g, '');
                                                    const fileName = `GASTOS-TRANSACCIONES-TARJETAS-${uniqueIdentifier}.xlsx`;

                                                    // Generar y descargar el archivo Excel en el navegador con un nombre único
                                                    XLSX.writeFile(workbook, fileName);
                                                });
                                                Livewire.on('error', function(message) {
                                                    alert(message);
                                                });
                                            });
                                        </script>

                                        <div class="mb-2 ml-4">
                                            <label for="">Se estan mostrando:
                                                {{ $registrotransaccionestotal }}
                                                transacciones.</label>
                                        </div>
                                        <div class="table-responsive">
                                            <table id="mitablaregistros1" class="table table-striped" role="grid"
                                                data-bs-toggle="data-table">
                                                <thead>
                                                    <tr>
                                                        <th>TRANSACCION</th>
                                                        <th>MONTO</th>
                                                        <th>MONTO DE USO</th>
                                                        <th>TARJETA <br> EMISORA</th>
                                                        <th>TARJETA <br> RECEPTORA</th>
                                                        <th>CUENTA <br> COMERCIAL</th>
                                                        <th>FECHA</th>
                                                        <th>CODIGO</th>
                                                        <th>RESPONSABLE</th>
                                                        <th>ACCIÓN</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($registrotransacciones as $item)
                                                        <tr>
                                                            <td>{{ $item->motivo }}</td>
                                                            <td>{{ $item->monto }}</td>
                                                            <td>{{ $item->montouso }}</td>
                                                            <td>{{ $item->tarjetaprincipal }}</td>
                                                            <td>{{ $item->tarjeta }}</td>
                                                            <td>{{ $item->nombrecuenta }}</td>
                                                            <td>{{ $item->fecha }}</td>
                                                            <td>{{ $item->codigo }}</td>
                                                            <td>{{ $item->responsable }}</td>

                                                            <td>
                                                                <a class="btn btn-sm btn-icon btn-danger"
                                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                                    title="Edit" data-original-title="Edit"
                                                                    wire:click="$emit('eliminarTransaccion',{{ $item->id }})">
                                                                    <span class="btn-inner">
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
                                                                    </span>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            <div class="px-6 py-3">
                                                {{ $registrotransacciones->links() }}
                                            </div>
                                        </div>
                                    @endif
                                    @if ($botonRecepcion == 'tarjetas')
                                        @livewire('marketing.mark-tarjetas')
                                    @endif
                                    @if ($botonRecepcion == 'cuentas')
                                        @livewire('marketing.mark-comerciales')
                                    @endif
                                    @if ($botonRecepcion == 'publicidades')
                                        <div>
                                            <div class="flex-wrap mt-2 ml-4 mr-4" style="display: flex;">
                                            </div>
                                            <div class="flex flex-row justify-between">
                                                <h3 class="ml-4" style="font-size: 18px;"><strong>LISTA DE
                                                        PUBLICIDADES</strong>
                                                </h3>
                                                @livewire('marketing.crear-publicidad')
                                            </div>
                                            <div class="table-responsive">
                                                <table class="table table-striped" role="grid"
                                                    data-bs-toggle="data-table">
                                                    <thead>
                                                        <tr>
                                                            <th>ACT/DESC</th>
                                                            <th>TIPO</th>
                                                            <th>SUCURSAL</th>
                                                            <th>CUENTA COMERCIAL</th>
                                                            <th>FECHA INICIO</th>
                                                            <th>FECHA FIN</th>
                                                            <th>COMENTARIO</th>
                                                            <th>ACCIÓN</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($tot as $item)
                                                            <tr data-id="{{ $item->id }}"
                                                                data-nombrecampana="{{ $item->nombrecampana }}"
                                                                data-sucursal="{{ $item->sucursal }}"
                                                                data-nombrecuenta="{{ $item->nombrecuenta }}"
                                                                data-fechainicio="{{ $item->fechainicio }}"
                                                                data-fechafin="{{ $item->fechafin }}"
                                                                data-estado="{{ $item->estado }}"
                                                                data-motivo="{{ $item->motivo }}">
                                                                <td>
                                                                    @if ($item->estado == 'Activo')
                                                                        <input type="checkbox"
                                                                            wire:click="guardartodo({{ $item->id }})"
                                                                            checked>
                                                                    @else
                                                                        <input type="checkbox"
                                                                            wire:click="guardartodo({{ $item->id }})">
                                                                    @endif
                                                                </td>
                                                                <td class="clickable">{{ $item->nombrecampana }}</td>
                                                                <td class="clickable">{{ $item->sucursal }}</td>
                                                                <td class="clickable">{{ $item->nombrecuenta }}</td>
                                                                <td class="clickable">{{ $item->fechainicio }}</td>
                                                                <td class="clickable">{{ $item->fechafin }}</td>
                                                                <td class="clickable">{{ $item->motivo }}</td>
                                                                <td>
                                                                    <div class="d-flex">
                                                                        <a class="btn btn-sm btn-icon btn-danger"
                                                                            data-bs-toggle="tooltip"
                                                                            data-bs-placement="top" title="Eliminar"
                                                                            data-original-title="Eliminar"
                                                                            wire:click="$emit('eliminarPublicidadTotal',{{ $item->id }})">
                                                                            <span class="btn-inner">
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
                                                                            </span>
                                                                        </a>
                                                                        @livewire('marketing.editar-publicidad', ['idpublicidad' => $item->id], key($item->id))
                                                                    </div>

                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                                <div class="px-6 py-3">

                                                </div>
                                            </div>
                                            <!-- Modal -->
                                            <div class="modal fade" id="detallePublicidadModal" tabindex="-1"
                                                aria-labelledby="detallePublicidadModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="detallePublicidadModalLabel">
                                                                Detalles de
                                                                Publicidad</h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body" id="detallePublicidad">
                                                            <!-- Aquí se mostrarán los detalles de la publicidad seleccionada -->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <script>
                                                const detallesPublicidadModal = document.getElementById('detallePublicidadModal');
                                                const detallePublicidad = document.getElementById('detallePublicidad');


                                                const tdClickables = document.querySelectorAll('.clickable');
                                                tdClickables.forEach(td => {
                                                    td.addEventListener('click', function() {

                                                        const tr = this.parentElement;
                                                        const id = tr.getAttribute('data-id');
                                                        const nombrecampana = tr.getAttribute('data-nombrecampana');
                                                        const sucursal = tr.getAttribute('data-sucursal');
                                                        const nombrecuenta = tr.getAttribute('data-nombrecuenta');
                                                        const fechainicio = tr.getAttribute('data-fechainicio');
                                                        const fechafin = tr.getAttribute('data-fechafin');
                                                        const estado = tr.getAttribute('data-estado');
                                                        const motivo = tr.getAttribute('data-motivo');
                                                        detallePublicidad.innerHTML = `

                                                <p>Nombre de Campaña: ${nombrecampana}</p>
                                                <p>Sucursal: ${sucursal}</p>
                                                <p>Nombre de Cuenta: ${nombrecuenta}</p>
                                                <p>Fecha de Inicio: ${fechainicio}</p>
                                                <p>Fecha de Fin: ${fechafin}</p>
                                                <p>Estado: ${estado}</p>
                                                <p>Comentario: ${motivo}</p>
                                            `;
                                                        var modal = new bootstrap.Modal(detallesPublicidadModal);
                                                        modal.show();
                                                    });
                                                });
                                            </script>




                                        </div>
                                    @endif
                                    @if ($botonRecepcion == 'campañas')
                                        @livewire('marketing.mark-campanas')
                                    @endif
                                    @if ($botonRecepcion == 'abreviatura')
                                        @livewire('marketing.mark-abreviaturas')
                                    @endif
                                    @if ($botonRecepcion == 'mensajes')
                                        @livewire('marketing.mark-mensajes')
                                    @endif
                                    @if ($botonRecepcion == 'mbq')
                                        @livewire('marketing.mbq')
                                    @endif

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @if ($opcion == 1)
                @livewire('marketing.mark-planificacion')
            @endif
        </div>
    </div>


</div>
