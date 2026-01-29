<div>

    <div class="table-responsive">
        <table class="table mb-0 table-striped text-nowrap">
            <thead>
                <th>CANTIDAD</th>
                <th>FECHA</th>
                <th>MODO</th>
                <th>OBSERVACION</th>
                <th>COSMETOLOGA</th>
                <th>RECEPCION</th>
                <th>ACCION</th>
            </thead>
            <tbody>
                @foreach ($pagos as $pago)
                    <tr>
                        <td>
                            {{ $pago->monto }}
                        </td>
                        <td>
                            {{ $pago->fecha }}
                        </td>
                        <td>
                            {{ $pago->modo }}
                        </td>
                        <td style="max-width: 100px; white-space: normal; word-wrap: break-word;">
                            {{ $pago->comentario }}
                        </td>

                        <td>
                            {{ $pago->cosmetologa }}
                        </td>
                        <td>
                            {{ $pago->responsable }}
                        </td>
                        <td>
                            <div class="d-flex">
                                <a class="mr-1 btn btn-sm btn-icon btn-warning d-flex align-items-center"
                                    data-bs-toggle="tooltip" data-bs-placement="top" title="EDITAR"
                                    data-original-title="Edit" wire:click="editarpago({{ $pago->id }})">

                                    <svg class="icon-20" width="20" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path opacity="0.4"
                                            d="M19.9927 18.9534H14.2984C13.7429 18.9534 13.291 19.4124 13.291 19.9767C13.291 20.5422 13.7429 21.0001 14.2984 21.0001H19.9927C20.5483 21.0001 21.0001 20.5422 21.0001 19.9767C21.0001 19.4124 20.5483 18.9534 19.9927 18.9534Z"
                                            fill="currentColor"></path>
                                        <path
                                            d="M10.309 6.90385L15.7049 11.2639C15.835 11.3682 15.8573 11.5596 15.7557 11.6929L9.35874 20.0282C8.95662 20.5431 8.36402 20.8344 7.72908 20.8452L4.23696 20.8882C4.05071 20.8903 3.88775 20.7613 3.84542 20.5764L3.05175 17.1258C2.91419 16.4915 3.05175 15.8358 3.45388 15.3306L9.88256 6.95545C9.98627 6.82108 10.1778 6.79743 10.309 6.90385Z"
                                            fill="currentColor"></path>
                                        <path opacity="0.4"
                                            d="M18.1208 8.66544L17.0806 9.96401C16.9758 10.0962 16.7874 10.1177 16.6573 10.0124C15.3927 8.98901 12.1545 6.36285 11.2561 5.63509C11.1249 5.52759 11.1069 5.33625 11.2127 5.20295L12.2159 3.95706C13.126 2.78534 14.7133 2.67784 15.9938 3.69906L17.4647 4.87078C18.0679 5.34377 18.47 5.96726 18.6076 6.62299C18.7663 7.3443 18.597 8.0527 18.1208 8.66544Z"
                                            fill="currentColor"></path>
                                    </svg>
                                </a>
                                @if (Auth::user()->rol == 'Administrador')
                                    <a class="mr-1 btn btn-sm btn-icon btn-danger d-flex align-items-center"
                                        data-bs-toggle="tooltip" data-bs-placement="top" title="ELIMINAR"
                                        data-original-title="Edit" wire:click="preeliminar({{ $pago->id }})">

                                        <svg class="icon-20" width="20" viewBox="0 0 24 24" fill="none"
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

                                <a class="mr-1 btn btn-sm btn-icon btn-primary d-flex align-items-center"
                                    data-bs-toggle="tooltip" data-bs-placement="top" title="IMPRIMIR"
                                    data-original-title="Edit" wire:click="imprimirpago({{ $pago->id }})">

                                    <i class="fa fa-print" aria-hidden="true" title="IMPRIMIR"
                                        style="color: white;"></i>
                                </a>

                            </div>
                        </td>
                    </tr>
                @endforeach
                </tr>
            </tbody>
        </table>
    </div>
    <x-modal wire:model="eliminarboton">
        <div class="px-6 py-4">
            <div class="text-lg font-medium text-gray-900">
                Estás seguro que deseas eliminar este pago?
            </div>

        </div>
        <div class="flex flex-row justify-end px-6 py-4 text-right bg-gray-100">
            <button type="submit" class="btn btn-danger" wire:click="eliminarPago">Sí,
                eliminar</button>
        </div>
    </x-modal>
    <x-modal wire:model="editar">
        <div class="px-6 py-4">
            <div class="text-lg font-medium text-gray-900">
                Edición de pago
            </div>
            <div class="mt-4">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form>
                    <div>
                        <label for="form-label">Seleccione el tratamiento:</label>
                        <select name="" id="" wire:model="tratamiento">
                            <option value="">Seleccione tratamiento</option>
                            @foreach ($tratamientos as $item)
                                <option value="{{ $item->id }}">{{ $item->nombretratamiento }}</option>
                            @endforeach
                        </select>
                    </div>
                    @if (Auth::user()->rol == 'Administrador')
                        <div class="form-group">
                            <label class="form-label" for="">Monto de pago:</label>
                            <input type="number" class="form-control" id="exampleInputDisabled1"
                                wire:model.defer="registro.monto">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="">Fecha de pago:</label>
                            <input type="date" class="form-control" id="exampleInputDisabled1"
                                wire:model.defer="registro.fecha">

                        </div>
                    @else
                        <div class="form-group">
                            <label class="form-label" for="">Monto de pago:</label>
                            <input type="number" class="form-control" id="exampleInputDisabled1"
                                wire:model.defer="registro.monto" disabled>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="">Fecha de pago:</label>
                            <input type="date" class="form-control" id="exampleInputDisabled1"
                                wire:model.defer="registro.fecha" disabled>

                        </div>
                    @endif

                    <div class="form-group">
                        <label class="form-label" for="">Observación:</label>
                        <input type="text" class="form-control" id="exampleInputDisabled1"
                            wire:model.defer="registro.comentario">

                    </div>
                    <div class="form-group">
                        <label class="form-label" for="">Modo de pago:</label>
                        <select class="mb-3 shadow-none form-select form-select-smmt-5" wire:model="registro.modo">
                            <option value="Qr">Qr</option>
                            <option value="Efectivo">Efectivo</option>

                        </select>
                    </div>
                </form>
            </div>
        </div>

        <div class="flex flex-row justify-end px-6 py-4 text-right bg-gray-100">
            <button type="submit" style="background-color: green;" class="btn btn-success"
                wire:click="guardartodo">Guardar</button>
        </div>
    </x-modal>
</div>
