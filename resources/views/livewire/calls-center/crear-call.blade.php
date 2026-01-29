<div>
    <button class="mt-2 btn btn-primary btn-sm d-flex align-items-center" wire:click="$set('crear',true)">
        <svg class="icon-20" width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path
                d="M14.4183 5.49C13.9422 5.40206 13.505 5.70586 13.4144 6.17054C13.3238 6.63522 13.6285 7.08891 14.0916 7.17984C15.4859 7.45166 16.5624 8.53092 16.8353 9.92995V9.93095C16.913 10.3337 17.2675 10.6265 17.6759 10.6265C17.7306 10.6265 17.7854 10.6215 17.8412 10.6115C18.3043 10.5186 18.609 10.0659 18.5184 9.60018C18.1111 7.51062 16.5027 5.89672 14.4183 5.49Z"
                fill="currentColor"></path>
            <path
                d="M14.3558 2.00793C14.1328 1.97595 13.9087 2.04191 13.7304 2.18381C13.5472 2.32771 13.4326 2.53557 13.4078 2.76841C13.355 3.23908 13.6946 3.66479 14.1646 3.71776C17.4063 4.07951 19.9259 6.60477 20.2904 9.85654C20.3392 10.2922 20.7047 10.621 21.1409 10.621C21.1738 10.621 21.2057 10.619 21.2385 10.615C21.4666 10.59 21.6698 10.4771 21.8132 10.2972C21.9556 10.1174 22.0203 9.89351 21.9944 9.66467C21.5403 5.60746 18.4002 2.45862 14.3558 2.00793Z"
                fill="currentColor"></path>
            <path fill-rule="evenodd" clip-rule="evenodd"
                d="M11.0317 12.9724C15.0208 16.9604 15.9258 12.3467 18.4656 14.8848C20.9143 17.3328 22.3216 17.8232 19.2192 20.9247C18.8306 21.237 16.3616 24.9943 7.6846 16.3197C-0.993478 7.644 2.76158 5.17244 3.07397 4.78395C6.18387 1.67385 6.66586 3.08938 9.11449 5.53733C11.6544 8.0765 7.04266 8.98441 11.0317 12.9724Z"
                fill="currentColor"></path>

        </svg><span>Nueva llamada</span></button>

    <x-sm-modal wire:model.defer="crear">
        <div class="px-6 py-4">
            <div class="text-xl font-semibold text-gray-900">
                REGISTRAR NUEVA LLAMADA
            </div>
            <div class="mt-6">
                <form>
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <!-- Primera columna -->
                        <div class="mt-4 form-group">
                            <label class="text-sm font-medium text-gray-700 form-label" for="cname">Seleccionar
                                Sucursal:</label>
                            <select name="type"
                                class="px-3 py-2 border rounded-md shadow-sm selectpicker form-control focus:outline-none focus:ring-2 focus:ring-green-500"
                                wire:model.defer="areaseleccionada">
                                <option>Seleccionar área</option>
                                @foreach ($areas as $area)
                                    <option>{{ $area->area }}</option>
                                @endforeach
                            </select>
                            @error('areaseleccionada')
                                <span class="text-xs text-red-500">No seleccionó ninguna área</span>
                            @enderror
                        </div>

                        <div class="mt-4 form-group">
                            <label class="text-sm font-medium text-gray-700 form-label" for="cname">Seleccionar medio
                                de ingreso:</label>
                            <select name="type"
                                class="px-3 py-2 border rounded-md shadow-sm selectpicker form-control focus:outline-none focus:ring-2 focus:ring-green-500"
                                wire:model.defer="modo">
                                <option value="">Seleccione medio</option>
                                <option value="Facebook">Facebook</option>
                                <option value="WhatsApp">WhatsApp</option>
                                <option value="Instagram">Instagram</option>
                                <option value="Google">Google Maps</option>
                                <option value="tiktok">Tik Tok</option>
                                <option value="Otro">Otro</option>
                                <option value="dep">Otro Departamento</option>
                            </select>
                            @error('modo')
                                <span class="text-xs text-red-500">No seleccionó medio</span>
                            @enderror
                        </div>

                        <!-- Segunda columna -->
                        <div class="mt-4 form-group">
                            <label class="text-sm font-medium text-gray-700 form-label" for="empresa">Nombre de
                                Cliente:</label>
                            <input type="text"
                                class="px-3 py-2 border rounded-md shadow-sm form-control focus:outline-none focus:ring-2 focus:ring-green-500"
                                id="empresa" oninput="convertirAMayusculas()" wire:model.defer="empresa">
                            @error('empresa')
                                <span class="text-xs text-red-500">Nombre requerido</span>
                            @enderror
                        </div>

                        <!-- Tercera columna -->
                        <div class="mt-4 form-group">
                            <label class="text-sm font-medium text-gray-700 form-label" for="telefono">Teléfono
                                principal:</label>
                            <input type="number"
                                class="px-3 py-2 border rounded-md shadow-sm form-control focus:outline-none focus:ring-2 focus:ring-green-500"
                                id="telefono" wire:model="telefono">
                            @if ($telefono !== null)
                                <span class="text-xs {{ $telefonoExistente ? 'text-red-500' : 'text-green-500' }}">
                                    {{ $telefonoExistente ? '❌ Número ya existente' : '✅ Número disponible' }}
                                </span>
                            @endif
                            @error('telefono')
                                <span class="text-xs text-red-500">Teléfono requerido</span>
                            @enderror
                        </div>

                        <div class="mt-4 form-group">
                            <label class="text-sm font-medium text-gray-700 form-label" for="fecha">Fecha de
                                llamada:</label>
                            <input type="date"
                                class="px-3 py-2 border rounded-md shadow-sm form-control focus:outline-none focus:ring-2 focus:ring-green-500"
                                id="fecha" value="2000-01-01" wire:model.defer="fecha">
                            @error('fecha')
                                <span class="text-xs text-red-500">Fecha requerida</span>
                            @enderror
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="flex flex-row justify-end px-6 py-4 text-right bg-gray-100">
            <button type="submit"
                class="px-4 py-2 text-white bg-green-500 rounded-md shadow-sm btn btn-success hover:bg-green-600"
                wire:click="guardartodo" wire:loading.remove wire:target="guardartodo">Crear</button>
            <span wire:loading wire:target="guardartodo" class="text-gray-500">Guardando...</span>
        </div>
    </x-sm-modal>


</div>
