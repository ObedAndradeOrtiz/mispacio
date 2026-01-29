<div>
    @if ($llamada->estado == 'llamadas')
        <div class="d-flex">
            <a class="mr-2 btn btn-sm btn-icon btn-success d-flex align-items-center" data-bs-toggle="tooltip"
                data-bs-placement="top" title="AGENDAR CITA" data-original-title="Edit" wire:click="$set('openArea4',true)">
                <svg class="icon-15" width="15" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M16.4109 2.76862L16.4119 3.51824C19.1665 3.73413 20.9862 5.61119 20.9891 8.48975L21 16.9155C21.0039 20.054 19.0322 21.985 15.8718 21.99L8.15188 22C5.01119 22.004 3.01482 20.027 3.01087 16.8795L3.00001 8.55272C2.99606 5.65517 4.75153 3.78311 7.50617 3.53024L7.50518 2.78061C7.5042 2.34083 7.83001 2.01 8.26444 2.01C8.69886 2.009 9.02468 2.33883 9.02567 2.77861L9.02666 3.47826L14.8914 3.47027L14.8904 2.77062C14.8894 2.33084 15.2152 2.001 15.6497 2C16.0742 1.999 16.4099 2.32884 16.4109 2.76862ZM4.52148 8.86157L19.4696 8.84158V8.49175C19.4272 6.34283 18.349 5.21539 16.4138 5.04748L16.4148 5.81709C16.4148 6.24688 16.0801 6.5877 15.6556 6.5877C15.2212 6.5887 14.8943 6.24887 14.8943 5.81909L14.8934 5.0095L9.02863 5.01749L9.02962 5.82609C9.02962 6.25687 8.70479 6.5967 8.27036 6.5967C7.83594 6.5977 7.50913 6.25887 7.50913 5.82809L7.50815 5.05847C5.58286 5.25137 4.51753 6.38281 4.52049 8.55072L4.52148 8.86157ZM15.2399 13.4043V13.4153C15.2498 13.8751 15.625 14.2239 16.0801 14.2139C16.5244 14.2029 16.8789 13.8221 16.869 13.3623C16.8483 12.9225 16.4918 12.5637 16.0485 12.5647C15.5944 12.5747 15.2389 12.9445 15.2399 13.4043ZM16.0554 17.892C15.6013 17.882 15.235 17.5032 15.234 17.0435C15.2241 16.5837 15.5884 16.2029 16.0426 16.1919H16.0525C16.5165 16.1919 16.8927 16.5707 16.8927 17.0405C16.8937 17.5102 16.5185 17.891 16.0554 17.892ZM11.1721 13.4203C11.1919 13.8801 11.568 14.2389 12.0222 14.2189C12.4665 14.1979 12.821 13.8181 12.8012 13.3583C12.7903 12.9085 12.425 12.5587 11.9807 12.5597C11.5266 12.5797 11.1711 12.9605 11.1721 13.4203ZM12.0262 17.8471C11.572 17.8671 11.1968 17.5082 11.1761 17.0485C11.1761 16.5887 11.5305 16.2089 11.9847 16.1879C12.429 16.1869 12.7953 16.5367 12.8052 16.9855C12.8259 17.4463 12.4705 17.8261 12.0262 17.8471ZM7.10433 13.4553C7.12408 13.915 7.50025 14.2749 7.95442 14.2539C8.39872 14.2339 8.75317 13.8531 8.73243 13.3933C8.72256 12.9435 8.35725 12.5937 7.91196 12.5947C7.45779 12.6147 7.10334 12.9955 7.10433 13.4553ZM7.95837 17.8521C7.5042 17.8731 7.12901 17.5132 7.10828 17.0535C7.10729 16.5937 7.46273 16.2129 7.9169 16.1929C8.3612 16.1919 8.7275 16.5417 8.73737 16.9915C8.7581 17.4513 8.40365 17.8321 7.95837 17.8521Z"
                        fill="currentColor"></path>
                </svg>
                <span class="ms-1" style="font-size: 8px;"></span>
            </a>
            <a class="mr-2 btn btn-sm btn-icon btn-warning d-flex align-items-center" data-bs-toggle="tooltip"
                data-bs-placement="top" title="REMARKETING" data-original-title="Edit"
                wire:click="$set('rellamar',true)">
                <svg class="icon-15" width="15" viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M11.5317 12.4724C15.5208 16.4604 16.4258 11.8467 18.9656 14.3848C21.4143 16.8328 22.8216 17.3232 19.7192 20.4247C19.3306 20.737 16.8616 24.4943 8.1846 15.8197C-0.493478 7.144 3.26158 4.67244 3.57397 4.28395C6.68387 1.17385 7.16586 2.58938 9.61449 5.03733C12.1544 7.5765 7.54266 8.48441 11.5317 12.4724Z"
                        fill="currentColor"></path>
                </svg>
                <span class="ms-1" style="font-size: 8px;"></span>
            </a>
        </div>
    @else
        <div class="d-flex">
            <a class="mr-1 btn btn-sm btn-icon btn-primary d-flex align-items-center" data-bs-toggle="tooltip"
                data-bs-placement="top" title="VER ULTIMA CITA" data-original-title="Edit"
                wire:click="$set('vermitratamiento',true)">
                <svg class="icon-20" width="20" viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path opacity="0.4" fill-rule="evenodd" clip-rule="evenodd"
                        d="M17.7366 6.04606C19.4439 7.36388 20.8976 9.29455 21.9415 11.7091C22.0195 11.8924 22.0195 12.1067 21.9415 12.2812C19.8537 17.1103 16.1366 20 12 20H11.9902C7.86341 20 4.14634 17.1103 2.05854 12.2812C1.98049 12.1067 1.98049 11.8924 2.05854 11.7091C4.14634 6.87903 7.86341 4 11.9902 4H12C14.0683 4 16.0293 4.71758 17.7366 6.04606ZM8.09756 12C8.09756 14.1333 9.8439 15.8691 12 15.8691C14.1463 15.8691 15.8927 14.1333 15.8927 12C15.8927 9.85697 14.1463 8.12121 12 8.12121C9.8439 8.12121 8.09756 9.85697 8.09756 12Z"
                        fill="currentColor"></path>
                    <path
                        d="M14.4308 11.997C14.4308 13.3255 13.3381 14.4115 12.0015 14.4115C10.6552 14.4115 9.5625 13.3255 9.5625 11.997C9.5625 11.8321 9.58201 11.678 9.61128 11.5228H9.66006C10.743 11.5228 11.621 10.6695 11.6601 9.60184C11.7674 9.58342 11.8845 9.57275 12.0015 9.57275C13.3381 9.57275 14.4308 10.6588 14.4308 11.997Z"
                        fill="currentColor"></path>
                </svg>
                <span class="ms-1" style="font-size: 8px;"></span>
            </a>

            {{-- @livewire('clientes.crear-cita', ['idcall' => $llamada->telefono], key($llamada->id)) --}}
            <a class="mr-1 btn btn-sm btn-icon btn-warning d-flex align-items-center" data-bs-toggle="tooltip"
                data-bs-placement="top" title="REMARKETING" data-original-title="Edit"
                wire:click="$set('rellamar',true)">
                <svg class="icon-15" width="15" viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M11.5317 12.4724C15.5208 16.4604 16.4258 11.8467 18.9656 14.3848C21.4143 16.8328 22.8216 17.3232 19.7192 20.4247C19.3306 20.737 16.8616 24.4943 8.1846 15.8197C-0.493478 7.144 3.26158 4.67244 3.57397 4.28395C6.68387 1.17385 7.16586 2.58938 9.61449 5.03733C12.1544 7.5765 7.54266 8.48441 11.5317 12.4724Z"
                        fill="currentColor"></path>
                </svg>
                <span class="ms-1" style="font-size: 8px;"></span>
            </a>
        </div>
    @endif

    <x-sm-modal wire:model.defer="rellamar">
        <div class="px-6 py-4">
            <div class="text-lg font-medium text-gray-900">
                ¿Volvió a llamar: {{ $llamada->empresa }} con teléfono: {{ $llamada->telefono }}?
            </div>
        </div>
        <div>
            <label for="">Comentario: </label>
            <input type="text" wire:model="comentariollamada">
        </div>
        <div class="flex flex-row justify-end px-1 py-1 text-right bg-gray-100">
            <label type="submit" class="mr-2 btn btn-light" wire:click="cancelar">Cancelar</label>
            <label type="submit" class="btn btn-success" wire:click="rellamar">Si llamé</label>
        </div>
    </x-sm-modal>
    <x-sm-modal wire:model.defer="openArea4">
        <div class="px-6 py-4">
            <div class="text-lg font-medium text-gray-900">
                Confirmar cita
            </div>
            <div class="mt-4 text-sm text-gray-600">
                <form>
                    <div class="mt-2 form-group col-md-12">

                        <label class="form-label" for="cname">Area seleccionada:</label>
                        <select name="type" class="selectpicker form-control" data-style="py-0"
                            wire:model.defer="llamada.area">
                            <option>Seleccionar area</option>
                            @foreach ($areas as $area)
                                <option>{{ $area->area }}</option>
                            @endforeach
                        </select>
                    </div>
                    @error('llamada.area')
                        <label style="color:firebrick">Area requerida</label>
                        <br>
                    @enderror
                    <div class="mt-2 form-group">
                        <label class="form-label" for="">Nombre de cliente:</label>
                        <input type="text" class="form-control" id="texto" oninput="convertirAMayusculas()"
                            wire:model="llamada.empresa">
                    </div>
                    @error('llamada.empresa')
                        <label style="color:firebrick">Nombre requerido</label>
                        <br>
                    @enderror
                    <div class="mt-2 form-group">
                        <label class="form-label" for="">Carnet de cliente:</label>
                        <input type="text" class="form-control" id="exampleInputDisabled1"
                            wire:model.defer="llamada.ci">
                    </div>
                    <div class="mt-2 form-group">
                        <label class="form-label" for="">Telefono principal:</label>
                        <input type="text" class="form-control" id="exampleInputDisabled1"
                            wire:model.defer="llamada.telefono">
                    </div>
                    @error('llamada.telefono')
                        <label style="color:firebrick">Telefono requerido</label>
                        <br>
                    @enderror
                    <div class="mt-2 form-group">
                        <label class="form-label" for="exampleInputdate">Fecha de cita:</label>
                        <input type="date" class="form-control" id="exampleInputdate" value="2000-01-01"
                            wire:model.defer="fechacita">
                    </div>
                    @error('fechacita')
                        <label style="color:firebrick">Fecha requerida</label>
                        <br>
                    @enderror
                    <div class="mt-2 form-group">
                        <label class="form-label">Hora de cita:</label>
                        {{-- <input type="time" class="form-control" id="hora" v wire:model.defer="horario"> --}}
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
                            @error('hora')
                                <label style="color:firebrick">Hora requerida</label>
                                <br>
                            @enderror
                            <select name="type" class="selectpicker form-control" data-style="py-0"
                                wire:model.defer="minuto">
                                <option>Seleccionar minuto</option>
                                <option>00</option>
                                <option>15</option>
                                <option>30</option>
                                <option>45</option>
                            </select>
                            @error('minuto')
                                <label style="color:firebrick">Minuto requerido</label>
                                <br>
                            @enderror
                        </div>
                    </div>


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
                        <label class="form-label" for="">Seleccionar Tratamientos:</label>
                        <div style="display: flex; ">
                            {{-- @if ($botonpaquete)
                                <button type="button" class="mr-4 boton btn btn-primary"
                                    wire:click="$set('botonpaquete',true)" style="flex: 1;">Lista de paquetes</button>
                                <button type="button" class="boton btn btn-outline-primary"
                                    wire:click="$set('botonpaquete',false)" style="flex: 1;">Lista de
                                    tratamientos</button>
                            @else --}}
                            {{-- <button type="button" class="mr-4 boton btn btn-outline-primary"
                                    wire:click="$set('botonpaquete',true)" style="flex: 1;">Lista de paquetes</button> --}}
                            <button type="button" class="boton btn btn-primary"
                                wire:click="$set('botonpaquete',false)" style="flex: 1;">Lista de
                                tratamientos</button>
                            {{-- @endif --}}
                        </div>
                        {{-- @if ($botonpaquete)
                            <div>
                                <select name="type" class="selectpicker form-control" data-style="py-0"
                                     wire:model.defer="paqueteelegido">
                                    <option>Ninguno</option>
                                    @foreach ($paquetes as $paquete)
                                        <option value="{{ $paquete->id }}">{{ $paquete->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @else --}}
                        <div class="">
                            <input type="text" class="form-control" id="exampleInputDisabled1"
                                wire:model="busquedatratamiento" placeholder="Buscar tratamiento...">
                        </div>
                        <div class="form-group" style="margin-bottom: 35px;">
                            @foreach ($tratamientos as $tratamiento)
                                <div>
                                    <input type="checkbox"
                                        wire:model.defer="tratamientosSeleccionados.{{ $tratamiento->id }}"
                                        value="{{ $tratamiento->id }}">
                                    <label for="">{{ $tratamiento->nombre }}(
                                        {{ $tratamiento->costo }}Bs.)</label>
                                </div>
                            @endforeach
                        </div>
                        {{-- @endif --}}

                    </div>
                    @error('elegido')
                        <label style="color:firebrick">Encargado requerido</label>
                        <br>
                    @enderror
                    <div class="mt-2 form-group">
                        <label class="form-label" for="">Agrear comentario (opcional):</label>
                        <input type="text" class="form-control" id="exampleInputDisabled1"
                            wire:model.defer="llamada.comentario">
                    </div>
                    <div class="mt-2 form-group">
                        <label class="form-label" for="exampleInputDisabled1">Regitrado por:</label>
                        <input type="text" class="form-control" id="exampleInputDisabled1" disabled=""
                            value="{{ Auth::user()->name }}">
                    </div>

                </form>
            </div>
        </div>

        <div class="flex flex-row justify-end px-6 py-4 text-right bg-gray-100">
            <label type="submit" class="mr-2 btn btn-light" wire:click="cancelar">Cancelar</label>
            <label type="submit" class="btn btn-success" wire:click="guardarllamada">Confirmar</label>
        </div>
    </x-sm-modal>
    <x-sm-modal wire:model.defer="vermitratamiento">
        <div class="px-6 py-4">
            <div class="text-lg font-medium text-gray-900">
                Información de tratamiendo:
            </div>
            <div class="mt-4 text-sm text-gray-600">
                <form>
                    <div class="form-group">
                        <label class="form-label" for="">Nombre de cliente: </label>
                        <input type="text" class="form-control" id="exampleInputDisabled1" disabled=""
                            wire:model.defer="nombre">
                    </div>
                    <div class="form-group">
                        <div class="px-4 card-body">
                            <div class="table-responsive">
                                <table id="user-list-table" class="table table-striped" role="grid"
                                    data-bs-toggle="data-table">
                                    <thead>
                                        <tr class="ligth">
                                            <th>Tratamiento</th>
                                            <th>Costo</th>
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
                                                ->where('idllamada', $llamada->id)
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
                                                    <td>{{ $coll->costo }}</td>
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
                    </div>
                </form>
            </div>
        </div>
    </x-sm-modal>
    <script>
        function convertirAMayusculas() {
            var input = document.getElementById("texto");
            input.value = input.value.toUpperCase();
        }
    </script>
</div>
