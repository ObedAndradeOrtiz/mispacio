<div>
    <button class="ml-4 mr-4 btn btn-primary" wire:click="$set('crearpublicidad',true)" wire:click.prevent.stop><span
            style="color: white;">REGISTRAR PUBLICIDAD</span></button>
    <x-sm-modal wire:model.defer="crearpublicidad" wire:click.prevent.stop>
        <div class="px-6 py-4">
            <!-- Título -->
            <div class="text-lg font-medium text-gray-900">
                NUEVA PUBLICIDAD
            </div>

            <!-- Contenido del formulario -->
            <div class="mt-4 text-sm text-gray-600">
                <form>
                    <!-- Tipo de Campaña -->
                    <div class="form-group">
                        <label class="form-label" for="campaña">TIPO DE CAMPAÑA</label>
                        <select name="type" id="campaña" class="selectpicker form-control" data-style="py-0"
                            wire:model.defer="campañaelegida">
                            <option value="">SELECCIONAR LA CAMPANA </option>
                            @foreach ($campañas as $campaña)
                                <option value="{{ $campaña->id }}">{{ $campaña->tipo }}</option>
                            @endforeach
                        </select>
                    </div>
                    <label class="mt-4 form-label">ENVIO DE PUBLICIDAD DE SUCURSAL</label>
                    <select class="mt-1 form-control" wire:model="sucursal">
                        <option value="suc0">TODAS LAS SUCURSALES</option>
                        <option value="suc1">ELEGIR SUCURSAL</option>
                        <option value="suc2">PERSONALIZADO</option>
                    </select>
                    @if ($sucursal != 'suc2')
                        <!-- Tipo de Cliente -->
                        <div class="form-group">
                            {{-- <label class="form-label">TIPO DE CLIENTE</label> --}}
                            <div class="flex-wrap gap-2 d-flex">
                                {{-- <select class="form-control" wire:model="tipocliente">
                                    <option value="tip0">TODOS LOS CLIENTES</option>
                                    <option value="tip11">PERSONALIZADO</option>
                                </select> --}}
                                <label class="mt-4 form-label">ASISTENCIA</label>
                                <select class="mt-1 form-control" wire:model="asistencia">
                                    <option value="noagen">NO AGENDADO</option>
                                    <option value="siasis">ASISTIDO</option>
                                    <option value="noasis">NO ASISTIDO</option>
                                    <option value="snasis">ASISTIDO/NO ASISTIDO</option>
                                </select>

                            </div>
                        </div>
                    @endif

                    @if ($sucursal != 'suc0')
                        <!-- Cuenta de WhatsApp -->
                        <div class="mt-4 form-group">
                            <label class="form-label" for="cuenta">CUENTA DE WHATSAPP</label>
                            <select name="type" id="cuenta" class="selectpicker form-control" data-style="py-0"
                                wire:model.defer="cuentaelegida">
                                <option value="">Seleccionar cuenta</option>
                                @foreach ($cuentas as $cuenta)
                                    <option value="{{ $cuenta->id }}">{{ $cuenta->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    @if ($sucursal == 'suc2')
                        <div class="mt-4 form-group">
                            <label class="form-label" for="cuenta">NUMERO DE WHATSAPP PERSONALIZADO</label>
                            <input type="text" name="" id="" wire:model="numero">
                        </div>
                    @endif


                    <!-- Frecuencia -->
                    <div class="mt-4 form-group">
                        <label class="form-label" for="frecuencia">FRECUENCIA</label>
                        <select id="frecuencia" class="form-control" wire:model="frecuencia">
                            <option value="frec0">UNA SOLA VEZ</option>
                            <option value="frec1">DIARIA</option>
                            <option value="frec2">SEMANAL</option>
                            <option value="frec3">MENSUAL</option>
                            <option value="frec4">RANGO DE FECHAS</option>
                        </select>
                    </div>

                    <!-- Temporalidad -->
                    <div class="mt-4 form-group">
                        <label class="form-label">TEMPORALIDAD/ RANGO</label>
                        <div class="row">
                            <!-- Fecha Inicio -->
                            @if ($frecuencia == 'frec4')
                                <label for="">FECHA DE EJECUCION</label>
                                <input type="date" id="fecharango" class="form-control" wire:model='fecharango'>
                            @endif
                            <div class="mt-3 col-md-6">
                                <label for="fechainicio">FECHA INICIO</label>
                                <input type="date" id="fechainicio" class="form-control" wire:model="fechainicio">
                            </div>

                            <!-- Fecha Final (opcional) -->
                            @if ($frecuencia == 'frec1')
                                <div class="mt-3 col-md-6">
                                    <label for="fechafinal">FECHA FINAL</label>
                                    <input type="date" id="fechafinal" class="form-control" wire:model="fechafinal">
                                </div>
                            @endif
                            @if ($frecuencia == 'frec4')
                                <div class="mt-3 col-md-6">
                                    <label for="fechafinal">FECHA FINAL</label>
                                    <input type="date" id="fechafinal" class="form-control" wire:model="fechafinal">
                                </div>
                            @endif

                            <!-- Hora -->
                            <div class="mt-3 col-md-6">
                                <label>HORA DE ENVIO</label>
                                <div class="gap-2 d-flex">
                                    <select class="form-control" wire:model.defer="hora">
                                        <option value="">Seleccionar hora</option>
                                        @for ($i = 0; $i < 24; $i++)
                                            <option value="{{ $i }}">{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}
                                            </option>
                                        @endfor
                                    </select>
                                    <select class="form-control" wire:model.defer="minuto">
                                        <option value="">Seleccionar minuto</option>
                                        @for ($i = 0; $i < 60; $i += 5)
                                            <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}">
                                                {{ str_pad($i, 2, '0', STR_PAD_LEFT) }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Comentario -->
                    <div class="mt-4 form-group">
                        <label class="form-label" for="comentario">COMENTARIO(OPCIONAL)</label>
                        <input type="text" id="comentario" class="form-control" wire:model.defer="comentario"
                            oninput="convertirAMayusculas()">
                    </div>
                </form>
            </div>
        </div>

        <!-- Acciones -->
        <div class="flex flex-row justify-end px-6 py-4 text-right bg-gray-100">
            <button type="submit" class="btn btn-success" wire:click="guardartodo" wire:loading.remove
                wire:target="guardartodo" style="background-color: green;">Crear</button>
            <span wire:loading wire:target="guardartodo">Guardando...</span>
        </div>
    </x-sm-modal>

</div>
</div>
