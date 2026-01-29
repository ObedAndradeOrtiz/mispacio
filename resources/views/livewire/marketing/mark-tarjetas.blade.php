<div>

    <div class="flex-wrap mt-2 ml-4 mr-4" style="display: flex;">
        {{-- <div style="margin-right: 1%;">
            <div class="form-group" style="margin-right: 1%; ">
                <div>
                    <label>Sucursal: </label>
                </div>
                <div>
                    <select wire:model="areaseleccionada">
                        <option value="">Todas</option>
                        @foreach ($areas as $lista)
                            <option value="{{ $lista->area }}">{{ $lista->area }}</option>
                        @endforeach
                    </select>
                </div>

            </div>

        </div>
        <div class="form-group" style="margin-right: 1%;">
            <div>
                <label>Cuenta comercial: </label>
            </div>
            <div><select wire:model="cuentaseleccionado">
                    <option value="">Todas</option>
                    @foreach ($cuentas as $lista)
                        <option value="{{ $lista->id }}">{{ $lista->name }}</option>
                    @endforeach
                </select></div>
        </div> --}}

    </div>
    <div class="flex flex-row justify-between">
        <h3 class="ml-4" style="font-size: 18px;"><strong>LISTA DE TARJETAS</strong> </h3>
        @livewire('marketing.crear-tarjeta')
    </div>
    <div class="mt-2" style=" border-radius: 5px;">
        <input type="text" class="form-control" id="exampleInputDisabled1" wire:model="busqueda"
            placeholder="Buscar tarjeta...">
    </div>
    {{-- <div class="mb-2 ml-4">
        <label for="">Se estan mostrando: {{ $registrotransaccionestotal }} transacciones.</label>
    </div> --}}
    <div class="table-responsive">
        <table id="mitablaregistros1" class="table table-striped" role="grid" data-bs-toggle="data-table">
            <thead>
                <tr>
                    <th>TARJETA</th>
                    <th>SALDO</th>
                    <th>NUMERO</th>
                    <th>FECHA</th>
                    <th>CVV</th>
                    <th>NR.CUENTA</th>
                    <th>BANCO</th>
                    <th>RESPONSABLE</th>
                    <th>ACCIÓN</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tarjetas as $item)
                    <tr>
                        <td>{{ $item->nombretarjeta }}</td>
                        <td>{{ $item->saldo }}</td>
                        <td>{{ $item->numero }}</td>
                        <td>{{ $item->fecha }}</td>
                        <td>{{ $item->motivo }}</td>
                        <td>{{ $item->nrocuenta }}</td>
                        <td>{{ $item->banco }}</td>
                        <td>{{ $item->responsable }}</td>
                        <td>@livewire('marketing.editar-tarjeta', ['idtarjeta' => $item->id], key($item->id))</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>

</div>
