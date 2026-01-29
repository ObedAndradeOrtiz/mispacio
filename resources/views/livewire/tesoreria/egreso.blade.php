<div class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-6xl p-8 bg-white rounded-lg shadow-xl">
        <h2 class="mb-6 text-2xl font-semibold text-center text-gray-800">REGISTRO DE GASTO</h2>
        <form>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                <!-- Sucursal -->
                <div>
                    <label class="form-label">Seleccione sucursal:</label>
                    <select class="form-control" wire:model="sucursal">
                        <option value="">Seleccione sucursal</option>
                        @foreach ($areas as $item)
                            <option value="{{ $item->id }}">{{ $item->area }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Tipo de egreso -->
                <div>
                    <label class="form-label">Tipo de egreso:</label>
                    <select class="form-control" wire:model="tipogasto">
                        <option>Seleccionar:</option>
                        <option value="AGUA POTABLE">AGUA POTABLE</option>
                        <option value="ALQUILER">ALQUILER</option>
                        <option value="GAS">GAS</option>
                        <option value="IMPUESTOS">IMPUESTOS</option>
                        <option value="LUZ ELECTRICA">LUZ ELECTRICA</option>
                        <option value="INTERNET/TEL">INTERNET/TEL</option>
                        <option value="Dra. PAOLA">Dra. PAOLA</option>
                        <option value="Sr. ALEXANDRE">Sr. ALEXANDRE</option>
                        <option value="ADELANTO AL PERSONAL">ADELANTO AL PERSONAL</option>
                        <option value="MATERIAL CAFETERIA">MATERIAL CAFETERIA</option>
                        <option value="MATERIAL ESCRITORIO">MATERIAL ESCRITORIO</option>
                        <option value="MATERIAL LIMPIEZA">MATERIAL LIMPIEZA</option>
                        <option value="MATERIAL DE PROCEDIMIENTOS">MATERIAL DE PROCEDIMIENTOS</option>
                        <option value="MATERIAL PARA EVENTOS">MATERIAL PARA EVENTOS</option>
                        <option value="MATERIAL PARA MANTENIMIENTO">MATERIAL PARA MANTENIMIENTO</option>
                        <option value="MANTENIMIENTO DE EQUIPOS">MANTENIMIENTO DE EQUIPOS</option>
                        <option value="MANTENIMIENTO DE SUCURSAL">MANTENIMIENTO DE SUCURSAL</option>
                        <option value="COMPRA DE EQUIPO">COMPRA DE EQUIPO</option>
                        <option value="COMPRA DE MUEBLE">COMPRA DE MUEBLE</option>
                        <option value="MERIENDAS">MERIENDAS</option>
                        <option value="PUBLICIDAD">PUBLICIDAD</option>
                        <option value="SERVICIOS PROFESIONALES">SERVICIOS PROFESIONALES</option>
                        <option value="TRAMITES">TRAMITES</option>
                        <option value="TRANSPORTE">TRANSPORTE</option>
                        <option value="SUELDO">PAGO SUELDO</option>
                        <option value="CAJA CHICA">CAJA CHICA</option>
                        <option value="VIATICOS">VIATICOS</option>
                        <option value="SERVICIO DE LIMPIEZA">SERVICIO DE LIMPIEZA</option>
                        <option value="PAGO DE EXPENSAS">PAGO DE EXPENSAS</option>
                        <option value="LIQUIDACION">LIQUIDACION</option>
                    </select>
                </div>

                <!-- Fecha -->
                <div>
                    <label class="form-label">Fecha de egreso:</label>
                    <input type="date" class="form-control" wire:model="fechagasto">
                </div>

                <!-- Cartera -->
                <div>
                    <label class="form-label">Cartera de egreso:</label>
                    <select class="form-control" wire:model="cartera">
                        <option value="Caja">Caja central</option>
                        <option value="Externo">Externo</option>
                    </select>
                </div>

                <!-- Monto -->
                <div>
                    <label class="form-label">Monto:</label>
                    <input type="number" class="form-control" wire:model="montoegreso">
                </div>

                <!-- Método de pago -->
                <div>
                    <label class="form-label">Método de pago:</label>
                    <select class="form-control" wire:model="modopago">
                        <option value="Qr">QR</option>
                        <option value="Efectivo">Efectivo</option>
                    </select>
                </div>

                <!-- Destinatario (condicional) -->
                @if ($tipogasto == 'ADELANTO AL PERSONAL' || $tipogasto == 'SUELDO')
                    <div>
                        <label class="form-label">Seleccionar destinatario:</label>
                        <select class="form-control" wire:model="destinario">
                            <option>Seleccionar usuario</option>
                            @foreach ($usersl as $area)
                                <option>{{ $area->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <!-- Comentario -->
                <div class="md:col-span-3">
                    <label class="form-label">Especifique el egreso (Opcional):</label>
                    <input type="text" class="form-control" wire:model="comentario">
                </div>

                <!-- Registrado por -->
                <div class="md:col-span-3">
                    <label class="form-label">Registrado por:</label>
                    <input type="text" class="bg-gray-100 form-control" disabled value="{{ Auth::user()->name }}">
                </div>
            </div>

            <!-- Botón Guardar -->
            <div class="flex justify-end mt-6">
                <button type="button" class="btn btn-success" style="background-color: green;" wire:click="confirmar">
                    Guardar
                </button>
            </div>
        </form>
    </div>
</div>
