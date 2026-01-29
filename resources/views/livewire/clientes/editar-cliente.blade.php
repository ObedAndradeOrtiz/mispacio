<div>
    <div class="flex flex-wrap">
        <a class="mt-1 mr-1 btn btn-sm btn-icon btn-success d-flex align-items-center" data-bs-toggle="tooltip"
            data-bs-placement="top" title="Ver cliente" data-original-title="Ver cliente"
            wire:click="$set('openArea5',true)">

            <svg class="icon-15" width="15" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path opacity="0.4" fill-rule="evenodd" clip-rule="evenodd"
                    d="M17.7366 6.04606C19.4439 7.36388 20.8976 9.29455 21.9415 11.7091C22.0195 11.8924 22.0195 12.1067 21.9415 12.2812C19.8537 17.1103 16.1366 20 12 20H11.9902C7.86341 20 4.14634 17.1103 2.05854 12.2812C1.98049 12.1067 1.98049 11.8924 2.05854 11.7091C4.14634 6.87903 7.86341 4 11.9902 4H12C14.0683 4 16.0293 4.71758 17.7366 6.04606ZM8.09756 12C8.09756 14.1333 9.8439 15.8691 12 15.8691C14.1463 15.8691 15.8927 14.1333 15.8927 12C15.8927 9.85697 14.1463 8.12121 12 8.12121C9.8439 8.12121 8.09756 9.85697 8.09756 12Z"
                    fill="currentColor"></path>
                <path
                    d="M14.4308 11.997C14.4308 13.3255 13.3381 14.4115 12.0015 14.4115C10.6552 14.4115 9.5625 13.3255 9.5625 11.997C9.5625 11.8321 9.58201 11.678 9.61128 11.5228H9.66006C10.743 11.5228 11.621 10.6695 11.6601 9.60184C11.7674 9.58342 11.8845 9.57275 12.0015 9.57275C13.3381 9.57275 14.4308 10.6588 14.4308 11.997Z"
                    fill="currentColor"></path>
            </svg><span class="ms-1" style="font-size: 8px;"><span>
        </a>
        <a class="mt-1 mr-1 btn btn-sm btn-icon btn-warning d-flex align-items-center" data-bs-toggle="tooltip"
            data-bs-placement="top" title="EDITAR" data-original-title="Edit" wire:click="$set('openArea',true)">

            <svg class="icon-15" width="15" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M11.4925 2.78906H7.75349C4.67849 2.78906 2.75049 4.96606 2.75049 8.04806V16.3621C2.75049 19.4441 4.66949 21.6211 7.75349 21.6211H16.5775C19.6625 21.6211 21.5815 19.4441 21.5815 16.3621V12.3341"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M8.82812 10.921L16.3011 3.44799C17.2321 2.51799 18.7411 2.51799 19.6721 3.44799L20.8891 4.66499C21.8201 5.59599 21.8201 7.10599 20.8891 8.03599L13.3801 15.545C12.9731 15.952 12.4211 16.181 11.8451 16.181H8.09912L8.19312 12.401C8.20712 11.845 8.43412 11.315 8.82812 10.921Z"
                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                <path d="M15.1655 4.60254L19.7315 9.16854" stroke="currentColor" stroke-width="1.5"
                    stroke-linecap="round" stroke-linejoin="round"></path>
            </svg>
            <span class="ms-1" style="font-size: 8px;"></span>

        </a>
        @if ($notiene == false)
            <a class="mt-1 mr-1 btn btn-sm btn-icon btn-primary d-flex align-items-center" data-bs-toggle="tooltip"
                data-bs-placement="top" title="NUEVA CITA" data-original-title="Nueva cita"
                wire:click="$set('openArea6',true)">

                <svg class="icon-15" width="15" viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path opacity="0.4"
                        d="M11.7761 21.8374C9.49311 20.4273 7.37081 18.7645 5.44807 16.8796C4.09069 15.5338 3.05404 13.8905 2.41735 12.0753C1.27971 8.53523 2.60399 4.48948 6.30129 3.2884C8.2528 2.67553 10.3752 3.05175 12.0072 4.29983C13.6398 3.05315 15.7616 2.67705 17.7132 3.2884C21.4105 4.48948 22.7436 8.53523 21.606 12.0753C20.9745 13.8888 19.944 15.5319 18.5931 16.8796C16.6686 18.7625 14.5465 20.4251 12.265 21.8374L12.0161 22L11.7761 21.8374Z"
                        fill="currentColor"></path>
                    <path
                        d="M12.0109 22.0001L11.776 21.8375C9.49013 20.4275 7.36487 18.7648 5.43902 16.8797C4.0752 15.5357 3.03238 13.8923 2.39052 12.0754C1.26177 8.53532 2.58605 4.48957 6.28335 3.28849C8.23486 2.67562 10.3853 3.05213 12.0109 4.31067V22.0001Z"
                        fill="currentColor"></path>
                    <path
                        d="M18.2304 9.99922C18.0296 9.98629 17.8425 9.8859 17.7131 9.72157C17.5836 9.55723 17.5232 9.3434 17.5459 9.13016C17.5677 8.4278 17.168 7.78851 16.5517 7.53977C16.1609 7.43309 15.9243 7.00987 16.022 6.59249C16.1148 6.18182 16.4993 5.92647 16.8858 6.0189C16.9346 6.027 16.9816 6.04468 17.0244 6.07105C18.2601 6.54658 19.0601 7.82641 18.9965 9.22576C18.9944 9.43785 18.9117 9.63998 18.7673 9.78581C18.6229 9.93164 18.4291 10.0087 18.2304 9.99922Z"
                        fill="currentColor"></path>
                </svg>
                <span class="ms-1" style="font-size: 8px;"></span>

            </a>
        @endif

    </div>

    <x-modal wire:model.defer="openArea">
        <div class="px-6 py-4">
            <div class="text-lg font-medium text-gray-900">
                Editar Cliente {{ $usuario->name }}
            </div>
            <div class="mt-4 text-sm text-gray-600">
                <form>
                    <div class="form-group">
                        <label class="form-label" for="">Nombre de cliente: </label>
                        <input type="text" class="form-control" id="texto" oninput="convertirAMayusculas()"
                            wire:model.defer="usuario.name">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="">Telefono:</label>
                        <input type="text" class="form-control" id="exampleInputDisabled1"
                            wire:model.defer="usuario.telefono">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="">Edad:</label>
                        <input type="text" class="form-control" id="exampleInputDisabled1"
                            wire:model.defer="usuario.edad">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="">CI:</label>
                        <input type="number" class="form-control" id="exampleInputDisabled1" wire:model.defer="ci">
                    </div>
                </form>
            </div>
        </div>
        <div class="flex flex-row justify-end px-6 py-4 text-right bg-gray-100">
            <label type="submit" class="btn btn-success" wire:click="guardartodo">Guardar</label>
        </div>
    </x-modal>
    <x-modal wire:model.defer="openArea5">
        <div class="px-6 py-4">
            <div class="text-lg font-medium text-gray-900">
                Información de {{ $usuario->name }}
            </div>
            <div class="mt-4 text-sm text-gray-600">
                <form>
                    <div class="form-group">
                        <label class="form-label" for="">Nombre de cliente: </label>
                        <input type="text" class="form-control" id="exampleInputDisabled1" disabled=""
                            wire:model.defer="usuario.name">
                    </div>
                    <div class="px-4 card-body">
                        <div class="table-responsive">

                            <table id="user-list-table" class="table table-striped" role="grid"
                                data-bs-toggle="data-table">
                                <thead>
                                    <tr class="ligth">
                                        <th>Tratamiento</th>
                                        <th>Fecha de tratamiendo</th>
                                        <th>Estado</th>
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
                                    @php
                                        $mistratamientos = DB::table('historial_clientes')
                                            ->where('idcliente', $usuario->id)
                                            ->get();
                                    @endphp
                                    @foreach ($mistratamientos as $item)
                                        <tr>
                                            @php
                                                $collection = DB::table('tratamientos')
                                                    ->where('id', $item->idtratamiento)
                                                    ->get();
                                            @endphp
                                            @foreach ($collection as $coll)
                                                <td>{{ $coll->nombre }}</td>
                                            @endforeach

                                            <td>{{ $item->fecha }}</td>
                                            <td>{{ $item->estado }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <style>
                                .container-but {
                                    display: flex;
                                    justify-content: center;
                                    align-items: center;
                                }
                            </style>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </x-modal>
    <x-modal wire:model.defer="openArea6">
        <div class="px-6 py-4">
            <div class="text-lg font-medium text-gray-900">

                @if ($verificar)
                    <label style="color:firebrick"> Cita ya creada para hoy</label>
                    <br>
                    Asignar tratamientos a la cita de {{ $usuario->name }}
                @else
                    Nueva cita de: {{ $usuario->name }}
                @endif

            </div>
            <div class="mt-4 text-sm text-gray-600">
                <form>
                    @if ($verificar)
                        <div class="mt-2 form-group">
                            <div class="mt-2 form-group">
                                <label class="form-label" for="exampleInputdate">Fecha de cita:</label>
                                <input type="date" class="form-control" id="exampleInputdate"
                                    wire:model="fechacita">
                            </div>
                            <label class="form-label" for="">Seleccionar Paquete o tratamientos:</label>
                            {{-- <div style="display: flex; ">

                                <button type="button" class="boton btn btn-primary"
                                    wire:click="$set('botonpaquete',false)" style="flex: 1;">Lista de
                                    tratamientos</button>

                            </div> --}}
                            <div class="">
                                <input type="text" class="form-control" id="exampleInputDisabled1"
                                    wire:model="busquedatratamiento" placeholder="Buscar tratamiento...">
                            </div>
                            @if ($botonpaquete)
                                <div>
                                    <select name="type" class="selectpicker form-control" data-style="py-0"
                                        wire:model.defer="elegidopaquete">
                                        <option>Ninguno</option>
                                        @foreach ($paquetes as $paquete)
                                            <option value="{{ $paquete->id }}">{{ $paquete->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @else
                                <div class="form-group" style="margin-bottom: 35px;">
                                    <label class="form-label" for="">Selecciona tratamientos:</label>
                                    @foreach ($tratamientos as $tratamiento)
                                        <div>
                                            <div>
                                                <input type="checkbox"
                                                    wire:model.defer="tratamientosSeleccionados.{{ $tratamiento->id }}"
                                                    value="{{ $tratamiento->id }}">
                                                <label
                                                    for="">{{ $tratamiento->nombre }}({{ $tratamiento->costo . 'Bs.' }})</label>
                                            </div>
                                        </div>
                                    @endforeach
                                    @error('tratamientosSeleccionados')
                                        <label style="color:firebrick"> Tratamiento requerido</label>
                                        <br>
                                    @enderror
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="form-group">
                            <label class="form-label" for="">Sucursal perteneciente:</label>
                            <select name="type" class="selectpicker form-control" data-style="py-0"
                                wire:model.defer="empresaseleccionada">
                                <option>Seleccionar sucursal</option>
                                @foreach ($empresas as $empresa)
                                    <option>{{ $empresa->area }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('empresaseleccionada')
                            <label style="color:firebrick"> No selecciono ningun area</label>
                            <br>
                        @enderror
                        <div class="form-group">
                            <label class="form-label" for="">Nombre de cliente: </label>
                            <input type="text" class="form-control" id="texto"
                                oninput="convertirAMayusculas()" disabled="" wire:model.defer="usuario.name">
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="">Telefono:</label>
                            <input type="text" class="form-control" id="exampleInputDisabled1" disabled=""
                                wire:model.defer="usuario.telefono">
                        </div>
                        <div class="mt-2 form-group">
                            <label class="form-label" for="exampleInputdate">Fecha de cita:</label>
                            <input type="date" class="form-control" id="exampleInputdate" wire:model="fechacita">
                        </div>
                        @error('fechacita')
                            <label style="color:firebrick"> Fecha requerida</label>
                            <br>
                        @enderror
                        <div class="mt-2 form-group">
                            <label class="form-label">Hora de cita:</label>
                            <div class="d-flex">
                                <select name="type" class="selectpicker form-control" data-style="py-0"
                                    wire:model.defer="hora">
                                    <option>Seleccionar hora</option>
                                    <option>00</option>
                                    <option>1</option>
                                    <option>2</option>
                                    <option>3</option>
                                    <option>4</option>
                                    <option>5</option>
                                    <option>6</option>
                                    <option>7</option>
                                    <option>8</option>
                                    <option>9</option>
                                    <option>10</option>
                                    <option>11</option>
                                    <option>12</option>
                                    <option>13</option>
                                    <option>14</option>
                                    <option>15</option>
                                    <option>16</option>
                                    <option>17</option>
                                    <option>18</option>
                                    <option>19</option>
                                    <option>20</option>
                                    <option>21</option>
                                    <option>22</option>
                                    <option>23</option>
                                </select>
                                <select name="type" class="selectpicker form-control" data-style="py-0"
                                    wire:model.defer="minuto">
                                    <option>Seleccionar minuto</option>
                                    <option>00</option>
                                    <option>15</option>
                                    <option>30</option>
                                    <option>45</option>
                                </select>
                            </div>
                        </div>
                        @error('hora')
                            <label style="color:firebrick"> Hora requerido</label>
                            <br>
                        @enderror
                        @error('minuto')
                            <label style="color:firebrick"> Minuto requerido</label>
                            <br>
                        @enderror

                        <div class="mt-2 form-group">
                            <label class="form-label" for="">Seleccionar Paquete o tratamientos:</label>
                            <div class="">
                                <input type="text" class="form-control" id="exampleInputDisabled1"
                                    wire:model="busquedatratamiento" placeholder="Buscar tratamiento...">
                            </div>
                            @if ($botonpaquete)
                                <div>
                                    <select name="type" class="selectpicker form-control" data-style="py-0"
                                        wire:model.defer="elegidopaquete">
                                        <option>Ninguno</option>
                                        @foreach ($paquetes as $paquete)
                                            <option value="{{ $paquete->id }}">{{ $paquete->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @else
                                <div class="form-group" style="margin-bottom: 35px;">
                                    <label class="form-label" for="">Selecciona tratamientos:</label>
                                    @foreach ($tratamientos as $tratamiento)
                                        <div>
                                            <div>
                                                <input type="checkbox"
                                                    wire:model.defer="tratamientosSeleccionados.{{ $tratamiento->id }}"
                                                    value="{{ $tratamiento->id }}">
                                                <label
                                                    for="">{{ $tratamiento->nombre }}({{ $tratamiento->costo . 'Bs.' }})</label>
                                            </div>
                                        </div>
                                    @endforeach
                                    @error('tratamientosSeleccionados')
                                        <label style="color:firebrick"> Tratamiento requerido</label>
                                        <br>
                                    @enderror
                                </div>
                            @endif
                        </div>
                    @endif

                </form>
            </div>
        </div>
        <div class="flex flex-row justify-end px-6 py-4 text-right bg-gray-100">
            <label type="submit" class="btn btn-success" wire:click="guardarCita" wire:loading.remove
                wire:target="guardarCita">Guardar Cita</label>
            <span class="" wire:loading wire:target="guardartodo">Guardando...</span>
        </div>
    </x-modal>
</div>
