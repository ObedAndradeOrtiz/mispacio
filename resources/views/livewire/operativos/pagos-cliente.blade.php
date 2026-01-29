<div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <div class="px-6 py-4">
        <div class="text-lg font-medium text-gray-900">
            Información del cliente: {{ $operativo->empresa }}
        </div>
        <div>
            @php
                $user = DB::table('users')->where('id', $operativo->idempresa)->get();
            @endphp
            @foreach ($user as $item)
                <label class="text-lg font-medium text-gray-900" for="">Teléfono: {{ $item->telefono }}</label>
                <br>
                <label class="text-lg font-medium text-gray-900" for="">Carnet: {{ $item->ci }}</label>
                <br>
                <label class="text-lg font-medium text-gray-900" for="">Edad: {{ $item->edad }}</label>
                <br>
            @endforeach
        </div>
        <div class="table-responsive">
            <table class="table mb-0 table-striped text-nowrap">
                <tbody>
                    <tr>
                        <td>
                            <div class="card" style="background-color: rgba(129, 128, 128, 0.24);">
                                <div class="card-header">
                                    <h3 class="card-title">Registro de tratamientos:</h3>
                                    <div class="card-options">
                                        <div class="d-flex">
                                            <select wire:model="tipodetratamiento" class="mr-1 form-select">
                                                <option value="Inactivo">Tratamientos pendientes</option>
                                                <option value="Finalizado">Tratamientos finalizados</option>
                                            </select>
                                            <label class="btn btn-success" data-bs-toggle="tooltip"
                                                data-bs-placement="top" title="AGREGAR TRATAMIENTO"
                                                data-original-title="Edit" wire:click="$set('agreagar',true)">
                                                <i class="bi bi-plus-circle fs-4"></i>Nuevo tratamiento
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body" style="height: 25vh; overflow-y:scroll;">
                                    <div class="table-responsive">
                                        <table class="table table-striped text-nowrap">
                                            <thead>
                                                <th>TRATAMIENTO</th>
                                                <th>MONTO A COBRAR</th>
                                                <th>MONTO PAGADO</th>
                                                <th>DEUDA</th>
                                                <th>ACCION</th>
                                            </thead>
                                            <tbody>
                                                @foreach ($tratamientos as $lista)
                                                    <tr>
                                                        @if ($lista->estado == $tipodetratamiento)
                                                            <td>
                                                                <label
                                                                    for="">{{ $lista->nombretratamiento }}</label>
                                                            </td>
                                                            <td>
                                                                {{ $lista->costo }}
                                                                <a for="" class="btn btn-warning"
                                                                    wire:click="boolTratamiento({{ $lista->id }})">Editar</a>
                                                            </td>

                                                            <td>@php
                                                                $total_pagado_lista = DB::table('registropagos')
                                                                    ->where('idoperativo', $lista->id)
                                                                    ->sum(DB::raw('CAST(monto AS DECIMAL(10, 2))'));
                                                            @endphp
                                                                {{ $total_pagado_lista }}
                                                            </td>
                                                            <td>
                                                                {{ $lista->costo - $total_pagado_lista }}
                                                            </td>
                                                            <td style="display: flex;">
                                                                @if ($lista->estado == 'Finalizado')
                                                                    <a wire:click="activarTratamiento({{ $lista->id }})"
                                                                        class="mx-1 btn btn-sm btn-success d-flex align-items-center justify-content-center"
                                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                                        title="Recuperar Tratamiento">
                                                                        <i class="fas fa-undo"></i>
                                                                    </a>
                                                                @else
                                                                    <a wire:click="finalizarTratamiento({{ $lista->id }})"
                                                                        class="mx-1 btn btn-sm btn-success d-flex align-items-center justify-content-center"
                                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                                        title="FINALIZAR TRATAMIENTO">
                                                                        <svg class="icon-20" width="20"
                                                                            viewBox="0 0 24 24" fill="none"
                                                                            xmlns="http://www.w3.org/2000/svg">
                                                                            <path opacity="0.4"
                                                                                d="M16.3405 1.99976H7.67049C4.28049 1.99976 2.00049 4.37976 2.00049 7.91976V16.0898C2.00049 19.6198 4.28049 21.9998 7.67049 21.9998H16.3405C19.7305 21.9998 22.0005 19.6198 22.0005 16.0898V7.91976C22.0005 4.37976 19.7305 1.99976 16.3405 1.99976Z"
                                                                                fill="currentColor"></path>
                                                                            <path
                                                                                d="M10.8134 15.248C10.5894 15.248 10.3654 15.163 10.1944 14.992L7.82144 12.619C7.47944 12.277 7.47944 11.723 7.82144 11.382C8.16344 11.04 8.71644 11.039 9.05844 11.381L10.8134 13.136L14.9414 9.00796C15.2834 8.66596 15.8364 8.66596 16.1784 9.00796C16.5204 9.34996 16.5204 9.90396 16.1784 10.246L11.4324 14.992C11.2614 15.163 11.0374 15.248 10.8134 15.248Z"
                                                                                fill="currentColor"></path>
                                                                        </svg>
                                                                    </a>
                                                                    <a class="mx-1 btn btn-sm btn-danger d-flex align-items-center justify-content-center"
                                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                                        title="ELIMINAR"
                                                                        wire:click="eliminarVista({{ $lista->id }})">
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
                                                                    </a>
                                                                @endif
                                                                @livewire('operativos.informacion-cliente', ['idoperativo' => $lista->id], key($lista->id))
                                                            </td>
                                                        @endif
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr tyle="width: 100%">
                        <td>
                            <div class="card" style="background-color: rgba(202, 199, 199, 0.521);">
                                <div class="card-header">
                                    <h3 class="card-title">Reporte de pagos realizados:</h3>
                                    <div class="card-options d-flex">
                                        <a class="mr-2 btn btn-success d-flex align-items-center"
                                            data-bs-toggle="tooltip" data-bs-placement="top" title="Cobrar"
                                            wire:click="$set('crear',true)">
                                            <i class="fas fa-cash-register me-1"></i> Cobrar
                                        </a>
                                        <a class="btn btn-warning d-flex align-items-center" data-bs-toggle="tooltip"
                                            data-bs-placement="top" title="Imprimir"
                                            wire:click="$set('confirmarimprimir',true)">
                                            <i class="fas fa-print me-1"></i> Imprimir
                                        </a>
                                    </div>
                                </div>
                                <div class="card-body" style="height: 25vh; overflow-y:scroll;">
                                    @livewire('pagos.lista-pagos', ['idoperativo' => $operativo->id])
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <x-modal wire:model='editarTratamientoBool'>
        <div class="px-6 py-4">
            <div class="text-lg font-medium text-gray-900">
                Ingrese nuevo monto a cobrar:
            </div>
            <div>
                <input type="text" wire:model='costo'>
            </div>
        </div>
        <div class="flex flex-row justify-end px-1 py-1 text-right bg-gray-100">
            <label for="" class="mr-2 btn btn-warning"
                wire:click="$set('editarTratamientoBool',false)">Cancelar</label>
            <label type="submit" class="btn btn-success" style="background: green;"
                wire:click="editarTaratamiento">Editar</label>
        </div>
    </x-modal>
    <x-modal wire:model='confirmarimprimir'>
        <div class="px-6 py-4">
            <div class="text-lg font-medium text-gray-900">
                Seleccionar cosmetóloga encargada:
            </div>
            <div class="mt-2 form-group">
                <select name="type" class="selectpicker form-control" data-style="py-0" wire:model="elegido">
                    <option value="">Selecciona un personal</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            @if ($elegido != '')
                <div class="flex flex-row justify-end px-6 py-4 text-right bg-gray-100">
                    <button type="submit" class="btn btn-success" wire:click="imprimir"> <i
                            class="fas fa-print me-1"></i>Imprimir</button>
                </div>
            @endif

        </div>
    </x-modal>

    <x-modal wire:model="eliminart">
        <div class="px-6 py-4">
            <div class="text-lg font-medium text-gray-900">
                ¿Desea eliminar este tratamiento?
            </div>
        </div>
        <div class="flex flex-row justify-end px-1 py-1 text-right bg-gray-100">
            <label type="submit" class="btn btn-danger" wire:click="eliminarTratamiento">Si eliminar</label>
        </div>
    </x-modal>


    <x-modal wire:model="crear">
        <div class="modal-content">
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6 col-lg-4">
                        <label for="tratamientoCobrar" class="form-label">Seleccione Tratamiento a Cobrar:</label>
                        <select name="" id="tratamientoCobrar" class="form-select"
                            wire:model="tratamientoCobrar">
                            <option value="">Seleccione tratamiento</option>
                            @foreach ($tratamientos as $item)
                                <option value="{{ $item->id }}">{{ $item->nombretratamiento }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Monto a pagar -->
                    <div class="col-md-6 col-lg-4">
                        <label class="form-label" for="cantidadpago">Monto a Pagar:</label>
                        <input type="text" class="form-control" id="cantidadpago" wire:model="cantidadpago"
                            pattern="^\d+(\,\d{1,2})?$|^\d+(\.\d{1,2})?$" placeholder="Ejemplo: 123.45 o 123,45"
                            title="Solo se permiten números con un punto o coma para decimales">
                    </div>


                    <!-- Método de pago -->
                    <div class="col-md-6 col-lg-4">
                        <label for="mododepago" class="form-label">Método de Pago:</label>
                        <select name="mododepago" class="form-select" wire:model="mododepago">
                            <option value="Efectivo">Efectivo</option>
                            <option value="Qr">QR</option>
                        </select>
                    </div>

                    <!-- Selección de cosmetóloga -->
                    <div class="col-md-6 col-lg-4">
                        <label for="elegido" class="form-label">Cosmetóloga:</label>
                        <select name="elegido" class="form-select" wire:model.defer="elegido">
                            <option value="">Seleccionar operario</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Observación -->
                <div class="mt-3 form-group">
                    <label for="comentarioobser" class="form-label">Observación:</label>
                    <input type="text" class="form-control" wire:model='comentarioobser'>
                </div>

                <!-- Registrado por -->
                <div class="mt-2 form-group">
                    <label for="registradoPor" class="form-label">Registrado por:</label>
                    <input type="text" class="form-control" id="registradoPor" disabled
                        value="{{ Auth::user()->name }}">
                </div>
            </div>
            @if ($cantidadpago != '')
                <div class="flex flex-row justify-end px-1 py-1 text-right bg-gray-100">
                    <button type="submit" class="btn btn-success" wire:click="guardartodo">Registrar pago</button>
                </div>
            @endif

        </div>
    </x-modal>

    <x-modal wire:model="agreagar">
        <label class="form-label" for="">Selecciona tratamientos para agregar:</label>
        <div class="card">
            <div class="card-body">
                <div class="mb-3">
                    @if ($haycuota > 0)
                        <label for="">Seleccione personal con enganche:</label>

                        <!-- Contenedor para organizar select y botón -->
                        <div class="d-flex">
                            <!-- Select (75% del ancho) -->
                            <select name="" id="" wire:model='personalenganche'
                                class="form-select w-70">
                                <option value="">Nadie</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>

                            <!-- Botón (25% del ancho) -->
                            <label style="background-color: green;" type="submit"
                                class="text-center btn btn-success w-30" wire:click="agregartratamientos">
                                Agregar tratamiento
                            </label>
                        </div>
                    @else
                        <label type="submit" class="text-center btn btn-success w-30"
                            wire:click="agregartratamientos">
                            Agregar tratamiento
                        </label>

                    @endif

                </div>

                <div class="mt2">
                    <input type="text" class="form-control" id="exampleInputDisabled1"
                        wire:model="busquedatratamiento" placeholder="Buscar tratamiento...">
                </div>
                <div style="height: 55vh; overflow-y:scroll;">
                    @foreach ($mistratamientospara as $tratamiento)
                        <div>
                            <div>
                                <input type="checkbox"
                                    wire:model.defer="tratamientosSeleccionadosnuevos.{{ $tratamiento->id }}"
                                    value="{{ $tratamiento->id }}">
                                <label
                                    for="">{{ $tratamiento->nombre }}({{ $tratamiento->costo . 'Bs.' }})</label>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>
        </div>
    </x-modal>
</div>
