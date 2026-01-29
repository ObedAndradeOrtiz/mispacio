<div>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        tr:hover {
            background-color: #f5f5f5;
        }
    </style>

    <div class="flex flex-row justify-between" style="margin: 2%;">
        <div>
            <div>
                <label for="semana">SEMANA DE PAGO</label>
                <select class="shadow-none form-select form-select-smmt-5" id="semana" wire:model="semana">
                    <option value="SIN SELECCIONAR">SIN SELECCIONAR</option>
                    <option value="SEMANA-1">SEMANA-1</option>
                    <option value="SEMANA-2">SEMANA-2</option>
                    <option value="SEMANA-3">SEMANA-3</option>
                    <option value="SEMANA-4">SEMANA-4</option>
                </select>
            </div>
            <div>
                <label for="semana">ESTADO DE PLANILLA</label>
                <select class="shadow-none form-select form-select-smmt-5" id="semana" wire:model="estado">
                    <option value="POR PAGAR">POR PAGAR</option>
                    <option value="PAGADO">PAGADO</option>

                </select>
            </div>
        </div>
        <div>
            <label for="fecha-fin">FECHA DE PAGO:</label>
            <input type="date" wire:model="fechapago">
            @if (Auth::user()->rol == 'Contador' ||
                    Auth::user()->rol == 'Administrador' ||
                    Auth::user()->rol == 'Sistema' ||
                    Auth::user()->rol == 'Recursos Humanos')
                <button class=" btn btn-warning" wire:click='actualizarplanilla'>ACTUALIZAR</button>
                <button class=" btn btn-success" wire:click='guardarplanilla'>GUARDAR PLANILLA</button>
            @endif

        </div>

    </div>
    <div>
        <h4>TOTALES DE: {{ $semana }}</h4>
        @php
            $nro = 0;
        @endphp
        <table>
            <thead>
                <th>NRO</th>
                <th>NOMBRE Y APELLIDO</th>
                <th>HABER BASICO</th>
                <th>BONOS</th>
                <th>SUELDO <br> HORA</th>
                <th>HORAS <br> DIAS</th>
                <th>DIAS <br> TRABAJADOS</th>
                <th>HORAS <br> EXTRAS</th>
                <th>ANTICIPOS</th>
                <th>LIQUIDO <br> PAGABLE</th>
            </thead>
            <tbody>
                @foreach ($planillas as $item)
                    <tr> @php
                        $nro = $nro + 1;
                    @endphp
                        <td>{{ $nro }}</td>
                        <td>{{ $item->nombre }}</td>
                        @if (Auth::user()->rol == 'Contador' ||
                                Auth::user()->rol == 'Administrador' ||
                                Auth::user()->rol == 'Sistema' ||
                                Auth::user()->rol == 'Recursos Humanos')
                            <td style="max-width: 100px;">
                                <input style="width: 100%;" type="number" wire:model="haberbasico.{{ $item->id }}"
                                    min="0" value="{{ $haberbasico[$item->id] }}">
                            </td>
                            <td style="max-width: 100px;">
                                <input style="width: 100%;" type="number" wire:model="bonos.{{ $item->id }}"
                                    value="{{ $bonos[$item->id] }}">
                            </td>
                            <td style="max-width: 100px;">
                                <input style="width: 100%;" type="number" wire:model="sueldohora.{{ $item->id }}"
                                    value="{{ $sueldohora[$item->id] }}">
                            </td>
                            <td style="max-width: 100px;">
                                <input style="width: 100%;" type="number" wire:model="horasdias.{{ $item->id }}"
                                    value="{{ $horasdias[$item->id] }}">
                            </td>
                            <td style="max-width: 100px;">
                                <input style="width: 100%;" type="number"
                                    wire:model="diastrabajados.{{ $item->id }}"
                                    value="{{ $diastrabajados[$item->id] }}">
                            </td>
                            <td style="max-width: 100px;">
                                <input style="width: 100%;" type="number" wire:model="horasextras.{{ $item->id }}"
                                    value="{{ $horasextras[$item->id] }}">
                            </td>

                            <td style="max-width: 100px;">
                                <input style="width: 100%;" type="number" wire:model="anticipo.{{ $item->id }}"
                                    value="{{ $anticipo[$item->id] }}">
                            </td>
                            <td style="max-width: 100px;">
                                <input style="width: 100%;" type="number" wire:model="pagado.{{ $item->id }}"
                                    value="{{ $pagado[$item->id] }}">
                            </td>
                        @else
                            <td style="max-width: 100px;">
                                <input style="width: 100%;" type="number" min="0" value="0" disabled>
                            </td>
                            <td style="max-width: 100px;">
                                <input style="width: 100%;" type="number" value="0" disabled>
                            </td>
                            <td style="max-width: 100px;">
                                <input style="width: 100%;" type="number" value="0" disabled>
                            </td>
                            <td style="max-width: 100px;">
                                <input style="width: 100%;" type="number" value="0" disabled>
                            </td>
                            <td style="max-width: 100px;">
                                <input style="width: 100%;" type="number" value="0"disabled>
                            </td>
                            <td style="max-width: 100px;">
                                <input style="width: 100%;" type="number" value="0" disabled>
                            </td>

                            <td style="max-width: 100px;">
                                <input style="width: 100%;" type="number" value="0" disabled>
                            </td>
                            <td style="max-width: 100px;">
                                <input style="width: 100%;" type="number" value="0" disabled>
                            </td>
                        @endif

                    </tr>
                @endforeach
                <tr>
                    <td>TOTALES</td>
                    <td></td>
                    <td>{{ $totalHaberBasico }}</td>
                    <td>{{ $totalBonos }}</td>
                    <td>{{ $totalSueldoHora }}</td>
                    <td>{{ $totalHorasDias }}</td>
                    <td>{{ $totalDiasTrabajados }}</td>
                    <td>{{ $totalHorasExtras }}</td>
                    <td>{{ $totalAnticipo }}</td>
                    <td>{{ $totalPagado }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
