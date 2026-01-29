<div>
    <button class="ml-4 btn btn-primary" wire:click="$set('crear',true)"> <svg class="icon-20" width="20"
            viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" clip-rule="evenodd"
                d="M9.5 12.5537C12.2546 12.5537 14.4626 10.3171 14.4626 7.52684C14.4626 4.73663 12.2546 2.5 9.5 2.5C6.74543 2.5 4.53737 4.73663 4.53737 7.52684C4.53737 10.3171 6.74543 12.5537 9.5 12.5537ZM9.5 15.0152C5.45422 15.0152 2 15.6621 2 18.2464C2 20.8298 5.4332 21.5 9.5 21.5C13.5448 21.5 17 20.8531 17 18.2687C17 15.6844 13.5668 15.0152 9.5 15.0152ZM19.8979 9.58786H21.101C21.5962 9.58786 22 9.99731 22 10.4995C22 11.0016 21.5962 11.4111 21.101 11.4111H19.8979V12.5884C19.8979 13.0906 19.4952 13.5 18.999 13.5C18.5038 13.5 18.1 13.0906 18.1 12.5884V11.4111H16.899C16.4027 11.4111 16 11.0016 16 10.4995C16 9.99731 16.4027 9.58786 16.899 9.58786H18.1V8.41162C18.1 7.90945 18.5038 7.5 18.999 7.5C19.4952 7.5 19.8979 7.90945 19.8979 8.41162V9.58786Z"
                fill="currentColor"></path>
        </svg></button>
    <x-modal wire:model.defer="crear">
        <div class="px-6 py-4">
            <div class="text-lg font-medium text-gray-900">
                Registrar usuario
            </div>
            <div class="mt-4 text-sm text-gray-600">
                <form>
                    <div class="form-group">
                        <label class="form-label" for="">Nombre de usario</label>
                        <input type="text" class="form-control" id="texto" oninput="convertirAMayusculas()"
                            wire:model.defer="name">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="">Nombre de Sucursal: </label>
                        <select name="type" class="selectpicker form-control" data-style="py-0"
                            wire:model.defer="sucursal">
                            <option>Seleccionar sucursal</option>
                            @foreach ($areas as $area)
                                <option>{{ $area->area }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="">Rol de usuario: </label>

                        <select name="type" class="selectpicker form-control" data-style="py-0"
                            wire:model.defer="rol">
                            <option>Seleccionar rol</option>
                            @foreach ($roles as $rol)
                                <option>{{ $rol->rol }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="exampleInputdate">Fecha de ingreso:</label>
                        <input type="date" class="form-control" id="exampleInputdate" value="2000-01-01"
                            wire:model="fechainicio">
                    </div>
                    <div class="form-group">

                        <label class="form-label" for="">Hora de inicio de turino:</label>
                        <input type="time" class="form-control" id="hora" name="hora"
                            wire:model.defer="horainicio">

                    </div>
                    <div class="form-group">

                        <label class="form-label" for="">Hora de fin de turno:</label>
                        <input type="time" class="form-control" id="hora" name="hora"
                            wire:model.defer="horafin">

                    </div>


                    <div class="form-group">

                        <label class="form-label" for="">Telefono principal:</label>
                        <input type="text" class="form-control" id="exampleInputDisabled1"
                            wire:model.defer="telefono">
                    </div>
                    <div class="form-group">

                        <label class="form-label" for="">Email:</label>
                        <input type="text" class="form-control" id="exampleInputDisabled1" wire:model.defer="email">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="">Sueldo:</label>
                        <input type="text" class="form-control" id="exampleInputDisabled1" wire:model.defer="sueldo">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="">Cuenta bancaria:</label>
                        <input type="text" class="form-control" id="exampleInputDisabled1" wire:model.defer="cuenta">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="">Contraseña:</label>
                        <input type="text" class="form-control" id="exampleInputDisabled1"
                            wire:model.defer="password">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="">Confirmar contraseña:</label>
                        <input type="text" class="form-control" id="exampleInputDisabled1"
                            wire:model.defer="password2">
                    </div>
                </form>
            </div>
        </div>
        <div class="flex flex-row justify-end px-6 py-4 text-right bg-gray-100">
            <label type="submit" class="btn btn-success" wire:click="guardartodo" wire:loading.remove
                wire:target="guardartodo">Crear</label>
            <span class="" wire:loading wire:target="guardartodo">Guardando...</span>
        </div>
    </x-modal>
</div>
