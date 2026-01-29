<div>
    <div class="d-flex">
        <a class="mr-2 btn btn-sm btn-icon btn-warning" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit"
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
        @if ($usuario->estado == 'Activo')
            <a class="mr-2 btn btn-sm btn-icon btn-danger" data-bs-toggle="tooltip" data-bs-placement="top"
                title="Edit" data-original-title="Edit" wire:click="$emit('inactivarUser',{{ $usuario->id }})">
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
                data-original-title="Edit" wire:click="$emit('activarUser',{{ $usuario->id }})">
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
        <a class="mr-2 btn btn-sm btn-icon btn-primary d-flex align-items-center" data-bs-toggle="tooltip"
            data-bs-placement="top" title="Ver cliente" data-original-title="INFORMACIÓN DE USUARIOS"
            wire:click="$set('openuser',true)">

            <svg class="icon-20" width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path opacity="0.4" fill-rule="evenodd" clip-rule="evenodd"
                    d="M17.7366 6.04606C19.4439 7.36388 20.8976 9.29455 21.9415 11.7091C22.0195 11.8924 22.0195 12.1067 21.9415 12.2812C19.8537 17.1103 16.1366 20 12 20H11.9902C7.86341 20 4.14634 17.1103 2.05854 12.2812C1.98049 12.1067 1.98049 11.8924 2.05854 11.7091C4.14634 6.87903 7.86341 4 11.9902 4H12C14.0683 4 16.0293 4.71758 17.7366 6.04606ZM8.09756 12C8.09756 14.1333 9.8439 15.8691 12 15.8691C14.1463 15.8691 15.8927 14.1333 15.8927 12C15.8927 9.85697 14.1463 8.12121 12 8.12121C9.8439 8.12121 8.09756 9.85697 8.09756 12Z"
                    fill="currentColor"></path>
                <path
                    d="M14.4308 11.997C14.4308 13.3255 13.3381 14.4115 12.0015 14.4115C10.6552 14.4115 9.5625 13.3255 9.5625 11.997C9.5625 11.8321 9.58201 11.678 9.61128 11.5228H9.66006C10.743 11.5228 11.621 10.6695 11.6601 9.60184C11.7674 9.58342 11.8845 9.57275 12.0015 9.57275C13.3381 9.57275 14.4308 10.6588 14.4308 11.997Z"
                    fill="currentColor"></path>
            </svg>
            <span class="ms-1" style="font-size: 8px;"></span>
        </a>
    </div>


        <x-modal wire:model.defer="openArea">
            <div class="px-6 py-4">
                <div class="text-lg font-medium text-gray-900">
                    Editar usuario {{ $usuario->name }}
                </div>
                <div class="mt-4 text-sm text-gray-600">
                    <form>
                        <div class="form-group">
                            <label class="form-label" for="">Nombre de usuario</label>
                            <input type="text" class="form-control" id="exampleInputDisabled1"
                                wire:model.defer="usuario.name">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="">Cambiar Sucursal: </label>
                            <select name="type" class="selectpicker form-control" data-style="py-0"
                                wire:model.defer="usuario.sucursal">
                                <option>Seleccionar sucursal</option>
                                @foreach ($areas as $area)
                                    <option>{{ $area->area }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="">Telefono:</label>
                            <input type="text" class="form-control" id="exampleInputDisabled1"
                                wire:model.defer="usuario.telefono">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="">Sueldo:</label>
                            <input type="number" class="form-control" id="exampleInputDisabled1"
                                wire:model.defer="usuario.sueldo">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="">Email:</label>
                            <input type="text" class="form-control" id="exampleInputDisabled1"
                                wire:model.defer="usuario.email">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="">Rol de usuario: </label>

                            <select name="type" class="selectpicker form-control" data-style="py-0"
                                wire:model.defer="usuario.rol">
                                <option>Seleccionar rol</option>
                                @foreach ($roles as $rol)
                                    <option>{{ $rol->rol }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="exampleInputdate">Fecha de ingreso:</label>
                            <input type="date" class="form-control" id="exampleInputdate" value="2000-01-01"
                                wire:model="usuario.fechainicio">
                        </div>
                        <div class="form-group">

                            <label class="form-label" for="">Hora de inicio de turno:</label>
                            <input type="time" class="form-control" id="hora" name="hora"
                                wire:model.defer="usuario.horainicio">

                        </div>
                        <div class="form-group">

                            <label class="form-label" for="">Hora de fin de turno:</label>
                            <input type="time" class="form-control" id="hora" name="hora"
                                wire:model.defer="usuario.horafin">

                        </div>
                        <div class="form-group">

                            <label class="form-label" for="">Colocar nueva contraseña:</label>
                            <input type="text" class="form-control" wire:model="newPassword"
                                placeholder="Nueva contraseña">

                        </div>


                        <div class="form-group">
                            <label class="form-label" for="">Foto de perfil:</label>
                            <input class="form-control" type="file" wire:model="image">
                            <img class="mt-4" src="{{ asset('storage/' . $usuario->path) }}" alt="">
                            @if ($image)
                                @if ($image->getClientOriginalExtension() === 'jpg' || $image->getClientOriginalExtension() === 'png')
                                    <img class="mt-4" src="{{ $image->temporaryUrl() }}" alt=""
                                        style="max-height: 250px;">
                                @endif
                            @endif
                        </div>

                    </form>
                </div>
                <div>
                    {{-- @if ($usuario->firma == '')
                <a href="/firma/{{ $usuario->id}}" class="boton btn btn-warning">Crear contrato</a>
            @endif --}}
                </div>
            </div>

            <div class="flex flex-row justify-end px-6 py-4 text-right bg-gray-100">
                <label type="submit" class="btn btn-success" wire:click="guardartodo">Guardar</label>
            </div>
        </x-modal>
        <x-modal wire:model.defer="openuser">
            <div class="px-6 py-4">
                <div class="text-lg font-medium text-gray-900">
                    Información de usuario {{ $usuario->name }}
                </div>
                <div class="text-lg font-medium text-gray-900">
                    Sucursal de trabajo: {{ $usuario->sucursal }}
                </div>
                <div class="text-lg font-medium text-gray-900">
                    Horario de trabajo: {{ $usuario->horainicio . ' - ' . $usuario->horafin }}
                </div>
                <div class="text-lg font-medium text-gray-900">
                    Numero de usuario: {{ $usuario->telefono }}
                </div>
                <div class="text-lg font-medium text-gray-900">
                    Sueldo Mensual: {{ $usuario->sueldo }}
                </div>
                <div class="mt-4 text-sm text-gray-600">
                    <form>
                        @php
                            $gastoarea = DB::table('gastos')
                                ->where('tipo', 'SUELDO')
                                ->where('iduser', $usuario->id)
                                ->get();
                        @endphp

                        <div class="table-responsive">

                            <table id="user-list-table" class="table table-striped" role="grid"
                                data-bs-toggle="data-table">
                                <thead>
                                    <tr class="ligth">
                                        <th>NOMBRE</th>
                                        <th>FECHA DE PAGO</th>
                                        <th>CANTIDAD</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <style>
                                        td {
                                            max-width: 200px;
                                            overflow: hidden;
                                            text-overflow: ellipsis;
                                            white-space: nowrap;
                                        }
                                    </style>
                                    @foreach ($gastoarea as $item)
                                        <tr>
                                            <td>{{ $item->nameuser }}</td>
                                            <td>{{ $item->fechainicio }}</td>
                                            <td>{{ $item->cantidad }}</td>


                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>



                    </form>
                </div>
                <div>
                </div>
            </div>


        </x-modal>

</div>
