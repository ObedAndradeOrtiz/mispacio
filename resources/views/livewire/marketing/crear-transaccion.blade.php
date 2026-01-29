<div>
    <button class="mt-2 btn btn-primary" wire:click="$set('creartransaccion',true)" wire:click.prevent.stop><span
            style="color: white;">NUEVA TRANSACCION</span></button>
    <button class="mt-2 btn btn-warning" wire:click="$set('aumento',true)" wire:click.prevent.stop><span
            style="color: white; ">RECARGAR SALDO TRJ</span></button>
    <x-modal wire:model.defer="creartransaccion" wire:click.prevent.stop>
        <div class="px-6 py-4">
            <div class="text-lg font-medium text-gray-900">
                NUEVA TRANSACCIÓN
            </div>
            <div class="table-responsive">
                <table id="mitablaregistros1" class="table table-striped" role="grid">
                    <tbody>
                        <tr>
                            <td>
                                <label class="form-label">TARJETA EMISORA: </label>
                            </td>
                            <td>
                                <select wire:model="tarjetae" style="font-size: 0.7vw;">
                                    <option value="">NINGUNA</option>
                                    @foreach ($tarjetas as $lista)
                                        <option value="{{ $lista->id }}">{{ $lista->nombretarjeta }}</option>
                                    @endforeach
                                </select>
                                @error('tarjetae')
                                    <label style="color:firebrick"> No selecciono Tarjeta emisora</label>
                                    <br>
                                @enderror
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label class="form-label">TIPO DE TRANSACCIÓN </label>
                            </td>
                            <td>
                                <select wire:model="opcion" style="font-size: 0.7vw;">
                                    <option value="">NINGUNA</option>
                                    <option value="verificacion">VERIFICACIÓN</option>
                                    <option value="seguro">SEGURO</option>
                                    <option value="pagosPublicidad">USAR SALDO PARA PUBLICIDAD</option>
                                    <option value="pagosPublicidadenvio">ENVIAR SALDO PARA PUBLICIDAD</option>
                                    <option value="envioSaldo">ENVIO DE SALDO</option>
                                    <option value="otro">OTROS</option>
                                </select>
                            </td>
                        </tr>
                        @if ($opcion != '')
                            @if ($opcion == 'verificacion')
                                <tr>
                                    <td>
                                        <label class="form-label">CANTIDAD DE DESCUENTO:</label>

                                    </td>
                                    <td>
                                        <input type="number" style="font-size: 0.7vw;" wire:model="cantidaddeuso">
                                        @error('cantidaddeuso')
                                            <label style="color:firebrick"> No puso la cantidad de envio</label>
                                            <br>
                                        @enderror
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label class="form-label">FECHA DE TRANSACCION</label>
                                    </td>
                                    <td>
                                        <input type="date" style="font-size: 0.7vw;" wire:model="fecha">
                                        @error('fecha')
                                            <label style="color:firebrick"> No hay fecha</label>
                                            <br>
                                        @enderror
                                    </td>
                                </tr>
                            @endif
                            @if ($opcion == 'seguro')
                                <tr>
                                    <td>
                                        <label class="form-label">CANTIDAD DE DESCUENTO:</label>

                                    </td>
                                    <td>
                                        <input type="number" style="font-size: 0.7vw;" wire:model="cantidaddeuso">
                                        @error('cantidaddeuso')
                                            <label style="color:firebrick"> No puso la cantidad de envio</label>
                                            <br>
                                        @enderror
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label class="form-label">FECHA DE TRANSACCION</label>
                                    </td>
                                    <td>
                                        <input type="date" style="font-size: 0.7vw;" wire:model="fecha">
                                        @error('fecha')
                                            <label style="color:firebrick"> No hay fecha</label>
                                            <br>
                                        @enderror
                                    </td>
                                </tr>
                            @endif
                            @if ($opcion == 'otro')
                                <tr>
                                    <td>
                                        <label class="form-label">TIPO DE DIVISA</label>
                                    </td>
                                    <td>
                                        <select wire:model="tipocambio" style="font-size: 0.7vw;">
                                            <option value="BS">BOLIVIANOS</option>
                                            <option value="USD">USD</option>
                                        </select>
                                    </td>
                                </tr>
                                @if ($tipocambio == 'USD')
                                    <tr>
                                        <td>
                                            <label class="form-label">TASA DE CAMBIO</label>
                                        </td>
                                        <td>
                                            <input type="number" style="font-size: 0.7vw;" wire:model="cantidadusd">

                                        </td>
                                    </tr>
                                @endif
                                <tr>
                                    <td>
                                        <label class="form-label">CANTIDAD DE DESCUENTO:</label>

                                    </td>
                                    <td>
                                        <input type="number" style="font-size: 0.7vw;" wire:model="cantidaddeuso">
                                        @error('cantidaddeuso')
                                            <label style="color:firebrick"> No puso la cantidad de envio</label>
                                            <br>
                                        @enderror
                                    </td>
                                    <td>
                                        <label
                                            for="">{{ (empty($cantidadusd) ? 0 : $cantidadusd) * (empty($cantidaddeuso) ? 0 : $cantidaddeuso) }}</label>
                                    </td>


                                </tr>
                                <tr>
                                    <td>
                                        <label class="form-label">DESCRIPCIÓN:</label>

                                    </td>
                                    <td>
                                        <input type="text" style="font-size: 0.7vw;" wire:model="motivo">
                                        @error('cantidaddeuso')
                                            <label style="color:firebrick"> No puso la cantidad de envio</label>
                                            <br>
                                        @enderror
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label class="form-label">FECHA DE TRANSACCION</label>
                                    </td>
                                    <td>
                                        <input type="date" style="font-size: 0.7vw;" wire:model="fecha">
                                        @error('fecha')
                                            <label style="color:firebrick"> No hay fecha</label>
                                            <br>
                                        @enderror
                                    </td>
                                </tr>
                            @endif
                            @if ($opcion == 'pagosPublicidad')
                                <tr>
                                    <td>
                                        <label class="form-label">CUENTA COMERCIAL</label>

                                    </td>
                                    <td>
                                        <select wire:model="cuentaseleccionado" style="font-size: 0.7vw;">
                                            <option value="">NINGUNA</option>
                                            @foreach ($cuentas as $lista)
                                                <option value="{{ $lista->id }}">{{ $lista->nombrecuenta }}</option>
                                            @endforeach
                                        </select>
                                        @error('cuentaseleccionado')
                                            <label style="color:firebrick"> No selecciono cuenta comercial</label>
                                            <br>
                                        @enderror
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label class="form-label">CANTIDAD DE USO</label>

                                    </td>
                                    <td>
                                        <input type="number" style="font-size: 0.7vw;" wire:model="cantidaddeuso">
                                        @error('cantidaddeuso')
                                            <label style="color:firebrick"> No puso la cantidad de envio</label>
                                            <br>
                                        @enderror
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label class="form-label">CODIGO TRANSACCIONAL</label>

                                    </td>
                                    <td>
                                        <input type="text" style="font-size: 0.7vw;" wire:model="codigo">
                                        @error('codigo')
                                            <label style="color:firebrick"> No hay codigo</label>
                                            <br>
                                        @enderror
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label class="form-label">FECHA DE TRANSACCION</label>
                                    </td>
                                    <td>
                                        <input type="date" style="font-size: 0.7vw;" wire:model="fecha">
                                        @error('fecha')
                                            <label style="color:firebrick"> No hay fecha</label>
                                            <br>
                                        @enderror
                                    </td>
                                </tr>
                            @endif
                            @if ($opcion == 'pagosPublicidadenvio')
                                <tr>
                                    <td>
                                        <label class="form-label">TARJETA RECEPTORA:</label>
                                    </td>
                                    <td>
                                        <select wire:model="tarjetap" style="font-size: 0.7vw;">
                                            <option value="">NINGUNA</option>
                                            @foreach ($tarjetas as $lista)
                                                <option value="{{ $lista->id }}">{{ $lista->nombretarjeta }}</option>
                                            @endforeach
                                        </select>
                                        @error('tarjetap')
                                            <label style="color:firebrick"> No selecciono Tarjeta receptora</label>
                                            <br>
                                        @enderror
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <label class="form-label">CUENTA COMERCIAL</label>

                                    </td>
                                    <td>
                                        <select wire:model="cuentaseleccionado" style="font-size: 0.7vw;">
                                            <option value="">NINGUNA</option>
                                            @foreach ($cuentas as $lista)
                                                <option value="{{ $lista->id }}">{{ $lista->nombrecuenta }}</option>
                                            @endforeach
                                        </select>
                                        @error('cuentaseleccionado')
                                            <label style="color:firebrick"> No selecciono cuenta comercial</label>
                                            <br>
                                        @enderror
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label class="form-label">TIPO DE DIVISA</label>
                                    </td>
                                    <td>
                                        <select wire:model="tipocambio" style="font-size: 0.7vw;">
                                            <option value="BS">BOLIVIANOS</option>
                                            <option value="USD">USD</option>
                                        </select>
                                    </td>
                                </tr>
                                @if ($tipocambio == 'USD')
                                    <tr>
                                        <td>
                                            <label class="form-label">TASA DE CAMBIO</label>
                                        </td>
                                        <td>
                                            <input type="number" style="font-size: 0.7vw;" wire:model="cantidadusd">

                                        </td>
                                    </tr>
                                @endif
                                <tr>
                                    <td>
                                        <label class="form-label">CANTIDAD DE ENVIO</label>

                                    </td>
                                    <td>
                                        <input type="number" style="font-size: 0.7vw;" wire:model="cantidaddeuso">
                                        @error('cantidaddeuso')
                                            <label style="color:firebrick"> No puso la cantidad de envio</label>
                                            <br>
                                        @enderror
                                    </td>
                                    <td>
                                        <label
                                            for="">{{ (empty($cantidadusd) ? 0 : $cantidadusd) * (empty($cantidaddeuso) ? 0 : $cantidaddeuso) }}</label>
                                    </td>



                                </tr>
                                <tr>
                                    <td>
                                        <label class="form-label">CANTIDAD DE USO</label>
                                    </td>
                                    <td>
                                        <input type="number" style="font-size: 0.7vw;" wire:model="cantidadde">
                                        @error('cantidadde')
                                            <label style="color:firebrick"> No puso la cantidad de uso</label>
                                            <br>
                                        @enderror
                                    </td>
                                    <td>
                                        <label
                                            for="">{{ (empty($cantidadusd) ? 0 : $cantidadusd) * (empty($cantidaddeuso) ? 0 : $cantidaddeuso) }}</label>
                                    </td>



                                </tr>
                                <tr>
                                    <td>
                                        <label class="form-label">CODIGO TRANSACCIONAL</label>

                                    </td>
                                    <td>
                                        <input type="text" style="font-size: 0.7vw;" wire:model="codigo">
                                        @error('codigo')
                                            <label style="color:firebrick"> No hay codigo</label>
                                            <br>
                                        @enderror
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label class="form-label">FECHA DE TRANSACCION</label>
                                    </td>
                                    <td>
                                        <input type="date" style="font-size: 0.7vw;" wire:model="fecha">
                                        @error('fecha')
                                            <label style="color:firebrick"> No hay fecha</label>
                                            <br>
                                        @enderror
                                    </td>
                                </tr>
                            @endif
                            @if ($opcion == 'envioSaldo')
                                <tr>
                                    <td>
                                        <label class="form-label">TARJETA RECEPTORA:</label>
                                    </td>
                                    <td>
                                        <select wire:model="tarjetap" style="font-size: 0.7vw;">
                                            <option value="">NINGUNA</option>
                                            @foreach ($tarjetas as $lista)
                                                <option value="{{ $lista->id }}">{{ $lista->nombretarjeta }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('tarjetap')
                                            <label style="color:firebrick"> No selecciono Tarjeta receptora</label>
                                            <br>
                                        @enderror
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label class="form-label">TIPO DE DIVISA</label>
                                    </td>
                                    <td>
                                        <select wire:model="tipocambio" style="font-size: 0.7vw;">
                                            <option value="BS">BOLIVIANOS</option>
                                            <option value="USD">USD</option>
                                        </select>
                                    </td>
                                </tr>
                                @if ($tipocambio == 'USD')
                                    <tr>
                                        <td>
                                            <label class="form-label">TASA DE CAMBIO</label>
                                        </td>
                                        <td>
                                            <input type="number" style="font-size: 0.7vw;" wire:model="cantidadusd">

                                        </td>
                                    </tr>
                                @endif
                                <tr>
                                    <td>
                                        <label class="form-label">CANTIDAD DE ENVIO</label>

                                    </td>
                                    <td>
                                        <input type="number" style="font-size: 0.7vw;" wire:model="cantidaddeuso">
                                        @error('cantidaddeuso')
                                            <label style="color:firebrick"> No puso la cantidad de envio</label>
                                            <br>
                                        @enderror
                                    </td>
                                    <td>
                                        <label
                                            for="">{{ (empty($cantidadusd) ? 0 : $cantidadusd) * (empty($cantidaddeuso) ? 0 : $cantidaddeuso) }}</label>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label class="form-label">FECHA DE TRANSACCION</label>
                                    </td>
                                    <td>
                                        <input type="date" style="font-size: 0.7vw;" wire:model="fecha">
                                        @error('fecha')
                                            <label style="color:firebrick"> No hay fecha</label>
                                            <br>
                                        @enderror
                                    </td>
                                </tr>
                            @endif


                        @endif

                    </tbody>
                </table>

            </div>

        </div>
        <div class="flex flex-row justify-end px-6 py-4 text-right bg-gray-100">
            <label type="submit" class="btn btn-success" style="background-color: green" wire:click="guardartodo"
                wire:loading.remove wire:target="guardartodo">Crear</label>
            <span class="" wire:loading wire:target="guardartodo">Guardando...</span>
        </div>
    </x-modal>
    <x-modal wire:model.defer="aumento" wire:click.prevent.stop>
        <div class="px-6 py-4">
            <div class="text-lg font-medium text-gray-900">
                NUEVO AUMENTO
            </div>
            <div class="table-responsive">
                <table id="mitablaregistros1" class="table table-striped" role="grid">
                    <thead>
                        <tr>


                        </tr>
                    </thead>
                    <tbody>

                        <tr>
                            <td>
                                <label class="form-label">TARJETA RECEPTORA:</label>

                            </td>
                            <td>
                                <select wire:model="tarjetap" style="font-size: 0.7vw;">
                                    <option value="">NINGUNA</option>
                                    @foreach ($tarjetas as $lista)
                                        <option value="{{ $lista->id }}">{{ $lista->nombretarjeta }}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <label class="form-label">CANTIDAD DE AUMENTO</label>

                            </td>
                            <td>
                                <input type="number" style="font-size: 0.7vw;" wire:model="cantidaaumnento">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label class="form-label">FECHA DE TRANSACCION</label>
                            </td>
                            <td>
                                <input type="date" style="font-size: 0.7vw;" wire:model="fecha">
                            </td>
                        </tr>
                    </tbody>
                </table>

            </div>

        </div>
        <div class="flex flex-row justify-end px-6 py-4 text-right bg-gray-100">
            <label type="submit" class="btn btn-success" style="background-color: green"
                wire:click="guardaraumento" wire:loading.remove wire:target="guardaraumento">Guardar</label>
            <span class="" wire:loading wire:target="guardaraumento">Guardando...</span>
        </div>
    </x-modal>
    <x-modal wire:model.defer="devolucion" wire:click.prevent.stop>
        <div class="px-6 py-4">
            <div class="text-lg font-medium text-gray-900">
                NUEVA DEVOLUCIÓN
            </div>
            <div class="table-responsive">
                <table id="mitablaregistros1" class="table table-striped" role="grid">
                    <thead>
                        <tr>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <label class="form-label">TARJETA EMISORA:</label>
                            </td>
                            <td>
                                <select wire:model="tarjetae" style="font-size: 0.7vw;">
                                    <option value="">NINGUNA</option>
                                    @foreach ($tarjetas as $lista)
                                        <option value="{{ $lista->id }}">{{ $lista->nombretarjeta }}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label class="form-label">TARJETA RECEPTORA:</label>
                            </td>
                            <td>
                                <select wire:model="tarjetap" style="font-size: 0.7vw;">
                                    <option value="">NINGUNA</option>
                                    @foreach ($tarjetas as $lista)
                                        <option value="{{ $lista->id }}">{{ $lista->nombretarjeta }}</option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label class="form-label">CANTIDAD DE DISMINUCION</label>
                            </td>
                            <td>
                                <input type="number" style="font-size: 0.7vw;" wire:model="cantidaaumnento">
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label class="form-label">FECHA DE TRANSACCION</label>
                            </td>
                            <td>
                                <input type="date" style="font-size: 0.7vw;" wire:model="fecha">
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="flex flex-row justify-end px-6 py-4 text-right bg-gray-100">
            <label type="submit" class="btn btn-success" style="background-color: green"
                wire:click="guardardevolucion" wire:loading.remove wire:target="guardardevolucion">Guardar</label>
            <span class="" wire:loading wire:target="guardardevolucion">Guardando...</span>
        </div>
    </x-modal>
</div>
