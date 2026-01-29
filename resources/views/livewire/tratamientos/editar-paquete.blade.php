<div>
    <div>

        <a class="btn btn-sm btn-icon btn-warning mr-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit"
            data-original-title="Edit" wire:click="$set('openArea',true)">
            <span class="btn-inner">
                <svg class="icon-20" width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M11.4925 2.78906H7.75349C4.67849 2.78906 2.75049 4.96606 2.75049 8.04806V16.3621C2.75049 19.4441 4.66949 21.6211 7.75349 21.6211H16.5775C19.6625 21.6211 21.5815 19.4441 21.5815 16.3621V12.3341"
                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M8.82812 10.921L16.3011 3.44799C17.2321 2.51799 18.7411 2.51799 19.6721 3.44799L20.8891 4.66499C21.8201 5.59599 21.8201 7.10599 20.8891 8.03599L13.3801 15.545C12.9731 15.952 12.4211 16.181 11.8451 16.181H8.09912L8.19312 12.401C8.20712 11.845 8.43412 11.315 8.82812 10.921Z"
                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    <path d="M15.1655 4.60254L19.7315 9.16854" stroke="currentColor" stroke-width="1.5"
                        stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
            </span>
        </a>
        @if ($paquete->estado == 'Activo')
            <a class="btn btn-sm btn-icon btn-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit"
                data-original-title="Edit" wire:click="inactivarpaquete">
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
        @else
            <a class="btn btn-sm btn-icon btn-success" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit"
                data-original-title="Edit" wire:click="activarpaquete">
                <span class="btn-inner">
                    <svg class="icon-20" width="20" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path opacity="0.4"
                            d="M8.23918 8.70907V7.36726C8.24934 5.37044 9.92597 3.73939 11.9989 3.73939C13.5841 3.73939 15.0067 4.72339 15.5249 6.19541C15.6976 6.65262 16.2057 6.89017 16.663 6.73213C16.8865 6.66156 17.0694 6.50253 17.171 6.29381C17.2727 6.08508 17.293 5.84654 17.2117 5.62787C16.4394 3.46208 14.3462 2 11.9786 2C8.95048 2 6.48126 4.41626 6.46094 7.38714V8.91084L8.23918 8.70907Z"
                            fill="currentColor"></path>
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M7.7688 8.71118H16.2312C18.5886 8.71118 20.5 10.5808 20.5 12.8867V17.8246C20.5 20.1305 18.5886 22.0001 16.2312 22.0001H7.7688C5.41136 22.0001 3.5 20.1305 3.5 17.8246V12.8867C3.5 10.5808 5.41136 8.71118 7.7688 8.71118ZM11.9949 17.3286C12.4928 17.3286 12.8891 16.941 12.8891 16.454V14.2474C12.8891 13.7703 12.4928 13.3827 11.9949 13.3827C11.5072 13.3827 11.1109 13.7703 11.1109 14.2474V16.454C11.1109 16.941 11.5072 17.3286 11.9949 17.3286Z"
                            fill="currentColor"></path>
                    </svg>
                </span>
            </a>
        @endif
        <x-modal wire:model.defer="openArea">
            <div class="px-6 py-4">
                <div class="text-lg font-medium text-gray-900">
                    Editar paquete
                </div>
                <div class="mt-4 text-sm text-gray-600">
                    <form>
                        <div class="form-group">
                            <label class="form-label" for="">Nombre de paquete:</label>
                            <input type="text" class="form-control" id="exampleInputDisabled1"
                                wire:model.defer="paquete.nombre">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="">Descripcion:</label>
                            <input type="text" class="form-control" id="exampleInputDisabled1"
                                wire:model.defer="paquete.descripcion">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="">Lista de tratamientos:</label>
                            @foreach ($tratamientosgenerales as $lista)
                                <div>
                                    <input type="checkbox" wire:click="toggleTratamiento({{ $lista->id }})" @if (in_array($lista->id, $tratamientosSeleccionados)) checked @endif>
                                    <label for="">{{ $lista->nombre }}</label>
                                </div>
                            @endforeach
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="">Costo:</label>
                            <input type="number" class="form-control" id="exampleInputDisabled1"
                                wire:model.defer="paquete.costo">
                        </div>

                    </form>
                </div>
            </div>

            <div class="flex flex-row justify-end px-6 py-4 bg-gray-100 text-right">
                <label type="submit" class="btn btn-success" wire:click="guardartodo">Guardar</label>
            </div>
        </x-modal>
    </div>

</div>
