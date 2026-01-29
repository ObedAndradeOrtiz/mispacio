<div>
    <a class="mr-2 btn btn-sm btn-icon btn-warning" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit"
        data-original-title="Edit" wire:click="$set('editar',true)">
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
    
    <x-modal wire:model.defer="editar">
        <div class="px-6 py-4">
            <div class="text-lg font-medium text-gray-900">
                Editar gasto
            </div>
            <div class="mt-4 text-sm text-gray-600">
                <form>
                    <div class="form-group">
                        <label class="form-label" for="">Monto de pago:</label>
                        <input type="number" class="form-control" id="exampleInputDisabled1"
                            wire:model.defer="registro.cantidad">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="">Modo de pago:</label>
                        <select class="mb-3 shadow-none form-select form-select-smmt-5" wire:model="registro.modo">
                                <option value="QR">Qr</option>
                                <option value="Efectivo">Efectivo</option>
                        </select>
                        <label class="form-label" for="">Tipo de egreso: </label>
                        <select class="mb-3 shadow-none form-select form-select-smmt-5" wire:model="registro.tipo">
                            <option value="AGUA POTABLE">AGUA POTABLE</option>
                            <option value="ALQUILER">ALQUILER</option>
                            <option value="GAS">GAS</option>
                            <option value="IMPUESTOS">IMPUESTOS</option>
                            <option value="LUZ ELECTRICA">LUZ ELECTRICA</option>
                            <option value="INTERNET/TEL">INTERNET/TEL</option>
                            <option value="Dra. PAOLA">Dra. PAOLA</option>
                            <option value="Sr. ALEXANDRE">Sr. ALEXANDRE</option>
                            <option value="ADELANTO AL PERSONAL">ADELANTO AL PERSONAL</option>
                            <option value="MATERIAL CAFETERIA">MATERIAL CAFETERIA</option>
                            <option value="MATERIAL ESCRITORIO">MATERIAL ESCRITORIO</option>
                            <option value="MATERIAL LIMPIEZA">MATERIAL LIMPIEZA</option>
                            <option value="MATERIAL DE PROCEDIMIENTOS">MATERIAL DE PROCEDIMIENTOS</option>
                            <option value="MATERIAL PARA EVENTOS">MATERIAL PARA EVENTOS</option>
                            <option value="MATERIAL PARA MANTENIMIENTO">MATERIAL PARA MANTENIMIENTO</option>
                            <option value="MANTENIMIENTO DE EQUIPOS">MANTENIMIENTO DE EQUIPOS</option>
                            <option value="MANTENIMIENTO DE SUCURSAL">MANTENIMIENTO DE SUCURSAL</option>
                            <option value="MERIENDAS">MERIENDAS</option>
                            <option value="PUBLICIDAD">PUBLICIDAD</option>
                            <option value="SERVICIOS PROFESIONALES">SERVICIOS PROFESIONALES</option>
                            <option value="TRAMITES">TRAMITES</option>
                            <option value="TRANSPORTE">TRANSPORTE</option>
                            <option value="OTRO">OTRO</option>
                        </select>
                    </div>
                </form>
            </div>
        </div>

        <div class="flex flex-row justify-end px-6 py-4 text-right bg-gray-100">
            <label type="submit" class="btn btn-success" wire:click="guardartodo">Guardar</label>
        </div>
    </x-modal>
</div>
