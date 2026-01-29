<div>
    <button title="Crear nuevo cliente" class="btn btn-sm btn-primary d-flex" wire:click="$set('crear',true)"
        style="font-size: 1vw;">
        <svg class="icon-20" width="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" clip-rule="evenodd"
                d="M9.5 12.5537C12.2546 12.5537 14.4626 10.3171 14.4626 7.52684C14.4626 4.73663 12.2546 2.5 9.5 2.5C6.74543 2.5 4.53737 4.73663 4.53737 7.52684C4.53737 10.3171 6.74543 12.5537 9.5 12.5537ZM9.5 15.0152C5.45422 15.0152 2 15.6621 2 18.2464C2 20.8298 5.4332 21.5 9.5 21.5C13.5448 21.5 17 20.8531 17 18.2687C17 15.6844 13.5668 15.0152 9.5 15.0152ZM19.8979 9.58786H21.101C21.5962 9.58786 22 9.99731 22 10.4995C22 11.0016 21.5962 11.4111 21.101 11.4111H19.8979V12.5884C19.8979 13.0906 19.4952 13.5 18.999 13.5C18.5038 13.5 18.1 13.0906 18.1 12.5884V11.4111H16.899C16.4027 11.4111 16 11.0016 16 10.4995C16 9.99731 16.4027 9.58786 16.899 9.58786H18.1V8.41162C18.1 7.90945 18.5038 7.5 18.999 7.5C19.4952 7.5 19.8979 7.90945 19.8979 8.41162V9.58786Z"
                fill="currentColor"></path>
        </svg>
    </button>
    <x-sm-modal wire:model.defer="crear">
        <div class="px-6 py-4">
            <div class="flex items-center justify-between py-4">
                <div class="text-lg font-medium text-gray-900">
                    Formulario de registro de cliente
                </div>
                <div class="text-right">
                    <label type="submit" class="btn btn-success" wire:click="guardartodo" wire:loading.remove
                        wire:target="guardartodo">
                        Guardar nuevo cliente
                    </label>
                    <span wire:loading wire:target="guardartodo">Guardando...</span>
                </div>
            </div>
            <div class="mt-4 text-sm text-gray-600">
                <form>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class="form-label">Sucursal donde se registrará:</label>
                            <select class="selectpicker form-control" data-style="py-0"
                                wire:model.defer="empresaseleccionada">
                                <option>Seleccionar sucursal</option>
                                @foreach ($empresas as $empresa)
                                    <option>{{ $empresa->area }}</option>
                                @endforeach
                            </select>
                            @error('empresaseleccionada')
                                <label style="color:firebrick"> No seleccionó ninguna sucursal</label>
                            @enderror
                        </div>

                        <div class="form-group col-md-6">
                            <label class="form-label">Nombre completo del cliente</label>
                            <input type="text" class="form-control" oninput="convertirAMayusculas()"
                                wire:model.defer="name">
                            @error('name')
                                <label style="color:firebrick"> Nombre requerido</label>
                            @enderror
                        </div>

                        <div class="form-group col-md-6">
                            <label class="form-label">Teléfono principal</label>
                            <input type="text" class="form-control" wire:model.defer="telefono">
                            @error('telefono')
                                <label style="color:firebrick"> Teléfono requerido</label>
                            @enderror
                        </div>

                        <div class="form-group col-md-6">
                            <label class="form-label">Nro. de carnet de identidad</label>
                            <input type="text" class="form-control" wire:model.defer="ci">
                        </div>

                        <div class="form-group col-md-6">
                            <label class="form-label">Edad:</label>
                            <input type="number" class="form-control" wire:model.defer="edad">
                        </div>

                        <div class="form-group col-md-6">
                            <label class="form-label">Sexo</label>
                            <select class="selectpicker form-control" data-style="py-0" wire:model.defer="sexo">
                                <option value='femenino'>Femenino</option>
                                <option value="masculino">Masculino</option>
                            </select>
                            @error('sexo')
                                <label style="color:firebrick"> Sexo requerido</label>
                            @enderror
                        </div>

                        <div class="form-group col-md-6">
                            <label class="form-label">Fecha de visita</label>
                            <input type="date" class="form-control" wire:model.defer="fechacita">
                            @error('fechacita')
                                <label style="color:firebrick"> Fecha requerida</label>
                            @enderror
                        </div>

                        <div class="form-group col-md-6">
                            <label class="form-label">Seleccionar el medio de registro</label>
                            <select class="selectpicker form-control" data-style="py-0" wire:model.defer="modoingreso">
                                <option value="Facebook">Facebook</option>
                                <option value="WhatsApp">WhatsApp</option>
                                <option value="Instagram">Instagram</option>
                                <option value="Google">Google Maps</option>
                                <option value="Otro">Otro</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-2 form-group">
                        <label class="form-label">Hora de visita</label>
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
                        @error('hora')
                            <label style="color:firebrick"> Hora requerido</label>
                            <br>
                        @enderror
                        @error('minuto')
                            <label style="color:firebrick"> Minuto requerido</label>
                            <br>
                        @enderror
                    </div>
                    <div class="mt-2 form-group">
                        <label class="form-label" for="">Seleccionar tratamientos a realizar:</label>
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
                            <div class="form-group">
                                <div class="">
                                    <input type="text" class="form-control" wire:model="busquedatratamiento"
                                        placeholder="Buscar tratamiento...">
                                </div>
                                <div style="max-height: 100px; overflow-y: scroll;">
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

                            </div>
                        @endif

                    </div>
                </form>
            </div>
        </div>


    </x-sm-modal>
</div>
