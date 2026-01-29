<div>
    <a class="mr-1 btn btn-sm btn-icon btn-success mr d-flex align-items-center" data-bs-toggle="tooltip"
        data-bs-placement="top" title="NUEVA CITA" data-original-title="Edit" wire:click="$set('crear',true)">
        <svg class="icon-20" width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
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


    <x-modal wire:model.defer="crear">
        <div class="px-6 py-4">
            <div class="text-lg font-medium text-gray-900">
                {{ date('Y-m-d') }}
                @if ($verificar)
                    <label style="color:firebrick"> Cita ya creada para hoy</label>
                    Asignar tratamientos a la cita de {{ $usuario->name }}
                @else
                    Nueva cita de: {{ $usuario->name }}
                @endif

            </div>
            <div class="mt-4 text-sm text-gray-600">
                <form>
                    <div class="form-group">
                        <label class="form-label" for="">Nombre de cliente: </label>
                        <input type="text" class="form-control" id="texto" oninput="convertirAMayusculas()"
                            disabled="" wire:model.defer="usuario.name">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="">Telefono:</label>
                        <input type="text" class="form-control" id="exampleInputDisabled1" disabled=""
                            wire:model.defer="usuario.telefono">
                    </div>

                    <div class="mt-2 form-group">
                        <label class="form-label" for="exampleInputdate">Fecha de cita:</label>
                        <input type="date" class="form-control" id="exampleInputdate" wire:model.defer="fechacita">
                    </div>
                    @error('fechacita')
                        <label style="color:firebrick"> Fecha requerida</label>
                        <br>
                    @enderror
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
                    {{-- <div class="mt-2 form-group">
                        <label class="form-label" for="">Encargado de cita:</label>
                        <select name="type" class="selectpicker form-control" data-style="py-0"
                            wire:model.defer="elegido">
                            <option>Seleccionar operario</option>
                            @foreach ($users as $user)
                                <option>{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div> --}}
                    <div class="mt-2 form-group">
                        <label class="form-label" for="">Seleccionar Paquete o tratamientos:</label>
                        <div style="display: flex; ">

                            <button type="button" class="boton btn btn-primary" wire:click="$set('botonpaquete',false)"
                                style="flex: 1;">Lista de
                                tratamientos</button>
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
                                <div class="">
                                    <input type="text" class="form-control" id="exampleInputDisabled1"
                                        wire:model="busquedatratamiento" placeholder="Buscar tratamiento...">
                                </div>
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
