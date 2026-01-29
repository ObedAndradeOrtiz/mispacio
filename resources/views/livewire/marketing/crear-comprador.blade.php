<div>
    <label class="btn btn-success" wire:click="$set('crear',true)">NUEVO</label>
    <x-modal wire:model.defer="crear" wire:click.prevent.stop>
        <div class="px-6 py-4">
            <div class="text-lg font-medium text-gray-900">
                NUEVO COMPRADOR
            </div>
            <div class="mt-4 text-sm text-gray-600">
                <form>
                    <div class="form-group">
                        <label class="form-label" for="">NOMBRE DE COMPRADOR</label>
                        <input type="text" class="form-control" id="texto" oninput="convertirAMayusculas()"
                            wire:model.defer="nombre">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="">EMAIL</label>
                        <input type="text" class="form-control" id="texto" oninput="convertirAMayusculas()"
                            wire:model.defer="email">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="">TELEFONO</label>
                        <input type="number" class="form-control" id="texto" oninput="convertirAMayusculas()"
                            wire:model.defer="telefono">
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="">CANTIDAD</label>
                        <input type="number" class="form-control" id="texto" oninput="convertirAMayusculas()"
                            wire:model="cantidad">
                    </div>
                    <div>
                        @if (!is_null($cantidad) && $cantidad != '')
                            <h1>EL MONTO A CANCELAR ES: <strong>{{ $cantidad * 70 }} Bs.</strong></h1>
                        @else
                            <h1>Por favor ingrese una cantidad válida.</h1>
                        @endif
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="">EVENTO A ASISTIR</label>
                        <select name="" id="" wire:model="eventoseleccionado">
                            <option value="">SELECCIONAR EVENTO</option>
                            <option value="evento1">EVENTO 1</option>
                            <option value="evento2">EVENTO 2</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="">METODO DE PAGO</label>
                        <select name="" id="" wire:model="modo">
                            <option value="">SELECCIONAR METODO DE PAGO</option>
                            <option value="QR">QR</option>
                            <option value="EFECTIVO">EFECTIVO</option>
                        </select>
                    </div>

                </form>
            </div>
        </div>
        <div class="flex flex-row justify-end px-6 py-4 text-right ">
            <label type="submit" class="btn btn-success" wire:click="guardar" wire:loading.remove
                wire:target="guardartodo">Crear</label>
            <span class="" wire:loading wire:target="guardar">Guardando...</span>
        </div>
    </x-modal>
</div>
