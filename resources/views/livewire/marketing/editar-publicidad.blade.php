<div>
    <a class="ml-1 btn btn-sm btn-icon btn-warning d-flex align-items-center" data-bs-toggle="tooltip"
        data-bs-placement="top" title="EDITAR" data-original-title="Edit" wire:click="$set('editar',true)">

        <svg class="icon-24" width="15" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path
                d="M11.4925 2.78906H7.75349C4.67849 2.78906 2.75049 4.96606 2.75049 8.04806V16.3621C2.75049 19.4441 4.66949 21.6211 7.75349 21.6211H16.5775C19.6625 21.6211 21.5815 19.4441 21.5815 16.3621V12.3341"
                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
            <path fill-rule="evenodd" clip-rule="evenodd"
                d="M8.82812 10.921L16.3011 3.44799C17.2321 2.51799 18.7411 2.51799 19.6721 3.44799L20.8891 4.66499C21.8201 5.59599 21.8201 7.10599 20.8891 8.03599L13.3801 15.545C12.9731 15.952 12.4211 16.181 11.8451 16.181H8.09912L8.19312 12.401C8.20712 11.845 8.43412 11.315 8.82812 10.921Z"
                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
            <path d="M15.1655 4.60254L19.7315 9.16854" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                stroke-linejoin="round"></path>
        </svg>
        {{-- <span class="ms-1" style="font-size: 8px;">EDITAR</span> --}}

    </a>
    <x-modal wire:model.defer="editar">
        <div class="px-6 py-4">
            <div class="text-lg font-medium text-gray-900">
                EDITAR PUBLICIDAD DE: {{ $publicidad->nombrecampana }}
            </div>
            <div class="mt-4 text-sm text-gray-600">
                <form>
                    <div class="mt-2 form-group d-flex">
                        <div>
                            @if ($publicidad->frecuencia == 'frec4')
                                <label for="">FECHA DE EJECUCION</label>
                                <input type="date" wire:model="publicidad.fecharango">
                            @endif

                            <label class="form-label" for="">FECHA DE INICIO:</label>
                            <input type="date" style="font-size: 0.7vw;" wire:model="publicidad.fechainicio">
                            @if ($publicidad->frecuencia == 'frec4')
                                <label class="form-label" for="">FECHA FINAL:</label>
                                <input type="date" style="font-size: 0.7vw;" wire:model="publicidad.fechafin">
                            @endif
                        </div>
                    </div>
                    <div class="mt-2 form-group d-flex">
                        <div>
                            <label class="form-label" for="hora">HORA:</label>
                            <input type="time" id="hora" style="font-size: 0.7vw;" wire:model="publicidad.hora">
                        </div>
                    </div>

                    <div>
                        <label for="">SELECCIONAR ESTADO: </label>
                        <select name="" id="" wire:model="publicidad.estado">
                            <option value="Activo">Activo</option>
                            <option value="Inactivo">Inactivo</option>
                        </select>
                    </div>
                </form>
            </div>
        </div>
        <div class="flex flex-row justify-end px-6 py-4 text-right bg-gray-100">
            <label type="submit" class="btn btn-success" wire:click="guardar">Guardar</label>
        </div>
    </x-modal>

</div>
