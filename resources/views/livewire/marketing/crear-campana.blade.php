<div>
    <div>
        <button class="ml-4 mr-4 btn btn-primary" wire:click="$set('crearcuenta',true)" wire:click.prevent.stop><span
                style="color: white;">REGISTRAR CAMPAÑA</span></button>
        <x-sm-modal wire:model.defer="crearcuenta" wire:click.prevent.stop>
            <div class="px-6 py-4">
                <div class="text-lg font-medium text-gray-900">
                    NUEVA CAMPAÑA
                </div>
                <div class="mt-4 text-sm text-gray-600">
                    <form>
                        <div class="form-group">
                            <label class="form-label" for="">NOMBRE DE CAMPAÑA:</label>
                            <input type="text" class="form-control" id="texto" oninput="convertirAMayusculas()"
                                wire:model.defer="name">
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="">MENSAJE:</label>
                            <textarea name="" id="" cols="55" rows="10" wire:model="comentario"></textarea>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="">IMAGEN:</label>
                            <input class="form-control" type="file" wire:model="image">
                            @if ($image)
                                @if ($image->getClientOriginalExtension() === 'jpg' || $image->getClientOriginalExtension() === 'png')
                                    <img class="mt-4" src="{{ $image->temporaryUrl() }}" alt=""
                                        style="max-height: 250px;">
                                @endif
                            @endif
                        </div>
                        <div>

                            <label class="form-label" for="">TRATAMIENTOS DE CLIENTES:</label>
                            <select name="" id="" wire:model="tipotratamiento">
                                <option value="todos">TODOS LOS TRATAMIENTOS</option>
                                <option value="elegir">ELEGIR TRATAMIENTO</option>
                            </select>
                            @if ($tipotratamiento == 'elegir')
                                <div class="">
                                    <input type="text" class="form-control" id="exampleInputDisabled1"
                                        wire:model="busquedatratamiento" placeholder="Buscar tratamiento...">
                                </div>
                                <div class="px-4 py-2 table-responsive card">
                                    <table class="table mb-0 table-striped text-nowrap">
                                        <thead>

                                            <th>
                                                CHECK
                                            </th>
                                            <th>
                                                TRATAMIENTO
                                            </th>
                                            <th>
                                                CLIENTES
                                            </th>
                                        </thead>
                                        <tbody>
                                            @foreach ($tratamientos as $tratamiento)
                                                <tr>
                                                    <td>
                                                        <input type="checkbox"
                                                            wire:model.defer="tratamientosSeleccionados.{{ $tratamiento->id }}"
                                                            value="{{ $tratamiento->id }}">
                                                    </td>
                                                    <td>
                                                        <label for="">{{ $tratamiento->nombre }}</label>
                                                    </td>
                                                    @php
                                                        $resultadostratamientos = DB::table('historial_clientes')
                                                            ->select(
                                                                'historial_clientes.nombretratamiento',
                                                                DB::raw(
                                                                    'COUNT(DISTINCT historial_clientes.idcliente) as total_ingreso',
                                                                ),
                                                            )
                                                            ->join(
                                                                'operativos',
                                                                DB::raw(
                                                                    'CAST(historial_clientes.idoperativo AS INTEGER)',
                                                                ),
                                                                '=',
                                                                'operativos.id',
                                                            )
                                                            ->where(
                                                                'historial_clientes.nombretratamiento',
                                                                $tratamiento->nombre,
                                                            )
                                                            ->count();
                                                    @endphp
                                                    <td>
                                                        {{ $resultadostratamientos }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>

                                    </table>
                                </div>
                            @endif

                        </div>
                    </form>
                </div>
            </div>
            <div class="flex flex-row justify-end px-6 py-4 text-right bg-gray-100">
                <label type="submit" class="btn btn-success" wire:click="guardartodo" wire:loading.remove
                    wire:target="guardartodo">Crear</label>
                <span class="" wire:loading wire:target="guardartodo">Guardando...</span>
            </div>

        </x-sm-modal>
    </div>

</div>
