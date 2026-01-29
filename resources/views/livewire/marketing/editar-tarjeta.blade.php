<div>
    <a class="btn btn-sm btn-icon btn-danger" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit"
        data-original-title="Edit" wire:click="$emit('inactivarTarjeta',{{ $tarjeta->id }})">
        <span class="btn-inner">
            <svg class="icon-20" width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path opacity="0.4"
                    d="M16.34 1.99976H7.67C4.28 1.99976 2 4.37976 2 7.91976V16.0898C2 19.6198 4.28 21.9998 7.67 21.9998H16.34C19.73 21.9998 22 19.6198 22 16.0898V7.91976C22 4.37976 19.73 1.99976 16.34 1.99976Z"
                    fill="currentColor"></path>
                <path
                    d="M15.0158 13.7703L13.2368 11.9923L15.0148 10.2143C15.3568 9.87326 15.3568 9.31826 15.0148 8.97726C14.6728 8.63326 14.1198 8.63426 13.7778 8.97626L11.9988 10.7543L10.2198 8.97426C9.87782 8.63226 9.32382 8.63426 8.98182 8.97426C8.64082 9.31626 8.64082 9.87126 8.98182 10.2123L10.7618 11.9923L8.98582 13.7673C8.64382 14.1093 8.64382 14.6643 8.98582 15.0043C9.15682 15.1763 9.37982 15.2613 9.60382 15.2613C9.82882 15.2613 10.0518 15.1763 10.2228 15.0053L11.9988 13.2293L13.7788 15.0083C13.9498 15.1793 14.1728 15.2643 14.3968 15.2643C14.6208 15.2643 14.8448 15.1783 15.0158 15.0083C15.3578 14.6663 15.3578 14.1123 15.0158 13.7703Z"
                    fill="currentColor"></path>
            </svg>
        </span>
    </a>
    @if ($tarjeta->motivo != '1')
        <a class="btn btn-sm btn-icon btn-primary" data-bs-toggle="tooltip" data-bs-placement="top"
            title="CONVERTIR A PRINCIPAL" data-original-title="Edit"
            wire:click="$emit('convTarjeta',{{ $tarjeta->id }})">
            <span class="btn-inner">
                CONV. A PRINCIPAL
            </span>
        </a>
    @else
        <a class="btn btn-sm btn-icon btn-success" data-bs-toggle="tooltip" data-bs-placement="top"
            title="CONVERTIR A PRINCIPAL" data-original-title="Edit">
            <span class="btn-inner">
                PRINCIPAL
            </span>
        </a>
    @endif
    <a class="mr-4 btn btn-sm btn-icon btn-warning" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit"
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
    <x-modal wire:model="openArea">
        <div class="px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="text-lg font-medium text-gray-900">
                    Editar Tarjeta {{ 'Res: ' . $tarje->responsable . ' Numero: ' . $tarje->numero }}
                </div>
            </div>
            <div class="mt-4 text-sm text-gray-600">
                <form>
                    <div class="form-group">
                        <label class="form-label" for="">Nombre de tarjeta: </label>
                        <input type="number" class="form-control" id="texto" oninput="convertirAMayusculas()"
                            wire:model.defer="tarje.nombretarjeta">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="">Numero de tarjeta: </label>
                        <input type="number" class="form-control" id="texto" oninput="convertirAMayusculas()"
                            wire:model.defer="tarje.numero">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="">Numero de cuenta: </label>
                        <input type="number" class="form-control" id="texto" oninput="convertirAMayusculas()"
                            wire:model.defer="tarje.nrocuenta">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="">Fecha vencimiento:</label>
                        <input type="date" class="form-control" id="exampleInputDisabled1"
                            wire:model.defer="tarje.fecha">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="">CVV:</label>
                        <input type="number" class="form-control" id="exampleInputDisabled1"
                            wire:model.defer="tarje.motivo">
                    </div>
                </form>
            </div>
        </div>
        <div class="flex flex-row justify-end px-6 py-4 text-right bg-gray-100">
            <label type="submit" class="btn btn-success" wire:click="guardartodo">Guardar</label>
        </div>
    </x-modal>



</div>
